<?php
/**
 * Plugin Name: Cat Picks
 * Description: Custom post type and featured by field for WJCT skills test.
 * Version: 1.0
 * Author: William Watts
 */

function cp_register_cat_picks_post_type() {

    $args = array(
        'public' => true,
        'label'  => 'Cat Picks',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );

    register_post_type('cat_picks', $args);
}

add_action('init', 'cp_register_cat_picks_post_type');

function cp_add_featured_by_meta_box() {
    add_meta_box(
        'cp_featured_by',
        'Featured By',
        'cp_featured_by_callback',
        'cat_picks',
        'side'
    );
}

add_action('add_meta_boxes', 'cp_add_featured_by_meta_box');

function cp_featured_by_callback($post) {
    $value = get_post_meta($post->ID, '_cp_featured_by', true);

    echo '<input type="text" name="cp_featured_by" value="' . esc_attr($value) . '" style="width:100%;">';
}

function cp_save_featured_by_meta($post_id) {

    if (isset($_POST['cp_featured_by'])) {
        update_post_meta(
            $post_id,
            '_cp_featured_by',
            sanitize_text_field($_POST['cp_featured_by'])
        );
    }
}

add_action('save_post', 'cp_save_featured_by_meta');

function cp_display_featured_by($content) {

    if (is_singular('cat_picks')) {

        $featured_by = get_post_meta(
            get_the_ID(),
            '_cp_featured_by',
            true
        );

        if ($featured_by) {
            $content .= '<p><strong>Featured By:</strong> ' . esc_html($featured_by) . '</p>';
        }
    }

    return $content;
}

add_filter('the_content', 'cp_display_featured_by');

