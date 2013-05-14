Tea Theme Options
=================

The Tea Theme Options (or **Tea TO**) allows you to easily add professional looking theme options panels to your WordPress theme. **Tea TO** uses the [Transient Wordpress API](http://codex.wordpress.org/Transients_API) and puts all data in cache.  
This document contains information on how to download, install, and start using the **Tea TO** Wordpress project.  
NOTA: **Tea TO** is built for [Wordpress](http://wordpress.org "CMS Wordpress") v3.x and uses the Wordpress built-in pages.


1) Installing the theme roller
------------------------------

**To get started, checkout** https://github.com/takeatea/tea_to_wp into the `wp-content/your_template/__YOUR_TEA_TO_CUSTOM_FOLDER__/`

    git clone https://github.com/takeatea/tea_to_wp __YOUR_TEA_TO_CUSTOM_FOLDER__

Check your new `__YOUR_TEA_TO_CUSTOM_FOLDER__` folder is created in your template directory.  
Include the `tea-theme-options.php` file in your `functions.php`

    include('__YOUR_TEA_TO_CUSTOM_FOLDER__/tea-theme-options.php');


2) Create a new Tea_Theme_Options object and set details
--------------------------------------------------------

Before starting, in your `functions.php` instanciate a new object with an uniq identifier:

    $tea = new Tea_Theme_Options('your_identifier');

And set details:

    $tea->__setDuration(86400); //Will stock transient for 86400sec, eq. to 24h
    $tea->__setDirectory('__YOUR_TEA_TO_CUSTOM_FOLDER__'); //Will define the current Tea TO directory.

NOTA: by default, the Tea TO will consider the `tea_theme_options` folder.


3) Let's roll!
--------------

Create your new first page with global details (as capability, icon, bigicon and description if you want):

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

