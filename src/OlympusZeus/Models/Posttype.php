<?php

namespace crewstyle\OlympusZeus\Models;

use crewstyle\OlympusZeus\Controllers\PosttypeHook;

/**
 * Abstract class to define Posttype model.
 *
 * @package Olympus Zeus
 * @subpackage Models\Posttype
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

abstract class Posttype
{
    /**
     * @var array
     */
    protected $args;

    /**
     * @var PosttypeHook
     */
    protected $hook;

    /**
     * @var string
     */
    protected $slug;

    /**
     * Gets the value of args.
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Sets the value of args.
     *
     * @param array $args the args
     *
     * @return self
     */
    protected function setArgs(array $args)
    {
        $this->args = $args;

        return $this;
    }

    /**
     * Gets the value of hook.
     *
     * @return PosttypeHook
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * Sets the value of hook.
     *
     * @param PosttypeHook $hook the hook
     *
     * @return self
     */
    protected function setHook(PosttypeHook $hook)
    {
        $this->hook = $hook;

        return $this;
    }

    /**
     * Gets the value of slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets the value of slug.
     *
     * @param string $slug the slug
     *
     * @return self
     */
    protected function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
