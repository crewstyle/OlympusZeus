<?php

namespace crewstyle\TeaThemeOptions\Menu\Engine;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Template\Template;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;
use crewstyle\TeaThemeOptions\Network\Network;
use crewstyle\TeaThemeOptions\PostType\PostType;
use crewstyle\TeaThemeOptions\Search\Search;

/**
 * TTO MENU
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Menu
 *
 * To get its own menu.
 *
 * @package Tea Theme Options
 * @subpackage Menu\Engine\Engine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.1.0
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
    protected $iconSmall = TTO_URI.'/assets/img/teato-tiny.svg';

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $pages = array();

    /**
     * @var string
     */
    protected static $template = TTO_PATH.'/Resources/contents/dashboard.php';

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     * @param array $options Define if we can display special pages
     *
     * @since 3.1.0
     */
    public function __construct($identifier, $options)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Initialize all default configurations
        $identifier = trim($identifier);
        $this->identifier = TeaThemeOptions::getUrlize($identifier);

        //Get current page
        $this->currentPage = isset($_REQUEST['page']) ? (string) $_REQUEST['page'] : '';

        //Initialize
        $this->initialize($options);
    }

    /**
     * Build default contents.
     *
     * @uses current_user_can()
     * @param array $options Define if we can display special pages
     *
     * @since 3.0.0
     */
    protected function initialize($options)
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

        include(self::getTemplate());

        //Build page with contents
        $contents[] = array(
            'titles' => $titles,
            'details' => $details,
        );
        unset($titles, $details);

        //Do action to add extra pages
        $contents = apply_filters('tea_to_menu_init', $contents, $options, $canuser);

        //Iterate on all contents
        foreach ($contents as $s => $ctn) {
            $this->addMenu($ctn['titles'], $ctn['details']);
        }
    }

    /**
     * Add a page to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 3.0.0
     */
    public function addMenu($configs = array(), $contents = array())
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
        //Check if contents are not empty
        else if (empty($contents)) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                definition: your contents are empty. See README.md for
                more explanations.')
            );

            return;
        }

        //Define the slug
        $slug = $this->identifier . (isset($configs['slug']) && !empty($configs['slug']) ? $configs['slug'] : '');
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
            'title' => isset($configs['title']) ? $configs['title'] : TeaThemeOptions::__('Tea T.O.'),
            'name' => !empty($name) ? $name : TeaThemeOptions::__('Tea T.O.'),
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
     * Get template.
     *
     * @return string $template Post type template
     *
     * @since 3.0.0
     */
    public static function getTemplate()
    {
        return (string) self::$template;
    }

    /**
     * Hook building admin bar.
     *
     * @uses add_node()
     *
     * @since 3.0.0
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

        //Get the Wordpress globals
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

            $wp_admin_bar->add_node(array(
                'parent' => $this->identifier,
                'id' => $this->identifier.$page['slug'],
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
        $tocheck = array();
        $tocheck = apply_filters('tea_to_menu_check', $tocheck, $this->identifier);

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
                    $this->iconSmall,               //icon
                    3                               //position
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
            $this->pages
        );
    }
}
