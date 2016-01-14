<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Background field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Background
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/background
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
     * @since 4.0.0
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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Background'),
            'height' => isset($content['height']) ? $content['height'] : '60',
            'width' => isset($content['width']) ? $content['width'] : '150',
            'description' => isset($content['description']) ? $content['description'] : '',
            'backgrounds' => isset($content['backgrounds']) && $content['backgrounds'] ? true : false,
            'can_upload' => $this->getCanUpload(),
            'delete' => OlympusZeus::translate('Delete selection'),
            'details' => $this->getBackgroundDetails(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_add_background' => OlympusZeus::translate('Add background'),
            't_cannot_upload' => OlympusZeus::translate('It seems you are not able to upload files.'),
            't_remove' => OlympusZeus::translate('Remove background'),

            't_choose_bg' => OlympusZeus::translate('Define a background image'),
            't_choose_color' => OlympusZeus::translate('Define a background color'),
            't_choose_position_size' => OlympusZeus::translate('Define a background'),
            't_choose_position' => OlympusZeus::translate('position'),
            't_choose_size' => OlympusZeus::translate('size'),
            't_choose_repeat' => OlympusZeus::translate('Define a background repeat'),
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
     * @since 4.0.0
     */
    public function getBackgroundDetails()
    {
        return array(
            'position'  => array(
                '0 0'           => OlympusZeus::translate('Left top'),
                '50% 0'         => OlympusZeus::translate('Center top'),
                '100% 0'        => OlympusZeus::translate('Right top'),
                '0 50%'         => OlympusZeus::translate('Left middle'),
                '50% 50%'       => OlympusZeus::translate('Center middle'),
                '100% 50%'      => OlympusZeus::translate('Right middle'),
                '0 100%'        => OlympusZeus::translate('Left bottom'),
                '50% 100%'      => OlympusZeus::translate('Center bottom'),
                '100% 100%'     => OlympusZeus::translate('Right bottom'),
            ),
            'repeat'    => array(
                'no-repeat'     => OlympusZeus::translate('Displayed only once.'),
                'repeat-x'      => OlympusZeus::translate('Repeated horizontally only.'),
                'repeat-y'      => OlympusZeus::translate('Repeated vertically only.'),
                'repeat'        => OlympusZeus::translate('Repeated all the way.'),
            ),
            'size'      => array(
                'auto'          => array(
                    'times',
                    OlympusZeus::translate('Normal state')
                ),
                'cover'         => array(
                    'arrows-h',
                    OlympusZeus::translate('As large as possible')
                ),
                'contain'       => array(
                    'arrows',
                    OlympusZeus::translate('Width and height can fit inside the content area')
                ),
            ),
        );
    }
}
