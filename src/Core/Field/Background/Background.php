<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Background;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO BACKGROUND FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'background',
 *     'title' => 'A new paint :D',
 *     'id' => 'my_background_field_id',
 *     'default' => array(
 *         'color' => '#ffffff',
 *         'image' => 'my_custom_default_background_url',
 *         'repeat' => 'no-repeat',
 *         'position' => 'left top',
 *         'size' => 'auto',
 *     ),
 *     'description' => "It's tricky :)",
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Background
 *
 * Class used to build Background field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Background
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Background extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-picture-o';

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Display HTML component.
     *
     * @param array $content Contains all field data
     * @param array $details Contains all field options
     *
     * @since 3.0.0
     */
    public function prepareField($content, $details = array())
    {
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $tpl = empty($prefix) ? 'pages' : 'terms';

        //Build defaults data
        $template = array(
            'id' => $content['id'],
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Background'),
            'height' => isset($content['height']) ? $content['height'] : '60',
            'width' => isset($content['width']) ? $content['width'] : '150',
            'description' => isset($content['description']) ? $content['description'] : '',
            'backgrounds' => isset($content['backgrounds']) && $content['backgrounds'] ? true : false,
            'can_upload' => $this->getCanUpload(),
            'delete' => TeaThemeOptions::__('Delete selection'),
            'details' => $this->getBackgroundDetails(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_add_background' => TeaThemeOptions::__('Add background'),
            't_cannot_upload' => TeaThemeOptions::__('It seems you are not able to upload files.'),
            't_remove' => TeaThemeOptions::__('Remove background'),

            't_choose_bg' => TeaThemeOptions::__('Define a background image'),
            't_choose_color' => TeaThemeOptions::__('Define a background color'),
            't_choose_position_size' => TeaThemeOptions::__('Define a background'),
            't_choose_position' => TeaThemeOptions::__('position'),
            't_choose_size' => TeaThemeOptions::__('size'),
            't_choose_repeat' => TeaThemeOptions::__('Define a background repeat'),
        );

        //Build defaults values
        $template['default'] = isset($content['default']) ? $content['default'] : array();
        $template['default']['image'] = isset($content['default']['image']) ? $content['default']['image'] : '';
        $template['default']['color'] = isset($content['default']['color']) ? $content['default']['color'] : '';
        $template['default']['repeat'] = isset($content['default']['repeat']) 
            ? $content['default']['repeat'] 
            : 'repeat';
        $template['default']['position'] = isset($content['default']['position']) 
            ? $content['default']['position'] 
            : '0 0';
        $template['default']['size'] = isset($content['default']['size']) 
            ? $content['default']['size'] 
            : 'auto';

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/background.html.twig', $template);
    }

    /**
     * Return all available background details.
     *
     * @return array $array Contains all background details
     *
     * @since 3.0.0
     */
    public function getBackgroundDetails()
    {
        return array(
            'position'  => array(
                '0 0'           => TeaThemeOptions::__('Left top'),
                '50% 0'         => TeaThemeOptions::__('Center top'),
                '100% 0'        => TeaThemeOptions::__('Right top'),
                '0 50%'         => TeaThemeOptions::__('Left middle'),
                '50% 50%'       => TeaThemeOptions::__('Center middle'),
                '100% 50%'      => TeaThemeOptions::__('Right middle'),
                '0 100%'        => TeaThemeOptions::__('Left bottom'),
                '50% 100%'      => TeaThemeOptions::__('Center bottom'),
                '100% 100%'     => TeaThemeOptions::__('Right bottom'),
            ),
            'repeat'    => array(
                'no-repeat'     => TeaThemeOptions::__('Displayed only once.'),
                'repeat-x'      => TeaThemeOptions::__('Repeated horizontally only.'),
                'repeat-y'      => TeaThemeOptions::__('Repeated vertically only.'),
                'repeat'        => TeaThemeOptions::__('Repeated all the way.'),
            ),
            'size'      => array(
                'auto'          => array(
                    'times',
                    TeaThemeOptions::__('Normal state')
                ),
                'cover'         => array(
                    'arrows-h',
                    TeaThemeOptions::__('As large as possible')
                ),
                'contain'       => array(
                    'arrows',
                    TeaThemeOptions::__('Width and height can fit inside the content area')
                ),
            ),
        );
    }
}
