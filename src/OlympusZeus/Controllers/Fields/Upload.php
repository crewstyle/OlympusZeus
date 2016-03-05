<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Upload field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Upload
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-upload
 *
 */

class Upload extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-upload';

    /**
     * @var string
     */
    protected $template = 'fields/upload.html.twig';

    /**
     * Prepare HTML component.
     *
     * @param array $content
     * @param array $details
     *
     * @since 5.0.0
     */
    protected function getVars($content, $details = [])
    {
        //Build defaults
        $defaults = [
            'id' => '',
            'title' => Translate::t('Upload'),
            'default' => [],
            'description' => '',
            'library' => 'image',
            'multiple' => false,
            'expand' => false,
            'alt' => '',
            'caption' => '',
            'can_upload' => OLZ_CAN_UPLOAD,

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //texts
            't_add_media' => Translate::t('Add media'),
            't_add_medias' => Translate::t('Add medias'),
            't_delete_item' => Translate::t('Delete selection'),
            't_delete_all' => Translate::t('Delete all medias'),
            't_cannot_upload' => Translate::t('It seems you are not able to upload files.'),

            't_sizes' => Translate::t('Available sizes'),
            't_size_full' => Translate::t('Full size media'),

            't_alt' => Translate::t('Alternative text'),
            't_caption' => Translate::t('Caption'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);
        $vars['library'] = 'pdf' === $vars['library'] ? 'application/pdf' : $vars['library'];

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Update vars
        $this->getField()->setVars($vars);
    }
}
