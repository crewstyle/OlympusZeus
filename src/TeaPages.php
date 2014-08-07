<?php
namespace Takeatea\TeaThemeOptions;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\Fields\Elasticsearch\Elasticsearch;
use Takeatea\TeaThemeOptions\Fields\Network\Network;

/**
 * TEA PAGES
 * 
 * Copyright (C) 2014, Achraf Chouk - ach@takeatea.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * Tea Pages
 *
 * To get its own pages.
 *
 * @package Tea Theme Options
 * @subpackage Tea Pages
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 1.4.0
 *
 */
class TeaPages
{
    //Define protected vars
    protected $adminmessage;
    protected $breadcrumb = array();
    protected $can_upload = false;
    protected $capability = TTO_CAP;
    protected $caps = false;
    protected $categories = array();
    protected $current = '';
    protected $duration = TTO_DURATION;
    protected $icon_small = '/assets/img/teato-tiny.svg';
    protected $icon_big = '/assets/img/teato.svg';
    protected $identifier;
    protected $includes = array();
    protected $index = null;
    protected $is_admin;
    protected $pages = array();
    protected $wp_contents = array();

    /**
     * Constructor.
     *
     * @uses current_user_can()
     * @param string $identifier Define the main slug
     * @param boolean $connect Define if we can display connections page
     * @param boolean $elastic Define if we can display elasticsearch page
     *
     * @since 1.4.3
     */
    public function __construct($identifier, $connect = true, $elastic = true)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check identifier
        if (empty($identifier)) {
            $this->adminmessage = __('Something went wrong in your parameters 
                definition. You need at least an identifier.', TTO_I18N);
            return;
        }

        //Define parameters
        $this->can_upload = current_user_can('upload_files');
        $this->identifier = $identifier;
        $this->caps = TeaThemeOptions::get_option('tea_capabilities', false);

        //Set capabilities if needed
        if (!$this->caps) {
            $this->updateCapabilities(false);
        }

        //Set default duration
        $this->setDuration();

        //Get current page
        $this->current = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

        //Define activated connection
        $isactive = TeaThemeOptions::getAccessToken(true);
        $isdismissed = TeaThemeOptions::get_option('tea_dismiss', false);

        //Check connection
        if (empty($isactive) && !$isdismissed) {
            // Show connect notice on dashboard and plugins pages
            add_action('load-index.php', array(&$this, '__getNotices'));
            add_action('load-plugins.php', array(&$this, '__getNotices'));
        }

        //Build Dashboard contents
        $this->buildDefaults($connect, $elastic);

        //Make some actions
        if (isset($_REQUEST['action']) && 'tea_action' == $_REQUEST['action']) {
            //Get the kind of action asked
            $for = isset($_REQUEST['for']) ? $_REQUEST['for'] : '';

            //Update capabilities...
            if ('caps' == $for) {
                $this->updateCapabilities();
            }
            //...Or update options...
            else if ('settings' == $for) {
                $this->updateOptions($_REQUEST, $_FILES);
            }
            //...Or update elasticsearch options...
            else if ('elasticsearch' == $for) {
                $this->updateElasticsearch($_REQUEST);
            }
            //...Or update network data
            else if ('callback' == $for || 'network' == $for) {
                $this->updateNetworks($_REQUEST);
            }
            //...Or dismiss admin notice
            else if ('dismiss' == $for) {
                $this->updateNotice(true);
            }
        }

        //Add custom CSS colors
        wp_admin_css_color(
            'teatocss',
            __('Tea T.O.'),
            TTO_URI . '/assets/css/teato.admin.css',
            array('#222', '#303231', '#55bb3a', '#91d04d')
        );
    }


    //------------------------------------------------------------------------//

    /**
     * WORDPRESS USED HOOKS
     **/

