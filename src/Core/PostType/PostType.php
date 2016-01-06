<?php

namespace crewstyle\TeaThemeOptions\Core\PostType;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Action\Action;
use crewstyle\TeaThemeOptions\Core\PostType\Engine\Engine;

/**
 * TTO POSTTYPE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO PostType
 *
 * To get its own post type.
 *
 * @package Tea Theme Options
 * @subpackage Core\PostType\PostType
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class PostType
{
    /**
     * @var Engine
     */
    protected $engine = null;

    /**
     * Constructor.
     *
     * @since 3.3.0
     */
    public function __construct()
    {
        //Initialize search
        $this->engine = new Engine();

        //Hooks
        add_filter('tto_template_footer_urls', function ($urls, $identifier) {
            return array_merge($urls, array(
                'posttypes' => array(
                    'url' => admin_url('admin.php?page='.$identifier.'&do=tto-action&from=footer&make=posttypes'),
                    'label' => TeaThemeOptions::__('post types'),
                )
            ));
        }, 10, 2);
    }

    /**
     * Add a post type to the theme options panel.
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

        $this->engine->addPostType($configs);
    }

    /**
     * Register post types.
     *
     * @since 3.3.0
     */
    public function buildPostTypes()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->buildPostTypes();
    }
}
