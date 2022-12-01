<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Img_Gallery' ) ) {

	/**
	 * Define Jet_Engine_Img_Gallery class
	 */
	class Jet_Engine_Img_Gallery {

		/**
		 * Render images gallery as slider
		 *
		 * @param  array  $images [description]
		 * @param  array  $args   [description]
		 * @return [type]         [description]
		 */
		public static function slider( $images = array(), $args = array() ) {

			if ( empty( $images ) ) {
				return '';
			}

			if ( wp_doing_ajax() ) {
				jet_engine()->frontend->register_listing_deps();
			}

			wp_enqueue_script( 'jquery-slick' );
			wp_enqueue_script( 'imagesloaded' );
			jet_engine()->frontend->frontend_scripts();

			$args = wp_parse_args( $args, array(
				'size'             => 'full',
				'lightbox'         => false,
				'slides_to_show'   => 1,
				'slides_to_show_t' => false,
				'slides_to_show_m' => false,
			) );

			$slider_atts =  array(
				'slidesToShow'   => $args['slides_to_show'],
				'dots'           => false,
				'slidesToScroll' => 1,
				'adaptiveHeight' => true,
				'prevArrow'      => '<div class="prev-arrow jet-engine-arrow slick-arrow"><svg class="svg-inline--fa fa-angle-left fa-w-8" style="" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="angle-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" data-fa-i2svg=""><path fill="currentColor" d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z"></path></svg></div>',
				'nextArrow'      => '<div class="next-arrow jet-engine-arrow slick-arrow"><svg class="svg-inline--fa fa-angle-right fa-w-8" style="" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" data-fa-i2svg=""><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg></div>',
				'rtl'            => is_rtl(),
			);

			$mobile_settings = apply_filters( 'jet-engine/gallery/slider/mobile-settings', array(
				'slides_to_show_t' => 1025,
				'slides_to_show_m' => 768,
			) );

			foreach ( $mobile_settings as $key => $breakpoint ) {

				if ( ! empty( $args[ $key ] ) ) {

					if ( ! isset( $slider_atts['responsive'] ) ) {
						$slider_atts['responsive'] = array();
					}

					$slider_atts['responsive'][] = array(
						'breakpoint' => $breakpoint,
						'settings'   => array(
							'slidesToShow' => $args[ $key ],
						),
					);

				}
			}

			$slider_atts = apply_filters( 'jet-engine/gallery/slider/atts', $slider_atts );
			$slider_atts = htmlspecialchars( json_encode( $slider_atts ) );

			echo '<div class="jet-engine-gallery-slider" data-atts="' . $slider_atts . '">';

			$gallery_id = self::get_gallery_id();

			foreach ( $images as $img_id ) {

				$img_data = self::get_img_data( $img_id, $args );
				$img_url  = $img_data['url'];
				$img_full = $img_data['full'];

				echo '<div class="jet-engine-gallery-slider__item">';

				if ( $args['lightbox'] ) {
					echo '<a href="' . $img_full . '" class="jet-engine-gallery-slider__item-wrap jet-engine-gallery-item-wrap is-lightbox" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . $gallery_id . '">';
				} else {
					echo '<span class="jet-engine-gallery-slider__item-wrap jet-engine-gallery-item-wrap">';
				}

				$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );

				echo '<img src="' . $img_url . '" alt="' . $alt . '" class="jet-engine-gallery-slider__item-img">';

				if ( $args['lightbox'] ) {
					echo '</a>';
				} else {
					echo '</span>';
				}

				echo '</div>';

			}

			echo '</div>';

		}

		/**
		 * Ensure slider JS is enqueued.
		 *
		 * @param  string $content
		 * @return string
		 */
		public static function ensure_slider_js( $content ) {
			ob_start();

			jet_engine()->frontend->register_listing_deps();

			wp_scripts()->done[] = 'jquery';
			wp_scripts()->print_scripts( 'jquery-slick' );
			wp_scripts()->print_scripts( 'imagesloaded' );

			return $content . ob_get_clean();
		}

		/**
		 * Render images gallery as grid
		 *
		 * @param  array   $images   [description]
		 * @param  string  $size     [description]
		 * @param  boolean $lightbox [description]
		 * @return string
		 */
		public static function grid( $images = array(), $args = array() ) {

			if ( empty( $images ) ) {
				return '';
			}

			$args = wp_parse_args( $args, array(
				'size'        => 'full',
				'lightbox'    => false,
				'cols_desk'   => 3,
				'cols_tablet' => 3,
				'cols_mobile' => 1,
			) );

			ob_start();

			$classes = array(
				'grid-col-desk-' . $args['cols_desk'],
				'grid-col-tablet-' . $args['cols_tablet'],
				'grid-col-mobile-' . $args['cols_mobile'],
			);
			$classes = sprintf( ' %s', implode( ' ', $classes ) );

			echo '<div class="jet-engine-gallery-grid' . $classes . '">';

			$gallery_id = self::get_gallery_id();

			foreach ( $images as $img_id ) {

				$img_data = self::get_img_data( $img_id, $args );
				$img_url  = $img_data['url'];
				$img_full = $img_data['full'];

				echo '<div class="jet-engine-gallery-grid__item">';

				if ( $args['lightbox'] ) {
					echo '<a href="' . $img_full . '" class="jet-engine-gallery-grid__item-wrap jet-engine-gallery-item-wrap is-lightbox" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . $gallery_id . '">';
				} else {
					echo '<span class="jet-engine-gallery-grid__item-wrap jet-engine-gallery-item-wrap">';
				}

				$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );

				echo '<img src="' . $img_url . '" alt="' . $alt . '" class="jet-engine-gallery-grid__item-img">';

				if ( $args['lightbox'] ) {
					echo '</a>';
				} else {
					echo '</span>';
				}

				echo '</div>';

			}

			echo '</div>';

			return ob_get_clean();

		}

		public static function get_img_data( $img_id = null, $args = array() ) {

			$result = array();

			if ( is_numeric( $img_id ) ) {
				if ( 'full' === $args['size'] ) {
					$result['url'] = $result['full'] = wp_get_attachment_image_url( $img_id, $args['size'] );
				} else {
					$result['url']  = wp_get_attachment_image_url( $img_id, $args['size'] );
					$result['full'] = wp_get_attachment_image_url( $img_id, 'full' );
				}
			} elseif ( is_array( $img_id ) ) {
				$result['url']  = wp_get_attachment_image_url( $img_id['id'], $args['size'] );
				$result['full'] = $img_id['url'];
			} else {
				$result['url'] = $result['full'] = $img_id;
			}

			return $result;

		}

		/**
		 * Returns random ID for gallery
		 *
		 * @return [type] [description]
		 */
		public static function get_gallery_id() {
			return 'gallery_' . rand( 1000, 9999 );
		}

	}

}
