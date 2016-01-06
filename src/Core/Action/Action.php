<?php

namespace crewstyle\TeaThemeOptions\Core\Action;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\PostType\Engine\Engine as PostTypeEngine;
use crewstyle\TeaThemeOptions\Core\Term\Engine\Engine as TermEngine;

/**
 * TTO ACTION
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Action
 *
 * Class used to make actions from GET param.
 *
 * @package Tea Theme Options
 * @subpackage Core\Action\Action
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
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
        if (!TTO_IS_ADMIN) {
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
     * @since 3.3.0
     */
    public function initialize()
    {
        //Get capabilities
        $capabilities = TeaThemeOptions::getConfigs('capabilities', 'backend');

        //Define capabilities
        if (empty($capabilities)) {
            $this->initializeDatum();
            return;
        }

        //Get current user action
        $action = isset($_REQUEST['do']) ? (string) $_REQUEST['do'] : '';

        if ('tto-action' !== $action) {
            return;
        }

        //Get the kind of action asked
        $from = isset($_REQUEST['from']) ? (string) $_REQUEST['from'] : '';

        //Simply display a message confirmation on TTO dashboard
        if ('dashboard' === $from) {
            TeaThemeOptions::notify('updated', TeaThemeOptions::__('Your Tea Theme Options\' settings are updated.'));
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
     * @since 3.3.0
     */
    protected function initializeDatum()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
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
        $wp_roles->add_cap('administrator', TTO_WP_CAP_MAX);

        //Capabilities
        TeaThemeOptions::setConfigs('capabilities', TTO_WP_CAP_MAX);

        //Backend
        TeaThemeOptions::setConfigs('backendhooks', array(
            'emojicons' => true,
            'versioncheck' => true,
            'baricons' => true,
            'menus' => true,
        ));

        //Frontend
        TeaThemeOptions::setConfigs('frontendhooks', array(
            'generated' => true,
            'bodyclass' => true,
            'emojicons' => true,
            'version' => true,
            'shortcodeformatter' => true,
        ));

        //Modules
        TeaThemeOptions::setConfigs('modules', array(
            'database' => true,
            'customlogin' => true,
            'sanitizedfilename' => true,
        ));

        //3rd-party modules
        TeaThemeOptions::setConfigs('3rd-search', false);
        TeaThemeOptions::setConfigs('3rd-related', false);
        TeaThemeOptions::setConfigs('3rd-comments', false);
        TeaThemeOptions::setConfigs('3rd-networks', false);
    }

    /**
     * Create roles and capabilities.
     *
     * @since 3.3.0
     */
    protected function updateFooter($make = 'capabilities')
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
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
            $wp_roles->add_cap('administrator', TTO_WP_CAP_MAX);

            //Update DB
            TeaThemeOptions::setConfigs('capabilities', true);
        }
        //Update post types or terms
        elseif ('posttypes' === $make || 'terms' === $make) {
            //Get post type's index
            $index = 'posttypes' === $make ? PostTypeEngine::getIndex() : TermEngine::getIndex();

            //Get all registered pages
            $contents = TeaThemeOptions::getConfigs($index);

            //Check params and if a master page already exists
            if (!empty($contents)) {
                //Delete configurations to built them back
                TeaThemeOptions::setConfigs($index, array());
            }

            //Flush all rewrite rules
            flush_rewrite_rules();
        }

        //Redirect to Tea TO homepage
        wp_safe_redirect(admin_url('admin.php?page='.$this->identifier.'&do=tto-action&from=dashboard'));
    }

    /**
     * Hide or unhide notice admin message.
     *
     * @param boolean $unhide Define if we have to hide or unhide notice
     *
     * @since 3.3.0
     */
    protected function updateNotice($unhide = true)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Set default
        TeaThemeOptions::setConfigs('notice', $unhide);
    }

    /**
     * Update Post type datum.
     *
     * @param array $request Contains all data in $_REQUEST
     *
     * @since 3.0.0
     */
    protected function updatePostType($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Make actions
        $posttype = new PostType(true, false);
        $posttype->makeActions($request);
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_REQUEST
     * @param array $files Contains all data in $_FILES
     *
     * @since 3.3.0
     */
    protected function updateSettings($request, $files)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
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
            if (preg_match('/^tto-configs-/', $k)) {
                $k = str_replace('tto-configs-', '', $k);
                TeaThemeOptions::setConfigs($k, $v);
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
                do_action('tto_action_update_settings', $k, $v);

                //Register option
                TeaThemeOptions::setOption($k, $v);
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
                TeaThemeOptions::setOption($k, $file['url']);
            }
        }

        //Notify
        TeaThemeOptions::notify('updated', TeaThemeOptions::__('The Tea Theme Options is updated.'));
    }
}
