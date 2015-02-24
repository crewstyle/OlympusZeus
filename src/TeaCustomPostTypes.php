<?php

namespace Takeatea\TeaThemeOptions;

/**
 * TEA CUSTOM POST TYPES
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * Tea Custom Post Types
 *
 * To get its own custom post types.
 *
 * @package Tea Theme Options
 * @subpackage Tea Custom Post Types
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 2.0.0
 *
 */
class TeaCustomPostTypes
{
    /**
     * @var array
     */
    protected $contents = array();

    /**
     * @var array
     */
    protected $customPostTypes = array();

    /**
     * @var array
     */
    protected $includes = array();

    /**
     * Constructor.
     *
     * @since 1.4.0
     */
    public function __construct()
    {
        //Get registered CPTs
        $this->customPostTypes = TeaThemeOptions::getConfigs('customposttypes');

        if (empty($this->customPostTypes)) {
            return;
        }

        //Register global action hook
        add_action('init', array(&$this, '__buildMenuCustomPostType'));
    }

    /**
     * Hook building menus for CPTS.
     *
     * @uses add_action()
     * @uses add_permastruct()
     * @uses add_rewrite_tag()
     * @uses flush_rewrite_rules()
     * @uses register_post_type()
     *
     * @since 1.5.2.3
     */
    public function __buildMenuCustomPostType()
    {
        if (empty($this->customPostTypes)) {
            return;
        }

        //Iterate on each cpt
        foreach ($this->customPostTypes as $key => $customPostType) {
            //Check if no master page is defined
            if (!isset($customPostType['title']) || empty($customPostType['title'])) {
                echo sprintf(__('Something went wrong in your parameters
                    definition: no title defined for your %s
                    custom post type. Please, try again by
                    filling the form properly.', TTO_I18N), '<b>'.$key.'</b>');
                continue;
            }

            //Get contents
            if (!empty($customPostType['contents'])) {
                $this->contents[] = $customPostType['slug'];
            }

            //Special case: define a post/page as title
            //to edit default post/page component
            if (in_array($customPostType['slug'], array('post', 'page'))) {
                continue;
            }

            //Check if post type already exists
            if (post_type_exists($customPostType['slug'])) {
                continue;
            }

            //Treat arrays
            $sups = isset($customPostType['supports']) && !empty($customPostType['supports'])
                ? $customPostType['supports']
                : array('title', 'editor', 'thumbnail');
            $taxs = isset($customPostType['taxonomies'])
                ? $customPostType['taxonomies']
                : array('category', 'post_tag');

            //Build labels
            $labels = array(
                'name' => $customPostType['title'],
                'singular_name' => isset($customPostType['singular']) && !empty($customPostType['singular']) ? $customPostType['singular'] : $customPostType['title'],
                'menu_name' => isset($customPostType['menu_name']) && !empty($customPostType['menu_name']) ? $customPostType['menu_name'] : $customPostType['title'],
                'all_items' => isset($customPostType['all_items']) && !empty($customPostType['all_items']) ? $customPostType['all_items'] : $customPostType['title'],
                'add_new' => isset($customPostType['add_new']) && !empty($customPostType['add_new']) ? $customPostType['add_new'] : __('Add new', TTO_I18N),
                'add_new_item' => isset($customPostType['add_new_item']) && !empty($customPostType['add_new_item']) ? $customPostType['add_new_item'] : sprintf(__('Add new %s', TTO_I18N), $customPostType['title']),
                'edit' => isset($customPostType['edit']) && !empty($customPostType['edit']) ? $customPostType['edit'] : __('Edit', TTO_I18N),
                'edit_item' => isset($customPostType['edit_item']) && !empty($customPostType['edit_item']) ? $customPostType['edit_item'] : sprintf(__('Edit %s', TTO_I18N), $customPostType['title']),
                'new_item' => isset($customPostType['new_item']) && !empty($customPostType['new_item']) ? $customPostType['new_item'] : sprintf(__('New %s', TTO_I18N), $customPostType['title']),
                'view' => isset($customPostType['view']) && !empty($customPostType['view']) ? $customPostType['view'] : __('View', TTO_I18N),
                'view_item' => isset($customPostType['view_item']) && !empty($customPostType['view_item']) ? $customPostType['view_item'] : sprintf(__('View %s', TTO_I18N), $customPostType['title']),
                'search_items' => isset($customPostType['search_items']) && !empty($customPostType['search_items']) ? $customPostType['search_items'] : sprintf(__('Search %s', TTO_I18N), $customPostType['title']),
                'not_found' => isset($customPostType['not_found']) && !empty($customPostType['not_found']) ? $customPostType['not_found'] : sprintf(__('No %s found', TTO_I18N), $customPostType['title']),
                'not_found_in_trash' => isset($customPostType['not_found_in_trash']) && !empty($customPostType['not_found_in_trash']) ? $customPostType['not_found_in_trash'] : sprintf(__('No %s found in Trash', TTO_I18N), $customPostType['title']),
                'parent_item_colon' => isset($customPostType['parent_item_colon']) && !empty($customPostType['parent_item_colon']) ? $customPostType['parent_item_colon'] : sprintf(__('Parent %s', TTO_I18N), $customPostType['title'])
            );

            //Build args
            $args = array(
                'labels' => $labels,
                'public' => isset($customPostType['options']['public']) && $customPostType['options']['public'] ? true : false,
                'show_ui' => isset($customPostType['options']['public']) && $customPostType['options']['public'] ? true : false,
                'show_in_menu' => isset($customPostType['options']['public']) && $customPostType['options']['public'] ? true : false,
                'hierarchical' => isset($customPostType['options']['hierarchical']) && $customPostType['options']['hierarchical'] ? true : false,
                'has_archive' => isset($customPostType['options']['has_archive']) && $customPostType['options']['has_archive'] ? true : false,
                'menu_icon' => isset($customPostType['menu_icon']) ? $customPostType['menu_icon'] : '',
                'menu_position' => isset($customPostType['menu_position']) ? $customPostType['menu_position'] : 100,
                'permalink_epmask' => EP_PERMALINK,
                'supports' => $sups,
                'taxonomies' => $taxs,
                'publicly_queryable' => true,
                'query_var' => true,
                'rewrite' => false,
            );

            //Action to register
            register_post_type($customPostType['slug'], $args);

            //Option
            $opt = $customPostType['slug'].'_tea_structure';

            //Get value
            $structure = TeaThemeOptions::get_option($opt, '/'.$customPostType['slug']);

            //Change structure
            add_rewrite_tag('%'.$customPostType['slug'].'%', '([^/]+)', $customPostType['slug'].'=');
            add_permastruct($customPostType['slug'], $structure, false);
        }

        add_action('post_type_link', array(&$this, '__translatePermalink'), 10, 3);

        if (TTO_IS_ADMIN) {
            //Display CPT custom fields
            add_action('admin_init', array(&$this, '__fieldsCustomPostType'));

            //Display settings in permalinks page
            add_action('admin_init', array(&$this, '__registerPermalinks'));

            //Save CPT custom fields
            add_action('save_post', array(&$this, '__saveCustomPostType'));

            //Flush all rewrite rules
            flush_rewrite_rules();
        }
    }

    /**
     * Hook building custom fields for CPTS.
     *
     * @uses add_meta_box()
     *
     * @since 1.4.0
     */
    public function __fieldsCustomPostType()
    {
        if (empty($this->contents) || empty($this->customPostTypes)) {
            return;
        }

        //Get all authorized fields
        $unauthorized = TeaFields::getDefaults('unauthorized');

        //Iterate on each cpt
        foreach ($this->contents as $cpt) {
            //Check if cpt exists or if its contents are empty
            if (!isset($this->customPostTypes[$cpt]['content']) || empty($this->customPostTypes[$cpt]['contents'])) {
                continue;
            }

            //Do it works!
            foreach ($this->customPostTypes[$cpt]['contents'] as $customPostType) {
                //Define vars
                $type = $customPostType['type'];

                //Check if we are authorized to use this field in CPTs
                if (in_array($type, $unauthorized)) {
                    continue;
                }

                //Set vars
                $class = ucfirst($type);
                $class = "\Takeatea\TeaThemeOptions\Fields\\$class\\$class";

                //Include class field
                if (!isset($this->includes[$type])) {
                    //Check if the class file exists
                    if (!class_exists($class)) {
                        echo sprintf(__('Something went wrong in your
                            parameters definition: the class %s
                            does not exist!', TTO_I18N), $class);
                        continue;
                    }

                    //Set the include
                    $this->includes[$type] = true;
                }

                //Title
                $title = isset($customPostType['title']) ? $customPostType['title'] : __('Metabox', TTO_I18N);

                //Add meta box
                add_meta_box(
                    $cpt . '-meta-box-' . $customPostType['id'],
                    $title,
                    array(&$class, 'templateCustomPostTypes'),
                    $cpt,
                    'normal',
                    'low',
                    array('contents' => $customPostType)
                );
            }
        }
    }

    /**
     * Hook building custom options in Permalink settings page.
     *
     * @uses register_setting()
     * @uses add_settings_field()
     *
     * @since 1.5.2.3
     */
    public function __registerPermalinks()
    {
        if (empty($this->customPostTypes)) {
            return false;
        }

        //Add section
        add_settings_section(
            'tea_custom_permalinks',                //ID
            __('Tea Custom Permalinks', TTO_I18N),  //Title
            array(&$this,'__permalinksTitle'),      //Callback
            'permalink'                             //Page
        );

        //Flush all rewrite rules
        if (isset($_POST['tea_custom_permalink_flush'])) {
            flush_rewrite_rules();
        }

        //Iterate on each cpt
        foreach ($this->customPostTypes as $customPostType) {
            //Special case: do not change post/page component
            if (in_array($customPostType['slug'], array('post', 'page'))) {
                continue;
            }

            //Option
            $opt = $customPostType['slug'].'_tea_structure';

            //Check POST
            if (isset($_POST[$opt])) {
                $value = $_POST[$opt];
                TeaThemeOptions::set_option($opt, $value);
            }

            //Get value
            $structure = TeaThemeOptions::get_option($opt, '/%'.$customPostType['slug'].'%-%post_id%');

            //Add fields
            add_settings_field(
                $opt,                                               //Identifier
                $customPostType['title'].' <code>%'.$customPostType['slug'].'%</code>',   //Title
                array(&$this,'__permalinksFields'),                 //Callback function
                'permalink',                                        //Page
                'tea_custom_permalinks',                            //Section
                array(
                    'name' => $customPostType['slug'].'_tea_structure',
                    'value' => $structure,
                )
            );
        }

        return true;
    }

    /**
     * Hook to display input value on Permalink settings page.
     *
     * @param array $opt Contains all usefull data
     *
     * @since 1.5.0
     */
    public function __permalinksFields($opt)
    {
        if (!empty($opt)) {
            include(TTO_PATH.'/Tpl/layouts/__layout_admin_permalinks.tpl.php');
        }
    }

    /**
     * Hook to display hidden input on Permalink settings title page.
     *
     * @since 1.5.0
     */
    public function __permalinksTitle()
    {
        //Display settings
        $this->__permalinksFields(array(
            'name' => 'tea_custom_permalink_flush',
            'value' => '1',
        ));
    }

    /**
     * Hook building custom fields for CPTS.
     *
     * @uses update_post_meta()
     *
     * @since 2.0.0
     */
    public function __saveCustomPostType()
    {
        global $post;

        if (!isset($post)) {
            return false;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post->ID;
        }

        //Check if we have some CPTS to initialize
        if (empty($this->contents) || empty($this->customPostTypes)) {
            return false;
        }

        // Check if its content is empty
        if (!isset($this->customPostTypes[$post->post_type]['contents']) || empty($this->customPostTypes[$post->post_type]['contents'])) {
            return false;
        }

        foreach ($this->customPostTypes[$post->post_type]['contents'] as $ctn) {
            $value = isset($_REQUEST[$ctn['id']]) ? $_REQUEST[$ctn['id']] : '';
            update_post_meta($post->ID, $post->post_type . '-' . $ctn['id'], $value);
        }

        return true;
    }

    /**
     * Hook building custom fields for CPTS.
     * From: http://shibashake.com/wordpress-theme/custom-post-type-permalinks-part-2
     *
     * @uses update_post_meta()
     *
     * @param string $permalink Contains permalink structure
     * @param integer $post_id Contains post ID
     * @param boolean $leavename Defeine wether to use postname or not
     * @return string $permalink Permalink final structure
     *
     * @since 1.5.0
     */
    public function __translatePermalink($permalink, $post_id, $leavename)
    {
        //Get post's datas
        $post = get_post($post_id);

        //Define permalink structure
        $rewritecode = array(
            '%year%',
            '%monthnum%',
            '%day%',
            '%hour%',
            '%minute%',
            '%second%',
            $leavename ? '' : '%postname%',
            '%post_id%',
            '%category%',
            '%author%',
            $leavename ? '' : '%pagename%',
        );

        if ('' == $permalink || in_array($post->post_status, array('draft', 'pending', 'auto-draft'))) {
            return $permalink;
        }

        //Need time
        $unixtime = strtotime($post->post_date);
        $date = explode(' ', date('Y m d H i s', $unixtime));

        //Need category
        $category = '';

        //Get categories
        if (strpos($permalink, '%category%') !== false) {
            $cats = get_the_category($post->ID);

            if ($cats) {
                usort($cats, '_usort_terms_by_ID');
                $category = $cats[0]->slug;

                if ($parent = $cats[0]->parent) {
                    $category = get_category_parents($parent, false, '/', true) . $category;
                }
            }

            //Show default category in permalinks, without having to assign it explicitly
            if (empty($category)) {
                $default_category = get_category(get_option('default_category'));
                $category = is_wp_error($default_category) ? '' : $default_category->slug;
            }
        }

        //Need author
        $author = '';

        //Get authors
        if (strpos($permalink, '%author%') !== false) {
            $authordata = get_userdata($post->post_author);
            $author = $authordata->__get('user_nicename');
        }

        //Define permalink values
        $rewritereplace = array(
            $date[0],
            $date[1],
            $date[2],
            $date[3],
            $date[4],
            $date[5],
            $post->post_name,
            $post->ID,
            $category,
            $author,
            $post->post_name,
        );

        //Change structure
        $permalink = str_replace($rewritecode, $rewritereplace, $permalink);

        //Return permalink
        return $permalink;
    }

    /**
     * Add a CPT to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    public function addCPT($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN || empty($configs) || !isset($configs['slug']) || empty($configs['slug'])) {
            return;
        }

        //Define the slug
        $slug = $configs['slug'];

        //Define cpt configurations
        $this->customPostTypes[$slug] = $configs;
        $this->customPostTypes[$slug]['contents'] = $contents;
    }

    /**
     * Build all CPTs to the theme options panel.
     *
     * @since 1.4.0
     */
    public function buildCustomPostTypes()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN || !count($this->customPostTypes)) {
            return;
        }

        TeaThemeOptions::setConfigs('customposttypes', $this->customPostTypes);
    }
}
