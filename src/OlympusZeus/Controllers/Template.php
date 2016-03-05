<?php

namespace crewstyle\OlympusZeus\Controllers;

use crewstyle\OlympusZeus\Models\Template as TemplateModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Notification;
use crewstyle\OlympusZeus\Controllers\Render;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds asked templates.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Template
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Template
{
    /**
     * @var TemplateModel
     */
    protected $template;

    /**
     * Constructor.
     *
     * @param string $identifier
     * @param string $currentpage
     * @param string $currentsection
     * @param array $details
     *
     * @since 5.0.0
     */
    public function __construct($identifier, $currentpage, $currentsection, $details)
    {
        $this->template = new TemplateModel();

        //Update vars
        $this->template->setIdentifier($identifier);
        $this->template->setCurrentPage($currentpage);
        $this->template->setCurrentSection($currentsection);
        $this->template->setDetails($details);

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
        $details = $this->template->getDetails();

        //Get details
        $slug = $details['slug'];
        $contents = [];

        //Check sections
        if (!empty($details['sections'])) {
            $sectionContents = [];

            //Get all datas
            foreach ($details['sections'] as $k => $section) {
                $sectionContents[$k] = [];

                /**
                 * Build section page contents.
                 *
                 * @var string $slug
                 * @var string $section
                 * @param array $sectionContents[$section]
                 * @return array $sectionContents[$section]
                 *
                 * @since 5.0.0
                 */
                $sectionContents[$k] = apply_filters('olz_menu_'.$slug.'-'.$k.'_contents', $sectionContents[$k]);
            }

            //Update sections' contents
            $contents['sections'] = $stns;
        }
        else {
            /**
             * Build page contents.
             *
             * @var string $slug
             * @param array $contents
             * @return array $contents
             *
             * @since 5.0.0
             */
            $contents = apply_filters('olz_menu_'.$slug.'_contents', $contents);
        }

        //Check if contents are not empty
        if (empty($contents)) {
            Notification::error(Translate::t('Something went wrong in your parameters
                definition: your contents are empty. See README.md for
                more explanations.'));
        }

        //Get all template variables to inject in Twig template
        $tplvars = $this->templateVars();

        //Build contents relatively to the type
        $fields = $this->templateFields($contents);

        //Merge all
        $vars = array_merge($tplvars, ['fields' => $fields]);

        //Display template
        Render::view('layouts/base.html.twig', $vars);
    }

    /**
     * Build header layout.
     *
     * @since 5.0.0
     */
    protected function templateVars()
    {
        $identifier = $this->template->getIdentifier();
        $currentpage = $this->template->getCurrentPage();
        $currentsection = $this->template->getCurrentSection();
        $details = $this->template->getDetails();

        //Prepare notices
        $notice = [];

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
        $title = $details['title'];

        //Works on subtitle
        $subtitle = $currentsection;

        //Build urls
        $urls = [
            'capabilities' => [
                'url' => current_user_can(OLZ_WP_CAP_MAX) 
                    ? admin_url('admin.php?page='.$identifier.'&do=olz-action&from=footer&make=capabilities') 
                    : '',
                'label' => Translate::t('capabilities'),
            ],
        ];

        /**
         * Display footer usefull urls.
         *
         * @param array $urls
         * @param string $identifier
         * @return array $urls
         *
         * @since 4.0.0
         */
        $urls = apply_filters('olz_template_footer_urls', $urls, $identifier);

        //Add last urls
        $urls['version'] = [
            'url' => '',
            'label' => sprintf('<code><small><b>v%s</b></small></code>', OLZ_VERSION),
            'target' => '_blank',
        ];
        $urls['documentation'] = [
            'url' => 'https://olympus.readme.io',
            'label' => Translate::t('Documentation'),
            'target' => '_blank',
        ];
        $urls['support'] = [
            'url' => 'https://olympus.readme.io/discuss',
            'label' => Translate::t('Support'),
            'target' => '_blank',
        ];
        $urls['teato'] = [
            'url' => 'https://teato.me',
            'label' => Translate::t('TeaTO.me'),
            'target' => '_blank',
        ];

        //Partners
        $partners = [
            [
                'url' => 'http://www.takeatea.com',
                'label' => Translate::t('Take a tea'),
                'image' => OLZ_URI.'/assets/img/partners/takeatea.png',
            ],
            [
                'url' => 'http://www.basketsession.com/',
                'label' => Translate::t('Éditions REVERSE Magazine'),
                'image' => OLZ_URI.'/assets/img/partners/basketsession.png',
            ],
        ];

        //Current page to check
        $current = empty($currentpage) ? $identifier : $currentpage;

        //Get all pages with link, icon and slug
        $template = [
            'identifier' => $identifier,
            'version' => OLZ_VERSION,
            'currentPage' => empty($currentpage) ? $identifier : $currentpage,
            'currentSection' => empty($currentsection) ? '' : $currentsection,

            'title' => empty($title) ? Translate::t('Olympus') : $title,
            'subtitle' => empty($subtitle) ? '' : $subtitle,
            'description' => $details['description'],
            'submit' => $details['submit'],
            'sections' => isset($details['sections']) ? $details['sections'] : [],
            'notice' => $notice,
            'urls' => $urls,
            'partners' => $partners,

            //texts
            't_title' => Translate::t('Olympus'),
            't_update' => Translate::t('Update'),
            't_copyright' => Translate::t('Built with ♥ by <a href="https://github.com/crewstyle" target="_blank"><b>Achraf Chouk</b></a>'),
            't_quote' => OLZ_QUOTE,
        ];

        //Get template
        return $template;
    }

    /**
     * Build each type content.
     *
     * @param array $contents Contains all data
     *
     * @since 5.0.0
     */
    protected function templateFields($contents)
    {
        $identifier = $this->template->getIdentifier();
        $currentpage = $this->template->getCurrentPage();
        $currentsection = $this->template->getCurrentSection();

        //Build special pages
        $enabled = [$identifier];

        /**
         * Get special pages.
         *
         * @param array $enabled
         * @param string $identifier
         * @return array $enabled
         *
         * @since 4.0.0
         */
        $enabled = apply_filters('olz_template_special_pages', $enabled, $identifier);

        //Define if we are in a special page or not
        $specials = in_array($currentpage, $enabled);

        $template = [];
        $usedIds = [];

        //Check contents
        if (empty($contents)) {
            return;
        }

        //Check sections
        $contents = isset($contents['sections']) ? $contents['sections'][$currentsection] : $contents;

        //Iteration on all array
        foreach ($contents as $content) {
            //Get type and id
            $type = isset($content['type']) ? $content['type'] : '';
            $id = isset($content['id']) ? $content['id'] : '';

            //Get field instance
            $field = Field::build($type, $id, $usedIds, $specials);

            //Check error
            if (is_array($field) && $field['error']) {
                $template[] = $field;
                continue;
            }

            //Update ids
            if (!empty($id)) {
                $usedIds[] = $id;
            }

            /**
             * Set current page.
             *
             * @param string $current
             *
             * @since 4.0.0
             */
            do_action('olz_field_current_page', $currentpage);

            //Display field content
            $template[] = $field->render($content, [], false);
        }

        return $template;
    }
}
