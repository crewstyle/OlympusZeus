<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Hr;

use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO HR FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'hr'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Hr
 *
 * Class used to build Hr field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Hr
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Hr extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-minus';

    /**
     * @var boolean
     */
    protected $hasId = false;

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
        //Get template
        return $this->renderField('fields/hr.html.twig', array());
    }
}
