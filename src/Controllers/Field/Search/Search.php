<?php
namespace crewstyle\TeaThemeOptions\Controllers\Field\Search;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Action\Action;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;
use crewstyle\TeaThemeOptions\Search\Search as SearchEngine;

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
 * @subpackage Controllers\Field\Search
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
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
        add_action('tea_to_field_current_page', array(&$this, 'hookCurrentPage'), 10, 1);
    }

    /**
     * Hook special hook
     *
     * @since 3.0.0
     */
    public function hookCurrentPage($current) {
        $this->setSearchCurrentPage($current);
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
        //Counter
        $countname = SearchEngine::getCountName();
        $counter = TeaThemeOptions::getConfigs($countname, -1);
        $count = !empty($counter) && -1 < $counter ? $counter[0] : -1;

        //Get current id and index
        $id = SearchEngine::getId();
        $index = SearchEngine::getIndex();

        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $tpl = empty($prefix) ? 'pages' : 'terms';

        $page = $this->getSearchCurrentPage();

        //Build defaults data
        $template = array(
            'id' => $id,
            'description' => isset($content['description']) ? $content['description'] : '',
            'mode' => isset($content['mode']) ? $content['mode'] : 'configurations',
            'page' => $page,
            'default' => isset($content['default']) ? $content['default'] : SearchEngine::getDefaults(),
            'formAction' => Action::buildAction($page),
            'count' => $count,

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            //-> toggle
            't_enable' => TeaThemeOptions::__('Enable'),
            't_disable' => TeaThemeOptions::__('Disable'),

            //-> errors
            't_no_index' => TeaThemeOptions::__('The connection seems good but there is no "Index name" 
                as you have set. Click on the "Create index" button.'),
            't_no_connection' => TeaThemeOptions::__('It seems there is a problem with your connection parameters. 
                Please, check your configurations.'),
            't_last_step' => TeaThemeOptions::__('Your parameters are well configured. 
                You can now use the Tea T.O. Search Engine in your website.<br/>Here is the last step: 
                click on the <b>"Index contents"</b> button to index your old posts.<br/><br/>You can also update
                your configuration.'),

            //-> notifications
            't_es_notify_0' => TeaThemeOptions::__('No post has already been indexed. Let\'s do it!'),
            't_es_notify_1' => TeaThemeOptions::__('Great job: your post has been indexed!'),
            't_es_notify_s' => sprintf(
                TeaThemeOptions::__('Well done: %d posts have been indexed!'),
                $count
            ),

            //-> ?
            't_create_index' => TeaThemeOptions::__('Create index'),
            't_index_contents' => TeaThemeOptions::__('Index contents'),
            't_enable_search' => TeaThemeOptions::__('Enable Search Engine?'),
            't_disable_search' => TeaThemeOptions::__('Disable Search Engine?'),

            //-> buttons
            't_es_index' => TeaThemeOptions::__('Index contents'),
            't_es_submit' => TeaThemeOptions::__('Submit'),
            't_es_update' => TeaThemeOptions::__('Update'),
        );

        $template['t_es_server_url'] = TeaThemeOptions::__('Server URL');
        $template['t_es_server_url_description'] = sprintf(
            TeaThemeOptions::__('If your search provider has given you a connection URL, 
                use that instead of filling out server information.
                <br/>http://<code>%s</code>:<code>%d</code> are common values.'),
            $template['default']['server_host'],
            $template['default']['server_port']
        );

        $template['t_es_index_name'] = TeaThemeOptions::__('Index name');
        $template['t_es_index_name_description'] = sprintf(
            TeaThemeOptions::__('Use a uniq name in lowercase with no special character.
                <br/><code>%s</code> is a good value.'),
            $template['default']['index_name']
        );

        $template['t_es_read_timeout'] = TeaThemeOptions::__('Read timeout');
        $template['t_es_read_timeout_description'] = sprintf(
            TeaThemeOptions::__('The maximum time (in seconds) that read requests should wait for server response. 
                If the call times out, wordpress will fallback to standard search.
                <br/><code>%s</code> is a good value.'),
            $template['default']['read_timeout']
        );

        $template['t_es_write_timeout'] = TeaThemeOptions::__('Write timeout');
        $template['t_es_write_timeout_description'] = sprintf(
            TeaThemeOptions::__('The maximum time (in seconds) that write requests should wait for server response. 
                This should be set long enough to index your entire site.
                <br/><code>%s</code> is a good value.'),
            $template['default']['write_timeout']
        );

        $template['t_es_template'] = TeaThemeOptions::__('Search template?');
        $template['t_es_template_tto'] = TeaThemeOptions::__('Use the Tea T.O. Search default template.');
        $template['t_es_template_theme'] = TeaThemeOptions::__('Use your own Search theme template.');
        $template['t_es_template_example'] = TeaThemeOptions::__('<a href="#es_code_example-content" class="tea-to-es-tpl">Click here</a> 
                to see how you can integrate Tea T.O. Elasticsearch results in your template.');

        $template['t_es_indexing'] = TeaThemeOptions::__('Choose in this section which contents you want to index. 
                This page list all post types and terms defined in your WordPress theme.<br/>');
        $template['t_es_gorgeous'] = TeaThemeOptions::__('Some of your posts have already been indexed. Gorgeous!');

        $template['t_es_posttypes'] = TeaThemeOptions::__('Post types');
        $template['t_es_terms'] = TeaThemeOptions::__('Terms');
        $template['t_es_check_all'] = TeaThemeOptions::__('Un/select all options');

        //Get indexes
        $template['configs'] = TeaThemeOptions::getConfigs($id);
        $template['index'] = TeaThemeOptions::getConfigs($index);

        //Get scores
        //$scores = TeaElasticsearch::getFields();

        //Check selected
        $val = TeaThemeOptions::getConfigs($id);
        $template['val'] = array_merge($template['default'], $val);

        //Get datum
        $posttypes = get_post_types(array('public' => 1));
        $terms = get_taxonomies(array('public' => 1));

        $template['posttypes'] = array();
        $template['terms'] = array();

        //Build arrays
        if ($posttypes) {
            foreach ($posttypes as $pt) {
                $template['posttypes'][] = array(
                    'key' => $pt,
                    'post' => get_post_type_object($pt),
                );
            }
        }
        if ($terms) {
            foreach ($terms as $tm) {
                $template['terms'][] = array(
                    'key' => $tm,
                    'term' => get_taxonomy($tm),
                );
            }
        }

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

    /**
     * Return ES id.
     *
     * @return string $id
     *
     * @since 3.0.0
     */
    public static function getSearchId()
    {
        return (string) self::$id;
    }

    /**
     * Return ES index.
     *
     * @return string $index
     *
     * @since 3.0.0
     */
    public static function getSearchIndex()
    {
        return (string) self::$index;
    }
}
