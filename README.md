Tea Theme Options
=================

The Tea Theme Options (or "**Tea TO**") allows you to easily add professional looking theme options panels to your WordPress theme.
This document contains information on how to download, install, and start using the Tea Theme Options Wordpress project.  
Note: Tea Theme Options is built for [Wordpress](http://wordpress.org "CMS Wordpress") v3.x and uses the Wordpress built-in pages.


1) Installing the theme roller
------------------------------

**To get started, checkout** https://github.com/takeatea/tea_to_wp into the `wp-content/your_template/`

    git clone https://github.com/takeatea/tea_to_wp __YOUR_TEA_TO_CUSTOM_FOLDER__

Check your new `__YOUR_TEA_TO_CUSTOM_FOLDER__` folder is created in your template directory (you can rename it if you want).

Include the `tea-theme-options.php` file in your `functions.php`

    include('__YOUR_TEA_TO_CUSTOM_FOLDER__/tea-theme-options.php');


2) Create a new Tea_Theme_Options and set details
-------------------------------------------------

Before starting, instanciate a new object with an uniq identifier:

    $tea = new Tea_Theme_Options('your_identifier');

And set details:

    $tea->__setDuration(86400); //Will stock transient for 86400sec, eq. to 24h
    $tea->__setDirectory('__YOUR_TEA_TO_CUSTOM_FOLDER__'); //Will define the current Tea Theme Options directory.

NOTA: by default, the Tea TO will consider the `tea_theme_options` folder


3) Let's roll!
--------------

Create your new first page with global details (as capability, icon, bigicon and descripton if you want):

    $tea->addPage(array(
        //used in your page
        'title' => 'My first page',
        //used in the admin menu
        'name' => 'First page',
        //a writer can use this page. If you want to give access only to the admin, use 'admin' instead
        'capability' => 'edit_pages',
        //used in the admin menu
        'icon' => get_template_directory_uri() . '/img/admin/settings_16.png',
        //used in your page
        'bigicon' => get_template_directory_uri() . '/img/admin/settings_32.png',
        //used in your page
        'description' => 'Here is a description of my first page'
    ));

Add all used fields in the last created page (to get definition fields, see below "5) Adding fields"):

    $tea->addFields(array(
        array(
            'type' => 'text',
            'title' => 'My first text title',
            'id' => 'my_first_text_id',
            'std' => 'My default text value'
            'description' => 'My first text description'
        ),
        array(
            'type' => 'password',
            'title' => 'My first password field',
            'id' => 'my_first_password_id',
            'description' => 'My first password description'
        )
    ));

Create a new sub page with usefull parameters (add description if you need it). Try to put uniq slug for each array:

    $tea->addSubpage(array(
        'title' => 'Subpage Options',
        'name' => 'Homepage',
        'slug' => '_homepage'
    ));

Add all used fields in the last created page (to get definition fields, see below "5) Adding fields"):

    $tea->addFields(array(
        array(
            'type' => 'text',
            'title' => 'My second text title',
            'id' => 'my_second_text_id',
            'std' => 'My other default text value'
            'description' => 'My second text description'
        ),
        array(
            'type' => 'checkbox',
            'title' => 'My first checkbox field',
            'id' => 'my_first_checkbox_id',
            'std' => array('check1', 'check2'),
            'description' => 'My first checkbox description',
            'options' => array(
                'check1' => 'First checbox',
                'check2' => 'Second checbox',
                'check3' => 'Third checbox'
            )
        )
    ));


4) Building menus
-----------------

The last step is to build menus and get your pages available in the adminbar menu.

    $tea->buildMenus();


5) Adding fields
----------------

Adding fields is quite simple. All you have to do is to create an array and define
all inputs nedded in your Wordpress template.
Note: the Tea Theme Options uses transient to stock options. All options are named as `tea_to_your_input_id`

All available types are:

+ **Display inputs**:
++ Br, display a simple breakline with clear css class
++ Heading, display a simple title
++ Hr, equivalent to the br input, but display an horizontal line

+ **Normal inputs**:
++ Checkbox
++ Hidden
++ Password
++ Radio
++ Text
++ Textarea
++ Upload (for images only)

