<?php

namespace crewstyle\TeaThemeOptions\Controllers\Hook;

use crewstyle\TeaThemeOptions\TeaThemeOptions;

/**
 * TTO HOOK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Hook
 *
 * To get its own hooks.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Hook\Hook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 * @todo Include https://github.com/crewstyle/clean-wordpress
 *
 */
class Hook
{
    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        //Admin panel
        if (TTO_IS_ADMIN) {
            //Build backend hooks
            $this->backendHooks();
        }
        else {
            //Build frontend hooks
            $this->frontendHooks();
        }

        //Build common hooks
        $this->commonHooks();
    }

    /**
     * Build admin hooks.
     *
     * @since 3.0.0
     */
    public function backendHooks()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //i18n
        $locale = apply_filters('theme_locale', get_locale(), TTO_I18N);
        load_textdomain(TTO_I18N, TTO_PATH.'/languages/'.$locale.'.mo');

        //Register admin page action hook
        add_action('admin_menu', array(&$this, 'hookAdminAssets'), 999);

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
    }

    /**
     * Build admin hooks.
     *
     * @since 3.0.0
     */
    public function frontendHooks()
    {
        //Admin panel
        if (TTO_IS_ADMIN) {
            return;
        }
    }

    /**
     * Build admin hooks.
     *
     * @since 3.0.0
     */
    public function commonHooks()
    {
        add_action('login_head', array(&$this, 'hookLoginPage'));
    }

    /**
     * Hook building assets.
     *
     * @since 3.0.0
     */
    public function hookAdminAssets()
    {
        add_action('admin_print_scripts', array(&$this, 'hookAdminScripts'));
        add_action('admin_print_styles', array(&$this, 'hookAdminStyles'));
    }

    /**
     * Hook building scripts.
     *
     * @uses wp_enqueue_media_tto()
     * @uses wp_enqueue_script()
     *
     * @since 3.0.0
     */
    public function hookAdminScripts()
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
     * @since 3.0.0
     */
    public function hookAdminStyles()
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
     * Display CSS form login page.
     *
     * @since 3.0.0
     */
    public function hookLoginPage()
    {
        echo '<link href="'.TTO_URI.'/assets/css/teato.login.css?ver=v'.TTO_VERSION_NUM
            .'" rel="stylesheet" type="text/css" />';
    }

    /**
     * Update rewrite rules options.
     *
     * @param array $args Define if the TTO has to make a redirect
     *
     * @since 3.0.0
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
         * @since 3.5.0
         *
         * @param array   $settings List of media view settings.
         * @param WP_Post $post     Post object.
         */
        $settings = apply_filters('media_view_settings', $settings, $post);

        /**
         * Filter the media view strings.
         *
         * @since 3.5.0
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
         * @since 3.5.0
         */
        do_action('wp_enqueue_media_tto');
    }
}
