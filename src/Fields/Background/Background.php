<?php

namespace Takeatea\TeaThemeOptions\Fields\Background;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA BACKGROUND FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'background',
 *     'title' => 'A new paint :D',
 *     'id' => 'my_background_field_id',
 *     'std' => array(
 *         'image' => 'my_default_background_url',
 *         'image_custom' => 'my_custom_default_background_url',
 *         'color' => '#ffffff',
 *         'repeat' => 'no-repeat',
 *         'position' => array(
 *             'x' => 'left',
 *             'y' => 'top'
 *         )
 *     ),
 *     'description' => "It's tricky :)",
 *     'default' => true
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Background
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Background
 * @since 1.4.0
 *
 */
class Background extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.3.0
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
     * @todo get default background values on FIELD abstract class and use of array_merge() to get user's default values
     *
     * @since 1.4.0
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Background', TTO_I18N);
        $height = isset($content['height']) ? $content['height'] : '60';
        $width = isset($content['width']) ? $content['width'] : '150';
        $description = isset($content['description']) ? $content['description'] : '';
        $default = isset($content['default']) && (true === $content['default'] || '1' == $content['default']) ? true : false;
        $can_upload = $this->getCanUpload();
        $delete = __('Delete selection', TTO_I18N);

        //Default values
        $std = isset($content['std']) ? $content['std'] : array();
        $std['image'] = isset($content['std']['image']) ? $content['std']['image'] : '';
        $std['image_custom'] = isset($content['std']['image_custom']) ? $content['std']['image_custom'] : '';
        $std['color'] = isset($content['std']['color']) ? $content['std']['color'] : '';
        $std['position'] = isset($content['std']['position']) ? $content['std']['position'] : array();
        $std['position']['x'] = isset($content['std']['position']['x']) ? $content['std']['position']['x'] : 'left';
        $std['position']['y'] = isset($content['std']['position']['y']) ? $content['std']['position']['y'] : 'top';
        $std['repeat'] = isset($content['std']['repeat']) ? $content['std']['repeat'] : 'repeat';

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($default) {
            $default = $this->getDefaults('images');
            $options = array_merge($default, $options);
        }

        //Positions
        $details = $this->getDefaults('background-details');
        $url = TTO_URI . '/src/Fields/Background/img/';

        //Check selected
        $val = TeaThemeOptions::get_option($prefix.$id, $std);

        //Get template
        include(TTO_PATH.'/Fields/Background/in_pages.tpl.php');
    }
}
