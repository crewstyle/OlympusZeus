=== Tea Theme Options (v1.4.1) ===


== Simple, easy to use and fully integrated Theme Options for Wordpress.

The Tea Theme Options (https://github.com/Takeatea/tea_theme_options) (or Tea TO) allows you to easily add professional looking theme options panels to your WordPress theme. The Tea TO is built for Wordpress (http://wordpress.org "CMS Wordpress") v3.x and uses the Wordpress built-in pages.

+ Options API (http://codex.wordpress.org/Options_API) - A simple and standardized way of storing data in the database.
+ Transients API (http://codex.wordpress.org/Transients_API) - Very similar to the Options API but with the added feature of an expiration time, which simplifies the process of using the wp_options database table to temporarily store cached information.
+ Elasticsearch API (http://www.elasticsearch.org/) - Elasticsearch creates scaleable, real-time search for your website by indexing all your datas millions of times a day.
+ Wordpress Media Manager - Beautiful interface: A streamlined, all-new experience where you can create galleries faster with drag-and-drop reordering, inline caption editing, and simplified controls.+ Wordpress Media Manager - Beautiful interface: A streamlined, all-new experience where you can create galleries faster with drag-and-drop reordering, inline caption editing, and simplified controls.
+ Easier for administrators - The interface is thought to be the most userfriendly. The Tea TO core adds some extra interface customisations to make your life easier.
+ Easier for developers - Create a new admin panel easily with only 2 lines. The Tea TO core is made to allow non-developer profiles to easily create the settings they need to customise their templates.  


== Summary

+ 1) Installation the theme roller
+ 2) Let's roll!
+ 3) Tiny example
+ 4) That's all folkes!
+ 5) Authors and Copyright


### 1) Installing the theme roller

To get started, go to into your template folder `wp-content/your_template/` and create a `composer.json` with these lines:

```json
{
    "name": "takeatea/your_template_name",
    "description": "",
    "type": "wordpress-theme",
    "minimum-stability": "dev",
    "require": {
        "takeatea/tea-theme-options": "1.4.1"
    }
}
```


Now, you have to install Composer and use it to download and install the Tea Theme Options with all its dependancies.  
To do so, follow the next command lines:

```
curl -s http://getcomposer.org/installer | php
php composer.phar install
# use this command if Composer is note up to date:
# php composer.phar self-update
```


### 2) Let's roll!

Check your theme folder and see the new `vendor` folder created.  
In it, you are supposed to find `composer`, `ruflin` and `takeatea` directories.

That's all!

The next step is to make your theme and the Tea Theme Options work together.  
(And it's pretty simple... :))


### 3) Tiny example

To create your first new settings page, simply follow the instructions.

1. First of all, use the operator `use` to include the library at the very top of your `functions.php` file:

```php
use Takeatea\TeaThemeOptions\TeaThemeOptions;
```


2 Next, just instanciate a TeaThemeOptions object:

```php
//Include composer autoload
require_once __DIR__.'/vendor/autoload.php';

//Instanciate a new TeaThemeOptions
$tea = new TeaThemeOptions();
```


3 Specify your wanted components:

```php
//Define page
$tea_titles = array(
    'title' => __('Example page'),
    'name' => __('Example page'),
    'slug' => '_example_page'
);
//Define fields
$tea_contents = array(
    array(
        'type' => 'heading',
        'title' => __('Header.')
    ),
    array(
        'type' => 'radio',
        'title' => __('Fix the main menu bar to the top of the screen?'),
        'id' => 'header_menu_position',
        'std' => 'yes',
        'options' => array(
            'yes' => __('Yes'),
            'no' => __('No')
        )
    ),
    array(
        'type' => 'heading',
        'title' => __('Footer.', TEMPLATE_DICTIONARY)
    ),
    array(
        'type' => 'text',
        'title' => __('Copyright.', TEMPLATE_DICTIONARY),
        'id' => 'footer_copyright',
        'std' => __('&copy; MyWebsite.com, all rights reserved ~ Built with passion and Tea Theme Options!')
    ),
);
//Add fields to the page
$tea->addPage($tea_titles, $tea_contents);
//Delete variables because we love our server!
unset($tea_titles, $tea_contents);
```


4 Build the page:

```php
//Build menus
$tea->buildPages();
```


### 4) That's all folkes!

Here is the latest step: check quickly your new panel options.

+ Go to your `your_wp_website/wp-admin`
+ Log in to your admin panel
+ See that you have a new `Tea T.O.` Link in your admin sidebar

That's all to begin working with Tea TO


### 5) Authors and Copyright

Take a Tea

+ http://takeatea.com
+ http://twitter.com/takeatea
+ http://github.com/takeatea

Achraf Chouk

+ http://fr.linkedin.com/in/achrafchouk/
+ http://twitter.com/crewstyle
+ http://github.com/crewstyle

Copyright 20xx Take a tea (http://takeatea.com "Take a tea")  
Brewed by Take a tea ;)
