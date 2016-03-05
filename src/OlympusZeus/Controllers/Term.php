<?php

namespace crewstyle\OlympusZeus\Controllers;

use crewstyle\OlympusZeus\Models\Term as TermModel;
use crewstyle\OlympusZeus\Controllers\TermHook;
use crewstyle\OlympusZeus\Controllers\Notification;
use crewstyle\OlympusZeus\Controllers\Option;
use crewstyle\OlympusZeus\Controllers\Render;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Gets its own post type.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Term
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Term
{
    /**
     * @var array
     */
    protected static $existingTerms = ['attachment', 'attachment_id', 'author', 'author_name', 'calendar', 'cat', 'category', 'category__and', 'category__in', 'category__not_in', 'category_name', 'comments_per_page', 'comments_popup', 'customize_messenger_channel', 'customized', 'cpage', 'day', 'debug', 'error', 'exact', 'feed', 'hour', 'link_category', 'm', 'minute', 'monthnum', 'more', 'name', 'nav_menu', 'nonce', 'nopaging', 'offset', 'order', 'orderby', 'p', 'page', 'page_id', 'paged', 'pagename', 'pb', 'perm', 'post', 'post__in', 'post__not_in', 'post_format', 'post_mime_type', 'post_status', 'post_tag', 'post_type', 'posts', 'posts_per_archive_page', 'posts_per_page', 'preview', 'robots', 's', 'search', 'second', 'sentence', 'showposts', 'static', 'subpost', 'subpost_id', 'tag', 'tag__and', 'tag__in', 'tag__not_in', 'tag_id', 'tag_slug__and', 'tag_slug__in', 'taxonomy', 'tb', 'term', 'theme', 'type', 'w', 'withcomments', 'withoutcomments', 'year'];

    /**
     * @var TermModel
     */
    protected $term;

    /**
     * Constructor.
     *
     * @param string $slug
     * @param array $args
     * @param array $labels
     *
     * @since 5.0.0
     */
    public function __construct($slug, $posttype, $args, $labels)
    {
        if (empty($labels) || !isset($labels['plural'], $labels['singular']) || empty($labels['plural']) || empty($labels['singular'])) {
            Notification::error(Translate::t('Singular and/or plural term are missing in your labels definition.'));

            return;
        }

        if (empty($posttype)) {
            Notification::error(Translate::t('Term\'s post type is not defined.'));

            return;
        }

        $this->term = new TermModel();

        $slug = Render::urlize($slug);
        $args = array_merge($this->defaultArgs($slug, $args));
        $args['labels'] = array_merge($this->defaultLabels($labels['plural'], $labels['singular'], $args['hierarchical']), $labels);

        //Update vars
        $this->term->setSlug($slug);
        $this->term->setPosttype($posttype);
        $this->term->setArgs($args);

        //Hooks
        if (OLZ_ISADMIN) {
            add_filter('olz_template_footer_urls', function ($urls, $identifier) {
                return array_merge($urls, [
                    'terms' => [
                        'url' => admin_url('admin.php?page='.$identifier.'&do=olz-action&from=footer&make=terms'),
                        'label' => Translate::t('terms'),
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
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => ['slug' => $slug],
            'show_admin_column' => true,
            'show_ui' => true,

            'show_in_rest' => true,
            'rest_base' => $slug,
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ];
    }

    /**
     * Build labels.
     *
     * @param string $plural
     * @param string $singular
     * @param boolean $hierarchical
     * @return array $labels
     *
     * @since 5.0.0
     */
    protected function defaultLabels($plural, $singular, $hierarchical = true)
    {
        $labels = [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'all_items' => $plural,

            'search_items' => Translate::t('Search items'),
            'edit_item' => Translate::t('Edit item'),
            'update_item' => Translate::t('Update item'),
            'add_new_item' => Translate::t('Add new item'),
            'new_item_name' => Translate::t('New item'),
        ];

        if ($hierarchical) {
            $labels = array_merge($labels, [
                'parent_item' => Translate::t('Parent item'),
                'parent_item_colon' => Translate::t('Parent item colon'),
            ]);
        }
        else {
            $labels = array_merge($labels, [
                'parent_item' => null,
                'parent_item_colon' => null,

                'popular_items' => Translate::t('Popular items'),
                'separate_items_with_commas' => Translate::t('Separate items with commas'),
                'choose_from_most_used' => Translate::t('Chooose from the most item used'),

                'add_or_remove_items' => Translate::t('Add or remove items'),
                'not_found' => Translate::t('No item found'),
            ]);
        }

        return $labels;
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
            $slug = $this->term->getSlug();
            $posttype = $this->term->getPosttype();
            $args = $this->term->getArgs();

            $issingle = 'single' === $args['choice'] ? true : false;
            $addcustomfields = false;

            //Check datum
            if (empty($slug) || empty($posttype) || empty($args)) {
                return [];
            }

            //Check forbodden keywords and already existing terms
            if (in_array($slug, self::$existingTerms) || taxonomy_exists($slug)) {
                return [];
            }

            //Action to register
            register_taxonomy($slug, $posttype, $args);

            //Works on hook
            $hook = new TermHook($slug, $addcustomfields, $issingle);
            $this->term->setHook($hook);
        }, 10, 1);
    }
}
