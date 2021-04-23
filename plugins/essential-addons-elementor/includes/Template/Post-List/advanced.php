<?php

/**
 * Template Name: Advanced
 */

use Essential_Addons_Elementor\Pro\Classes\Helper;
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


$category = get_the_category();
$cat_name = $cat_id = null;
$show_cat = ($settings['eael_post_list_post_cat'] != '');
if (!empty($category[0])) {
    $cat_id = isset($category[0]->term_id) ? $category[0]->term_id : null;
    $cat_name = isset($category[0]->name) ? $category[0]->name : null;
}
$cat_is_ready = ($show_cat && $cat_name && $cat_id);
echo '<div class="eael-post-list-post ' . (has_post_thumbnail() ? '' : 'eael-empty-thumbnail') . '">';
echo ($settings['eael_post_list_layout_type'] == 'advanced' ? '<div class="eael-post-list-post-inner">' : '');
if ($settings['eael_post_list_post_feature_image'] === 'yes') {
    echo '<div class="eael-post-list-thumbnail ' . (has_post_thumbnail() ? '' : 'eael-empty-thumbnail') . '">';
    if (has_post_thumbnail()) {
        echo '<img src="' . wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['eael_post_featured_image_size']) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">';
    }
    echo '<a href="' . get_the_permalink() . '"></a>
            </div>';
}

echo '<div class="eael-post-list-content">';
if ($settings['eael_post_list_layout_type'] == 'default' && $cat_is_ready) {
    echo '<div class="meta-categories">
                    <a href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html($category[0]->name) . '</a>
                </div>';
}

if ($settings['eael_post_list_layout_type'] == 'advanced' && ($iterator == 8) && $cat_is_ready) {
    echo '<div class="boxed-meta">
                    <div class="meta-categories">
                        <a href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html($cat_name) . '</a>
                    </div>
                </div>';
}

if ($settings['eael_post_list_post_title'] == 'yes' && !empty($settings['eael_post_list_title_tag'])) {
	$validate_tag = Helper::eael_pro_validate_html_tag($settings['eael_post_list_title_tag']);
    echo "<{$validate_tag} class=\"eael-post-list-title\">";
    echo '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
    echo "</{$validate_tag}>";
}

if ($settings['eael_post_list_post_meta'] === 'yes') {
    echo '<div class="meta">
                    <span><i class="far fa-calendar-alt"></i> ' . get_the_date('d M Y') . '</span>
                </div>';
}

if ($settings['eael_post_list_post_excerpt'] === 'yes') {
    if ($settings['eael_post_list_layout_type'] == 'advanced') {
        echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_post_list_post_excerpt_length'], $settings['eael_post_list_excerpt_expanison_indicator']) . '</p>';
    }
}

if ( isset($settings['eael_show_read_more_button']) && $settings['eael_show_read_more_button'] ) {

    echo '<a href="' . get_the_permalink() . '" class="eael-post-elements-readmore-btn">' . esc_attr($settings['eael_post_list_read_more_text']) . '</a>';

}

if ($settings['eael_post_list_layout_type'] == 'advanced') {
    echo '<div class="boxed-meta">';
    if ($settings['eael_post_list_author_meta'] != '') {
        echo '<div class="author-meta">
                            <a href="' . get_author_posts_url(get_the_author_meta('ID')) . '" class="author-photo">
                                ' . get_avatar(get_the_author_meta('ID'), 100, false, get_the_title() . '-author') . '
                            </a>

                            <div class="author-info">
                                <h5>' . get_the_author_posts_link() . '</h5>
                                <a href="' . get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')) . '"><p>' . get_the_date('d.m.y') . '</p></a>
                            </div>
                        </div>';
    }

    if ($iterator != 8) {
        if ($cat_is_ready) {
            echo '<div class="meta-categories">
                                <div class="meta-cats-wrap">
                                    <a href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html($cat_name) . '</a>
                                </div>
                            </div>';
        }
    }
    echo '</div>';
}
echo '</div>';
echo ($settings['eael_post_list_layout_type'] == 'advanced' ? '</div>' : '');
echo '</div>';

$iterator++;
