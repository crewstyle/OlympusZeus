<?php
/**
 * Tea TO backend functions and definitions
 * 
 * @package TakeaTea
 * @subpackage Tea Theme Options
 * @since Tea Theme Options 1.0.1
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//


/**
 * Tea Theme Option page.
 *
 * To get its own settings
 *
 * @since Tea Theme Options 1.0.1
 * @todo Special field:     WYSIWYG, Typeahead
 * @todo Shortcodes panel:  Youtube, Vimeo, Dailymotion, Google Maps, Google Adsense,
 *                          Related posts, Private content, RSS Feed, Embed PDF,
 *                          Price table, Carousel, Icons
 * @todo Custom Post Types: Project, Carousel
 * @todo Special input: typeahead with Ajax URL
 */
class Tea_Theme_Options
{
    public $adminmessage;
    public $breadcrumb;
    public $categories;
    public $current;
    public $directory;
    public $duration;
    public $identifier;
    public $index;
    public $page;
    public $pages;
    public $subpages;

    /**
     * Constructor.
     *
     * @uses __setDuration()
     * @uses __updateOptions()
     * @param string $identifier
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __construct($identifier = '')
    {
        //Check identifier
        if (empty($identifier))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. You need at least an identifier.');
            return false;
        }

        //Define parameters
        $this->breadcrumb = array();
        $this->categories = array();
        $this->directory = '';
        $this->identifier = $identifier;
        $this->index = null;
        $this->page = array();
        $this->pages = array();
        $this->subpages = array();

        //Set default duration
        $this->__setDuration();

        //Get page
        $this->current = isset($_GET['page']) ? $_GET['page'] : '';

        //Update options
        if (isset($_POST['tea_to_settings']))
        {
            $this->__updateOptions($_POST, $_FILES);
        }
    }

    /**
     * Add a page to the theme options panel.
     *
     * @param array $configs
     *
     * @since Tea Theme Options 1.0.1
     */
    public function addPage($configs = array())
    {
        //Check params and if a master page already exists
        if (empty($configs))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. See README.md for more explanations.');
            return false;
        }
        else if (!empty($this->page))
        {
            $this->adminmessage = __('Something went wrong: you already have a master page. Use addSubpage instead!');
            return false;
        }

