<?php

namespace crewstyle\OlympusZeus\Controllers;

use Symfony\Component\HttpFoundation\Request;

/**
 * Displays admin messages and notifications when its needed.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Request
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Request
{
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct(){}

    /**
     * Return request value.
     *
     * @param string $param
     *
     * @since 5.0.0
     */
    public static function get($param, $default = '')
    {
        return Request::createFromGlobals()->query->get($param, $default);
    }
}
