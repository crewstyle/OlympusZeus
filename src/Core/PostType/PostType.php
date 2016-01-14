<?php

namespace crewstyle\OlympusZeus\Core\Posttype;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Action\Action;
use crewstyle\OlympusZeus\Core\Posttype\PosttypeEngine;

/**
 * Gets its own post type.
 *
 * @package Olympus Zeus
 * @subpackage Core\Posttype\Posttype
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Posttype
{
    /**
     * @var PosttypeEngine
     */
    protected $posttypeEngine = null;

    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        //Initialize search
        $this->posttypeEngine = new PosttypeEngine();

        //Hooks
        add_filter('olz_template_footer_urls', function ($urls, $identifier) {
            return array_merge($urls, array(
                'posttypes' => array(
                    'url' => admin_url('admin.php?page='.$identifier.'&do=olz-action&from=footer&make=posttypes'),
                    'label' => OlympusZeus::translate('post types'),
                )
            ));
        }, 10, 2);
    }

    /**
     * Add a post type to the theme options panel.
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

        $this->posttypeEngine->addPostType($configs);
    }

    /**
     * Register post types.
     *
     * @since 4.0.0
     */
    public function buildPostTypes()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->posttypeEngine->buildPostTypes();
    }
}
