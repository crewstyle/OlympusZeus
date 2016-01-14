<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Upload field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Upload
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/upload
 * @see https://olympus.readme.io/docs/upload-multiple
 *
 */

class Upload extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-upload';

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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Upload'),
            'default' => isset($content['default']) ? $content['default'] : array(),
            'library' => isset($content['library']) ? $content['library'] : 'image',
            'description' => isset($content['description']) ? $content['description'] : '',
            'multiple' => isset($content['multiple']) && $content['multiple'] ? true : false,
            'expand' => isset($content['expand']) && $content['expand'] ? true : false,
            'alt' => isset($content['alt']) ? $content['alt'] : '',
            'caption' => isset($content['caption']) ? $content['caption'] : '',
            'can_upload' => $this->getCanUpload(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_add_media' => OlympusZeus::translate('Add media'),
            't_add_medias' => OlympusZeus::translate('Add medias'),
            't_delete_item' => OlympusZeus::translate('Delete selection'),
            't_delete_all' => OlympusZeus::translate('Delete all medias'),
            't_cannot_upload' => OlympusZeus::translate('It seems you are not able to upload files.'),

            't_sizes' => OlympusZeus::translate('Available sizes'),
            't_size_full' => OlympusZeus::translate('Full size media'),
        );

        //Fix bug with PDF
        $template['library'] = 'pdf' == $template['library'] ? 'application/pdf' : $template['library'];

        //Get titles
        $template['t_alt'] = !empty($template['alt']) ? $template['alt'] : OlympusZeus::translate('Alternative text');
        $template['t_caption'] = !empty($template['caption']) ? $template['caption'] : OlympusZeus::translate('Caption');

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/upload.html.twig', $template);
    }
}
