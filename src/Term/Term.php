<?php

namespace crewstyle\TeaThemeOptions\Term;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Action\Action;
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
 * @since 3.2.0
 *
 */
class Term
{
    /**
     * @var boolean
     */
    protected $enable = true;

    /**
     * @var TermEngine
     */
    protected $engine = null;

    /**
     * Constructor.
     *
     * @param boolean $posttypes Define if Post types engine is enabled
     * @param boolean $enable Define if Terms engine is enabled
     *
     * @since 3.2.0
     */
    public function __construct($posttypes = true, $enable = true)
    {
        $this->enable = $posttypes && $enable;

        //Check post types engine
        if (!$this->enable) {
            return;
        }

        //Initialize search
        $this->engine = new Engine();

        //Hooks
        add_filter('tea_to_footer_urls', array(&$this, 'hookFooterUrls'), 10, 2);
    }

    /**
     * Add a term to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     * @param array $contents Contains all data
     *
     * @since 3.2.0
     */
    public function addTerm($configs = array(), $contents = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN || !$this->enable) {
            return;
        }

        $this->engine->addTerm($configs, $contents);
    }

    /**
     * Register terms.
     *
     * @since 3.2.0
     */
    public function buildTerms()
    {
        //Admin panel
        if (!TTO_IS_ADMIN || !$this->enable) {
            return;
        }

        $this->engine->buildTerms();
    }

    /**
     * Hook special filter
     *
     * @return array $urls
     * @return string $identifier
     *
     * @since 3.2.0
     */
    public function hookFooterUrls($urls, $identifier) {
        $urls['terms'] = array(
            'url' => Action::buildAction($identifier, 'footer') . '&make=terms',
            'label' => TeaThemeOptions::__('Update terms'),
        );

        return $urls;
    }
}
