<?php

namespace Takeatea\TeaThemeOptions;

use Takeatea\TeaThemeOptions\Fields\Network\Network;

/**
 * TEA THEME OPTIONS
 *
 * Plugin Name: Tea Theme Options
 * Version: 1.5.2.16
 * Snippet URI: https://github.com/Takeatea/tea_theme_options
 * Read The Doc: http://tea-theme-options.readme.io/
 * Description: The Tea Theme Options (or "Tea TO") allows you to easily add
 * professional looking theme options panels to your WordPress theme.
 * It offers the best and easy way to create custom post types and
 * custom taxonomies.
 *
 * Author: Achraf Chouk
 * Author URI: http://takeatea.com/
 * License: The MIT License (MIT)
 *
 * The MIT License (MIT)
 *
 * Copyright (C) 2014, Achraf Chouk - ach@takeatea.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

//The context used to define if the PHP files can be executed
defined('TTO_CONTEXT')      or define('TTO_CONTEXT', 'tea-theme-options');
//The current version
defined('TTO_IS_ADMIN')     or define('TTO_IS_ADMIN', is_admin());
//The current version
defined('TTO_VERSION')      or define('TTO_VERSION', '1.5.2.16');
//The i18n language code
defined('TTO_I18N')         or define('TTO_I18N', 'tea_theme_options');
//The transient expiration duration
defined('TTO_DURATION')     or define('TTO_DURATION', 86400);
//The blog home url
defined('TTO_HOME')         or define('TTO_HOME', get_option('home'));
//The language blog
defined('TTO_LOCAL')        or define('TTO_LOCAL', get_bloginfo('language'));
//The URI
defined('TTO_URI')          or define('TTO_URI', get_template_directory_uri().'/vendor/takeatea/tea-theme-options');
//The path
defined('TTO_PATH')         or define('TTO_PATH', dirname(__FILE__));
//The wp-includes URI
defined('TTO_INC')          or define('TTO_INC', includes_url());
//The capabilities
defined('TTO_CAP')          or define('TTO_CAP', 'edit_posts');
//The custom capabilities
defined('TTO_CAP_MAX')      or define('TTO_CAP_MAX', 'manage_tea_theme_options');
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
 * @since 1.5.2.14
 *
 * @todo Special field:     Typeahead
 * @todo Shortcodes panel:  Youtube, Vimeo, Dailymotion, Embed PDF,
 * @todo Shortcodes panel:  Google Adsense, Related posts, Private content,
 * @todo Shortcodes panel:  RSS Feed, Price table, Carousel, Icons
 *
 */
class TeaThemeOptions
{
    //Define protected vars
    protected $pages = null;
    protected $customposttypes = null;
    protected $customtaxonomies = null;
    protected $canbuildpages = false;
    protected $canbuildcpts = false;
    protected $canbuildtaxonomies = false;
    protected $elasticsearch = null;

    /**
     * Constructor.
     *
     * @uses add_filter()
     * @uses load_textdomain()
     * @uses wp_next_scheduled()
     * @uses wp_schedule_event()
     * @param string $identifier Define the main slug
     * @param array $options Contains all options to dis/enable
     * @internal param bool $connect Define if we can display connections page
     * @internal param bool $elastic Define if we can display elasticsearch page
     *
     * @since 1.5.2.8
     */
    public function __construct($identifier = 'tea_theme_options', $options = array())
    {
        //Admin panel
        if (TTO_IS_ADMIN) {
            //i18n
            $locale = apply_filters('theme_locale', get_locale(), TTO_I18N);
            load_textdomain(TTO_I18N, TTO_PATH.'/languages/'.$locale.'.mo');

            //Build options
            $opts = array(
                'connect'   => isset($options['connect'])   ? $options['connect']   : true,
                'elastic'   => isset($options['elastic'])   ? $options['elastic']   : true,
                'notifs'    => isset($options['notifs'])    ? $options['notifs']    : true,
            );

            //Page component
            $this->pages = new TeaPages($identifier, $opts);
        }

        //Define custom schedule
        if (!wp_next_scheduled('tea_task_schedule')) {
            wp_schedule_event(time(), 'hourly', 'tea_task_schedule');
        }

        //Register custom schedule filter
        add_filter('tea_task_schedule', array(&$this, '__cronSchedules'));

        //CPT component
        $this->customposttypes = new TeaCustomPostTypes();

        //Taxonomies component
        $this->customtaxonomies = new TeaCustomTaxonomies();

        //Elasticsearch component
        $this->elasticsearch = new TeaElasticsearch();

        //Add custom css to login page
        add_action('login_head', array(&$this, '__loginPage'));
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
     * Display CSS form login page.
     *
     * @since 1.4.3.5
     */
    public function __loginPage()
    {
        echo '<link href="'.TTO_URI.'/assets/css/teato.login.css" rel="stylesheet" type="text/css" />';
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
     * Add a CPT to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 1.4.0
     */
    public function addTaxonomy($configs = array(), $contents = array())
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Add page
        $this->customtaxonomies->addTaxonomy($configs, $contents);
        $this->canbuildtaxonomies = true;
    }

    /**
     * Register taxonomies.
     *
     * @uses add_action()
     *
     * @since 1.4.0
     */
    public function buildTaxonomies()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if we got cpts
        if (!$this->canbuildtaxonomies) {
            return;
        }

        //Build menus
        $this->customtaxonomies->buildTaxonomies();
    }

