<?php
/**
 * Tea TO backend functions and definitions
 * 
 * @package TakeaTea
 * @subpackage Tea Theme Options
 * @since Tea Theme Options 1.2.0
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
 * @since Tea Theme Options 1.2.0
 * @todo Special field:     RTE, Typeahead, Date, Geolocalisation
 * @todo Shortcodes panel:  Youtube, Vimeo, Dailymotion, Google Maps, Google Adsense,
 *                          Related posts, Private content, RSS Feed, Embed PDF,
 *                          Price table, Carousel, Icons
 * @todo Custom Post Types: Project, Carousel
 */
class Tea_Theme_Options
{
    public $adminmessage;
    public $breadcrumb;
    public $capability;
    public $categories;
    public $can_upload;
    public $current;
    public $directory;
    public $duration;
    public $identifier;
    public $index;
    public $is_admin;
    public $pages;
    public $wp_contents;

    /**
     * Constructor.
     *
     * @uses __setDuration()
     * @uses __updateOptions()
     * @param string $identifier
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __construct($identifier = 'tea_theme_options')
    {
        //Check identifier
        if (empty($identifier))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. You need at least an identifier.');
            return false;
        }

        //Define parameters
        $this->breadcrumb = array();
        $this->capability = 'edit_pages';
        $this->categories = array();
        $this->can_upload = current_user_can('upload_files');
        $this->directory = '';
        $this->identifier = $identifier;
        $this->index = null;
        $this->is_admin = is_admin() ? true : false;
        $this->pages = array();
        $this->wp_contents = array();

        //Set default duration
        $this->__setDuration();

        //Get page
        $this->current = isset($_GET['page']) ? $_GET['page'] : '';

        //Build Documentation content
        //$this->__buildDefaults();

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
     * @since Tea Theme Options 1.2.0
     */
    public function addPage($configs = array(), $contents = array())
    {
        //Check params and if a master page already exists
        if (empty($configs))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: your configs are empty. See README.md for more explanations.');
            return false;
        }
        else if (empty($contents))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: your contents are empty. See README.md for more explanations.');
            return false;
        }

        //Update capabilities
        if (isset($configs['capability'])) {
            $this->capability = $configs['capability'];
        }

        //Define the slug
        $slug = isset($configs['slug']) ? $this->__getSlug($configs['slug']) : $this->__getSlug();

        //Update the current page index
        $this->index = $slug;

        //Define page configurations
        $this->pages[$this->index]['title'] = isset($configs['title']) ? $configs['title'] : 'Theme Options';
        $this->pages[$this->index]['name'] = isset($configs['name']) ? $configs['name'] : 'Tea Theme Options';
        $this->pages[$this->index]['icon'] = isset($configs['icon']) ? $configs['icon'] : null;
        $this->pages[$this->index]['bigicon'] = isset($configs['bigicon']) ? $configs['bigicon'] : null;
        $this->pages[$this->index]['position'] = isset($configs['position']) ? $configs['position'] : null;
        $this->pages[$this->index]['description'] = isset($configs['description']) ? $configs['description'] : '';
        $this->pages[$this->index]['submit'] = isset($configs['submit']) ? $configs['submit'] : true;
        $this->pages[$this->index]['slug'] = $slug;
        $this->pages[$this->index]['contents'] = $contents;
    }

    /**
     * Register menus.
     *
     * @uses add_action() - Wordpress
     *
     * @since Tea Theme Options 1.2.0
     * @todo
     */
    public function buildMenus()
    {
        //Check if no master page is defined
        if (empty($this->pages))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: no master page found. You can simply do that by using the addPage public function.');
            return false;
        }
        //Check if we are in admin panel
        if ($this->is_admin)
        {
            //Initialize the current index page
            $this->index = null;

            //Register admin page action hook
            add_action('admin_menu', array(&$this, '__buildMenuPage'));

            //Register admin message action hook
            add_action('admin_notices', array(&$this, '__showAdminMessage'));

            //Register admin bar action hook
            add_action('wp_before_admin_bar_render', array(&$this, '__buildAdminBar'));
        }
    }


    //--------------------------------------------------------------------------//

    /**
     * Hook building scripts.
     *
     * @uses wp_enqueue_script() - Wordpress
     *
     * @since Tea Theme Options 1.1.1
     */
    public function __assetScripts()
    {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('tea-to', $this->directory . '/js/teato.js', array('jquery'));
    }

    /**
     * Hook building styles.
     *
     * @uses wp_enqueue_style() - Wordpress
     *
     * @since Tea Theme Options 1.1.1
     */
    public function __assetStyles()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('tea-to', $this->directory . '/css/teato.css');
    }


    //--------------------------------------------------------------------------//

    /**
     * Hook building admin bar.
     *
     * @since Tea Theme Options 1.1.1
     * @todo
     */
    public function __buildAdminBar()
    {
        global $wp_admin_bar;

        //Build WP menu in admin bar
        $wp_admin_bar->add_menu( array(
            'id' => $this->identifier,
            'title' => $this->pages[$this->identifier]['name'],
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
     * @since Tea Theme Options 1.2.0
     */
    public function __buildMenuPage()
    {
        //Check if no master page is defined
        if (empty($this->pages))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition: no master page found. You can simply do that by using the addPage public function.');
            return false;
        }

        //Set the current page
        $is_page = $this->identifier == $this->current ? true : false;

        //Set the current page
        $is_page = $this->identifier == $this->current ? true : false;

        //Add submenu pages
        foreach ($this->pages as $key => $page)
        {
            //Build slug and check it
            $is_page = $page['slug'] == $this->current ? true : $is_page;

            if ($this->identifier == $page['slug']) {
                //Add page
                add_menu_page(
                    $page['title'],                 //page title
                    $page['name'],                  //page name
                    $this->capability,              //capability
                    $this->identifier,              //parent slug
                    array(&$this, '__buildContent'),//function to display content
                    $page['icon']                   //icon
                );
            }
            else {
                //Add subpage
                add_submenu_page(
                    $this->identifier,              //parent slug
                    $page['title'],                 //page title
                    $page['name'],                  //page name
                    $this->capability,              //capability
                    $page['slug'],                  //menu slug
                    array(&$this, '__buildContent') //function to display content
                );
            }

            //Build breadcrumb
            $this->breadcrumb[] = array(
                'title' => $page['name'],
                'slug' => $page['slug']
            );
        }

        //Load assets
        if (!empty($this->current) && $is_page)
        {
            //Get the directory
            if (empty($this->directory))
            {
                $this->__setDirectory();
            }

            //Load assets action hook
            add_action('admin_print_scripts', array(&$this, '__assetScripts'));
            add_action('admin_print_styles', array(&$this, '__assetStyles'));
        }
    }


    //--------------------------------------------------------------------------//

    /**
     * Build default contents
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __buildDefaults()
    {
        //Build data
        $titles = array(
            'title' => __('Documentation'),
            'name' => __('Documentation'),
            'slug' => '_documentation',
            'submit' => false
        );
        $details = array(
            array(
                'type' => 'p',
                'title' => __('Simple, easy to use, very extendable Options framework for WP themes.'),
                'content' => __('There are many great theme option frameworks out there already, like the Options Framework, and the UP Theme Framework. Which is great, alot of this frameworks ideas, and functionality has been built upon the knowledge gained from those frameworks. But neither of them provided me with the extendability i needed/wanted. What i really wanted to do was merge those frameworks and add a little spice. I wanted a few specific things that have been the core reason for this framework.<br/><ul><li><b>Section Tabs</b> - I wanted each settings section to have its own "tab" which can be clicked through.</li><li><b>Use the WordPress Core Settings API</b> - I wanted the settings to use the core WordPress functions for creating/editing settings (no point reinventing the wheel).</li><li><b>Custom Error Handling</b> - Inline with the custom tabs i wanted to be able to display error totals, and custom messages for each section/setting.</li><li><b>Easily Extendable</b> - I wanted to be able to extend the framework for custom use applications. For settings fields and custom validation.</li><li><b>Customisable</b> - I wanted to be able to customise the functionality, and style without hacking at the core.</li></ul>This is why the NHP Theme Options Framework has been created. Every function/action can be customised, with more hook points and configuration options being added with every update.')
            )
        );

        //Build page with contents
        $this->addPage($titles, $details);
    }


    //--------------------------------------------------------------------------//

    /**
     * Build header layout.
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __buildLayoutHeader()
    {
        //Get all pages with link, icon and title
        $links = $this->breadcrumb;
        $icon = $this->pages[$this->identifier]['bigicon'];
        $page = empty($this->current) ? $this->identifier : $this->current;
        $title = empty($this->current) ? $this->pages[$this->identifier]['title'] : $this->pages[$this->current]['title'];
        $description = empty($this->current) ? $this->pages[$this->identifier]['description'] : $this->pages[$this->current]['description'];

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
     * @since Tea Theme Options 1.2.0
     */
    public function __buildContent()
    {
        //Get current infos
        $current = empty($this->current) ? $this->identifier : $this->current;

        //Checks contents
        if (empty($this->pages[$current]['contents']))
        {
            $this->adminmessage = __('Something went wrong: it seems you forgot to attach contents to the current page. Use of addFields() function to make the magic.');
            return false;
        }

        //Build header
        $this->__buildLayoutHeader();

        //Get globals
        $icon = $this->pages[$current]['icon'];

        //Get contents
        $title = $this->pages[$current]['title'];
        $contents = $this->pages[$current]['contents'];

        //Build contents relatively to the type
        $this->__buildType($contents);

        //Build footer
        $this->__buildLayoutFooter();
    }

    /**
     * Build footer layout.
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __buildLayoutFooter()
    {
        //Get all pages with submit button
        $submit = empty($this->current) ? $this->pages[$this->identifier]['submit'] : $this->pages[$this->current]['submit'];

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
     * @since Tea Theme Options 1.2.0
     */
    public function __buildType($contents, $group = false)
    {
        //Iteration on all array
        foreach ($contents as $key => $content)
        {
            //Check if an id is defined at least
            if (!isset($content['id']) && !in_array($content['type'], array('br', 'heading', 'hr', 'group', 'p')))
            {
                $this->adminmessage = __('Something went wrong in your parameters definition: no id is defined!');
                $this->adminmessage .= '<pre>' . var_dump($content) . '</pre>';
                return false;
            }

            //Get the right template
            switch ($content['type'])
            {
                //Display inputs
                case 'br':
                    $this->__fieldBr();
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
                case 'hr':
                    $this->__fieldHr();
                    break;
                case 'p':
                    $this->__fieldP();
                    break;

                //Normal inputs
                case 'choice':
                    $this->__fieldChoice($content, $group);
                    break;
                case 'hidden':
                    $this->__fieldHidden($content);
                    break;
                default:
                case 'text':
                    $this->__fieldText($content, $group);
                    break;
                case 'textarea':
                    $this->__fieldTextarea($content, $group);
                    break;

                //Special inputs
                case 'background':
                    $this->__fieldBackground($content, $group);
                    break;
                case 'color':
                    $this->__fieldColor($content, $group);
                    break;
                case 'date':
                    $this->__fieldDate($content, $group);
                    break;
                case 'font':
                    $this->__fieldFont($content, $group);
                    break;
                case 'image':
                    $this->__fieldImage($content, $group);
                    break;
                case 'rte':
                    $this->__fieldRTE($content, $group);
                    break;
                case 'social':
                    $this->__fieldSocial($content, $group);
                    break;
                case 'upload':
                    $this->__fieldUpload($content, $group);
                    break;

                //Wordpress inputs
                case 'categories':
                case 'menus':
                case 'pages':
                case 'posts':
                case 'posttypes':
                case 'tags':
                    $this->__fieldWordpressContents($content, $group);
                    break;
            }
        }
    }


    //--------------------------------------------------------------------------//

    /**
     * Build br component.
     *
     * @since Tea Theme Options 1.1.0
     */
    public function __fieldBr()
    {
        include('tpl/fields/__field_br.tpl.php');
    }

    /**
     * Build group component.
     *
     * @uses __buildType()
     * @param array $content Contains all data
     *
     * @since Tea Theme Options 1.1.1
     */
    public function __fieldGroup($content)
    {
        $percent = 0;

        //Get the number of subcontents
        foreach ($content['contents'] as $ctn) {
            if (in_array($ctn['type'], array('br', 'heading', 'hr')))
            {
                break;
            }

            $percent++;
        }

        //Get controlled percent
        switch ($percent)
        {
            case 1:
                $percent = '98';
                break;
            case 2:
                $percent = '50';
                break;
            case 3:
                $percent = '33';
                break;
            default:
            case 4:
                $percent = '25';
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
     * Build hr component.
     *
     * @since Tea Theme Options 1.1.0
     */
    public function __fieldHr()
    {
        include('tpl/fields/__field_hr.tpl.php');
    }

    /**
     * Build p component.
     *
     * @since Tea Theme Options 1.1.1
     */
    public function __fieldP()
    {
        //Default variables
        $title = isset($content['title']) ? $content['title'] : __('Tea Paragraph');
        $content = isset($content['content']) ? $content['content'] : '';

        //Get template
        include('tpl/fields/__field_p.tpl.php');
    }


    //-------------------------------------//

    /**
     * Build choice component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.1.0
     */
    public function __fieldChoice($content, $group) {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Choice');
        $options = isset($content['options']) ? $content['options'] : array();
        $description = isset($content['description']) ? $content['description'] : '';

        //Expand & multiple variables
        $multiple = isset($content['multiple']) ? $content['multiple'] : false;
        $expand = isset($content['expand']) ? $content['expand'] : false;

        if ($multiple) {
            $std = isset($content['std']) ? $content['std'] : array();
            $input = $expand ? 'checkbox' : 'multiselect';

            //Check selected
            $vals = $this->__getOption($id, $std);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        else {
            $std = isset($content['std']) ? $content['std'] : '';
            $input = $expand ? 'radio' : 'select';

            //Check selected
            $val = $this->__getOption($id, $std);
        }

        //Get template
        include('tpl/fields/__field_' . $input . '.tpl.php');
    }

    /**
     * Build hidden component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.1.0
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
     * Build text component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.1.0
     */
    public function __fieldText($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea ') . ucfirst($content['type']);
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $maxlength = isset($content['maxlength']) ? 'maxlength="' . $content['maxlength'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $options = isset($content['options']) ? $content['options'] : array();

        //Special variables
        $min = $max = $step = '';
        $options['type'] = !isset($options['type']) || empty($options['type']) ? 'text' : $options['type'];

        //Check options
        switch ($options['type'])
        {
            case 'number':
            case 'range':
                //Infos
                $type = $options['type'];
                //Special variables
                $min = isset($options['min']) ? 'min="' . $options['min'] . '"' : 'min="1"';
                $max = isset($options['max']) ? 'max="' . $options['max'] . '"' : 'max="50"';
                $step = isset($options['step']) ? 'step="' . $options['step'] . '"' : 'step="1"';
                break;
            default:
            case 'email':
            case 'password':
            case 'search':
            case 'text':
            case 'url':
                //Infos
                $type = $options['type'];
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
     * @since Tea Theme Options 1.1.1
     */
    public function __fieldTextarea($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Textarea');
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $rows = isset($content['rows']) ? $content['rows'] : '8';

        //Check selected
        $val = $this->__getOption($id, $std);
        $val = stripslashes($val);

        //Get template
        include('tpl/fields/__field_textarea.tpl.php');
    }


    //-------------------------------------//

    /**
     * Build background component.
     *
     * @uses __getDefaults()
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __fieldBackground($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Background');
        $std = isset($content['std']) ? $content['std'] : array('image' => '', 'image_custom' => '', 'color' => '', 'repeat' => 'repeat', 'position_x' => '', 'position_x_pos' => 'left', 'position_y' => '', 'position_y_pos' => 'top');
        $height = isset($content['height']) ? $content['height'] : '60';
        $width = isset($content['width']) ? $content['width'] : '150';
        $description = isset($content['description']) ? $content['description'] : '';
        $defaults = isset($content['default']) && true === $content['default'] ? true : false;
        $can_upload = $this->can_upload;
        $delete = __('Delete selection');

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($defaults)
        {
            $defaults = $this->__getDefaults('images');
            $options = array_merge($defaults, $options);
        }

        //Positions
        $repeats = $this->__getDefaults('background-repeat');

        //Radio selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_background.tpl.php');
    }

    /**
     * Build color component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __fieldColor($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Color');
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_color.tpl.php');
    }

    /**
     * Build date component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __fieldDate($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Date');
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Check selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_date.tpl.php');
    }

    /**
     * Build font component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.1.0
     */
    public function __fieldFont($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Font');
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $defaults = isset($content['default']) && true === $content['default'] ? true : false;

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($defaults)
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
     * Build image component.
     *
     * @uses __getDefaults()
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.1.1
     */
    public function __fieldImage($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Image');
        $std = isset($content['std']) ? $content['std'] : '';
        $height = isset($content['height']) ? $content['height'] : '60';
        $width = isset($content['width']) ? $content['width'] : '150';
        $multiple = isset($content['multiple']) ? $content['multiple'] : false;
        $description = isset($content['description']) ? $content['description'] : '';
        $type = $multiple ? 'checkbox' : 'radio';

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if (isset($content['default']) && true === $content['default'])
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
     * Build RTE component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.1.1
     */
    public function __fieldRTE($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Textarea');
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $rows = isset($content['rows']) ? $content['rows'] : '8';

        //Check selected
        $val = $this->__getOption($id, $std);
        $val = stripslashes($val);

        //Get template
        include('tpl/fields/__field_rte.tpl.php');
    }

    /**
     * Build social component.
     *
     * @uses __getDefaults()
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.1.1
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
        $default = isset($content['default']) ? $content['default'] : array();
        $options = $this->__getDefaults('social', $default);

        //Get values
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_social.tpl.php');
    }

    /**
     * Build upload component.
     *
     * @uses __getOption()
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __fieldUpload($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Upload');
        $std = isset($content['std']) ? $content['std'] : '';
        $library = isset($content['library']) ? $content['library'] : 'image';
        $description = isset($content['description']) ? $content['description'] : '';
        $multiple = isset($content['multiple']) && true == $content['multiple'] ? '1' : '0';
        $can_upload = $this->can_upload;
        $delete = __('Delete selection');

        //Check selected
        $val = $this->__getOption($id, $std);

        //Get template
        include('tpl/fields/__field_upload.tpl.php');
    }


    //-------------------------------------//

    /**
     * Build wordpress contents component.
     *
     * @uses __getOption()
     * @uses get_categories() - Wordpress
     * @uses wp_get_nav_menus() - Wordpress
     * @uses get_pages() - Wordpress
     * @uses wp_get_recent_posts() - Wordpress
     * @uses get_post_types() - Wordpress
     * @uses get_the_tags() - Wordpress
     * @param array $content Contains all data
     * @param bool $group Define if the field is displayed in group or not
     *
     * @since Tea Theme Options 1.2.0
     */
    public function __fieldWordpressContents($content, $group)
    {
        //Default variables
        $id = $content['id'];
        $type = $content['type'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Wordpress Contents');
        $multiple = isset($content['multiple']) ? $content['multiple'] : false;
        $description = isset($content['description']) ? $content['description'] : '';

        //Access the WordPress Categories via an Array
        if (empty($this->wp_contents) || !isset($this->wp_contents[$type]))
        {
            $this->wp_contents[$type] = array();

            //Set the first item
            if (!$multiple)
            {
                $this->wp_contents[$type][-1] = '---';
            }

            switch ($type)
            {
                default:
                case 'categories':
                    //Build request
                    $categories_obj = get_categories();

                    //Iterate on categories
                    foreach ($categories_obj as $cat)
                    {
                        //For Wordpress version < 3.0
                        if (empty($cat->cat_ID))
                        {
                            continue;
                        }

                        //Get the id and the name
                        $this->wp_contents[$type][$cat->cat_ID] = $cat->cat_name;
                    }
                    break;
                case 'menus':
                    //Build request
                    $menus_obj = wp_get_nav_menus(array('hide_empty' => false, 'orderby' => 'none'));

                    //Iterate on menus
                    foreach ($menus_obj as $menu)
                    {
                        //For Wordpress version < 3.0
                        if (empty($menu->term_id))
                        {
                            continue;
                        }

                        //Get the id and the name
                        $this->wp_contents[$type][$menu->term_id] = $menu->name;
                    }
                    break;
                case 'pages':
                    //Build request
                    $pages_obj = get_pages(array('sort_column' => 'post_parent,menu_order'));

                    //Iterate on pages
                    foreach ($pages_obj as $pag)
                    {
                        //For Wordpress version < 3.0
                        if (empty($pag->ID))
                        {
                            continue;
                        }

                        //Get the id and the name
                        $this->wp_contents[$type][$pag->ID] = $pag->post_title;
                    }
                    break;
                case 'posts':
                    //Get vars
                    $post = !isset($content['posttype']) ? 'post' : (is_array($content['posttype']) ? implode(',', $content['posttype']) : $content['posttype']);
                    $number = isset($content['number']) ? $content['number'] : 50;

                    //Build request
                    $posts_obj = wp_get_recent_posts(array('numberposts' => $number, 'post_type' => $post, 'post_status' => 'publish'), OBJECT);

                    //Iterate on posts
                    foreach ($posts_obj as $pos)
                    {
                        //For Wordpress version < 3.0
                        if (empty($pos->ID))
                        {
                            continue;
                        }

                        //Get the id and the name
                        $this->wp_contents[$type][$pos->ID] = $pos->post_title;
                    }
                    break;
                case 'posttypes':
                    //Build request
                    $types_obj = get_post_types(array(), 'object');

                    //Iterate on posttypes
                    foreach ($types_obj as $typ)
                    {
                        //Get the the name
                        $this->wp_contents[$type][$typ->name] = $typ->labels->name;
                    }
                    break;
                case 'tags':
                    //Build request
                    $tags_obj = get_the_tags();

                    //Iterate on tags
                    foreach ($tags_obj as $tag)
                    {
                        //Get the id and the name
                        $this->wp_contents[$type][$tag->term_id] = $tag->name;
                    }
                    break;
            }
        }

        //Set the categories
        $contents = $this->wp_contents[$type];

        //Check selected
        $vals = $this->__getOption($id, array());
        $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));

        //Get template
        include('tpl/fields/__field_wordpress.tpl.php');
    }


    //--------------------------------------------------------------------------//

    /**
     * Return default values.
     *
     * @return array $defaults All defaults data provided by the Tea TO
     *
     * @since Tea Theme Options 1.1.1
     */
    public function __getDefaults($return = 'images', $wanted = array())
    {
        $defaults = array();

        //Return defauls background
        if ('images' == $return)
        {
            $url = $this->directory . '/img/patterns/';

            $defaults = array(
                $url . 'none.png' => __('No background'),
                $url . 'bright_squares.png' => __('Bright squares'),
                $url . 'circles.png' => __('Circles'),
                $url . 'crosses.png' => __('Crosses'),
                $url . 'crosslines.png' => __('Crosslines'),
                $url . 'cubes.png' => __('Cubes'),
                $url . 'double_lined.png' => __('Double lined'),
                $url . 'honeycomb.png' => __('Honeycomb'),
                $url . 'linen.png' => __('Linen'),
                $url . 'project_paper.png' => __('Project paper'),
                $url . 'texture.png' => __('Tetxure'),
                $url . 'vertical_lines.png' => __('Vertical lines'),
                $url . 'vichy.png' => __('Vichy'),
                $url . 'wavecut.png' => __('Wavecut'),
                $url . 'custom.png' => 'CUSTOM'
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
                'addthis' => array(),
                'bloglovin' => array(__('Follow me on Bloglovin'), __('http://www.bloglovin.com/blog/__userid__/__username__')),
                'deviantart' => array(__('Follow me on Deviantart'), __('http://__username__.deviantart.com/')),
                'dribbble' => array(__('Follow me on Dribbble'), __('http://dribbble.com/__username__')),
                'facebook' => array(__('Follow me on Facebook'), __('http://www.facebook.com/__username__')),
                'flickr' => array(__('Follow me on Flickr'), __('http://www.flickr.com/photos/__username__')),
                'forrst' => array(__('Follow me on Forrst'), __('http://forrst.com/people/__username__')),
                'friendfeed' => array(__('Follow me on FriendFeed'), __('http://friendfeed.com/__username__')),
                'hellocoton' => array(__('Follow me on Hellocoton'), __('http://www.hellocoton.fr/mapage/__username__')),
                'googleplus' => array(__('Follow me on Google+'), __('http://plus.google.com/__username__')),
                'instagram' => array(__('Follow me on Instagram'), __('http://www.instagram.com/__username__')),
                'lastfm' => array(__('Follow me on LastFM'), __('http://www.lastfm.fr/user/__username__')),
                'linkedin' => array(__('Follow me on LinkedIn'), __('http://fr.linkedin.com/in/__username__')),
                'pinterest' => array(__('Follow me on Pinterest'), __('http://pinterest.com/__username__')),
                'rss' => array(__('Subscribe to my RSS feed')),
                'skype' => array(__('Connect us on Skype')),
                'tumblr' => array(__('Follow me on Tumblr')),
                'twitter' => array(__('Follow me on Twitter'), __('http://www.twitter.com/__username__')),
                'vimeo' => array(__('Follow me on Vimeo'), __('http://www.vimeo.com/__username__')),
                'youtube' => array(__('Follow me on Youtube'), __('http://www.youtube.com/user/__username__'))
            );

            $defaults = array();

            //Return only wanted
            foreach ($wanted as $want)
            {
                if (array_key_exists($want, $socials))
                {
                    $defaults[$want] = $socials[$want];
                }
            }
        }
        else if ('background-repeat' == $return)
        {
            $defaults = array(
                'no-repeat' => __('Background is displayed only once.'),
                'repeat-x' => __('Background is repeated horizontally only.'),
                'repeat-y' => __('Background is repeated vertically only.'),
                'repeat' => __('Background is repeated.')
            );
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
     * @since Tea Theme Options 1.1.1
     */
    public function __setOption($key, $value, $dependancy = array())
    {
        //Check the category
        //if (empty($key) || empty($value))
        if (empty($key))
        {
            $this->adminmessage = sprintf(__('Something went wrong. Key "%s" and/or its value is empty.'), $key);
            return false;
        }

        //Check the key for special "NONE" value
        $value = 'NONE' == $value ? '' : $value;

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
        if (false !== strpos($key, '__children'))
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


/**
 * Return a value from options
 *
 * @since Tea Theme Options 1.1.0
 */
function _get_option($option, $default = '', $transient = false) {
    //If a transient is asked...
    if ($transient) {
        //Get value from transient
        $value = get_transient($option);

        if (false === $value) {
            //Get it from DB
            $value = get_option($option);

            //Put the default value if not
            $value = false === $value ? $default : $value;

            //Set the transient for this value
            set_transient($option, $value, TRANSIENT_DURATION);
        }
    }
    //Else...
    else {
        //Get value from DB
        $value = get_option($option);

        //Put the default value if not
        $value = false === $value ? $default : $value;
    }

    //Return value
    return $value;
}


/**
 * Set a value into options
 *
 * @since Tea Theme Options 1.1.1
 */
function _set_option($option, $value, $transient = false) {
    //If a transient is asked...
    if ($transient) {
        //Set the transient for this value
        set_transient($option, $value, TRANSIENT_DURATION);
    }

    //Set value into DB
    update_option($option, $value);
}
