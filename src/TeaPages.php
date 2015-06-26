<?php

namespace Takeatea\TeaThemeOptions;

use Takeatea\TeaThemeOptions\Fields\Elasticsearch\Elasticsearch;
use Takeatea\TeaThemeOptions\Fields\Network\Network;

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

/**
 * Tea Pages
 *
 * To get its own pages.
 *
 * @package Tea Theme Options
 * @subpackage Tea Pages
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 2.3.3
 *
 */
class TeaPages
{
    /**
     * @var array
     */
    protected $breadcrumb = array();

    /**
     * @var bool
     */
    protected $can_upload = false;

    /**
     * @var string
     */
    protected $capability = TTO_CAP;

    /**
     * @var array
     */
    protected $categories = array();

    /**
     * @var string
     */
    protected $current = '';

    /**
     * @var int
     */
    protected $duration = TTO_DURATION;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var string
     */
    protected $icon_small = '/assets/img/teato-tiny.svg';

    /**
     * @var string
     */
    protected $icon_big = '/assets/img/teato.svg';

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $includes = array();

    /**
     * @var null
     */
    protected $index = null;

    /**
     * @var array
     */
    protected $pages = array();

    /**
     * @var array
     */
    protected $wp_contents = array();

    /**
     * Constructor.
     *
     * @uses current_user_can()
     * @param string $identifier Define the main slug
     * @param array $options Define if we can display connections and elasticsearch pages
     *
     * @since 2.3.3
     */
    public function __construct($identifier, $options)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check identifier
        if (empty($identifier)) {
            TeaAdminMessage::__display(
                __('Something went wrong in your parameters
                definition. You need at least an identifier.', TTO_I18N)
            );
        }

        //Define parameters
        $this->identifier = $identifier;
        $this->can_upload = current_user_can('upload_files');

        //Define caps
        $capabilities = TeaThemeOptions::getConfigs('capabilities');

        if (empty($capabilities)) {
            $this->updateCapabilities(false);
        }

        //Get current page
        $this->current = isset($_REQUEST['page']) ? (string) $_REQUEST['page'] : '';

        //Define activated connection
        $isactive = TeaThemeOptions::access_token(true);
        $isdismissed = TeaThemeOptions::getConfigs('dismiss');

        //Check connection
        if (empty($isactive) && empty($isdismissed) && $options['notifications']) {
            // Show connect notice on dashboard and plugins pages
            add_action('load-index.php', array(&$this, '__getNotices'));
            add_action('load-plugins.php', array(&$this, '__getNotices'));
        }

        //Build Dashboard contents
        $this->buildDefaults($options['social'], $options['elasticsearch']);

        //Make some actions
        if (isset($_REQUEST['action']) && 'tea_action' == $_REQUEST['action']) {
            //Get the kind of action asked
            $for = isset($_REQUEST['for']) ? $_REQUEST['for'] : '';

            //Update capabilities...
            if ('caps' == $for) {
                $this->updateCapabilities();
            }
            //...Or update CPTs options...
            elseif ('cpts' == $for) {
                $this->updateCpts();
            }
            //...Or update rewrite rules...
            elseif ('rewrites' == $for) {
                $this->updateRewrites();
            }
            //...Or update options...
            elseif ('settings' == $for) {
                $this->updateOptions($_REQUEST, $_FILES);
            }
            //...Or update elasticsearch options...
            elseif ('elasticsearch' == $for) {
                $this->updateElasticsearch($_REQUEST);
            }
            //...Or update network data
            elseif ('callback' == $for || 'network' == $for) {
                $this->updateNetworks($_REQUEST);
            }
            //...Or dismiss admin notice
            elseif ('dismiss' == $for) {
                $this->updateNotice(true);
            }
            //...Or display message on dashboard
            elseif ('dashboard' == $for) {
                $this->updateNotice(true);
            }
        }

        //Add custom CSS colors ~ Earth
        wp_admin_css_color(
            'teatocss-earth',
            __('Tea T.O. ~ Earth'),
            TTO_URI.'/assets/css/teato.admin.earth.css',
            array('#222', '#303231', '#55bb3a', '#91d04d')
        );
        //Add custom CSS colors ~ Ocean
        wp_admin_css_color(
            'teatocss-ocean',
            __('Tea T.O. ~ Ocean'),
            TTO_URI.'/assets/css/teato.admin.ocean.css',
            array('#222', '#303231', '#3a80bb', '#4d9dd0')
        );
        //Add custom CSS colors ~ Vulcan
        wp_admin_css_color(
            'teatocss-vulcan',
            __('Tea T.O. ~ Vulcan'),
            TTO_URI.'/assets/css/teato.admin.vulcan.css',
            array('#222', '#303231', '#bb3a3a', '#d04d4d')
        );
        //Add custom CSS colors ~ Wind
        wp_admin_css_color(
            'teatocss-wind',
            __('Tea T.O. ~ Wind'),
            TTO_URI.'/assets/css/teato.admin.wind.css',
            array('#222', '#303231', '#69d2e7', '#a7dbd8')
        );
    }

