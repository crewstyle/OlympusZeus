<?php

namespace crewstyle\TeaThemeOptions\Core\PostType\Engine;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\PostType\Hook\Hook;

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
 * @subpackage Core\PostType\Engine\Engine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Engine
{
    /**
     * @var Hook
     */
    protected $hook = null;

    /**
     * @var string
     */
    protected static $index = 'posttypes';

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
     * @since 3.3.0
     */
    public function addPostType($configs = array())
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
}
