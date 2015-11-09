<?php

namespace crewstyle\TeaThemeOptions\Term\Engine;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Term\Hook\Hook;

/**
 * TTO TERM ENGINE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Term Engine
 *
 * Class used to work with Term Engine.
 *
 * @package Tea Theme Options
 * @subpackage Term\Engine\Engine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
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
    protected static $index = 'term';

    /**
     * @var string
     */
    protected static $prefix = '%TERM%-%SLUG%-';

    /**
     * @var array
     */
    protected $terms = array();

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        //Initialize all default configurations
        $this->terms = TeaThemeOptions::getConfigs(self::getIndex());
        $this->hook = new Hook();

        //Add WP Hooks
        if (!TTO_IS_ADMIN) {
            //$this->hook->setTerms($this->terms);
        }
    }

    /**
     * Add a term to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 3.0.0
     */
    public function addTerm($configs = array(), $contents = array())
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
        $slug = $configs['slug'];

        //Check if slug has already been registered
        if (isset($this->terms[$slug]) && !empty($this->terms[$slug])) {
            return;
        }

        //Update contents
        $configs['contents'] = $contents;

        //Define cpt configurations
        $this->terms[$slug] = $configs;
    }

    /**
     * Build terms.
     *
     * @uses add_action()
     *
     * @since 3.0.0
     */
    public function buildTerms()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we are in admin panel
        if (empty($this->terms)) {
            return;
        }

        //Add WP Hooks
        $this->hook->setTerms($this->terms);
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
     * Get prefix.
     *
     * @return string $prefix Post type prefix
     *
     * @since 3.0.0
     */
    public static function getPrefix()
    {
        return (string) self::$prefix;
    }
}
