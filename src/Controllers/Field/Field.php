<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field;

use crewstyle\TeaThemeOptions\TeaThemeOptions;

/**
 * TTO FIELD
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

/**
 * TTO Field
 *
 * Abstract class to define all field context with authorized fields, how to
 * write some functions and every usefull checks.
 *
 * @package Tea Theme Options
 * @subpackage Field
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
abstract class Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-circle-o';

    /**
     * @var boolean
     */
    protected $hasId = true;

    /**
     * @var array
     */
    protected $includes = array();

    /**
     * @var array
     */
    protected static $unauthorized = array('network', 'posttype', 'search', 'section');

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Retrieve the $can_upload value
     *
     * @uses current_user_can()
     * @return bool $can_upload Get if the user can upload files
     *
     * @since 3.0.0
     */
    protected function getCanUpload()
    {
        //Check if user can upload
        return current_user_can('upload_files');
    }

    /**
     * Build Field component.
     *
     * @param string $type Contains field type
     * @param string $id Contains field ID
     * @param boolean $special Defines if we are in special page or not
     * @param array $ids Contains all ids already in use
     *
     * @return $class|false
     *
     * @since 3.0.0
     */
    public static function getField($type, $id, $special = array(), $ids = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return false;
        }

        $error = array(
            'error' => true,
            'template' => 'layouts/notification.html.twig',
            'vars' => array('content' => ''),
        );

        //Check type integrity
        if (empty($type)) {
            $error['vars']['content'] = TeaThemeOptions::__('Something went wrong in your
                parameters definition: no type defined!');

            return $error;
        }

        //Get all default fields in the Tea T.O. package
        $unauthorized = self::getUnauthorizedFields();

        //Check if the asked field is unknown
        if (in_array($type, $unauthorized) && !$special) {
            $error['vars']['content'] = sprintf(TeaThemeOptions::__('Something went wrong in your
                parameters definition with the id <code>%s</code>:
                the defined type is unknown!'), $id);

            return $error;
        }

        //Set class
        $cls = ucfirst($type);
        $class = "\\crewstyle\\TeaThemeOptions\\Controllers\\Field\\$cls\\$cls";

        //Check if the class file exists
        if (!class_exists($class)) {
            $error['vars']['content'] = sprintf(TeaThemeOptions::__('Something went wrong in
                your parameters definition: the class <code>%s</code>
                does not exist!'), $cls);

            return $error;
        }

        //Instanciate class
        $field = new $class();

        //Check if field needs an id
        if ($field->hasID() && !$id) {
            $error['vars']['content'] = sprintf(TeaThemeOptions::__('Something went wrong in
                your parameters definition: the type <code>%s</code>
                needs an id.'), $type);

            return $error;
        }

        //Check if field needs an id
        if ($field->hasID() && in_array($id, $ids)) {
            $error['vars']['content'] = sprintf(TeaThemeOptions::__('Something went wrong in
                your parameters definition: the id <code>%s</code> is already in use.'), $id);

            return $error;
        }

        //Return $field
        return $field;
    }

    /**
     * Retrieve field value
     *
     * @param array $details Contains field details to build value to retrieve
     * @param object $default Contains default value in case we dont find any stored value
     * @param string $id Contains field ID
     * @param boolean $multiple Define if the stored value is an array or not
     *
     * @return string|integer|array|object|boolean|null
     *
     * @since 3.0.0
     */
    public static function getFieldValue($details, $default, $id = '', $multiple = false)
    {
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';

        //Post types
        if (!empty($post)) {
            $value = get_post_meta($post->ID, $post->post_type.'-'.$id, !$multiple);
            $value = empty($value) ? $default : $value;
        }
        //Default
        else {
            $value = TeaThemeOptions::getOption($prefix.$id, $default);
        }

        //Strip slasches?
        return $multiple ? $value : stripslashes($value);
    }

    /**
     * Return unauthorized fields to use.
     *
     * @return array $array All fields unauthorized to use
     *
     * @since 3.0.0
     */
    /*public static function getAuthorizedFields()
    {
        $folder = TTO_PATH . '/Controllers/Field/';

        //List all registered fields
        $fields = glob($folder . '*', GLOB_ONLYDIR);
        $unauth = self::getUnauthorizedFields();
        $auth = array();

        foreach ($fields as $fd) {
            $name = str_replace($folder, '', $fd);
            $lowname = strtolower($name);

            if (in_array($lowname, $unauth)) {
                continue;
            }

            $class = "\\crewstyle\\TeaThemeOptions\\Controllers\\Field\\$name\\$name";

            $auth[] = array(
                'icon' => $class::$faicon,
                'name' => $lowname,
                'title' => $name
            );
        }

        return $auth;
    }*/

    /**
     * Return unauthorized fields to use.
     *
     * @return array $array All fields unauthorized to use
     *
     * @since 3.0.0
     */
    public static function getUnauthorizedFields()
    {
        return self::$unauthorized;
    }

    /**
     * Check if field needs an ID.
     *
     * @return boolean $hasId Define if Field need an Id or not
     *
     * @since 3.0.0
     */
    public function hasID()
    {
        //Return value defined for each field
        return $this->hasId;
    }

    /**
     * Prepare HTML component.
     *
     * @param array $content Contains all field data
     * @param array $details Contains all field options
     *
     * @since 3.0.0
     */
    abstract public function prepareField($content, $details = array());

    /**
     * Render component.
     *
     * @param string $template Twig template to display
     * @param array $vars Contains all field options
     *
     * @since 3.0.0
     */
    public function renderField($template, $vars)
    {
        //Display template
        return array(
            'template' => $template,
            'vars' => $vars,
        );
    }

    /**
     * Build HTML component.
     *
     * @param array $post Contains all data such as Wordpress asks
     * @param array $args Contains all data such as Wordpress asks
     *
     * @return int|null
     *
     * @since 3.0.0
     */
    public function hookFieldBuild($post, $args)
    {
        //If autosave...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return isset($post->ID) ? $post->ID : null;
        }

        //Get contents
        $content = isset($args['args']['contents']) ? $args['args']['contents'] : array();
        $field = isset($args['args']['field']) ? $args['args']['field'] : '';

        //Check if a type is defined
        if (empty($content) || empty($field) || !isset($args['args']['type'])) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('A field is missing because no type is defined.')
            );

            return null;
        }

        //Get values
        $type = $args['args']['type'];
        $id = isset($content['id']) ? $content['id'] : '';

        //Display field content
        $tpl = $field->prepareField($content, array('post' => $post));
        TeaThemeOptions::getRender($tpl['template'], $tpl['vars']);

        //Return post if it is asked
        return isset($post->ID) ? $post->ID : null;
    }
}
