<?php
/**
 * Tea TO backend functions and definitions
 * 
 * @package TakeaTea
 * @subpackage Tea Fields
 * @since Tea Theme Options 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Fields
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
abstract class Tea_Fields
{
    //Define protected/private vars
    private $adminmessage;
    private $includes = array();
    private $wpcontents = array();

    /**
     * Constructor.
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __construct(){}


    //--------------------------------------------------------------------------//

    /**
     * ABSTRACT FUNCTIONS
     **/

    abstract protected function templatePages($content, $post = array());

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $post Contains all data
     * @param array $args Contains all data
     *
     * @since Tea Theme Options 1.3.0
     */
    static function templateCustomPostTypes($post, $args)
    {
        //If autosave...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        {
            return $post_id;
        }

        //Get values
        $type = $args['args']['contents']['type'];
        $class = 'Tea_Fields_' . ucfirst($type);
        require_once(TTO_PATH . 'classes/fields/' . $type . '/class-tea-fields-' . $type . '.php');

        //Make the magic
        $field = new $class();
        $field->templatePages($args, $post);
    }


    //--------------------------------------------------------------------------//

    /**
     * FUNCTIONS
     **/

    /**
     * Checks if an ID is defined
     *
     * @param array $content Contains all field data
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function checkId($content)
    {
        //Check if an id is defined at least
        if (!isset($content['id']))
        {
            $this->setAdminMessage(sprintf(__('Something went wrong in your parameters definition: no id is defined for your <b>%s</b> field!', TTO_I18N), $content['type']));
            return;
        }
    }

    /**
     * Return default values.
     *
     * @param string $return Define what to return
     * @param array $wanted Usefull in social case to return only what the user wants
     * @return array $defaults All defaults data provided by the Tea TO
     *
     * @since Tea Theme Options 1.3.0
     */
    static function getDefaults($return = 'images', $wanted = array())
    {
        $defaults = array();

        //Return defaults background values
        if ('background-details' == $return)
        {
            $defaults = array(
                'position'  => array(
                    'x'     => array(
                        'left'      => __('Left', TTO_I18N),
                        'center'    => __('Center', TTO_I18N),
                        'right'     => __('Right', TTO_I18N)
                    ),
                    'y'     => array(
                        'top'       => __('Top', TTO_I18N),
                        'middle'    => __('Middle', TTO_I18N),
                        'bottom'    => __('Bottom', TTO_I18N)
                    )
                ),
                'repeat'    => array(
                    'no-repeat'     => __('Background is displayed only once.', TTO_I18N),
                    'repeat-x'      => __('Background is repeated horizontally only.', TTO_I18N),
                    'repeat-y'      => __('Background is repeated vertically only.', TTO_I18N),
                    'repeat'        => __('Background is repeated.', TTO_I18N)
                )
            );
        }
        //Return defauls TTO fields for CPTs
        else if ('fieldscpts' == $return)
        {
            $defaults = array(
                'checkbox', 'radio', 'select', 'multiselect',
                'text', 'textarea', 'background', 'color',
                'font', 'rte', 'social', 'upload', 'wordpress'
            );
        }
        //Return defauls TTO fields
        else if ('fields' == $return)
        {
            $defaults = array(
                'br', 'heading', 'hr', 'list', 'p', 'checkbox',
                'hidden', 'radio', 'select', 'multiselect',
                'text', 'textarea', 'background', 'color', 'font',
                'include', 'rte', 'social', 'upload', 'wordpress'
            );
        }
        //Return defauls FLickr API keys
        else if ('flickr' == $return)
        {
            $defaults = array(
                'api_key'       => '202431176865b4c5f725087d26bd78af',
                'api_secret'    => '2efaf89685c295ea'
            );
        }
        //Return defaults font
        else if ('fonts' == $return)
        {
            $defaults = array(
                array('sansserif', 'Sans serif', ''),
                array('Arvo', 'Arvo', '400,700'),
                array('Bree+Serif', 'Bree Serif', '400'),
                array('Cabin', 'Cabin', '400,500,600,700'),
                array('Cantarell', 'Cantarell', '400,700'),
                array('Copse', 'Copse', '400'),
                array('Cuprum', 'Cuprum', '400,700'),
                array('Droid+Sans', 'Droid Sans', '400,700'),
                array('Lobster+Two', 'Lobster Two', '400,700'),
                array('Open+Sans', 'Open Sans', '300,400,600,700,800'),
                array('Oswald', 'Oswald', '300,400,700'),
                array('Pacifico', 'Pacifico', '400'),
                array('Patua+One', 'Patua One', '400'),
                array('PT+Sans', 'PT Sans', '400,700'),
                array('Puritan', 'Puritan', '400,700'),
                array('Qwigley', 'Qwigley', '400'),
                array('Titillium+Web', 'Titillium Web', '200,300,400,600,700,900'),
                array('Vollkorn', 'Vollkorn', '400,700'),
                array('Yanone+Kaffeesatz', 'Yanone Kaffeesatz', '200,300,400,700')
            );
        }
        //Return defauls background images
        else if ('images' == $return)
        {
            $defaults = array(
                'none.png'           => __('No background', TTO_I18N),
                'bright_squares.png' => __('Bright squares', TTO_I18N),
                'circles.png'        => __('Circles', TTO_I18N),
                'crosses.png'        => __('Crosses', TTO_I18N),
                'crosslines.png'     => __('Crosslines', TTO_I18N),
                'cubes.png'          => __('Cubes', TTO_I18N),
                'double_lined.png'   => __('Double lined', TTO_I18N),
                'honeycomb.png'      => __('Honeycomb', TTO_I18N),
                'linen.png'          => __('Linen', TTO_I18N),
                'project_paper.png'  => __('Project paper', TTO_I18N),
                'texture.png'        => __('Texture', TTO_I18N),
                'vertical_lines.png' => __('Vertical lines', TTO_I18N),
                'vichy.png'          => __('Vichy', TTO_I18N),
                'wavecut.png'        => __('Wavecut', TTO_I18N),
                'custom.png'         => 'CUSTOM'
            );
        }
        //Return defauls TTO types and only networks
        else if ('networks' == $return)
        {
            $defaults = array(
                'flickr'    => __('FlickR', TTO_I18N),
                'instagram' => __('Instagram', TTO_I18N),
                'twitter'   => __('Twitter', TTO_I18N)
            );
        }
        //Return defauls TTO types and only networks
        else if ('networks_callback' == $return)
        {
            $defaults = array(
                'instagram_token'   => 'instagram',
                'twitter_token'     => 'twitter'
            );
        }
        //Return defaults social button
        else if ('social' == $return)
        {
            $socials = array(
                'addthis'       => array(),
                'bloglovin'     => array(__('Follow me on Bloglovin', TTO_I18N), __('http://www.bloglovin.com/blog/__userid__/__username__', TTO_I18N)),
                'deviantart'    => array(__('Follow me on Deviantart', TTO_I18N), __('http://__username__.deviantart.com/', TTO_I18N)),
                'dribbble'      => array(__('Follow me on Dribbble', TTO_I18N), __('http://dribbble.com/__username__', TTO_I18N)),
                'facebook'      => array(__('Follow me on Facebook', TTO_I18N), __('http://www.facebook.com/__username__', TTO_I18N)),
                'flickr'        => array(__('Follow me on Flickr', TTO_I18N), __('http://www.flickr.com/photos/__username__', TTO_I18N)),
                'forrst'        => array(__('Follow me on Forrst', TTO_I18N), __('http://forrst.com/people/__username__', TTO_I18N)),
                'friendfeed'    => array(__('Follow me on FriendFeed', TTO_I18N), __('http://friendfeed.com/__username__', TTO_I18N)),
                'hellocoton'    => array(__('Follow me on Hellocoton', TTO_I18N), __('http://www.hellocoton.fr/mapage/__username__', TTO_I18N)),
                'googleplus'    => array(__('Follow me on Google+', TTO_I18N), __('http://plus.google.com/__username__', TTO_I18N)),
                'instagram'     => array(__('Follow me on Instagram', TTO_I18N), __('http://www.instagram.com/__username__', TTO_I18N)),
                'lastfm'        => array(__('Follow me on LastFM', TTO_I18N), __('http://www.lastfm.fr/user/__username__', TTO_I18N)),
                'linkedin'      => array(__('Follow me on LinkedIn', TTO_I18N), __('http://fr.linkedin.com/in/__username__', TTO_I18N)),
                'pinterest'     => array(__('Follow me on Pinterest', TTO_I18N), __('http://pinterest.com/__username__', TTO_I18N)),
                'rss'           => array(__('Subscribe to my RSS feed', TTO_I18N)),
                'skype'         => array(__('Connect us on Skype', TTO_I18N)),
                'tumblr'        => array(__('Follow me on Tumblr', TTO_I18N), __('http://', TTO_I18N)),
                'twitter'       => array(__('Follow me on Twitter', TTO_I18N), __('http://www.twitter.com/__username__', TTO_I18N)),
                'vimeo'         => array(__('Follow me on Vimeo', TTO_I18N), __('http://www.vimeo.com/__username__', TTO_I18N)),
                'youtube'       => array(__('Follow me on Youtube', TTO_I18N), __('http://www.youtube.com/user/__username__', TTO_I18N))
            );

            $defaults = array();

            //Return only wanted
            if (isset($wanted) && !empty($wanted))
            {
                foreach ($wanted as $want)
                {
                    if (array_key_exists($want, $socials))
                    {
                        $defaults[$want] = $socials[$want];
                    }
                }
            }
            else
            {
                $defaults = $socials;
            }
        }
        //Return defauls Twitter API keys
        else if ('twitter' == $return)
        {
            $defaults = array(
                'consumer_key'      => 'T6K5yb4oGrS5UTZxsvDdhw',
                'consumer_secret'   => 'gpamCLVGgNZGN3jprq40A4JD5KzQ2PLqFIu5lUQyw'
            );
        }
        //Return defaults text types
        else if ('texts' == $return)
        {
            $defaults = array(
                'text' => __('Text', TTO_I18N),
                'email' => __('Email', TTO_I18N),
                'number' => __('Number', TTO_I18N),
                'range' => __('Range', TTO_I18N),
                'password' => __('Password', TTO_I18N),
                'search' => __('Search', TTO_I18N),
                'url' => __('URL', TTO_I18N)
            );
        }
        //Return defauls TTO types
        else if ('types' == $return)
        {
            $defaults = array(
                __('Display fields', TTO_I18N) => array(
                    'br' => __('Breakline', TTO_I18N),
                    'heading' => __('Heading', TTO_I18N),
                    'hr' => __('Horizontal rule', TTO_I18N),
                    'list' => __('List items', TTO_I18N),
                    'p' => __('Paragraph', TTO_I18N)
                ),
                __('Common fields', TTO_I18N) => array(
                    'text' => __('Basic text, password, email, number and more', TTO_I18N),
                    'checkbox' => __('Checkbox', TTO_I18N),
                    'hidden' => __('Hidden', TTO_I18N),
                    'multiselect' => __('Multiselect', TTO_I18N),
                    'radio' => __('Radio', TTO_I18N),
                    'select' => __('Select', TTO_I18N),
                    'textarea' => __('Textarea', TTO_I18N)
                ),
                __('Special fields', TTO_I18N) => array(
                    'background' => __('Background', TTO_I18N),
                    'color' => __('Color', TTO_I18N),
                    'font' => __('Google Fonts', TTO_I18N),
                    'include' => __('Include PHP file', TTO_I18N),
                    'social' => __('Social Links', TTO_I18N),
                    'rte' => __('Wordpress RTE', TTO_I18N),
                    'upload' => __('Wordpress Upload', TTO_I18N)
                ),
                __('Wordress fields', TTO_I18N) => array(
                    'wordpress' => __('Categories, menus, pages, posts, posttypes and tags', TTO_I18N)
                )
            );
        }
        //Return defauls TTO types for CPTs
        else if ('typescpts' == $return)
        {
            $defaults = array(
                __('Common fields', TTO_I18N) => array(
                    'text' => __('Basic text, password, email, number and more', TTO_I18N),
                    'checkbox' => __('Checkbox', TTO_I18N),
                    'multiselect' => __('Multiselect', TTO_I18N),
                    'radio' => __('Radio', TTO_I18N),
                    'select' => __('Select', TTO_I18N),
                    'textarea' => __('Textarea', TTO_I18N)
                ),
                __('Special fields', TTO_I18N) => array(
                    'background' => __('Background', TTO_I18N),
                    'color' => __('Color', TTO_I18N),
                    'font' => __('Google Fonts', TTO_I18N),
                    'social' => __('Social Links', TTO_I18N),
                    'rte' => __('Wordpress RTE', TTO_I18N),
                    'upload' => __('Wordpress Upload', TTO_I18N)
                ),
                __('Wordress fields', TTO_I18N) => array(
                    'wordpress' => __('Categories, menus, pages, posts, posttypes and tags', TTO_I18N)
                )
            );
        }
        //Return defaults TTO fields without ids
        else if ('withoutids' == $return)
        {
            $defaults = array(
                'br', 'heading', 'hr', 'list', 'p', 'include'
            );
        }
        //Return defauls TTO types and only Wordpress
        else if ('wordpress' == $return)
        {
            $defaults = array(
                'categories' => __('Categories', TTO_I18N),
                'menus' => __('Menus', TTO_I18N),
                'pages' => __('Pages', TTO_I18N),
                'posts' => __('Posts', TTO_I18N),
                'posttypes' => __('Post types', TTO_I18N),
                'tags' => __('Tags', TTO_I18N)
            );
        }

        //Return the array
        return $defaults;
    }

    /**
     * Return option's value from transient.
     *
     * @param string $key The name of the transient
     * @param var $default The default value if no one is found
     * @return var $value
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getOption($key, $default)
    {
        //Return value from DB
        return _get_option($key, $default);
    }

    /**
     * ACCESSORS
     **/

    /**
     * Retrieve the $adminmessage value
     *
     * @return bool $content Get the defined warning
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getAdminMessage()
    {
        //Return value
        return $this->adminmessage;
    }

    /**
     * Define the $adminmessage value
     *
     * @param bool $content Set the defined warning
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function setAdminMessage($content = '')
    {
        if (!empty($content))
        {
            include_once(TTO_PATH . 'classes/tpl/layouts/__layout_admin_message.tpl.php');
        }
    }

    /**
     * Retrieve the $can_upload value
     *
     * @uses current_user_can()
     * @return bool $can_upload Get if the user can upload files
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getCanUpload()
    {
        //Return value
        return current_user_can('upload_files');
    }

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getIncludes()
    {
        //Return value
        return $this->includes;
    }

    /**
     * Set includes.
     *
     * @param string $context Name of the included file's context
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function setIncludes($context)
    {
        $includes = $this->getIncludes();
        $this->includes[$context] = true;
    }

    /**
     * Get Wordpress contents already registered.
     *
     * @param string $type Wordpress content type to return
     * @param bool $multiple Define if there is multiselect or not
     * @return array $wpcontents Array of Wordpress content type registered
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function getWPContents($type = 'posts', $multiple = false)
    {
        //Access the WordPress Categories via an Array
        if (empty($this->wpcontents) || !isset($this->wpcontents[$type]))
        {
            $this->setWPContents($type, $multiple);
        }

        //Return value
        return $this->wpcontents[$type];
    }

    /**
     * Set Wordpress contents.
     *
     * @param string $type Wordpress content type to return
     * @param bool $multiple Define if there is multiselect or not
     *
     * @since Tea Theme Options 1.3.0
     */
    protected function setWPContents($type = 'posts', $multiple = false)
    {
        //Access the WordPress Categories via an Array
        if (empty($this->wpcontents) || !isset($this->wpcontents[$type]))
        {
            $this->wpcontents[$type] = array();

            //Set the first item
            if (!$multiple)
            {
                $this->wpcontents[$type][-1] = '---';
            }

            //Get asked contents

            //Menus
            if ('menus' == $type)
            {
                //Build request
                $menus_obj = wp_get_nav_menus(array('hide_empty' => false, 'orderby' => 'none'));

                //Iterate on menus
                foreach ($menus_obj as $menu)
                {
                    //For Wordpress version < 3.0
                    if (empty($menu->term_id))
                    {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][$menu->term_id] = $menu->name;
                }
            }
            //Pages
            else if ('pages' == $type)
            {
                //Build request
                $pages_obj = get_pages(array('sort_column' => 'post_parent,menu_order'));

                //Iterate on pages
                foreach ($pages_obj as $pag)
                {
                    //For Wordpress version < 3.0
                    if (empty($pag->ID))
                    {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][$pag->ID] = $pag->post_title;
                }
            }
            //Posts
            else if ('posts' == $type)
            {
                //Get vars
                $post = !isset($content['posttype']) ? 'post' : (is_array($content['posttype']) ? implode(',', $content['posttype']) : $content['posttype']);
                $number = isset($content['number']) ? $content['number'] : 50;

                //Build request
                $posts_obj = wp_get_recent_posts(array('numberposts' => $number, 'post_type' => $post, 'post_status' => 'publish'), OBJECT);

                //Iterate on posts
                foreach ($posts_obj as $pos)
                {
                    //For Wordpress version < 3.0
                    if (empty($pos->ID))
                    {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][$pos->ID] = $pos->post_title;
                }
            }
            //Post types
            else if ('posttypes' == $type)
            {
                //Build request
                $types_obj = get_post_types(array(), 'object');

                //Iterate on posttypes
                foreach ($types_obj as $typ)
                {
                    //Get the the name
                    $this->wpcontents[$type][$typ->name] = $typ->labels->name;
                }
            }
            //Tags
            else if ('tags' == $type)
            {
                //Build request
                $tags_obj = get_the_tags();

                //Iterate on tags
                foreach ($tags_obj as $tag)
                {
                    //Get the id and the name
                    $this->wpcontents[$type][$tag->term_id] = $tag->name;
                }
            }
            //Categories
            else
            {
                //Build request
                $categories_obj = get_categories(array('hide_empty' => 0));

                //Iterate on categories
                foreach ($categories_obj as $cat)
                {
                    //For Wordpress version < 3.0
                    if (empty($cat->cat_ID))
                    {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][$cat->cat_ID] = $cat->cat_name;
                }
            }
        }
    }
}
