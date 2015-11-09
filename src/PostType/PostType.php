<?php

namespace crewstyle\TeaThemeOptions\PostType;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\PostType\Engine\Engine;

/**
 * TTO POST TYPE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO PostType
 *
 * To get its own posttype.
 *
 * @package Tea Theme Options
 * @subpackage PostType\PostType
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class PostType
{
    /**
     * @var PostTypeEngine
     */
    protected $engine = null;

    /**
     * @var string
     */
    protected static $slug = '_posttype';

    /**
     * @var string
     */
    protected static $template = TTO_PATH.'/Resources/contents/posttype.php';

    /**
     * Constructor.
     *
     * @param boolean $enable Define if Post types engine is enabled
     * @param boolean $filters Define if we need to call filters
     *
     * @since 3.0.0
     */
    public function __construct($enable = true, $filters = true)
    {
        //Check post types engine
        if (!$enable) {
            return;
        }

        //Initialize search
        $this->engine = new Engine();

        //Hooks
        /*if ($filters) {
            add_filter('tea_to_menu_check', array(&$this, 'hookMenuCheck'), 10, 2);
            add_filter('tea_to_menu_init', array(&$this, 'hookMenuInit'), 10, 3);
            add_filter('tea_to_template_special', array(&$this, 'hookTemplateSpecial'), 10, 2);
        }*/
    }

    /**
     * Add a post type to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 3.0.0
     */
    public function addPostType($configs = array(), $contents = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->addPostType($configs, $contents);
    }

    /**
     * Register post types.
     *
     * @since 3.0.0
     */
    public function buildPostTypes()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->buildPostTypes();
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
        return (array) Engine::getModes();
    }

    /**
     * Get slug.
     *
     * @return string $slug Post type slug
     *
     * @since 3.0.0
     */
    public static function getSlug()
    {
        return (string) self::$slug;
    }

    /**
     * Get template.
     *
     * @return string $template Post type template
     *
     * @since 3.0.0
     */
    public static function getTemplate()
    {
        return (string) self::$template;
    }

    /**
     * Hook special filter
     *
     * @return array $tocheck
     *
     * @since 3.0.0
     */
    public function hookMenuCheck($tocheck, $identifier) {
        return array_merge($tocheck, array(
            $identifier . self::getSlug()
        ));
    }

    /**
     * Hook special filter
     *
     * @return array $contents
     *
     * @since 3.0.0
     */
    public function hookMenuInit($contents, $options, $canuser) {
        // Get post types engine page contents
        if (!$options['posttypes'] || !$canuser) {
            return $contents;
        }

        $slug = self::getSlug();

        //Get enabled modes
        $modes = self::getModes();
        $mode = isset($_GET['mode']) && in_array($_GET['mode'], $modes) ? $_GET['mode'] : 'list';

        include(self::getTemplate());

        $contents[] = array(
            'titles' => $titles,
            'details' => $details,
        );
        unset($titles, $details);

        return $contents;
    }

    /**
     * Hook special filter
     *
     * @return array $enabled
     *
     * @since 3.0.0
     */
    public function hookTemplateSpecial($enabled, $identifier) {
        $enabled[] = $identifier . self::getSlug();
        return $enabled;
    }

    /**
     * Actions to execute after updating or using the Post types Engine.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeActions($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get current user action
        $action = isset($request['action']) ? (string) $request['action'] : '';

        if ('tea-to-action' != $action) {
            return;
        }

        //Get the kind of action asked
        $for = isset($request['for']) ? (string) $request['for'] : '';

        //Check action to make
        if ('posttype' != $for) {
            return;
        }

        //Get action to make
        $make = isset($request['make']) ? $request['make'] : '';

        //Create post type
        if ('create' == $make) {
            $this->makeCreate($request);
        }
        //Edit post type
        else if ('edit' == $make) {
            $this->makeEdit($request);
        }
        //Delete post type
        else if ('delete' == $make) {
            $this->makeDelete($request);
        }
    }

    /**
     * Create a new Post Type.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeCreate($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->makeCreate($request);
    }

    /**
     * Edit a Post Type.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeEdit($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->makeEdit($request);
    }

    /**
     * Delete a Post Type.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeDelete($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->makeDelete($request);
    }
}
