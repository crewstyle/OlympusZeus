<?php

namespace crewstyle\OlympusZeus\Models;

/**
 * Abstract class to define Field model.
 *
 * @package Olympus Zeus
 * @subpackage Models\Field
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

abstract class Field
{
    /**
     * @var string
     */
    protected $faIcon;

    /**
     * @var boolean
     */
    protected $hasId = true;

    /**
     * @var array
     */
    protected $includes = [];

    /**
     * @var boolean
     */
    protected $isAuthorized = true;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $vars;

    /**
     * Gets the value of faIcon.
     *
     * @return string
     */
    public function getFaIcon()
    {
        return $this->faIcon;
    }

    /**
     * Sets the value of faIcon.
     *
     * @param string $faIcon the fa icon
     *
     * @return self
     */
    protected function setFaIcon($faIcon)
    {
        $this->faIcon = $faIcon;

        return $this;
    }

    /**
     * Gets the value of hasId.
     *
     * @return boolean
     */
    public function getHasId()
    {
        return $this->hasId;
    }

    /**
     * Sets the value of hasId.
     *
     * @param boolean $hasId the has id
     *
     * @return self
     */
    protected function setHasId($hasId)
    {
        $this->hasId = $hasId;

        return $this;
    }

    /**
     * Gets the value of includes.
     *
     * @return array
     */
    public function getIncludes()
    {
        return $this->includes;
    }

    /**
     * Sets the value of includes.
     *
     * @param array $includes the includes
     *
     * @return self
     */
    protected function setIncludes(array $includes)
    {
        $this->includes = $includes;

        return $this;
    }

    /**
     * Gets the value of isAuthorized.
     *
     * @return boolean
     */
    public function getIsAuthorized()
    {
        return $this->isAuthorized;
    }

    /**
     * Sets the value of isAuthorized.
     *
     * @param boolean $isAuthorized the is authorized
     *
     * @return self
     */
    protected function setIsAuthorized($isAuthorized)
    {
        $this->isAuthorized = $isAuthorized;

        return $this;
    }

    /**
     * Gets the value of template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets the value of template.
     *
     * @param string $template the template
     *
     * @return self
     */
    protected function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Gets the value of vars.
     *
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Sets the value of vars.
     *
     * @param array $vars the vars
     *
     * @return self
     */
    protected function setVars(array $vars)
    {
        $this->vars = $vars;

        return $this;
    }
}
