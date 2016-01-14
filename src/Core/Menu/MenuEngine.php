<?php

namespace crewstyle\OlympusZeus\Core\Menu;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Menu\MenuTemplate;

/**
 * Gets its own menu engine.
 *
 * @package Olympus Zeus
 * @subpackage Core\Menu\MenuEngine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class MenuEngine
{
    const ME_ICONSMALL = '/assets/img/zeus-icon.svg';
    const ME_TPL_DASHBOARD = '/Resources/contents/dashboard.php';
    const ME_TPL_SETTINGS = '/Resources/contents/settings.php';
    const ME_TPL_SETTINGS_GLOBAL = '/Resources/contents/settings-global.php';
    const ME_TPL_SETTINGS_MODULES = '/Resources/contents/settings-modules.php';

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
     * @since 4.0.0
     */
    public function __construct($identifier)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Initialize all default configurations
        $identifier = trim($identifier);
        $this->identifier = OlympusZeus::getUrlize($identifier);

        //Get current page and section
        $this->currentPage = isset($_REQUEST['page']) ? (string) $_REQUEST['page'] : '';
        $this->currentSection = isset($_REQUEST['section']) ? (string) $_REQUEST['section'] : '';

        //Initialize
        $this->initialize();

        //Update contents
        add_filter('olz_template_special_pages', array(&$this, 'hookTemplateSpecial'), 10, 2);
        add_filter('olz_menu_settings-global_contents', array(&$this, 'hookMenuContentsGlobal'));
        add_filter('olz_menu_settings-modules_contents', array(&$this, 'hookMenuContentsModules'));
    }

    /**
     * Build default contents.
     *
     * @uses current_user_can()
     * @todo other 3rd-party modules
     *
     * @since 4.0.0
     */
    protected function initialize()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check identifier
        if (empty($this->identifier)) {
            OlympusZeus::notify('error',
                OlympusZeus::translate('Something went wrong in your parameters
                definition. You need at least an identifier.')
            );

            return;
        }

        //Get current user capabilities
        $canuser = current_user_can(OLZ_WP_CAP_MAX);
        $contents = array();

        //Build page with contents
        $settingslink = admin_url('admin.php?page='.$this->identifier.'-settings');
        include(OLZ_PATH.self::ME_TPL_DASHBOARD);
        $contents[] = $configs;

        //Options
        $thirdsearch = $thirdrelated = $thirdcomments = $thirdnetworks = false;

        //Settings page's contents
        if ($this->identifier.'-settings' === $this->currentPage) {
            //Get modules
            $thirdcomments  = OlympusZeus::getConfigs('3rd-comments');
            $thirdnetworks  = OlympusZeus::getConfigs('3rd-networks');
            $thirdrelated   = OlympusZeus::getConfigs('3rd-related');
            $thirdsearch    = OlympusZeus::getConfigs('3rd-search');
        }

        //Build page with contents
        include(OLZ_PATH.self::ME_TPL_SETTINGS);
        $contents[] = $configs;

        /**
         * Add extra pages.
         *
         * @param array $contents
         * @param boolean $canuser
         * @return array $contents
         *
         * @since 4.0.0
         */
        $contents = apply_filters('olz_menu_engine_initialize', $contents, $canuser);

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
     * @since 4.0.0
     */
    public function addMenu($configs = array())
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check if a master page already exists
        if (empty($configs)) {
            OlympusZeus::notify('error',
                OlympusZeus::translate('Something went wrong in your parameters
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
            OlympusZeus::notify('error',
                sprintf(OlympusZeus::translate('Something went wrong in your parameters
                definition: a page with <code>%s</code> slug already exists. See README.md for
                more explanations.'), $slug)
            );

            return;
        }

        //Define page configurations
        $this->pages[$slug] = array(
            'id'            => isset($configs['slug']) && !empty($configs['slug']) ? $configs['slug'] : $this->identifier,
            'title'         => isset($configs['title']) ? $configs['title'] : OlympusZeus::translate('Olympus'),
            'name'          => !empty($name) ? $name : OlympusZeus::translate('Olympus'),
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
     * @since 4.0.0
     */
    public function buildMenus()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            OlympusZeus::notify('error',
                OlympusZeus::translate('Something went wrong in your parameters
                definition: no master page found. You can simply do that by
                using the <code>OlympusZeus::addPage</code> public function.')
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
     * @since 4.0.0
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
     * @since 4.0.0
     */
    public function hookAdminBar()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check if there is no problems on page definitions
        if (empty($this->pages) || !isset($this->pages[$this->identifier])) {
            OlympusZeus::notify('error',
                OlympusZeus::translate('Something went wrong in your parameters
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
     * @since 4.0.0
     */
    public function hookAdminMenu()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check if no master page is defined
        if (empty($this->pages)) {
            OlympusZeus::notify('error',
                OlympusZeus::translate('Something went wrong in your parameters
                definition: no master page found. You can simply do that by
                using the <code>OlympusZeus::addMenu</code> public function.')
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
         * @since 4.0.0
         */
        $tocheck = apply_filters('olz_menu_engine_check', $tocheck, $this->identifier);

        //Add submenu pages
        foreach ($this->pages as $page) {
            //Main page
            if ($this->identifier == $page['slug']) {
                //Update slug and capability
                $slug = $this->identifier;
                $capability = OLZ_WP_CAP;

                //Add menu page
                add_menu_page(
                    $page['title'],                 //page title
                    $page['title'],                 //page name
                    $capability,                    //capability
                    $slug,                          //parent slug
                    array(&$this, 'tplContents'),   //display content
                    OLZ_URI.self::ME_ICONSMALL,     //icon
                    80                              //position
                );
            }
            else {
                //Build slug and check it
                $slug = $page['slug'];
                $capability = in_array($page['slug'], $tocheck) ? OLZ_WP_CAP_MAX : OLZ_WP_CAP;
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
     * @return string $class The admin body class preprended with "olympus-mainwrap"
     *
     * @since 4.0.0
     */
    public function hookBodyClass($admin_body_class = '')
    {
        //Admin panel
        if (!OLZ_ISADMIN || !isset($this->pages[$this->currentPage])) {
            return '';
        }

        return $admin_body_class.' olympus-mainwrap';
    }

    /**
     * Hook special filter
     *
     * @return array $contents
     *
     * @since 3.3.0
     */
    public function hookMenuContentsGlobal($contents) {
        include(OLZ_PATH.self::ME_TPL_SETTINGS_GLOBAL);
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
        include(OLZ_PATH.self::ME_TPL_SETTINGS_MODULES);
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
     * @since 4.0.0
     */
    public function tplContents()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Instantiate templates
        $template = new MenuTemplate(
            $this->identifier,
            $this->currentPage,
            $this->currentSection,
            $this->pages
        );
    }
}
