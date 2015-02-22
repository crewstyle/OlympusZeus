<?php

namespace Takeatea\TeaThemeOptions;

/**
 * TEA FUNCTIONS
 *
 *
 * Include this file in your composer.json to use the
 * _get_option and _set_option functions
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Return a value from options
 *
 * @since 2.0.0
 */
function _get_option($option, $default = '', $transient = false)
{
    return TeaThemeOptions::get_option($option, $default, $transient);
}

/**
 * Set a value into options
 *
 * @since 2.0.0
 */
function _set_option($option, $value, $transient = false)
{
    TeaThemeOptions::set_option($option, $value, $transient);
}