Add all used fields in the last created page (to get definition fields, see below the [Adding fields](#5-adding-fields) section):

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

Add all used fields in the last created subpage (to get definition fields, see below the [Adding fields](#5-adding-fields) section):

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

The last step is to build menus and get your pages available in the admin menu.

    $tea->buildMenus();


5) Adding fields
----------------

Adding fields is quite simple. All you have to do is to create an array and define all inputs nedded in your Wordpress template.  
NOTA: the **Tea TO** uses [Transient Wordpress API](http://codex.wordpress.org/Transients_API) to stock options.

All available types are:

**Display inputs**:
+ Br, display a simple breakline with clear css class
+ Heading, display a simple title
+ Hr, equivalent to the br input, but display an horizontal line

**Normal inputs**:
+ Checkbox
+ Hidden
+ Multiselect
+ Password
+ Radio
+ Select
+ Text (or Email or Search or Url)
+ Textarea
+ Upload (for images only) with the [Wordpress Media Manager](http://codex.wordpress.org/Version_3.5#Highlights).

**Special inputs**:
+ Category, offers a WP category listing with multiselect option if needed
+ Color, offers an input text with colorpicker
+ Font, offers a list of font choices with image labels (uniq choice)
+ Group, offers the possibility to add multiple inputs
+ Image, offers a list of choices with image labels (uniq choice)
+ Menu, display menus as information (no action required)
+ Number (or Range), display new HTML5 inputs
+ Page, display pages as information (no action required)
+ Sidebar, display widgets as information (no action required)
+ Social, display a list of wanted social buttons with multichoice

**Next inputs**: __IN PROGRESS__
+ Button, offers a button with custom interaction
+ Date, offers an input text with calendar interaction
+ Post, offers a WP post listing with multiselect option if needed
+ Tag, offers a WP tag listing with multiselect option if needed
+ Typeahead, offers an input text with ajax call
+ WYSIWYG, offers a Rich Text Editor


6) Display inputs
-----------------

Adding a `br`

    array(
        'type' => 'br'
    )

Adding a `heading`

    array(
        'type' => 'heading',
        'title' => 'Heading'
    )

Adding a `hr`

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

Adding a `multiselect`

    array(
        'type' => 'multiselect',
        'title' => 'Multiselect',
        'id' => 'simple_multiselect',
        'std' => array('mountain', 'beach'),
        'description' => 'Simple description to multiselect panel',
        'options' => array(
            'mountain' => 'Mountain',
            'sea' => 'Sea',
            'beach' => 'Beach'
        )
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

Adding a `select`

    array(
        'type' => 'select',
        'title' => 'Select',
        'id' => 'simple_select',
        'std' => 'sea',
        'description' => 'Simple description to select panel',
        'options' => array(
            'mountain' => 'Mountain',
            'sea' => 'Sea',
            'beach' => 'Beach'
        )
    )

Adding a `text`  
NOTA: you can define the maxlength optional attribute. You can use `email`, `search` or `url` type to use the new HTML5 inputs.

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
NOTA: the upload input uses the [Wordpress Media Manager](http://codex.wordpress.org/Version_3.5#Highlights).

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
NOTA: the category input has a special method which detects if the ID has the `__children` term to register in transient its children categories.

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
NOTA: the **Tea TO** package offers a large set of fonts. If you want them, set the `default` attribute to `true`.

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
NOTA: groups are able to display to 4 input columns per line. You can break a line by adding more than 4 inputs or with a `br`, `heading` or `hr` input.

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
NOTA: the **Tea TO** package offers a large set of background patterns. If you want them, set the `default` attribute to `true`.

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

Adding a `menu`

    array(
        'type' => 'menu',
        'title' => 'Menu',
        'id' => 'id_of_the_menu_expected',
        'description' => 'Simple description to menu panel'
    )

Adding a `number`  
NOTA: you can use `range` type to use the new HTML5 inputs

    array(
        'type' => 'number',
        'title' => 'Number',
        'id' => 'simple_number',
        'std' => 'Simple number',
        'min' => 12, //'1' by default
        'max' => 120, //'50' by default
        'step' => 10, //'1' by default
        'description' => 'Simple description to number panel'
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
`addthis`, `deviantart`, `dribbble`, `facebook`, `flickr`, `forrst`, `friendfeed`, `googleplus`, `instagram`, `lastfm`, `linkedin`, `pinterest`, `rss`, `skype`, `tumblr`, `twitter`, `vimeo`

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
            'instagram',
            'pinterest',
            'addthis'
        )
    )


9) Special inputs
-----------------

Adding an `date` __IN PROGRESS__

    array(
        'type' => 'date',
        'title' => 'Date',
        'id' => 'simple_date',
        'std' => 'Simple date',
        'description' => 'Simple description to date panel'
    )

Adding an `post` __IN PROGRESS__

    array(
        'type' => 'post',
        'title' => 'Post',
        'id' => 'simple_post',
        'std' => 'Simple post',
        'description' => 'Simple description to post panel'
    )

Adding an `tag` __IN PROGRESS__

    array(
        'type' => 'tag',
        'title' => 'Tag',
        'id' => 'simple_tag',
        'std' => 'Simple tag',
        'description' => 'Simple description to tag panel'
    )

Adding an `typeahead` __IN PROGRESS__

    array(
        'type' => 'typeahead',
        'title' => 'Typeahead',
        'id' => 'simple_typeahead',
        'std' => 'Simple typeahead',
        'description' => 'Simple description to typeahead panel',
        'ajaxurl' => 'name_of_your_ajax_url_to_parse' //define the ajax url to parse
    )

Adding an `wysiwyg` __IN PROGRESS__

    array(
        'type' => 'wysiwyg',
        'title' => 'WYSIWYG',
        'id' => 'simple_wysiwyg',
        'std' => 'Simple wysiwyg',
        'description' => 'Simple description to wysiwyg panel'
    )


10) Get data from Transient
--------------------------

To get your data back in your theme, you have to know the ID of what you want to retrieve.  
Don't forget that the **Tea TO** uses the Transient Wordpress API, and get back data is quite simple.  
In this example, we will display the `simple_text` data on the screen:

    //`simple_text` is the ID of our text input, defined above

    //get the data from the cache via Transient API
    $mytext = get_transient('simple_text');

    //checks if the `simple_text` is in cache. And if not...
    if (false === $mytext)
    {
        //get data from DB
        $mytext_from_option = get_option('simple_text');

        //check if the data is in the DB
        $mytext = false === $mytext_from_option ? 'default text value' : $mytext_from_option;

        //set the data in the cache via Transient API with name - value - time in cache
        set_transient('simple_text', $mytext, 86400);
    }

    //display the data
    echo $mytext;

You can do more better. Try to test your value before any manipulation.


11) That's all folkes!
---------------------

Here is the latest step: check quickly your new panel options.

+ Go to your `your_wp_website/wp-admin`
+ Log in to your admin panel
+ **See that you have a new Link in your admin sidebar**

That's all to begin working on **Tea TO**


12) Authors
----------

**Take a Tea**

+ http://twitter.com/takeatea
+ http://github.com/takeatea

**Achraf Chouk**

+ http://twitter.com/crewstyle
+ http://github.com/crewstyle


13) Copyright and license
------------------------

Copyright 2013 [Take a tea](http://takeatea.com "Take a tea")  
Infusé par Take a tea ;)

