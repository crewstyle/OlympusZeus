<?php

namespace Takeatea\TeaThemeOptions;

/**
 * TEA ADMIN MESSAGE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * Tea Admin Message
 *
 * Class used to display admin messages and notifications when its needed.
 *
 * @package Tea Theme Options
 * @subpackage Tea Admin Message
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 1.5.2.14
 *
 */
class TeaAdminMessage
{
    //Define protected vars
    public $fields = array();

    /**
     * Constructor.
     *
     * @uses add_action()
     *
     * @since 1.5.0
     */
    public function __construct()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Register admin message action hook
        add_action('admin_notices', array(&$this, '__showFieldError'));
    }

    /**
     * Display admin message.
     *
     * @param string $content Define content to display
     *
     * @since 1.5.0
     */
    public static function __display($content = '')
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check content and display it
        if (!empty($content)) {
            include_once(TTO_PATH.'/Tpl/layouts/__layout_admin_message.tpl.php');
        }
    }

    /**
     * Display admin fields error.
     *
     * @since 1.5.0
     */
    public function __showFieldError()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check content and display it
        if (!empty($this->fields)) {
            //Get content
            $fields = $this->fields;
            $content = sprintf(
                __('Something went wrong in your
                parameters definition: no id(s) defined
                for the following field(s) %s', TTO_I18N),
                implode(', ', $fields)
            );

            //Display message
            include_once(TTO_PATH.'/Tpl/layouts/__layout_admin_message.tpl.php');
        }
    }

    /**
     * Add field error into admin message.
     *
     * @param string $content Define content to display
     *
     * @since 1.5.0
     */
    public function addFieldError($content = '')
    {
        //Check content
        if (!empty($content)) {
            $this->fields[] = $content;
        }
    }
}
