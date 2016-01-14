<?php

namespace crewstyle\OlympusZeus\Core;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Notification\Notification;
use crewstyle\OlympusZeus\Core\Option\Option;
use crewstyle\OlympusZeus\Core\Render\Render;
use crewstyle\OlympusZeus\Core\Translate\Translate;

/**
 * Gets all core methods.
 *
 * @package Olympus Zeus
 * @subpackage Core\Core
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

abstract class Core
{
    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        //Initialize Translate instance
        new Translate();
    }

    /**
     * Display notification.
     *
     * @param string $type Define notice type to display
     * @param string $content Contains typo to display
     * @return string $content
     *
     * @since 4.0.0
     */
    public static function notify($type, $content)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        Notification::notify($type, $content);
    }

    /**
     * Translate typo.
     *
     * @param string $content Contains typo to translate
     * @return Translate
     *
     * @since 4.0.0
     */
    public static function translate($content)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        return (string) Translate::translate($content);
    }

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
        return Option::getConfigs($option);
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
        Option::setConfigs($option, $value);
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
        Option::delOption($option, $transient);
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
        return Option::getOption($option, $default, $transient);
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
        Option::setOption($option, $value, $transient);
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
        Option::updateOption($option, $value);
    }

    /**
     * Get renderer.
     *
     * @param string $template Twig template to display
     * @param array $vars Contains all field options
     *
     * @since 4.0.0
     */
    public static function getRender($template, $vars)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        Render::render($template, $vars);
    }

    /**
     * Slugify string.
     *
     * @param string $text Text string to slugify
     * @param string $separator Character used to separate each word
     * @return string $slugified Slugified string
     *
     * @since 4.0.0
     */
    public static function getUrlize($text, $separator = '-')
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        return Render::urlize($text, $separator);
    }
}
