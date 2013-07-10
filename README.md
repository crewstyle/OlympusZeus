# Tea Theme Options


## Simple, easy to use and fully integrated Theme Options for Wordpress.

The [Tea Theme Options](http://takeatea.github.com/tea_to_wp/) (or **Tea TO**) allows you to easily add professional looking theme options panels to your WordPress theme. The **Tea TO** is built for [Wordpress](http://wordpress.org "CMS Wordpress") v3.x and uses the Wordpress built-in pages.

+ [**Options API**](http://codex.wordpress.org/Options_API) - A simple and standardized way of storing data in the database.
+ [**Transients API**](http://codex.wordpress.org/Transients_API) - Very similar to the Options API but with the added feature of an expiration time, which simplifies the process of using the wp_options database table to temporarily store cached information.
+ **Wordpress Media Manager** - Beautiful interface: A streamlined, all-new experience where you can create galleries faster with drag-and-drop reordering, inline caption editing, and simplified controls.
+ **Full of Options** - 3 kinds of options used to display information, store fields values or get data from your Wordpress database. The options are made to build your Wordpress pages easily.
+ **Easier for administrators** - The interface is thought to be the most userfriendly. The Tea TO core adds some extra interface customisations to make your life easier.
+ **Easier for developers** - Create a new admin panel easily with only 2 lines. The Tea TO core is made to allow non-developer profiles to easily create the settings they need to customise their templates.  


## Theme Options Field Types.


### Display fields.

+ **Breakline or Horizontal rule** - Can be usefull.

+ **Heading** - Display a simple title.

+ **List items** - Show items in an unordered list.

+ **Paragraphe** - A simple text content.

+ **Group** - Usefull to group some settings and be focused on grouped values.

+ **Features** - **Special field** used only to build this documentation page (but you can use it as well).


### Commn fields.

+ **Basic Text** - The most basic of form fields. Basic, but important.

+ **Email, number and more** - The most basic of form fields extended. You can choose between email, password, number, range, search and url.

+ **Hidden field** - A hidden field, if you need to store a special data.

+ **Textarea** - Again basic, but essencial.

+ **Checkbox** - No need to introduce it...

+ **Radio** - Its brother (or sister, as you want).

+ **Select** - Provide a list of possible option values.

+ **Multiselect** - The same list as previous one but with multichoices.


### Special fields.

+ **Background** - Great for managing a complete background layout with options.

+ **Color** - Need some custom colors? Use the Wordpress color picker.

+ **Date** - Provide a calendar widget to select a date.

+ **Google Fonts** - Want to use a custom font provided by Google Web Fonts? It's easy now.

+ **Images** - Offers a list of choices with image labels (as radio buttons).

+ **RTE** - Want a full rich editing experience? Use the Wordpress editor.

+ **Social** - Who has never needed social links on his website? You can manage them easily here.

+ **Wordpress Upload** - Upload images (only for now), great for logo or default thumbnail. It uses the [Wordpress Media Manager](http://codex.wordpress.org/Version_3.5#Highlights).


### Wordress fields

+ **Categories** - Display a list of Wordpress categories.

+ **Menus** - Display a list of Wordpress menus.

+ **Pages** - Display a list of Wordpress pages.

+ **Posts** - Display a list of Wrdpress posts.

+ **Post Types** - Display a list of Wordpress posttypes.

+ **Tags** - Display a list of Wordpress tags.


**Summary**
+ [1) Installing the theme roller](#1-installing-the-theme-roller)
+ [2) Create a new Tea_Theme_Options object and set details](#2-create-a-new-tea_theme_options-object-and-set-details)
+ [3) Let's roll!](#3-lets-roll)
+ [4) Building menus](#4-building-menus)
+ [5) Adding fields](#5-adding-fields)
+ [6) Display inputs](#6-display-inputs)
+ [7) Normal inputs](#7-normal-inputs)
+ [8) Special inputs](#8-special-inputs)
+ [9) Next inputs](#9-next-inputs)
+ [10) Example](#10-example)
+ [11) Get data from Transient](#11-get-data-from-transient)
+ [12) Previews](#12-previews)
+ [13) That's all folkes!](#13-thats-all-folkes)
+ [14) Authors](#14-authors)
+ [15) Copyright and license](#15-copyright-and-license)


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

    $tea = new Tea_Theme_Options();

And set details if you want:

    $tea->__setDuration(86400); //Will stock transient for 86400sec, eq. 24h
    $tea->__setDirectory('__YOUR_TEA_TO_CUSTOM_FOLDER__'); //Will define the current Tea TO directory.

NOTA: by default, the **Tea TO** will consider the `tea_theme_options` folder.


3) Let's roll!
--------------

Create your new first page settings (as capability, icon, bigicon and description if you want):

    //Build page
    $tea_titles = array(
        //used in your page
        'title' => 'My first page',
        //used in the admin menu
        'name' => 'First page',
        //a writer can use this page. If you want to give access only to the admin, use 'admin' instead
        'capability' => 'edit_pages',
        //used in your page
        'description' => 'Here is a description of my first page'
    );

Add all used fields in your page (to get definition fields, see below the [Adding fields](#5-adding-fields) section):

    //Add fields
    $tea_configs = array(
        array(
            'type' => 'text', //define the input type
            'title' => 'My first text title', //define the title block
            'id' => 'my_first_text_id', //define the uniq ID
            'std' => 'My default text value', //define the default value
            'description' => 'My first text description' //define the description block
        ),
        array(
            'type' => 'textarea',
            'title' => 'My first textarea field',
            'id' => 'my_first_textarea_id',
            'description' => 'My first textarea description'
        )
    );

Add your created details in your page and unset arrays:

    $tea->addPage($tea_titles, $tea_configs);
    unset($tea_titles, $tea_configs);

Repeat the process as you want/need :)


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
+ Br
+ Hr
+ Heading
+ P
+ List
+ Features
+ Group

**Normal inputs**:
+ Text (or Password or Email or Number or Range or Search or Url)
+ Textarea
+ Hidden
+ Checkbox
+ Radio
+ Select

**Special inputs**:
+ Color
+ Font
+ Image
+ Social
+ Background
+ Wordpress Upload

**Wordpress inputs**:
+ Categories
+ Menus
+ Pages
+ Posts
+ Posttypes
+ Tags

**Next inputs**: __IN PROGRESS__
+ Autocompletion
+ Date
+ Geolocalisation
+ RTE


6) Display inputs
-----------------

Adding a `br`

    array(
        'type' => 'br'
    )

Adding a `hr`

    array(
        'type' => 'hr'
    )

Adding a `heading`

    array(
        'type' => 'heading',
        'title' => 'Take a tea, simply'
    )

Adding a `paragraph`

    array(
        'type' => 'p',
        'content' => 'Hello and welcome to the "Tea Test Academy"'
    )

Adding a `list`

    array(
        'type' => 'list',
        'contents' => array(
            'Just admit it:',
            'this is the best',
            'Wordpress Theme Options Framework :)',
            'Thanks to Take a Tea :D'
        )
    )

Adding a `features`

    array(
        'type' => 'features',
        'title' => 'Here is what I am',
        'contents' => array(
            array(
                'title' => 'Real good gamer',
                'content' => 'Yeah, I am the best at Super Bomberman 2 on SNES :D',
                'code' => 'I am <b>the</b> best as I said ;)' //Use HTML code to describe your feature on popin
            )
            //You can repeat this array as much as you want
        )
    )

Adding a `group`

    array(
        'type' => 'group',
        'title' => 'Everybooooodyyyyy need someboooodddyyyyy',
        'contents' => array(
            //Put here all your needed fields like this one...
            array(
                'type' => 'br'
            )
        )
    )


7) Normal inputs
----------------

Adding a `text`  
NOTA: you can define the maxlength optional attribute.

    array(
        'type' => 'text',
        'title' => 'What do you like?',
        'id' => 'my_text_field_id',
        'std' => 'Penguins, I am sure they\'re gonna dominate the World!',
        'placeholder' => 'McDonald\'s as well',
        'description' => 'Put in here everything you want.',
        'maxlength' => 120
    )

NOTA: you can use `email`, `search` or `url` type to use the new HTML5 inputs.

    array(
        'type' => 'text',
        'title' => 'How much do you like Penguins?',
        'id' => 'my_text_field_id',
        'std' => 100,
        'placeholder' => '50',
        'description' => 'Tell us how much do like Penguins to have a chance to get into our private Penguins community ;)',
        'options' => array(
            'type' => 'number',
            'min' => 10,
            'max' => 100,
            'step' => 1
        )
    )

Adding a `hidden`

    array(
        'type' => 'hidden',
        'id' => 'my_hidden_field_id',
        'std' => 'Haha I will dominate the World!!! MOUAHAHAHAHAHA - Crazy Penguin'
    )

Adding a `textarea`

    array(
        'type' => 'textarea',
        'title' => 'How do Penguins drink their cola?',
        'id' => 'my_textarea_field_id',
        'std' => 'On the rocks.',
        'placeholder' => 'Tell us how?',
        'description' => 'A simple question to know if you will be able to survive to the Penguin domination.'
    )

Adding a `checkbox`

    array(
        'type' => 'checkbox',
        'title' => 'What are your preferred personas?',
        'id' => 'my_checkbox_field_id',
        'std' => array('minions', 'lapinscretins'), //define the default choice(s)
        'description' => '',
        //define the options
        'options' => array(
            'minions' => 'The Minions', //value => label
            'lapinscretins' => 'The Lapins Crétins',
            'marvel' => 'All Marvel Superheroes',
            'franklin' => 'Franklin (everything is possible)',
            'spongebob' => 'Spongebob (nothing to say... Love it)'
        )
    )

Adding a `radio`

    array(
        'type' => 'radio',
        'title' => 'Ok ok... But what is your favorite?',
        'id' => 'my_radio_field_id',
        'std' => 'minions',
        'description' => '- "Bapouet?" - "Na na na, baapouet!" - "AAAAAAAAAA Bapoueeeeettttt!!!!"',
        'options' => array(
            'minions' => 'The Minions',
            'lapinscretins' => 'The Lapins Crétins'
        )
    )

Adding a `select`

    array(
        'type' => 'select',
        'title' => 'Prove it: what do they mean by "Bapouet"?',
        'id' => 'my_select_field_id',
        'std' => '',
        'description' => 'Don\'t cheat: the movie is NOT the solution :)',
        'options' => array(
            'toy' => 'A simple toy',
            'milk' => 'Just milk',
            'unicorn-toy' => 'A unicorn toy... Very stupid :p',
            'tails' => 'A red fox with a tiny cute fire tail with his blue faster hedgedog friend'
        )
    )

Adding a `multiselect`

    array(
        'type' => 'select',
        'title' => 'Select the Minions that you may know',
        'id' => 'my_multiselect_field_id',
        'std' => '',
        'description' => 'Pay attention to this question ;)',
        'options' => array(
            'henry' => 'Henry',
            'jacques' => 'Jacques',
            'kevin' => 'Kevin',
            'tom' => 'Tom'
        )
    )


8) Special inputs
-----------------

Adding a `color`

    array(
        'type' => 'color',
        'title' => 'What is your favorite Coke?',
        'id' => 'my_color_field_id',
        'std' => '#000000',
        'description' => 'Do not choose the Coke Zero, right? ;)'
    )

Adding a `font`  
NOTA: the **Tea TO** package offers a large set of fonts. If you want them, set the `default` attribute to `true`.

    array(
        'type' => 'font',
        'title' => 'Choose your style',
        'id' => 'my_font_field_id',
        'std' => 'my_gorgeous_font',
        'description' => 'Tell us how to scribe :D',
        'default' => true,
        'options' => array(
            'my_gorgeous_font' => 'my_gorgeous_font_url',
            'an_other_font' => 'an_other_font_url'
        )
    )

Adding an `image`

    array(
        'type' => 'image',
        'title' => 'Choose your avatar',
        'id' => 'my_image_field_id',
        'std' => 'beach',
        'description' => 'A uniq avatar to define yourself',
        'multiselect' => true, //if you need more than one choice
        'height' => 50, //if you need to define a special height (60px by default)
        'width' => 50, //if you need to define a special width (150px by default)
        'options' => array(
            'my_first_image_url',
            'my_second_image_url',
            'my_third_image_url'
        )
    )

Adding a `social`  
NOTA: use the `default` attribute to list all the included social buttons, from the list below  
`addthis`, `bloglovin`, `deviantart`, `dribbble`, `facebook`, `flickr`, `forrst`, `friendfeed`, `hellocoton`, `googleplus`, `instagram`, `lastfm`, `linkedin`, `pinterest`, `rss`, `skype`, `tumblr`, `twitter`, `vimeo`, `youtube`

    array(
        'type' => 'social',
        'title' => 'Big Brother is watching you...',
        'id' => 'my_social_field_id',
        'std' => array('facebook', 'twitter', 'googleplus', 'addthis'),
        'description' => '...Or not!',
        'default' => array(
            'facebook',
            'twitter',
            'googleplus',
            'instagram',
            'pinterest',
            'addthis'
        )
    )

Adding a `background`  
NOTA: the background input uses the [Wordpress Media Manager](http://codex.wordpress.org/Version_3.5#Highlights). The **Tea TO** package offers a large set of image patterns. If you want them, set the `default` attribute to `true`.

    array(
        'type' => 'background',
        'title' => 'A new paint :D',
        'id' => 'my_background_field_id',
        'std' => array(
            'image' => 'my_background_default_url',
            'color' => '#ffffff',
            'repeat' => 'no-repeat',
            'position_x' => ',
            'position_x_pos' => 'left',
            'position_y' => ',
            'position_y_pos' => 'top'
        ),
        'description' => 'It\'s tricky :)',
        'default' => true
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


9) Wordpress inputs
-------------------

Adding a `categories`

    array(
        'type' => 'categories',
        'title' => 'My Wordpress categories',
        'id' => 'my_categories_field_id',
        'multiselect' => true //Optional: to "false" by default
    )

Adding a `menus`

    array(
        'type' => 'menus',
        'title' => 'My Wordpress menus',
        'id' => 'my_menus_field_id',
        'multiselect' => true //Optional: to "false" by default
    )

Adding a `pages`

    array(
        'type' => 'pages',
        'title' => 'My Wordpress pages',
        'id' => 'my_pages_field_id',
        'multiselect' => true //Optional: to "false" by default
    )

Adding a `posts`

    array(
        'type' => 'posts',
        'title' => 'My Wordpress posts',
        'id' => 'my_posts_field_id',
        'multiselect' => true //Optional: to "false" by default
    )

Adding a `post types`

    array(
        'type' => 'posttypes',
        'title' => 'My Wordpress post types',
        'id' => 'my_posttypes_field_id',
        'multiselect' => true //Optional: to "false" by default
    )

Adding a `tags`

    array(
        'type' => 'tags',
        'title' => 'My Wordpress tags',
        'id' => 'my_tags_field_id',
        'multiselect' => true //Optional: to "false" by default
    )


10) Next inputs
---------------

Adding a `address` __IN PROGRESS__

    array(
        'type' => 'address',
        'title' => 'What is your postal address?',
        'id' => 'my_address_field_id',
        'std' => 'Arica, Chile',
        'description' => '<a href="http://goo.gl/maps/fwqS8" class="openit">Always Coca Cola :)</a>',
        'ajaxurl' => 'name_of_your_ajax_url_to_parse' //define the ajax url to parse
    )

Adding a `autocomplete` __IN PROGRESS__

    array(
        'type' => 'autocomplete',
        'title' => 'Autocomplete...',
        'id' => 'my_autocompletion_field_id',
        'std' => '...Because you are assisted :D',
        'description' => ''
    )

Adding a `date` __IN PROGRESS__

    array(
        'type' => 'date',
        'title' => 'What\'s the day today?',
        'id' => 'my_date_field_id',
        'std' => '06-03-2013',
        'description' => 'Choose your date simply'
    )

Adding a `wysiwyg` __IN PROGRESS__

    array(
        'type' => 'wysiwyg',
        'title' => 'WYSIWYG',
        'id' => 'simple_wysiwyg',
        'std' => 'Simple wysiwyg',
        'description' => 'Simple description to wysiwyg panel'
    )


10) Example
-----------

