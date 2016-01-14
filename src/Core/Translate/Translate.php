<?php

namespace crewstyle\OlympusZeus\Core\Translate;

use crewstyle\OlympusZeus\OlympusZeus;

/**
 * Translates typos.
 *
 * @package Olympus Zeus
 * @subpackage Core\Translate\Translate
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
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
     * @param string $content Contains typo to translate
     * @return Translate
     *
     * @since 4.0.0
     */
    public static function translate($content)
    {
        return __($content, OLZ_I18N);
    }
}
