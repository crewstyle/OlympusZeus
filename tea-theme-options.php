<?php
/**
 * Tea TO backend functions and definitions
 * 
 * @package TakeaTea
 * @subpackage Tea Theme Options
 *
 * Plugin Name: Tea Theme Options
 * Version: 1.3.0.1
 * Snippet URI: https://github.com/Takeatea/tea_theme_options
 * Description: The Tea Theme Options (or "Tea TO") allows you to easily add professional looking theme options panels to your WordPress theme.
 * Author: Achraf Chouk
 * Author URI: http://takeatea.com/
 * License: GPL v3
 *
 * Tea Theme Options Plugin
 * Copyright (C) 2013, Achraf Chouk - ach@takeatea.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

//Usefull definitions for the Tea Theme Options
defined('TTO_VERSION')      or define('TTO_VERSION', '1.3.0.1');
defined('TTO_I18N')         or define('TTO_I18N', 'tea_theme_options');
defined('TTO_DURATION')     or define('TTO_DURATION', 86400);
defined('TTO_URI')          or define('TTO_URI', TEMPLATE_DIR_URI . '/' . basename(dirname(__FILE__)) . '/');
defined('TTO_PATH')         or define('TTO_PATH', TEMPLATE_DIR . '/' . basename(dirname(__FILE__)) . '/');
defined('TTO_ACTION')       or define('TTO_ACTION', 'tea_json_options');
defined('TTO_NONCE')        or define('TTO_NONCE', 'tea-ajax-nonce');


//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Theme Option page.
 *
 * To get its own settings
 *
 * @since Tea Theme Options 1.3.0
 * @todo Special field:     Typeahead, Date, Geolocalisation
 * @todo Shortcodes panel:  Youtube, Vimeo, Dailymotion, Google Maps, Google Adsense,
 *                          Related posts, Private content, RSS Feed, Embed PDF,
 *                          Price table, Carousel, Icons
 */
class Tea_Theme_Options
{
    //Define protected vars
    protected $is_admin;
    protected $pages = null;
    protected $customposttypes = null;

    /**
     * Constructor.
     *
     * @uses add_filter()
     * @uses load_plugin_textdomain()
     * @uses register_activation_hook()
     * @uses register_uninstall_hook()
     * @uses wp_next_scheduled()
     * @uses wp_schedule_event()
     * @param string $identifier Define the plugin main slug
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __construct($identifier = 'tea_theme_options')
    {
        //Check if we are in admin panel
        $this->setIsAdmin();
        $is_admin = $this->getIsAdmin();

        //Admin panel
        if ($is_admin)
        {
            //i18n
            load_plugin_textdomain(TTO_I18N, false, TTO_PATH . '/languages');

            //Page component
            require_once(TTO_PATH . 'classes/class-tea-pages.php');
            $this->pages = new Tea_Pages($identifier);
        }

        //Define custom schedule
        if (!wp_next_scheduled('tea_task_schedule'))
        {
            wp_schedule_event(time(), 'hourly', 'tea_task_schedule');
        }

        //Register custom schedule filter
        add_filter('tea_task_schedule', array(&$this, '__cronSchedules'));

        //Page component
        require_once(TTO_PATH . 'classes/class-tea-custom-post-types.php');
        $this->customposttypes = new Tea_Custom_Post_Types($is_admin);
    }


    //--------------------------------------------------------------------------//

    /**
     * WORDPRESS USED HOOKS
     **/

    /**
     * Display a warning message on the admin panel.
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __cronSchedules()
    {
        //Require file
        require_once(TTO_PATH . 'classes/fields/network/class-tea-fields-network.php');

        //Make the magic
        $field = new Tea_Fields_Network();
        $field->updateNetworks();
    }

    /**
     * CONTENTS METHODS
     **/

    /**
     * Add a page to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since Tea Theme Options 1.3.0
     */
    public function addPage($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!$this->getIsAdmin())
        {
            return false;
        }

        //Add page
        $this->pages->addPage($configs, $contents);
    }

    /**
     * Register menus.
     *
     * @uses add_action()
     *
     * @since Tea Theme Options 1.3.0
     */
    public function buildMenus()
    {
        //Check if we are in admin panel
        if (!$this->getIsAdmin())
        {
            return false;
        }

        //Build menus
        $this->pages->buildMenus();
    }

    /**
     * Add a CPT to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since Tea Theme Options 1.3.0
     */
    public function addCPT($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!$this->getIsAdmin())
        {
            return false;
        }

        //Add page
        $this->customposttypes->addCPT($configs, $contents);
    }

    /**
     * Register menus.
     *
     * @uses add_action()
     *
     * @since Tea Theme Options 1.3.0
     */
    public function buildCPTs()
    {
        //Check if we are in admin panel
        if (!$this->getIsAdmin())
        {
            return false;
        }

        //Build menus
        $this->customposttypes->buildCPTs();
    }

    /**
     * Get is_admin.
     *
     * @return bool $is_admin Define if we are in admin panel or not
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Set is_admin.
     *
     * @param bool $is_admin Define if we are in admin panel or not
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function setIsAdmin()
    {
        $this->is_admin = is_admin() ? true : false;
    }
}

/**
 * Set a value into options
 *
 * @since Tea Theme Options 1.3.0
 */
function _del_option($option, $transient = false)
{
    //If a transient is asked...
    if ($transient)
    {
        //Delete the transient
        delete_transient($option);
    }

    //Delete value from DB
    delete_option($option);
}

/**
 * Return a value from options
 *
 * @since Tea Theme Options 1.3.0
 */
function _get_option($option, $default = '', $transient = false)
{
    //If a transient is asked...
    if ($transient)
    {
        //Get value from transient
        $value = get_transient($option);

        if (false === $value)
        {
            //Get it from DB
            $value = get_option($option);

            //Put the default value if not
            $value = false === $value ? $default : $value;

            //Set the transient for this value
            set_transient($option, $value, TTO_DURATION);
        }
    }
    //Else...
    else
    {
        //Get value from DB
        $value = get_option($option);

        //Put the default value if not
        $value = false === $value ? $default : $value;
    }

    //Return value
    return $value;
}

/**
 * Set a value into options
 *
 * @since Tea Theme Options 1.3.0
 */
function _set_option($option, $value, $transient = false)
{
    //If a transient is asked...
    if ($transient)
    {
        //Set the transient for this value
        set_transient($option, $value, TTO_DURATION);
    }

    //Set value into DB
    update_option($option, $value);
}
