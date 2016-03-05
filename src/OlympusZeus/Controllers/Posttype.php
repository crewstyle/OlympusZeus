<?php

namespace crewstyle\OlympusZeus\Controllers;

use crewstyle\OlympusZeus\Models\Posttype as PosttypeModel;
use crewstyle\OlympusZeus\Controllers\PosttypeHook;
use crewstyle\OlympusZeus\Controllers\Notification;
use crewstyle\OlympusZeus\Controllers\Option;
use crewstyle\OlympusZeus\Controllers\Render;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Gets its own post type.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Posttype
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Posttype
{
    /**
     * @var PosttypeModel
     */
    protected $posttype;

    /**
     * Constructor.
     *
     * @param string $slug
     * @param array $args
     * @param array $labels
     *
     * @since 5.0.0
     */
    public function __construct($slug, $args, $labels)
    {
        if (empty($labels) || !isset($labels['plural'], $labels['singular']) || empty($labels['plural']) || empty($labels['singular'])) {
            Notification::error(Translate::t('Singular and/or plural term are missing in your labels definition.'));

            return;
        }

        $this->posttype = new PosttypeModel();

        $slug = Render::urlize($slug);
        $args = array_merge($this->defaultArgs($slug, $args));
        $args['labels'] = array_merge($this->defaultLabels($labels['plural'], $labels['singular']), $labels);

        //Update vars
        $this->posttype->setSlug($slug);
        $this->posttype->setArgs($args);

        //Hooks
        if (OLZ_ISADMIN) {
            add_filter('olz_template_footer_urls', function ($urls, $identifier) {
                return array_merge($urls, [
                    'posttypes' => [
                        'url' => admin_url('admin.php?page='.$identifier.'&do=olz-action&from=footer&make=posttypes'),
                        'label' => Translate::t('post types'),
                    ]
                ]);
            }, 10, 2);
        }
    }

    /**
     * Build args.
     *
     * @param string $slug
     * @return array $args
     *
     * @since 5.0.0
     */
    protected function defaultArgs($slug)
    {
        return [
            'can_export' => true,
            'capability_type' => 'post',
            'description' => '',
            'exclude_from_search' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => '',
            'menu_position' => 100,
            'public' => false,
            'publicly_queryable' => false,

            'permalink_epmask' => EP_PERMALINK,
            'query_var' => true,
            'rewrite' => false,
            'show_in_menu' => true,
            'show_ui' => true,

            'supports' => [],
            'taxonomies' => [],

            'show_in_rest' => true,
            'rest_base' => $slug,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];
    }

    /**
     * Build labels.
     *
     * @param string $plural
     * @param string $singular
     * @return array $labels
     *
     * @since 5.0.0
     */
    protected function defaultLabels($plural, $singular)
    {
        return [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'all_items' => $plural,

            'add_new' => Translate::t('Add new'),
            'add_new_item' => Translate::t('Add new item'),
            'edit' => Translate::t('Edit'),
            'edit_item' => Translate::t('Edit item'),
            'new_item' => Translate::t('New item'),

            'not_found' => Translate::t('No item found'),
            'not_found_in_trash' => Translate::t('No item found in Trash'),
            'parent_item_colon' => Translate::t('Parent item'),
            'search_items' => Translate::t('Search items'),

            'view' => Translate::t('View'),
            'view_item' => Translate::t('View item'),
        ];
    }

    /**
     * Register post types.
     *
     * @since 5.0.0
     */
    protected function register()
    {
        add_action('init', function (){
            //Store details
            $slug = $this->posttype->getSlug();
            $args = $this->posttype->getArgs();

            //Special case: define a post/page as title
            //to edit default post/page component
            if (in_array($slug, ['post', 'page'])) {
                return [];
            }

            //Check if post type already exists
            if (post_type_exists($slug)) {
                return [];
            }

            //Action to register
            register_post_type($slug, $args);

            //Update post type
            $posttype = array_merge($posttype, $args);

            //Option
            $opt = str_replace('%SLUG%', $slug, '%SLUG%-olz-structure');

            //Get value
            $structure = Option::get($opt, '/%'.$slug.'%-%post_id%');

            //Change structure
            add_rewrite_tag('%'.$slug.'%', '([^/]+)', $slug.'=');
            add_permastruct($slug, $structure, false);

            //Works on hook
            $hook = new PosttypeHook($slug);
            $this->posttype->setHook($hook);
        }, 10, 1);
    }
}
