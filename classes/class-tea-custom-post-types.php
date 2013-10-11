<?php
/**
 * Tea TO backend functions and definitions
 * 
 * @package TakeaTea
 * @subpackage Tea Custom Post Types
 * @since Tea Theme Options 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Custom Post Types
 *
 * To get its own Custom Post Types
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Custom_Post_Types
{
    //Define protected vars
    protected $contents = array();
    protected $cpts = array();
    protected $includes = array();
    protected $is_admin;

    /**
     * Constructor.
     *
     * @param boolean $is_admin Define if we are in admin panel or not
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __construct($is_admin)
    {
        //Define admin status
        $this->setIsAdmin($is_admin);

        //Register global action hook
        add_action('init', array(&$this, '__buildMenuCustomPostType'));
    }


    //--------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Hook building menus for CPTS.
     *
     * @uses register_post_type()
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __buildMenuCustomPostType()
    {
        //Get all registered pages
        $cpts = _get_option('tea_config_cpts', array());
        $is_admin = $this->getIsAdmin();

        //Check if we have some CPTS to initialize
        if (empty($cpts))
        {
            return false;
        }

        //Iterate on each cpt
        foreach ($cpts as $key => $cpt)
        {
            //Check if no master page is defined
            if (!isset($cpt['title']) || empty($cpt['title']))
            {
                echo sprintf(__('Something went wrong in your parameters definition: no title defined for you <b>%s</b> custom post type. Please, try again by filling the form properly.', TTO_I18N), $key);
                continue;
            }

            //Special case: define a post/page as title to edit default post/page component
            if (!in_array($cpt['slug'], array('post', 'page')))
            {
                //Treat arrays
                $sups = isset($cpt['supports']) && !empty($cpt['supports']) ? $cpt['supports'] : array('title', 'editor', 'thumbnail');
                $taxs = isset($cpt['taxonomies']) && !empty($cpt['taxonomies']) ? $cpt['taxonomies'] : array('category', 'post_tag');

                //Build labels
                $labels = array(
                    'name' => $cpt['title'],
                    'singular_name' => isset($cpt['singular']) && !empty($cpt['singular']) ? $cpt['singular'] : $cpt['title'],
                    'menu_name' => isset($cpt['menu_name']) && !empty($cpt['menu_name']) ? $cpt['menu_name'] : $cpt['title'],
                    'all_items' => isset($cpt['all_items']) && !empty($cpt['all_items']) ? $cpt['all_items'] : $cpt['title'],
                    'add_new' => isset($cpt['add_new']) && !empty($cpt['add_new']) ? $cpt['add_new'] : __('Add new', TTO_I18N),
                    'add_new_item' => isset($cpt['add_new_item']) && !empty($cpt['add_new_item']) ? $cpt['add_new_item'] : sprintf(__('Add new %s', TTO_I18N), $cpt['title']),
                    'edit' => isset($cpt['edit']) && !empty($cpt['edit']) ? $cpt['edit'] : __('Edit', TTO_I18N),
                    'edit_item' => isset($cpt['edit_item']) && !empty($cpt['edit_item']) ? $cpt['edit_item'] : sprintf(__('Edit %s', TTO_I18N), $cpt['title']),
                    'new_item' => isset($cpt['new_item']) && !empty($cpt['new_item']) ? $cpt['new_item'] : sprintf(__('New %s', TTO_I18N), $cpt['title']),
                    'view' => isset($cpt['view']) && !empty($cpt['view']) ? $cpt['view'] : __('View', TTO_I18N),
                    'view_item' => isset($cpt['view_item']) && !empty($cpt['view_item']) ? $cpt['view_item'] : sprintf(__('View %s', TTO_I18N), $cpt['title']),
                    'search_items' => isset($cpt['search_items']) && !empty($cpt['search_items']) ? $cpt['search_items'] : sprintf(__('Search %s', TTO_I18N), $cpt['title']),
                    'not_found' => isset($cpt['not_found']) && !empty($cpt['not_found']) ? $cpt['not_found'] : sprintf(__('No %s found', TTO_I18N), $cpt['title']),
                    'not_found_in_trash' => isset($cpt['not_found_in_trash']) && !empty($cpt['not_found_in_trash']) ? $cpt['not_found_in_trash'] : sprintf(__('No %s found in Trash', TTO_I18N), $cpt['title']),
                    'parent_item_colon' => isset($cpt['parent_item_colon']) && !empty($cpt['parent_item_colon']) ? $cpt['parent_item_colon'] : sprintf(__('Parent %s', TTO_I18N), $cpt['title'])
                );

                //Build args
                $args = array(
                    'labels' => $labels,
                    'public' => isset($cpt['options']['public']) && $cpt['options']['public'] ? true : false,
                    'publicly_queryable' => isset($cpt['options']['public']) && $cpt['options']['public'] ? true : false,
                    'show_ui' => isset($cpt['options']['public']) && $cpt['options']['public'] ? true : false,
                    'show_in_menu' => isset($cpt['options']['public']) && $cpt['options']['public'] ? true : false,
                    'hierarchical' => isset($cpt['options']['hierarchical']) && $cpt['options']['hierarchical'] ? true : false,
                    'rewrite' => array('slug' => $cpt['slug']),
                    'permalink_epmask' => EP_PERMALINK,
                    'supports' => $sups,
                    'query_var' => isset($cpt['options']['query_var']) && $cpt['options']['query_var'] ? true : false,
                    'taxonomies' => $taxs,
                    'menu_icon' => isset($cpt['menu_icon']) ? $cpt['menu_icon'] : ''
                );

                //Action to register
                register_post_type($cpt['slug'], $args);
            }

            //Get contents
            if (!empty($cpt['contents']))
            {
                $this->contents[] = $cpt['slug'];
            }
        }

        //Get all admin details
        if ($is_admin)
        {
            //Display CPT custom fields
            add_action('admin_init', array(&$this, '__fieldsCustomPostType'));

            //Save CPT custom fields
            add_action('save_post', array(&$this, '__saveCustomPostType'));

            //Flush all rewrite rules
            flush_rewrite_rules();
        }
    }

    /**
     * Hook building custom fields for CPTS.
     *
     * @uses register_post_type()
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __fieldsCustomPostType()
    {
        //Get all registered pages
        $cpts = _get_option('tea_config_cpts', array());
        $is_admin = $this->getIsAdmin();
        $includes = $this->getIncludes();

        //Check if we have some CPTS to initialize
        if (empty($this->contents) || empty($cpts))
        {
            return false;
        }

        //Get all authorized fields
        require_once(TTO_PATH . 'classes/class-tea-fields.php');
        $authorized = Tea_Fields::getDefaults('fieldscpts');
        $wps = Tea_Fields::getDefaults('wordpress');

        //Iterate on each cpt
        foreach ($this->contents as $cpt)
        {
            //Check if cpt exists or if its contents are empty
            if (!isset($cpts[$cpt]) || empty($cpts[$cpt]['contents']))
            {
                continue;
            }

            //Do it works!
            foreach ($cpts[$cpt]['contents'] as $ctn)
            {
                //Define vars
                $type = $ctn['type'];

                //Check if we are authorized to use this field in CPTs
                if(!in_array($type, $authorized))
                {
                    continue;
                }

                //Set types in special case
                if(array_key_exists($type, $wps))
                {
                    $type = 'wordpress';
                }

                //Set vars
                $class = 'Tea_Fields_' . ucfirst($type);
                $inc = TTO_PATH . 'classes/fields/' . $type . '/class-tea-fields-' . $type . '.php';

                //Include class field
                if (!isset($includes[$type]))
                {
                    //Set the include
                    $this->setIncludes($type);

                    //Check if the class file exists
                    if (!file_exists($inc))
                    {
                        $this->adminmessage = sprintf(__('Something went wrong in your parameters definition: the file <b>%s</b> does not exist!', TTO_I18N), $inc);
                        continue;
                    }

                    //Require file
                    require_once($inc);
                }

                //Add meta box
                add_meta_box(
                    $cpt . '-meta-box-' . $ctn['id'],
                    $ctn['title'],
                    array(&$class, 'templateCustomPostTypes'),
                    $cpt,
                    'normal',
                    'low',
                    array('contents' => $ctn)
                );
            }
        }
    }

    /**
     * Hook building custom fields for CPTS.
     *
     * @uses register_post_type()
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __saveCustomPostType()
    {
        global $post;

        if (!isset($post))
        {
            return false;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        {
            return $post_id;
        }

        //Get all registered pages
        $cpts = _get_option('tea_config_cpts', array());
        $is_admin = $this->getIsAdmin();
        $includes = $this->getIncludes();

        //Check if we have some CPTS to initialize
        if (empty($this->contents) || empty($cpts))
        {
            return false;
        }

        //Get all authorized fields
        require_once(TTO_PATH . 'classes/class-tea-fields.php');
        $authorized = Tea_Fields::getDefaults('fieldscpts');
        $wps = Tea_Fields::getDefaults('wordpress');

        //Iterate on each cpt
        foreach ($this->contents as $cpt)
        {
            //Check if cpt exists or if its contents are empty
            if (!isset($cpts[$cpt]) || empty($cpts[$cpt]['contents']))
            {
                continue;
            }

            //Do it works!
            foreach ($cpts[$cpt]['contents'] as $ctn)
            {
                //Register values
                $value = isset($_REQUEST[$ctn['id']]) ? $_REQUEST[$ctn['id']] : '';
                update_post_meta($post->ID, $cpt . '-' . $ctn['id'], $value);
            }
        }
    }


    //--------------------------------------------------------------------------//

    /**
     * BUILD METHODS
     **/

    /**
     * Add a CPT to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since Tea Theme Options 1.3.0
     */
    public function addCPT($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!$this->getIsAdmin())
        {
            return false;
        }

        //Check params and if a master page already exists
        if (empty($configs))
        {
            return false;
        }
        else if (!isset($configs['slug']) || empty($configs['slug']))
        {
            return false;
        }

        //Define the slug
        $slug = $configs['slug'];

        //Define cpt configurations
        $this->cpts[$slug] = $configs;
        $this->cpts[$slug]['contents'] = $contents;
    }

    /**
     * Build all CPTs to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since Tea Theme Options 1.3.0
     */
    public function buildCPTs()
    {
        //Check if we are in admin panel
        if (!$this->getIsAdmin())
        {
            return false;
        }

        //Check params and if a master page already exists
        if (empty($this->cpts))
        {
            return false;
        }

        //Define cpt configurations
        _set_option('tea_config_cpts', $this->cpts);
    }

    /**
     * ACCESSORS METHODS
     **/

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getIncludes()
    {
        return $this->includes;
    }

    /**
     * Set includes.
     *
     * @param string $context Name of the included file's context
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function setIncludes($context)
    {
        $includes = $this->getIncludes();
        $this->includes[$context] = true;
    }

    /**
     * Get is_admin.
     *
     * @return bool $is_admin Define if we are in admin panel or not
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Set is_admin.
     *
     * @param bool $is_admin Define if we are in admin panel or not
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin ? true : false;
    }
}
