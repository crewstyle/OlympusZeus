<?php

namespace crewstyle\TeaThemeOptions\Controllers\Template;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Action\Action;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO TEMPLATE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Template
 *
 * Class used to make actions from GET param.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Template\Template
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.2.4
 *
 */
class Template
{
    /**
     * @var array
     */
    protected $breadcrumb = array();

    /**
     * @var string
     */
    protected $current = '';

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $pages = array();

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     * @param string $currentpage Define the current page
     * @param array $pages Contains all usefull pages
     *
     * @since 3.0.0
     */
    public function __construct($identifier, $currentpage, $pages)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get details
        $this->identifier = $identifier;
        $this->current = $currentpage;
        $this->pages = $pages;

        //Build breadcrumb
        foreach ($this->pages as $page) {
            //Works on title
            $title = $page['title'];

            //Check title
            if (preg_match('/<span style=\"color\:\#([a-zA-Z0-9]{3,6})\">(.*)<\/span>/i', $title, $matches)) {
                $title = '<b style="color:#'.$matches[1].'">'.$matches[2].'</b>';
            }

            //Build breadcrumb
            $this->breadcrumb[] = array(
                'title' => $title,
                'slug' => $page['slug']
            );
        }

        //Initialize layout
        $this->initialize();
    }

    /**
     * Build header layout.
     *
     * @since 3.0.0
     */
    protected function initialize()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get current infos
        $current = empty($this->current) ? $this->identifier : $this->current;

        //Checks contents
        if (empty($this->pages[$current]['contents'])) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong: it seems you
                forgot to attach contents to the current page.')
            );
        }

        //Get current page contents
        $contents = $this->pages[$current]['contents'];

        //Get all template variables to inject in Twig template
        $tplvars = $this->tplVars();

        //Build contents relatively to the type
        $fields = $this->tplFields($contents);

        //Merge all
        $vars = array_merge($tplvars, array('fields' => $fields));

        //Display template
        TeaThemeOptions::getRender('layouts/menu.html.twig', $vars);
    }

    /**
     * Build header layout.
     *
     * @since 3.2.4
     */
    protected function tplVars()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Build notices
        $notice = array();
        $action = isset($_REQUEST['action']) && 'tea-to-action' == $_REQUEST['action'] ? true : false;
        $for = isset($_REQUEST['for']) ? $_REQUEST['for'] : false;

        if ($action && $for && 'settings' == $for) {
                $notice[] = array('updated', TeaThemeOptions::__('The Tea Theme Options is updated.'));
        }
        else if ($action && $for && 'dashboard' == $for) {
            $notice[] = array('updated', TeaThemeOptions::__('Your Tea Theme Options\' settings are updated.'));
        }

        /**
         * Display notice screen.
         *
         * @param array $notice An array of all notices with their statuses
         *
         * @since 3.0.0
         */
        $notice = apply_filters('tea_to_notice', $notice);

        //Works on title
        $title = empty($this->current) 
            ? $this->pages[$this->identifier]['title'] 
            : $this->pages[$this->current]['title'];

        //Build urls
        $urls = array(
            'capabilities' => array(
                'url' => current_user_can(TTO_WP_CAP_MAX) 
                    ? Action::buildAction($this->identifier, 'footer').'&make=capabilities' : '',
                'label' => TeaThemeOptions::__('Update capabilities'),
            ),
        );

        /**
         * Display footer usefull urls.
         *
         * @param array $notice An array of all footer usefull urls
         *
         * @since 3.2.0
         */
        $urls = apply_filters('tea_to_footer_urls', $urls, $this->identifier);

        //Partners
        $partners = array(
            array(
                'url' => 'http://www.takeatea.com',
                'label' => 'Take a tea',
                'image' => TTO_URI.'/assets/img/partners/takeatea.png',
            ),
        );

        //Get all pages with link, icon and slug
        $template = array(
            'identifier' => $this->identifier,
            'version' => TTO_VERSION,
            'icon' => file_get_contents(TTO_URI.'/assets/img/teato.svg', FILE_USE_INCLUDE_PATH),
            'currentPage' => empty($this->current) ? $this->identifier : $this->current,

            'title' => empty($title) ? TeaThemeOptions::__('Tea Theme Options') : $title,
            'description' => empty($this->current) 
                ? $this->pages[$this->identifier]['description'] 
                : $this->pages[$this->current]['description'],
            'submit' => empty($this->current) 
                ? $this->pages[$this->identifier]['submit'] 
                : $this->pages[$this->current]['submit'],
            'breadcrumb' => $this->breadcrumb,
            'notice' => $notice,
            'urls' => $urls,
            'partners' => $partners,

            //texts
            't_tto_title' => TeaThemeOptions::__('Tea T.O.'),
            't_tto_search' => TeaThemeOptions::__('Search template'),
            't_tto_update' => TeaThemeOptions::__('Update'),
            't_tto_copyright' => TeaThemeOptions::__('&copy; 2015, all rights reserved. Built with ♥ by Achraf Chouk.'),
            't_tto_contact' => sprintf(
                TeaThemeOptions::__('Please, check  
                    <a href="https://tea-theme-options.readme.io/discuss" target="_blank">
                    <b>the Tea T.O. support</b></a> if you have any suggestions.'),
                TTO_VERSION
            ),
            't_tto_version' => sprintf(
                TeaThemeOptions::__('Your Tea Theme Option is in <code><small>version %s</small></code>'),
                TTO_VERSION
            ),
            't_tto_quote' => TTO_QUOTE,
        );

        //Get template
        return $template;
    }

    /**
     * Build each type content.
     *
     * @param array $contents Contains all data
     *
     * @since 3.0.0
     */
    protected function tplFields($contents)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        $enabled = array($this->identifier);
        $enabled = apply_filters('tea_to_template_special', $enabled, $this->identifier);

        //Define if we are in a special page or not
        $specials = in_array(
            $this->current,
            $enabled
        );

        $template = array();
        $ids = array();

        //Iteration on all array
        foreach ($contents as $content) {
            //Get type and id
            $type = isset($content['type']) ? $content['type'] : '';
            $id = isset($content['id']) ? $content['id'] : '';

            //Get field instance
            $field = Field::getField($type, $id, $specials, $ids);

            //Check error
            if (is_array($field) && $field['error']) {
                $template[] = $field;
                continue;
            }

            //Update ids
            if (!empty($id)) {
                $ids[] = $id;
            }

            //Set current page
            do_action('tea_to_field_current_page', $this->current);

            //Display field content
            $template[] = $field->prepareField($content);
        }

        return $template;
    }
}