Here is a working example to define in your functions.php theme page.

    define('BLOG_NAME', 'My blog name');
    define('TEMPLATE_DICTIONNARY', 'mytemplate');
    define('TEMPLATE_DIR_URI', get_template_directory_uri());

    //Instanciate a new Tea_Theme_Options
    $tea = new Tea_Theme_Options('tea_options');

    //Build page
    $tea_configs = array(
        'title' => BLOG_NAME,
        'name' => __('Tea T.O.', TEMPLATE_DICTIONNARY),
        'capability' => 'edit_pages',
        'icon' => TEMPLATE_DIR_URI . '/img/admin/settings_16.png',
        'bigicon' => TEMPLATE_DIR_URI . '/img/admin/settings_32.png',
        'description' => ''
    );
    $tea->addPage($tea_configs);

    //Add fields
    $tea_configs = array(
        array(
            'type' => 'text',
            'title' => __('Your Google+ profile link.', TEMPLATE_DICTIONNARY),
            'id' => 'global_google_profile_link',
            'placeholder' => __('https://plus.google.com/...', TEMPLATE_DICTIONNARY),
            'description' => __('Paste your Google+ account profile link here to appear as the website publisher', TEMPLATE_DICTIONNARY)
        ),
        array(
            'type' => 'font',
            'title' => __('Main font.', TEMPLATE_DICTIONNARY),
            'id' => 'global_main_font',
            'std' => DEFAULT_FONT,
            'description' => __('Set the main website font for titles.', TEMPLATE_DICTIONNARY),
            'default' => true,
            'options' => array(
                'Montserrat' => TEMPLATE_DIR_URI . '/img/montserrat.png'
            )
        )
    );
    $tea->addFields($tea_configs);

    //Build subpage
    $tea_configs = array(
        'title' => __('Contents', TEMPLATE_DICTIONNARY),
        'name' => __('Contents', TEMPLATE_DICTIONNARY),
        'slug' => '_contents'
    );
    $tea->addSubpage($tea_configs);

    //Add fields
    $tea_configs = array(
        array(
            'type' => 'text',
            'title' => __('Project list title.', TEMPLATE_DICTIONNARY),
            'id' => 'contents_title'
        ),
        array(
            'type' => 'textarea',
            'title' => __('Project list intro.', TEMPLATE_DICTIONNARY),
            'id' => 'contents_intro'
        ),
        array(
            'type' => 'upload',
            'title' => __('Default project image.', TEMPLATE_DICTIONNARY),
            'id' => 'contents_default__image',
            'description' => __('The image must be <b>340 * 280 pixels</b> per default.', TEMPLATE_DICTIONNARY)
        )
    );
    $tea->addFields($tea_configs);

    //Build menus
    $tea->buildMenus();

    //Unset array
    unset($tea_configs);

