<?php

namespace crewstyle\TeaThemeOptions\Core\Option;

/**
 * TTO OPTION
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Option
 *
 * Class used to work with WP options.
 *
 * @package Tea Theme Options
 * @subpackage Core\Option\Option
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
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
     * Get configs.
     *
     * @param string $option Define the option asked
     * @return array $configs Define configurations
     *
     * @since 3.3.0
     */
    public static function getConfigs($option = 'login')
    {
        //Get datas from DB
        $configs = self::getOption('tto-configs', array());

        //Check if data is available
        $return = isset($configs[$option]) ? $configs[$option] : array();

        //Return value
        return $return;
    }

    /**
     * Set configs.
     *
     * @param string $option Define the option to update
     * @param array|integer|string $value Define the value
     *
     * @since 3.3.0
     */
    public static function setConfigs($option = 'login', $value = true)
    {
        //Get datas from DB
        $configs = self::getOption('tto-configs', array());

        //Check data
        if (isset($configs[$option])) {
            unset($configs[$option]);
        }

        //Define the data
        $configs[$option] = $value;

        //Update DB
        self::setOption('tto-configs', $configs);
    }

    /**
     * Set a value into options
     *
     * @param string $option Contains option name to delete from DB
     * @param integer $transient Define if we use transiant API or not
     *
     * @since 3.0.0
     */
    public static function delOption($option, $transient = 0)
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
     * @since 3.0.0
     */
    public static function getOption($option, $default = '', $transient = 0)
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
                set_transient($option, $value, TTO_DURATION);
            }
        }
        //Else...
        else {
            //Get value from DB
            $value = get_option($option);

            //Put the default value if not
            $value = false === $value ? $default : $value;
        }

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
     * @since 3.0.0
     */
    public static function setOption($option, $value, $transient = 0)
    {
        //If a transient is asked...
        if (!empty($transient)) {
            //Set it for this value
            set_transient($option, $value, TTO_DURATION);
        }

        //Set value into DB without autoload
        if (false === get_option($option)) {
            add_option($option, $value, '', 'no');
        }
        else {
            self::updateOption($option, $value);
        }
    }

    /**
     * Force update a value into options without transient
     *
     * @param string $option Contains option name to update from DB
     * @param string $value Contains value to insert
     *
     * @since 3.0.0
     */
    public static function updateOption($option, $value)
    {
        update_option($option, $value);
    }
}
