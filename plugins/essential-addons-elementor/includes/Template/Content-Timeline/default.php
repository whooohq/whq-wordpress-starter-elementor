<?php

/**
 * Template Name: Default
 *
 */
use Essential_Addons_Elementor\Pro\Classes\Helper;
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


echo '<div class="eael-content-timeline-block">
    <div class="eael-content-timeline-line">
        <div class="eael-content-timeline-inner"></div>
    </div>
    <div class="eael-content-timeline-img eael-picture '.(('bullet' === $settings['eael_show_image_or_icon']) ? 'eael-content-timeline-bullet': '').'">';
    
        if( "img" === $settings["eael_show_image_or_icon"] ) {
            echo '<img src="'. esc_url( $settings['eael_icon_image']['url'] ).'" alt="'.esc_attr(get_post_meta($settings['eael_icon_image']['id'], '_wp_attachment_image_alt', true)).'">';
        }

        if( 'icon' === $settings['eael_show_image_or_icon'] ) {
            if( isset($settings['eael_content_timeline_circle_icon']['url'])) {
                echo '<img class="content-timeline-bullet-svg" src="'.esc_attr( $settings['eael_content_timeline_circle_icon']['url'] ).'" alt="'.esc_attr(get_post_meta($settings['eael_content_timeline_circle_icon']['id'], '_wp_attachment_image_alt', true)).'"/>';
            }else {
                echo '<i class="'.esc_attr( $settings['eael_content_timeline_circle_icon'] ).'"></i>';
            }
        }
    echo '</div>';

    echo '<div class="eael-content-timeline-content">';
        if( 'yes' == $settings['eael_show_title'] ) {
            echo '<'.Helper::eael_pro_validate_html_tag($settings['title_tag']).' class="eael-timeline-title"><a href="'.esc_url( get_the_permalink() ).'"' . $settings['title_link_nofollow'] . '' . $settings['title_link_target_blank'] .'>'.get_the_title().'</a></'.Helper::eael_pro_validate_html_tag($settings['title_tag']).'>';
        }

        if( 'yes' == $settings['eael_show_excerpt'] ) {
            if(empty($settings['eael_excerpt_length'])) {
                echo '<p>'.strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()).'</p>';
            }else {
                echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_excerpt_length'], $settings['expanison_indicator']) . '</p>';
            }
        }

        if( 'yes' == $settings['eael_show_read_more'] && !empty( $settings['eael_read_more_text'] ) ) {
            echo '<a href="'.esc_url( get_the_permalink() ).'" class="eael-read-more"' . $settings['read_more_link_nofollow'] . '' . $settings['read_more_link_target_blank'] .'>'.esc_html__( $settings['eael_read_more_text'], 'essential-addons-elementor' ).'</a>';
        }

        echo '<span class="eael-date">'.get_the_date().'</span>';
echo '</div></div>';
