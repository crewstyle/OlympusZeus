<?php

namespace crewstyle\OlympusZeus\Plugins\Search;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Plugins\Search\SearchElastica;

/**
 * Works with Search.
 *
 * @package Olympus Zeus
 * @subpackage Plugins\Search\SearchEngine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class SearchEngine extends SearchElastica
{
    /**
     * @var boolean
     */
    protected $template = false;

    /**
     * Constructor.
     *
     * @param array $configs Contains all Search Engine configurations
     * @param boolean $hook Define if we have to use hooks or not
     *
     * @since 4.0.0
     */
    public function __construct($configs, $hook)
    {
        //Initialize Elastica
        $this->setConfig($configs);

        //Check index
        if ($hook && isset($configs['status']) && 200 == $configs['status']) {
            //Add WP Hooks
            if (OLZ_ISADMIN) {
                add_action('delete_post',       array(&$this, 'hookDeleteItem'));
                add_action('trash_post',        array(&$this, 'hookDeleteItem'));
                add_action('save_post',         array(&$this, 'hookSaveItem'));
            }
            else {
                $this->template = isset($ctn['template']) && 'yes' == $ctn['template'] ? true : false;

                add_action('pre_get_posts',     array(&$this, 'hookSearchProcess'), 500, 2);
                add_filter('the_posts',         array(&$this, 'hookSearchResults'));
                add_action('template_redirect', array(&$this, 'hookSearchTemplate'));
            }
        }
    }

    /**
     * Hook on deleting post.
     *
     * @param number $post_id Contains post ID
     *
     * @since 4.0.0
     */
    public function hookDeleteItem($post_id)
    {
        //Check post param
        if (is_object($post_id)) {
            //Got the WP post object with all datas
            $post = $post_id;
        }
        else {
            //Got only the post ID, so we need to retrieve the entire object
            $post = get_post($post_id);
        }

        //Maybe we need to delete post?
        $this->postDelete($post);
    }

    /**
     * Hook on saving post.
     *
     * @param number $post_id Contains post ID
     *
     * @since 4.0.0
     */
    public function hookSaveItem($post_id)
    {
        //Check post param
        if (is_object($post_id)) {
            //Got the WP post object with all datas
            $post = $post_id;
        }
        else {
            //Got only the post ID, so we need to retrieve the entire object
            $post = get_post($post_id);
        }

        //Do not need to update Elastica Client on revisions and autosaves
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) {
            return;
        }

        //Maybe we need to delete post?
        if ('trash' == $post->post_status) {
            $this->postDelete($post);
        }
        //Or simply update post?
        else if ('publish' == $post->post_status) {
            $this->postUpdate($post);
        }
    }

    /**
     * Hook on search.
     *
     * @param object $wp_query Contains query post sent by WP core
     *
     * @since 4.0.0
     */
    public function hookSearchProcess($wp_query)
    {
        //Check page
        if (OLZ_ISADMIN || !$wp_query->is_main_query() || !is_search()) {
            return;
        }

        //Get wp_query nulled
        $wp_query->posts = null;
        unset($wp_query->query_vars['author']);
        unset($wp_query->query_vars['title']);
        unset($wp_query->query_vars['content']);
    }

    /**
     * Hook on search.
     *
     * @param array $posts Contains all posts sent by WP core
     * @return array $posts Send an empty array
     *
     * @since 4.0.0
     */
    public function hookSearchResults($posts)
    {
        //Check page
        if (OLZ_ISADMIN || !is_search() || !$this->template) {
            return $posts;
        }

        //Return nothing to let the template do everything
        return array();
    }

    /**
     * Hook on search.
     *
     * @since 4.0.0
     */
    public function hookSearchTemplate()
    {
        //Check page
        if (OLZ_ISADMIN || !is_search() || !$this->template) {
            return;
        }

        //Display template
        OlympusZeus::getRender('plugins/fields/search_results.html.twig', array(
            'results' => $this->searchContents(),
            't_see_all' => OlympusZeus::__('See all'),
            't_read_more' => OlympusZeus::__('Read more'),
            't_date' => OlympusZeus::__('By <a href="%s" itemprop="author">%s</a> on 
                <time datetime="%s" itemprop="datePublished">%s</time>'),
            't_apologies' => OlympusZeus::__('Apologies, but no results were found for the requested archive. 
                Perhaps searching will help find a related post.'),
        ));

        exit;
    }
}