+ **Special inputs**:
++ Category, offers a WP category listing with multiselect option if needed
++ Color, offers an input text with colorpicker
++ Font, offers a list of font choices with image labels (uniq choices)
++ Group, offers the possibility to add multiple inputs
++ Image, offers a list of choices with image labels (uniq choices)
++ Menu, display menus as information (no action required)
++ Page, display pages as information (no action required)
++ Sidebar, display widgets as information (no action required)
++ Social, display a list of wanted social buttons with multichoice
++ Typeahead, offers an input text with ajax call __IN PROGRESS__


6) Display inputs
-----------------

Adding a `br`

    array(
        'type' => 'br'
    )

Adding an `heading`

    array(
        'type' => 'heading',
        'title' => 'Heading'
    )

Adding an `hr`

    array(
        'type' => 'hr'
    )


7) Normal inputs
----------------

Adding a `checkbox`

    array(
        'type' => 'checkbox', //define the input type
        'title' => 'Checkbox', //define the title block
        'id' => 'simple_checkbox', //define the uniq ID
        'std' => array('mountain', 'beach'), //define the default choices
        'description' => 'Simple description to checkbox panel', //define the description block
        //define the options
        'options' => array(
            'mountain' => 'Mountain', //value => label
            'sea' => 'Sea',
            'beach' => 'Beach'
        )
    )

Adding a `hidden`

    array(
        'type' => 'hidden',
        'id' => 'simple_hidden',
        'std' => 'Simple hidden'
    )

Adding a `password`

    array(
        'type' => 'password',
        'title' => 'Password',
        'id' => 'simple_password',
        'placeholder' => 'Simple placeholder',
        'description' => 'Simple description to password panel'
    )

Adding a `radio`

    array(
        'type' => 'radio',
        'title' => 'Radio buttons',
        'id' => 'simple_radio',
        'std' => 'sea',
        'description' => 'Simple description to radio buttons panel',
        'options' => array(
            'mountain' => 'Mountain',
            'sea' => 'Sea',
            'beach' => 'Beach'
        )
    )

Adding a `text`
NOTA: you can define the maxlength optional attribute

    array(
        'type' => 'text',
        'title' => 'Text',
        'id' => 'simple_text',
        'std' => 'Simple text',
        'placeholder' => 'Simple placeholder',
        'description' => 'Simple description to text panel',
        'maxlength' => 7
    )

Adding a `textarea`

    array(
        'type' => 'textarea',
        'title' => 'Textarea',
        'id' => 'simple_textarea',
        'std' => 'Simple textarea',
        'placeholder' => 'Simple placeholder',
        'description' => 'Simple description to textarea panel'
    )

Adding an `upload`
NOTA: the upload input uses the Media file uploader or Wordpress.

    array(
        'type' => 'upload',
        'title' => 'Upload',
        'id' => 'simple_upload',
        'std' => get_template_directory_uri() . 'img/admin/default.png',
        'description' => 'Simple description to upload panel'
    )


8) Special inputs
-----------------

Adding a `category`
NOTA: the category input has a special method which detects if the ID has the `__category` term to register in transient more than expected, as the category title, slug and category.

    array(
        'type' => 'category',
        'title' => 'Category',
        'id' => 'simple_cat__category', //if you need the id only, don't use the `__category` term
        'multiselect' => true //Optional: to "false" by default
    )

Adding a `color`

    array(
        'type' => 'color',
        'title' => 'Color',
        'id' => 'simple_color',
        'std' => '#123456',
        'description' => 'Simple description to color panel'
    )

Adding an `font`
NOTA: the Tea Theme Options package offers a large set of fonts. If you want them, set the `default` attribute to `true`

    array(
        'type' => 'font',
        'title' => 'Font',
        'id' => 'simple_font',
        'std' => 'font1',
        'description' => 'Simple description to font panel',
        'default' => true,
        'options' => array(
            'font1' => get_template_directory_uri() . '/img/font1.png',
            'font2' => get_template_directory_uri() . '/img/font2.png',
            'font3' => get_template_directory_uri() . '/img/font3.png'
        )
    )

