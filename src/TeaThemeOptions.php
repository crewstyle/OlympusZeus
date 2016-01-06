<?php

namespace crewstyle\TeaThemeOptions;

use crewstyle\TeaThemeOptions\Core\Core;
use crewstyle\TeaThemeOptions\Core\Notification\Notification;
use crewstyle\TeaThemeOptions\Core\Option\Option;
use crewstyle\TeaThemeOptions\Core\Render\Render;
use crewstyle\TeaThemeOptions\Core\Translate\Translate;

/**
 * TEA THEME OPTIONS
 *
 * Plugin Name: Tea Theme Options
 * Version: 3.3.0
 * Snippet URI: https://github.com/crewstyle/TeaThemeOptions
 * Read The Doc: http://tea-theme-options.readme.io/
 * Description: The Tea Theme Options (or "Tea TO") allows you to easily add
 * professional looking theme options panels to your WordPress theme.
 * It offers the best and easy way to create custom post types and
 * custom taxonomies.
 *
 * Author: Achraf Chouk
 * Author URI: https://github.com/crewstyle
 * License: The MIT License (MIT)
 *
 * The MIT License (MIT)
 *
 * Copyright (C) 2013-2015, Achraf Chouk - achrafchouk@gmail.com
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
//The value defining if we are in admin panel or not
defined('TTO_IS_ADMIN')     or define('TTO_IS_ADMIN', is_admin());
//The current version
defined('TTO_VERSION')      or define('TTO_VERSION', '3.3.0');
//The current baseline
defined('TTO_QUOTE')        or define('TTO_QUOTE', 'Spartans! Ready your breakfast and eat hearty... For tonight, we dine in hell! ~ 300');
//The current version
defined('TTO_VERSION_NUM')  or define('TTO_VERSION_NUM', '330');
//The i18n language code
defined('TTO_I18N')         or define('TTO_I18N', 'tea_theme_options');
//The transient expiration duration
defined('TTO_DURATION')     or define('TTO_DURATION', 86400);
//The blog home url
defined('TTO_HOME')         or define('TTO_HOME', get_option('home'));
//The language blog
defined('TTO_LOCAL')        or define('TTO_LOCAL', get_bloginfo('language'));
//The URI
defined('TTO_URI')          or define('TTO_URI', get_template_directory_uri().'/vendor/crewstyle/tea-theme-options');
//The path
defined('TTO_PATH')         or define('TTO_PATH', dirname(__FILE__));
//The wp-includes URI
defined('TTO_INC')          or define('TTO_INC', includes_url());
//The nonce ajax value
defined('TTO_NONCE')        or define('TTO_NONCE', 'tea-ajax-nonce');
//The value defining if theme uses post thumbnails or not
defined('TTO_CAN_THUMB')    or define('TTO_CAN_THUMB', current_theme_supports('post-thumbnails'));
//The capabilities
defined('TTO_WP_CAP')       or define('TTO_WP_CAP', 'edit_posts');
//The custom capabilities
defined('TTO_WP_CAP_MAX')   or define('TTO_WP_CAP_MAX', 'manage_tea_theme_options');


//----------------------------------------------------------------------------//

/**
 * Tea Theme Option master class.
 *
 * To get its own settings, define all functions used to build custom pages and
 * custom post types.
 *
 * @package Tea Theme Options
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 * @todo Special field:     Typeahead
 * @todo Shortcodes panel:  Youtube, Vimeo, Dailymotion, Embed PDF,
 * @todo Shortcodes panel:  Google Adsense, Related posts, Private content (membership),
 * @todo Shortcodes panel:  RSS Feed, Price table, Carousel, Icons, ...
 *
 */
class TeaThemeOptions
{
    /**
     * @var Core
     */
    protected $core = null;

    /**
     * Constructor.
     *
     * @since 3.3.0
     */
    public function __construct()
    {
        //Instanciate Core
        $this->core = new Core();
    }

