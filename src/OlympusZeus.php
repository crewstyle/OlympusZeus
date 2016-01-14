<?php

namespace crewstyle\OlympusZeus;

use crewstyle\OlympusZeus\Core\Core;
use crewstyle\OlympusZeus\Core\Action\Action;
use crewstyle\OlympusZeus\Core\Hook\Hook;
use crewstyle\OlympusZeus\Core\Menu\Menu;
use crewstyle\OlympusZeus\Plugins\Plugins;

/**
 * OLYMPUS ZEUS
 *
 * Library Name: Olympus Zeus
 * Version: 4.0.1
 * Snippet URI: https://github.com/crewstyle/OlympusZeus
 * Read The Doc: http://olympus-zeus.readme.io/
 * Description: The Olympus Zeus library allows you to easily add
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
 * Copyright (C) 2016, Achraf Chouk - achrafchouk@gmail.com
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

/**
 * Customizable Olympus Zeus constants.
 */

//The value defining if we are in admin panel or not
defined('OLZ_ISADMIN')      or define('OLZ_ISADMIN', is_admin());
//The nonce ajax value
defined('OLZ_NONCE')        or define('OLZ_NONCE', 'olympus-zeus-ajax-nonce');

//The blog home url
defined('OLZ_HOME')         or define('OLZ_HOME', get_option('home'));
//The language blog
defined('OLZ_LOCAL')        or define('OLZ_LOCAL', get_bloginfo('language'));
//The path
defined('OLZ_PATH')         or define('OLZ_PATH', dirname(__FILE__));
//The URI
defined('OLZ_URI')          or define('OLZ_URI', get_template_directory_uri().'/vendor/crewstyle/olympus-zeus');
//The Twig cache folder
defined('OLZ_CACHE')        or define('OLZ_CACHE', OLZ_PATH.'/../cache');

/**
 * Olympus Zeus constants.
 */

//The context used to define if the PHP files can be executed
define('OLZ_CONTEXT', 'olympus-zeus');
//The i18n language code
define('OLZ_I18N', 'olympus-zeus');
//The current version
define('OLZ_VERSION', '4.0.1');
//The current version
define('OLZ_VERSION_NUM', str_replace('.', '', OLZ_VERSION));
//The current baseline
define('OLZ_QUOTE', 'I\'m a damsel, I\'m in distress, I can handle this. Have a nice day. ~ Hercules');
//The value defining if theme uses post thumbnails or not
define('OLZ_CAN_THUMB', current_theme_supports('post-thumbnails'));
//The transient expiration duration
define('OLZ_DURATION', 86400);
//The capabilities
define('OLZ_WP_CAP', 'edit_posts');
//The custom capabilities
define('OLZ_WP_CAP_MAX', 'manage_tea_theme_options');
//The Twig views folder
define('OLZ_TWIG_VIEWS', OLZ_PATH.'/Resources/views');


//----------------------------------------------------------------------------//

/**
 * Olympus Zeus master class.
 *
 * To get its own settings, define all functions used to build custom pages and
 * custom post types.
 *
 * @package Olympus Zeus
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @todo Special field:     Typeahead
 * @todo Shortcodes panel:  Youtube, Vimeo, Dailymotion, Embed PDF,
 * @todo Shortcodes panel:  Google Adsense, Related posts, Private content (membership),
 * @todo Shortcodes panel:  RSS Feed, Price table, Carousel, Icons, ...
 *
 */
class OlympusZeus extends Core
{
    /**
     * @var Action
     */
    protected $action = null;

    /**
     * @var Hook
     */
    protected $hook = null;

    /**
     * @var Menu
     */
    protected $menu = null;

    /**
     * @var Plugins
     */
    protected $plugins = null;

    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        parent::__construct();

        //Build identifier
        $identifier = 'olympus';

        /**
         * Update OLZ identifier.
         *
         * @param string $identifier
         * @return string $identifier
         *
         * @since 4.0.0
         */
        $identifier = apply_filters('olz_core_identifier', $identifier);

        //Instanciate Action
        $this->action = new Action($identifier);

        //Instanciate Hook
        $this->hook = new Hook();

        //Instanciate Menu
        $this->menu = new Menu($identifier);

        //Instanciate Plugins
        $this->plugins = new Plugins($identifier);
    }

    /**
     * Add a new page menu.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 4.0.0
     */
    public function addMenu($configs)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->menu->getMenuEngine()->addMenu($configs);
    }

    /**
     * Add a new post type.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 4.0.0
     */
    public function addPostType($configs = array())
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->menu->getPosttype()->addPostType($configs);
    }

    /**
     * Add a new term.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 4.0.0
     */
    public function addTerm($configs = array())
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->menu->getTerm()->addTerm($configs);
    }

    /**
     * Build menu.
     *
     * @since 4.0.0
     */
    public function buildMenus()
    {
        //Admin panel and if we can build
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->menu->getMenuEngine()->buildMenus();
    }

    /**
     * Build post types.
     *
     * @since 4.0.0
     */
    public function buildPostTypes()
    {
        //Admin panel and if we can build
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->menu->getPosttype()->buildPostTypes();
    }

    /**
     * Build terms.
     *
     * @since 4.0.0
     */
    public function buildTerms()
    {
        //Admin panel and if we can build
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->menu->getTerm()->buildTerms();
    }

    /**
     * Get search engine.
     *
     * @return Search $search
     *
     * @since 4.0.0
     */
    public function search()
    {
        return $this->plugins->getSearch();
    }
}
