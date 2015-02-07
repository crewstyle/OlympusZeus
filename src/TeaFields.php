<?php

namespace Takeatea\TeaThemeOptions;

/**
 * TEA FIELDS
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * Tea Fields
 *
 * Abstract class to define all field context with authorized fields, how to
 * write some functions and every usefull checks.
 *
 * @package Tea Theme Options
 * @subpackage Tea Fields
 * @author Achraf Chouk <ach@takeatea.com>
 * @since 1.5.2.14
 *
 */
abstract class TeaFields
{
    //Define protected/private vars
    public $adminmessage = null;
    private $includes = array();
    private $wpcontents = array();

    /**
     * Constructor.
     *
     * @since 1.5.0
     */
    public function __construct()
    {
        //Init errors
        $this->adminmessage = new TeaAdminMessage();
    }


    //------------------------------------------------------------------------//

    /**
     * ABSTRACT & STATIC FUNCTIONS
     */

    /**
     * Display HTML component.
     *
     * @param array $content Contains all field data
     * @param array $post Contains all post data
     * @param string $prefix Contains prefix name
     *
     * @since 1.4.0
     */
    abstract protected function templatePages($content, $post = array(), $prefix = '');

    /**
     * Build HTML component.
     *
     * @param array $post Contains all data such as Wordpress asks
     * @param array $args Contains all data such as Wordpress asks
     *
     * @since 1.4.0
     */
    static function templateCustomPostTypes($post, $args)
    {
        //If autosave...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return isset($post->ID) ? $post->ID : 0;
        }

        //Get values
        $type = $args['args']['contents']['type'];
        $class = ucfirst($type);

