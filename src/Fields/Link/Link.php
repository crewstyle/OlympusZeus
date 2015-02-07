<?php

namespace Takeatea\TeaThemeOptions\Fields\Link;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA LINK FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'link',
 *     'title' => 'Never gonna give you up!',
 *     'id' => 'my_link_field_id',
 *     'description' => 'Never gonna give you up!',
 *     'std' => array(
 *         'url' => "http://www.youtube.com/watch?v=BROWqjuTM0g",
 *         'label' => 'Never gonna get you down!', //optional, display link instead
 *         'target' => '_blank', //optional, "_self" by default
 *         'rel' => 'nofollow', //optional, "external" by default
 *     ),
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Link
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Link
 * @since 1.5.2.14
 *
 */
class Link extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.5.0
     */
    public function __construct(){}


    //------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     * @param string $prefix Contains meta post prefix
     *
     * @since 1.5.0
     */
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Check current post on CPTs
        if (empty($post)) {
            //Check if an id is defined at least
            $this->checkId($content);
        }
        else {
            //Modify content
            $content = $content['args']['contents'];
        }

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Link', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';

        //Default values
        $std = isset($content['std']) ? $content['std'] : array();
        $std['url'] = isset($std['url']) ? $std['url'] : '';
        $std['label'] = isset($std['label']) ? $std['label'] : $std['url'];
        $std['target'] = isset($std['target']) ? $std['target'] : '_self';
        $std['rel'] = isset($std['rel']) ? $std['rel'] : 'external';

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, $std);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
            $vals = empty($vals) ? $std : (is_array($vals) ? $vals[0] : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Link/in_pages.tpl.php');
    }
}
