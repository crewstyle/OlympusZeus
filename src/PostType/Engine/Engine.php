<?php

namespace crewstyle\TeaThemeOptions\PostType\Engine;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\PostType\Hook\Hook;

/**
 * TTO POSTTYPE ENGINE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO PostType Engine
 *
 * Class used to work with PostType Engine.
 *
 * @package Tea Theme Options
 * @subpackage PostType\Engine\Engine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.2.0
 *
 */
class Engine
{
    /**
     * @var array
     */
    protected $contents = array();

    /**
     * @var Hook
     */
    protected $hook = null;

    /**
     * @var string
     */
    protected static $index = 'posttype';

    /**
     * @var array
     */
    protected static $modes = array('list', 'edit', 'delete', 'fields');

    /**
     * @var string
     */
    protected static $permalink = '%SLUG%-tea-to-structure';

    /**
     * @var array
     */
    protected $posttypes = array();

    /**
     * @var boolean
     */
    protected $registered = false;

    /**
     * Constructor.
     *
     * @since 3.2.0
     */
    public function __construct()
    {
        //Instanciate Hook
        $this->hook = new Hook();

        //Add WP Hooks
        if (!TTO_IS_ADMIN) {
            $this->posttypes = TeaThemeOptions::getConfigs(self::getIndex());
            $this->hook->setPostTypes($this->posttypes);
            $this->registered = true;
        }
    }

    /**
     * Add a post type to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 3.1.0
     */
    public function addPostType($configs = array(), $contents = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we are in admin panel
        if (empty($configs) || !isset($configs['slug']) || empty($configs['slug'])) {
            return;
        }

        //Define the slug
        $configs['slug'] = TeaThemeOptions::getUrlize($configs['slug']);
        $slug = $configs['slug'];

        //Check if slug has already been registered
        if (isset($this->posttypes[$slug]) && !empty($this->posttypes[$slug])) {
            return;
        }

        //Define cpt configurations
        $this->posttypes[$slug] = $configs;
        $this->posttypes[$slug]['contents'] = $contents;
    }

    /**
     * Build post types.
     *
     * @uses add_action()
     *
     * @since 3.0.0
     */
    public function buildPostTypes()
    {
        //Admin panel
        if (!TTO_IS_ADMIN || $this->registered) {
            return;
        }

        //Check if we are in admin panel
        if (empty($this->posttypes)) {
            return;
        }

        //Add WP Hooks
        $this->hook->setPostTypes($this->posttypes);
    }

    /**
     * Get index.
     *
     * @return string $index Post type index
     *
     * @since 3.0.0
     */
    public static function getIndex()
    {
        return (string) self::$index;
    }

    /**
     * Get modes.
     *
     * @return array $modes Post type modes
     *
     * @since 3.0.0
     */
    public static function getModes()
    {
        return (array) self::$modes;
    }

    /**
     * Get permalink.
     *
     * @return string $permalink Post type permalink
     *
     * @since 3.0.0
     */
    public static function getPermalink()
    {
        return (string) self::$permalink;
    }

    /**
     * Add a post type to the theme options panel.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     * @param boolean $replace Define wether to replace or not slug
     *
     * @since 3.0.0
     */
    public function makeCreate($request = array(), $replace = false)
    {
        /*//Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check request
        if (empty($request) || !isset($request['slug']) || empty($request['slug'])) {
            return;
        }

        $posttype = array();
        $content = $request;
        $slug = $content['slug'];

        //Special case: define a post/page as title
        //to edit default post/page component
        if (in_array($slug, array('post', 'page'))) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                    definition: your slug cannot be <b>post</b> or <b>page</b>. 
                    Please, try again by filling the form properly.')
            );

            return;
        }

        //Check if post type already exists
        if (post_type_exists($slug)) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                    definition: your slug already exists in your WordPress website. 
                    Please, try again by filling the form properly.')
            );

            return;
        }

        //Get slug
        $posttype['slug'] = $slug;

        //Check title
        if (!isset($content['name']) || empty($content['name'])) {
            TeaThemeOptions::notify('error',
                sprintf(TeaThemeOptions::__('Something went wrong in your parameters
                    definition: no plural title defined for your <b>%s</b> post type. 
                    Please, try again by filling the form properly.'), $slug)
            );

            return;
        }

        //Register slug contents
        //if (!empty($content['contents'])) {
        //    $this->contents[] = $slug;
        //}

        //Check display
        //$posttype['display'] = isset($content['display']) ? $content['display'] : 'normal';

        //Build labels
        $posttype['labels'] = $this->defineLabels($content);
        $posttype['labels']['supports'] = array();
        $posttype['labels']['taxonomies'] = array();

        //Build args
        $posttype['args'] = $this->defineArgs($content);

        //Build supports
        foreach ($content['supports'] as $k => $v) {
            if ('1' != $v) {
                continue;
            }

            $posttype['labels']['supports'][] = $k;
        }

        //Build taxonomies
        foreach ($content['taxonomies'] as $k => $v) {
            if ('1' != $v) {
                continue;
            }

            $posttype['labels']['taxonomies'][] = $k;
        }

        //Build permalink
        $posttype['permalink'] = str_replace('%SLUG%', $posttype['slug'], self::getPermalink());

        //Get global post types
        $index = self::getIndex();
        $pts = TeaThemeOptions::getConfigs($index);

        if ($replace) {
            $news = array();

            //Set new post type
            foreach($pts as $pt) {
                $news[$posttype['slug']] = $pt['slug'] != $posttype['slug'] ? $pt : $posttype;
            }

            //Update global post types
            TeaThemeOptions::setConfigs($index, $news);
        }
        else {
            //Set new post type
            if (!isset($pts[$posttype['slug']])) {
                $pts[$posttype['slug']] = $posttype;
            }

            //Update global post types
            TeaThemeOptions::setConfigs($index, $pts);
        }*/
    }

    /**
     * Add a post type to the theme options panel.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeEdit($request = array())
    {
        /*//Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->makeCreate($request, true);*/
    }

    /**
     * Add a post type to the theme options panel.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeDelete($request = array())
    {
        /*//Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we are in admin panel
        if (empty($request) || !isset($request['slug']) || empty($request['slug'])) {
            return;
        }

        $index = self::getIndex();
        $slug = $request['slug'];
        $confirm = isset($request['confirm']) && 'delete' === $request['confirm'] ? true : false;

        //Get global post types
        $pts = TeaThemeOptions::getConfigs($index);
        $news = array();

        //Set new post type
        foreach($pts as $pt) {
            if ($slug === $pt['slug']) {
                continue;
            }

            $news[] = $pt;
        }

        //Update global post types
        TeaThemeOptions::setConfigs($index, $news);

        //Keep or delete contents
        if ($confirm) {
            //Delete posts'Post type from DB
            global $wpdb;
            $query = "DELETE FROM " . $wpdb->posts . " WHERE post_type='" . $slug . "'";
            $wpdb->query($query);
        }*/
    }
}