    /**
     * Hook building scripts.
     *
     * @uses wp_enqueue_media_tto()
     * @uses wp_enqueue_script()
     *
     * @since 1.5.0-1
     */
    public function __assetScripts()
    {
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get jQuery
        $jq = array('jquery');

        //Enqueue media and colorpicker scripts
        //if (function_exists('wp_enqueue_media_tto')) {
            $this->wp_enqueue_media_tto();
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('accordion');
        //}
        //else {
            //wp_enqueue_script('media-upload');
            //wp_enqueue_script('farbtastic');
        //}

        //Enqueue all minified scripts
        wp_enqueue_script('tea-to', TTO_URI.'/assets/js/teato.min.js', $jq);
    }

    /**
     * Hook building styles.
     *
     * @uses wp_enqueue_style()
     *
     * @since 1.4.0
     */
    public function __assetStyles()
    {
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Enqueue usefull styles
        wp_enqueue_style('media-views');
        wp_enqueue_style('farbtastic');
        wp_enqueue_style('wp-color-picker');

        //Enqueue all minified styles
        wp_enqueue_style('tea-to', TTO_URI.'/assets/css/teato.min.css');
    }

    /**
     * Hook unload scripts.
     *
     * @uses wp_deregister_script()
     *
     * @param string $admin_body_class Contains the admin body class
     * @return string $class The admin body class preprended with "teato-mainwrap"
     *
     * @since 1.5.0
     */
    public function __bodyStyle($admin_body_class = '')
    {
        if (!TTO_IS_ADMIN || !isset($this->pages[$this->current])) {
            return '';
        }

        return $admin_body_class.' teato-mainwrap';
    }

    /**
     * Hook building admin bar.
     *
     * @uses add_menu()
     *
     * @since 1.5.1
     */
    public function __buildAdminBar()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if there is no problems on page definitions
        if (!isset($this->pages[$this->identifier]) || empty($this->pages)) {
            TeaAdminMessage::__display(
                __('Something went wrong in your parameters
                definition: no master page defined!', TTO_I18N)
            );
        }

        //Get the Wordpress globals
        global $wp_admin_bar;

        //Add submenu pages
        foreach ($this->pages as $page) {
            //Check the main page
            if ($this->identifier == $page['slug']) {
                //Build WP menu in admin bar
                $wp_admin_bar->add_menu(array(
                    'id' => $this->identifier,
                    'title' => $page['title'],
                    'href' => admin_url('admin.php?page='.$this->identifier)
                ));
            }

            $wp_admin_bar->add_menu(array(
                'parent' => $this->identifier,
                'id' => $this->identifier.$page['slug'],
                'href' => admin_url('admin.php?page='.$page['slug']),
                'title' => $page['name'],
                'meta' => false
            ));
        }
    }

    /**
     * Hook building menus.
     *
     * @uses add_action()
     * @uses add_menu_page()
     * @uses add_submenu_page()
     *
     * @since 1.5.2.19
     */
    public function __buildMenuPage()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            TeaAdminMessage::__display(
                __('Something went wrong in your parameters
                definition: no master page found. You can simply do that by
                using the addPage public function.', TTO_I18N)
            );
        }

        //Set the current page
        $is_page = $this->identifier == $this->current ? true : false;
        $tocheck = array($this->identifier.'_connections', $this->identifier.'_elasticsearch');

        //Menu icon
        $menuicon = TTO_URI.$this->icon_small;

        //Set icon
        $this->icon_small = TTO_PATH.'/..'.$this->icon_small;
        $this->icon_big = TTO_PATH.'/..'.$this->icon_big;

        //Add submenu pages
        foreach ($this->pages as $page) {
            //Build slug and check it
            $is_page = $page['slug'] == $this->current ? true : $is_page;
            $capability = in_array($page['slug'], $tocheck)
                ? TTO_CAP_MAX
                : $this->capability;

            //Check the main page
            if ($this->identifier == $page['slug']) {
                //Add page
                add_menu_page(
                    $page['title'],                 //page title
                    $page['title'],                 //page name
                    $this->capability,              //capability
                    $this->identifier,              //parent slug
                    array(&$this, 'buildContent'),  //display content
                    $menuicon,                      //icon
                    3                               //position
                );

                //Add first subpage
                add_submenu_page(
                    $this->identifier,              //parent slug
                    $page['title'],                 //page title
                    $page['name'],                  //page name
                    $this->capability,              //capability
                    $this->identifier,              //parent slug
                    array(&$this, 'buildContent')   //display content
                );
            }
            else {
                //Add subpage
                add_submenu_page(
                    $this->identifier,              //parent slug
                    $page['title'],                 //page title
                    $page['name'],                  //page name
                    $capability,                    //capability
                    $page['slug'],                  //menu slug
                    array(&$this, 'buildContent')   //display content
                );
            }

            //Build breadcrumb
            $this->breadcrumb[] = array(
                'title' => $page['title'],
                'slug' => $page['slug']
            );
        }

