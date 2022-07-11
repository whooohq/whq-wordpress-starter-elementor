<?php
/**
 * Elementor views manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Render_Dynamic_Image' ) ) {

	class Jet_Engine_Render_Dynamic_Image extends Jet_Engine_Render_Base {

		private $source     = false;
		private $show_field = true;

		public function get_name() {
			return 'jet-listing-dynamic-image';
		}

		public function default_settings() {
			return array(
				'dynamic_image_source'        => 'post_thumbnail',
				'image_url_prefix'            => '',
				'dynamic_image_size'          => 'full',
				'dynamic_avatar_size'         => 50,
				'dynamic_image_source_custom' => '',
				'linked_image'                => true,
				'image_link_source'           => '_permalink',
				'link_url_prefix'             => '',
				'open_in_new'                 => false,
				'hide_if_empty'               => false,
				'object_context'              => 'default_object'
			);
		}

		/**
		 * Render image
		 *
		 * @return [type] [description]
		 */
		public function render_image( $settings ) {

			$listing_source = jet_engine()->listings->data->get_listing_source();
			$source         = isset( $settings['dynamic_image_source'] ) ? $settings['dynamic_image_source'] : 'post_thumbnail';
			$custom         = isset( $settings['dynamic_image_source_custom'] ) ? $settings['dynamic_image_source_custom'] : false;

			if ( ! $source && ! $custom ) {
				return;
			}

			$object_context = isset( $settings['object_context'] ) ? $settings['object_context'] : false;
			$size           = $this->get_image_size( $settings );

			if ( $custom ) {
				$this->render_image_by_meta_field( $custom, $size, $settings );
				return;
			}

			if ( 'post_thumbnail' === $source ) {

					$post = jet_engine()->listings->data->get_object_by_context( $object_context );

					if ( ! $post ) {
						$post = jet_engine()->listings->data->get_current_object();
					}

					if ( ! $post || 'WP_Post' !== get_class( $post ) ) {
						return $this->process_fallback_image( $settings );
					}

					if ( ! has_post_thumbnail( $post->ID ) ) {
						return $this->process_fallback_image( $settings );
					}

					$thumbnail_id = get_post_thumbnail_id( $post );

					echo get_the_post_thumbnail( $post->ID, $size, array( 'alt' => $this->get_image_alt( $thumbnail_id ) ) );

					return;

			} elseif ( 'user_avatar' === $source ) {

				$user = jet_engine()->listings->data->get_object_by_context( $object_context );

				if ( ! $user ) {
					$user = jet_engine()->listings->data->get_current_object();
				}

				$size = ! empty( $settings['dynamic_avatar_size'] ) ? $settings['dynamic_avatar_size'] : array( 'size' => 50 );
				$size = ! empty( $size['size'] ) ? $size['size'] : 50;

				if ( $user && 'WP_User' === get_class( $user ) ) {
					echo str_replace( 'avatar ', 'jet-avatar ', get_avatar( $user->ID, $size ) );
				} elseif ( $user && 'WP_User' !== get_class( $user ) && is_user_logged_in() ) {
					$user = wp_get_current_user();
					echo str_replace( 'avatar ', 'jet-avatar ', get_avatar( $user->ID, $size ) );
				} else {
					return $this->process_fallback_image( $settings );
				}

			} elseif ( 'options_page' === $source ) {

				$option = ! empty( $settings['dynamic_field_option'] ) ? $settings['dynamic_field_option'] : false;
				$image  = jet_engine()->listings->data->get_option( $option );

				if ( ! $image ) {
					return $this->process_fallback_image( $settings );
				} else {

					$image_data = Jet_Engine_Tools::get_attachment_image_data_array( $image, 'id' );
					$image      = $image_data['id'];

					echo wp_get_attachment_image( $image, $size, false, array( 'alt' => $this->get_image_alt( $image ) ) );
				}

			} else {
				$this->render_image_by_meta_field( $source, $size, $settings );
			}

		}

		/**
		 * Process image fallback if set or hide widget
		 *
		 * @param  array  $settings [description]
		 * @return [type]           [description]
		 */
		public function process_fallback_image( $settings = array() ) {

			$size = $this->get_image_size( $settings );

			if ( ! empty( $settings['hide_if_empty'] ) ) {
				$this->show_field = false;
			} elseif ( ! empty( $settings['fallback_image'] ) ) {

				$attachment_id = is_array( $settings['fallback_image'] ) ? $settings['fallback_image']['id'] : $settings['fallback_image'];

				if ( empty( $attachment_id ) ) {
					return;
				}

				echo wp_get_attachment_image( $attachment_id, $size );

			}

		}

		public function render_image_by_meta_field( $field = null, $size = 'full', $settings = array() ) {

			$custom_output = apply_filters(
				'jet-engine/listings/dynamic-image/custom-image',
				false,
				$this->get_settings(),
				$size
			);

			if ( $custom_output ) {
				echo $custom_output;
				return;
			}

			$image = false;

			$object_context = isset( $settings['object_context'] ) ? $settings['object_context'] : false;

			if ( jet_engine()->relations->legacy->is_relation_key( $field ) ) {
				$related_post = get_post_meta( get_the_ID(), $field, false );
				if ( ! empty( $related_post ) ) {
					$related_post = $related_post[0];
					if ( has_post_thumbnail( $related_post ) ) {
						$image = get_post_thumbnail_id( $related_post );
					}
				}
			} else {
				$image = jet_engine()->listings->data->get_meta(
					$field,
					jet_engine()->listings->data->get_object_by_context( $object_context )
				);
			}

			if ( is_array( $image ) && isset( $image['url'] ) ) {

				if ( $size && 'full' !== $size ) {
					$image = $image['id'];
				} else {
					$image = $image['url'];
				}

			} elseif ( is_array( $image ) ) {
				$image = array_values( $image );
				$image = $image[0];
			}

			if ( ! $image ) {
				return $this->process_fallback_image( $settings );
			}

			if ( ! empty( $settings['image_url_prefix'] ) ) {
				$image = $settings['image_url_prefix'] . $image;
			}

			if ( filter_var( $image, FILTER_VALIDATE_URL ) ) {
				printf( '<img src="%1$s" alt="%2$s">', $image, get_the_title() );
			} else {
				echo wp_get_attachment_image( $image, $size, false, array( 'alt' => $this->get_image_alt( $image ) ) );
			}

		}

		public function get_image_size( $settings = array() ) {
			$size = isset( $settings['dynamic_image_size'] ) ? $settings['dynamic_image_size'] : 'full';

			return apply_filters( 'jet-engine/listings/dynamic-image/size', $size, 'dynamic_image', $settings );
		}

		public function get_image_alt( $img_id ) {
			$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );

			if ( ! $alt ) {
				$alt = get_the_title();
			}

			return $alt;
		}

		public function get_image_url( $settings ) {

			$is_linked = $this->get( 'linked_image' );

			if ( ! $is_linked ) {
				return false;
			}

			$source = ! empty( $settings['image_link_source'] ) ? $settings['image_link_source'] : '_permalink';
			$custom = ! empty( $settings['image_link_source_custom'] ) ? $settings['image_link_source_custom'] : false;
			$object_context = isset( $settings['object_context'] ) ? $settings['object_context'] : false;

			$url = apply_filters(
				'jet-engine/listings/dynamic-image/custom-url',
				false,
				$settings
			);

			if ( false !== $url ) {
				return $url;
			}

			if ( $custom ) {
				$url = jet_engine()->listings->data->get_meta(
					$custom,
					jet_engine()->listings->data->get_object_by_context( $object_context )
				);
			} elseif ( '_permalink' === $source ) {
				$url = jet_engine()->listings->data->get_current_object_permalink(
					jet_engine()->listings->data->get_object_by_context( $object_context )
				);
			} elseif ( 'options_page' === $source ) {
				$option = ! empty( $settings['image_link_option'] ) ? $settings['image_link_option'] : false;
				$url    = jet_engine()->listings->data->get_option( $option );
			} elseif ( $source ) {
				$url = jet_engine()->listings->data->get_meta(
					$source,
					jet_engine()->listings->data->get_object_by_context( $object_context )
				);
			}

			if ( is_array( $url ) ) {
				$url = $url[0];
			}

			if ( ! empty( $settings['link_url_prefix'] ) ) {
				$url = $settings['link_url_prefix'] . $url;
			}

			return $url;

		}

		public function render() {

			$base_class = $this->get_name();
			$settings   = $this->get_settings();

			$classes = array(
				'jet-listing',
				$base_class,
			);

			if ( ! empty( $settings['className'] ) ) {
				$classes[] = esc_attr( $settings['className'] );
			}

			printf( '<div class="%1$s">', implode( ' ', $classes ) );

				do_action( 'jet-engine/listing/dynamic-image/before-image', $this );

				$image_url = $this->get_image_url( $settings );

				if ( $image_url ) {

					$open_in_new = isset( $settings['open_in_new'] ) ? $settings['open_in_new'] : '';
					$rel_attr    = isset( $settings['rel_attr'] ) ? esc_attr( $settings['rel_attr'] ) : '';
					$rel         = '';
					$target      = '';

					if ( $rel_attr ) {
						$rel = sprintf( ' rel="%s"', $rel_attr );
					}

					if ( $open_in_new ) {
						$target = ' target="_blank"';
					}

					printf( '<a href="%1$s" class="%2$s__link"%3$s%4$s>', $image_url, $base_class, $rel, $target );
				}

				$this->render_image( $settings );

				if ( $image_url ) {
					echo '</a>';
				}

				do_action( 'jet-engine/listing/dynamic-image/after-image', $this );

			echo '</div>';

		}

	}

}
