<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Social field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Social
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/social
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
     * @since 4.0.0
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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Social'),
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
            't_check_all' => OlympusZeus::translate('Un/select all options'),
            't_add_social' => OlympusZeus::translate('Add a social network'),
            't_delete_all' => OlympusZeus::translate('Delete all social networks'),
            't_socials' => OlympusZeus::translate('Social networks'),
            't_socials_select' => OlympusZeus::translate('Selection'),
            't_socials_description' => OlympusZeus::translate('Choose the social networks you want to display'),
            't_close' => OlympusZeus::translate('Close'),
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
     * @since 4.0.0
     */
    public function getSocialNetworks()
    {
        return array(
            'twitter'       => array(
                'url'           => OlympusZeus::translate('http://www.twitter.com/'),
                'title'         => OlympusZeus::translate('Twitter'),
                'value'         => OlympusZeus::translate('Follow me on Twitter'),
                'placeholder'   => OlympusZeus::translate('http://www.twitter.com/__username__'),
                'color'         => '#55acee',
            ),
            'facebook'      => array(
                'url'           => OlympusZeus::translate('http://www.facebook.com/'),
                'title'         => OlympusZeus::translate('Facebook'),
                'value'         => OlympusZeus::translate('Follow me on Facebook'),
                'placeholder'   => OlympusZeus::translate('http://www.facebook.com/__username__'),
                'color'         => '#3b5998',
            ),
            'instagram'     => array(
                'url'           => OlympusZeus::translate('http://www.instagram.com/'),
                'title'         => OlympusZeus::translate('Instagram'),
                'value'         => OlympusZeus::translate('Follow me on Instagram'),
                'placeholder'   => OlympusZeus::translate('http://www.instagram.com/__username__'),
                'color'         => '#3f729b',
            ),
            'google-plus'   => array(
                'url'           => OlympusZeus::translate('http://plus.google.com/'),
                'title'         => OlympusZeus::translate('Google+'),
                'value'         => OlympusZeus::translate('Follow me on Google+'),
                'placeholder'   => OlympusZeus::translate('http://plus.google.com/__username__'),
                'color'         => '#dc4e41',
            ),
            'linkedin'      => array(
                'url'           => OlympusZeus::translate('http://www.linkedin.com/'),
                'title'         => OlympusZeus::translate('LinkedIn'),
                'value'         => OlympusZeus::translate('Follow me on LinkedIn'),
                'placeholder'   => OlympusZeus::translate('http://www.linkedin.com/in/__username__'),
                'color'         => '#0077b5',
            ),



            '500px'         => array(
                'url'           => OlympusZeus::translate('https://500px.com/'),
                'title'         => OlympusZeus::translate('500px'),
                'value'         => OlympusZeus::translate('See my portfolio on 500px'),
                'placeholder'   => OlympusZeus::translate('https://500px.com/__username__'),
                'color'         => '#0099e5',
            ),
            'behance'       => array(
                'url'           => OlympusZeus::translate('http://www.behance.com/'),
                'title'         => OlympusZeus::translate('Behance'),
                'value'         => OlympusZeus::translate('See my portfolio on Behance'),
                'placeholder'   => OlympusZeus::translate('http://www.behance.com/__username__'),
                'color'         => '#1769ff',
            ),
            'bitbucket'     => array(
                'url'           => OlympusZeus::translate('https://bitbucket.org/'),
                'title'         => OlympusZeus::translate('Bitbucket'),
                'value'         => OlympusZeus::translate('Tip me on Bitbucket'),
                'placeholder'   => OlympusZeus::translate('https://bitbucket.org/__username__'),
                'color'         => '#205081',
            ),
            'codepen'       => array(
                'url'           => OlympusZeus::translate('http://codepen.io/'),
                'title'         => OlympusZeus::translate('CodePen'),
                'value'         => OlympusZeus::translate('Follow me on Codepen.io'),
                'placeholder'   => OlympusZeus::translate('http://codepen.io/__username__'),
                'color'         => '#000000',
            ),
            'delicious'     => array(
                'url'           => OlympusZeus::translate('https://delicious.com/'),
                'title'         => OlympusZeus::translate('Delicious'),
                'value'         => OlympusZeus::translate('Follow me on Delicious'),
                'placeholder'   => OlympusZeus::translate('https://delicious.com/__username__'),
                'color'         => '#3399ff',
            ),
            'deviantart'    => array(
                'url'           => OlympusZeus::translate('http://www.deviantart.com/'),
                'title'         => OlympusZeus::translate('DeviantArt'),
                'value'         => OlympusZeus::translate('Follow me on Deviantart'),
                'placeholder'   => OlympusZeus::translate('http://__username__.deviantart.com/'),
                'color'         => '#05cc47',
            ),
            'dribbble'      => array(
                'url'           => OlympusZeus::translate('http://dribbble.com/'),
                'title'         => OlympusZeus::translate('Dribbble'),
                'value'         => OlympusZeus::translate('Follow me on Dribbble'),
                'placeholder'   => OlympusZeus::translate('http://dribbble.com/__username__'),
                'color'         => '#ea4c89',
            ),
            'flickr'        => array(
                'url'           => OlympusZeus::translate('http://www.flickr.com/'),
                'title'         => OlympusZeus::translate('FlickR'),
                'value'         => OlympusZeus::translate('Follow me on Flickr'),
                'placeholder'   => OlympusZeus::translate('http://www.flickr.com/photos/__username__'),
                'color'         => '#0063dc',
            ),
            'foursquare'    => array(
                'url'           => OlympusZeus::translate('https://www.foursquare.com/'),
                'title'         => OlympusZeus::translate('4square'),
                'value'         => OlympusZeus::translate('See me on Foursquare'),
                'placeholder'   => OlympusZeus::translate('https://www.foursquare.com/__username__'),
                'color'         => '#f94877',
            ),
            'gittip'        => array(
                'url'           => OlympusZeus::translate('https://www.gittip.com/'),
                'title'         => OlympusZeus::translate('Gittip'),
                'value'         => OlympusZeus::translate('Follow me on Gittip'),
                'placeholder'   => OlympusZeus::translate('https://www.gittip.com/__username__'),
                'color'         => '#663300',
            ),
            'github'        => array(
                'url'           => OlympusZeus::translate('https://github.com/'),
                'title'         => OlympusZeus::translate('Github'),
                'value'         => OlympusZeus::translate('Follow me on Github'),
                'placeholder'   => OlympusZeus::translate('https://github.com/__username__'),
                'color'         => '#333333',
            ),
            'gratipay'      => array(
                'url'           => OlympusZeus::translate('https://gratipay.com/'),
                'title'         => OlympusZeus::translate('Gratipay'),
                'value'         => OlympusZeus::translate('Tip me Gratipay'),
                'placeholder'   => OlympusZeus::translate('https://gratipay.com/__username__'),
                'color'         => '#6c2e00',
            ),
            'lastfm'        => array(
                'url'           => OlympusZeus::translate('http://www.lastfm.fr/'),
                'title'         => OlympusZeus::translate('Last FM'),
                'value'         => OlympusZeus::translate('Follow me on LastFM'),
                'placeholder'   => OlympusZeus::translate('http://www.lastfm.fr/user/__username__'),
                'color'         => '#d51007',
            ),
            'pinterest'     => array(
                'url'           => OlympusZeus::translate('http://pinterest.com/'),
                'title'         => OlympusZeus::translate('Pinterest'),
                'value'         => OlympusZeus::translate('Follow me on Pinterest'),
                'placeholder'   => OlympusZeus::translate('http://pinterest.com/__username__'),
                'color'         => '#bd081c',
            ),
            'reddit'        => array(
                'url'           => OlympusZeus::translate('http://www.reddit.com/'),
                'title'         => OlympusZeus::translate('Reddit'),
                'value'         => OlympusZeus::translate('Follow me on Reddit'),
                'placeholder'   => OlympusZeus::translate('http://www.reddit.com/user/__username__'),
                'color'         => '#ff4500',
            ),
            'skype'         => array(
                'url'           => OlympusZeus::translate('http://www.skype.com/'),
                'title'         => OlympusZeus::translate('Skype'),
                'value'         => OlympusZeus::translate('Connect us on Skype'),
                'placeholder'   => OlympusZeus::translate('__username__'),
                'color'         => '#00aff0',
            ),
            'soundcloud'    => array(
                'url'           => OlympusZeus::translate('https://soundcloud.com/'),
                'title'         => OlympusZeus::translate('SoundCloud'),
                'value'         => OlympusZeus::translate('Follow me on Soundcloud'),
                'placeholder'   => OlympusZeus::translate('https://soundcloud.com/__username__'),
                'color'         => '#ff3300',
            ),
            'stumbleupon'   => array(
                'url'           => OlympusZeus::translate('http://www.stumbleupon.com/'),
                'title'         => OlympusZeus::translate('Stumbleupon'),
                'value'         => OlympusZeus::translate('Follow me on Stumbleupon'),
                'placeholder'   => OlympusZeus::translate('http://www.stumbleupon.com/stumbler/__username__'),
                'color'         => '#eb4924',
            ),
            'tumblr'        => array(
                'url'           => OlympusZeus::translate('https://www.tumblr.com/'),
                'title'         => OlympusZeus::translate('Tumblr'),
                'value'         => OlympusZeus::translate('Follow me on Tumblr'),
                'placeholder'   => OlympusZeus::translate('http://'),
                'color'         => '#35465c',
            ),
            'vimeo'         => array(
                'url'           => OlympusZeus::translate('http://www.vimeo.com/'),
                'title'         => OlympusZeus::translate('Vimeo'),
                'value'         => OlympusZeus::translate('Follow me on Vimeo'),
                'placeholder'   => OlympusZeus::translate('http://www.vimeo.com/__username__'),
                'color'         => '#1ab7ea',
            ),
            'vine'          => array(
                'url'           => OlympusZeus::translate('https://vine.co/'),
                'title'         => OlympusZeus::translate('Vine'),
                'value'         => OlympusZeus::translate('Follow me on Vine.co'),
                'placeholder'   => OlympusZeus::translate('https://vine.co/__username__'),
                'color'         => '#00b488',
            ),
            'vk'            => array(
                'url'           => OlympusZeus::translate('http://vk.com/'),
                'title'         => OlympusZeus::translate('VKontakte'),
                'value'         => OlympusZeus::translate('Follow me on VK.com'),
                'placeholder'   => OlympusZeus::translate('http://vk.com/__username__'),
                'color'         => '#45668e',
            ),
            'weibo'         => array(
                'url'           => OlympusZeus::translate('http://www.weibo.com/'),
                'title'         => OlympusZeus::translate('Weibo'),
                'value'         => OlympusZeus::translate('Follow me on Weibo'),
                'placeholder'   => OlympusZeus::translate('http://www.weibo.com/__username__'),
                'color'         => '#e6162d',
            ),
            'youtube'       => array(
                'url'           => OlympusZeus::translate('http://www.youtube.com/'),
                'title'         => OlympusZeus::translate('Youtube'),
                'value'         => OlympusZeus::translate('Follow me on Youtube'),
                'placeholder'   => OlympusZeus::translate('http://www.youtube.com/user/__username__'),
                'color'         => '#cd201f',
            ),
            'rss'           => array(
                'url'           => '',
                'title'         => OlympusZeus::translate('RSS'),
                'value'         => OlympusZeus::translate('Subscribe to my RSS feed'),
                'placeholder'   => OlympusZeus::translate('__rssfeed__'),
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