        // Load assets action hook
        add_action('admin_print_scripts', array(&$this, '__assetScripts'));
        add_action('admin_print_styles', array(&$this, '__assetStyles'));
        add_filter('admin_body_class', array(&$this, '__bodyStyle'));
    }

    /**
     * Display the admin notice.
     *
     * @since 1.4.3
     */
    public function __displayNotice()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check user capability
        if (!current_user_can(TTO_CAP_MAX)) {
            return;
        }

        //Define contents
        $connect = admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=connect');
        $dismiss = admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=dismiss');

        //Display all
        include(TTO_PATH.'/Tpl/layouts/__layout_admin_connect.tpl.php');
    }

    /**
     * Hook to display admin notice.
     *
     * @since 1.4.3
     */
    public function __getNotices()
    {
        add_action('admin_notices', array(&$this, '__displayNotice'));
    }

    /**
     * Add a page to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 1.5.1
     */
    public function addPage($configs = array(), $contents = array())
    {
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check params and if a master page already exists
        if (empty($configs)) {
            TeaAdminMessage::__display(
                __('Something went wrong in your parameters
                definition: your configs are empty. See README.md for
                more explanations.', TTO_I18N)
            );
        }
        elseif (empty($contents)) {
            TeaAdminMessage::__display(
                __('Something went wrong in your parameters
                definition: your contents are empty. See README.md for
                more explanations.', TTO_I18N)
            );
        }

        //Update capabilities
        $this->capability = TTO_CAP;

        //Define the slug
        $slug = $this->identifier.(isset($configs['slug']) ? $configs['slug'] : '');
        $name = isset($configs['name']) ? $configs['name'] : (isset($configs['title']) ? $configs['title'] : '');

        //Update the current page index
        $this->index = $slug;

        //Define page configurations
        $this->pages[$slug] = array(
            'title' => isset($configs['title']) ? $configs['title'] : __('Tea T.O.', TTO_I18N),
            'name' => !empty($name) ? $name : __('Tea T.O.', TTO_I18N),
            'position' => isset($configs['position']) ? $configs['position'] : null,
            'description' => isset($configs['description']) ? $configs['description'] : '',
            'submit' => isset($configs['submit']) ? $configs['submit'] : true,
            'slug' => $slug,
            'contents' => $contents
        );
    }

    /**
     * Register menus.
     *
     * @uses add_action()
     *
     * @since 1.4.0
     */
    public function buildPages()
    {
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            TeaAdminMessage::__display(
                __('Something went wrong in your parameters
                definition: no master page found. You can simply do that by
                using the addPage public function.', TTO_I18N)
            );
        }

        //Initialize the current index page
        $this->index = null;

        //Register admin bar action hook
        add_action('wp_before_admin_bar_render', array(&$this, '__buildAdminBar'));

        //Register admin page action hook
        add_action('admin_menu', array(&$this, '__buildMenuPage'), 999);
    }

    /**
     * Build connection content.
     *
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    protected function buildConnection($contents)
    {
        $this->includes['network'] = true;

        $field = new Network();
        $field->setCurrentPage(empty($this->current) ? $this->identifier : $this->current);
        $field->templatePages($contents);
    }

    /**
     * Build content layout.
     *
     * @since 1.5.0
     */
    public function buildContent()
    {
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get current infos
        $current = empty($this->current) ? $this->identifier : $this->current;

        //Checks contents
        if (empty($this->pages[$current]['contents'])) {
            TeaAdminMessage::__display(
                __('Something went wrong: it seems you
                forgot to attach contents to the current page.', TTO_I18N)
            );
        }

        $this->buildLayoutHeader();
        $contents = $this->pages[$current]['contents'];

        //Build contents relatively to the type (special cases: Connections)
        if ($this->identifier.'_connections' == $current) {
            $contents = 1 == count($contents) ? $contents[0] : $contents;
            $this->buildConnection($contents);
        }
        //Build contents relatively to the type (special cases: Elasticsearh)
        elseif ($this->identifier.'_elasticsearch' == $current) {
            $contents = 1 == count($contents) ? $contents[0] : $contents;
            $this->buildElasticsearch($contents);
        }
        else {
            $this->buildType($contents);
        }

        $this->buildLayoutFooter();
    }

    /**
     * Get errors.
     *
     * @return array $errors Contains all error messages
     *
     * @since 1.5.0
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Define if there are errors or not.
     *
     * @return boolean $haserrors Determine if there are errors or not
     *
     * @since 1.5.0
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * Build default contents
     *
     * @param boolean $connect Define if we can display connections page
     * @param boolean $elastic Define if we can display elasticsearch page
     * @internal param number $step Define which default pages do we need
     * @todo find a better way to integrate defaults
     *
     * @since 1.5.0
     */
    protected function buildDefaults($connect = true, $elastic = true)
    {
        include(TTO_PATH.'/Tpl/contents/__content_dashboard.tpl.php');

        // Get current user capabilities
        $canuser = current_user_can(TTO_CAP_MAX);

        //Build page with contents
        $this->addPage($titles, $details);
        $titles = $details = array();

        // Get network connections page contents
        if ($connect && $canuser) {
            include(TTO_PATH.'/Tpl/contents/__content_connections.tpl.php');
            //Build page with contents
            $this->addPage($titles, $details);
            $titles = $details = array();
        }

        // Get network connections page contents
        if ($elastic && $canuser) {
            include(TTO_PATH.'/Tpl/contents/__content_elasticsearch.tpl.php');

            //Build page with contents
            $this->addPage($titles, $details);
            $titles = $details = array();
        }
    }

    /**
     * Build elasticsearch content.
     *
     * @param array $contents Contains all data
     *
     * @since 1.5.0
     */
    protected function buildElasticsearch($contents)
    {
        if ($elasticSearch = $this->registerElasticSearch()) {
            $elasticSearch->templatePages($contents);
        }
    }

    /**
     * Build header layout.
     *
     * @since 1.5.2.1
     */
    protected function buildLayoutHeader()
    {
        //Get all pages with link, icon and slug
        $identifier = $this->identifier;
        $links = $this->breadcrumb;
        $icon = $this->icon_big;
        $page = empty($this->current) ? $identifier : $this->current;

        //Works on params
        $updated =
            isset($_REQUEST['action']) && 'tea_action' == $_REQUEST['action']
            && isset($_REQUEST['for']) && 'settings' == $_REQUEST['for']
                ? true
                : false;
        $dashboard =
            isset($_REQUEST['action']) && 'tea_action' == $_REQUEST['action']
            && isset($_REQUEST['for']) && 'dashboard' == $_REQUEST['for']
                ? true
                : false;

        //Works on title
        $title = empty($this->current) ?
            $this->pages[$this->identifier]['title'] :
            $this->pages[$this->current]['title'];
        $title = empty($title) ? __('Tea Theme Options', TTO_I18N) : $title;

        //Works on description
        $description = empty($this->current) ?
            $this->pages[$this->identifier]['description'] :
            $this->pages[$this->current]['description'];

        //Works on submit button
        $submit = empty($this->current) ?
            $this->pages[$this->identifier]['submit'] :
            $this->pages[$this->current]['submit'];

        //Include template
        include(TTO_PATH.'/Tpl/layouts/__layout_header.tpl.php');
    }

    /**
     * Build footer layout.
     *
     * @since 2.3.3
     */
    protected function buildLayoutFooter()
    {
        //Get all pages with submit button
        $submit = empty($this->current) ?
            $this->pages[$this->identifier]['submit'] :
            $this->pages[$this->current]['submit'];

        //Display version
        $version = TTO_VERSION;

        //Display pages
        $capurl = current_user_can(TTO_CAP_MAX)
            ? admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=caps')
            : '';
        $cpturl = admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=cpts');
        $rwturl = admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=rewrites');

        //Include template
        include(TTO_PATH.'/Tpl/layouts/__layout_footer.tpl.php');
    }

    /**
     * Build each type content.
     *
     * @param array $contents Contains all data
     * @todo try to include CUSTOM fields outside the Tea T.O.
     *
     * @since 1.5.0
     */
    protected function buildType($contents)
    {
        //Get all default fields in the Tea T.O. package
        $unauthorized = TeaFields::getDefaults('unauthorized');
        $specials = in_array($this->current, array($this->identifier, $this->identifier.'_connections', $this->identifier.'_elasticsearch'));

        //Iteration on all array
        foreach ($contents as $key => $content) {
            //Get type
            $type = $content['type'];

            //Check if the asked field is unknown
            if (in_array($type, $unauthorized) && !$specials) {
                TeaAdminMessage::__display(
                    sprintf(__('Something went wrong in your
                    parameters definition with the id %s:
                    the defined type is unknown!', TTO_I18N), $content['id'])
                );
                continue;
            }

            //Set vars
            $class = ucfirst($type);
            $class = "\Takeatea\TeaThemeOptions\Fields\\$class\\$class";

            //Include class field
            if (!isset($this->includes[$type])) {
                //Check if the class file exists
                if (!class_exists($class)) {
                    TeaAdminMessage::__display(
                        sprintf(__('Something went wrong in
                        your parameters definition: the class %s
                        does not exist!', TTO_I18N), $class)
                    );
                    continue;
                }

                //Set the include
                $this->includes[$type] = true;
            }

            /** @var $field TeaFields */
            $field = new $class();
            try {
                $field->templatePages($content);
            }
            catch (TeaThemeException $e){
                $this->errors[] = $e->getMessage();
            }
        }
    }

    /**
     * Register uniq option into transient.
     *
     * @uses get_cat_name()
     * @uses get_categories()
     * @uses get_category()
     * @uses get_category_feed_link()
     * @uses get_category_link()
     * @param string $key The name of the transient
     * @param array $value The default value if no one is found
     * @param array $dependency The default value if no one is found
     * @todo find a better way for the plugin version
     *
     * @since 1.5.2
     */
    protected function setOption($key, $value, $dependency = array())
    {
        //Check the category
        if (empty($key)) {
            TeaAdminMessage::__display(
                sprintf(__('Something went wrong. Key "%s"
                and/or its value is empty.', TTO_I18N), $key)
            );
        }

        //Check the key for special "NONE" value
        $value = 'NONE' == $value ? '' : $value;

        //Set the option
        TeaThemeOptions::set_option($key, $value, $this->duration);

        //Special usecase: category. We can also register information
        //as title, slug, description and children
        if (false !== strpos($key, '__category')) {
            //Make the value as an array
            $value = !is_array($value) ? array($value) : $value;

            //All contents
            $details = array();

            //Iterate on categories
            foreach ($value as $c) {
                //Get all children
                $cats = get_categories(array(
                    'child_of' => $c,
                    'hide_empty' => 0
                ));
                $children = array();

                //Iterate on children to get ID only
                foreach ($cats as $ca) {
                    $children[$ca->cat_ID] = array(
                        'id' => $ca->cat_ID,
                        'name' => get_cat_name($ca->cat_ID),
                        'link' => get_category_link($ca->cat_ID),
                        'feed' => get_category_feed_link($ca->cat_ID),
                        'children' => array()
                    );
                }

                //Get all details
                $category = get_category($c);

                //Build details with extra options
                $details[$c] = array(
                    'id' => $category->term_id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'slug' => get_category_link($c),
                    'feed' => get_category_feed_link($c),
                    'children' => $children
                );
            }

            //Set the other parameters: details as children
            TeaThemeOptions::set_option($key.'_details', $details, $this->duration);
        }
        //Special usecase: checkboxes. When it's not checked, no data is sent
        //through the $_POST array
        elseif (false !== strpos($key, '__checkbox') && !empty($dependency)) {
            //Get the key
            $previous = str_replace('__checkbox', '', $key);

            //Check if it exists (= unchecked) and set the option
            if (!isset($dependency[$previous])) {
                TeaThemeOptions::set_option($previous, $value, $this->duration);
            }
        }
    }

    /**
     * Create roles and capabilities.
     *
     * @param boolean $redirect Define if the TTO has to make a redirect
     *
     * @since 1.5.2.1
     */
    protected function updateCapabilities($redirect = true)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get global WP roles
        global $wp_roles;

        //Check them
        if (class_exists('WP_Roles')) {
            $wp_roles = !isset($wp_roles) ? new WP_Roles() : $wp_roles;
        }

        if (!is_object($wp_roles)) {
            return;
        }

        //Add custom role
        $wp_roles->add_cap('administrator', TTO_CAP_MAX);

        //Update DB
        TeaThemeOptions::setConfigs('capabilities', true);

        //Redirect to Tea TO homepage
        if ($redirect) {
            wp_safe_redirect(admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=dashboard'));
        }
    }

    /**
     * Update CPTs options.
     *
     * @param boolean $redirect Define if the TTO has to make a redirect
     *
     * @since 1.5.2.1
     */
    protected function updateCpts($redirect = true)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get all registered pages
        $cpts = TeaThemeOptions::getConfigs('customposttypes');

        //Check params and if a master page already exists
        if (empty($cpts)) {
            return;
        }

        //Delete cpts configurations to built them back
        TeaThemeOptions::setConfigs('customposttypes', array());

        //Redirect to Tea TO homepage
        if ($redirect) {
            wp_safe_redirect(admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=dashboard'));
        }
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_POST
     *
     * @since 1.4.0
     */
    protected function updateElasticsearch($request)
    {
        if ($elasticSearch = $this->registerElasticSearch()) {
            $elasticSearch->actionElasticsearch($request);
        }
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_POST
     *
     * @since 1.5.0
     */
    protected function updateNetworks($request)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get action
        $for = isset($request['for']) ? $request['for'] : '';

        //Check if a network connection is asked
        if ('callback' != $for && 'network' != $for) {
            TeaAdminMessage::__display(
                __('Something went wrong in your parameters
                definition. You need to specify a network to make the
                connection happens.', TTO_I18N)
            );
        }

        //Defaults variables
        $page = empty($this->current) ? $this->identifier : $this->current;

        //Include class field
        if (!isset($this->includes['network'])) {
            //Set the include
            $this->includes['network'] = true;
        }

        //Make the magic
        $field = new Network();
        $field->setCurrentPage($page);
        $field->actionNetwork($request);
    }

    /**
     * Hide or unhide notice admin message.
     *
     * @param boolean $dismiss Define if we have to hide or unhide notice
     *
     * @since 1.4.3
     */
    protected function updateNotice($dismiss)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Set default
        TeaThemeOptions::setConfigs('dismiss', $dismiss);
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_REQUEST
     * @param array $files Contains all data in $_FILES
     *
     * @since 1.4.3.2
     */
    protected function updateOptions($request, $files)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Set all options in transient
        foreach ($request as $k => $v) {
            //Don't register this default value
            if (in_array($k, array('action', 'for', 'updated', 'submit'))) {
                continue;
            }

            //Special usecase: checkboxes. When it's not checked,
            //no data is sent through the $_POST array
            $p = false !== strpos($k, '__checkbox') ? $request : array();

            //Register option and transient
            $this->setOption($k, $v, $p);
        }

        //Check if files are attempting to be uploaded
        if (!empty($files)) {
            //Get required files
            require_once(ABSPATH.'wp-admin/includes/image.php');
            require_once(ABSPATH.'wp-admin/includes/file.php');
            require_once(ABSPATH.'wp-admin/includes/media.php');

            //Set all URL in transient
            foreach ($files as $k => $v) {
                //Don't do nothing if no file is defined
                if (empty($v['tmp_name'])) {
                    continue;
                }

                //Do the magic
                $file = wp_handle_upload($v, array('test_form' => false));

                //Register option and transient
                $this->setOption($k, $file['url']);
            }
        }
    }

    /**
     * Update rewrite rules options.
     *
     * @param boolean $redirect Define if the TTO has to make a redirect
     *
     * @since 2.3.3
     */
    protected function updateRewrites($redirect = true)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Flush all rewrite rules
        flush_rewrite_rules();

        //Redirect to Tea TO homepage
        if ($redirect) {
            wp_safe_redirect(admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=dashboard'));
        }
    }

    /**
     * @return Elasticsearch|void
     */
    protected function registerElasticSearch()
    {
        $page = empty($this->current) ? $this->identifier : $this->current;
        $this->includes['elasticsearch'] = true;

        $field = new Elasticsearch();
        $field->setCurrentPage($page);

        return $field;
    }












    /**
     * Update rewrite rules options.
     *
     * @param array $args Define if the TTO has to make a redirect
     *
     * @since 2.3.3
     */
    protected function wp_enqueue_media_tto($args = array())
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Enqueue me just once per page, please.
        if (did_action('wp_enqueue_media_tto')) {
            return;
        }

        global $content_width, $wpdb, $wp_locale;

        $defaults = array(
            'post' => null,
        );
        $args = wp_parse_args($args, $defaults);

        //We're going to pass the old thickbox media tabs to `media_upload_tabs`
        //to ensure plugins will work. We will then unset those tabs.
        $tabs = array(
            //handler action suffix => tab label
            'type'     => '',
            'type_url' => '',
            'gallery'  => '',
            'library'  => '',
        );

        /** This filter is documented in wp-admin/includes/media.php */
        $tabs = apply_filters('media_upload_tabs', $tabs);
        unset($tabs['type'], $tabs['type_url'], $tabs['gallery'], $tabs['library']);

        $props = array(
            'link'  => get_option('image_default_link_type'), //db default is 'file'
            'align' => get_option('image_default_align'), //empty default
            'size'  => get_option('image_default_size'),  //empty default
        );

        $exts = array_merge(wp_get_audio_extensions(), wp_get_video_extensions());
        $mimes = get_allowed_mime_types();
        $ext_mimes = array();
        foreach ($exts as $ext) {
            foreach ($mimes as $ext_preg => $mime_match) {
                if (preg_match('#' . $ext . '#i', $ext_preg)) {
                    $ext_mimes[ $ext ] = $mime_match;
                    break;
                }
            }
        }

        if ( false === ( $has_audio = get_transient( 'has_audio' ) ) ) {
                $has_audio = (bool) $wpdb->get_var( "
                        SELECT ID
                        FROM $wpdb->posts
                        WHERE post_type = 'attachment'
                        AND post_mime_type LIKE 'audio%'
                        LIMIT 1
                " );
                set_transient( 'has_audio', $has_audio );
        }
        if ( false === ( $has_video = get_transient( 'has_video' ) ) ) {
                $has_video = (bool) $wpdb->get_var( "
                        SELECT ID
                        FROM $wpdb->posts
                        WHERE post_type = 'attachment'
                        AND post_mime_type LIKE 'video%'
                        LIMIT 1
                " );
                set_transient( 'has_video', $has_video );
        }
        $months = $wpdb->get_results($wpdb->prepare("
            SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month
            FROM $wpdb->posts
            WHERE post_type = %s
            ORDER BY post_date DESC
        ", 'attachment'));
        foreach ($months as $month_year) {
            $month_year->text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($month_year->month), $month_year->year);
        }

        $settings = array(
            'tabs'      => $tabs,
            'tabUrl'    => add_query_arg(array('chromeless' => true), admin_url('media-upload.php')),
            'mimeTypes' => wp_list_pluck(get_post_mime_types(), 0),
            /** This filter is documented in wp-admin/includes/media.php */
            'captions'  => ! apply_filters('disable_captions', ''),
            'nonce'     => array(
                'sendToEditor' => wp_create_nonce('media-send-to-editor'),
           ),
            'post'    => array(
                'id' => 0,
           ),
            'defaultProps' => $props,
            'attachmentCounts' => array(
                'audio' => ($has_audio) ? 1 : 0,
                'video' => ($has_video) ? 1 : 0
           ),
            'embedExts'    => $exts,
            'embedMimes'   => $ext_mimes,
            'contentWidth' => $content_width,
            'months'       => $months,
            'mediaTrash'   => MEDIA_TRASH ? 1 : 0
        );

        $post = null;
        if (isset($args['post'])) {
            $post = get_post($args['post']);
            $settings['post'] = array(
                'id' => $post->ID,
                'nonce' => wp_create_nonce('update-post_' . $post->ID),
           );

            $thumbnail_support = current_theme_supports('post-thumbnails', $post->post_type) && post_type_supports($post->post_type, 'thumbnail');
            if (! $thumbnail_support && 'attachment' === $post->post_type && $post->post_mime_type) {
                if (wp_attachment_is('audio', $post)) {
                    $thumbnail_support = post_type_supports('attachment:audio', 'thumbnail') || current_theme_supports('post-thumbnails', 'attachment:audio');
                } elseif (wp_attachment_is('video', $post)) {
                    $thumbnail_support = post_type_supports('attachment:video', 'thumbnail') || current_theme_supports('post-thumbnails', 'attachment:video');
                }
            }

            if ($thumbnail_support) {
                $featured_image_id = get_post_meta($post->ID, '_thumbnail_id', true);
                $settings['post']['featuredImageId'] = $featured_image_id ? $featured_image_id : -1;
            }
        }

        $hier = $post && is_post_type_hierarchical($post->post_type);

        $strings = array(
            //Generic
            'url'         => __('URL'),
            'addMedia'    => __('Add Media'),
            'search'      => __('Search'),
            'select'      => __('Select'),
            'cancel'      => __('Cancel'),
            'update'      => __('Update'),
            'replace'     => __('Replace'),
            'remove'      => __('Remove'),
            'back'        => __('Back'),
            /* translators: This is a would-be plural string used in the media manager.
               If there is not a word you can use in your language to avoid issues with the
               lack of plural support here, turn it into "selected: %d" then translate it.
             */
            'selected'    => __('%d selected'),
            'dragInfo'    => __('Drag and drop to reorder media files.'),

            //Upload
            'uploadFilesTitle'  => __('Upload Files'),
            'uploadImagesTitle' => __('Upload Images'),

            //Library
            'mediaLibraryTitle'      => __('Media Library'),
            'insertMediaTitle'       => __('Insert Media'),
            'createNewGallery'       => __('Create a new gallery'),
            'createNewPlaylist'      => __('Create a new playlist'),
            'createNewVideoPlaylist' => __('Create a new video playlist'),
            'returnToLibrary'        => __('&#8592; Return to library'),
            'allMediaItems'          => __('All media items'),
            'allDates'               => __('All dates'),
            'noItemsFound'           => __('No items found.'),
            'insertIntoPost'         => $hier ? __('Insert into page') : __('Insert into post'),
            'unattached'             => __('Unattached'),
            'trash'                  => _x('Trash', 'noun'),
            'uploadedToThisPost'     => $hier ? __('Uploaded to this page') : __('Uploaded to this post'),
            'warnDelete'             => __("You are about to permanently delete this item.\n  'Cancel' to stop, 'OK' to delete."),
            'warnBulkDelete'         => __("You are about to permanently delete these items.\n  'Cancel' to stop, 'OK' to delete."),
            'warnBulkTrash'          => __("You are about to trash these items.\n  'Cancel' to stop, 'OK' to delete."),
            'bulkSelect'             => __('Bulk Select'),
            'cancelSelection'        => __('Cancel Selection'),
            'trashSelected'          => __('Trash Selected'),
            'untrashSelected'        => __('Untrash Selected'),
            'deleteSelected'         => __('Delete Selected'),
            'deletePermanently'      => __('Delete Permanently'),
            'apply'                  => __('Apply'),
            'filterByDate'           => __('Filter by date'),
            'filterByType'           => __('Filter by type'),
            'searchMediaLabel'       => __('Search Media'),
            'noMedia'                => __('No media attachments found.'),

            //Library Details
            'attachmentDetails'  => __('Attachment Details'),

            //From URL
            'insertFromUrlTitle' => __('Insert from URL'),

            //Featured Images
            'setFeaturedImageTitle' => __('Set Featured Image'),
            'setFeaturedImage'    => __('Set featured image'),

            //Gallery
            'createGalleryTitle' => __('Create Gallery'),
            'editGalleryTitle'   => __('Edit Gallery'),
            'cancelGalleryTitle' => __('&#8592; Cancel Gallery'),
            'insertGallery'      => __('Insert gallery'),
            'updateGallery'      => __('Update gallery'),
            'addToGallery'       => __('Add to gallery'),
            'addToGalleryTitle'  => __('Add to Gallery'),
            'reverseOrder'       => __('Reverse order'),

            //Edit Image
            'imageDetailsTitle'     => __('Image Details'),
            'imageReplaceTitle'     => __('Replace Image'),
            'imageDetailsCancel'    => __('Cancel Edit'),
            'editImage'             => __('Edit Image'),

            //Crop Image
            'chooseImage' => __('Choose Image'),
            'selectAndCrop' => __('Select and Crop'),
            'skipCropping' => __('Skip Cropping'),
            'cropImage' => __('Crop Image'),
            'cropYourImage' => __('Crop your image'),
            'cropping' => __('Cropping&hellip;'),
            'suggestedDimensions' => __('Suggested image dimensions:'),
            'cropError' => __('There has been an error cropping your image.'),

            //Edit Audio
            'audioDetailsTitle'     => __('Audio Details'),
            'audioReplaceTitle'     => __('Replace Audio'),
            'audioAddSourceTitle'   => __('Add Audio Source'),
            'audioDetailsCancel'    => __('Cancel Edit'),

            //Edit Video
            'videoDetailsTitle'     => __('Video Details'),
            'videoReplaceTitle'     => __('Replace Video'),
            'videoAddSourceTitle'   => __('Add Video Source'),
            'videoDetailsCancel'    => __('Cancel Edit'),
            'videoSelectPosterImageTitle' => __('Select Poster Image'),
            'videoAddTrackTitle'    => __('Add Subtitles'),

            //Playlist
            'playlistDragInfo'    => __('Drag and drop to reorder tracks.'),
            'createPlaylistTitle' => __('Create Audio Playlist'),
            'editPlaylistTitle'   => __('Edit Audio Playlist'),
            'cancelPlaylistTitle' => __('&#8592; Cancel Audio Playlist'),
            'insertPlaylist'      => __('Insert audio playlist'),
            'updatePlaylist'      => __('Update audio playlist'),
            'addToPlaylist'       => __('Add to audio playlist'),
            'addToPlaylistTitle'  => __('Add to Audio Playlist'),

            //Video Playlist
            'videoPlaylistDragInfo'    => __('Drag and drop to reorder videos.'),
            'createVideoPlaylistTitle' => __('Create Video Playlist'),
            'editVideoPlaylistTitle'   => __('Edit Video Playlist'),
            'cancelVideoPlaylistTitle' => __('&#8592; Cancel Video Playlist'),
            'insertVideoPlaylist'      => __('Insert video playlist'),
            'updateVideoPlaylist'      => __('Update video playlist'),
            'addToVideoPlaylist'       => __('Add to video playlist'),
            'addToVideoPlaylistTitle'  => __('Add to Video Playlist'),
        );

        /**
         * Filter the media view settings.
         *
         * @since 3.5.0
         *
         * @param array   $settings List of media view settings.
         * @param WP_Post $post     Post object.
         */
        $settings = apply_filters('media_view_settings', $settings, $post);

        /**
         * Filter the media view strings.
         *
         * @since 3.5.0
         *
         * @param array   $strings List of media view strings.
         * @param WP_Post $post    Post object.
         */
        $strings = apply_filters('media_view_strings', $strings,  $post);

        $strings['settings'] = $settings;

        //Ensure we enqueue media-editor first, that way media-views is
        //registered internally before we try to localize it. see #24724.
        wp_enqueue_script('media-editor');
        wp_localize_script('media-views', '_wpMediaViewsL10n', $strings);

        wp_enqueue_script('media-audiovideo');
        wp_enqueue_style('media-views');
        if (is_admin()) {
            wp_enqueue_script('mce-view');
            wp_enqueue_script('image-edit');
        }
        wp_enqueue_style('imgareaselect');
        wp_plupload_default_settings();

        require_once ABSPATH . WPINC . '/media-template.php';
        add_action('admin_footer', 'wp_print_media_templates');
        add_action('wp_footer', 'wp_print_media_templates');
        add_action('customize_controls_print_footer_scripts', 'wp_print_media_templates');

        /**
         * Fires at the conclusion of wp_enqueue_media_tto().
         *
         * @since 3.5.0
         */
        do_action('wp_enqueue_media_tto');
    }
}
