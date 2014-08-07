<?php
namespace Takeatea\TeaThemeOptions\Fields\Radio;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA RADIO FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'radio',
 *     'title' => 'Ok ok... But what is your favorite?',
 *     'id' => 'my_radio_field_id',
 *     'std' => 'minions',
 *     'mode' => 'image',
 *     'description' => '- "Bapouet?" - "Na na na, baapouet!" - "AAAAAAAAAA Bapoueeeeettttt!!!!"',
 *     'options' => array(
 *         'minions' => 'The Minions',
 *         'lapinscretins' => 'The Lapins Crétins'
 *     )
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Radio
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Radio
 * @since 1.4.0
 *
 */
class Radio extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    public function __construct(){}


    //------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     *
     * @since 1.4.0
     */
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Check if an id is defined at least
        if (empty($post)) {
            //Check if an id is defined at least
            $this->checkId($content);
        }
        else {
            //Modify content
            $content = $content['args']['contents'];
        }

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Radio', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';
        $std = isset($content['std']) ? $content['std'] : '';
        $mode = isset($content['mode']) ? $content['mode'] : '';
        $options = isset($content['options']) ? $content['options'] : array();

        //Default way
        if (empty($post)) {
            //Check selected
            $val = TeaThemeOptions::get_option($prefix.$id, $std);
        }
        //On CPT
        else {
            //Check selected
            $val = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
        }

        //Get template
        include(TTO_PATH . '/Fields/Radio/in_pages.tpl.php');
    }
}
