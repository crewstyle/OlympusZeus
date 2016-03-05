<?php

namespace crewstyle\OlympusZeus\Controllers;

/**
 * Works with WP options.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Option
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Option
{
    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Set a value into options
     *
     * @param string $option Contains option name to delete from DB
     * @param integer $transient Define if we use transiant API or not
     *
     * @since 5.0.0
     */
    public static function delete($option, $transient = 0)
    {
        //If a transient is asked...
        if (!empty($transient)) {
            //Delete it
            delete_transient($option);
        }

        //Delete value from DB
        delete_option($option);
    }

    /**
     * Return a value from options
     *
     * @param string $option Contains option name to retrieve from DB
     * @param string $default Contains default value if no data was found
     * @param integer $transient Define if we use transiant API or not
     * @return mixed|string|void
     *
     * @since 5.0.0
     */
    public static function get($option, $default = '', $transient = 0)
    {
        //If a transient is asked...
        if (!empty($transient)) {
            //Get value from it
            $value = get_transient($option);

            if (false === $value) {
                //Get it from DB
                $value = get_option($option);

                //Put the default value if not
                $value = false === $value ? $default : $value;

                //Set the transient for this value
                set_transient($option, $value, OLZ_DURATION);
            }
        }
        //Else...
        else {
            //Get value from DB
            $value = get_option($option);

            //Put the default value if not
            $value = false === $value ? $default : $value;
        }

        /**
         * Works on option.
         *
         * @var string $option
         * @param array $value
         * @return array $value
         *
         * @since 5.0.0
         */
        $value = apply_filters('olz_get_option_'.$option, $value);

        //Return value
        return $value;
    }

    /**
     * Set a value into options
     *
     * @param string $option Contains option name to update from DB
     * @param string $value Contains value to insert
     * @param integer $transient Define if we use transiant API or not
     *
     * @since 5.0.0
     */
    public static function set($option, $value, $transient = 0)
    {
        /**
         * Works on option.
         *
         * @var string $option
         * @param array $value
         * @return array $value
         *
         * @since 5.0.0
         */
        $option = apply_filters('olz_set_option_'.$option, $value);

        //If a transient is asked...
        if (!empty($transient)) {
            //Set it for this value
            set_transient($option, $value, OLZ_DURATION);
        }

        //Set value into DB without autoload
        if (false === get_option($option)) {
            add_option($option, $value, '', 'no');
        }
        else {
            self::update($option, $value);
        }
    }

    /**
     * Force update a value into options without transient
     *
     * @param string $option
     * @param string $value
     *
     * @since 5.0.0
     */
    public static function update($option, $value)
    {
        update_option($option, $value);
    }

    /**
     * Get configs.
     *
     * @param string $option Define the option asked
     * @return array $configs Define configurations
     *
     * @since 5.0.0
     */
    public static function getConfigs($option = 'login')
    {
        //Get datas from DB
        $configs = self::get('olz-configs', []);

        //Check if data is available
        $return = isset($configs[$option]) ? $configs[$option] : [];

        //Return value
        return $return;
    }

    /**
     * Set configs.
     *
     * @param string $option Define the option to update
     * @param array|integer|string $value Define the value
     *
     * @since 5.0.0
     */
    public static function setConfigs($option = 'login', $value = true)
    {
        //Get datas from DB
        $configs = self::get('olz-configs', []);

        //Check data
        if (isset($configs[$option])) {
            unset($configs[$option]);
        }

        //Define the data
        $configs[$option] = $value;

        //Update DB
        self::set('olz-configs', $configs);
    }

    /**
     * Retrieve field value
     *
     * @param array $details
     * @param object $default
     * @param string $id
     * @param boolean $multiple
     *
     * @since 5.0.0
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
                $suboption = str_replace(['[', ']'], '', $suboption);

                //Get value
                $vals = self::getConfigs($option);
                $value = !$vals ? $default : (isset($vals[$suboption]) ? $vals[$suboption] : $default);
            }
            else {
                //Get value
                $value = self::getConfigs($id);
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
            $option = !empty($prefix) ? str_replace(['%TERM%', '%SLUG%'], [$prefix, $id], $structure) : $id;
            $value = self::get($option, $default);
        }

        //Strip slasches?
        return $multiple || is_array($value) ? $value : stripslashes($value);
    }

    /**
     * Force update a value into term options without transient
     *
     * @param string $termId
     * @param string $option
     * @param string $value
     *
     * @since 5.0.0
     */
    public static function updateTermMeta($termId, $option, $value)
    {
        update_term_meta($termId, $option, $value);
    }
}
