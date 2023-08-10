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
$cat_name_2 = $cat_id_2 = null;
$cat_name_3 = $cat_id_3 = null;

$show_cat = ($settings['eael_post_list_post_cat'] != '');
$max_cat_length = ! empty ( $settings['eael_post_list_post_cat_max_length'] ) ? intval( $settings['eael_post_list_post_cat_max_length'] ) : 1;
$cat_separator = ! empty ( $settings['eael_post_list_post_cat_separator'] ) ? esc_html( $settings['eael_post_list_post_cat_separator'] ) : '';

if ( !is_wp_error($category) ) {
    if( ! empty( $category[0] ) ){
        $cat_id = isset($category[0]->term_id) ? $category[0]->term_id : null;
        $cat_name = isset($category[0]->name) ? $category[0]->name : null;
    }
    
    if( ! empty( $category[1] ) ){
        $cat_id_2 = isset($category[1]->term_id) ? $category[1]->term_id : null;
        $cat_name_2 = isset($category[1]->name) ? $category[1]->name : null;
    }
    
    if( ! empty( $category[2] ) ){
        $cat_id_3 = isset($category[2]->term_id) ? $category[2]->term_id : null;
        $cat_name_3 = isset($category[2]->name) ? $category[2]->name : null;
    }
}
$cat_is_ready = ($show_cat && $cat_name && $cat_id);
$separator_1 = ! empty( $cat_id_2 ) ? $cat_separator : '';
$separator_2 = ! empty( $cat_id_3 ) ? $cat_separator : '';

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
				echo '<div class="meta-categories">';
                    echo '<a href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html($cat_name . $separator_1) . '</a>';       
                                            
                    if( $cat_id_2 && ( 2 === $max_cat_length || 3 === $max_cat_length ) ){
                        echo '<a href="' . esc_url(get_category_link($cat_id_2)) . '">' . esc_html($cat_name_2 . $separator_2) . '</a>';
                    }
            
                    if( $cat_id_3 && 3 === $max_cat_length ){
                        echo '<a href="' . esc_url(get_category_link($cat_id_3)) . '">' . esc_html($cat_name_3) . '</a>';
                    }
                echo '</div>';
			}
			$validate = Helper::eael_pro_validate_html_tag($settings['eael_post_list_title_tag']);
            if ($settings['eael_post_list_post_title'] == 'yes' && !empty($settings['eael_post_list_title_tag'])) {
                echo "<{$validate} class=\"eael-post-list-title\">";
                    echo '<a href="' . get_the_permalink() . '"' . $link_settings['title_link_nofollow'] . '' . $link_settings['title_link_target_blank'] . '>' . esc_html( get_the_title() ) . '</a>';
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
