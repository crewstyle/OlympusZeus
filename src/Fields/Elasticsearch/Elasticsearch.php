<?php
namespace Takeatea\TeaThemeOptions\Fields\Elasticsearch;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaElasticsearch;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA ELASTICSEARCH FIELD
 * You do not have to use it
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Fields Network
 *
 * To get its own Fields
 *
 * @since 1.4.0
 *
 */
class Elasticsearch extends TeaFields
{
    //Define protected vars
    private $currentpage;
    private $id = 'tea_elastic';

    /**
     * Constructor.
     *
     * @since 1.4.0
     */
    public function __construct(){}


    //--------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     *
     * @since 1.4.0
     */
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Do not display Elasticsearch field on CPTs
        if (!empty($post)) {
            return;
        }

        //Default variables
        $id = $this->getId();
        $title = isset($content['title']) ? $content['title'] : __('Tea Elasticsearch', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';
        $page = $this->getCurrentPage();
        $index = TeaThemeOptions::get_option('tea_elastic_index', 0);

        //Default values
        $std = isset($content['std']) ? $content['std'] : array(
            'enable' => 'no',
            'server_host' => 'localhost',
            'server_port' => '9200',
            'index_name' => 'teasearch',
            'read_timeout' => 5,
            'write_timeout' => 10,
            'template' => 'no',
            'scores' => array(),
            'index_post' => array(),
            'index_tax' => array()
        );

        //Get scores
        $scores = TeaElasticsearch::getFields();

        //Check selected
        $vals = $this->getOption($prefix.$id, $std);
        $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        $vals = array_merge($std, $vals);

        //Get template
        include(TTO_PATH . '/Fields/Elasticsearch/in_pages.tpl.php');
    }

    /**
     * Build action method.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function actionElasticsearch($request)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check if this action was properly called
        if (!isset($request['for']) || 'elasticsearch' != $request['for']) {
            return;
        }

        //Get id
        $id = $this->getId();

        //Check if this action was properly called
        if (isset($request['tea_elastic_enable'])) {
            //Index datas
            $this->enableElasticsearch($request);
        }
        else if (isset($request['tea_elastic_index'])) {
            //Index datas
            $this->indexElasticsearch();
        }
        else if (isset($request[$id])) {
            //Update datas
            $this->updateElasticsearch($request);
        }
    }

    /**
     * Index all search datas.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function enableElasticsearch($request)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get id
        $id = $this->getId();

        //Check if this action was properly called
        if (!isset($request[$id], $request[$id]['enable'])) {
            return;
        }

        //Get datas
        $ctn = TeaThemeOptions::get_option($id, array());

        //Enable or disable Elasticsearch
        $ctn['enable'] = $request[$id]['enable'];

        //Update datas
        TeaThemeOptions::set_option($id, $ctn);
    }

    /**
     * Index all search datas.
     *
     * @since 1.4.0
     */
    public function indexElasticsearch()
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Create new occurrence
        $els = new TeaElasticsearch(true, false);
        $results = $els->indexContents();

        //Checks contents
        if (!$results) {
            $this->adminmessage = __('Something went wrong: it seems you 
                forgot to attach contents to the current page.', TTO_I18N);
            return;
        }
        else if (1 == $results) {
            $this->adminmessage = __('Great: your post has been indexed!', TTO_I18N);
            return;
        }
        else {
            $this->adminmessage = sprintf(
                __('Good job: %d posts have been indexed!', TTO_I18N),
                $results
            );
            return;
        }
    }

    /**
     * Update all search datas.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function updateElasticsearch($request)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check values integrity
        $id = $this->getId();
        $ctn = TeaThemeOptions::get_option($id, array());

        //Update all datas
        $new = array_merge($ctn, $request[$id]);

        //Define data in DB
        TeaThemeOptions::set_option($id, $new);
    }

    /**
     * ACCESSORS
     **/

    /**
     * Retrieve the $currentpage value
     *
     * @return string $currentpage Get the current page
     *
     * @since 1.4.0
     */
    protected function getCurrentPage()
    {
        //Return value
        return $this->currentpage;
    }

    /**
     * Define the $currentpage value
     *
     * @param string $currentpage Get the current page
     *
     * @since 1.4.0
     */
    public function setCurrentPage($currentpage = '')
    {
        //Define value
        $this->currentpage = $currentpage;
    }

    /**
     * Retrieve the $id value
     *
     * @return string $id Get the current id
     *
     * @since 1.4.0
     */
    protected function getId()
    {
        //Return value
        return $this->id;
    }

    /**
     * Define the $id value
     *
     * @param string $id Get the current id
     *
     * @since 1.4.0
     */
    public function setId($id = 'tea_elastic')
    {
        //Define value
        $this->id = $id;
    }
}
