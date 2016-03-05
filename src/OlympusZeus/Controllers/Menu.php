<?php

namespace crewstyle\OlympusZeus\Controllers;

use crewstyle\OlympusZeus\Models\Menu as MenuModel;
use crewstyle\OlympusZeus\Controllers\Notification;
use crewstyle\OlympusZeus\Controllers\Request;
use crewstyle\OlympusZeus\Controllers\Template;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Gets its own menu.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Menu
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

class Menu
{
    /**
     * @var MenuModel
     */
    protected $menu;

    /**
     * Constructor.
     *
     * @param string $identifier
     * @param array $options
     *
     * @since 5.0.0
     */
    public function __construct($identifier, $options)
    {
        $this->menu = new MenuModel();

        //Set default option's values
        $defaults = [
            //Page options
            'title' => Translate::t('Menu Title'),
            'name' => Translate::t('Menu Name'),
            'icon' => OLZ_URI.'/assets/img/zeus-icon.svg',
            'position' => 80,
            'sections' => [],

            //Options
            'capabilities' => OLZ_WP_CAP_MAX,
            'adminbar' => true,
        ];

        //Update vars
        $this->menu->setIdentifier($identifier);
        $this->menu->setOptions(array_merge($defaults, $options));

        //Create root menu
        $this->addRootMenu();
    }

    /**
     * Add root single menu.
     *
     * @since 5.0.0
     */
    public function addRootMenu()
    {
        $identifier = $this->menu->getIdentifier();
        $options = $this->menu->getOption();

        //Add main page
        add_menu_page(
            $this->menu->getOption('title'),
            $this->menu->getOption('name'),
            $this->menu->getOption('capabilities'),
            $this->menu->getIdentifier(),
            [&$this, 'callback'],
            $this->menu->getOption('icon'),
            $this->menu->getOption('position')
        );

        //Update pages
        $this->menu->setPages([]);
        $this->menu->addPage($this->menu->getIdentifier(), [
            'title' => $this->menu->getOption('title'),
            'name' => $this->menu->getOption('name'),
            'sections' => $this->menu->getOption('sections'),
            'slug' => $identifier,
        ]);

        //Add admin bar menu
        if ($this->menu->getOption('adminbar')) {
            $this->addRootAdminBar();
        }
    }

    /**
     * Add child single menu.
     *
     * @param string $slug
     * @param array $options
     *
     * @since 5.0.0
     */
    public function addChild($slug, $options)
    {
        //Admin panel
        if (empty($slug)) {
            Notification::error(Translate::t('Your slug or callback function is missing.'));

            return;
        }

        //Check page
        if ($this->menu->hasPage($slug)) {
            Notification::error(Translate::t('Your page\' slug has already been defined.'));

            return;
        }

        //Set default option's values
        $defaults = [
            'title' => Translate::t('Menu Sub Title'),
            'name' => Translate::t('Menu Sub Name'),
            'sections' => [],
            'capabilities' => OLZ_WP_CAP_MAX,
            'adminbar' => true,
        ];

        //Merge options
        $options = array_merge($defaults, $options);

        //Add child page
        add_submenu_page(
            $this->menu->getIdentifier(),
            $options['title'],
            $options['name'],
            $options['capabilities'],
            $slug,
            [&$this, 'callback']
        );

        //Update pages
        $this->menu->addPage($slug, [
            'title' => $options['title'],
            'name' => $options['name'],
            'sections' => $options['sections'],
            'slug' => $slug,
        ]);

        //Add admin bar menu
        if ($this->menu->getOption('adminbar')) {
            $this->addChildAdminBar($slug, $options);
        }
    }

    /**
     * Add root admin bar menu.
     *
     * @since 5.0.0
     */
    public function addRootAdminBar()
    {
        global $wp_admin_bar;

        //Add main admin bar
        $wp_admin_bar->add_node([
            'parent' => '',
            'id' => $this->menu->getIdentifier(),
            'title' => $this->menu->getOption('title'),
            'href' => admin_url('admin.php?page='.$this->menu->getIdentifier()),
            'meta' => false
        ]);
    }

    /**
     * Define hook.
     *
     * @param string $slug
     * @param array $options
     *
     * @since 5.0.0
     */
    public function addChildAdminBar($slug, $options)
    {
        global $wp_admin_bar;

        //Add child admin bar
        $wp_admin_bar->add_node([
            'parent' => $this->menu->getIdentifier(),
            'id' => $this->menu->getIdentifier().'-'.$slug,
            'title' => $options['title'],
            'href' => admin_url('admin.php?page='.$this->menu->getIdentifier().'-'.$slug),
            'meta' => false
        ]);
    }

    /**
     * Hook method.
     *
     * @since 5.0.0
     */
    public function callback()
    {
        //Get current page and section
        $currentPage = Request::get('page');
        $currentSection = Request::get('section');

        //Check current page
        if (!$this->menu->hasPage($currentPage)) {
            return;
        }

        //Instantiate templates
        $template = new Template(
            $this->menu->getIdentifier(),
            $currentPage,
            $currentSection,
            $this->menu->getPage($currentPage)
        );
    }
}
