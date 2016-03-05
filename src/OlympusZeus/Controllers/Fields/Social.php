<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Social field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Social
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-social
 *
 */

class Social extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-globe';

    /**
     * @var string
     */
    protected $template = 'fields/social.html.twig';

    /**
     * Prepare HTML component.
     *
     * @param array $content
     * @param array $details
     *
     * @since 5.0.0
     */
    protected function getVars($content, $details = [])
    {
        //Build defaults
        $defaults = [
            'id' => '',
            'title' => Translate::t('Social networks'),
            'default' => [],
            'description' => '',
            'expandable' => false,
            'socials' => [],

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //texts
            't_check_all' => Translate::t('Un/select all options'),
            't_add_social' => Translate::t('Add a social network'),
            't_delete_all' => Translate::t('Delete all social networks'),
            't_socials' => Translate::t('Social networks'),
            't_socials_select' => Translate::t('Selection'),
            't_socials_description' => Translate::t('Choose the social networks you want to display'),
            't_close' => Translate::t('Close'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);
        $vars['count'] = count($vars['default']);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Works on socials
        $socials = $this->getSocialNetworks();

        //List everything
        foreach ($socials as $k => $sc) {
            $vars['socials'][$k] = $sc;
            $vars['socials'][$k]['active'] = false;

            //Check if key exists in vals
            if (array_key_exists($k, $vars['val'])) {
                $vars['socials'][$k]['active'] = true;
            }

            //Get color in RGB format
            $color = $this->hex2rgb($sc['color']);
            $vars['socials'][$k]['color'] = empty($color) ? '' : $color['r'].','.$color['g'].','.$color['b'];
        }

        //Works on values
        foreach ($vars['val'] as $k => $sc) {
            $vars['val'][$k]['title'] = $vars['socials'][$k]['title'];
            $vars['val'][$k]['color'] = $vars['socials'][$k]['color'];
        }

        //Update vars
        $this->getField()->setVars($vars);
    }

    /**
     * Return all available Social networks.
     *
     * @return array $socials
     *
     * @since 5.0.0
     */
    protected function getSocialNetworks()
    {
        return [
            'twitter'       => [
                'url'           => Translate::t('http://www.twitter.com/'),
                'title'         => Translate::t('Twitter'),
                'value'         => Translate::t('Follow me on Twitter'),
                'placeholder'   => Translate::t('http://www.twitter.com/__username__'),
                'color'         => '#55acee',
            ],
            'facebook'      => [
                'url'           => Translate::t('http://www.facebook.com/'),
                'title'         => Translate::t('Facebook'),
                'value'         => Translate::t('Follow me on Facebook'),
                'placeholder'   => Translate::t('http://www.facebook.com/__username__'),
                'color'         => '#3b5998',
            ],
            'instagram'     => [
                'url'           => Translate::t('http://www.instagram.com/'),
                'title'         => Translate::t('Instagram'),
                'value'         => Translate::t('Follow me on Instagram'),
                'placeholder'   => Translate::t('http://www.instagram.com/__username__'),
                'color'         => '#3f729b',
            ],
            'google-plus'   => [
                'url'           => Translate::t('http://plus.google.com/'),
                'title'         => Translate::t('Google+'),
                'value'         => Translate::t('Follow me on Google+'),
                'placeholder'   => Translate::t('http://plus.google.com/__username__'),
                'color'         => '#dc4e41',
            ],
            'linkedin'      => [
                'url'           => Translate::t('http://www.linkedin.com/'),
                'title'         => Translate::t('LinkedIn'),
                'value'         => Translate::t('Follow me on LinkedIn'),
                'placeholder'   => Translate::t('http://www.linkedin.com/in/__username__'),
                'color'         => '#0077b5',
            ],



            '500px'         => [
                'url'           => Translate::t('https://500px.com/'),
                'title'         => Translate::t('500px'),
                'value'         => Translate::t('See my portfolio on 500px'),
                'placeholder'   => Translate::t('https://500px.com/__username__'),
                'color'         => '#0099e5',
            ],
            'behance'       => [
                'url'           => Translate::t('http://www.behance.com/'),
                'title'         => Translate::t('Behance'),
                'value'         => Translate::t('See my portfolio on Behance'),
                'placeholder'   => Translate::t('http://www.behance.com/__username__'),
                'color'         => '#1769ff',
            ],
            'bitbucket'     => [
                'url'           => Translate::t('https://bitbucket.org/'),
                'title'         => Translate::t('Bitbucket'),
                'value'         => Translate::t('Tip me on Bitbucket'),
                'placeholder'   => Translate::t('https://bitbucket.org/__username__'),
                'color'         => '#205081',
            ],
            'codepen'       => [
                'url'           => Translate::t('http://codepen.io/'),
                'title'         => Translate::t('CodePen'),
                'value'         => Translate::t('Follow me on Codepen.io'),
                'placeholder'   => Translate::t('http://codepen.io/__username__'),
                'color'         => '#000000',
            ],
            'delicious'     => [
                'url'           => Translate::t('https://delicious.com/'),
                'title'         => Translate::t('Delicious'),
                'value'         => Translate::t('Follow me on Delicious'),
                'placeholder'   => Translate::t('https://delicious.com/__username__'),
                'color'         => '#3399ff',
            ],
            'deviantart'    => [
                'url'           => Translate::t('http://www.deviantart.com/'),
                'title'         => Translate::t('DeviantArt'),
                'value'         => Translate::t('Follow me on Deviantart'),
                'placeholder'   => Translate::t('http://__username__.deviantart.com/'),
                'color'         => '#05cc47',
            ],
            'dribbble'      => [
                'url'           => Translate::t('http://dribbble.com/'),
                'title'         => Translate::t('Dribbble'),
                'value'         => Translate::t('Follow me on Dribbble'),
                'placeholder'   => Translate::t('http://dribbble.com/__username__'),
                'color'         => '#ea4c89',
            ],
            'flickr'        => [
                'url'           => Translate::t('http://www.flickr.com/'),
                'title'         => Translate::t('FlickR'),
                'value'         => Translate::t('Follow me on Flickr'),
                'placeholder'   => Translate::t('http://www.flickr.com/photos/__username__'),
                'color'         => '#0063dc',
            ],
            'foursquare'    => [
                'url'           => Translate::t('https://www.foursquare.com/'),
                'title'         => Translate::t('4square'),
                'value'         => Translate::t('See me on Foursquare'),
                'placeholder'   => Translate::t('https://www.foursquare.com/__username__'),
                'color'         => '#f94877',
            ],
            'gittip'        => [
                'url'           => Translate::t('https://www.gittip.com/'),
                'title'         => Translate::t('Gittip'),
                'value'         => Translate::t('Follow me on Gittip'),
                'placeholder'   => Translate::t('https://www.gittip.com/__username__'),
                'color'         => '#663300',
            ],
            'github'        => [
                'url'           => Translate::t('https://github.com/'),
                'title'         => Translate::t('Github'),
                'value'         => Translate::t('Follow me on Github'),
                'placeholder'   => Translate::t('https://github.com/__username__'),
                'color'         => '#333333',
            ],
            'gratipay'      => [
                'url'           => Translate::t('https://gratipay.com/'),
                'title'         => Translate::t('Gratipay'),
                'value'         => Translate::t('Tip me Gratipay'),
                'placeholder'   => Translate::t('https://gratipay.com/__username__'),
                'color'         => '#6c2e00',
            ],
            'lastfm'        => [
                'url'           => Translate::t('http://www.lastfm.fr/'),
                'title'         => Translate::t('Last FM'),
                'value'         => Translate::t('Follow me on LastFM'),
                'placeholder'   => Translate::t('http://www.lastfm.fr/user/__username__'),
                'color'         => '#d51007',
            ],
            'pinterest'     => [
                'url'           => Translate::t('http://pinterest.com/'),
                'title'         => Translate::t('Pinterest'),
                'value'         => Translate::t('Follow me on Pinterest'),
                'placeholder'   => Translate::t('http://pinterest.com/__username__'),
                'color'         => '#bd081c',
            ],
            'reddit'        => [
                'url'           => Translate::t('http://www.reddit.com/'),
                'title'         => Translate::t('Reddit'),
                'value'         => Translate::t('Follow me on Reddit'),
                'placeholder'   => Translate::t('http://www.reddit.com/user/__username__'),
                'color'         => '#ff4500',
            ],
            'skype'         => [
                'url'           => Translate::t('http://www.skype.com/'),
                'title'         => Translate::t('Skype'),
                'value'         => Translate::t('Connect us on Skype'),
                'placeholder'   => Translate::t('__username__'),
                'color'         => '#00aff0',
            ],
            'soundcloud'    => [
                'url'           => Translate::t('https://soundcloud.com/'),
                'title'         => Translate::t('SoundCloud'),
                'value'         => Translate::t('Follow me on Soundcloud'),
                'placeholder'   => Translate::t('https://soundcloud.com/__username__'),
                'color'         => '#ff3300',
            ],
            'stumbleupon'   => [
                'url'           => Translate::t('http://www.stumbleupon.com/'),
                'title'         => Translate::t('Stumbleupon'),
                'value'         => Translate::t('Follow me on Stumbleupon'),
                'placeholder'   => Translate::t('http://www.stumbleupon.com/stumbler/__username__'),
                'color'         => '#eb4924',
            ],
            'tumblr'        => [
                'url'           => Translate::t('https://www.tumblr.com/'),
                'title'         => Translate::t('Tumblr'),
                'value'         => Translate::t('Follow me on Tumblr'),
                'placeholder'   => Translate::t('http://'),
                'color'         => '#35465c',
            ],
            'vimeo'         => [
                'url'           => Translate::t('http://www.vimeo.com/'),
                'title'         => Translate::t('Vimeo'),
                'value'         => Translate::t('Follow me on Vimeo'),
                'placeholder'   => Translate::t('http://www.vimeo.com/__username__'),
                'color'         => '#1ab7ea',
            ],
            'vine'          => [
                'url'           => Translate::t('https://vine.co/'),
                'title'         => Translate::t('Vine'),
                'value'         => Translate::t('Follow me on Vine.co'),
                'placeholder'   => Translate::t('https://vine.co/__username__'),
                'color'         => '#00b488',
            ],
            'vk'            => [
                'url'           => Translate::t('http://vk.com/'),
                'title'         => Translate::t('VKontakte'),
                'value'         => Translate::t('Follow me on VK.com'),
                'placeholder'   => Translate::t('http://vk.com/__username__'),
                'color'         => '#45668e',
            ],
            'weibo'         => [
                'url'           => Translate::t('http://www.weibo.com/'),
                'title'         => Translate::t('Weibo'),
                'value'         => Translate::t('Follow me on Weibo'),
                'placeholder'   => Translate::t('http://www.weibo.com/__username__'),
                'color'         => '#e6162d',
            ],
            'youtube'       => [
                'url'           => Translate::t('http://www.youtube.com/'),
                'title'         => Translate::t('Youtube'),
                'value'         => Translate::t('Follow me on Youtube'),
                'placeholder'   => Translate::t('http://www.youtube.com/user/__username__'),
                'color'         => '#cd201f',
            ],
            'rss'           => [
                'url'           => '',
                'title'         => Translate::t('RSS'),
                'value'         => Translate::t('Subscribe to my RSS feed'),
                'placeholder'   => Translate::t('__rssfeed__'),
                'color'         => '#f26522',
            ],
        ];
    }

    /**
     * Return RGB array from hex color.
     *
     * @param string $hexcolor
     * @return array $rgb
     *
     * @since 5.0.0
     */
    protected function hex2rgb($hexcolor) 
    {
        preg_match("/^#{0,1}([0-9a-f]{1,6})$/i", $hexcolor, $match);

        if (!isset($match[1])) {
            return [];
        }

        $color = $match[1];

        #91d04d
        if (6 == strlen($color)) {
            list($r, $g, $b) = [
                $color[0] . $color[1],
                $color[2] . $color[3],
                $color[4] . $color[5]
            ];
        }
        #aaa
        else if (3 == strlen($color)) {
            list($r, $g, $b) = [
                $color[0] . $color[0],
                $color[1] . $color[1],
                $color[2] . $color[2]
            ];
        }
        #bb
        else if (2 == strlen($color)) {
            list($r, $g, $b) = [
                $color[0] . $color[1],
                $color[0] . $color[1],
                $color[0] . $color[1]
            ];
        }
        #c
        else if (1 == strlen($color)) {
            list($r, $g, $b) = [
                $color . $color,
                $color . $color,
                $color . $color
            ];
        }
        else {
            return [];
        }

        return [
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b),
        ];
    }
}
