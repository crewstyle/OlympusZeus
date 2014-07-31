<?php
namespace Takeatea\TeaThemeOptions;

use Takeatea\TeaThemeOptions\Fields\Network;
use Takeatea\TeaThemeOptions\TeaCustomPostTypes;
use Takeatea\TeaThemeOptions\TeaElasticsearch;
use Takeatea\TeaThemeOptions\TeaPages;

/**
 * TEA THEME OPTIONS
 *
 * Plugin Name: Tea Theme Options
 * Version: 1.4.1
 * Snippet URI: http://git.tools.takeatea.com/crewstyle/tea_theme_options
 * Description: The Tea Theme Options (or "Tea TO") allows you to easily add 
 * professional looking theme options panels to your WordPress theme.
 * Author: Achraf Chouk
 * Author URI: http://takeatea.com/
 * License: GPL v3
 *
 * Copyright (C) 2014, Achraf Chouk - ach@takeatea.com
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


//----------------------------------------------------------------------------//

include(__DIR__ . '/vendor/autoload.php');

//----------------------------------------------------------------------------//

//The current version
defined('TTO_IS_ADMIN')     or define('TTO_IS_ADMIN', is_admin());
//The current version
defined('TTO_VERSION')      or define('TTO_VERSION', '1.4.1');
//The i18n language code
defined('TTO_I18N')         or define('TTO_I18N', 'tea_theme_options');
//The transient expiration duration
defined('TTO_DURATION')     or define('TTO_DURATION', 86400);
//The transient expiration duration
defined('TTO_LOCAL')        or define('TTO_LOCAL', get_bloginfo('language'));
//The URI
defined('TTO_URI')          or define('TTO_URI', get_template_directory_uri() . '/' . basename(dirname(__FILE__)));
//The path
defined('TTO_PATH')         or define('TTO_PATH', __DIR__);
//The nonce ajax value
defined('TTO_NONCE')        or define('TTO_NONCE', 'tea-ajax-nonce');

//----------------------------------------------------------------------------//

/**
 * Tea Theme Option master class.
 *
 * To get its own settings, define all functions used to build custom pages and 
 * custom post types.
 *
 * @package Tea Theme Options
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 1.4.0
 *
 * @todo Special field:     Typeahead
 * @todo Shortcodes panel:  Youtube, Vimeo, Dailymotion, Google Maps, 
 *                          Google Adsense, Related posts, Private content, 
 *                          RSS Feed, Embed PDF, Price table, Carousel, Icons
 *
 */
class TeaThemeOptions
{
    //Define protected vars
    protected $pages = null;
    protected $customposttypes = null;
    protected $canbuildpages = false;
    protected $canbuildcpts = false;
    protected $elasticsearch = null;

    /**
     * Constructor.
     *
     * @uses add_filter()
     * @uses load_plugin_textdomain()
     * @uses wp_next_scheduled()
     * @uses wp_schedule_event()
     * @param string $identifier Define the main slug
     * @param boolean $connect Define if we can display connections page
     * @param boolean $elastic Define if we can display elasticsearch page
     *
     * @since 1.4.0
     */
    public function __construct($identifier = 'tea_theme_options', $connect = true, $elastic = true)
    {
        //Admin panel
        if (TTO_IS_ADMIN) {
            //i18n
            load_plugin_textdomain(TTO_I18N, false, TTO_PATH . '/languages');

            //Page component
            $this->pages = new TeaPages($identifier, $connect, $elastic);
        }

        //Define custom schedule
        if (!wp_next_scheduled('tea_task_schedule')) {
            wp_schedule_event(time(), 'hourly', 'tea_task_schedule');
        }

        //Register custom schedule filter
        add_filter('tea_task_schedule', array(&$this, '__cronSchedules'));

        //CPT component
        $this->customposttypes = new TeaCustomPostTypes();

        //Elasticsearch component
        $this->elasticsearch = new TeaElasticsearch();
    }


    //------------------------------------------------------------------------//

    /**
     * WORDPRESS USED HOOKS
     **/

    /**
     * Display a warning message on the admin panel.
     *
     * @since 1.4.0
     * @todo: make social connection with http://teato.me
     */
    public function __cronSchedules()
    {
        //Make the magic
        $field = new Network();
        $field->updateNetworks();
    }

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Add a page to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    public function addPage($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Add page
        $this->pages->addPage($configs, $contents);
        $this->canbuildpages = true;
    }

    /**
     * Register menus.
     *
     * @uses add_action()
     *
     * @since 1.4.0
     */
    public function buildPages()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we got pages
        if (!$this->canbuildpages) {
            return;
        }

        //Build menus
        $this->pages->buildPages();
    }

    /**
     * Add a CPT to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    public function addCPT($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Add page
        $this->customposttypes->addCPT($configs, $contents);
        $this->canbuildcpts = true;
    }

    /**
     * Register menus.
     *
     * @uses add_action()
     *
     * @since 1.4.0
     */
    public function buildCPTs()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we got cpts
        if (!$this->canbuildcpts) {
            return;
        }

        //Build menus
        $this->customposttypes->buildCPTs();
    }

    /**
     * Get elasticsearch.
     *
     * @return object $elasticearch
     *
     * @since 1.4.0
     */
    public function getElasticsearch()
    {
        //Return value
        return $this->elasticsearch;
    }
}

/**
 * Set a value into options
 *
 * @since 1.4.0
 */
function _del_option($option, $transient = false)
{
    //If a transient is asked...
    if ($transient) {
        //Delete the transient
        delete_transient($option);
    }

    //Delete value from DB
    delete_option($option);
}

/**
 * Return a value from options
 *
 * @since 1.4.0
 */
function _get_option($option, $default = '', $transient = false)
{
    //If a transient is asked...
    if ($transient) {
        //Get value from transient
        $value = get_transient($option);

        if (false === $value) {
            //Get it from DB
            $value = get_option($option);

            //Put the default value if not
            $value = false === $value ? $default : $value;

            //Set the transient for this value
            set_transient($option, $value, TTO_DURATION);
        }
    }
    //Else...
    else {
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
 * @since 1.4.0
 */
function _set_option($option, $value, $transient = false)
{
    //If a transient is asked...
    if ($transient) {
        //Set the transient for this value
        set_transient($option, $value, TTO_DURATION);
    }

    //Set value into DB
    update_option($option, $value);
}
