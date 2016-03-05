<?php

namespace crewstyle\OlympusZeus\Controllers;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Fields\*;
use crewstyle\OlympusZeus\Controllers\Plugins\*;
use crewstyle\OlympusZeus\Controllers\Option;
use crewstyle\OlympusZeus\Controllers\Render;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Abstract class to define all field context with authorized fields, how to
 * write some functions and every usefull checks.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Field
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

abstract class Field
{
    /**
     * @var FieldModel
     */
    protected $field;

    /**
     * @var string
     */
    protected $faIcon = 'fa-circle-o';

    /**
     * @var string
     */
    protected $template = 'fields/text.html.twig';

    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
        $this->field = new FieldModel();
        $this->field->setFaIcon($this->faIcon);
        $this->field->setTemplate($this->template);
    }

    /**
     * Build Field component.
     *
     * @param string $type
     * @param string $id
     * @param array $alreadyused
     * @param boolean $special
     *
     * @return $class|false
     *
     * @since 5.0.0
     */
    public static function build($type, $id, $alreadyused = [], $special = false)
    {
        //Prepare error
        $error = [
            'error' => true,
            'template' => 'layouts/notification.html.twig',
            'vars' => ['content' => ''],
        ];

        //Check type integrity
        if (empty($type)) {
            $error['vars']['content'] = Translate::t('Something went wrong in your
                parameters definition: no type defined!');

            return $error;
        }

        //Set class
        $class = ucfirst($type);

        //Check if the class file exists
        if (!class_exists($class)) {
            $error['vars']['content'] = sprintf(Translate::t('Something went wrong in
                your parameters definition: the class <code>%s</code>
                does not exist!'), $class);

            return $error;
        }

        //Check if the asked field is unknown
        if (!$class::getIsauthorized() && !$special) {
            $error['vars']['content'] = sprintf(Translate::t('Something went wrong in your
                parameters definition with the id <code>%s</code>:
                the defined type is unknown!'), $id);

            return $error;
        }

        //Check if field needs an id
        if ($class::getHasid() && !$id) {
            $error['vars']['content'] = sprintf(Translate::t('Something went wrong in
                your parameters definition: the type <code>%s</code>
                needs an id.'), $type);

            return $error;
        }

        //Check if field needs an id
        if ($class::getHasid() && in_array($id, $alreadyused)) {
            $error['vars']['content'] = sprintf(Translate::t('Something went wrong in
                your parameters definition: the id <code>%s</code> is already in use.'), $id);

            return $error;
        }

        //Instanciate class
        $field = new $class();

        //Return $field
        return $field;
    }

    /**
     * Gets the value of field.
     *
     * @return FieldModel
     *
     * @since 5.0.0
     */
    protected function getField()
    {
        return $this->field;
    }

    /**
     * Retrieve field value
     *
     * @param array $details
     * @param object $default
     * @param string $id
     * @param boolean $multiple
     *
     * @return string|integer|array|object|boolean|null
     *
     * @since 5.0.0
     */
    public static function getValue($details, $default, $id = '', $multiple = false)
    {
        return Option::getFieldValue($details, $default, $id, $multiple);
    }

    /**
     * Prepare HTML component.
     *
     * @param array $content
     * @param array $details
     *
     * @since 5.0.0
     */
    abstract protected function getVars($content, $details = []);

    /**
     * Render HTML component.
     *
     * @param array $content
     * @param array $details
     * @param boolean $renderView
     * @param boolean $renderView
     *
     * @since 5.0.0
     */
    protected function render($content, $details = [], $renderView = true)
    {
        $this->getVars($content, $details);
        $tpl = [
            'template' => $this->field->getTemplate(),
            'vars' => $this->field->getVars()
        ];

        //Render view or return values
        if ($renderView) {
            Render::view($tpl['template'], $tpl['vars']);
        }
        else {
            return $tpl;
        }
    }
}
