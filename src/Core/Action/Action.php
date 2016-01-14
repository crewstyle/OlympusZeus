<?php

namespace crewstyle\OlympusZeus\Core\Action;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Posttype\Posttype;
use crewstyle\OlympusZeus\Core\Posttype\PosttypeEngine;
use crewstyle\OlympusZeus\Core\Term\TermEngine;

/**
 * Used to make actions from GET param.
 *
 * @package Olympus Zeus
 * @subpackage Core\Action\Action
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Action
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     *
     * @since 3.3.0
     */
    public function __construct($identifier)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Get identifier and current page
        $this->identifier = $identifier;

        //Make good action
        $this->initialize();
    }

    /**
     * Initialize actions.
     *
     * @since 4.0.0
     */
    public function initialize()
    {
        //Get capabilities
        $capabilities = OlympusZeus::getConfigs('capabilities', 'backend');

        //Define capabilities
        if (empty($capabilities)) {
            $this->initializeDatas();
            return;
        }

        //Get current user action
        $action = isset($_REQUEST['do']) ? (string) $_REQUEST['do'] : '';

        if ('olz-action' !== $action) {
            return;
        }

        //Get the kind of action asked
        $from = isset($_REQUEST['from']) ? (string) $_REQUEST['from'] : '';

        //Simply display a message confirmation on TTO dashboard
        if ('dashboard' === $from) {
            OlympusZeus::notify('updated', OlympusZeus::translate('Your Olympus\' settings are updated.'));
        }
        //Update from footer links...
        elseif ('footer' === $from) {
            //Get the kind of action asked
            $make = isset($_REQUEST['make']) ? (string) $_REQUEST['make'] : '';

            //Update capabilities, posttypes or rewrite rules...
            if (in_array($make, array('capabilities', 'posttypes', 'terms'))) {
                $this->updateFooter($make);
            }
        }
        //...Or display/dismiss notice on dashboard...
        elseif ('notice-enable' == $from || 'notice-dismiss' == $from) {
            $status = 'notice-enable' == $from ? true : false;
            $this->updateNotice($status);
        }
        //...Or update posttypes data...
        elseif ('posttype' == $from) {
            $this->updatePostType($_REQUEST);
        }
        //...Or update options...
        elseif ('settings' == $from) {
            $this->updateSettings($_REQUEST, $_FILES);
        }
    }

    /**
     * Initialize all TTO datum.
     *
     * @since 4.0.0
     */
    protected function initializeDatas()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Get global WP roles
        global $wp_roles;

        //Check them
        if (class_exists('WP_Roles')) {
            $wp_roles = !isset($wp_roles) ? new WP_Roles() : $wp_roles;
        }

        if (!is_object($wp_roles)) {
            return;
        }

        //Add custom role
        $wp_roles->add_cap('administrator', OLZ_WP_CAP_MAX);

        //Capabilities
        OlympusZeus::setConfigs('capabilities', OLZ_WP_CAP_MAX);

        //Backend
        OlympusZeus::setConfigs('backendhooks', array(
            'emojicons' => true,
            'versioncheck' => true,
            'baricons' => true,
            'menus' => true,
        ));

        //Frontend
        OlympusZeus::setConfigs('frontendhooks', array(
            'generated' => true,
            'bodyclass' => true,
            'emojicons' => true,
            'version' => true,
            'shortcodeformatter' => true,
        ));

        //Modules
        OlympusZeus::setConfigs('modules', array(
            'database' => true,
            'customlogin' => true,
            'sanitizedfilename' => true,
        ));

        //3rd-party modules
        OlympusZeus::setConfigs('3rd-search', false);
        OlympusZeus::setConfigs('3rd-related', false);
        OlympusZeus::setConfigs('3rd-comments', false);
        OlympusZeus::setConfigs('3rd-networks', false);
    }

    /**
     * Create roles and capabilities.
     *
     * @since 4.0.0
     */
    protected function updateFooter($make = 'capabilities')
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Update capabilities
        if ('capabilities' === $make) {
            //Get global WP roles
            global $wp_roles;

            //Check them
            if (class_exists('WP_Roles')) {
                $wp_roles = !isset($wp_roles) ? new WP_Roles() : $wp_roles;
            }

            if (!is_object($wp_roles)) {
                return;
            }

            //Add custom role
            $wp_roles->add_cap('administrator', OLZ_WP_CAP_MAX);

            //Update DB
            OlympusZeus::setConfigs('capabilities', true);
        }
        //Update post types or terms
        elseif ('posttypes' === $make || 'terms' === $make) {
            //Get post type's index
            $index = 'posttypes' === $make ? PosttypeEngine::getIndex() : TermEngine::getIndex();

            //Get all registered pages
            $contents = OlympusZeus::getConfigs($index);

            //Check params and if a master page already exists
            if (!empty($contents)) {
                //Delete configurations to built them back
                OlympusZeus::setConfigs($index, array());
            }

            //Flush all rewrite rules
            flush_rewrite_rules();
        }

        //Redirect to homepage
        wp_safe_redirect(admin_url('admin.php?page='.$this->identifier.'&do=olz-action&from=dashboard'));
    }

    /**
     * Hide or unhide notice admin message.
     *
     * @param boolean $unhide Define if we have to hide or unhide notice
     *
     * @since 4.0.0
     */
    protected function updateNotice($unhide = true)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Set default
        OlympusZeus::setConfigs('notice', $unhide);
    }

    /**
     * Update Post type datum.
     *
     * @param array $request Contains all data in $_REQUEST
     *
     * @since 4.0.0
     */
    protected function updatePostType($request)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Make actions
        $posttype = new Posttype(true, false);
        $posttype->makeActions($request);
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_REQUEST
     * @param array $files Contains all data in $_FILES
     *
     * @since 4.0.0
     */
    protected function updateSettings($request, $files)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Check request
        if (empty($request)) {
            return;
        }

        //Set all options
        foreach ($request as $k => $v) {
            //Don't register this default value
            if (in_array($k, array('do', 'from', 'updated', 'submit'))) {
                continue;
            }

            //Check the key for special "NONE" value
            $v = 'NONE' == $v || empty($v) ? '' : $v;

            //Check settings
            if (preg_match('/^olz-configs-/', $k)) {
                $k = str_replace('olz-configs-', '', $k);
                OlympusZeus::setConfigs($k, $v);
            }
            else {
                /**
                 * Fires for each data saved in DB.
                 *
                 * @param string $k Option's name
                 * @param string $v Option's value
                 *
                 * @since 3.3.0
                 */
                do_action('olz_action_update_settings', $k, $v);

                //Register option
                OlympusZeus::setOption($k, $v);
            }
        }

        //Check if files are attempting to be uploaded
        if (!empty($files)) {
            //Get required files
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            //Set all URL in transient
            foreach ($files as $k => $v) {
                //Don't do nothing if no file is defined
                if (empty($v['tmp_name'])) {
                    continue;
                }

                //Do the magic
                $file = wp_handle_upload($v, array('test_form' => false));

                //Register option and transient
                OlympusZeus::setOption($k, $file['url']);
            }
        }

        //Notify
        OlympusZeus::notify('updated', OlympusZeus::translate('The Olympus is updated.'));
    }
}
