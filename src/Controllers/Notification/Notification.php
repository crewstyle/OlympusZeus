<?php

namespace crewstyle\TeaThemeOptions\Controllers\Notification;

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
 * @subpackage Controllers\Notification\Notification
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Notification
{
    /**
     * @var string
     */
    public $content = '';

    /**
     * Constructor.
     *
     * @param string $content Define content to display
     *
     * @since 3.0.0
     */
    public function __construct($content = '')
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN || empty($content)) {
            return;
        }

        $this->content = $content;

        //Display or return it
        add_filter('tea_to_notice', array(&$this, 'hookNotice'), 10, 1);
    }

    /**
     * Hook special filter
     *
     * @return string $notice
     *
     * @since 3.0.0
     */
    public function hookNotice($notice) {
        return $notice . $this->content;
    }
}
