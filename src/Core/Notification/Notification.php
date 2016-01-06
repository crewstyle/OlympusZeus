<?php

namespace crewstyle\TeaThemeOptions\Core\Notification;

use crewstyle\TeaThemeOptions\TeaThemeOptions;

/**
 * TTO NOTIFICATION
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Notification
 *
 * Class used to display admin messages and notifications when its needed.
 *
 * @package Tea Theme Options
 * @subpackage Core\Notification\Notification
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Notification
{
    /**
     * Constructor.
     *
     * @since 3.3.0
     */
    public function __construct(){}

    /**
     * Constructor.
     *
     * @param string $type Define notice type to display
     * @param string $content Define content to display
     *
     * @since 3.3.0
     */
    public static function notify($type, $content = '')
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN || empty($content)) {
            return;
        }

        //Display or return it
        add_filter('tto_template_notice', function ($notice) use ($type, $content) {
            $notice[] = array($type, $content);
            return $notice;
        }, 10, 1);
    }
}
