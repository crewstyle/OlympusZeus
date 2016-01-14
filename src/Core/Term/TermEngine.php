<?php

namespace crewstyle\OlympusZeus\Core\Term;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Term\TermHook;

/**
 * Works with Term.
 *
 * @package Olympus Zeus
 * @subpackage Core\Term\TermEngine
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */
class TermEngine
{
    /**
     * @var TermHook
     */
    protected $termHook = null;

    /**
     * @var string
     */
    protected static $index = 'term';

    /**
     * @var string
     */
    protected static $prefix = '%TERM%-%SLUG%';

    /**
     * @var array
     */
    protected $terms = array();

    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        //Initialize all default configurations
        $this->terms = OlympusZeus::getConfigs(self::getIndex());
        $this->termHook = new TermHook();
    }

    /**
     * Add a term to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 4.0.0
     */
    public function addTerm($configs = array())
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
        if (isset($this->terms[$slug]) && !empty($this->terms[$slug])) {
            return;
        }

        //Define cpt configurations
        $this->terms[$slug] = $configs;
    }

    /**
     * Build terms.
     *
     * @uses add_action()
     *
     * @since 4.0.0
     */
    public function buildTerms()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check if we are in admin panel
        if (empty($this->terms)) {
            return;
        }

        //Add WP Hooks
        $this->termHook->setTerms($this->terms);
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