    /**
     * Get configs.
     *
     * @param string $option Define the option asked
     * @return array $configs Define configurations
     *
     * @since 1.4.3.3
     */
    public static function getConfigs($option = 'capabilities')
    {
        //Get datas from DB
        $configs = TeaThemeOptions::get_option('tea_to_configs', array());

        //Check if data is available
        $return = isset($configs[$option]) ? $configs[$option] : array();
        $return = !is_array($return) ? array($return) : $return;

        //Return value
        return $return;
    }

    /**
     * Set configs.
     *
     * @param string $option Define the option to update
     * @param array|integer|string $value Define the value
     *
     * @since 1.4.3.3
     */
    public static function setConfigs($option = 'capabilities', $value = 'manage_tea_theme_options')
    {
        //Get datas from DB
        $configs = TeaThemeOptions::get_option('tea_to_configs', array());

        //Define the data
        $configs[$option] = $value;

        //Update DB
        TeaThemeOptions::set_option('tea_to_configs', $configs);
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

    /**
     * STATICS
     **/

    /**
     * Return the access token got from teato.me
     *
     * @param integer $user_id The user ID stored in DB for the connection
     * @return array $array Contains all data for the connection
     *
     * @since 1.5.0
     */
    public static function access_token($user_id = 0)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return array();
        }

        //Check user ID
        if ($user_id) {
            //Get datas
            $tokens = TeaThemeOptions::getConfigs('tokens');

            //Check integrity
            if (/*empty($userid) || */empty($tokens)) {
                return array();
            }

            //Get user token
            $single = isset($tokens[$user_id]) ? $tokens[$user_id] : '';
            $chunks = explode('.', $single);

            //Check chunks
            if (empty($chunks[0]) || empty($chunks[1])) {
                return array();
            }

            //Define token
            $token = $chunks[0].'.'.$chunks[1];
        }
        else {
            //Get stored blog token
            $token = TeaThemeOptions::getConfigs('token_blog');

            //Check the token
            if (empty($token)) {
                return array();
            }
        }

        //Return datas
        return array(
            'secret' => $token[0],
            'external_user_id' => $user_id,
        );
    }

    /**
     * Set a value into options
     *
     * @param string $option Contains option name to delete from DB
     * @param integer $transient Define if we use transiant API or not
     *
     * @since 1.5.0
     */
    public static function del_option($option, $transient = 0)
    {
        //If a transient is asked...
        if (!empty($transient)) {
            //Delete the transient
            delete_transient($option);
        }

        //Delete value from DB
        delete_option($option);
    }

    /**
     * Return a value from options
     *
     * @param string $option Contains option name to retrieve from DB
     * @param string $default Contains default value if no data was found
     * @param integer $transient Define if we use transiant API or not
     * @return mixed|string|void
     *
     * @since 1.5.0
     */
    public static function get_option($option, $default = '', $transient = 0)
    {
        //If a transient is asked...
        if (!empty($transient)) {
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
     * @param string $option Contains option name to update from DB
     * @param string $value Contains value to insert
     * @param integer $transient Define if we use transiant API or not
     *
     * @since 1.5.0
     */
    public static function set_option($option, $value, $transient = 0)
    {
        //If a transient is asked...
        if (!empty($transient)) {
            //Set the transient for this value
            set_transient($option, $value, TTO_DURATION);
        }

        //Set value into DB
        update_option($option, $value);
    }
}
