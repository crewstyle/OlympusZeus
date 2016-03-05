<?php

namespace crewstyle\OlympusZeus\Models;

/**
 * Abstract class to define Hook model.
 *
 * @package Olympus Zeus
 * @subpackage Models\Hook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

abstract class Hook
{
    /**
     * @var function
     */
    protected $callback;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var integer
     */
    protected $priority;

    /**
     * @var string
     */
    protected $type;

    /**
     * Gets the value of callback.
     *
     * @return function
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Sets the value of callback.
     *
     * @param function $callback the callback
     *
     * @return self
     */
    protected function setCallback(function $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Gets the value of identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the value of identifier.
     *
     * @param string $identifier the identifier
     *
     * @return self
     */
    protected function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Gets the value of priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets the value of priority.
     *
     * @param integer $priority the priority
     *
     * @return self
     */
    protected function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Gets the value of type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param string $type the type
     *
     * @return self
     */
    protected function setType($type)
    {
        $this->type = 'action' === $type ? 'action' : 'filter';

        return $this;
    }
}
