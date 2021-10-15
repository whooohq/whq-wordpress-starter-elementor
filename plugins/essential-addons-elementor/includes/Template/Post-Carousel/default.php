<?php
/**
 * Template Name: Default
 *
 */
use Essential_Addons_Elementor\Pro\Classes\Helper;
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if(isset($settings['title_tag'])){
	$settings['title_tag'] = Helper::eael_pro_validate_html_tag($settings['title_tag']);
}

echo '<div class="swiper-slide">';
if ( $settings['eael_post_carousel_preset_style'] === 'two' ) {
    echo '<article class="eael-grid-post eael-post-grid-column">
    <div class="eael-grid-post-holder">
        <div class="eael-grid-post-holder-inner">';

    if (  ( $settings['eael_show_image'] == '0' || $settings['eael_show_image'] == 'yes' ) && has_post_thumbnail() ) {
        echo '<div class="eael-entry-media eael-entry-medianone">';

	    if ($settings['eael_show_post_terms'] === 'yes') {
		    echo Helper::get_terms_as_list($settings['eael_post_terms'], $settings['eael_post_terms_max_length']);
	    }

        if ( isset( $settings['post_block_hover_animation'] ) && 'none' !== $settings['post_block_hover_animation'] ) {
            echo '<div class="eael-entry-overlay ' . ( $settings['post_block_hover_animation'] ) . '">';

            if ( isset( $settings['__fa4_migrated']['eael_post_grid_bg_hover_icon_new'] ) || empty( $settings['eael_post_grid_bg_hover_icon'] ) ) {
                echo '<i class="' . $settings['eael_post_grid_bg_hover_icon_new'] . '" aria-hidden="true"></i>';
            } else {
                echo '<i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>';
            }
            echo '<a href="' . get_the_permalink() . '"' . $settings['image_link_nofollow'] . '' . $settings['image_link_target_blank'] . '></a></div>';
        }
        
        echo '<div class="eael-entry-thumbnail">
                <img src="' . esc_url( wp_get_attachment_image_url( get_post_thumbnail_id(), $settings['image_size'] ) ) . '" alt="' . esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) . '">
                <a href="' . get_the_permalink() . '"' . $settings['image_link_nofollow'] . '' . $settings['image_link_target_blank'] . '></a>
             </div>';
        echo '</div>';

    }



    if ( $settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt'] ):
        echo '<div class="eael-entry-wrapper">';
        
        echo '<header class="eael-entry-header">';
        
        if ( $settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-header' ) {
            

            echo '<div class="eael-entry-meta">';
            if ( $settings['eael_show_date'] === 'yes' ) {
                echo '<span class="eael-meta-posted-on"><i class="far fa-clock"></i><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
            }
            if ( $settings['eael_show_post_terms'] === 'yes' ) {
                if ( $settings['eael_post_terms'] === 'category' ) {
                    $terms = get_the_category();
                }
                if ( $settings['eael_post_terms'] === 'tags' ) {
                    $terms = get_the_tags();
                }
                if ( !empty( $terms ) ) {
                    $html = '<ul class="post-meta-categories">';
                    $count = 0;
                    foreach ( $terms as $term ) {
                        if ( $count === intval( $settings['eael_post_terms_max_length'] ) ) {
                            break;
                        }
                        if ( $count === 0 ) {
                            $html .= '<li class="meta-cat-icon"><i class="far fa-folder-open"></i></li>';
                        }
                        $link = ( $settings['eael_post_terms'] === 'category' ) ? get_category_link( $term->term_id ) : get_tag_link( $term->term_id );
                        $html .= '<li>';
                        $html .= '<a href="' . esc_url( $link ) . '">';
                        $html .= $term->name;
                        $html .= '</a>';
                        $html .= '</li>';
                        $count++;
                    }
                    $html .= '</ul>';
                    echo $html;
                }
            }
            echo '</div>';


        }

        if ( $settings['eael_show_title'] ) {
            echo '<' . $settings['title_tag'] . ' class="eael-entry-title">';
            echo '<a class="eael-grid-post-link" href="' . get_permalink() . '" title="' . get_the_title() . '"' . $settings['title_link_nofollow'] . '' . $settings['title_link_target_blank'] . '>';
            if ( empty( $settings['eael_title_length'] ) ) {
                echo get_the_title();
            } else {
                echo implode( " ", array_slice( explode( " ", get_the_title() ), 0, $settings['eael_title_length'] ) );
            }
            echo '</a>';
            echo '</' . $settings['title_tag'] . '>';
        }
        
        echo '</header>';


        echo '</div>';

        echo '<div class="eael-entry-content">
        <div class="eael-grid-post-excerpt">';
        if ( $settings['eael_show_excerpt'] ) {
            if ( empty( $settings['eael_excerpt_length'] ) ) {
                echo '<p>' . strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ) . '</p>';
            } else {
                echo '<p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['eael_excerpt_length'], $settings['expanison_indicator'] ) . '</p>';
            }
        }
        if ( class_exists( 'WooCommerce' ) && $settings['post_type'] == 'product' ) {
            echo '<p class="eael-entry-content-btn">';
            woocommerce_template_loop_add_to_cart();
            echo '</p>';
        } else {
            echo '<div class="eael-post-elements-readmore-wrap"><a href="' . get_the_permalink() . '" class="eael-post-elements-readmore-btn"' . $settings['read_more_link_nofollow'] . '' . $settings['read_more_link_target_blank'] . '>' . esc_attr( $settings['read_more_button_text'] ) . '</a></div>';
        }
        echo '</div>
            </div>';

        if ( $settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-footer' ) {
            echo '<div class="eael-entry-footer-two">';

            echo '<div class="eael-entry-meta">';
            if ( $settings['eael_show_date'] === 'yes' ) {
                echo '<span class="eael-meta-posted-on"><i class="far fa-clock"></i><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
            }
            if ( $settings['eael_show_post_terms'] === 'yes' ) {
	            if ( $settings['eael_post_terms'] === 'category' ) {
		            $terms = get_the_category();
	            }

	            if ( $settings['eael_post_terms'] === 'tags' ) {
		            $terms = get_the_tags();
	            }

                if ( !empty( $terms ) ) {
                    $html = '<ul class="post-meta-categories">';
                    $count = 0;
                    foreach ( $terms as $term ) {
                        if ( $count === intval( $settings['eael_post_terms_max_length'] ) ) {
                            break;
                        }
                        if ( $count === 0 ) {
                            $html .= '<li class="meta-cat-icon"><i class="far fa-folder-open"></i></li>';
                        }
                        $link = ( $settings['eael_post_terms'] === 'category' ) ? get_category_link( $term->term_id ) : get_tag_link( $term->term_id );
                        $html .= '<li>';
                        $html .= '<a href="' . esc_url( $link ) . '">';
                        $html .= $term->name;
                        $html .= '</a>';
                        $html .= '</li>';
                        $count++;
                    }
                    $html .= '</ul>';
                    echo $html;
                }
            }
            echo '</div>';

            echo '</div>';
        }
    endif;
    echo '</div></div></article>';
} else if ( $settings['eael_post_carousel_preset_style'] === 'three' ) {

    echo '<article class="eael-grid-post eael-post-grid-column">
    <div class="eael-grid-post-holder">
        <div class="eael-grid-post-holder-inner">';

    if (  ( $settings['eael_show_image'] == '0' || $settings['eael_show_image'] == 'yes' ) && has_post_thumbnail() ) {
        echo '<div class="eael-entry-media eael-entry-medianone">';

	    if ($settings['eael_show_post_terms'] === 'yes') {
		    echo Helper::get_terms_as_list($settings['eael_post_terms'], $settings['eael_post_terms_max_length']);
	    }

        if ( isset( $settings['post_block_hover_animation'] ) && 'none' !== $settings['post_block_hover_animation'] ) {
            echo '<div class="eael-entry-overlay ' . ( $settings['post_block_hover_animation'] ) . '">';
            
            if ( isset( $settings['__fa4_migrated']['eael_post_grid_bg_hover_icon_new'] ) || empty( $settings['eael_post_grid_bg_hover_icon'] ) ) {
                echo '<i class="' . $settings['eael_post_grid_bg_hover_icon_new'] . '" aria-hidden="true"></i>';
            } else {
                echo '<i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>';
            }
            echo '<a href="' . get_the_permalink() . '"' . $settings['image_link_nofollow'] . '' . $settings['image_link_target_blank'] . '></a></div>';
        }
        
        echo '<div class="eael-entry-thumbnail">
                <img src="' . esc_url( wp_get_attachment_image_url( get_post_thumbnail_id(), $settings['image_size'] ) ) . '" alt="' . esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) . '">
                <a href="' . get_the_permalink() . '"></a>
            </div>';
        echo '</div>';

        if ( $settings['eael_show_date'] === 'yes' ) {
            echo '<span class="eael-meta-posted-on"><time datetime="' . get_the_date() . '"><span>' . get_the_date( 'd' ) . '</span>' . get_the_date( 'F' ) . '</time></span>';
        }
    }


    if ( $settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt'] ):
        echo '<div class="eael-entry-wrapper">';
        
        echo '<header class="eael-entry-header">';

        if ( $settings['eael_show_title'] ) {
            echo '<' . $settings['title_tag'] . ' class="eael-entry-title">';
            echo '<a class="eael-grid-post-link" href="' . get_permalink() . '" title="' . get_the_title() . '"' . $settings['title_link_nofollow'] . '' . $settings['title_link_target_blank'] . '>';
            if ( empty( $settings['eael_title_length'] ) ) {
                echo get_the_title();
            } else {
                echo implode( " ", array_slice( explode( " ", get_the_title() ), 0, $settings['eael_title_length'] ) );
            }
            echo '</a>';
            echo '</' . $settings['title_tag'] . '>';
        }

        echo '</header>';



        echo '</div>';


        echo '<div class="eael-entry-content">
            <div class="eael-grid-post-excerpt">';
            if ( $settings['eael_show_excerpt'] ) {
                if ( empty( $settings['eael_excerpt_length'] ) ) {
                    echo '<p>' . strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ) . '</p>';
                } else {
                    echo '<p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['eael_excerpt_length'], $settings['expanison_indicator'] ) . '</p>';
                }
            }
            if ( class_exists( 'WooCommerce' ) && $settings['post_type'] == 'product' ) {
                echo '<p class="eael-entry-content-btn">';
                woocommerce_template_loop_add_to_cart();
                echo '</p>';
            } else {
                echo '<div class="eael-post-elements-readmore-wrap"><a href="' . get_the_permalink() . '" class="eael-post-elements-readmore-btn"' . $settings['read_more_link_nofollow'] . '' . $settings['read_more_link_target_blank'] . '>' . esc_attr( $settings['read_more_button_text'] ) . '</a></div>';
            }
            echo '</div>
        </div>';



    endif;
    echo '</div></div></article>';
} else {
    echo '<article class="eael-grid-post eael-post-grid-column">
    <div class="eael-grid-post-holder">
        <div class="eael-grid-post-holder-inner">';


        if (  ( $settings['eael_show_image'] == '0' || $settings['eael_show_image'] == 'yes' ) && has_post_thumbnail() ) {
            echo '<div class="eael-entry-media eael-entry-medianone">';

	        if ($settings['eael_show_post_terms'] === 'yes') {
		        echo Helper::get_terms_as_list($settings['eael_post_terms'], $settings['eael_post_terms_max_length']);
	        }

            if ( isset( $settings['post_block_hover_animation'] ) && 'none' !== $settings['post_block_hover_animation'] ) {
                echo '<div class="eael-entry-overlay ' . ( $settings['post_block_hover_animation'] ) . '">';

                if ( isset( $settings['__fa4_migrated']['eael_post_grid_bg_hover_icon_new'] ) || empty( $settings['eael_post_grid_bg_hover_icon'] ) ) {
                    echo '<i class="' . $settings['eael_post_grid_bg_hover_icon_new'] . '" aria-hidden="true"></i>';
                } else {
                    echo '<i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>';
                }
                echo '<a href="' . get_the_permalink() . '"' . $settings['image_link_nofollow'] . '' . $settings['image_link_target_blank'] . '></a></div>';
            }
            
            echo '<div class="eael-entry-thumbnail">
                    <img src="' . esc_url( wp_get_attachment_image_url( get_post_thumbnail_id(), $settings['image_size'] ) ) . '" alt="' . esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) . '">
                    <a href="' . get_the_permalink() . '"></a>
                </div>';
            echo '</div>';
        }


    if ( $settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt'] ):
        echo '<div class="eael-entry-wrapper">';

        $metaMarkup = '';
        $metaMarkup .= '<div class="eael-entry-meta">';
        if ( $settings['eael_show_author'] === 'yes' ) {
            $metaMarkup .= '<span class="eael-posted-by">' . get_the_author_posts_link() . '</span>';
        }
        if ( $settings['eael_show_date'] === 'yes' ) {
            $metaMarkup .= '<span class="eael-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
        }
        $metaMarkup .= '</div>';

        // render
        echo '<header class="eael-entry-header">';

        if ( $settings['eael_show_title'] ) {
            echo '<' . $settings['title_tag'] . ' class="eael-entry-title">';
            echo '<a class="eael-grid-post-link" href="' . get_permalink() . '" title="' . get_the_title() . '"' . $settings['title_link_nofollow'] . '' . $settings['title_link_target_blank'] . '>';
            if ( empty( $settings['eael_title_length'] ) ) {
                echo get_the_title();
            } else {
                echo implode( " ", array_slice( explode( " ", get_the_title() ), 0, $settings['eael_title_length'] ) );
            }
            echo '</a>';
            echo '</' . $settings['title_tag'] . '>';
        }
        if ( $settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-header' ) {
            echo $metaMarkup;
        }
        echo '</header>';

        echo '</div>';


        echo '<div class="eael-entry-content">
            <div class="eael-grid-post-excerpt">';
            if ( $settings['eael_show_excerpt'] ) {
                if ( empty( $settings['eael_excerpt_length'] ) ) {
                    echo '<p>' . strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ) . '</p>';
                } else {
                    echo '<p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['eael_excerpt_length'], $settings['expanison_indicator'] ) . '</p>';
                }
            }
            if ( class_exists( 'WooCommerce' ) && $settings['post_type'] == 'product' ) {
                echo '<p class="eael-entry-content-btn">';
                woocommerce_template_loop_add_to_cart();
                echo '</p>';
            } else {
                echo '<div class="eael-post-elements-readmore-wrap"><a href="' . get_the_permalink() . '" class="eael-post-elements-readmore-btn"' . $settings['read_more_link_nofollow'] . '' . $settings['read_more_link_target_blank'] . '>' . esc_attr( $settings['read_more_button_text'] ) . '</a></div>';
            }
            echo '</div>
        </div>';


        if ( $settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-footer' ) {
            
            echo '<div class="eael-entry-footer">';
            if ( $settings['eael_show_avatar'] === 'yes' ) {
                echo '<div class="eael-author-avatar">
                    <a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_avatar( get_the_author_meta( 'ID' ), 96 ) . '</a>
                </div>';
            }
            echo '<div class="eael-entry-meta">';
            if ( $settings['eael_show_author'] === 'yes' ) {
                echo '<div class="eael-posted-by">' . get_the_author_posts_link() . '</div>';
            }
            if ( $settings['eael_show_date'] === 'yes' ) {
                echo '<div class="eael-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></div>';
            }
            echo '</div>';
            echo '</div>';

        }
    endif;
    echo '</div></div></article>';
}
echo '</div>';
