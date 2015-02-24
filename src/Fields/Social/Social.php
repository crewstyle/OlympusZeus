<?php

namespace Takeatea\TeaThemeOptions\Fields\Social;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA SOCIAL FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'social',
 *     'title' => 'Big Brother is watching you...',
 *     'id' => 'my_social_field_id',
 *     'description' => '...Or not!',
 *     'expandable' => true,
 *     'default' => array(
 *         'facebook' => array(
 *             'display' => '1',
 *             'label' => 'Become a fan',
 *             'link' => 'http://www.facebook.com/takeatea'
 *         ),
 *         'twitter' => array(
 *             'display' => '1',
 *             'label' => 'Follow us',
 *             'link' => 'https://twitter.com/takeatea'
 *         ),
 *         'instagram' => array(
 *             'display' => '0',
 *             'label' => 'Take a shot',
 *             'link' => 'http://instagram.com/takeatea'
 *         ),
 *         'rss' => array(
 *             'label' => 'Subscribe to our feed'
 *         )
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Social
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Social
 * @since 2.0.0
 *
 */
class Social extends TeaFields
{
    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     * @param string $prefix
     *
     * @since 2.0.0
     */
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Do not display field on CPTs
        if (!empty($post)) {
            return;
        }

        //Check if an id is defined at least
        $this->checkId($content);

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Social', TTO_I18N);
        $default = isset($content['default']) ? $content['default'] : array();
        $description = isset($content['description']) ? $content['description'] : '';
        $expandable = isset($content['expandable']) && is_bool($content['expandable']) ? $content['expandable'] : true;

        //Get the social networks
        $socials = $this->getDefaults('social');

        //Count options
        $count = count($default);
        $socials = array();

        //Check if Google Font has already been included
        if (!isset($this->includes['socialtemplate'])) {
            $this->includes['socialtemplate'] = true;

            //Define our stylesheets
            $socials = $this->getDefaults('social');
        }

        //Get all social networks added
        $diff = array_diff_key($default, $socials);

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, $default);
            $vals = empty($vals) ? array() : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, false);
            $vals = empty($vals) ? array() : (is_array($vals) ? $vals : array($vals));
        }

        //Get all social networks added and not saved yet
        $news = array_diff_key($diff, $vals);

        //Get template
        include(TTO_PATH.'/Fields/Social/in_pages.tpl.php');
    }
}