Adding a `group`
NOTA: groups is able to display to 4 input columns per line. You can break a line by 2 ways:
+ Add more than 4 inputs
+ Add a `br`, `heading` or `hr` input

    array(
        'type' => 'group',
        'title' => 'Group',
        'description' => 'Simple optional group description',
        'contents' => array(
            array(
                'type' => 'radio',
                'title' => 'Radio group',
                'id' => 'simple_radio_group',
                'std' => 'beach',
                'description' => 'Simple description to radio group panel',
                'options' => array(
                    'mountain' => 'Mountain',
                    'sea' => 'Sea',
                    'beach' => 'Beach'
                )
            ),
            array(
                'type' => 'text',
                'title' => 'Text group',
                'id' => 'simple_text_group',
                'std' => 'Simple text group',
                'description' => 'Simple description to text group panel'
            ),
            array(
                'type' => 'br'
            ),
            array(
                'type' => 'sidebar',
                'title' => 'Sidebar group',
                'id' => '1st-homepage-content-block',
                'description' => 'Simple description to text group panel'
            )
        )
    )

Adding an `image`
NOTA: the Tea Theme Options package offers a large set of background patterns. If you want them, set the `default` attribute to `true`

    array(
        'type' => 'image',
        'title' => 'Image',
        'id' => 'simple_image',
        'std' => 'beach',
        'description' => 'Simple description to image panel',
        'multiselect' => true, //if you need more than one choice
        'height' => 25, //if you need to define a special height (60px by default)
        'width' => 140, //if you need to define a special width (150px by default)
        'default' => true,
        'options' => array(
            get_template_directory_uri() . '/img/mountain.png',
            get_template_directory_uri() . '/img/sea.png',
            get_template_directory_uri() . '/img/beach.png'
        )
    )

Adding an `menu`

    array(
        'type' => 'menu',
        'title' => 'Menu',
        'id' => 'id_of_the_menu_expected',
        'description' => 'Simple description to menu panel'
    )

Adding a `page`

    array(
        'type' => 'page',
        'title' => 'Page',
        'id' => 'simple_page',
        'multiselect' => true
    )

Adding a `sidebar`

    array(
        'type' => 'sidebar',
        'title' => 'Sidebar',
        'id' => '1st-homepage-content-block', //define the sidebar id
        'description' => 'Simple description to text panel'
    )

Adding a `social`
NOTA: use the `wanted` attribute to list all the wanted social buttons, from the list below.
`addthis`, `deviantart`, `dribbble`, `facebook`, `flickr`, `forrst`, `friendfeed`, `googleplus`, `lastfm`, `linkedin`, `pinterest`, `rss`, `skype`, `tumblr`, `twitter`, `vimeo`

    array(
        'type' => 'social',
        'title' => 'Social',
        'id' => 'simple_social',
        'std' => array('facebook', 'twitter', 'googleplus', 'addthis'),
        'description' => 'Simple description to social panel',
        'wanted' => array( //Define here all the wanted social buttons in your order
            'facebook',
            'twitter',
            'googleplus',
            'pinterest',
            'addthis'
        )
    )

Adding an `typeahead`

    array(
        'type' => 'typeahead',
        'title' => 'Typeahead',
        'id' => 'simple_typeahead',
        'std' => 'Simple typeahead',
        'description' => 'Simple description to typeahead panel',
        'ajaxurl' => 'name_of_your_ajax_url_to_parse' //define the ajax url to parse
    )


9) That's all folkes!
---------------------

Here is the latest step: check quickly your new panel options.

+ Go to your `your_wp_website/wp-admin`
+ Log in to your admin panel
+ See that you have a new Link in your admin sidebar

That's all to begin working on Tea Theme Options


10) Authors
----------

**Take a Tea**

+ http://twitter.com/takeatea
+ http://github.com/takeatea

**Achraf Chouk**

+ http://twitter.com/crewstyle
+ http://github.com/crewstyle


11) Copyright and license
------------------------

Copyright 2012 [Take a tea](http://takeatea.com "Take a tea")
Influsé par Take a tea ;)

