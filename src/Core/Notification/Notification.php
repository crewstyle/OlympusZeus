<?php

namespace crewstyle\OlympusZeus\Core\Notification;

use crewstyle\OlympusZeus\OlympusZeus;

/**
 * Displays admin messages and notifications when its needed.
 *
 * @package Olympus Zeus
 * @subpackage Core\Notification\Notification
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
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
     * @since 4.0.0
     */
    public static function notify($type, $content = '')
    {
        //Check if we are in admin panel
        if (!OLZ_ISADMIN || empty($content)) {
            return;
        }

        //Display or return it
        add_filter('olz_template_notice', function ($notice) use ($type, $content) {
            $notice[] = array($type, $content);
            return $notice;
        }, 10, 1);
    }
}
