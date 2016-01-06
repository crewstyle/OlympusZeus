<?php

namespace crewstyle\TeaThemeOptions\Core\Term;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Action\Action;
use crewstyle\TeaThemeOptions\Core\Term\Engine\Engine;

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
 * @subpackage Core\Term\Term
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Term
{
    /**
     * @var Engine
     */
    protected $engine = null;

    /**
     * Constructor.
     *
     * @since 3.3.0
     */
    public function __construct()
    {
        //Initialize search
        $this->engine = new Engine();

        //Hooks
        add_filter('tto_template_footer_urls', function ($urls, $identifier) {
            return array_merge($urls, array(
                'terms' => array(
                    'url' => admin_url('admin.php?page='.$identifier.'&do=tto-action&from=footer&make=terms'),
                    'label' => TeaThemeOptions::__('terms'),
                )
            ));
        }, 10, 2);
    }

    /**
     * Add a term to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 3.3.0
     */
    public function addTerm($configs = array())
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $this->engine->addTerm($configs);
    }

    /**
     * Register terms.
     *
     * @since 3.3.0
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
