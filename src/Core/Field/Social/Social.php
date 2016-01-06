<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Social;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO SOCIAL FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'social',
 *     'title' => 'Big Brother is watching you...',
 *     'id' => 'my_social_field_id',
 *     'description' => '...Or not!',
 *     'expandable' => false,
 *     'default' => array(
 *         'facebook' => array(
 *             'display' => '1',
 *             'label' => 'Become a fan',
 *             'link' => 'http://www.facebook.com/achrafchouk'
 *         ),
 *         'twitter' => array(
 *             'display' => '1',
 *             'label' => 'Follow us',
 *             'link' => 'https://twitter.com/crewstyle'
 *         ),
 *         'instagram' => array(
 *             'display' => '0',
 *             'label' => 'Take a shot',
 *             'link' => 'https://instagram.com/crewstyle'
 *         ),
 *         'rss' => array(
 *             'label' => 'Subscribe to our feed'
 *         )
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Social
 *
 * Class used to build Social field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Social
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Social extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-globe';

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Display HTML component.
     *
     * @param array $content Contains all field data
     * @param array $details Contains all field options
     *
     * @since 3.0.0
     */
    public function prepareField($content, $details = array())
    {
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $tpl = empty($prefix) ? 'pages' : 'terms';

        //Build defaults data
        $template = array(
            'id' => $content['id'],
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Social'),
            'default' => isset($content['default']) ? $content['default'] : array(),
            'description' => isset($content['description']) ? $content['description'] : '',
            'expandable' => isset($content['expandable']) && is_bool($content['expandable']) 
                ? $content['expandable'] 
                : false,

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_check_all' => TeaThemeOptions::__('Un/select all options'),
            't_add_social' => TeaThemeOptions::__('Add a social network'),
            't_delete_all' => TeaThemeOptions::__('Delete all social networks'),
            't_socials' => TeaThemeOptions::__('Social networks'),
            't_socials_select' => TeaThemeOptions::__('Selection'),
            't_socials_description' => TeaThemeOptions::__('Choose the social networks you want to display'),
            't_close' => TeaThemeOptions::__('Close'),
        );

        //Count
        $template['count'] = count($template['default']);

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Works on socials
        $socials = $this->getSocialNetworks();
        $template['socials'] = array();

        //List everything
        foreach ($socials as $k => $sc) {
            $template['socials'][$k] = $sc;
            $template['socials'][$k]['active'] = false;

            //Check if key exists in vals
            if (array_key_exists($k, $template['val'])) {
                $template['socials'][$k]['active'] = true;
            }

            //Get color in RGB format
            $color = $this->hex2rgb($sc['color']);
            $template['socials'][$k]['color'] = '';

            if (!empty($color)) {
                $template['socials'][$k]['color'] = $color['r'].','.$color['g'].','.$color['b'];
            }
        }

        //Works on values
        foreach ($template['val'] as $k => $sc) {
            $template['val'][$k]['title'] = $template['socials'][$k]['title'];
            $template['val'][$k]['color'] = $template['socials'][$k]['color'];
        }

        //Get template
        return $this->renderField('fields/social.html.twig', $template);
    }

    /**
     * Return all available Social networks.
     *
     * @return array $array Contains all Social networks
     *
     * @since 3.3.0
     */
    public function getSocialNetworks()
    {
        return array(
            'twitter'       => array(
                'url'           => TeaThemeOptions::__('http://www.twitter.com/'),
                'title'         => TeaThemeOptions::__('Twitter'),
                'value'         => TeaThemeOptions::__('Follow me on Twitter'),
                'placeholder'   => TeaThemeOptions::__('http://www.twitter.com/__username__'),
                'color'         => '#55acee',
            ),
            'facebook'      => array(
                'url'           => TeaThemeOptions::__('http://www.facebook.com/'),
                'title'         => TeaThemeOptions::__('Facebook'),
                'value'         => TeaThemeOptions::__('Follow me on Facebook'),
                'placeholder'   => TeaThemeOptions::__('http://www.facebook.com/__username__'),
                'color'         => '#3b5998',
            ),
            'instagram'     => array(
                'url'           => TeaThemeOptions::__('http://www.instagram.com/'),
                'title'         => TeaThemeOptions::__('Instagram'),
                'value'         => TeaThemeOptions::__('Follow me on Instagram'),
                'placeholder'   => TeaThemeOptions::__('http://www.instagram.com/__username__'),
                'color'         => '#3f729b',
            ),
            'google-plus'   => array(
                'url'           => TeaThemeOptions::__('http://plus.google.com/'),
                'title'         => TeaThemeOptions::__('Google+'),
                'value'         => TeaThemeOptions::__('Follow me on Google+'),
                'placeholder'   => TeaThemeOptions::__('http://plus.google.com/__username__'),
                'color'         => '#dc4e41',
            ),
            'linkedin'      => array(
                'url'           => TeaThemeOptions::__('http://www.linkedin.com/'),
                'title'         => TeaThemeOptions::__('LinkedIn'),
                'value'         => TeaThemeOptions::__('Follow me on LinkedIn'),
                'placeholder'   => TeaThemeOptions::__('http://www.linkedin.com/in/__username__'),
                'color'         => '#0077b5',
            ),



            '500px'         => array(
                'url'           => TeaThemeOptions::__('https://500px.com/'),
                'title'         => TeaThemeOptions::__('500px'),
                'value'         => TeaThemeOptions::__('See my portfolio on 500px'),
                'placeholder'   => TeaThemeOptions::__('https://500px.com/__username__'),
                'color'         => '#0099e5',
            ),
            'behance'       => array(
                'url'           => TeaThemeOptions::__('http://www.behance.com/'),
                'title'         => TeaThemeOptions::__('Behance'),
                'value'         => TeaThemeOptions::__('See my portfolio on Behance'),
                'placeholder'   => TeaThemeOptions::__('http://www.behance.com/__username__'),
                'color'         => '#1769ff',
            ),
            'bitbucket'     => array(
                'url'           => TeaThemeOptions::__('https://bitbucket.org/'),
                'title'         => TeaThemeOptions::__('Bitbucket'),
                'value'         => TeaThemeOptions::__('Tip me on Bitbucket'),
                'placeholder'   => TeaThemeOptions::__('https://bitbucket.org/__username__'),
                'color'         => '#205081',
            ),
            'codepen'       => array(
                'url'           => TeaThemeOptions::__('http://codepen.io/'),
                'title'         => TeaThemeOptions::__('CodePen'),
                'value'         => TeaThemeOptions::__('Follow me on Codepen.io'),
                'placeholder'   => TeaThemeOptions::__('http://codepen.io/__username__'),
                'color'         => '#000000',
            ),
            'delicious'     => array(
                'url'           => TeaThemeOptions::__('https://delicious.com/'),
                'title'         => TeaThemeOptions::__('Delicious'),
                'value'         => TeaThemeOptions::__('Follow me on Delicious'),
                'placeholder'   => TeaThemeOptions::__('https://delicious.com/__username__'),
                'color'         => '#3399ff',
            ),
            'deviantart'    => array(
                'url'           => TeaThemeOptions::__('http://www.deviantart.com/'),
                'title'         => TeaThemeOptions::__('DeviantArt'),
                'value'         => TeaThemeOptions::__('Follow me on Deviantart'),
                'placeholder'   => TeaThemeOptions::__('http://__username__.deviantart.com/'),
                'color'         => '#05cc47',
            ),
            'dribbble'      => array(
                'url'           => TeaThemeOptions::__('http://dribbble.com/'),
                'title'         => TeaThemeOptions::__('Dribbble'),
                'value'         => TeaThemeOptions::__('Follow me on Dribbble'),
                'placeholder'   => TeaThemeOptions::__('http://dribbble.com/__username__'),
                'color'         => '#ea4c89',
            ),
            'flickr'        => array(
                'url'           => TeaThemeOptions::__('http://www.flickr.com/'),
                'title'         => TeaThemeOptions::__('FlickR'),
                'value'         => TeaThemeOptions::__('Follow me on Flickr'),
                'placeholder'   => TeaThemeOptions::__('http://www.flickr.com/photos/__username__'),
                'color'         => '#0063dc',
            ),
            'foursquare'    => array(
                'url'           => TeaThemeOptions::__('https://www.foursquare.com/'),
                'title'         => TeaThemeOptions::__('4square'),
                'value'         => TeaThemeOptions::__('See me on Foursquare'),
                'placeholder'   => TeaThemeOptions::__('https://www.foursquare.com/__username__'),
                'color'         => '#f94877',
            ),
            'gittip'        => array(
                'url'           => TeaThemeOptions::__('https://www.gittip.com/'),
                'title'         => TeaThemeOptions::__('Gittip'),
                'value'         => TeaThemeOptions::__('Follow me on Gittip'),
                'placeholder'   => TeaThemeOptions::__('https://www.gittip.com/__username__'),
                'color'         => '#663300',
            ),
            'github'        => array(
                'url'           => TeaThemeOptions::__('https://github.com/'),
                'title'         => TeaThemeOptions::__('Github'),
                'value'         => TeaThemeOptions::__('Follow me on Github'),
                'placeholder'   => TeaThemeOptions::__('https://github.com/__username__'),
                'color'         => '#333333',
            ),
            'gratipay'      => array(
                'url'           => TeaThemeOptions::__('https://gratipay.com/'),
                'title'         => TeaThemeOptions::__('Gratipay'),
                'value'         => TeaThemeOptions::__('Tip me Gratipay'),
                'placeholder'   => TeaThemeOptions::__('https://gratipay.com/__username__'),
                'color'         => '#6c2e00',
            ),
            'lastfm'        => array(
                'url'           => TeaThemeOptions::__('http://www.lastfm.fr/'),
                'title'         => TeaThemeOptions::__('Last FM'),
                'value'         => TeaThemeOptions::__('Follow me on LastFM'),
                'placeholder'   => TeaThemeOptions::__('http://www.lastfm.fr/user/__username__'),
                'color'         => '#d51007',
            ),
            'pinterest'     => array(
                'url'           => TeaThemeOptions::__('http://pinterest.com/'),
                'title'         => TeaThemeOptions::__('Pinterest'),
                'value'         => TeaThemeOptions::__('Follow me on Pinterest'),
                'placeholder'   => TeaThemeOptions::__('http://pinterest.com/__username__'),
                'color'         => '#bd081c',
            ),
            'reddit'        => array(
                'url'           => TeaThemeOptions::__('http://www.reddit.com/'),
                'title'         => TeaThemeOptions::__('Reddit'),
                'value'         => TeaThemeOptions::__('Follow me on Reddit'),
                'placeholder'   => TeaThemeOptions::__('http://www.reddit.com/user/__username__'),
                'color'         => '#ff4500',
            ),
            'skype'         => array(
                'url'           => TeaThemeOptions::__('http://www.skype.com/'),
                'title'         => TeaThemeOptions::__('Skype'),
                'value'         => TeaThemeOptions::__('Connect us on Skype'),
                'placeholder'   => TeaThemeOptions::__('__username__'),
                'color'         => '#00aff0',
            ),
            'soundcloud'    => array(
                'url'           => TeaThemeOptions::__('https://soundcloud.com/'),
                'title'         => TeaThemeOptions::__('SoundCloud'),
                'value'         => TeaThemeOptions::__('Follow me on Soundcloud'),
                'placeholder'   => TeaThemeOptions::__('https://soundcloud.com/__username__'),
                'color'         => '#ff3300',
            ),
            'stumbleupon'   => array(
                'url'           => TeaThemeOptions::__('http://www.stumbleupon.com/'),
                'title'         => TeaThemeOptions::__('Stumbleupon'),
                'value'         => TeaThemeOptions::__('Follow me on Stumbleupon'),
                'placeholder'   => TeaThemeOptions::__('http://www.stumbleupon.com/stumbler/__username__'),
                'color'         => '#eb4924',
            ),
            'tumblr'        => array(
                'url'           => TeaThemeOptions::__('https://www.tumblr.com/'),
                'title'         => TeaThemeOptions::__('Tumblr'),
                'value'         => TeaThemeOptions::__('Follow me on Tumblr'),
                'placeholder'   => TeaThemeOptions::__('http://'),
                'color'         => '#35465c',
            ),
            'vimeo'         => array(
                'url'           => TeaThemeOptions::__('http://www.vimeo.com/'),
                'title'         => TeaThemeOptions::__('Vimeo'),
                'value'         => TeaThemeOptions::__('Follow me on Vimeo'),
                'placeholder'   => TeaThemeOptions::__('http://www.vimeo.com/__username__'),
                'color'         => '#1ab7ea',
            ),
            'vine'          => array(
                'url'           => TeaThemeOptions::__('https://vine.co/'),
                'title'         => TeaThemeOptions::__('Vine'),
                'value'         => TeaThemeOptions::__('Follow me on Vine.co'),
                'placeholder'   => TeaThemeOptions::__('https://vine.co/__username__'),
                'color'         => '#00b488',
            ),
            'vk'            => array(
                'url'           => TeaThemeOptions::__('http://vk.com/'),
                'title'         => TeaThemeOptions::__('VKontakte'),
                'value'         => TeaThemeOptions::__('Follow me on VK.com'),
                'placeholder'   => TeaThemeOptions::__('http://vk.com/__username__'),
                'color'         => '#45668e',
            ),
            'weibo'         => array(
                'url'           => TeaThemeOptions::__('http://www.weibo.com/'),
                'title'         => TeaThemeOptions::__('Weibo'),
                'value'         => TeaThemeOptions::__('Follow me on Weibo'),
                'placeholder'   => TeaThemeOptions::__('http://www.weibo.com/__username__'),
                'color'         => '#e6162d',
            ),
            'youtube'       => array(
                'url'           => TeaThemeOptions::__('http://www.youtube.com/'),
                'title'         => TeaThemeOptions::__('Youtube'),
                'value'         => TeaThemeOptions::__('Follow me on Youtube'),
                'placeholder'   => TeaThemeOptions::__('http://www.youtube.com/user/__username__'),
                'color'         => '#cd201f',
            ),
            'rss'           => array(
                'url'           => '',
                'title'         => TeaThemeOptions::__('RSS'),
                'value'         => TeaThemeOptions::__('Subscribe to my RSS feed'),
                'placeholder'   => TeaThemeOptions::__('__rssfeed__'),
                'color'         => '#f26522',
            ),
        );
    }

    /**
     * Return RGB array from hex color.
     *
     * @param string $hexcolor Hex color.
     * @return array $array Contains RGB values.
     *
     * @since 3.0.0
     */
    protected function hex2rgb($hexcolor) 
    {
        preg_match("/^#{0,1}([0-9a-f]{1,6})$/i", $hexcolor, $match);

        if (!isset($match[1])) {
            return array();
        }

        $color = $match[1];

        #91d04d
        if (6 == strlen($color)) {
            list($r, $g, $b) = array(
                $color[0] . $color[1],
                $color[2] . $color[3],
                $color[4] . $color[5]
            );
        }
        #aaa
        else if (3 == strlen($color)) {
            list($r, $g, $b) = array(
                $color[0] . $color[0],
                $color[1] . $color[1],
                $color[2] . $color[2]
            );
        }
        #bb
        else if (2 == strlen($color))
        {
            list($r, $g, $b) = array(
                $color[0] . $color[1],
                $color[0] . $color[1],
                $color[0] . $color[1]
            );
        }
        #c
        else if (1 == strlen($color)) {
            list($r, $g, $b) = array(
                $color . $color,
                $color . $color,
                $color . $color
            );
        }
        else {
            return array();
        }

        return array(
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b),
        );
    }
}
