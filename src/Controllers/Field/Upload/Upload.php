<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Upload;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO UPLOAD FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'upload',
 *     'title' => 'Upload',
 *     'id' => 'simple_upload',
 *     'description' => 'Simple description to upload panel'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Upload
 *
 * Class used to build Upload field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Upload
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Upload'),
            'default' => isset($content['default']) ? $content['default'] : array(),
            'library' => isset($content['library']) ? $content['library'] : 'image',
            'description' => isset($content['description']) ? $content['description'] : '',
            'multiple' => isset($content['multiple']) && $content['multiple'] ? true : false,
            'expand' => isset($content['expand']) && $content['expand'] ? true : false,
            'can_upload' => $this->getCanUpload(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_add_media' => TeaThemeOptions::__('Add media'),
            't_add_medias' => TeaThemeOptions::__('Add medias'),
            't_delete_item' => TeaThemeOptions::__('Delete selection'),
            't_delete_all' => TeaThemeOptions::__('Delete all medias'),
            't_cannot_upload' => TeaThemeOptions::__('It seems you are not able to upload files.'),

            't_alt' => TeaThemeOptions::__('Alternative text'),
            't_caption' => TeaThemeOptions::__('Caption'),
            't_sizes' => TeaThemeOptions::__('Available sizes'),
            't_size_full' => TeaThemeOptions::__('Full size media'),
        );

        //Fix bug with PDF
        $template['library'] = 'pdf' == $template['library'] ? 'application/pdf' : $template['library'];

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/upload.html.twig', $template);
    }
}
