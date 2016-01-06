<?php

namespace crewstyle\TeaThemeOptions\Core\Hook\BackendHook;

use crewstyle\TeaThemeOptions\TeaThemeOptions;

/**
 * TTO BACKEND HOOK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Backend Hook
 *
 * To get its own hooks.
 *
 * @package Tea Theme Options
 * @subpackage Core\Hook\BackendHook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class BackendHook
{
    /**
     * Constructor.
     *
     * @since 3.3.0
     */
    public function __construct()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }
    }

    /**
     * Build admin hooks.
     *
     * @since 3.3.0
     */
    public function makeHooks()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //i18n
        $locale = apply_filters('theme_locale', get_locale(), TTO_I18N);
        load_textdomain(TTO_I18N, TTO_PATH.'/languages/'.$locale.'.mo');

        //Add custom CSS colors ~ Earth
        wp_admin_css_color(
            'teatocss-earth',
            TeaThemeOptions::__('Tea T.O. ~ Earth'),
            TTO_URI.'/assets/css/teato.admin.earth.css?ver=v'.TTO_VERSION_NUM,
            array('#222', '#303231', '#55bb3a', '#91d04d')
        );
        //Add custom CSS colors ~ Ocean
        wp_admin_css_color(
            'teatocss-ocean',
            TeaThemeOptions::__('Tea T.O. ~ Ocean'),
            TTO_URI.'/assets/css/teato.admin.ocean.css?ver=v'.TTO_VERSION_NUM,
            array('#222', '#303231', '#3a80bb', '#4d9dd0')
        );
        //Add custom CSS colors ~ Vulcan
        wp_admin_css_color(
            'teatocss-vulcan',
            TeaThemeOptions::__('Tea T.O. ~ Vulcan'),
            TTO_URI.'/assets/css/teato.admin.vulcan.css?ver=v'.TTO_VERSION_NUM,
            array('#222', '#303231', '#bb3a3a', '#d04d4d')
        );
        //Add custom CSS colors ~ Wind
        wp_admin_css_color(
            'teatocss-wind',
            TeaThemeOptions::__('Tea T.O. ~ Wind'),
            TTO_URI.'/assets/css/teato.admin.wind.css?ver=v'.TTO_VERSION_NUM,
            array('#222', '#303231', '#69d2e7', '#a7dbd8')
        );

        //Hooks
        add_action('admin_menu',                    array(&$this, 'hookAssets'), 999);

        //Get configs
        $hooks = TeaThemeOptions::getConfigs('backendhooks');
        $modules = TeaThemeOptions::getConfigs('modules');

        if (isset($hooks['emojicons']) && $hooks['emojicons']) {
            add_action('init',                      array(&$this, 'hookDisableWPEmojicons'));
        }

        if (isset($hooks['versioncheck']) && $hooks['versioncheck']) {
            add_action('after_setup_theme',         array(&$this, 'hookRemoveAdminWPVersionCheck'));
        }

        if (isset($hooks['baricons']) && $hooks['baricons']) {
            add_action('wp_before_admin_bar_render',array(&$this, 'hookRemoveBarIcons'));
        }

        if (isset($hooks['menus']) && $hooks['menus']) {
            add_action('admin_menu',                array(&$this, 'hookRemoveMenus'));
        }

        if (isset($modules['sanitizedfilename']) && $modules['sanitizedfilename']) {
            add_filter('sanitize_file_name',        array(&$this, 'hookSanitizeFilename'), 10);
        }
    }

    /**
     * Hook building assets.
     *
     * @since 3.3.0
     */
    public function hookAssets()
    {
        add_action('admin_print_scripts', array(&$this, 'hookAssetsScripts'));
        add_action('admin_print_styles', array(&$this, 'hookAssetsStyles'));
    }

    /**
     * Hook building scripts.
     *
     * @uses wp_enqueue_media_tto()
     * @uses wp_enqueue_script()
     *
     * @since 3.3.0
     */
    public function hookAssetsScripts()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get jQuery
        $jq = array('jquery');

        //Enqueue media and colorpicker scripts
        self::wp_enqueue_media_tto();
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('accordion');

        //Enqueue all minified scripts
        wp_enqueue_script('tea-to', TTO_URI.'/assets/js/teato.min.js', $jq, 'v'.TTO_VERSION_NUM);
    }

    /**
     * Hook building styles.
     *
     * @uses wp_enqueue_style()
     *
     * @since 3.3.0
     */
    public function hookAssetsStyles()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Enqueue usefull styles
        wp_enqueue_style('media-views');
        wp_enqueue_style('farbtastic');
        wp_enqueue_style('wp-color-picker');

        //Enqueue all minified styles
        wp_enqueue_style('tea-to', TTO_URI.'/assets/css/teato.min.css', array(), 'v'.TTO_VERSION_NUM);
    }

    /**
     * Disable emojicons introduced with WP 4.2 in backend panel.
     *
     * @uses remove_action()
     * @uses add_filter()
     *
     * @since 3.3.0
     */
    public function hookDisableWPEmojicons()
    {
        //All actions related to emojis
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        //Filter function used to remove the tinymce emoji plugin
        add_filter('tiny_mce_plugins', array(&$this, 'hookDisableWPEmojiconsTinymce'));
    }
    public function hookDisableWPEmojiconsTinymce($plugins)
    {
        if (is_array($plugins)) {
            return array_diff($plugins, array('wpemoji'));
        }
        return array();
    }

    /**
     * Sets up WP version check for admin panel.
     *
     * @uses current_user_can() To know if current user has permissions.
     * @uses add_action() To add a hook action.
     * @uses add_filter() To add a hook filter.
     *
     * @since 3.3.0
     */
    public function hookRemoveAdminWPVersionCheck()
    {
        //Remove WORDPRESS update in the administration to all users, except the admin
        if (!current_user_can('edit_users')) {
            add_action('init', create_function('$a', "remove_action('init', 'wp_version_check');"), 2);
            add_filter('pre_option_update_core', create_function('$a', "return null;"));
        }
    }

    /**
     * Remove menu items from WordPress admin bar.
     *
     * @uses remove_menu() To remove specific menu.
     *
     * @since 3.3.0
     */
    public function hookRemoveBarIcons()
    {
        global $wp_admin_bar;

        //Remove all useless WP bar menus
        $wp_admin_bar->remove_menu('wp-logo');
        $wp_admin_bar->remove_menu('about');
        $wp_admin_bar->remove_menu('comments');
        $wp_admin_bar->remove_menu('new-content');
        $wp_admin_bar->remove_menu('wporg');
        $wp_admin_bar->remove_menu('documentation');
        $wp_admin_bar->remove_menu('support-forums');
        $wp_admin_bar->remove_menu('feedback');
        $wp_admin_bar->remove_menu('view-site');
    }

    /**
     * Remove dashboard menus to editor.
     *
     * @uses current_user_can() To know if current user has permissions.
     * @uses remove_menu_page() To remove a menu page.
     * @uses remove_submenu_page() To remove a submenu page.
     *
     * @since 3.3.0
     */
    public function hookRemoveMenus()
    {
        //Remove WORDPRESS pages in the administration to all users, except the admin
        if (!current_user_can('edit_users')) {
            remove_submenu_page('themes.php', 'themes.php');
            remove_menu_page('plugins.php');
            remove_submenu_page('index.php', 'update-core.php');
            remove_submenu_page('options-general.php', 'options-media.php');
            remove_menu_page('link-manager.php');
            remove_menu_page('tools.php');
        }
    }

    /**
     * Sanitize filenames.
     *
     * @param string $filename Name of the file to sanitize
     * @uses remove_accents() Converts all accent characters to ASCII characters
     *
     * @since 3.3.0
     */
    public function hookSanitizeFilename($filename)
    {
        //Invalid characters
        $invalid = array(
            'Ã€'=>'A', 'Ã'=>'A', 'Ã‚'=>'A', 'Ãƒ'=>'A', 'Ä€'=>'A', 'Ä‚'=>'A', 'È¦'=>'A', 'Ã„'=>'A', 'áº¢'=>'A', 'Ã…'=>'A', 'Ç'=>'A', 'È€'=>'A', 'È‚'=>'A', 'Ä„'=>'A', 'áº '=>'A', 'á¸€'=>'A', 'áº¦'=>'A', 'áº¤'=>'A', 'áºª'=>'A', 'áº¨'=>'A', 'áº°'=>'A', 'áº®'=>'A', 'áº´'=>'A', 'áº²'=>'A', 'Ç '=>'A', 'Çž'=>'A', 'Çº'=>'A', 'áº¬'=>'A', 'áº¶'=>'A',
            'Ã†'=>'AE', 'Ç¼'=>'AE', 'Ç¢'=>'AE',
            'á¸‚'=>'B', 'Æ'=>'B', 'á¸„'=>'B', 'á¸†'=>'B', 'Æ‚'=>'B', 'Æ„'=>'B', 'Ãž'=>'B',
            'Äˆ'=>'C', 'ÄŠ'=>'C', 'ÄŒ'=>'C', 'Æ‡'=>'C', 'Ã‡'=>'C', 'á¸ˆ'=>'C',
            'á¸Š'=>'D', 'ÆŠ'=>'D', 'á¸Œ'=>'D', 'á¸Ž'=>'D', 'á¸'=>'D', 'á¸’'=>'D', 'ÄŽ'=>'D',
            'Ä'=>'Dj', 'Æ‰'=>'Dj',
            'Ãˆ'=>'E', 'Ã‰'=>'E', 'ÃŠ'=>'E', 'áº¼'=>'E', 'Ä’'=>'E', 'Ä”'=>'E', 'Ä–'=>'E', 'Ã‹'=>'E', 'áºº'=>'E', 'Äš'=>'E', 'È„'=>'E', 'È†'=>'E', 'áº¸'=>'E', 'È¨'=>'E', 'Ä˜'=>'E', 'á¸˜'=>'E', 'á¸š'=>'E', 'á»€'=>'E', 'áº¾'=>'E', 'á»„'=>'E', 'á»‚'=>'E', 'á¸”'=>'E', 'á¸–'=>'E', 'á»†'=>'E', 'á¸œ'=>'E', 'ÆŽ'=>'E', 'Æ'=>'E',
            'á¸ž'=>'F', 'Æ‘'=>'F',
            'Ç´'=>'G', 'Äœ'=>'G', 'á¸ '=>'G', 'Äž'=>'G', 'Ä '=>'G', 'Ç¦'=>'G', 'Æ“'=>'G', 'Ä¢'=>'G', 'Ç¤'=>'G',
            'Ä¤'=>'H', 'á¸¦'=>'H', 'Èž'=>'H', 'Ç¶'=>'H', 'á¸¤'=>'H', 'á¸¨'=>'H', 'á¸ª'=>'H', 'Ä¦'=>'H',
            'ÃŒ'=>'I', 'Ã'=>'I', 'ÃŽ'=>'I', 'Ä¨'=>'I', 'Äª'=>'I', 'Ä¬'=>'I', 'Ä°'=>'I', 'Ã'=>'I', 'á»ˆ'=>'I', 'Ç'=>'I', 'á»Š'=>'I', 'Ä®'=>'I', 'Èˆ'=>'I', 'ÈŠ'=>'I', 'á¸¬'=>'I', 'Æ—'=>'I', 'á¸®'=>'I',
            'Ä²'=>'IJ',
            'Ä´'=>'J',
            'á¸°'=>'K', 'Ç¨'=>'K', 'á¸´'=>'K', 'Æ˜'=>'K', 'á¸²'=>'K', 'Ä¶'=>'K', 'Ä¹'=>'L', 'á¸º'=>'L', 'á¸¶'=>'L', 'Ä»'=>'L', 'á¸¼'=>'L', 'Ä½'=>'L', 'Ä¿'=>'L', 'Å'=>'L', 'á¸¸'=>'L',
            'á¸¾'=>'M', 'á¹€'=>'M', 'á¹‚'=>'M', 'Æœ'=>'M', 'Ã‘'=>'N', 'Ç¸'=>'N', 'Åƒ'=>'N', 'Ã‘'=>'N', 'á¹„'=>'N', 'Å‡'=>'N', 'ÅŠ'=>'N', 'Æ'=>'N', 'á¹†'=>'N', 'Å…'=>'N', 'á¹Š'=>'N', 'á¹ˆ'=>'N', 'È '=>'N',
            'Ã’'=>'O', 'Ã“'=>'O', 'Ã”'=>'O', 'Ã•'=>'O', 'ÅŒ'=>'O', 'ÅŽ'=>'O', 'È®'=>'O', 'Ã–'=>'O', 'á»Ž'=>'O', 'Å'=>'O', 'Ç‘'=>'O', 'ÈŒ'=>'O', 'ÈŽ'=>'O', 'Æ '=>'O', 'Çª'=>'O', 'á»Œ'=>'O', 'ÆŸ'=>'O', 'Ã˜'=>'O', 'á»’'=>'O', 'á»'=>'O', 'á»–'=>'O', 'á»”'=>'O', 'È°'=>'O', 'Èª'=>'O', 'È¬'=>'O', 'á¹Œ'=>'O', 'á¹'=>'O', 'á¹’'=>'O', 'á»œ'=>'O', 'á»š'=>'O', 'á» '=>'O', 'á»ž'=>'O', 'á»¢'=>'O', 'Ç¬'=>'O', 'á»˜'=>'O', 'Ç¾'=>'O', 'Æ†'=>'O', 'Å’'=>'OE',
            'á¹”'=>'P', 'á¹–'=>'P', 'Æ¤'=>'P',
            'Å”'=>'R', 'á¹˜'=>'R', 'Å˜'=>'R',   'È'=>'R', 'È’'=>'R', 'á¹š'=>'R', 'Å–'=>'R', 'á¹ž'=>'R', 'á¹œ'=>'R', 'Æ¦'=>'R',
            'Åš'=>'S', 'Åœ'=>'S', 'á¹ '=>'S', 'Å '=>'S', 'á¹¢'=>'S', 'È˜'=>'S', 'Åž'=>'S', 'á¹¤'=>'S', 'á¹¦'=>'S', 'á¹¨'=>'S',
            'á¹ª'=>'T', 'Å¤'=>'T', 'Æ¬'=>'T', 'Æ®'=>'T', 'á¹¬'=>'T', 'Èš'=>'T', 'Å¢'=>'T', 'á¹°'=>'T', 'á¹®'=>'T', 'Å¦'=>'T',
            'Ã™'=>'U', 'Ãš'=>'U', 'Ã›'=>'U', 'Å¨'=>'U', 'Åª'=>'U', 'Å¬'=>'U', 'Ãœ'=>'U', 'á»¦'=>'U', 'Å®'=>'U', 'Å°'=>'U', 'Ç“'=>'U', 'È”'=>'U', 'È–'=>'U', 'Æ¯'=>'U', 'á»¤'=>'U', 'á¹²'=>'U', 'Å²'=>'U', 'á¹¶'=>'U', 'á¹´'=>'U',   'á¹¸'=>'U', 'á¹º'=>'U', 'Ç›'=>'U', 'Ç—'=>'U', 'Ç•'=>'U', 'Ç™'=>'U', 'á»ª'=>'U',     'á»¨'=>'U', 'á»®'=>'U', 'á»¬'=>'U', 'á»°'=>'U',
            'á¹¼'=>'V', 'á¹¾'=>'V', 'Æ²'=>'V',
            'áº€'=>'W', 'áº‚'=>'W', 'Å´'=>'W', 'áº†'=>'W', 'áº„'=>'W', 'áºˆ'=>'W',
            'áºŠ'=>'X', 'áºŒ'=>'X',
            'á»²'=>'Y', 'Ã'=>'Y', 'Å¶'=>'Y', 'á»¸'=>'Y', 'È²'=>'Y', 'áºŽ'=>'Y', 'Å¸'=>'Y', 'á»¶'=>'Y', 'Æ³'=>'Y', 'á»´'=>'Y',
            'Å¹'=>'Z', 'áº'=>'Z', 'Å»'=>'Z', 'Å½'=>'Z', 'È¤'=>'Z', 'áº’'=>'Z', 'áº”'=>'Z', 'Æµ'=>'Z',
            'Ã '=>'a', 'Ã¡'=>'a', 'Ã¢'=>'a', 'Ã£'=>'a', 'Ä'=>'a', 'Äƒ'=>'a', 'È§'=>'a', 'Ã¤'=>'a', 'áº£'=>'a', 'Ã¥'=>'a', 'ÇŽ'=>'a', 'È'=>'a', 'Ä…'=>'a', 'áº¡'=>'a', 'á¸'=>'a', 'áºš'=>'a', 'áº§'=>'a', 'áº¥'=>'a', 'áº«'=>'a', 'áº©'=>'a', 'áº±'=>'a', 'áº¯'=>'a', 'áºµ'=>'a', 'áº³'=>'a', 'Ç¡'=>'a', 'ÇŸ'=>'a', 'Ç»'=>'a', 'áº­'=>'a', 'áº·'=>'a',
            'Ã¦'=>'ae', 'Ç½'=>'ae', 'Ç£'=>'ae',
            'á¸ƒ'=>'b', 'É“'=>'b', 'á¸…'=>'b', 'á¸‡'=>'b', 'Æ€'=>'b', 'Æƒ'=>'b', 'Æ…'=>'b', 'Ã¾'=>'b',
            'Ä‡'=>'c', 'Ä‰'=>'c', 'Ä‹'=>'c', 'Ä'=>'c', 'Æˆ'=>'c', 'Ã§'=>'c', 'á¸‰'=>'c',
            'á¸‹'=>'d', 'É—'=>'d', 'á¸'=>'d', 'á¸'=>'d', 'á¸‘'=>'d', 'á¸“'=>'d', 'Ä'=>'d', 'Ä‘'=>'d', 'ÆŒ'=>'d', 'È¡'=>'d',
            'Ä‘'=>'dj',
            'Ã¨'=>'e', 'Ã©'=>'e', 'Ãª'=>'e', 'áº½'=>'e', 'Ä“'=>'e', 'Ä•'=>'e', 'Ä—'=>'e', 'Ã«'=>'e', 'áº»'=>'e', 'Ä›'=>'e', 'È…'=>'e', 'È‡'=>'e', 'áº¹'=>'e', 'È©'=>'e', 'Ä™'=>'e', 'á¸™'=>'e', 'á¸›'=>'e', 'á»'=>'e', 'áº¿'=>'e',             'á»…'=>'e', 'á»ƒ'=>'e', 'á¸•'=>'e', 'á¸—'=>'e', 'á»‡'=>'e', 'á¸'=>'e', 'Ç'=>'e', 'É›'=>'e',
            'á¸Ÿ'=>'f', 'Æ’'=>'f',
            'Çµ'=>'g', 'Ä'=>'g', 'á¸¡'=>'g', 'ÄŸ'=>'g', 'Ä¡'=>'g', 'Ç§'=>'g', 'É '=>'g', 'Ä£'=>'g', 'Ç¥'=>'g',
            'Ä¥'=>'h', 'á¸£'=>'h', 'á¸§'=>'h', 'ÈŸ'=>'h', 'Æ•'=>'h', 'á¸¥'=>'h', 'á¸©'=>'h', 'á¸«'=>'h', 'áº–'=>'h', 'Ä§'=>'h',
            'Ã¬'=>'i', 'Ã­'=>'i', 'Ã®'=>'i', 'Ä©'=>'i', 'Ä«'=>'i', 'Ä­'=>'i', 'Ä±'=>'i', 'Ã¯'=>'i', 'á»‰'=>'i', 'Ç'=>'i', 'á»‹'=>'i', 'Ä¯'=>'i', 'È‰'=>'i', 'È‹'=>'i', 'á¸­'=>'i',  'É¨'=>'i', 'á¸¯'=>'i',
            'Ä³'=>'ij',
            'Äµ'=>'j', 'Ç°'=>'j',
            'á¸±'=>'k', 'Ç©'=>'k', 'á¸µ'=>'k', 'Æ™'=>'k', 'á¸³'=>'k', 'Ä·'=>'k',
            'Äº'=>'l', 'á¸»'=>'l', 'á¸·'=>'l', 'Ä¼'=>'l', 'á¸½'=>'l', 'Ä¾'=>'l', 'Å€'=>'l', 'Å‚'=>'l', 'Æš'=>'l', 'á¸¹'=>'l', 'È´'=>'l',
            'á¸¿'=>'m', 'á¹'=>'m', 'á¹ƒ'=>'m', 'É¯'=>'m',
            'Ç¹'=>'n', 'Å„'=>'n', 'Ã±'=>'n', 'á¹…'=>'n', 'Åˆ'=>'n', 'Å‹'=>'n', 'É²'=>'n', 'á¹‡'=>'n', 'Å†'=>'n', 'á¹‹'=>'n', 'á¹‰'=>'n', 'Å‰'=>'n', 'Æž'=>'n', 'Èµ'=>'n',
            'Ã²'=>'o', 'Ã³'=>'o', 'Ã´'=>'o', 'Ãµ'=>'o', 'Å'=>'o', 'Å'=>'o', 'È¯'=>'o', 'Ã¶'=>'o', 'á»'=>'o', 'Å‘'=>'o', 'Ç’'=>'o', 'È'=>'o', 'È'=>'o', 'Æ¡'=>'o', 'Ç«'=>'o', 'á»'=>'o', 'Éµ'=>'o', 'Ã¸'=>'o', 'á»“'=>'o', 'á»‘'=>'o', 'á»—'=>'o', 'á»•'=>'o', 'È±'=>'o', 'È«'=>'o', 'È­'=>'o', 'á¹'=>'o', 'á¹'=>'o', 'á¹‘'=>'o', 'á¹“'=>'o', 'á»'=>'o', 'á»›'=>'o', 'á»¡'=>'o', 'á»Ÿ'=>'o', 'á»£'=>'o', 'Ç­'=>'o', 'á»™'=>'o', 'Ç¿'=>'o', 'É”'=>'o',
            'Å“'=>'oe',
            'á¹•'=>'p', 'á¹—'=>'p', 'Æ¥'=>'p',
            'Å•'=>'r', 'á¹™'=>'r', 'Å™'=>'r', 'È‘'=>'r', 'È“'=>'r', 'á¹›'=>'r', 'Å—'=>'r', 'á¹Ÿ'=>'r', 'á¹'=>'r',
            'Å›'=>'s', 'Å'=>'s', 'á¹¡'=>'s', 'Å¡'=>'s', 'á¹£'=>'s', 'È™'=>'s', 'ÅŸ'=>'s', 'á¹¥'=>'s', 'á¹§'=>'s', 'á¹©'=>'s', 'Å¿'=>'s', 'áº›'=>'s',
            'ÃŸ'=>'Ss',
            'á¹«'=>'t', 'áº—'=>'t', 'Å¥'=>'t', 'Æ­'=>'t', 'Êˆ'=>'t', 'Æ«'=>'t', 'á¹­'=>'t', 'È›'=>'t', 'Å£'=>'t', 'á¹±'=>'t', 'á¹¯'=>'t', 'Å§'=>'t', 'È¶'=>'t',
            'Ã¹'=>'u', 'Ãº'=>'u', 'Ã»'=>'u', 'Å©'=>'u', 'Å«'=>'u', 'Å­'=>'u', 'Ã¼'=>'u', 'á»§'=>'u', 'Å¯'=>'u', 'Å±'=>'u', 'Ç”'=>'u', 'È•'=>'u', 'È—'=>'u', 'Æ°'=>'u', 'á»¥'=>'u', 'á¹³'=>'u', 'Å³'=>'u', 'á¹·'=>'u', 'á¹µ'=>'u', 'á¹¹'=>'u', 'á¹»'=>'u', 'Çœ'=>'u', 'Ç˜'=>'u', 'Ç–'=>'u', 'Çš'=>'u', 'á»«'=>'u', 'á»©'=>'u', 'á»¯'=>'u', 'á»­'=>'u', 'á»±'=>'u',
            'á¹½'=>'v', 'á¹¿'=>'v',
            'áº'=>'w', 'áºƒ'=>'w', 'Åµ'=>'w', 'áº‡'=>'w', 'áº…'=>'w', 'áº˜'=>'w', 'áº‰'=>'w',
            'áº‹'=>'x', 'áº'=>'x',
            'Ã½'=>'y', 'Ã½'=>'y', 'á»³'=>'y', 'Ã½'=>'y', 'Å·'=>'y', 'È³'=>'y', 'áº'=>'y', 'Ã¿'=>'y', 'Ã¿'=>'y', 'á»·'=>'y', 'áº™'=>'y', 'Æ´'=>'y', 'á»µ'=>'y',
            'Åº'=>'z', 'áº‘'=>'z', 'Å¼'=>'z', 'Å¾'=>'z', 'È¥'=>'z', 'áº“'=>'z', 'áº•'=>'z', 'Æ¶'=>'z',
            'â„–'=>'No',
            'Âº'=>'o',
            'Âª'=>'a',
            'â‚¬'=>'E',
            'Â©'=>'C',
            'â„—'=>'P',
            'â„¢'=>'tm',
            'â„ '=>'sm'
        );

        //Make the magic
        $sanitized_filename = str_replace(array_keys($invalid), array_values($invalid), $filename);
        $sanitized_filename = remove_accents($sanitized_filename);
        $sanitized_filename = preg_replace('/[^a-zA-Z0-9-_\.]/', '', strtolower($sanitized_filename));

        //Return the new name
        return $sanitized_filename;
    }

    /**
     * Update rewrite rules options.
     *
     * @param array $args Define if the TTO has to make a redirect
     *
     * @since 3.3.0
     */
    static function wp_enqueue_media_tto($args = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Enqueue me just once per page, please.
        if (did_action('wp_enqueue_media_tto') || did_action('wp_enqueue_media')) {
            return;
        }

        global $content_width, $wpdb, $wp_locale;

        $defaults = array(
            'post' => null,
        );
        $args = wp_parse_args($args, $defaults);

        //We're going to pass the old thickbox media tabs to `media_upload_tabs`
        //to ensure plugins will work. We will then unset those tabs.
        $tabs = array(
            //handler action suffix => tab label
            'type'     => '',
            'type_url' => '',
            'gallery'  => '',
            'library'  => '',
        );

        /** This filter is documented in wp-admin/includes/media.php */
        $tabs = apply_filters('media_upload_tabs', $tabs);
        unset($tabs['type'], $tabs['type_url'], $tabs['gallery'], $tabs['library']);

        $props = array(
            'link'  => get_option('image_default_link_type'), //db default is 'file'
            'align' => get_option('image_default_align'), //empty default
            'size'  => get_option('image_default_size'),  //empty default
        );

        $exts = array_merge(wp_get_audio_extensions(), wp_get_video_extensions());
        $mimes = get_allowed_mime_types();
        $ext_mimes = array();

        foreach ($exts as $ext) {
            foreach ($mimes as $ext_preg => $mime_match) {
                if (preg_match('#' . $ext . '#i', $ext_preg)) {
                    $ext_mimes[ $ext ] = $mime_match;
                    break;
                }
            }
        }

        if ( false === ( $has_audio = get_transient( 'has_audio' ) ) ) {
                $has_audio = (bool) $wpdb->get_var( "
                    SELECT ID
                    FROM $wpdb->posts
                    WHERE post_type = 'attachment'
                    AND post_mime_type LIKE 'audio%'
                    LIMIT 1
                " );
                set_transient( 'has_audio', $has_audio );
        }

        if ( false === ( $has_video = get_transient( 'has_video' ) ) ) {
                $has_video = (bool) $wpdb->get_var( "
                    SELECT ID
                    FROM $wpdb->posts
                    WHERE post_type = 'attachment'
                    AND post_mime_type LIKE 'video%'
                    LIMIT 1
                " );
                set_transient( 'has_video', $has_video );
        }

        $months = $wpdb->get_results($wpdb->prepare("
            SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month
            FROM $wpdb->posts
            WHERE post_type = %s
            ORDER BY post_date DESC
        ", 'attachment'));

        foreach ($months as $month_year) {
            $month_year->text = sprintf(
                TeaThemeOptions::__('%1$s %2$d'),
                $wp_locale->get_month($month_year->month),
                $month_year->year
            );
        }

        $settings = array(
            'tabs'      => $tabs,
            'tabUrl'    => add_query_arg(array('chromeless' => true), admin_url('media-upload.php')),
            'mimeTypes' => wp_list_pluck(get_post_mime_types(), 0),
            /** This filter is documented in wp-admin/includes/media.php */
            'captions'  => ! apply_filters('disable_captions', ''),
            'nonce'     => array(
                'sendToEditor' => wp_create_nonce('media-send-to-editor'),
           ),
            'post'    => array(
                'id' => 0,
           ),
            'defaultProps' => $props,
            'attachmentCounts' => array(
                'audio' => ($has_audio) ? 1 : 0,
                'video' => ($has_video) ? 1 : 0
           ),
            'embedExts'    => $exts,
            'embedMimes'   => $ext_mimes,
            'contentWidth' => $content_width,
            'months'       => $months,
            'mediaTrash'   => MEDIA_TRASH ? 1 : 0
        );

        $post = null;
        if (isset($args['post'])) {
            $post = get_post($args['post']);
            $settings['post'] = array(
                'id' => $post->ID,
                'nonce' => wp_create_nonce('update-post_' . $post->ID),
           );

            $thumbnail_support = current_theme_supports('post-thumbnails', $post->post_type) 
                && post_type_supports($post->post_type, 'thumbnail');

            if (! $thumbnail_support && 'attachment' === $post->post_type && $post->post_mime_type) {
                if (wp_attachment_is('audio', $post)) {
                    $thumbnail_support = post_type_supports('attachment:audio', 'thumbnail') 
                        || current_theme_supports('post-thumbnails', 'attachment:audio');
                } elseif (wp_attachment_is('video', $post)) {
                    $thumbnail_support = post_type_supports('attachment:video', 'thumbnail') 
                        || current_theme_supports('post-thumbnails', 'attachment:video');
                }
            }

            if ($thumbnail_support) {
                $featured_image_id = get_post_meta($post->ID, '_thumbnail_id', true);
                $settings['post']['featuredImageId'] = $featured_image_id ? $featured_image_id : -1;
            }
        }

        $hier = $post && is_post_type_hierarchical($post->post_type);

        $strings = array(
            //Generic
            'url'         => TeaThemeOptions::__('URL'),
            'addMedia'    => TeaThemeOptions::__('Add Media'),
            'search'      => TeaThemeOptions::__('Search'),
            'select'      => TeaThemeOptions::__('Select'),
            'cancel'      => TeaThemeOptions::__('Cancel'),
            'update'      => TeaThemeOptions::__('Update'),
            'replace'     => TeaThemeOptions::__('Replace'),
            'remove'      => TeaThemeOptions::__('Remove'),
            'back'        => TeaThemeOptions::__('Back'),
            /* translators: This is a would-be plural string used in the media manager.
               If there is not a word you can use in your language to avoid issues with the
               lack of plural support here, turn it into "selected: %d" then translate it.
             */
            'selected'    => TeaThemeOptions::__('%d selected'),
            'dragInfo'    => TeaThemeOptions::__('Drag and drop to reorder media files.'),

            //Upload
            'uploadFilesTitle'  => TeaThemeOptions::__('Upload Files'),
            'uploadImagesTitle' => TeaThemeOptions::__('Upload Images'),

            //Library
            'mediaLibraryTitle'      => TeaThemeOptions::__('Media Library'),
            'insertMediaTitle'       => TeaThemeOptions::__('Insert Media'),
            'createNewGallery'       => TeaThemeOptions::__('Create a new gallery'),
            'createNewPlaylist'      => TeaThemeOptions::__('Create a new playlist'),
            'createNewVideoPlaylist' => TeaThemeOptions::__('Create a new video playlist'),
            'returnToLibrary'        => TeaThemeOptions::__('&#8592; Return to library'),
            'allMediaItems'          => TeaThemeOptions::__('All media items'),
            'allDates'               => TeaThemeOptions::__('All dates'),
            'noItemsFound'           => TeaThemeOptions::__('No items found.'),
            'insertIntoPost'         => $hier 
                ? TeaThemeOptions::__('Insert into page') 
                : TeaThemeOptions::__('Insert into post'),
            'unattached'             => TeaThemeOptions::__('Unattached'),
            'trash'                  => _x('Trash', 'noun'),
            'uploadedToThisPost'     => $hier 
                ? TeaThemeOptions::__('Uploaded to this page') 
                : TeaThemeOptions::__('Uploaded to this post'),
            'warnDelete'             => TeaThemeOptions::__("You are about to permanently delete this item.
                \n  'Cancel' to stop, 'OK' to delete."),
            'warnBulkDelete'         => TeaThemeOptions::__("You are about to permanently delete these items.
                \n  'Cancel' to stop, 'OK' to delete."),
            'warnBulkTrash'          => TeaThemeOptions::__("You are about to trash these items.
                \n  'Cancel' to stop, 'OK' to delete."),
            'bulkSelect'             => TeaThemeOptions::__('Bulk Select'),
            'cancelSelection'        => TeaThemeOptions::__('Cancel Selection'),
            'trashSelected'          => TeaThemeOptions::__('Trash Selected'),
            'untrashSelected'        => TeaThemeOptions::__('Untrash Selected'),
            'deleteSelected'         => TeaThemeOptions::__('Delete Selected'),
            'deletePermanently'      => TeaThemeOptions::__('Delete Permanently'),
            'apply'                  => TeaThemeOptions::__('Apply'),
            'filterByDate'           => TeaThemeOptions::__('Filter by date'),
            'filterByType'           => TeaThemeOptions::__('Filter by type'),
            'searchMediaLabel'       => TeaThemeOptions::__('Search Media'),
            'noMedia'                => TeaThemeOptions::__('No media attachments found.'),

            //Library Details
            'attachmentDetails'  => TeaThemeOptions::__('Attachment Details'),

            //From URL
            'insertFromUrlTitle' => TeaThemeOptions::__('Insert from URL'),

            //Featured Images
            'setFeaturedImageTitle' => TeaThemeOptions::__('Set Featured Image'),
            'setFeaturedImage'    => TeaThemeOptions::__('Set featured image'),

            //Gallery
            'createGalleryTitle' => TeaThemeOptions::__('Create Gallery'),
            'editGalleryTitle'   => TeaThemeOptions::__('Edit Gallery'),
            'cancelGalleryTitle' => TeaThemeOptions::__('&#8592; Cancel Gallery'),
            'insertGallery'      => TeaThemeOptions::__('Insert gallery'),
            'updateGallery'      => TeaThemeOptions::__('Update gallery'),
            'addToGallery'       => TeaThemeOptions::__('Add to gallery'),
            'addToGalleryTitle'  => TeaThemeOptions::__('Add to Gallery'),
            'reverseOrder'       => TeaThemeOptions::__('Reverse order'),

            //Edit Image
            'imageDetailsTitle'     => TeaThemeOptions::__('Image Details'),
            'imageReplaceTitle'     => TeaThemeOptions::__('Replace Image'),
            'imageDetailsCancel'    => TeaThemeOptions::__('Cancel Edit'),
            'editImage'             => TeaThemeOptions::__('Edit Image'),

            //Crop Image
            'chooseImage' => TeaThemeOptions::__('Choose Image'),
            'selectAndCrop' => TeaThemeOptions::__('Select and Crop'),
            'skipCropping' => TeaThemeOptions::__('Skip Cropping'),
            'cropImage' => TeaThemeOptions::__('Crop Image'),
            'cropYourImage' => TeaThemeOptions::__('Crop your image'),
            'cropping' => TeaThemeOptions::__('Cropping&hellip;'),
            'suggestedDimensions' => TeaThemeOptions::__('Suggested image dimensions:'),
            'cropError' => TeaThemeOptions::__('There has been an error cropping your image.'),

            //Edit Audio
            'audioDetailsTitle'     => TeaThemeOptions::__('Audio Details'),
            'audioReplaceTitle'     => TeaThemeOptions::__('Replace Audio'),
            'audioAddSourceTitle'   => TeaThemeOptions::__('Add Audio Source'),
            'audioDetailsCancel'    => TeaThemeOptions::__('Cancel Edit'),

            //Edit Video
            'videoDetailsTitle'     => TeaThemeOptions::__('Video Details'),
            'videoReplaceTitle'     => TeaThemeOptions::__('Replace Video'),
            'videoAddSourceTitle'   => TeaThemeOptions::__('Add Video Source'),
            'videoDetailsCancel'    => TeaThemeOptions::__('Cancel Edit'),
            'videoSelectPosterImageTitle' => TeaThemeOptions::__('Select Poster Image'),
            'videoAddTrackTitle'    => TeaThemeOptions::__('Add Subtitles'),

            //Playlist
            'playlistDragInfo'    => TeaThemeOptions::__('Drag and drop to reorder tracks.'),
            'createPlaylistTitle' => TeaThemeOptions::__('Create Audio Playlist'),
            'editPlaylistTitle'   => TeaThemeOptions::__('Edit Audio Playlist'),
            'cancelPlaylistTitle' => TeaThemeOptions::__('&#8592; Cancel Audio Playlist'),
            'insertPlaylist'      => TeaThemeOptions::__('Insert audio playlist'),
            'updatePlaylist'      => TeaThemeOptions::__('Update audio playlist'),
            'addToPlaylist'       => TeaThemeOptions::__('Add to audio playlist'),
            'addToPlaylistTitle'  => TeaThemeOptions::__('Add to Audio Playlist'),

            //Video Playlist
            'videoPlaylistDragInfo'    => TeaThemeOptions::__('Drag and drop to reorder videos.'),
            'createVideoPlaylistTitle' => TeaThemeOptions::__('Create Video Playlist'),
            'editVideoPlaylistTitle'   => TeaThemeOptions::__('Edit Video Playlist'),
            'cancelVideoPlaylistTitle' => TeaThemeOptions::__('&#8592; Cancel Video Playlist'),
            'insertVideoPlaylist'      => TeaThemeOptions::__('Insert video playlist'),
            'updateVideoPlaylist'      => TeaThemeOptions::__('Update video playlist'),
            'addToVideoPlaylist'       => TeaThemeOptions::__('Add to video playlist'),
            'addToVideoPlaylistTitle'  => TeaThemeOptions::__('Add to Video Playlist'),
        );

        /**
         * Filter the media view settings.
         *
         * @since WordPress 3.5.0
         *
         * @param array   $settings List of media view settings.
         * @param WP_Post $post     Post object.
         */
        $settings = apply_filters('media_view_settings', $settings, $post);

        /**
         * Filter the media view strings.
         *
         * @since WordPress 3.5.0
         *
         * @param array   $strings List of media view strings.
         * @param WP_Post $post    Post object.
         */
        $strings = apply_filters('media_view_strings', $strings,  $post);

        $strings['settings'] = $settings;

        //Ensure we enqueue media-editor first, that way media-views is
        //registered internally before we try to localize it. see #24724.
        wp_enqueue_script('media-editor');
        wp_localize_script('media-views', '_wpMediaViewsL10n', $strings);

        wp_enqueue_script('media-audiovideo');
        wp_enqueue_style('media-views');

        if (TTO_IS_ADMIN) {
            wp_enqueue_script('mce-view');
            wp_enqueue_script('image-edit');
        }

        wp_enqueue_style('imgareaselect');
        wp_plupload_default_settings();

        require_once ABSPATH . WPINC . '/media-template.php';
        add_action('admin_footer', 'wp_print_media_templates');
        add_action('wp_footer', 'wp_print_media_templates');
        add_action('customize_controls_print_footer_scripts', 'wp_print_media_templates');

        /**
         * Fires at the conclusion of wp_enqueue_media_tto().
         *
         * @since WordPress 3.5.0
         */
        do_action('wp_enqueue_media_tto');
    }
}