        //Define page configurations
        $this->page['title'] = isset($configs['title']) ? $configs['title'] : 'Theme Options';
        $this->page['name'] = isset($configs['name']) ? $configs['name'] : 'Tea Theme Options';
        $this->page['capability'] = isset($configs['capability']) ? $configs['capability'] : 'edit_pages';
        $this->page['icon'] = isset($configs['icon']) ? $configs['icon'] : null;
        $this->page['bigicon'] = isset($configs['bigicon']) ? $configs['bigicon'] : null;
        $this->page['position'] = isset($configs['position']) ? $configs['position'] : null;
        $this->page['description'] = isset($configs['description']) ? $configs['description'] : '';
    }

    /**
     * Add a subpage to the theme options panel.
     *
     * @param array $configs
     *
     * @since Tea Theme Options 1.0.1
     */
    public function addSubpage($configs = array())
    {
        //Check params and if no master page is defined
        if (empty($configs))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. See README.md for more explanations.');
            return false;
        }
        else if (!isset($configs['slug']))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. You need at least a slug.');
            return false;
        }
        else if (empty($this->page))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: no master page found. You can simply do that by using the addPage public function.');
            return false;
        }

        //Define the subslug
        $slug = $this->__getSlug($configs['slug']);

        //Update the current page index
        $this->index = $slug;

        //Define subpage configurations
        $this->subpages[$this->index]['title'] = isset($configs['title']) ? $configs['title'] : 'Theme Options';
        $this->subpages[$this->index]['name'] = isset($configs['name']) ? $configs['name'] : 'Tea Theme Options';
        $this->subpages[$this->index]['description'] = isset($configs['description']) ? $configs['description'] : '';
        $this->subpages[$this->index]['slug'] = $configs['slug'];
    }

    /**
     * Add fields to the last created page.
     *
     * @param array $configs
     *
     * @since Tea Theme Options 1.0.1
     */
    public function addFields($configs = array())
    {
        //Check params and if no master page is defined
        if (empty($configs))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. See README.md for more explanations.');
            return false;
        }
        else if (empty($this->page))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: no master page found. You can simply do that by using the addPage public function.');
            return false;
        }

        //Push contents on the right page
        if (!isset($this->index))
        {
            $this->page['contents'] = $configs;
        }
        else
        {
            $this->subpages[$this->index]['contents'] = $configs;
        }
    }

    /**
     * Register menus.
     *
     * @uses add_action() - Wordpress
     *
     * @since Tea Theme Options 1.0.1
     * @todo
     */
    public function buildMenus()
    {
        //Check if no master page is defined
        if (empty($this->page))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: no master page found. You can simply do that by using the addPage public function.');
            return false;
        }

        //Initialize the current index page
        $this->index = null;

        //Register action hook
        add_action('admin_menu', array(&$this, '__buildMenuPage'));
        //Register admin message
        add_action('admin_notices', array(&$this, '__showAdminMessage'));
        /**
         * @todo not for now...
         */
        //add_action('wp_before_admin_bar_render', array(&$this, '__buildAdminBar'));
    }


    //--------------------------------------------------------------------------//

    /**
     * Hook building admin bar.
     *
     * @since Tea Theme Options 1.0.1
     * @todo
     */
    /*public function __buildAdminBar()
    {
        global $wp_admin_bar;

        //Build WP menu in admin bar
        $wp_admin_bar->add_menu( array(
            'id' => $this->identifier,
            'title' => __('Options'),
            'href' => admin_url('admin.php?page=' . $this->identifier)
        ));
    }*/

    /**
     * Hook building menus.
     *
     * @uses __getSlug()
     * @uses __setDirectory()
     * @uses add_action() - Wordpress
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __buildMenuPage()
    {
        //Check if no master page is defined
        if (empty($this->page))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: no master page found. You can simply do that by using the addPage public function.');
            return false;
        }

        //Get capability
        $capability = $this->page['capability'];

        //Add master page
        add_menu_page(
            $this->page['title'],               //page title
            $this->page['name'],                //page name
            $capability,                        //capability
            $this->identifier,                  //menu slug
            array(&$this, '__buildContent'),    //function to display content
            $this->page['icon']                 //icon
        );

        //Build breadcrumb
        $this->breadcrumb[] = array(
            'title' => $this->page['name'],
            'slug' => $this->identifier
        );

        //Add submenu pages
        foreach ($this->subpages as $key => $subpage)
        {
            //Build slug
            $slug = $this->__getSlug($subpage['slug']);

            //Add subpage
            add_submenu_page(
                $this->identifier,              //parent slug
                $subpage['title'],              //page title
                $subpage['name'],               //page name
                $capability,                    //capability
                $slug,                          //menu slug
                array(&$this, '__buildContent') //function to display content
            );

            //Build breadcrumb
            $this->breadcrumb[] = array(
                'title' => $subpage['name'],
                'slug' => $slug
            );
        }

        //Get the directory
        if (empty($this->directory))
        {
            $this->__setDirectory();
        }

        //Load assets
        add_action('admin_print_scripts', array(&$this, '__assetScripts'));
        add_action('admin_print_styles', array(&$this, '__assetStyles'));
    }


    //--------------------------------------------------------------------------//

    /**
     * Build scripts.
     *
     * @uses wp_enqueue_script() - Wordpress
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __assetScripts()
    {
        wp_enqueue_script('thickbox', '/'.WPINC.'/js/thickbox/thickbox.js', array('jquery'));
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('tea-to', $this->directory . '/js/teato.js', array('jquery'));
    }

    /**
     * Build styles.
     *
     * @uses wp_enqueue_style() - Wordpress
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __assetStyles()
    {
        wp_enqueue_style('thick-box', '/'.WPINC.'/js/thickbox/thickbox.css');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('tea-to', $this->directory . '/css/teato.css');
    }


    //--------------------------------------------------------------------------//

    /**
     * Build header layout.
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __buildLayoutHeader()
    {
        //Get all pages with link, icon and title
        $links = $this->breadcrumb;
        $icon = $this->page['bigicon'];
        $page = empty($this->current) ? $this->identifier : $this->current;
        $title = empty($this->current) || $this->identifier == $this->current ? $this->page['title'] : $this->subpages[$this->current]['title'];
        $description = empty($this->current) || $this->identifier == $this->current ? $this->page['description'] : $this->subpages[$this->current]['description'];

        //Include template
        include('tpl/layouts/__layout_header.tpl.php');
    }

    /**
     * Build content layout.
     *
     * @uses __buildLayoutFooter()
     * @uses __buildLayoutHeader()
     * @uses __buildType()
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __buildContent()
    {
        //Build header
        $this->__buildLayoutHeader();

        //Get globals
        $icon = $this->page['icon'];

        //Get contents
        if(isset($this->page['contents']) && $this->current == $this->identifier)
        {
            $title = $this->page['title'];
            $contents = $this->page['contents'];
        }
        else if (isset($this->subpages[$this->current]['contents']))
        {
            $title = $this->subpages[$this->current]['title'];
            $contents = $this->subpages[$this->current]['contents'];
        }
        else
        {
            $this->adminmessage = __('Something went wrong: it seems you forgot to attach contents to the current page. Use of addFields() function to make the magic.');
            return false;
        }

        //Build contents relatively to the type
        $this->__buildType($contents);

        //Build footer
        $this->__buildLayoutFooter();
    }

    /**
     * Build footer layout.
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __buildLayoutFooter()
    {
        //Include template
        include('tpl/layouts/__layout_footer.tpl.php');
    }


    //--------------------------------------------------------------------------//

    /**
     * Build each type content.
     *
     * @uses __field*()
     * @param array $contents
     * @param bool $group
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __buildType($contents, $group = false)
    {
        //Iteration on all array
        foreach ($contents as $key => $content)
        {
            //Check if an id is defined at least
            if (!isset($content['id']) && !in_array($content['type'], array('br', 'heading', 'hr', 'group')))
            {
                $this->adminmessage = __('Something went wrong in your parameters definition: no id is defined!');
                $this->adminmessage .= '<pre>' . var_dump($content) . '</pre>';
                return false;
            }

            //Get the right template
            switch ($content['type'])
            {
                case 'br':
                    $this->__fieldBr();
                    break;
                case 'category':
                    $this->__fieldCategory($content, $group);
                    break;
                case 'checkbox':
                    $this->__fieldCheckbox($content, $group);
                    break;
                case 'color':
                    $this->__fieldColor($content, $group);
                    break;
                case 'font':
                    $this->__fieldFont($content, $group);
                    break;
                case 'group':
                    if (!$group)
                    {
                        $this->__fieldGroup($content);
                    }
                    break;
                case 'heading':
                    $this->__fieldHeading($content);
                    break;
                case 'hidden':
                    $this->__fieldHidden($content);
                    break;
                case 'hr':
                    $this->__fieldHr();
                    break;
                case 'image':
                    $this->__fieldImage($content, $group);
                    break;
                case 'menu':
                    $this->__fieldMenu($content, $group);
                    break;
                case 'multiselect':
                    $this->__fieldMultiselect($content, $group);
                    break;
                case 'number':
                case 'range':
                    $this->__fieldNumber($content, $group);
                    break;
                case 'page':
                    $this->__fieldPage($content, $group);
                    break;
                default:
                case 'password':
                    $this->__fieldPassword($content, $group);
                    break;
                case 'radio':
                    $this->__fieldRadio($content, $group);
                    break;
                case 'select':
                    $this->__fieldSelect($content, $group);
                    break;
                case 'sidebar':
                    $this->__fieldSidebar($content, $group);
                    break;
                case 'social':
                    $this->__fieldSocial($content, $group);
                    break;
                case 'email':
                case 'search':
                case 'text':
                case 'url':
                    $this->__fieldText($content, $group);
                    break;
                case 'textarea':
                    $this->__fieldTextarea($content, $group);
                    break;
                case 'upload':
                    $this->__fieldUpload($content, $group);
                    break;
            }
        }
    }


    //--------------------------------------------------------------------------//

    /**
     * Build hr component.
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldBr()
    {
        include('tpl/fields/__field_br.tpl.php');
    }

    /**
     * Build category component.
     *
     * @uses __getOption()
     * @uses get_categories() - Wordpress
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldCategory($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Categories');
        $multiselect = isset($content['multiselect']) ? $content['multiselect'] : false;
        $description = isset($content['description']) ? $content['description'] : '';

        //Access the WordPress Categories via an Array
        if (empty($this->categories))
        {
            $this->categories = array();
            $categories_obj = get_categories(array('hide_empty' => 0, 'parent' => 0));

            foreach ($categories_obj as $cat)
            {
                //Don't consider the select WP default title
                if ('Select a category:' == $cat->cat_name)
                {
                    continue;
                }

                //Get the id and the name
                $this->categories[$cat->cat_ID] = $cat->cat_name;
            }
        }

        //Set the categories
        $categories = $this->categories;

        //Check selected
        $vals = $this->__getOption($id, array());
        $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));

        //Get template
        include('tpl/fields/__field_category.tpl.php');
    }

    /**
     * Build checkbox component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldCheckbox($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Checkbox');
        $std = isset($content['std']) ? $content['std'] : array();
        $options = isset($content['options']) ? $content['options'] : array();
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $vals = $this->__getOption($id, $std);
        $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));

        //Get template
        include('tpl/fields/__field_checkbox.tpl.php');
    }

    /**
     * Build text component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldColor($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Text');
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $val = $this->__getOption($id, $std);
        $val = empty($val) ? '#000000' : $val;

        //Get template
        include('tpl/fields/__field_color.tpl.php');
    }

    /**
     * Build font component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldFont($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Font');
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if (isset($content['default']) && true == $content['default'])
        {
            $defaults = $this->__getDefaults('fonts');
            $options = array_merge($defaults, $options);
        }

        //Radio selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_font.tpl.php');
    }

    /**
     * Build group component.
     *
     * @uses __buildType()
     * @param array $content Contains all data
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldGroup($content)
    {
        $count = 0;

        //Get the number of subcontents
        foreach ($content['contents'] as $ctn) {
            if (in_array($ctn['type'], array('br', 'heading', 'hr')))
            {
                break;
            }

            $count++;
        }

        //Get controlled count
        switch ($count)
        {
            case 1:
                $count = '98';
                break;
            case 2:
                $count = '50';
                break;
            case 3:
                $count = '33';
                break;
            default:
            case 4:
                $count = '25';
                break;
        }

        //Default variables
        $title = isset($content['title']) ? $content['title'] : __('Tea Group');
        $description = isset($content['description']) ? $content['description'] : '';

        //Get group header
        include('tpl/fields/__field_group_header.tpl.php');

        //Recursive content
        $this->__buildType($content['contents'], true);

        //Get group footer
        include('tpl/fields/__field_group_footer.tpl.php');
    }

    /**
     * Build heading component.
     *
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldHeading($content)
    {
        //Default variables
        $title = isset($content['title']) ? $content['title'] : __('Tea Heading');

        //Get template
        include('tpl/fields/__field_heading.tpl.php');
    }

    /**
     * Build hidden component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldHidden($content)
    {
        //Default variables
        $id = $content['id'];
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_hidden.tpl.php');
    }

    /**
     * Build hr component.
     *
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldHr()
    {
        include('tpl/fields/__field_hr.tpl.php');
    }

    /**
     * Build image component.
     *
     * @uses __getDefaults()
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldImage($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Image');
        $std = isset($content['std']) ? $content['std'] : '';
        $height = isset($content['height']) ? $content['height'] : '60';
        $width = isset($content['width']) ? $content['width'] : '150';
        $multiselect = isset($content['multiselect']) ? $content['multiselect'] : false;
        $description = isset($content['description']) ? $content['description'] : '';
        $type = $multiselect ? 'checkbox' : 'radio';

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if (isset($content['default']) && true == $content['default'])
        {
            $defaults = $this->__getDefaults('images');
            $options = array_merge($defaults, $options);
        }

        //Radio selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_image.tpl.php');
    }

    /**
     * Build sidebar component.
     *
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldMenu($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Menu');
        $description = isset($content['description']) ? $content['description'] : '';

        //Get template
        include('tpl/fields/__field_menu.tpl.php');
    }

    /**
     * Build multiselect component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldMultiselect($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Multiselect');
        $std = isset($content['std']) ? $content['std'] : array();
        $options = isset($content['options']) ? $content['options'] : array();
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $vals = $this->__getOption($id, $std);
        $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));

        //Get template
        include('tpl/fields/__field_multiselect.tpl.php');
    }

    /**
     * Build number component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldNumber($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Number');
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $min = isset($content['min']) ? 'min="' . $content['min'] . '"' : 'min="1"';
        $max = isset($content['max']) ? 'max="' . $content['max'] . '"' : 'max="50"';
        $step = isset($content['step']) ? 'step="' . $content['step'] . '"' : 'step="1"';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check the type
        switch ($content['type'])
        {
            case 'number':
            case 'range':
                $type = $content['type'];
                break;
            default:
                $type = 'number';
                break;
        }

        //Check selected
        $val = $this->__getOption($id, $std);
        $val = stripslashes($val);

        //Get template
        include('tpl/fields/__field_number.tpl.php');
    }

    /**
     * Build page component.
     *
     * @uses __getOption()
     * @uses get_pages() - Wordpress
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldPage($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Pages');
        $multiselect = isset($content['multiselect']) ? $content['multiselect'] : false;
        $description = isset($content['description']) ? $content['description'] : '';

        //Access the WordPress Pages via an Array
        if (empty($this->pages))
        {
            $this->pages = array();
            $pages_obj = get_pages(array('sort_column' => 'post_parent,menu_order'));

            foreach ($pages_obj as $pag)
            {
                //Don't consider the select WP default title
                if ('Select a page:' == $pag->post_title)
                {
                    continue;
                }

                //Get the id and the name
                $this->pages[$pag->ID] = $pag->post_title;
            }
        }

        //Set the pages
        $pages = $this->pages;


        //Check selected
        $vals = $this->__getOption($id, array());
        $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));

        //Get template
        include('tpl/fields/__field_page.tpl.php');
    }

    /**
     * Build password component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldPassword($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Password');
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $val = $this->__getOption($id, $std);
        $val = stripslashes($val);

        //Get template
        include('tpl/fields/__field_password.tpl.php');
    }

    /**
     * Build radio component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldRadio($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Radio');
        $std = isset($content['std']) ? $content['std'] : '';
        $options = isset($content['options']) ? $content['options'] : array();
        $description = isset($content['description']) ? $content['description'] : '';

        //Radio selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_radio.tpl.php');
    }

    /**
     * Build select component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldSelect($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Select');
        $std = isset($content['std']) ? $content['std'] : '';
        $options = isset($content['options']) ? $content['options'] : array();
        $description = isset($content['description']) ? $content['description'] : '';

        //Radio selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_select.tpl.php');
    }

    /**
     * Build sidebar component.
     *
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldSidebar($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Sidebar');
        $description = isset($content['description']) ? $content['description'] : '';

        //Get template
        include('tpl/fields/__field_sidebar.tpl.php');
    }

    /**
     * Build social component.
     *
     * @uses __getDefaults()
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldSocial($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Social');
        $std = isset($content['std']) ? $content['std'] : array();
        $description = isset($content['description']) ? $content['description'] : '';
        $url = $this->directory . '/img/social/icon-';

        //Get options
        $wanted = isset($content['wanted']) ? $content['wanted'] : array();
        $options = $this->__getDefaults('social', $wanted);

        //Radio selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_social.tpl.php');
    }

    /**
     * Build text component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldText($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Text');
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $maxlength = isset($content['maxlength']) ? 'maxlength="' . $content['maxlength'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check the type
        switch ($content['type'])
        {
            case 'email':
            case 'search':
            case 'text':
            case 'url':
                $type = $content['type'];
                break;
            default:
                $type = 'text';
                break;
        }

        //Check selected
        $val = $this->__getOption($id, $std);
        $val = stripslashes($val);

        //Get template
        include('tpl/fields/__field_text.tpl.php');
    }

    /**
     * Build textarea component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldTextarea($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Textarea');
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $val = $this->__getOption($id, $std);
        $val = stripslashes($val);

        //Get template
        include('tpl/fields/__field_textarea.tpl.php');
    }

    /**
     * Build upload component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __fieldUpload($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Upload');
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_upload.tpl.php');
    }


    //--------------------------------------------------------------------------//

    /**
     * Return default values.
     *
     * @return array $defaults All defaults data provided by the Tea TO
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __getDefaults($return = 'images', $wanted = array())
    {
        $defaults = array();

        //Return defauls background
        if ('images' == $return)
        {
            $url = $this->directory . '/img/patterns/';

            $defaults = array(
                $url . 'no_background.png',
                $url . 'bright_squares.png',
                $url . 'circles.png',
                $url . 'crosses.png',
                $url . 'crosslines.png',
                $url . 'cubes.png',
                $url . 'double_lined.png',
                $url . 'honeycomb.png',
                $url . 'linen.png',
                $url . 'project_paper.png',
                $url . 'texture.png',
                $url . 'vichy.png',
                $url . 'wavecut.png'
            );
        }
        //Return defaults font
        else if ('fonts' == $return)
        {
            $url = $this->directory . '/img/fonts/';

            $defaults = array(
                'sansserif' => $url . 'sansserif.png',
                'Arvo' => $url . 'arvo.png',
                'Cantarell' => $url . 'cantarell.png',
                'Copse' => $url . 'copse.png',
                'Cuprum' => $url . 'cuprum.png',
                'Lobster' => $url . 'lobster.png',
                'Lobster+Two' => $url . 'lobstertwo.png',
                'Qwigley' => $url . 'qwigley.png',
                'Orbitron' => $url . 'orbitron.png',
                'Puritan' => $url . 'puritan.png',
                'Sacramento' => $url . 'sacramento.png',
                'Titillium+Web' => $url . 'titillium.png',
                'Vollkorn' => $url . 'vollkorn.png',
                'Yanone+Kaffeesatz' => $url . 'yanonekaffeesatz.png'
            );
        }
        //Return defaults social button
        else if ('social' == $return)
        {
            $socials = array(
                'addthis',
                'bloglovin',
                'deviantart',
                'dribbble',
                'facebook',
                'flickr',
                'forrst',
                'friendfeed',
                'hellocoton',
                'googleplus',
                'instagram',
                'lastfm',
                'linkedin',
                'pinterest',
                'rss',
                'skype',
                'tumblr',
                'twitter',
                'vimeo',
                'youtube'
            );

            $defaults = array();

            //Return only wanted
            foreach ($wanted as $want)
            {
                if (in_array($want, $socials))
                {
                    $defaults[] = $want;
                }
            }
        }

        //Return the array
        return $defaults;
    }

    /**
     * Get Tea TO directory.
     *
     * @return string $directory Path of the Tea TO directory
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __getDirectory()
    {
        return $this->directory;
    }
    /**
     * Set Tea TO directory.
     *
     * @param string $directory The path of the Tea TO directory
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __setDirectory($directory = '')
    {
        $this->directory = empty($directory) ? get_template_directory_uri() . '/tea_theme_options' : $directory;
    }

    /**
     * Get transient duration.
     *
     * @return number $duration Transient duration in secondes
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __getDuration()
    {
        return $this->duration;
    }
    /**
     * Set transient duration.
     *
     * @param number $duration Transient duration in secondes
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __setDuration($duration = 86400)
    {
        $this->duration = $duration;
    }

    /**
     * Return option's value from transient.
     *
     * @uses __setOption()
     * @uses get_transient() - Wordpress
     * @uses get_option() - Wordpress
     * @param string $key The name of the transient
     * @param var $default The default value if no one is found
     * @return var $value
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __getOption($key, $default)
    {
        //Get transient
        $value = get_transient($key);

        //Check if transient exists
        if (false === $value)
        {
            //Get from the DB
            $value = get_option($key);

            //Set the value if needed
            $value = false === $value ? $default : $value;

            //Set the transient
            $this->__setOption($key, $value);
        }

        //Return the value
        return $value;
    }

    /**
     * Register uniq option into transient.
     *
     * @uses get_cat_name() - Wordpress
     * @uses get_categories() - Wordpress
     * @uses get_category() - Wordpress
     * @uses get_category_feed_link() - Wordpress
     * @uses get_category_link() - Wordpress
     * @uses is_wp_error() - Wordpress
     * @uses set_transient() - Wordpress
     * @uses update_option() - Wordpress
     * @param string $key The name of the transient
     * @param var $value The default value if no one is found
     * @param array $dependancy The default value if no one is found
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __setOption($key, $value, $dependancy = array())
    {
        //Set the option
        update_option($key, $value);

        //Set the transient
        set_transient($key, $value, $this->duration);

        //Special usecase: category. We can also register information as title, slug, description
        if (false !== strpos($key, '__category'))
        {
            //Get the category details
            $category = get_category($value);

            //Check the category
            if (is_wp_error($category))
            {
                $this->adminmessage = __('Something went wrong. The selected category is unknown.');
                return false;
            }

            //Set the other parameters: title
            update_option($key . '_title', $category->name);
            set_transient($key . '_title', $category->name, $this->duration);

            //Set the other parameters: description
            update_option($key . '_description', $category->description);
            set_transient($key . '_description', $category->description, $this->duration);

            //Set the other parameters: slug
            $slug = get_category_link($value);
            update_option($key . '_slug', $slug);
            set_transient($key . '_slug', $slug, $this->duration);

            //Set the other parameters: feed
            $feed = get_category_feed_link($value);
            update_option($key . '_feed', $feed);
            set_transient($key . '_feed', $feed, $this->duration);
        }

        //Special usecase: categories. We can also register information as title, slug, description and children
        if (false !== strpos($key, '__categories'))
        {
            //All contents
            $allcats = array();

            //Iterate on categories
            foreach ($value as $c)
            {
                //Get all children
                $cats = get_categories('child_of=' . $c);
                $children = '';

                //Iterate on children to get ID only
                foreach ($cats as $ca)
                {
                    //Get all children
                    $children .= (!empty($children) ? ',' : '') . $ca->cat_ID;

                    //Idem
                    $allcats[$ca->cat_ID] = array(
                        'id' => $ca->cat_ID,
                        'name' => get_cat_name($ca->cat_ID),
                        'link' => get_category_link($ca->cat_ID),
                        'has_parent' => true,
                        'children' => ''
                    );
                }

                //Add some extra contents
                $allcats[$c] = array(
                    'id' => $c,
                    'name' => get_cat_name($c),
                    'link' => get_category_link($c),
                    'has_parent' => false,
                    'children' => $children
                );
            }

            //Set the other parameters: width
            update_option($key . '_children', $allcats);
            set_transient($key . '_children', $allcats, $this->duration);
        }

        //Special usecase: checkboxes. When it's not checked, no data is sent through the $_POST array
        if (false !== strpos($key, '__checkbox') && !empty($dependancy))
        {
            //Get the key
            $previous = str_replace('__checkbox', '', $key);

            //Check if it exists (if not that means the user unchecked it) and set the option
            if (!isset($dependancy[$previous]))
            {
                update_option($previous, $value);
                set_transient($previous, $value, $this->duration);
            }
        }

        //Special usecase: image. We can also register information as width, height, mimetype from upload and image inputs
        if (false !== strpos($key, '__upload'))
        {
            //Get the image details
            $image = getimagesize($value);

            //Set the other parameters: width
            update_option($key . '_width', $image[0]);
            set_transient($key . '_width', $image[0], $this->duration);

            //Set the other parameters: height
            update_option($key . '_height', $image[1]);
            set_transient($key . '_height', $image[1], $this->duration);

            //Set the other parameters: mimetype
            update_option($key . '_mime', $image['mime']);
            set_transient($key . '_mime', $image['mime'], $this->duration);
        }
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses __setOption()
     * @uses wp_handle_upload() - Wordpress
     * @param array $post Contains all data in $_POST
     * @param array $files Contains all data in $_FILES
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __updateOptions($post, $files)
    {
        //Set all options in transient
        foreach ($post as $k => $v)
        {
            //Don't register this default value
            if ('tea_to_settings' == $k || 'submit' == $k)
            {
                continue;
            }

            //Special usecase: checkboxes. When it's not checked, no data is sent through the $_POST array
            $p = false !== strpos($k, '__checkbox') ? $post : array();

            //Register option and transient
            $this->__setOption($k, $v, $p);
        }

        //Check if files are attempting to be uploaded
        if (!empty($files))
        {
            //Get required files
            require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
            require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
            require_once(ABSPATH . 'wp-admin' . '/includes/media.php');

            //Set all URL in transient
            foreach ($files as $k => $v)
            {
                //Don't do nothing if no file is defined
                if (empty($v['tmp_name']))
                {
                    continue;
                }

                //Do the magic
                $file = wp_handle_upload($v, array('test_form' => false));

                //Register option and transient
                $this->__setOption($k, $file['url']);
            }
        }
    }

    /**
     * Returns automatical slug.
     *
     * @param string $slug
     * @return string $identifier.$slug
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __getSlug($slug = '')
    {
        return $this->identifier . $slug;
    }


    //--------------------------------------------------------------------------//

    /**
     * Display a warning message on the admin panel.
     *
     * @since Tea Theme Options 1.0.1
     */
    public function __showAdminMessage()
    {
        if (!empty($this->adminmessage))
        {
            $content = $this->adminmessage;

            //Get template
            include('tpl/layouts/__admin_message.tpl.php');
        }
    }
}
