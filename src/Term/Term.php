<?php

namespace crewstyle\TeaThemeOptions\Term;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Term\Engine\Engine;

/**
 * TTO TERM
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Term
 *
 * To get its own term.
 *
 * @package Tea Theme Options
 * @subpackage Term\Term
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Term
{
    /**
     * @var TermEngine
     */
    protected $engine = null;

    /**
     * Constructor.
     *
     * @param boolean $enable Define if Post types engine is enabled
     *
     * @since 3.0.0
     */
    public function __construct($enable = true)
    {
        //Check post types engine
        if (!$enable) {
            return;
        }

        //Initialize search
        $this->engine = new Engine();
    }

    /**
     * Add a term to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 3.0.0
     */
    public function addTerm($configs = array(), $contents = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->addTerm($configs, $contents);
    }

    /**
     * Register terms.
     *
     * @since 3.0.0
     */
    public function buildTerms()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->buildTerms();
    }
}
