<?php

namespace Takeatea\TeaThemeOptions;

/**
 * TEA CUSTOM TAXONOMIES
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * Tea Custom Taxonomies
 *
 * To get its own custom taxonomies.
 *
 * @package Tea Theme Options
 * @subpackage Tea Custom Taxonomies
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 2.0.0
 *
 */
class TeaCustomTaxonomies
{
    //Define protected vars
    protected $contents = array();
    protected $taxonomies = array();
    protected $includes = array();

    /**
     * Constructor.
     *
     * @since 1.4.2
     */
    public function __construct()
    {
        //Get registered taxonomies
        $this->taxonomies = TeaThemeOptions::getConfigs('customtaxonomies');

        //Check params and if a master page already exists
        if (empty($this->taxonomies)) {
            return;
        }

        //Register global action hook
        add_action('init', array(&$this, '__buildMenuCustomTaxonomy'));
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
     * @since 1.5.2.1
     */
    public function __buildMenuCustomTaxonomy()
    {
        //Get custom post types
        $taxonomies = $this->taxonomies;

        //Check if we have some taxonomies to initialize
        if (empty($taxonomies)) {
            return;
        }

        //Iterate on each cpt
        foreach ($taxonomies as $key => $tax) {
            //Check if no master page is defined
            if (!isset($tax['slug']) || empty($tax['slug'])) {
                echo sprintf(__('Something went wrong in your parameters
                    definition: no slug defined for your <b>%s</b>
                    custom taxonomy. Please, try again by
                    filling the form properly.', TTO_I18N), $key);
                continue;
            }

            //Check if no master page is defined
            if (!isset($tax['post_type']) || empty($tax['post_type'])) {
                echo sprintf(__('Something went wrong in your parameters
                    definition: no post type defined for your <b>%s</b>
                    custom taxonomy. Please, try again by
                    filling the form properly.', TTO_I18N), $key);
                continue;
            }

            //Define slug
            $slug = $tax['slug'];

            //Get contents
            if (!empty($tax['contents'])) {
                $this->contents[] = $slug;
            }

            //Special case: define a category/post_tag
            if (!in_array($slug, array('attachment', 'attachment_id', 'author', 'author_name', 'calendar', 'cat', 'category', 'category__and', 'category__in', 'category__not_in', 'category_name', 'comments_per_page', 'comments_popup', 'customize_messenger_channel', 'customized', 'cpage', 'day', 'debug', 'error', 'exact', 'feed', 'hour', 'link_category', 'm', 'minute', 'monthnum', 'more', 'name', 'nav_menu', 'nonce', 'nopaging', 'offset', 'order', 'orderby', 'p', 'page', 'page_id', 'paged', 'pagename', 'pb', 'perm', 'post', 'post__in', 'post__not_in', 'post_format', 'post_mime_type', 'post_status', 'post_tag', 'post_type', 'posts', 'posts_per_archive_page', 'posts_per_page', 'preview', 'robots', 's', 'search', 'second', 'sentence', 'showposts', 'static', 'subpost', 'subpost_id', 'tag', 'tag__and', 'tag__in', 'tag__not_in', 'tag_id', 'tag_slug__and', 'tag_slug__in', 'taxonomy', 'tb', 'term', 'theme', 'type', 'w', 'withcomments', 'withoutcomments', 'year')) && !taxonomy_exists($slug)) {
                //Build labels
                $title = !isset($tax['contents']) || empty($tax['title']) ? ucfirst($slug) : $tax['title'];
                $labels = array(
                    'name' => $title,
                    'singular_name' => $title,
                    'search_items' => sprintf(__('Search %s'), $title),
                    'popular_items' => sprintf(__('Popular %s'), $title),
                    'all_items' => sprintf(__('All %s'), $title),
                    'parent_item' => null,
                    'parent_item_colon' => null,
                    'edit_item' => sprintf(__('Edit %s'), $title),
                    'update_item' => sprintf(__('Update %s'), $title),
                    'add_new_item' => sprintf(__('Add New %s'), $title),
                    'new_item_name' => sprintf(__('New %s Name'), $title),
                    'separate_items_with_commas' => sprintf(__('Separate %s with commas'), $title),
                    'add_or_remove_items' => sprintf(__('Add or remove %s'), $title),
                    'choose_from_most_used' => sprintf(__('Choose from the most used %s'), $title),
                    'not_found' => sprintf(__('No %s found.'), $title),
                    'menu_name' => sprintf(__('%s'), $title),
                );
                $labels = array_merge($labels, $tax['labels']);

                //Check rewrite
                $tax['labels'] = $labels;
                $tax['rewrite'] = isset($tax['rewrite']) ? $tax['rewrite'] : $tax['title'];

                //Build args
                $args = array(
                    'hierarchical' => false,
                    'labels' => $labels,
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'query_var' => true,
                );
                $args = array_merge($args, $tax);

                //Action to register
                register_taxonomy($slug, $tax['post_type'], $args);
            }

            //Get all admin details
            if (TTO_IS_ADMIN) {
                //Display CPT custom fields
                add_action($slug.'_edit_form_fields', array(&$this, '__fieldsCustomTaxonomy'), 10, 1);
                add_action($slug.'_add_form_fields', array(&$this, '__fieldsCustomTaxonomy'), 10, 1);

                //Save CPT custom fields
                add_action('edited_'.$slug, array(&$this, '__saveCustomTaxonomy'), 10, 2);
                add_action('created_'.$slug, array(&$this, '__saveCustomTaxonomy'), 10, 2);

                //Flush all rewrite rules
                flush_rewrite_rules();
            }
        }
    }

    /**
     * Hook building custom fields for Taxonomies.
     *
     * @uses add_meta_box()
     * @param string|object $term Contain term used by function
     *
     * @since 1.4.3.1
     */
    public function __fieldsCustomTaxonomy($term)
    {
        //Check term
        if (!isset($term) || empty($term)) {
            return;
        }

        //Check admin
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get custom taxonomies
        $taxonomies = $this->taxonomies;

        //Get all registered pages
        $includes = $this->getIncludes();

        //Check if we have some taxonomies to initialize
        if (empty($this->contents) || empty($taxonomies)) {
            return;
        }

        //Get all authorized fields
        $unauthorized = TeaFields::getDefaults('unauthorized');
        $isobject = is_object($term);
        $tax = $isobject ? $term->taxonomy : $term;

        //Check if we have taxonomy
        if (!isset($taxonomies[$tax]) || empty($taxonomies[$tax])) {
            return;
        }

        //Check if we have taxonomy
        if (!isset($taxonomies[$tax]['contents']) || empty($taxonomies[$tax]['contents'])) {
            return;
        }

        //Display header wrap
        echo $isobject ? '<div class="tea_tax_wrap">' : '<div class="tea_tax_main">';

        //Iterate on each contents
        foreach ($taxonomies[$tax]['contents'] as $ctn) {
            //Define vars
            $type = $ctn['type'];

            //Check if we are authorized to use this field in CPTs
            if(in_array($type, $unauthorized)) {
                continue;
            }

            //Set vars
            $class = ucfirst($type);
            $class = "\Takeatea\TeaThemeOptions\Fields\\$class\\$class";

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

            //Set prefix
            $prefix = $isobject ? $term->term_id.'-'.$tax.'-' : $tax.'-';

            //Title
            $item = new $class();
            $item->templatePages($ctn, array(), $prefix);
        }

        //Display footer wrap
        echo '</div>';
    }

    /**
     * Hook building custom fields for CPTS.
     *
     * @uses update_post_meta()
     * @param number $term_id Contain term ID
     * @return number $return
     *
     * @since 2.0.0
     */
    public function __saveCustomTaxonomy($term_id)
    {
        //Check term
        if (!isset($term_id) || empty($term_id)) {
            return;
        }

        //Check admin
        if (!TTO_IS_ADMIN) {
            return $term_id;
        }

        //Get custom taxonomies
        $taxonomies = $this->taxonomies;

        //Get all registered pages
        $includes = $this->getIncludes();

        //Get all requests
        $request = isset($_REQUEST) ? $_REQUEST : array();

        //Check if we have some CPTS to initialize
        if (empty($this->contents) || empty($taxonomies) || empty($request)) {
            return $term_id;
        }

        //Check integrity
        if (!isset($request['taxonomy'])) {
            return $term_id;
        }

        //Get current saved taxonomy
        $tax = $request['taxonomy'];

        //Check if tax exists or if its contents are empty
        if (!isset($taxonomies[$tax]) || empty($taxonomies[$tax]['contents'])) {
            continue;
        }

        //Do it works!
        foreach ($taxonomies[$tax]['contents'] as $ctn) {
            //Register values
            $value = isset($request[$ctn['id']]) ? $request[$ctn['id']] : '';
            update_option($term_id.'-'.$tax.'-'.$ctn['id'], $value);
        }

        //Everything is allright!
        return true;
    }

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Add a Taxo to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 1.4.2
     */
    public function addTaxonomy($configs = array(), $contents = array())
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
        $this->taxonomies[$slug] = $configs;
        $this->taxonomies[$slug]['contents'] = $contents;
    }

    /**
     * Build all CPTs to the theme options panel.
     *
     * @since 1.4.2
     */
    public function buildTaxonomies()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get all registered pages
        $taxonomies = $this->taxonomies;

        //Check params and if a master page already exists
        if (empty($taxonomies)) {
            return;
        }

        //Define cpt configurations
        TeaThemeOptions::setConfigs('customtaxonomies', $taxonomies);
    }

    /**
     * ACCESSORS
     **/

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since 1.4.2
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
     * @since 1.4.2
     */
    protected function setIncludes($context)
    {
        //Define value
        $this->includes[$context] = true;
    }
}
