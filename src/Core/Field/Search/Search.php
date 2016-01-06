<?php
namespace crewstyle\TeaThemeOptions\Core\Field\Search;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Action\Action;
use crewstyle\TeaThemeOptions\Core\Field\Field;
use crewstyle\TeaThemeOptions\Plugins\Search\Search as SearchEngine;

/**
 * TTO SEARCH FIELD
 *
 * You do not have to use it!
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Search
 *
 * Class used to build Search field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Search
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Search extends Field
{
    /**
     * @var string
     */
    private $currentpage;

    /**
     * @var boolean
     */
    protected $hasId = false;

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        add_action('tto_field_current_page', array(&$this, 'hookCurrentPage'), 10, 1);
    }

    /**
     * Hook special hook
     *
     * @since 3.0.0
     */
    public function hookCurrentPage($current) {
        $page = admin_url('admin.php?page='.$current.'&section=search&from=plugin');
        $this->setSearchCurrentPage($page);
    }

    /**
     * Display HTML component.
     *
     * @param array $content Contains all field data
     * @param array $details Contains all field options
     *
     * @since 3.0.0
     */
    public function prepareField($content, $details = array())
    {
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $tpl = empty($prefix) ? 'pages' : 'terms';

        //Get indexed content count and status
        $count = TeaThemeOptions::getConfigs(SearchEngine::getIndex().'-count', 0);
        $status = TeaThemeOptions::getConfigs(SearchEngine::getIndex().'-status', 0);

        //Get page
        $page = $this->getSearchCurrentPage().'&do=tto-action&make=create';

        //Get right page
        if (0 < $status) {
            $page = $this->getSearchCurrentPage().'&do=tto-action&make=index';
        }

        //Build defaults data
        $template = array(
            'count' => $count,
            'page' => $page,
            'status' => $status,

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            //-> notifications
            't_es_notify_n' => sprintf(
                TeaThemeOptions::__('<small>Check your configurations to properly use the Search Engine.</small>
                    <a href="%s" class="button button-primary">Create now your Search Index</a>'),
                $page
            ),
            't_es_notify_0' => sprintf(
                TeaThemeOptions::__('<small>Your parameters are well configured but your contents have not been indexed.</small>
                    <a href="%s" class="button button-primary">Let\'s do it!</a>'),
                $page
            ),
            't_es_notify_s' => sprintf(
                TeaThemeOptions::__('<small>Well done: <code>%d</code> posts have been indexed!</small>
                    <a href="%s" class="button button-main">Update contents!</a>'),
                $count,
                $page
            ),
        );

        //Get template
        return $this->renderField('fields/search.html.twig', $template);
    }

    /**
     * Retrieve the $currentpage value
     *
     * @return string $currentpage Get the current page
     *
     * @since 3.0.0
     */
    protected function getSearchCurrentPage()
    {
        return $this->currentpage;
    }

    /**
     * Define the $currentpage value
     *
     * @param string $currentpage Get the current page
     *
     * @since 3.0.0
     */
    public function setSearchCurrentPage($currentpage = '')
    {
        $this->currentpage = $currentpage;
    }
}
