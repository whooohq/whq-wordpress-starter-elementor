<?php
/**
 * Template Name: Default
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$classes = \Essential_Addons_Elementor\Pro\Traits\Dynamic_Filterable_Gallery::get_dynamic_gallery_item_classes();

if ($settings['eael_fg_grid_style'] == 'eael-hoverer') {
        echo '<div class="dynamic-gallery-item ' . esc_attr(implode(' ', $classes)) . '">
            <div class="dynamic-gallery-item-inner">
                <div class="dynamic-gallery-thumbnail">';

                    if(has_post_thumbnail()) {
                        echo '<img src="' . wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size']) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">';
                    }else {
                        echo '<img src="'.\Elementor\Utils::get_placeholder_image_src().'">';
                    }

                    if ('eael-none' !== $settings['eael_fg_grid_hover_style']) {
                        echo  '<div class="caption ' . esc_attr($settings['eael_fg_grid_hover_style']) . ' ">';
                            if ('true' == $settings['eael_fg_show_popup']) {
                                if ('media' == $settings['eael_fg_show_popup_styles']) {
                                    echo '<a href="' . wp_get_attachment_image_url(get_post_thumbnail_id(), 'full') . '" class="popup-media eael-magnific-link"></a>';
                                } elseif ('buttons' == $settings['eael_fg_show_popup_styles']) {
                                    echo '<div class="item-content">';
                                        if($settings['eael_show_hover_title']) {
                                            echo '<h2 class="title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
                                        }
                                        if($settings['eael_show_hover_excerpt']) {
                                            echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_post_excerpt'], '<a class="eael_post_excerpt_read_more" href="' . get_the_permalink() . '"> ' . $settings['eael_post_excerpt_read_more'] . '</a>') . '</p>';
                                        }
                                    echo '</div>';
                                    echo '<div class="buttons">';
                                        if (!empty($settings['eael_section_fg_zoom_icon'])) {
                                            if(has_post_thumbnail()) {
                                                echo  '<a href="' . wp_get_attachment_image_url(get_post_thumbnail_id(), 'full') . '" class="eael-magnific-link">';
                                            }else { // If there is no real image on this post/page then change of anchor tag with placeholder image src
                                                echo '<a href="'.\Elementor\Utils::get_placeholder_image_src().'" class="eael-magnific-link">';
                                            }
                                                if( isset($settings['eael_section_fg_zoom_icon']['url']) ) {
                                                    echo '<img class="eael-dnmcg-svg-icon" src="'.esc_url($settings['eael_section_fg_zoom_icon']['url']).'" alt="'.esc_attr(get_post_meta($settings['eael_section_fg_zoom_icon']['id'], '_wp_attachment_image_alt', true)).'" />';
                                                }else {
                                                    echo '<i class="' . esc_attr($settings['eael_section_fg_zoom_icon']) . '"></i>';
                                                }
                                            echo '</a>';
                                        }

                                        if (!empty($settings['eael_section_fg_link_icon'])) {
                                            echo  '<a href="' . get_the_permalink() . '">';
                                                if( isset($settings['eael_section_fg_link_icon']['url'])) {
                                                    echo '<img class="eael-dnmcg-svg-icon" src="'.esc_url($settings['eael_section_fg_link_icon']['url']).'" alt="'.esc_attr(get_post_meta($settings['eael_section_fg_link_icon']['id'], '_wp_attachment_image_alt', true)).'" />';
                                                }else {
                                                    echo '<i class="' . esc_attr($settings['eael_section_fg_link_icon']) . '"></i>';
                                                }
                                            echo '</a>';
                                        }
                                    echo '</div>';
                                }
                            }
                        echo '</div>';
                    }
                echo '</div>
            </div>
        </div>';
} else if ($settings['eael_fg_grid_style'] == 'eael-cards') {
    echo '<div class="dynamic-gallery-item ' . esc_attr(implode(' ', $classes)) . '">
        <div class="dynamic-gallery-item-inner">
            <div class="dynamic-gallery-thumbnail">';
                if(has_post_thumbnail()) {
                    echo '<img src="' . wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size']) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">';
                }else {
                    echo '<img src="'.\Elementor\Utils::get_placeholder_image_src().'">';
                }

                if ('media' == $settings['eael_fg_show_popup_styles'] && 'eael-none' == $settings['eael_fg_grid_hover_style']) {
                    if(has_post_thumbnail()) {
                        echo '<a href="' . wp_get_attachment_image_url(get_post_thumbnail_id(), 'full') . '" class="popup-only-media eael-magnific-link"></a>';
                    }else {
                        echo '<a href="'.\Elementor\Utils::get_placeholder_image_src().'" class="popup-only-media eael-magnific-link"></a>';
                    }
                }

                if ('eael-none' !== $settings['eael_fg_grid_hover_style']) {
                    if ('media' == $settings['eael_fg_show_popup_styles']) {
                        echo '<div class="caption media-only-caption">';
                    } else {
                        echo '<div class="caption ' . esc_attr($settings['eael_fg_grid_hover_style']) . ' ">';
                    }
                    if ('true' == $settings['eael_fg_show_popup']) {
                        if ('media' == $settings['eael_fg_show_popup_styles']) {
                            if(has_post_thumbnail()) { // If there is no real image on this post/page then change of anchor tag with placeholder image src
                                echo '<a href="' . wp_get_attachment_image_url(get_post_thumbnail_id(), 'full') . '" class="popup-media eael-magnific-link"></a>';
                            }else {
                                echo '<a href="'.\Elementor\Utils::get_placeholder_image_src().'" class="popup-media eael-magnific-link"></a>';
                            }
                        } elseif ('buttons' == $settings['eael_fg_show_popup_styles']) {
                            echo '<div class="buttons">';
                                if (!empty($settings['eael_section_fg_zoom_icon'])) {
                                    if( has_post_thumbnail() ) {
                                        echo  '<a href="' . wp_get_attachment_image_url(get_post_thumbnail_id(), 'full') . '" class="eael-magnific-link">';
                                    }else {
                                        echo  '<a href="'.\Elementor\Utils::get_placeholder_image_src().'" class="eael-magnific-link">';
                                    }
                                        if( isset($settings['eael_section_fg_zoom_icon']['url']) ) {
                                            echo '<img class="eael-dnmcg-svg-icon" src="'.esc_url($settings['eael_section_fg_zoom_icon']['url']).'" alt="'.esc_attr(get_post_meta($settings['eael_section_fg_zoom_icon']['id'], '_wp_attachment_image_alt', true)).'" />';
                                        }else {
                                            echo '<i class="' . esc_attr($settings['eael_section_fg_zoom_icon']) . '"></i>';
                                        }
                                    echo '</a>';
                                }

                                if (!empty($settings['eael_section_fg_link_icon'])) {
                                    echo  '<a href="' . get_the_permalink() . '">';
                                        if( isset($settings['eael_section_fg_link_icon']['url'])) {
                                            echo '<img class="eael-dnmcg-svg-icon" src="'.esc_url($settings['eael_section_fg_link_icon']['url']).'" alt="'.esc_attr(get_post_meta($settings['eael_section_fg_link_icon']['id'], '_wp_attachment_image_alt', true)).'" />';
                                        }else {
                                            echo '<i class="' . esc_attr($settings['eael_section_fg_link_icon']) . '"></i>';
                                        }
                                    echo '</a>';
                                }
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                }
            echo '</div>

            <div class="item-content">';
             if($settings['eael_show_hover_title']) {
                echo '<h2 class="title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
            } if($settings['eael_show_hover_excerpt']) {
                 echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_post_excerpt'], '<a class="eael_post_excerpt_read_more" href="' . get_the_permalink() . '"> ' . $settings['eael_post_excerpt_read_more'] . '</a>') . '</p>';
             }

                if (('buttons' == $settings['eael_fg_show_popup_styles']) && ('eael-none' == $settings['eael_fg_grid_hover_style'])) {
                    echo '<div class="buttons entry-footer-buttons">';
                        if (!empty($settings['eael_section_fg_zoom_icon'])) {
                            echo '<a href="' . wp_get_attachment_image_url(get_post_thumbnail_id(), 'full') . '" class="eael-magnific-link"><i class="' . esc_attr($settings['eael_section_fg_zoom_icon']) . '"></i></a>';
                        }
                        if (!empty($settings['eael_section_fg_link_icon'])) {
                            echo '<a href="' . get_the_permalink() . '"><i class="' . esc_attr($settings['eael_section_fg_link_icon']) . '"></i></a>';
                        }
                    echo '</div>';
                }
            echo '</div>
        </div>
    </div>';
}
