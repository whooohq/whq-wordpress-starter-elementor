<?php
	/**
	 * Loop item template
	 */
	$settings  = $this->get_settings_for_display();
	$target    = $this->_loop_item( array( 'item_link_target' ), ' target="%s"' );
	$rel       = $this->_loop_item( array( 'item_link_rel' ), ' rel="%s"' );

	$item_settings = $this->_processed_item;

	$content_type = ! empty( $item_settings['item_content_type'] ) ? $item_settings['item_content_type'] : 'default';

	$img      = $this->get_advanced_carousel_img( 'jet-banner__img' );

	if( 'true' === $settings['hide_items_without_image'] && empty( $img ) ){
		return;
	}

	$lightbox = 'data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . $this->get_id() . '"';
	$settings = $this->get_settings_for_display();
?>
<div class="jet-carousel__item">
	<div class="jet-carousel__item-inner">
	<figure class="jet-banner jet-effect-<?php echo esc_attr( $this->get_settings_for_display( 'animation_effect' ) ); ?>"><?php

			if ( $item_settings['item_content_type'] === 'default' ) {
				if ( $settings['item_link_type'] === 'lightbox' && $img ) {

					$show_lightbox_title = isset( $settings['lightbox_show_title' ] ) ? $settings['lightbox_show_title'] : "false";
					$show_lightbox_desc  = isset( $settings['lightbox_show_desc' ] ) ? $settings['lightbox_show_desc'] : "false";
					$lightbox_title = '';
					$lightbox_desc = '';

					if ( "true" === $show_lightbox_title ) {
						$lightbox_title = 'data-elementor-lightbox-title="' . $this->_loop_item( array( 'item_title' ) ) . '"';
					}

					if ( "true" === $show_lightbox_desc ) {
						$lightbox_desc = 'data-elementor-lightbox-description="' . wp_strip_all_tags( $this->_loop_item( array( 'item_text' ) ) ) . '"';
					}

					printf( '<a href="%1$s" class="jet-banner__link" %2$s %3$s %4$s>', $item_settings['item_image']['url'], $lightbox, $lightbox_title, $lightbox_desc );
				} else {
					echo $this->_loop_item( array( 'item_link' ), '<a href="%s" class="jet-banner__link"' . $target . $rel . '>' );
				}
			}

			echo '<div class="jet-banner__overlay"></div>';
			echo $img;
			echo '<figcaption class="jet-banner__content">';
				echo '<div class="jet-banner__content-wrap">';
					switch ( $content_type ) {
						case 'default':
							echo $this->_loop_item( array( 'item_title' ), '<' . $title_tag . ' class="jet-banner__title">%s</' . $title_tag . '>' );
							echo $this->_loop_item( array( 'item_text' ), '<div class="jet-banner__text">%s</div>' );
							break;
						case 'template':
							echo $this->_loop_item_template_content();
							break;
					}
				echo '</div>';
			echo '</figcaption>';

		if ( $item_settings['item_content_type'] === 'default' ) {
			if ( $settings['item_link_type'] === 'lightbox' && $img ) {
				printf( '</a>' );
			} else {
				echo $this->_loop_item( array( 'item_link' ), '</a>' );
			}
		}
	?></figure>
	</div>
</div>