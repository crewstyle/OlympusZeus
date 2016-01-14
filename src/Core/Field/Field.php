<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;

/**
 * Abstract class to define all field context with authorized fields, how to
 * write some functions and every usefull checks.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
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
    protected static $hasId = true;

    /**
     * @var array
     */
    protected $includes = array();

    /**
     * @var boolean
     */
    public static $isauthorized = true;

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
        if (!OLZ_ISADMIN) {
            return false;
        }

        $error = array(
            'error' => true,
            'template' => 'layouts/notification.html.twig',
            'vars' => array('content' => ''),
        );

        //Check type integrity
        if (empty($type)) {
            $error['vars']['content'] = OlympusZeus::translate('Something went wrong in your
                parameters definition: no type defined!');

            return $error;
        }

        //Set class
        $cls = ucfirst($type);
        $default = "\\crewstyle\\OlympusZeus\\Core\\Field\\".$cls;
        $plugin = "\\crewstyle\\OlympusZeus\\Plugins\\".$cls."\\".$cls."Field";

        //Check if the class file exists
        if (!class_exists($default) && !class_exists($plugin)) {
            $error['vars']['content'] = sprintf(OlympusZeus::translate('Something went wrong in
                your parameters definition: the class <code>%s</code>
                does not exist!'), $cls);

            return $error;
        }

        //Get the class
        $class = class_exists($default) ? $default : $plugin;

        //Check if the asked field is unknown
        if (!$class::$isauthorized && !$special) {
            $error['vars']['content'] = sprintf(OlympusZeus::translate('Something went wrong in your
                parameters definition with the id <code>%s</code>:
                the defined type is unknown!'), $id);

            return $error;
        }

        //Check if field needs an id
        if ($class::$hasId && !$id) {
            $error['vars']['content'] = sprintf(OlympusZeus::translate('Something went wrong in
                your parameters definition: the type <code>%s</code>
                needs an id.'), $type);

            return $error;
        }

        //Check if field needs an id
        if ($class::$hasId && in_array($id, $ids)) {
            $error['vars']['content'] = sprintf(OlympusZeus::translate('Something went wrong in
                your parameters definition: the id <code>%s</code> is already in use.'), $id);

            return $error;
        }

        //Instanciate class
        $field = new $class();

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
     * @since 3.3.0
     */
    public static function getFieldValue($details, $default, $id = '', $multiple = false)
    {
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $termid = isset($details['term_id']) ? $details['term_id'] : 0;
        $structure = isset($details['structure']) ? $details['structure'] : '';

        //Post types
        if (!empty($post)) {
            $value = get_post_meta($post->ID, $post->post_type.'-'.$id, !$multiple);
            $value = empty($value) ? $default : $value;
        }
        //Special settings
        else if (preg_match('/^olz-configs-/', $id)) {
            //Update option from olz_configs_frontend_login into frontend_login
            $option = $prefix.$id;
            $id = str_replace('olz-configs-', '', $id);

            //Check id[suboption]
            if (preg_match('/\[.*\]/', $id)) {
                //Get option
                $option = substr($id, 0, strpos($id,'['));

                //Get suboption
                $suboption = substr($id, strpos($id,'['));
                $suboption = str_replace(array('[', ']'), '', $suboption);

                //Get value
                $vals = OlympusZeus::getConfigs($option);
                $value = !$vals ? $default : (isset($vals[$suboption]) ? $vals[$suboption] : $default);
            }
            else {
                //Get value
                $value = OlympusZeus::getConfigs($id);
                $value = !$value ? $default : $value;
            }
        }
        //WP 4.4
        else if (function_exists('get_term_meta') && !empty($prefix) && !empty($termid)) {
            $value = get_term_meta($termid, $prefix.'-'.$id, true);
            $value = !$value ? $default : $value;
        }
        //Default
        else {
            $option = !empty($prefix) ? str_replace(array('%TERM%', '%SLUG%'), array($prefix, $id), $structure) : $id;
            $value = OlympusZeus::getOption($option, $default);
        }

        //Strip slasches?
        return $multiple || is_array($value) ? $value : stripslashes($value);
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
     * @return array
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
            OlympusZeus::notify('error',
                OlympusZeus::translate('A field is missing because no type is defined.')
            );

            return null;
        }

        //Get values
        //$type = $args['args']['type'];
        //$id = isset($content['id']) ? $content['id'] : '';

        //Display field content
        $tpl = $field->prepareField($content, array('post' => $post));
        OlympusZeus::getRender($tpl['template'], $tpl['vars']);

        //Return post if it is asked
        return isset($post->ID) ? $post->ID : null;
    }
}
