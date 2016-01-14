<?php

namespace crewstyle\OlympusZeus\Core\Term;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Action\Action;
use crewstyle\OlympusZeus\Core\Term\TermEngine;

/**
 * Gets its own term.
 *
 * @package Olympus Zeus
 * @subpackage Core\Term\Term
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Term
{
    /**
     * @var TermEngine
     */
    protected $termEngine = null;

    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        //Initialize search
        $this->termEngine = new TermEngine();

        //Hooks
        add_filter('olz_template_footer_urls', function ($urls, $identifier) {
            return array_merge($urls, array(
                'terms' => array(
                    'url' => admin_url('admin.php?page='.$identifier.'&do=olz-action&from=footer&make=terms'),
                    'label' => OlympusZeus::translate('terms'),
                )
            ));
        }, 10, 2);
    }

    /**
     * Add a term to the theme options panel.
     *
     * @param array $configs Array containing all configurations
     *
     * @since 4.0.0
     */
    public function addTerm($configs = array())
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->termEngine->addTerm($configs);
    }

    /**
     * Register terms.
     *
     * @since 4.0.0
     */
    public function buildTerms()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        $this->termEngine->buildTerms();
    }
}
