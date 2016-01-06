<?php

namespace crewstyle\TeaThemeOptions\Core\Menu\Engine;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Menu\Template\Template;

/**
 * TTO MENU
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//
defined('ME_ICONSMALL')             or define('ME_ICONSMALL', TTO_URI.'/assets/img/teato-icon.svg');
defined('ME_TPL_DASHBOARD')         or define('ME_TPL_DASHBOARD', TTO_PATH.'/Resources/contents/dashboard.php');
defined('ME_TPL_SETTINGS')          or define('ME_TPL_SETTINGS', TTO_PATH.'/Resources/contents/settings.php');
defined('ME_TPL_SETTINGS_GLOBAL')   or define('ME_TPL_SETTINGS_GLOBAL', TTO_PATH.'/Resources/contents/settings-global.php');
defined('ME_TPL_SETTINGS_MODULES')  or define('ME_TPL_SETTINGS_MODULES', TTO_PATH.'/Resources/contents/settings-modules.php');

/**
 * TTO Menu
 *
 * To get its own menu.
 *
 * @package Tea Theme Options
 * @subpackage Core\Menu\Engine\Engine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Engine
{
    /**
     * @var string
     */
    protected $currentPage = '';

    /**
     * @var string
     */
    protected $currentSection = '';

    /**
     * @var Hook
     */
    protected $hook = null;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $pages = array();

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     *
     * @since 3.1.0
     */
    public function __construct($identifier)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Initialize all default configurations
        $identifier = trim($identifier);
        $this->identifier = TeaThemeOptions::getUrlize($identifier);

        //Get current page and section
        $this->currentPage = isset($_REQUEST['page']) ? (string) $_REQUEST['page'] : '';
        $this->currentSection = isset($_REQUEST['section']) ? (string) $_REQUEST['section'] : '';

        //Initialize
        $this->initialize();

        //Update contents
        add_filter('tto_template_special_pages', array(&$this, 'hookTemplateSpecial'), 10, 2);
        add_filter('tto_menu_settings-global_contents', array(&$this, 'hookMenuContentsGlobal'));
        add_filter('tto_menu_settings-modules_contents', array(&$this, 'hookMenuContentsModules'));
    }

    /**
     * Build default contents.
     *
     * @uses current_user_can()
     * @todo other 3rd-party modules
     *
     * @since 3.3.0
     */
    protected function initialize()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check identifier
        if (empty($this->identifier)) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                definition. You need at least an identifier.')
            );

            return;
        }

        //Get current user capabilities
        $canuser = current_user_can(TTO_WP_CAP_MAX);
        $contents = array();

        //Build page with contents
        $ttoquote = TTO_QUOTE;
        $settingslink = admin_url('admin.php?page='.$this->identifier.'-settings');
        include(ME_TPL_DASHBOARD);
        $contents[] = $configs;

        //Options
        $thirdsearch = $thirdrelated = $thirdcomments = $thirdnetworks = false;

        //Settings page's contents
        if ($this->identifier.'-settings' === $this->currentPage) {
            //Get modules
            $thirdcomments  = TeaThemeOptions::getConfigs('3rd-comments');
            $thirdnetworks  = TeaThemeOptions::getConfigs('3rd-networks');
            $thirdrelated   = TeaThemeOptions::getConfigs('3rd-related');
            $thirdsearch    = TeaThemeOptions::getConfigs('3rd-search');
        }

        //Build page with contents
        include(ME_TPL_SETTINGS);
        $contents[] = $configs;

        /**
         * Add extra pages.
         *
         * @param array $contents
         * @param boolean $canuser
         * @return array $contents
         *
         * @since 3.2.0
         */
        $contents = apply_filters('tto_menu_engine_initialize', $contents, $canuser);

        //Iterate on all contents
        foreach ($contents as $s => $ctn) {
            $this->addMenu($ctn);
        }
    }

    /**
     * Add a page to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 3.3.0
     */
    public function addMenu($configs = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if a master page already exists
        if (empty($configs)) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                definition: your configs are empty. See README.md for
                more explanations.')
            );

            return;
        }

        //Define the slug
        $slug = $this->identifier.(isset($configs['slug']) && !empty($configs['slug']) ? '-'.$configs['slug'] : '');
        $name = isset($configs['name']) ? $configs['name'] : (isset($configs['title']) ? $configs['title'] : '');

        //Check if an other page has this slug
        if (isset($this->pages[$slug])) {
            TeaThemeOptions::notify('error',
                sprintf(TeaThemeOptions::__('Something went wrong in your parameters
                definition: a page with <code>%s</code> slug already exists. See README.md for
                more explanations.'), $slug)
            );

            return;
        }

        //Define page configurations
        $this->pages[$slug] = array(
            'id'            => isset($configs['slug']) && !empty($configs['slug']) ? $configs['slug'] : $this->identifier,
            'title'         => isset($configs['title']) ? $configs['title'] : TeaThemeOptions::__('Tea T.O.'),
            'name'          => !empty($name) ? $name : TeaThemeOptions::__('Tea T.O.'),
            'position'      => isset($configs['position']) ? $configs['position'] : null,
            'description'   => isset($configs['description']) ? $configs['description'] : '',
            'sections'      => isset($configs['sections']) ? $configs['sections'] : array(),
            'contents'      => isset($configs['slug']) && !empty($configs['slug']) ? '' : $configs['contents'],
            'submit'        => isset($configs['submit']) ? $configs['submit'] : true,
            'slug'          => $slug,
        );
    }

    /**
     * Register menus.
     *
     * @uses add_action()
     *
     * @since 3.0.0
     */
    public function buildMenus()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                definition: no master page found. You can simply do that by
                using the <code>TeaThemeOptions::addPage</code> public function.')
            );

            return;
        }

        //Register admin bar action hook
        add_action('wp_before_admin_bar_render', array(&$this, 'hookAdminBar'));

        //Register admin page action hook
        add_action('admin_menu', array(&$this, 'hookAdminMenu'), 999);
    }

    /**
     * Return all pages.
     *
     * @return array $pages
     *
     * @since 3.0.0
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Hook building admin bar.
     *
     * @uses add_node()
     *
     * @since 3.3.0
     */
    public function hookAdminBar()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if there is no problems on page definitions
        if (empty($this->pages) || !isset($this->pages[$this->identifier])) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                definition: no master page defined!')
            );

            return;
        }

        //Get the WordPress globals
        global $wp_admin_bar;

        //Add submenu pages
        foreach ($this->pages as $page) {
            //Check the main page
            if ($this->identifier == $page['slug']) {
                //Build WP menu in admin bar
                $wp_admin_bar->add_node(array(
                    'parent' => '',
                    'id' => $this->identifier,
                    'title' => $page['title'],
                    'href' => admin_url('admin.php?page='.$this->identifier),
                    'meta' => false
                ));
            }

            //Build WP menu in admin bar
            $wp_admin_bar->add_node(array(
                'parent' => $this->identifier,
                'id' => $this->identifier.'-'.$page['slug'],
                'title' => $page['name'],
                'href' => admin_url('admin.php?page='.$page['slug']),
                'meta' => false
            ));
        }
    }

    /**
     * Hook building menus.
     *
     * @uses add_filter()
     * @uses add_menu_page()
     * @uses add_submenu_page()
     *
     * @since 3.0.0
     */
    public function hookAdminMenu()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                definition: no master page found. You can simply do that by
                using the <code>TeaThemeOptions::addMenu</code> public function.')
            );

            return;
        }

        //Load assets action hook
        add_filter('admin_body_class', array(&$this, 'hookBodyClass'));

        //Reserved words
        $tocheck = array(
            $this->identifier.'-settings'
        );

        /**
         * Update reserved words.
         *
         * @param boolean $tocheck
         * @param string $identifier
         * @return boolean $tocheck
         *
         * @since 3.2.0
         */
        $tocheck = apply_filters('tto_menu_engine_check', $tocheck, $this->identifier);

        //Add submenu pages
        foreach ($this->pages as $page) {
            //Main page
            if ($this->identifier == $page['slug']) {
                //Update slug and capability
                $slug = $this->identifier;
                $capability = TTO_WP_CAP;

                //Add menu page
                add_menu_page(
                    $page['title'],                 //page title
                    $page['title'],                 //page name
                    $capability,                    //capability
                    $slug,                          //parent slug
                    array(&$this, 'tplContents'),   //display content
                    ME_ICONSMALL,                   //icon
                    80                              //position
                );
            }
            else {
                //Build slug and check it
                $slug = $page['slug'];
                $capability = in_array($page['slug'], $tocheck) ? TTO_WP_CAP_MAX : TTO_WP_CAP;
            }

            //Add subpage
            add_submenu_page(
                $this->identifier,                  //parent slug
                $page['title'],                     //page title
                $page['name'],                      //page name
                $capability,                        //capability
                $slug,                              //parent slug
                array(&$this, 'tplContents')        //display content
            );
        }
    }

    /**
     * Hook body CSS classes.
     *
     * @param string $admin_body_class Contains the admin body class
     * @return string $class The admin body class preprended with "teato-mainwrap"
     *
     * @since 3.0.0
     */
    public function hookBodyClass($admin_body_class = '')
    {
        //Admin panel
        if (!TTO_IS_ADMIN || !isset($this->pages[$this->currentPage])) {
            return '';
        }

        return $admin_body_class.' tea-to-mainwrap';
    }

    /**
     * Hook special filter
     *
     * @return array $contents
     *
     * @since 3.3.0
     */
    public function hookMenuContentsGlobal($contents) {
        include(ME_TPL_SETTINGS_GLOBAL);
        return $return;
    }

    /**
     * Hook special filter
     *
     * @return array $contents
     *
     * @since 3.3.0
     */
    public function hookMenuContentsModules($contents) {
        include(ME_TPL_SETTINGS_MODULES);
        return $return;
    }

    /**
     * Hook special filter
     *
     * @return array $enabled
     *
     * @since 3.3.0
     */
    public function hookTemplateSpecial($enabled, $identifier) {
        $enabled[] = $identifier.'-settings';
        return $enabled;
    }

    /**
     * Build main contents layout.
     *
     * @since 3.0.0
     */
    public function tplContents()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Instantiate templates
        $template = new Template(
            $this->identifier,
            $this->currentPage,
            $this->currentSection,
            $this->pages
        );
    }
}