Here is how to get data in your template (i.e. in yout header.php)

    //Get data from DB
    $intro = get_option('contents_intro');

    //Check transitivity
    $intro = false === $intro ? 'No content found...' : $intro;

    //Display it
    echo $intro;


11) Get data from Transient
---------------------------

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


12) Previews
------------

Main view with Google font special field
![Main view with Google font special field](http://takeatea.com/teato/teato-a.png)

Wordpress color field
![Wordpress color field](http://takeatea.com/teato/teato-b.png)
![Wordpress color field opened](http://takeatea.com/teato/teato-f.png)

Group field with text inputs
![Group field with text inputs](http://takeatea.com/teato/teato-c.png)

Checkboxes field with un/select all
![Checkboxes field with un/select all](http://takeatea.com/teato/teato-d.png)

Social special field
![Social special field](http://takeatea.com/teato/teato-e.png)


13) That's all folkes!
----------------------

Here is the latest step: check quickly your new panel options.

+ Go to your `your_wp_website/wp-admin`
+ Log in to your admin panel
+ **See that you have a new Link in your admin sidebar**

That's all to begin working with **Tea TO**


14) Authors
-----------

**Take a Tea**

+ http://takeatea.com
+ http://twitter.com/takeatea
+ http://github.com/takeatea

**Achraf Chouk**

+ http://fr.linkedin.com/in/achrafchouk/
+ http://twitter.com/crewstyle
+ http://github.com/crewstyle


15) Copyright and license
-------------------------

Copyright 2013 [Take a tea](http://takeatea.com "Take a tea")  
Infusé par Take a tea ;)
