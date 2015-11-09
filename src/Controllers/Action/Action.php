<?php

namespace crewstyle\TeaThemeOptions\Controllers\Action;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
//use crewstyle\TeaThemeOptions\Network\Network;
use crewstyle\TeaThemeOptions\PostType\Engine\Engine as PostTypeEngine;
use crewstyle\TeaThemeOptions\Search\Search;
use crewstyle\TeaThemeOptions\Term\Engine\Engine as TermEngine;

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
 * @subpackage Controllers\Action\Action
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
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
     * @since 3.0.0
     */
    public function __construct($identifier)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get identifier and current page
        $this->identifier = $identifier;

        //Get capabilities
        $capabilities = TeaThemeOptions::getConfigs('capabilities');

        //Define capabilities
        if (empty($capabilities)) {
            $this->updateFooterCapabilities(false);
        }

        //Make good action
        $this->initialize();
    }

    /**
     * Build action.
     *
     * @param string $page Contain current page.
     * @param string $for Contain data on what to build.
     * @param boolean $mode Define if we use mode statement on post types.
     *
     * @since 3.0.0
     */
    public static function buildAction($page = '', $for = '', $mode = false)
    {
        if (empty($for)) {
            return 'admin.php?page=' . $page;
        }

        $action = 'admin.php?page=' . $page . (!$mode ? '&action=tea-to-action&for=' : '&' . $mode);

        $links = array(
            //footer links
            'footer',
            //notifications
            'notice-enable',
            'notice-dismiss',
            //post types
            'posttype',
            //search
            'search',
        );

        if (!$mode && in_array($for, $links)) {
            return $action.$for;
        }
        else {
            return !$mode ? $action.'dashboard' : $action;
        }
    }

    /**
     * Initialize actions.
     *
     * @since 3.0.0
     */
    public function initialize()
    {
        //Get current user action
        $action = isset($_REQUEST['action']) ? (string) $_REQUEST['action'] : '';

        if ('tea-to-action' != $action) {
            return;
        }

        //Get the kind of action asked
        $for = isset($_REQUEST['for']) ? (string) $_REQUEST['for'] : '';

        //Update from footer links...
        if ('footer' == $for) {
            //Get the kind of action asked
            $make = isset($_REQUEST['make']) ? (string) $_REQUEST['make'] : '';

            //Update capabilities...
            if ('capabilities' == $make) {
                $this->updateFooterCapabilities();
            }
            //...Or update posttypes options...
            elseif ('posttypes' == $make) {
                $this->updateFooterPostTypes();
            }
            //...Or update rewrite rules...
            elseif ('terms' == $make) {
                $this->updateFooterTerms();
            }
        }
        //...Or update network data...
        elseif ('network' == $for || 'callback' == $for) {
            $this->updateNetwork($_REQUEST);
        }
        //...Or display/dismiss notice on dashboard...
        elseif ('notice-enable' == $for || 'notice-dismiss' == $for) {
            $status = 'notice-enable' == $for ? true : false;
            $this->updateNotice($status);
        }
        //...Or update posttypes data...
        elseif ('posttype' == $for) {
            $this->updatePostType($_REQUEST);
        }
        //...Or update search data...
        elseif ('search' == $for) {
            $this->updateSearch($_REQUEST);
        }
        //...Or update options...
        elseif ('settings' == $for) {
            $this->updateSettings($_REQUEST, $_FILES);
        }
    }

    /**
     * WP Redirect.
     *
     * @param string $page Contain current page.
     *
     * @since 3.0.0
     */
    public static function redirect($page = '')
    {
        if (empty($page)) {
            return;
        }

        wp_safe_redirect(admin_url($page));
    }

    /**
     * Create roles and capabilities.
     *
     * @param boolean $redirect Define if the TTO has to make a redirect
     *
     * @since 3.0.0
     */
    protected function updateFooterCapabilities($redirect = true)
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

        //Update DB
        TeaThemeOptions::setConfigs('capabilities', true);

        //Redirect to Tea TO homepage
        if ($redirect) {
            $action = self::buildAction($this->identifier, 'dashboard');
            wp_safe_redirect(admin_url($action));
        }
    }

    /**
     * Update PostTypes options.
     *
     * @param boolean $redirect Define if the TTO has to make a redirect
     *
     * @since 3.0.0
     */
    protected function updateFooterPostTypes($redirect = true)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $index = PostTypeEngine::getIndex();

        //Get all registered pages
        $pts = TeaThemeOptions::getConfigs($index);

        //Check params and if a master page already exists
        if (!empty($pts)) {
            //Delete cpts configurations to built them back
            TeaThemeOptions::setConfigs($index, array());
        }

        //Flush all rewrite rules
        flush_rewrite_rules();

        //Redirect to Tea TO homepage
        if ($redirect) {
            $action = self::buildAction($this->identifier, 'dashboard');
            wp_safe_redirect(admin_url($action));
        }
    }

    /**
     * Update rewrite rules options.
     *
     * @param boolean $redirect Define if the TTO has to make a redirect
     *
     * @since 3.0.0
     */
    protected function updateFooterTerms($redirect = true)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $index = TermEngine::getIndex();

        //Get all registered pages
        $pts = TeaThemeOptions::getConfigs($index);

        //Check params and if a master page already exists
        if (!empty($pts)) {
            //Delete cpts configurations to built them back
            TeaThemeOptions::setConfigs($index, array());
        }

        //Flush all rewrite rules
        flush_rewrite_rules();

        //Redirect to Tea TO homepage
        if ($redirect) {
            $action = self::buildAction($this->identifier, 'dashboard');
            wp_safe_redirect(admin_url($action));
        }
    }

    /**
     * Update Networks datum.
     *
     * @param array $request Contains all data in $_REQUEST
     * @todo Networks!
     *
     * @since 3.0.0
     */
    protected function updateNetwork($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Make actions
        $network = new Network();
        $network->makeActions($request);

        /*//Get action
        $for = isset($request['for']) ? $request['for'] : '';

        //Check if a network connection is asked
        if ('callback' != $for && 'network' != $for) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
                definition. You need to specify a network to make the
                connection happens.')
            );
        }

        //Defaults variables
        $page = empty($this->current) ? $this->identifier : $this->current;

        //Make the magic
        //$field = new Network();
        //$field->setCurrentPage($page);
        //$field->actionNetwork($request);*/
    }

    /**
     * Hide or unhide notice admin message.
     *
     * @param boolean $dismiss Define if we have to hide or unhide notice
     *
     * @since 3.0.0
     */
    protected function updateNotice($dismiss)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Set default
        TeaThemeOptions::setConfigs('notice', $dismiss);
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
     * Update Search datum.
     *
     * @param array $request Contains all data in $_REQUEST
     *
     * @since 3.0.0
     */
    protected function updateSearch($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Make actions
        $search = new Search(true, true, false);
        $search->makeActions($request);
    }

    /**
     * Register $_POST and $_FILES into transients.
     *
     * @uses wp_handle_upload()
     * @param array $request Contains all data in $_REQUEST
     * @param array $files Contains all data in $_FILES
     *
     * @since 3.0.0
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
            if (in_array($k, array('action', 'for', 'updated', 'submit'))) {
                continue;
            }

            //Check the key for special "NONE" value
            $v = 'NONE' == $v || empty($v) ? '' : $v;

            //Register option
            TeaThemeOptions::setOption($k, $v);
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
    }
}
