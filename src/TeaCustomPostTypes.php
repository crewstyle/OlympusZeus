<?php
namespace Takeatea\TeaThemeOptions;

/**
 * TEA CUSTOM POST TYPES
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
 * Tea Custom Post Types
 *
 * To get its own custom post types.
 *
 * @package Tea Theme Options
 * @subpackage Tea Custom Post Types
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 1.4.0
 *
 */
class TeaCustomPostTypes
{
    //Define protected vars
    protected $contents = array();
    protected $cpts = array();
    protected $includes = array();

    /**
     * Constructor.
     *
     * @since 1.4.0
     */
    public function __construct()
    {
        //Get registered CPTs
        $this->cpts = _get_option('tea_config_cpts', array());

        //Check params and if a master page already exists
        if (empty($this->cpts)) {
            return;
        }

        //Register global action hook
        add_action('init', array(&$this, '__buildMenuCustomPostType'));
    }


    //------------------------------------------------------------------------//

    /**
     * WORDPRESS USED HOOKS
     **/

    /**
     * Hook building menus for CPTS.
     *
     * @uses add_action()
     * @uses flush_rewrite_rules()
     * @uses register_post_type()
     *
     * @since 1.4.0
     */
    public function __buildMenuCustomPostType()
    {
        //Get custom post types
        $cpts = $this->cpts;

        //Check if we have some CPTS to initialize
        if (empty($cpts)) {
            return;
        }

        //Iterate on each cpt
        foreach ($cpts as $key => $cpt) {
            //Check if no master page is defined
            if (!isset($cpt['title']) || empty($cpt['title'])) {
                echo sprintf(__('Something went wrong in your parameters 
                    definition: no title defined for you <b>%s</b> 
                    custom post type. Please, try again by 
                    filling the form properly.', TTO_I18N), $key);
                continue;
            }

            //Get contents
            if (!empty($cpt['contents'])) {
                $this->contents[] = $cpt['slug'];
            }

            //Special case: define a post/page as title 
            //to edit default post/page component
            if (in_array($cpt['slug'], array('post', 'page'))) {
                continue;
            }

            //Check if post type already exists
            if (post_type_exists($cpt['slug'])) {
                continue;
            }

            //Treat arrays
            $sups = isset($cpt['supports']) && !empty($cpt['supports']) ? $cpt['supports'] : array('title', 'editor', 'thumbnail');
            $taxs = isset($cpt['taxonomies']) ? $cpt['taxonomies'] : array('category', 'post_tag');

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
                'has_archive' => isset($cpt['options']['has_archive']) && $cpt['options']['has_archive'] ? true : false,
                'rewrite' => isset($cpt['options']['rewrite']) ? $cpt['options']['rewrite'] : array('slug' => $cpt['slug']),
                'query_var' => isset($cpt['options']['query_var']) && $cpt['options']['query_var'] ? true : false,
                'menu_icon' => isset($cpt['menu_icon']) ? $cpt['menu_icon'] : '',
                'menu_position' => isset($cpt['menu_position']) ? $cpt['menu_position'] : 100,
                'permalink_epmask' => EP_PERMALINK,
                'supports' => $sups,
                'taxonomies' => $taxs
            );

            //Action to register
            register_post_type($cpt['slug'], $args);
        }

        //Get all admin details
        if (TTO_IS_ADMIN) {
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
     * @uses add_meta_box()
     *
     * @since 1.4.0
     */
    public function __fieldsCustomPostType()
    {
        //Get custom post types
        $cpts = $this->cpts;

        //Get all registered pages
        $includes = $this->getIncludes();

        //Check if we have some CPTS to initialize
        if (empty($this->contents) || empty($cpts)) {
            return;
        }

        //Get all authorized fields
        $authorized = TeaFields::getDefaults('fieldscpts');

        //Iterate on each cpt
        foreach ($this->contents as $cpt) {
            //Check if cpt exists or if its contents are empty
            if (!isset($cpts[$cpt]) || empty($cpts[$cpt]['contents'])) {
                continue;
            }

            //Do it works!
            foreach ($cpts[$cpt]['contents'] as $ctn) {
                //Define vars
                $type = $ctn['type'];

                //Check if we are authorized to use this field in CPTs
                if(!in_array($type, $authorized)) {
                    continue;
                }

                //Set vars
                $class = ucfirst($type);

                //Include class field
                if (!isset($includes[$type])) {
                    //Check if the class file exists
                    if (!class_exists($class)) {
                        echo sprintf(__('Something went wrong in your
                            parameters definition: the class <b>%s</b>
                            does not exist!', TTO_I18N), $class);
                        continue;
                    }

                    //Set the include
                    $this->setIncludes($type);
                }

                //Title
                $title = isset($ctn['title']) ? $ctn['title'] : __('Metabox', TTO_I18N);

                //Add meta box
                add_meta_box(
                    $cpt . '-meta-box-' . $ctn['id'],
                    $title,
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
     * @uses update_post_meta()
     *
     * @since 1.4.0
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

        //Get all registered pages
        $cpts = $this->cpts;

        //Check if we have some CPTS to initialize
        if (empty($this->contents) || empty($cpts)) {
            return false;
        }

        //Iterate on each cpt
        foreach ($this->contents as $cpt) {
            //Check if cpt exists or if its contents are empty
            if (!isset($cpts[$cpt]) || empty($cpts[$cpt]['contents'])) {
                continue;
            }

            //Do it works!
            foreach ($cpts[$cpt]['contents'] as $ctn) {
                //Register values
                $value = isset($_REQUEST[$ctn['id']]) ? $_REQUEST[$ctn['id']] : '';
                update_post_meta($post->ID, $cpt . '-' . $ctn['id'], $value);
            }
        }

        //Everything is allright!
        return true;
    }

    /**
     * MAIN FUNCTIONS
     **/

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
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check params and if a master page already exists
        if (empty($configs) || !isset($configs['slug']) || empty($configs['slug'])) {
            return;
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
     * @since 1.4.0
     */
    public function buildCPTs()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get all registered pages
        $cpts = $this->cpts;

        //Check params and if a master page already exists
        if (empty($cpts)) {
            return;
        }

        //Define cpt configurations
        _set_option('tea_config_cpts', $cpts);
    }

    /**
     * ACCESSORS
     **/

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since 1.4.0
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
     * @since 1.4.0
     */
    protected function setIncludes($context)
    {
        $this->includes[$context] = true;
    }
}
