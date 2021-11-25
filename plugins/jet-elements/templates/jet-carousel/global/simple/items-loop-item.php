<?php
	/**
	 * Loop item template
	 */

	$target = $this->_loop_item( array( 'item_link_target' ), ' target="%s"' );
	$rel = $this->_loop_item( array( 'item_link_rel' ), ' rel="%s"' );
	$img = $this->get_advanced_carousel_img( 'jet-carousel__item-img' );

	$item_settings = $this->_processed_item;

	$content_type = ! empty( $item_settings['item_content_type'] ) ? $item_settings['item_content_type'] : 'default';

	$lightbox = 'data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="'.$this->get_id().'"';
	$settings = $this->get_settings_for_display();

?><div class="jet-carousel__item">
	<div class="jet-carousel__item-inner"><?php
		if ( $img ) {
			if ( $settings['item_link_type'] === 'link' ) {
				echo $this->_loop_item( array( 'item_link' ), '<a href="%s" class="jet-carousel__item-link"' . $target . $rel . ' >' );
			} else {
				printf( '<a href="%1$s" class="jet-carousel__item-link" %2$s>', $item_settings['item_image']['url'], $lightbox );
			}
			
			echo $img;

			if ( $settings['item_link_type'] === 'link' ) {
				echo $this->_loop_item( array( 'item_link' ), '</a>' );
			} else {
				printf( '</a>' );
			}
		}
		echo '<div class="jet-carousel__content">';
			switch ( $content_type ) {
				case 'default':
					$title  = $this->_loop_item( array( 'item_title' ) );
					$text   = $this->_loop_item( array( 'item_text' ), '<div class="jet-carousel__item-text">%s</div>' );
					$button = $this->_loop_button_item( array( 'item_link', 'item_button_text' ), '<a class="elementor-button elementor-size-md jet-carousel__item-button" href="%1$s"' . $target . $rel . '>%2$s</a>' );

					$link         = $this->_loop_item( array( 'item_link' ) );
					$title_format = '<%1$s class="jet-carousel__item-title">%2$s</%1$s>';

					if ( $link_title && $link ) {
						$title_format = '<%1$s class="jet-carousel__item-title"><a href="%3$s"%4$s%5$s>%2$s</a></%1$s>';
					}

					if ( $title || $text || $button ) {
						printf( $title_format, $title_tag, $title, esc_url( $link ), $target, $rel );
						echo $text;
						echo $button;
					}
					break;
				case 'template':
					echo $this->_loop_item_template_content();
					break;
			}
		echo '</div>';

?></div>
</div>
