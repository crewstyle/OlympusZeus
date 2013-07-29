<?php
//Build data
$titles = array(
    'title' => __('Documentation'),
    'name' => __('Documentation'),
    'slug' => '_documentation',
    'submit' => false
);
$details = array(
    array(
        'type' => 'heading',
        'title' => __('Simple, easy to use and fully integrated Theme Options for Wordpress.')
    ),
    array(
        'type' => 'p',
        'content' => __('The <a href="http://takeatea.github.com/tea_to_wp/" title="Tea Theme Options" class="openit">Tea Theme Options</a> (or <b>Tea TO</b>) allows you to easily add professional looking theme options panels to your WordPress theme.<br/>The <b>Tea TO</b> is built for <a href="http://wordpress.org" target="_blank">Wordpress</a> v3.x and uses the Wordpress built-in pages.')
    ),
    array(
        'type' => 'list',
        'contents' => array(
            __('<b>Option API</b> - A simple and standardized way of storing data in the database.'),
            __('<b>Transient API</b> - Very similar to the Options API but with the added feature of an expiration time, which simplifies the process of using the wp_options database table to temporarily store cached information.'),
            __('<b>Wordpress Media Manager</b> - Beautiful interface: A streamlined, all-new experience where you can create galleries faster with drag-and-drop reordering, inline caption editing, and simplified controls.'),
            __('<b>Full of Options</b> - 3 kind of options used to display information, store fields values or get data from your Wordpress database. The options are made to build your Wordpress pages easily.'),
            __('<b>Easier for administrators</b> - The interface is thought to be the most userfriendly. The Tea TO core adds some extra interface customisations to make your life easier.'),
            __('<b>Easier for developers</b> - Create a new admin panel easily with only 2 lines. The Tea TO core is made to allow non-developer profiles to easily create the settings they need to customise their templates.')
        )
    ),
    array(
        'type' => 'heading',
        'title' => __('Theme Options Field Types.')
    ),
    array(
        'type' => 'features',
        'title' => __('Display fields.'),
        'contents' => array(
            array(
                'title' => __('Breakline or Horizontal rule'),
                'content' => __('Can be usefull.'),
                'code' => 'array(
    "type" => "br", //replace by "hr"
);'
            ),
            array(
                'title' => __('Heading'),
                'content' => __('Display a simple title.'),
                'code' => 'array(
    "type" => "heading",
    "title" => "My heading title"
);'
            ),
            array(
                'title' => __('Paragraph'),
                'content' => __('A simple text content.'),
                'code' => 'array(
    "type" => "p",
    "content" => "My long text content as Lorem Ipsum"
);'
            ),
            array(
                'title' => __('List items'),
                'content' => __('Show items in an unordered list.'),
                'code' => 'array(
    "type" => "list",
    "contents" => array(
        "My first content",
        "My second content",
        "My third content",
        "Simply..."
    )
);'
            ),
            array(
                'title' => __('Features'),
                'content' => __('<b>Special field</b> used only to build this documentation page (but you can use it as well).'),
                'code' => 'array(
    "type" => "features",
    "title" => "My features title",
    "contents" => array(
        array(
            "title" => "My 1st feature title",
            "content" => "My 1st feature long content",
            "code" => "My 1st feature HTML code to display in popin after clicking on a button"
        )
        //You can repeat this array as much as you want
    )
);'
            ),
            array(
                'title' => __('Group'),
                'content' => __('Usefull to group some settings and be focused on grouped values.'),
                'code' => 'array(
    "type" => "group",
    "title" => "My group title",
    "contents" => array(
        //Put here all your needed fields like this one...
        array(
            "type" => "br"
        )
    )
);'
            )
        )
    ),
    array(
        'type' => 'features',
        'title' => __('Common fields.'),
        'contents' => array(
            array(
                'title' => __('Basic Text'),
                'content' => __('The most basic of form fields. Basic, but important.'),
                'code' => 'array(
    "type" => "text",
    "title" => "My text title",
    "id" => "my_text_field_id",
    "std" => "My default value",
    "placeholder" => "My usefull placeholder",
    "description" => "My field description"
);'
            ),
            array(
                'title' => __('Email, number and more'),
                'content' => __('The most basic of form fields extended. You can choose between email, password, number, range, search and url.'),
                'code' => 'array(
    "type" => "text",
    "title" => "My text title",
    "id" => "my_number_field_id",
    "std" => "My default value",
    "placeholder" => "My usefull placeholder",
    "description" => "My field description",
    "options" => array(
        "type" => "number",
        "min" => 10,
        "max" => 100,
        "step" => 5
    )
);'
            ),
            array(
                'title' => __('Hidden field'),
                'content' => __('A hidden field, if you need to store a special data.'),
                'code' => 'array(
    "type" => "hidden",
    "id" => "my_hidden_field_id",
    "std" => "My default value"
);'
            ),
            array(
                'title' => __('Textarea'),
                'content' => __('Again basic, but essencial.'),
                'code' => 'array(
    "type" => "textarea",
    "title" => "My textarea title",
    "id" => "my_textarea_field_id",
    "rows" => 10
);'
            ),
            array(
                'title' => __('Checkbox'),
                'content' => __('No need to introduce it...'),
                'code' => 'array(
    "type" => "checkbox",
    "title" => "My choices title",
    "id" => "my_checkbox_field_id",
    "std" => array("3", "5"),
    "options" => array(
        3 => "Three",
        4 => "Four,
        5 => "Five"
    )
);'
            ),
            array(
                'title' => __('Radio'),
                'content' => __('Its brother (or sister, as you want).'),
                'code' => 'array(
    "type" => "radio",
    "title" => "My choice title",
    "id" => "my_radio_field_id",
    "std" => "3",
    "options" => array(
        3 => "Three",
        4 => "Four,
        5 => "Five"
    )
);'
            ),
            array(
                'title' => __('Select'),
                'content' => __('Provide a list of possible option values.'),
                'code' => 'array(
    "type" => "select",
    "title" => "My choice title",
    "id" => "my_select_field_id",
    "std" => "3",
    "options" => array(
        3 => "Three",
        4 => "Four,
        5 => "Five"
    )
);'
            ),
            array(
                'title' => __('Multiselect'),
                'content' => __('The same list as previous one but with multichoices.'),
                'code' => 'array(
    "type" => "select",
    "title" => "My choices title",
    "id" => "my_multiselect_field_id",
    "std" => array("3", "5"),
    "multiple" => true,
    "options" => array(
        3 => "Three",
        4 => "Four,
        5 => "Five"
    )
);'
            )
        )
    ),
    array(
        'type' => 'features',
        'title' => __('Special fields.'),
        'contents' => array(
            array(
                'title' => __('Background'),
                'content' => __('Great for managing a complete background layout with options.'),
                'code' => 'array(
    "type" => "background",
    "title" => "My background field title",
    "id" => "my_background_field_id",
    "default" => true,
    "std" => array(
        "image" => "my_background_default_url",
        "color" => "#ffffff",
        "repeat" => "no-repeat",
        "position_x" => "left",
        "position_y" => "top"
    ),
    "description" => "My background field description"
);'
            ),
            array(
                'title' => __('Color'),
                'content' => __('Need some custom colors? Use the Wordpress color picker.'),
                'code' => 'array(
    "type" => "color",
    "title" => "My color field title",
    "id" => "my_color_field_id",
    "std" => "#ffffff",
    "description" => "My color field description"
);'
            ),
            array(
                'title' => __('Google Fonts'),
                'content' => __('Want to use a custom font provided by Google Web Fonts? It\'s easy now.'),
                'code' => 'array(
    "type" => "font",
    "title" => "My font field title",
    "id" => "my_font_field_id",
    "std" => "Lobster",
    "description" => "My font field description",
    "default" => true,
    "options" => array(
        "PT+Sans" => array("PT Sans", "400,700")
    )
);'
            ),
            array(
                'title' => __('Include'),
                'content' => __('Offers the possibility to include a php file.'),
                'code' => 'array(
    "type" => "include",
    "title" => "My include field title",
    "file" => "my_path_file"
);'
            ),
            array(
                'title' => __('RTE'),
                'content' => __('Want a full rich editing experience? Use the Wordpress editor.'),
                'code' => 'array(
    "type" => "rte",
    "title" => "My RTE field title",
    "id" => "my_rte_field_id",
    "description" => "My RTE field description"
);'
            ),
            array(
                'title' => __('Social'),
                'content' => __('Who has never needed social links on his website? You can manage them easily here.'),
                'code' => 'array(
    "type" => "social",
    "title" => "My social field title",
    "id" => "my_social_field_id",
    "description" => "My social field description",
    "default" => array(
        "addthis", "bloglovin", "deviantart", "dribbble", "facebook",
        "flickr", "forrst", "friendfeed", "hellocoton", "googleplus",
        "instagram", "lastfm", "linkedin", "pinterest", "rss",
        "skype", "tumblr", "twitter", "vimeo", "youtube"
    )
);'
            ),
            array(
                'title' => __('Wordpress Upload'),
                'content' => __('Upload images (only for now), great for logo or default thumbnail. It uses the <a href="http://codex.wordpress.org/Version_3.5#Highlights" class="openit">Wordpress Media Manager</a>'),
                'code' => 'array(
    "type" => "upload",
    "title" => "My upload field title",
    "id" => "my_upload_field_id",
    "description" => "My upload field description",
    "library" => "image", //Default value, tells to accept only images
    "multiple" => true //If you need to upload a gallery
);'
            )
        )
    ),
    array(
        'type' => 'features',
        'title' => __('Wordpress fields'),
        'contents' => array(
            array(
                'title' => __('Categories'),
                'content' => __('Display a list of Wordpress categories.'),
                'code' => 'array(
    "type" => "categories",
    "id" => "my_categories_field_id"
);'
            ),
            array(
                'title' => __('Menus'),
                'content' => __('Display a list of Wordpress menus.'),
                'code' => 'array(
    "type" => "menus",
    "id" => "my_menus_field_id"
);'
            ),
            array(
                'title' => __('Pages'),
                'content' => __('Display a list of Wordpress pages.'),
                'code' => 'array(
    "type" => "pages",
    "id" => "my_pages_field_id"
);'
            ),
            array(
                'title' => __('Posts'),
                'content' => __('Display a list of Wrdpress posts.'),
                'code' => 'array(
    "type" => "posts",
    "id" => "my_posts_field_id"
);'
            ),
            array(
                'title' => __('Post Types'),
                'content' => __('Display a list of Wordpress posttypes.'),
                'code' => 'array(
    "type" => "posttypes",
    "id" => "my_posttypes_field_id"
);'
            ),
            array(
                'title' => __('Tags'),
                'content' => __('Display a list of Wordpress tags.'),
                'code' => 'array(
    "type" => "tags",
    "id" => "my_tags_field_id"
);'

            )
        )
    ),
    array(
        'type' => 'features',
        'title' => __('Connection fields'),
        'contents' => array(
            array(
                'title' => 'Instagram',
                'content' => __('Make a bridge between your website and your Instagram profile.'),
                'code' => 'array(
    "type" => "instagram",
    "title" => "Welcome to Instagram",
    "description" => "It\'s your profile. You, you and you."
);'
            )
        )
    )
);