        //Make the magic
        $class = '\Takeatea\TeaThemeOptions\Fields\\'.$class.'\\'.$class;
        $field = new $class();
        $field->templatePages($args, $post);
    }

    /**
     * FUNCTIONS
     **/

    /**
     * Checks if an ID is defined
     *
     * @param array $content Contains all field data
     *
     * @since 1.5.0
     */
    protected function checkId($content)
    {
        //Check if an id is defined at least
        if (!isset($content['id'])) {
            //Set type
            $type = isset($content['type'])
                ? $content['type']
                : __('undefined (?)', TTO_I18N);

            //Display message
            $this->updateAdminMessage('<b>'.$type.'</b>');
        }
    }

    /**
     * Return default values.
     *
     * @param string $return Define what to return
     * @return array $defaults All defaults data provided by the Tea TO
     * @todo find a better way to social networks
     *
     * @since 1.4.3.2
     */
    static function getDefaults($return = 'images')
    {
        $defaults = array();

        //Return defaults background values
        if ('background-details' == $return) {
            $defaults = array(
                'position'  => array(
                    'x'     => array(
                        'left'      => __('Left', TTO_I18N),
                        'center'    => __('Center', TTO_I18N),
                        'right'     => __('Right', TTO_I18N),
                    ),
                    'y'     => array(
                        'top'       => __('Top', TTO_I18N),
                        'middle'    => __('Middle', TTO_I18N),
                        'bottom'    => __('Bottom', TTO_I18N),
                    ),
                ),
                'repeat'    => array(
                    'no-repeat'     => __('Background is displayed only once.', TTO_I18N),
                    'repeat-x'      => __('Background is repeated horizontally only.', TTO_I18N),
                    'repeat-y'      => __('Background is repeated vertically only.', TTO_I18N),
                    'repeat'        => __('Background is repeated all the way.', TTO_I18N),
                ),
            );
        }

        //Return defaults font
        else if ('fonts' == $return) {
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
                array('Yanone+Kaffeesatz', 'Yanone Kaffeesatz', '200,300,400,700'),
            );
        }

        //Return defaults background images
        else if ('images' == $return) {
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
                'custom.png'         => 'CUSTOM',
            );
        }

        //Return defaults TTO types and only networks
        else if ('networks' == $return) {
            $defaults = array(
                'facebook'      => __('Facebook', TTO_I18N),
                'googleplus'    => __('Google+', TTO_I18N),
                'instagram'     => __('Instagram', TTO_I18N),
                'twitter'       => __('Twitter', TTO_I18N),
            );
        }

        //Return defaults TTO networks callback
        else if ('networks_callback' == $return) {
            $defaults = array(
            );
        }

        //Return defaults social button
        else if ('social' == $return) {
            $defaults = array(
                'behance'       => array(
                    __('See my portfolio on Behance', TTO_I18N),
                    __('http://www.behance.com/__username__', TTO_I18N),
                ),
                'bitbucket'     => array(
                    __('Tip me on Bitbucket', TTO_I18N),
                    __('https://bitbucket.org/__username__', TTO_I18N),
                ),
                'codepen'       => array(
                    __('Follow me on Codepen.io', TTO_I18N),
                    __('http://codepen.io/__username__', TTO_I18N),
                ),
                'delicious'     => array(
                    __('Follow me on Delicious', TTO_I18N),
                    __('https://delicious.com/__username__', TTO_I18N),
                ),
                'deviantart'    => array(
                    __('Follow me on Deviantart', TTO_I18N),
                    __('http://__username__.deviantart.com/', TTO_I18N),
                ),
                'dribbble'      => array(
                    __('Follow me on Dribbble', TTO_I18N),
                    __('http://dribbble.com/__username__', TTO_I18N),
                ),
                'facebook'      => array(
                    __('Follow me on Facebook', TTO_I18N),
                    __('http://www.facebook.com/__username__', TTO_I18N),
                ),
                'flickr'        => array(
                    __('Follow me on Flickr', TTO_I18N),
                    __('http://www.flickr.com/photos/__username__', TTO_I18N),
                ),
                'foursquare'    => array(
                    __('See me on Foursquare', TTO_I18N),
                    __('https://fr.foursquare.com/__username__', TTO_I18N),
                ),
                'github'        => array(
                    __('Follow me on Github', TTO_I18N),
                    __('https://github.com/__username__', TTO_I18N),
                ),
                'gittip'        => array(
                    __('Follow me on Gittip', TTO_I18N),
                    __('https://www.gittip.com/__username__', TTO_I18N),
                ),
                'google-plus'   => array(
                    __('Follow me on Google+', TTO_I18N),
                    __('http://plus.google.com/__username__', TTO_I18N),
                ),
                'gratipay'      => array(
                    __('Tip me Gratipay', TTO_I18N),
                    __('https://gratipay.com/__username__', TTO_I18N),
                ),
                'instagram'     => array(
                    __('Follow me on Instagram', TTO_I18N),
                    __('http://www.instagram.com/__username__', TTO_I18N),
                ),
                'lastfm'        => array(
                    __('Follow me on LastFM', TTO_I18N),
                    __('http://www.lastfm.fr/user/__username__', TTO_I18N),
                ),
                'linkedin'      => array(
                    __('Follow me on LinkedIn', TTO_I18N),
                    __('http://www.linkedin.com/in/__username__', TTO_I18N),
                ),
                'pinterest'     => array(
                    __('Follow me on Pinterest', TTO_I18N),
                    __('http://pinterest.com/__username__', TTO_I18N),
                ),
                'reddit'        => array(
                    __('Follow me on Reddit', TTO_I18N),
                    __('http://www.reddit.com/user/__username__', TTO_I18N),
                ),
                'skype'         => array(
                    __('Connect us on Skype', TTO_I18N),
                ),
                'soundcloud'    => array(
                    __('Follow me on Soundcloud', TTO_I18N),
                    __('https://soundcloud.com/__username__', TTO_I18N),
                ),
                'stumbleupon'   => array(
                    __('Follow me on Stumbleupon', TTO_I18N),
                    __('http://www.stumbleupon.com/stumbler/__username__', TTO_I18N),
                ),
                'tumblr'        => array(
                    __('Follow me on Tumblr', TTO_I18N),
                    __('http://', TTO_I18N),
                ),
                'twitter'       => array(
                    __('Follow me on Twitter', TTO_I18N),
                    __('http://www.twitter.com/__username__', TTO_I18N),
                ),
                'vimeo'         => array(
                    __('Follow me on Vimeo', TTO_I18N),
                    __('http://www.vimeo.com/__username__', TTO_I18N),
                ),
                'vine'          => array(
                    __('Follow me on Vine.co', TTO_I18N),
                    __('https://vine.co/__username__', TTO_I18N),
                ),
                'vk'            => array(
                    __('Follow me on VK.com', TTO_I18N),
                    __('http://vk.com/__username__', TTO_I18N),
                ),
                'weibo'         => array(
                    __('Follow me on Weibo', TTO_I18N),
                    __('http://www.weibo.com/__username__', TTO_I18N),
                ),
                'youtube'       => array(
                    __('Follow me on Youtube', TTO_I18N),
                    __('http://www.youtube.com/user/__username__', TTO_I18N),
                ),
                'rss'           => array(
                    __('Subscribe to my RSS feed', TTO_I18N),
                ),
            );
        }

        //Return defaults unauthorized TTO fields
        else if ('unauthorized' == $return) {
            $defaults = array(
                'elasticsearch', 'network', 'section',
            );
        }

        //Return the array
        return $defaults;
    }

    /**
     * ACCESSORS
     **/

    /**
     * Retrieve the $can_upload value
     *
     * @uses current_user_can()
     * @return bool $can_upload Get if the user can upload files
     *
     * @since 1.3.0
     */
    protected function getCanUpload()
    {
        //Return value
        return current_user_can('upload_files');
    }

    /**
     * Update adminmessage.
     *
     * @param string $content Contains field type in error
     *
     * @since 1.5.0
     */
    public function updateAdminMessage($content)
    {
        if (null === $this->adminmessage) {
            $this->adminmessage = new TeaAdminMessage();
        }

        $this->adminmessage->addFieldError($content);
    }

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since 1.3.0
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
     * @since 1.5.0
     */
    protected function setIncludes($context)
    {
        $this->includes[$context] = true;
    }

    /**
     * Get Wordpress contents already registered.
     *
     * @param string $type Wordpress content type to return
     * @param bool $multiple Define if there is multiselect or not
     * @param array $options Define options if needed
     * @param int $post Define the post ID for meta boxes
     * @return array $wpcontents Array of Wordpress content type registered
     *
     * @since 1.4.0
     */
    protected function getWPContents($type = 'posts', $multiple = false, $options = array(), $post = 0)
    {
        //Access the WordPress Categories via an Array
        if (empty($this->wpcontents) || !isset($this->wpcontents[$type]) || !empty($options)) {
            $this->setWPContents($type, $multiple, $options, $post);
        }

        //Return value
        return $this->wpcontents[$type];
    }

    /**
     * Set Wordpress contents.
     *
     * @uses wp_get_nav_menus()
     * @uses get_pages()
     * @uses wp_get_recent_posts()
     * @uses get_post_types()
     * @uses get_the_tags()
     * @uses get_categories()
     * @param string $type Wordpress content type to return
     * @param bool $multiple Define if there is multiselect or not
     * @param array $options Define if there are options to the WP request
     * @param int $post Define the post ID for meta boxes
     *
     * @since 1.5.2-1
     */
    protected function setWPContents($type = 'posts', $multiple = false, $options = array(), $post = 0)
    {
        //Check if we already have datas stored
        if (!empty($this->wpcontents) && isset($this->wpcontents[$type])) {
            return;
        }

        //Access the WordPress Categories via an Array
        $this->wpcontents[$type] = array();

        //Set the first item
        if (!$multiple) {
            $this->wpcontents[$type][0][-1] = '---';
        }

        //Exclude current post
        if (isset($options['exclude']) && 'current' == $options['exclude']) {
            $options['exclude'] = $post;
        }

        //Get asked contents

        //Menus
        if ('menus' == $type) {
            //Build options
            $request = array(
                'hide_empty' => false,
                'orderby' => 'none'
            );
            $args = array_merge($request, $options);

            //Build request
            $menus_obj = wp_get_nav_menus($args);

            //Iterate on menus
            if (!empty($menus_obj)) {
                foreach ($menus_obj as $menu) {
                    //For Wordpress version < 3.0
                    if (empty($menu->term_id)) {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][0][$menu->term_id] = $menu->name;
                }
            }
        }

        //Pages
        else if ('pages' == $type) {
            //Build options
            $request = array(
                'sort_column' => 'post_parent,menu_order'
            );
            $args = array_merge($request, $options);

            //Build request
            $pages_obj = get_pages($args);

            //Iterate on pages
            if (!empty($pages_obj)) {
                foreach ($pages_obj as $pag) {
                    //For Wordpress version < 3.0
                    if (empty($pag->ID)) {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][0][$pag->ID] = $pag->post_title;
                }
            }
        }

        //Posts
        else if ('posts' == $type) {
            //Build options
            $request = array(
                'numberposts' => 50,
                'post_type' => isset($options['post_type']) ? $options['post_type'] : 'post',
                'post_status' => 'publish'
            );
            $args = array_merge($request, $options);

            //Build request
            $posts_obj = wp_get_recent_posts($args, OBJECT);

            //Iterate on posts
            if (!empty($posts_obj)) {
                foreach ($posts_obj as $pos) {
                    //For Wordpress version < 3.0
                    if (empty($pos->ID)) {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][$pos->post_type][$pos->ID] = $pos->post_title;
                }
            }
        }

        //Post types
        else if ('posttypes' == $type) {
            //Build options
            $request = array();
            $args = array_merge($request, $options);

            //Build request
            $types_obj = get_post_types($args, 'object');

            //Iterate on posttypes
            if (!empty($types_obj)) {
                foreach ($types_obj as $typ) {
                    //Get the the name
                    $this->wpcontents[$type][0][$typ->name] = $typ->labels->name;
                }
            }
        }

        //Tags
        else if ('tags' == $type) {
            //Build request
            $tags_obj = get_the_tags();

            //Iterate on tags
            if (!empty($tags_obj)) {
                foreach ($tags_obj as $tag) {
                    //Get the id and the name
                    $this->wpcontents[$type][0][$tag->term_id] = $tag->name;
                }
            }
        }

        //Taxonomies
        else if ('taxonomies' == $type) {
            //Build request
            $taxs_obj = get_terms($options['term'], array('hide_empty' => 0));

            //Iterate on tags
            if (!empty($taxs_obj)) {
                foreach ($taxs_obj as $tax) {
                    //Get the id and the name
                    $this->wpcontents[$type][0][$tax->term_id] = $tax->name;
                }
            }
        }

        //Categories
        else {
            //Build options
            $request = array(
                'hide_empty' => 0
            );
            $args = array_merge($request, $options);

            //Build request
            $categories_obj = get_categories($args);

            //Iterate on categories
            if (!empty($categories_obj)) {
                foreach ($categories_obj as $cat) {
                    //For Wordpress version < 3.0
                    if (empty($cat->cat_ID)) {
                        continue;
                    }

                    //Get the id and the name
                    $this->wpcontents[$type][0][$cat->cat_ID] = $cat->cat_name;
                }
            }
        }
    }
}