    /**
     * Hook building scripts.
     *
     * @uses wp_enqueue_media()
     * @uses wp_enqueue_script()
     *
     * @since 1.4.0
     */
    public function __assetScripts()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we are in TeaTO context
        /*if (!isset($this->pages[$this->current])) {
            return;
        }*/

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
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we are in TeaTO context
        /*if (!isset($this->pages[$this->current])) {
            return;
        }*/

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
     * @since 1.4.0
     */
    public function __assetUnloaded()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //wp_deregister_script('media-models');
    }

    /**
     * Hook unload scripts.
     *
     * @uses wp_deregister_script()
     *
     * @since 1.4.0
     */
    public function __bodyStyle($admin_body_class = '')
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we are in TeaTO context
        if (!isset($this->pages[$this->current])) {
            return;
        }

        return $admin_body_class . ' teato-mainwrap';
    }

    /**
     * Hook building admin bar.
     *
     * @uses add_menu()
     *
     * @since 1.4.0
     */
    public function __buildAdminBar()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if there is no problems on page definitions
        if (!isset($this->pages[$this->identifier]) || empty($this->pages)) {
            $this->adminmessage = __('Something went wrong in your parameters 
                definition: no master page defined!', TTO_I18N);
            return;
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
            else {
                //Build the subpages
                $wp_admin_bar->add_menu(array(
                    'parent' => $this->identifier,
                    'id' => $this->getSlug($page['slug']),
                    'href' => admin_url('admin.php?page=' . $page['slug']),
                    'title' => $page['title'],
                    'meta' => false
                ));
            }
        }
    }

    /**
     * Hook building menus.
     *
     * @uses add_action()
     * @uses add_menu_page()
     * @uses add_submenu_page()
     *
     * @since 1.4.0
     */
    public function __buildMenuPage()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            $this->adminmessage = __('Something went wrong in your parameters 
                definition: no master page found. You can simply do that by 
                using the addPage public function.', TTO_I18N);
            return;
        }

        //Set the current page
        $is_page = $this->identifier == $this->current ? true : false;

        //Set icon
        $this->icon_small = TTO_URI . $this->icon_small;
        $this->icon_big = TTO_URI . $this->icon_big;

        //Add submenu pages
        foreach ($this->pages as $page) {
            //Build slug and check it
            $is_page = $page['slug'] == $this->current ? true : $is_page;
            $capability = in_array($page['slug'], array($this->identifier.'_connections', $this->identifier.'_elasticsearch')) 
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
                    $this->icon_small               //icon
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
                'title' => $page['name'],
                'slug' => $page['slug']
            );
        }

        //Unload unwanted assets
        if (!empty($this->current) && $is_page) {
            add_action('admin_head', array(&$this, '__assetUnloaded'));
        }

        //Load assets action hook
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
        include(TTO_PATH . '/Tpl/layouts/__layout_admin_connect.tpl.php');
    }

    /**
     * Hook to display admin notice.
     *
     * @since 1.4.3
     */
    public function __getNotices()
    {
        //Make the magic
        add_action('admin_notices', array(&$this, '__displayNotice'));
    }

    /**
     * Display a warning message on the admin panel.
     *
     * @since 1.4.0
     */
    public function __showAdminMessage()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get content
        $content = $this->adminmessage;

        //Get template and display content
        if (!empty($content)) {
            include(TTO_PATH . '/Tpl/layouts/__layout_admin_message.tpl.php');
        }
    }


    //------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Add a page to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    public function addPage($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check params and if a master page already exists
        if (empty($configs)) {
            $this->adminmessage = __('Something went wrong in your parameters 
                definition: your <b>configs</b> are empty. See README.md for 
                more explanations.', TTO_I18N);
            return;
        }
        else if (empty($contents)) {
            $this->adminmessage = __('Something went wrong in your parameters 
                definition: your <b>contents</b> are empty. See README.md for 
                more explanations.', TTO_I18N);
            return;
        }

        //Update capabilities
        $this->capability = TTO_CAP;

        //Define the slug
        $slug = isset($configs['slug']) ? $this->getSlug($configs['slug']) : $this->getSlug();

        //Update the current page index
        $this->index = $slug;

        //Define page configurations
        $this->pages[$slug] = array(
            'title' => isset($configs['title']) ? $configs['title'] : __('Tea T.O.', TTO_I18N),
            'name' => isset($configs['name']) ? $configs['name'] : __('Tea T.O.', TTO_I18N),
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
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            $this->adminmessage = __('Something went wrong in your parameters 
                definition: no master page found. You can simply do that by 
                using the addPage public function.', TTO_I18N);
            return;
        }

        //Initialize the current index page
        $this->index = null;

        //Register admin bar action hook
        add_action('wp_before_admin_bar_render', array(&$this, '__buildAdminBar'));

        //Register admin page action hook
        add_action('admin_menu', array(&$this, '__buildMenuPage'));

        //Register admin message action hook
        add_action('admin_notices', array(&$this, '__showAdminMessage'));
    }


    //------------------------------------------------------------------------//

    /**
     * Build connection content.
     *
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    protected function buildConnection($contents)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Default variables
        $page = empty($this->current) ? $this->identifier : $this->current;
        $includes = $this->getIncludes();

        //Include class field
        if (!isset($includes['network'])) {
            //Set the include
            $this->setIncludes('network');
        }

        //Make the magic
        $field = new Network();
        $field->setCurrentPage($page);
        $field->templatePages($contents);
    }

    /**
     * Build content layout.
     *
     * @since 1.4.0
     */
    public function buildContent()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get current infos
        $current = empty($this->current) ? $this->identifier : $this->current;

        //Checks contents
        if (empty($this->pages[$current]['contents'])) {
            $this->adminmessage = __('Something went wrong: it seems you 
                forgot to attach contents to the current page.', TTO_I18N);
            return;
        }

        //Build header
        $this->buildLayoutHeader();

        //Get contents
        $title = $this->pages[$current]['title'];
        $contents = $this->pages[$current]['contents'];

        //Build contents relatively to the type (special cases: Connections page)
        if ($this->identifier . '_connections' == $current) {
            $contents = 1 == count($contents) ? $contents[0] : $contents;
            $this->buildConnection($contents);
        }
        //Build contents relatively to the type (special cases: Elasticsearh page)
        else if ($this->identifier . '_elasticsearch' == $current) {
            $contents = 1 == count($contents) ? $contents[0] : $contents;
            $this->buildElasticsearch($contents);
        }
        else {
            $this->buildType($contents);
        }

        //Build footer
        $this->buildLayoutFooter();
    }

    /**
     * Build default contents
     *
     * @param number $step Define which default pages do we need
     * @param boolean $connect Define if we can display connections page
     * @param boolean $elastic Define if we can display elasticsearch page
     * @todo find a better way to integrate defaults
     *
     * @since 1.4.0
     */
    protected function buildDefaults($connect = true, $elastic = true)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get dashboard page contents
        include(TTO_PATH . '/Tpl/contents/__content_dashboard.tpl.php');

        //Get current user capabilities
        $canuser = current_user_can(TTO_CAP_MAX);

        //Build page with contents
        $this->addPage($titles, $details);
        unset($titles, $details);

        //Get network connections page contents
        if ($connect && $canuser) {
            include(TTO_PATH . '/Tpl/contents/__content_connections.tpl.php');

            //Build page with contents
            $this->addPage($titles, $details);
            unset($titles, $details);
        }

        //Get network connections page contents
        if ($elastic && $canuser) {
            include(TTO_PATH . '/Tpl/contents/__content_elasticsearch.tpl.php');

            //Build page with contents
            $this->addPage($titles, $details);
            unset($titles, $details);
        }
    }

    /**
     * Build elasticsearch content.
     *
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    protected function buildElasticsearch($contents)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Default variables
        $page = empty($this->current) ? $this->identifier : $this->current;
        $includes = $this->getIncludes();

        //Include class field
        if (!isset($includes['elasticsearch'])) {
            $this->setIncludes('elasticsearch');
        }

        //Make the magic
        $field = new Elasticsearch();
        $field->setCurrentPage($page);
        $field->templatePages($contents);
    }

    /**
     * Build header layout.
     *
     * @since 1.4.3
     */
    protected function buildLayoutHeader()
    {
        //Get all pages with link, icon and slug
        $links = $this->breadcrumb;
        $icon = $this->icon_big;
        $page = empty($this->current) ? $this->identifier : $this->current;

        //Works on params
        $updated = isset($_REQUEST['action']) && 'tea_action' == $_REQUEST['action'] && isset($_REQUEST['for']) && 'settings' == $_REQUEST['for'] ? true : false;

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
        include(TTO_PATH . '/Tpl/layouts/__layout_header.tpl.php');
    }

    /**
     * Build footer layout.
     *
     * @since 1.4.0
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
        $capurl = current_user_can(TTO_CAP_MAX) ? admin_url('admin.php?page='.$this->identifier.'&action=tea_action&for=caps') : '';

        //Include template
        include(TTO_PATH . '/Tpl/layouts/__layout_footer.tpl.php');
    }

    /**
     * Build each type content.
     *
     * @param array $contents Contains all data
     * @todo try to include CUSTOM fields outside the Tea T.O.
     *
     * @since 1.4.0
     */
    protected function buildType($contents)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get includes
        $includes = $this->getIncludes();

        //Get all default fields in the Tea T.O. package
        $defaults_fields = TeaFields::getDefaults('fields');
        $wps = TeaFields::getDefaults('wordpress');

        //Iteration on all array
        foreach ($contents as $key => $content) {
            //Get type
            $type = $content['type'];

            //Check if the asked field is unknown
            if (!in_array($type, $defaults_fields)) {
                $this->adminmessage = sprintf(__('Something went wrong in your 
                    parameters definition with the id <b>%s</b>: 
                    the defined type is unknown!', TTO_I18N), $content['id']);
                continue;
            }

            //Set vars
            $class = ucfirst($type);
            $class = "\Takeatea\TeaThemeOptions\Fields\\$class\\$class";
            $includes = $this->getIncludes();

            //Include class field
            if (!isset($includes[$type])) {
                //Check if the class file exists
                if (!class_exists($class)) {
                    $this->adminmessage = sprintf(__('Something went wrong in 
                        your parameters definition: the class <b>%s</b> 
                        does not exist!', TTO_I18N), $class);
                    continue;
                }

                //Set the include
                $this->setIncludes($type);
            }

            //Make the magic
            $field = new $class();
            $field->templatePages($content);
        }
    }

    /**
     * ACCESSORS
     **/

    /**
     * Get transient duration.
     *
     * @return number $duration Transient duration in secondes
     *
     * @since 1.3.0
     */
    protected function getDuration()
    {
        //Return value
        return $this->duration;
    }

    /**
     * Set transient duration.
     *
     * @param integer $duration Transient duration in secondes
     *
     * @since 1.4.0
     */
    protected function setDuration($duration = TTO_DURATION)
    {
        //Define value
        $this->duration = $duration;
    }

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since 1.3.0
     */
    protected function getIncludes()
    {
        //Return value
        return $this->includes;
    }

    /**
     * Set includes.
     *
     * @param string $context Name of the included file's context
     *
     * @since 1.3.0
     */
    protected function setIncludes($context)
    {
        $includes = $this->getIncludes();
        $this->includes[$context] = true;
    }

    /**
     * Return option's value from transient.
     *
     * @param string $key The name of the transient
     * @param var $default The default value if no one is found
     * @return var $value
     *
     * @since 1.4.0
     */
    protected function getOption($key, $default)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Return value from DB
        return TeaThemeOptions::get_option($key, $default);
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
     * @param var $value The default value if no one is found
     * @param array $dependancy The default value if no one is found
     * @todo find a better way for the plugin version
     *
     * @since 1.4.0
     */
    protected function setOption($key, $value, $dependancy = array())
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check the category
        if (empty($key)) {
            $this->adminmessage = sprintf(__('Something went wrong. Key "%s" 
                and/or its value is empty.', TTO_I18N), $key);
            return;
        }

        //Check the key for special "NONE" value
        $value = 'NONE' == $value ? '' : $value;

        //Get duration
        $duration = $this->getDuration();

        //Set the option
        TeaThemeOptions::set_option($key, $value, $duration);

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
                    //Idem
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
            TeaThemeOptions::set_option($key . '_details', $details, $duration);
        }

        //Special usecase: checkboxes. When it's not checked, no data is sent 
        //through the $_POST array
        else if (false !== strpos($key, '__checkbox') && !empty($dependancy)) {
            //Get the key
            $previous = str_replace('__checkbox', '', $key);

            //Check if it exists (if not that means the user unchecked it) and set the option
            if (!isset($dependancy[$previous])) {
                TeaThemeOptions::set_option($previous, $value, $duration);
            }
        }

        //Special usecase: image. We can also register information as 
        //width, height, mimetype from upload and image inputs
        else if (false !== strpos($key, '__upload')) {
            //Get the image details
            $image = getimagesize($value);

            //Build details
            $details = array(
                'width' => $image[0],
                'height' => $image[1],
                'mime' => $image['mime']
            );

            //Set the other parameters
            TeaThemeOptions::set_option($key . '_details', $details, $duration);
        }
    }

    /**
     * Create roles and capabilities.
     *
     * @param string $key The name of the transient
     *
     * @since 1.4.3
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
        TeaThemeOptions::set_option('tea_capabilities', true);

        //Redirect to Tea TO homepage
        if ($redirect) {
            wp_safe_redirect(admin_url('admin.php?page='.$this->identifier));
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
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Defaults variables
        $page = empty($this->current) ? $this->identifier : $this->current;
        $includes = $this->getIncludes();

        //Include class field
        if (!isset($includes['elasticsearch'])) {
            //Set the include
            $this->setIncludes('elasticsearch');
        }

        //Make the magic
        $field = new Elasticsearch();
        $field->setCurrentPage($page);
        $field->actionElasticsearch($request);
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_POST
     *
     * @since 1.4.3
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
            $this->adminmessage = __('Something went wrong in your parameters 
                definition. You need to specify a network to make the 
                connection happens.', TTO_I18N);
            return;
        }

        //Defaults variables
        $page = empty($this->current) ? $this->identifier : $this->current;
        $includes = $this->getIncludes();

        //Include class field
        if (!isset($includes['network'])) {
            //Set the include
            $this->setIncludes('network');
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
        TeaThemeOptions::set_option('tea_dismiss', $dismiss);
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_REQUEST
     * @param array $files Contains all data in $_FILES
     *
     * @since 1.4.3
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
            if ('action' == $k || 'for' == $k || 'updated' == $k || 'submit' == $k) {
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
     * Returns automatical slug.
     *
     * @param string $slug
     * @return string $identifier.$slug
     *
     * @since 1.3.0
     */
    protected function getSlug($slug = '')
    {
        //Return value
        return $this->identifier . $slug;
    }
}
