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
 *     'default' => array(
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
 *     'backgrounds' => true
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
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
 * @since 2.0.0
 *
 */
class Background extends TeaFields
{
    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    public function __construct(){}

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     * @param string $prefix
     * @todo get default background values on FIELD abstract class and use of array_merge() to get user's default values
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Background', TTO_I18N);
        $height = isset($content['height']) ? $content['height'] : '60';
        $width = isset($content['width']) ? $content['width'] : '150';
        $description = isset($content['description']) ? $content['description'] : '';
        $backgrounds = isset($content['backgrounds']) && (true === $content['backgrounds'] || '1' == $content['backgrounds']) ? true : false;
        $can_upload = $this->getCanUpload();
        $delete = __('Delete selection', TTO_I18N);

        //Default values
        $default = isset($content['default']) ? $content['default'] : array();
        $default['image'] = isset($content['default']['image']) ? $content['default']['image'] : '';
        $default['image_custom'] = isset($content['default']['image_custom']) ? $content['default']['image_custom'] : '';
        $default['color'] = isset($content['default']['color']) ? $content['default']['color'] : '';
        $default['position'] = isset($content['default']['position']) ? $content['default']['position'] : array();
        $default['position']['x'] = isset($content['default']['position']['x']) ? $content['default']['position']['x'] : 'left';
        $default['position']['y'] = isset($content['default']['position']['y']) ? $content['default']['position']['y'] : 'top';
        $default['repeat'] = isset($content['default']['repeat']) ? $content['default']['repeat'] : 'repeat';

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($backgrounds) {
            $backgrounds = $this->getDefaults('images');
            $options = array_merge($backgrounds, $options);
        }

        //Positions
        $details = $this->getDefaults('background-details');
        $url = TTO_URI . '/src/Fields/Background/img/';

        //Check selected
        $val = TeaThemeOptions::get_option($prefix.$id, $default);

        //Get template
        include(TTO_PATH.'/Fields/Background/in_pages.tpl.php');
    }
}
