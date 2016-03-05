<?php

namespace crewstyle\OlympusZeus\Controllers;

/**
 * Translates typos.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Translate
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Translate
{
    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        //Load WordPress language file if exists
        $locale = apply_filters('theme_locale', get_locale(), OLZ_I18N);
        load_textdomain(OLZ_I18N, OLZ_PATH.'/../languages/'.$locale.'.mo');
    }

    /**
     * Translate typo.
     *
     * @param string $content
     * @param array $args
     * @return Translate
     *
     * @since 5.0.0
     */
    public static function t($content)
    {
        return __($content, OLZ_I18N);
    }
}
