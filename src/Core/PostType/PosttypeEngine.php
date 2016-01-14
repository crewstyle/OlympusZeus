<?php

namespace crewstyle\OlympusZeus\Core\Posttype;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Posttype\PosttypeHook;

/**
 * Works with Posttype.
 *
 * @package Olympus Zeus
 * @subpackage Core\Posttype\PosttypeEngine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class PosttypeEngine
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
    protected static $permalink = '%SLUG%-olz-structure';

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
     * @since 4.0.0
     */
    public function __construct()
    {
        //Instanciate Hook
        $this->hook = new PosttypeHook();

        //Add WP Hooks
        if (!OLZ_ISADMIN) {
            $this->posttypes = OlympusZeus::getConfigs(self::getIndex());
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
     * @since 4.0.0
     */
    public function addPostType($configs = array())
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check if we are in admin panel
        if (empty($configs) || !isset($configs['slug']) || empty($configs['slug'])) {
            return;
        }

        //Define the slug
        $configs['slug'] = OlympusZeus::getUrlize($configs['slug']);
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
     * @since 4.0.0
     */
    public function buildPostTypes()
    {
        //Admin panel
        if (!OLZ_ISADMIN || $this->registered) {
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
