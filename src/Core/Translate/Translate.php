<?php

namespace crewstyle\TeaThemeOptions\Core\Translate;

use crewstyle\TeaThemeOptions\TeaThemeOptions;

/**
 * TTO TRANSLATE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Translate
 *
 * Class used to translate typos.
 *
 * @package Tea Theme Options
 * @subpackage Core\Translate\Translate
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Translate
{
    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Translate typo.
     *
     * @param string $content Contains typo to translate
     * @return Translate
     *
     * @since 3.0.0
     */
    public static function __($content)
    {
        return __($content, TTO_I18N);
    }
}
