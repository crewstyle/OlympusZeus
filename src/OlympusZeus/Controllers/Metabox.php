<?php

namespace crewstyle\OlympusZeus\Controllers;

use crewstyle\OlympusZeus\Models\Metabox as MetaboxModel;
use crewstyle\OlympusZeus\Controllers\Notification;
use crewstyle\OlympusZeus\Controllers\Render;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Gets its own post type.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Metabox
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Metabox
{
    /**
     * @var MetaboxModel
     */
    protected $metabox;

    /**
     * Constructor.
     *
     * @param string $identifier
     * @param string $slug
     * @param string $title
     * @param array $args
     *
     * @since 5.0.0
     */
    public function __construct($identifier, $slug, $title, $args)
    {
        $this->metabox = new MetaboxModel();

        $this->metabox->setId($identifier);
        $this->metabox->setSlug($slug);
        $this->metabox->setTitle($title);
        $this->metabox->setArgs($args);

        $this->addMetabox();
    }

    /**
     * Add metabox.
     *
     * @since 5.0.0
     */
    protected function addMetabox()
    {
        add_meta_box(
            $this->metabox->getId(),
            $this->metabox->getTitle(),
            [&$this, 'callback'],
            $this->metabox->getSlug(),
            $this->metabox->getContext(),
            $this->metabox->getPriority(),
            $this->metabox->getArgs()
        );
    }

    /**
     * Callback function.
     *
     * @param array $post
     * @param array $args
     * @return int|null
     *
     * @since 5.0.0
     */
    public function callback($post, $args)
    {
        //If autosave...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return isset($post->ID) ? $post->ID : null;
        }

        //Get contents
        $content = isset($args['args']['contents']) ? $args['args']['contents'] : [];
        $field = isset($args['args']['field']) ? $args['args']['field'] : '';

        //Check if a type is defined
        if (empty($content) || empty($field) || !isset($args['args']['type'])) {
            Notification::error(Translate::t('A field is missing because no type is defined.'));

            return null;
        }

        //Display field content
        $tpl = $field->render($content, ['post' => $post]);

        //Return post if it is asked
        return isset($post->ID) ? $post->ID : null;
    }
}
