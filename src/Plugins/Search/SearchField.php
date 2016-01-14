<?php

namespace crewstyle\OlympusZeus\Plugins\Search;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Action\Action;
use crewstyle\OlympusZeus\Core\Field\Field;
use crewstyle\OlympusZeus\Plugins\Search\Search;

/**
 * Builds Search field.
 *
 * @package Olympus Zeus
 * @subpackage Plugins\Search\SearchField
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class SearchField extends Field
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
     * @var boolean
     */
    public static $isauthorized = false;

    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        add_action('olz_field_current_page', array(&$this, 'hookCurrentPage'), 10, 1);
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
     * @since 4.0.0
     */
    public function prepareField($content, $details = array())
    {
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $tpl = empty($prefix) ? 'pages' : 'terms';

        //Get indexed content count and status
        $count = OlympusZeus::getConfigs(Search::getIndex().'-count', 0);
        $status = OlympusZeus::getConfigs(Search::getIndex().'-status', 0);

        //Get page
        $page = $this->getSearchCurrentPage().'&do=olz-action&make=create';

        //Get right page
        if (0 < $status) {
            $page = $this->getSearchCurrentPage().'&do=olz-action&make=index';
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
                OlympusZeus::__('<small>Check your configurations to properly use the Search Engine.</small>
                    <a href="%s" class="button button-primary">Create now your Search Index</a>'),
                $page
            ),
            't_es_notify_0' => sprintf(
                OlympusZeus::__('<small>Your parameters are well configured but your contents have not been indexed.</small>
                    <a href="%s" class="button button-primary">Let\'s do it!</a>'),
                $page
            ),
            't_es_notify_s' => sprintf(
                OlympusZeus::__('<small>Well done: <code>%d</code> posts have been indexed!</small>
                    <a href="%s" class="button button-main">Update contents!</a>'),
                $count,
                $page
            ),
        );

        //Get template
        return $this->renderField('plugins/fields/search.html.twig', $template);
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
