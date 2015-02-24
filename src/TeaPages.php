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
 * @since 1.5.2.19
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
     * @since 1.5.2.8
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

        // Define parameters
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
        if (empty($isactive) && empty($isdismissed) && $options['notifs']) {
            // Show connect notice on dashboard and plugins pages
            add_action('load-index.php', array(&$this, '__getNotices'));
            add_action('load-plugins.php', array(&$this, '__getNotices'));
        }

        //Build Dashboard contents
        $this->buildDefaults($options['connect'], $options['elastic']);

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
            TTO_URI . '/assets/css/teato.admin.earth.css',
            array('#222', '#303231', '#55bb3a', '#91d04d')
        );
        //Add custom CSS colors ~ Ocean
        wp_admin_css_color(
            'teatocss-ocean',
            __('Tea T.O. ~ Ocean'),
            TTO_URI . '/assets/css/teato.admin.ocean.css',
            array('#222', '#303231', '#3a80bb', '#4d9dd0')
        );
        //Add custom CSS colors ~ Vulcan
        wp_admin_css_color(
            'teatocss-vulcan',
            __('Tea T.O. ~ Vulcan'),
            TTO_URI . '/assets/css/teato.admin.vulcan.css',
            array('#222', '#303231', '#bb3a3a', '#d04d4d')
        );
        //Add custom CSS colors ~ Wind
        wp_admin_css_color(
            'teatocss-wind',
            __('Tea T.O. ~ Wind'),
            TTO_URI . '/assets/css/teato.admin.wind.css',
            array('#222', '#303231', '#69d2e7', '#a7dbd8')
        );
    }

    /**
     * Hook building scripts.
     *
     * @uses wp_enqueue_media()
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
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('accordion');
        }
        else {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('farbtastic');
        }

        //Enqueue all minified scripts
        wp_enqueue_script('tea-to', TTO_URI . '/assets/js/teato.min.js', $jq);
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
        wp_enqueue_style('tea-to', TTO_URI . '/assets/css/teato.min.css');
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
                    'href' => admin_url('admin.php?page=' . $this->identifier)
                ));
            }

            $wp_admin_bar->add_menu(array(
                'parent' => $this->identifier,
                'id' => $this->identifier . $page['slug'],
                'href' => admin_url('admin.php?page=' . $page['slug']),
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
        $menuicon = TTO_URI . $this->icon_small;

        //Set icon
        $this->icon_small = TTO_PATH . '/..' . $this->icon_small;
        $this->icon_big = TTO_PATH . '/..' . $this->icon_big;

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
        $slug = $this->identifier . (isset($configs['slug']) ? $configs['slug'] : '');
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
        if ($this->identifier . '_connections' == $current) {
            $contents = 1 == count($contents) ? $contents[0] : $contents;
            $this->buildConnection($contents);
        }
        //Build contents relatively to the type (special cases: Elasticsearh)
        elseif ($this->identifier . '_elasticsearch' == $current) {
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
     * @since 1.5.2.1
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
            TeaThemeOptions::set_option($key . '_details', $details, $this->duration);
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
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

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
}
