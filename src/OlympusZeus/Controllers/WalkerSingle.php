<?php

namespace crewstyle\OlympusZeus\Controllers;

/**
 * Gets its own Walker.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\WalkerSingle
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 */

if (!class_exists('Walker')) {
    require_once(ABSPATH.'wp-includes/class-wp-walker.php');
}

class WalkerSingle extends \Walker
{
    /**
     * @var string
     */
    public $tree_type = 'category';

    /**
     * @var array
     */
    public $db_fields = [
        'id' => 'term_id',
        'parent' => 'parent',
    ];

    /**
     * Starts the list before the elements are added.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of category. Used for tab indentation.
     * @param array  $args   An array of arguments. @see wp_terms_checklist()
     *
     * @since 3.3.0
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent<ul class='children'>\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of category. Used for tab indentation.
     * @param array  $args   An array of arguments. @see wp_terms_checklist()
     *
     * @since 3.3.0
     */
    public function end_lvl(&$output, $depth = 0, $args = [])
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start the element output.
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category The current term object.
     * @param int    $depth    Depth of the term in reference to parents. Default 0.
     * @param array  $args     An array of arguments. @see wp_terms_checklist()
     * @param int    $id       ID of the current term.
     *
     * @since 3.3.0
     */
    public function start_el(&$output, $category, $depth = 0, $args = [], $id = 0)
    {
        $taxonomy = empty($args['taxonomy']) ? 'category' : $args['taxonomy'];
        $name = 'category' === $taxonomy ? 'post_category' : 'tax_input['.$taxonomy.']';

        $args['popular_cats'] = empty($args['popular_cats']) ? [] : $args['popular_cats'];
        $class = in_array($category->term_id, $args['popular_cats']) ? ' class="popular-category"' : '';
        $args['selected_cats'] = empty($args['selected_cats']) ? [] : $args['selected_cats'];

        if (!empty($args['list_only'])) {
            $aria_cheched = 'false';
            $inner_class = 'category';

            if (in_array($category->term_id, $args['selected_cats'])) {
                $inner_class .= ' selected';
                $aria_cheched = 'true';
            }

            /** This filter is documented in wp-includes/category-template.php */
            $output .= "\n".'<li'.$class.'>'.
                '<div class="'.$inner_class.'" data-term-id='.$category->term_id.
                ' tabindex="0" role="checkbox" aria-checked="'.$aria_cheched.'">'.
                esc_html($category->name).'</div>';
        }
        else {
            /** This filter is documented in wp-includes/category-template.php */
            $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>".
                '<label class="selectit"><input value="'.$category->term_id.
                '" type="radio" name="'.$name.'" id="in-'.$taxonomy.'-'.$category->term_id.'"'.
                checked(in_array($category->term_id, $args['selected_cats']), true, false).
                disabled(empty($args['disabled']), false, false).' /> '.
                esc_html($category->name).'</label>';
        }
    }

    /**
     * Ends the element output, if needed.
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category The current term object.
     * @param int    $depth    Depth of the term in reference to parents. Default 0.
     * @param array  $args     An array of arguments. @see wp_terms_checklist()
     *
     * @since 3.3.0
     */
    public function end_el(&$output, $category, $depth = 0, $args = [])
    {
        $output .= "</li>\n";
    }
}