    /**
     * Translate typo.
     *
     * @param string $content Contains typo to translate
     * @return Translate
     *
     * @since 3.3.0
     */
    public static function __($content)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        return (string) Translate::__($content);
    }

    /**
     * Add a new page menu.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 3.3.0
     */
    public function addMenu($configs)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->core->getCoreMenu()->getMenu()->addMenu($configs);
    }

    /**
     * Add a new post type.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 3.3.0
     */
    public function addPostType($configs = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->core->getCoreMenu()->getPosttype()->addPostType($configs);
    }

    /**
     * Add a new term.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 3.3.0
     */
    public function addTerm($configs = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->core->getCoreMenu()->getTerm()->addTerm($configs);
    }

    /**
     * Build menu.
     *
     * @since 3.3.0
     */
    public function buildMenus()
    {
        //Admin panel and if we can build
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->core->getCoreMenu()->getMenu()->buildMenus();
    }

    /**
     * Build post types.
     *
     * @since 3.3.0
     */
    public function buildPostTypes()
    {
        //Admin panel and if we can build
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->core->getCoreMenu()->getPosttype()->buildPostTypes();
    }

    /**
     * Build terms.
     *
     * @since 3.3.0
     */
    public function buildTerms()
    {
        //Admin panel and if we can build
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->core->getCoreMenu()->getTerm()->buildTerms();
    }

    /**
     * Display notification.
     *
     * @param string $type Define notice type to display
     * @param string $content Contains typo to display
     * @return string $content
     *
     * @since 3.3.0
     */
    public static function notify($type, $content)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        Notification::notify($type, $content);
    }

    /**
     * Get search engine.
     *
     * @return Search $search
     *
     * @since 3.3.0
     */
    public function search()
    {
        return $this->core->getSearch();
    }

    /**
     * Get configs.
     *
     * @param string $option Define the option asked
     * @return array $configs Define configurations
     *
     * @since 3.3.0
     */
    public static function getConfigs($option = 'login')
    {
        return Option::getConfigs($option);
    }

    /**
     * Set configs.
     *
     * @param string $option Define the option to update
     * @param array|integer|string $value Define the value
     *
     * @since 3.3.0
     */
    public static function setConfigs($option = 'login', $value = true)
    {
        Option::setConfigs($option, $value);
    }

    /**
     * Set a value into options
     *
     * @param string $option Contains option name to delete from DB
     * @param integer $transient Define if we use transiant API or not
     *
     * @since 3.0.0
     */
    public static function delOption($option, $transient = 0)
    {
        Option::delOption($option, $transient);
    }

    /**
     * Return a value from options
     *
     * @param string $option Contains option name to retrieve from DB
     * @param string $default Contains default value if no data was found
     * @param integer $transient Define if we use transiant API or not
     * @return mixed|string|void
     *
     * @since 3.0.0
     */
    public static function getOption($option, $default = '', $transient = 0)
    {
        return Option::getOption($option, $default, $transient);
    }

    /**
     * Set a value into options
     *
     * @param string $option Contains option name to update from DB
     * @param string $value Contains value to insert
     * @param integer $transient Define if we use transiant API or not
     *
     * @since 3.0.0
     */
    public static function setOption($option, $value, $transient = 0)
    {
        Option::setOption($option, $value, $transient);
    }

    /**
     * Force update a value into options without transient
     *
     * @param string $option Contains option name to update from DB
     * @param string $value Contains value to insert
     *
     * @since 3.0.0
     */
    public static function updateOption($option, $value)
    {
        Option::updateOption($option, $value);
    }

    /**
     * Get renderer.
     *
     * @param string $template Twig template to display
     * @param array $vars Contains all field options
     *
     * @since 3.0.0
     */
    public static function getRender($template, $vars)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        Render::render($template, $vars);
    }

    /**
     * Slugify string.
     *
     * @param string $text Text string to slugify
     * @param string $separator Character used to separate each word
     * @return string $slugified Slugified string
     *
     * @since 3.1.0
     */
    public static function getUrlize($text, $separator = '-')
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        return Render::urlize($text, $separator);
    }
}
