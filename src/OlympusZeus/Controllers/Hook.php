<?php

namespace crewstyle\OlympusZeus\Controllers;

use crewstyle\OlympusZeus\Models\Hook;

/**
 * Gets its own hooks.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Hook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Hook
{
    /**
     * @var Hook
     */
    protected $hook;

    /**
     * Constructor.
     *
     * @param string $type
     * @param string $identifier
     * @param string $callback
     * @param mixed $priority
     *
     * @since 5.0.0
     */
    public function __construct($type, $identifier, $callback, $priority = 10)
    {
        $this->hook = new Hook();

        $this->hook->setType($type);
        $this->hook->setIdentifier($identifier);
        $this->hook->setCallback($callback);
        $this->hook->setPriority($priority);
    }

    /**
     * Define hook.
     *
     * @param null|mixed $args
     * @return void
     *
     * @since 5.0.0
     */
    public function listen($args = null)
    {
        if ('action' === $this->hook->getType()) {
            return do_action($this->hook->getIdentifier(), $args);
        }

        return apply_filters($this->hook->getIdentifier(), $args);
    }

    /**
     * Execute hook action/filter.
     *
     * @return void
     *
     * @since 5.0.0
     */
    public function run()
    {
        if ('action' === $this->hook->getType()) {
            return add_action($this->hook->getIdentifier(), [&$this, 'callback'], $this->hook->getPriority());
        }

        return add_filter($this->hook->getIdentifier(), [&$this, 'callback'], $this->hook->getPriority());
    }

    /**
     * Hook method.
     *
     * @since 5.0.0
     */
    public function callback()
    {
        $this->hook->getCallback();
    }
}
