<?php
namespace Takeatea\TeaThemeOptions\Fields\Review;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA REVIEW FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'review',
 *     'title' => 'Give me your price...',
 *     'description' => 'Place your bets, no more bets!',
 *     'note' => true,
 *     'rate' => true,
 *     'all' => false,
 *     'authors' => array(1, 2, 3), //IDs of all users authorized to review
 *     'id' => 'my_review_field_id'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Gallery
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Gallery
 * @since 1.4.0
 *
 */
class Review extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.4.0
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
            $auth = 0;
        }
        else {
            //Modify content
            $content = $content['args']['contents'];
            $auth = $post->post_author;
        }

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Review', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';
        $note = isset($content['note']) ? $content['note'] : true;
        $rate = isset($content['rate']) ? $content['rate'] : true;
        $review_all = isset($content['all']) ? $content['all'] : false;
        $level = isset($content['level']) ? $content['level'] : '';

        //Define users
        $authors = get_users(array(
            'blog_id' => get_current_blog_id(),
            'role' => $level,
            'orderby' => 'role',
            'order' => 'ASC',
            'fields' => 'id',
        ));
        $current = get_current_user_id();

        //Check for authors
        if (empty($authors)) {
            return;
        }
        //Check again
        foreach ($authors as $author) {
            $users[] = $author;
        }
        //Last check
        if (empty($users)) {
            return;
        }

        //Default values
        $std = array(
            1 => array(
                'note' => 1,
                'rate' => '',
            ),
        );

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, $std);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, false);
            $vals = empty($vals) ? $std : (is_array($vals) ? $vals[0] : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Review/in_pages.tpl.php');
    }
}
