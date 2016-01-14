<?php

namespace crewstyle\OlympusZeus\Core\Menu;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds asked templates.
 *
 * @package Olympus Zeus
 * @subpackage Core\Menu\MenuTemplate
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class MenuTemplate
{
    /**
     * @var array
     */
    protected $breadcrumb = array();

    /**
     * @var string
     */
    protected $currentPage = '';

    /**
     * @var string
     */
    protected $currentSection = '';

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
     * @param string $currentsection Define the current section
     * @param array $pages Contains all usefull pages
     *
     * @since 4.0.0
     */
    public function __construct($identifier, $currentpage, $currentsection, $pages)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Get details
        $this->identifier = $identifier;
        $this->currentPage = $currentpage;
        $this->currentSection = $currentsection;
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
     * @since 4.0.0
     */
    protected function initialize()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Get current infos
        $currentpage = empty($this->currentPage) ? $this->identifier : $this->currentPage;
        $currentsection = empty($this->currentSection) ? '' : $this->currentSection;
        $slug = $this->pages[$currentpage]['id'];
        $contents = array();

        //Check sections
        if (!empty($this->pages[$currentpage]['sections'])) {
            $stns = array();

            //Get all datas
            foreach ($this->pages[$currentpage]['sections'] as $k => $section) {
                $stns[$k] = array();

                //Update section
                if (empty($this->currentSection)) {
                    $this->currentSection = $k;
                    $this->breadcrumb['currentSection'] = $section['title'];
                }
                else if ($this->currentSection === $k) {
                    $this->breadcrumb['currentSection'] = $section['title'];
                }

                /**
                 * Build section page contents.
                 *
                 * @var string $slug
                 * @var string $section
                 * @param array $stns[$section]
                 * @return array $stns[$section]
                 *
                 * @since 4.0.0
                 */
                $stns[$k] = apply_filters('olz_menu_'.$slug.'-'.$k.'_contents',$stns[$k]);
            }

            //Update sections' contents
            $contents['sections'] = $stns;
        }
        else if ($currentpage == $this->identifier) {
            //Main page special case
            $contents = $this->pages[$currentpage]['contents'];
        }
        else {
            $contents = array();

            /**
             * Build page contents.
             *
             * @var string $slug
             * @param array $contents
             * @return array $contents
             *
             * @since 4.0.0
             */
            $contents = apply_filters('olz_menu_'.$slug.'_contents', $contents);
        }

        //Check if contents are not empty
        if (empty($contents)) {
            OlympusZeus::notify('error',
                OlympusZeus::translate('Something went wrong in your parameters
                definition: your contents are empty. See README.md for
                more explanations.')
            );
        }

        //Get all template variables to inject in Twig template
        $tplvars = $this->tplVars();

        //Build contents relatively to the type
        $fields = $this->tplFields($contents);

        //Merge all
        $vars = array_merge($tplvars, array('fields' => $fields));

        //Display template
        OlympusZeus::getRender('layouts/base.html.twig', $vars);
    }

    /**
     * Build header layout.
     *
     * @since 4.0.0
     */
    protected function tplVars()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Prepare notices
        $notice = array();

        /**
         * Display notice screen.
         *
         * @param array $notice
         * @return array $notice
         *
         * @since 4.0.0
         */
        $notice = apply_filters('olz_template_notice', $notice);

        //Works on title
        $title = empty($this->currentPage) 
            ? $this->pages[$this->identifier]['title'] 
            : $this->pages[$this->currentPage]['title'];

        //Works on subtitle
        $subtitle = empty($this->currentSection) ? '' : $this->breadcrumb['currentSection'];

        //Build urls
        $urls = array(
            'capabilities' => array(
                'url' => current_user_can(OLZ_WP_CAP_MAX) 
                    ? admin_url('admin.php?page='.$this->identifier.'&do=olz-action&from=footer&make=capabilities') 
                    : '',
                'label' => OlympusZeus::translate('capabilities'),
            ),
        );

        /**
         * Display footer usefull urls.
         *
         * @param array $urls
         * @param string $identifier
         * @return array $urls
         *
         * @since 4.0.0
         */
        $urls = apply_filters('olz_template_footer_urls', $urls, $this->identifier);

        //Add last urls
        $urls['version'] = array(
            'url' => '',
            'label' => sprintf('<code><small><b>v%s</b></small></code>', OLZ_VERSION),
            'target' => '_blank',
        );
        $urls['documentation'] = array(
            'url' => 'https://olympus.readme.io',
            'label' => OlympusZeus::translate('Documentation'),
            'target' => '_blank',
        );
        $urls['support'] = array(
            'url' => 'https://olympus.readme.io/discuss',
            'label' => OlympusZeus::translate('Support'),
            'target' => '_blank',
        );
        $urls['teato'] = array(
            'url' => 'https://teato.me',
            'label' => OlympusZeus::translate('TeaTO.me'),
            'target' => '_blank',
        );

        //Partners
        $partners = array(
            array(
                'url' => 'http://www.takeatea.com',
                'label' => 'Take a tea',
                'image' => OLZ_URI.'/assets/img/partners/takeatea.png',
            ),
            array(
                'url' => 'http://www.basketsession.com/',
                'label' => 'Éditions REVERSE Magazine',
                'image' => OLZ_URI.'/assets/img/partners/basketsession.png',
            ),
        );

        //Current page to check
        $current = empty($this->currentPage) ? $this->identifier : $this->currentPage;

        //Get all pages with link, icon and slug
        $template = array(
            'identifier' => $this->identifier,
            'version' => OLZ_VERSION,
            'currentPage' => empty($this->currentPage) ? $this->identifier : $this->currentPage,
            'currentSection' => empty($this->currentSection) ? '' : $this->currentSection,

            'title' => empty($title) ? OlympusZeus::translate('Olympus') : $title,
            'subtitle' => empty($subtitle) ? '' : $subtitle,
            'description' => $this->pages[$current]['description'],
            'submit' => $this->pages[$current]['submit'],
            'sections' => isset($this->pages[$current]['sections']) ? $this->pages[$current]['sections'] : array(),
            'breadcrumb' => $this->breadcrumb,
            'notice' => $notice,
            'urls' => $urls,
            'partners' => $partners,

            //texts
            't_title' => OlympusZeus::translate('Olympus'),
            't_update' => OlympusZeus::translate('Update'),
            't_copyright' => OlympusZeus::translate('Built with ♥ by <a href="https://github.com/crewstyle" target="_blank"><b>Achraf Chouk</b></a>'),
            't_quote' => OLZ_QUOTE,
        );

        //Get template
        return $template;
    }

    /**
     * Build each type content.
     *
     * @param array $contents Contains all data
     *
     * @since 4.0.0
     */
    protected function tplFields($contents)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Build special pages
        $enabled = array($this->identifier);

        /**
         * Get special pages.
         *
         * @param array $enabled
         * @param string $identifier
         * @return array $enabled
         *
         * @since 4.0.0
         */
        $enabled = apply_filters('olz_template_special_pages', $enabled, $this->identifier);

        //Define if we are in a special page or not
        $specials = in_array(
            $this->currentPage,
            $enabled
        );

        $template = array();
        $ids = array();

        //Check contents
        if (empty($contents)) {
            return;
        }

        //Check sections
        $contents = isset($contents['sections']) ? $contents['sections'][$this->currentSection] : $contents;

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

            /**
             * Set current page.
             *
             * @param string $current
             *
             * @since 4.0.0
             */
            do_action('olz_field_current_page', $this->currentPage);

            //Display field content
            $template[] = $field->prepareField($content);
        }

        return $template;
    }
}
