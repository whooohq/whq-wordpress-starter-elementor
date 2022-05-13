<?php

/**
 * Template Name: Preset 3
 */

use Elementor\Group_Control_Image_Size;
use Essential_Addons_Elementor\Pro\Classes\Helper;
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


$category = get_the_category();
if ( $settings['post_type'] === 'product' ) {
	$category = get_the_terms( get_the_ID(), 'product_cat' );
}

$cat_name = $cat_id = null;
$show_cat = ($settings['eael_post_list_post_cat'] != '');
if (!empty($category[0])) {
    $cat_id = isset($category[0]->term_id) ? $category[0]->term_id : null;
    $cat_name = isset($category[0]->name) ? $category[0]->name : null;
}
$cat_is_ready = ($show_cat && $cat_name && $cat_id);
$post_featured_image_url = Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id(),
	'eael_post_featured_image', $settings );

$img = '';
if ($settings['eael_post_list_post_feature_image'] === 'yes') {
	$img = 'style="background-image: url(' . $post_featured_image_url . ')"';
}

echo '<div class="eael-post-list-post ' . (has_post_thumbnail() ? '' : 'eael-empty-thumbnail') . '">';

		echo '<div class="eael-post-list-featured-inner" '.$img.'>';

        echo '<div class="eael-post-list-content">';
			if ($cat_is_ready) {
				echo '<div class="meta-categories">
                    <a href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html($cat_name) . '</a>
                </div>';
			}
			$validate = Helper::eael_pro_validate_html_tag($settings['eael_post_list_title_tag']);
            if ($settings['eael_post_list_post_title'] == 'yes' && !empty($settings['eael_post_list_title_tag'])) {
                echo "<{$validate} class=\"eael-post-list-title\">";
                    echo '<a href="' . get_the_permalink() . '"' . $link_settings['title_link_nofollow'] . '' . $link_settings['title_link_target_blank'] . '>' . get_the_title() . '</a>';
                echo "</{$validate}>";
            }

            if ($settings['eael_post_list_post_meta'] === 'yes') {
                echo '<div class="meta">
                    <span><i class="far fa-calendar-alt"></i> ' . get_the_date(get_option('date_format')) . '</span>
                </div>';
            }

            if ($settings['eael_post_list_post_excerpt'] === 'yes') {
                echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_post_list_post_excerpt_length'], $settings['eael_post_list_excerpt_expanison_indicator']) . '</p>';
            }

            if ( isset($settings['eael_show_read_more_button']) && $settings['eael_show_read_more_button'] ) {
        
                echo '<a href="' . get_the_permalink() . '" class="eael-post-elements-readmore-btn"' . $link_settings['read_more_link_nofollow'] . '' . $link_settings['read_more_link_target_blank'] . '>' . esc_attr($settings['eael_post_list_read_more_text']) . '</a>';
            
            }
        echo '</div>';
        echo '</div>';
echo '</div>';
