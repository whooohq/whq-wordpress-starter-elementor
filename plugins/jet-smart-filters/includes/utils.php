<?php
/**
 * Utils class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Utils' ) ) {

	/**
	 * Define Jet_Smart_Filters_Utils class
	 */
	class Jet_Smart_Filters_Utils {

		/**
		 * Returns template content as string
		 *
		 * @return string
		 */
		public function get_template_html( $template ) {

			ob_start();
			include jet_smart_filters()->get_template( $template );
			$html = ob_get_clean();

			return preg_replace('~>\\s+<~m', '><', $html);

		}

		/**
		 * Returns parsed template
		 *
		 * @return string
		 */
		public function template_parse( $template ) {

			$html_template = $this->get_template_html( $template );

			preg_match_all( '/\/%(.+?)%\//', $html_template, $matches, PREG_SET_ORDER );
			foreach ( $matches as $item ) {
				$prefix = ! preg_match( '/(if|for|else|{|})/', $item[0] ) ? 'echo ' : '';
				$html_template = str_replace( $item[0], '<?php ' . $prefix . trim( $item[1] ) . ' ?>', $html_template );
			}

			return $html_template;

		}

		/**
		 * Returns image size array in slug => name format
		 *
		 * @return  array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			return array_merge( array( 'full' => esc_html__( 'Full', 'jet-woo-builder' ), ), $result );
		}

		/**
		 * Returns additional providers
		 *
		 * @return string
		 */
		public function get_additional_providers( $settings ) {

			if ( empty( $settings['additional_providers_enabled'] ) ) {
				return '';
			}

			if ( ! empty( $settings['additional_providers_list'] ) ) {
				$additional_providers = $settings['additional_providers_list'];
			} else if ( ! empty( $settings['additional_providers'] ) ) {
				// backward compatibility
				$additional_providers = array_map( function ( $additional_provider ) {
					return array( 'additional_provider' => $additional_provider );
				}, $settings['additional_providers'] );
			} else {
				return '';
			}

			$output_data      = [];
			$default_query_id = ! empty( $settings['query_id'] ) ? $settings['query_id'] : 'default';

			foreach ( $additional_providers as $additional_provider ) {
				$provider = ! empty( $additional_provider['additional_provider'] ) ? $additional_provider['additional_provider'] : false;
				$query_id = ! empty( $additional_provider['additional_query_id'] ) ? $additional_provider['additional_query_id'] : $default_query_id;

				if ( $provider ) {
					$output_data[] = $provider . ( $query_id ? '/' . $query_id : '' );
				}
			}

			return $output_data ? htmlspecialchars( json_encode( $output_data ) ) : '';

		}

		/**
		 * Merge Query Args
		 *
		 * @return Array
		 */
		public function merge_query_args( $current_query_args, $new_query_args ) {

			foreach ( $new_query_args as $key => $value ) {
				if ( in_array( $key, array( 'tax_query', 'meta_query' ) ) && ! empty( $current_query_args[$key] ) ) {
					$value = array_merge( $current_query_args[$key], $value );
				}

				$current_query_args[$key] = $value;
			}

			return $current_query_args;

		}

		/**
		 * Insert in array after key
		 *
		 * @return Array
		 */
		public function array_insert_after( $source = array(), $after = null, $insert = array() ) {

			$index  = array_search( $after, array_keys( $source ) );

			if ( false === $index ) {
				return $source + $insert;
			}

			$offset = $index + 1;

			return array_slice( $source, 0, $offset, true ) + $insert + array_slice( $source, $offset, null, true );

		}

		/**
		 * Returns URL with filters applied
		 *
		 * @param  string $base_url [description]
		 * @param  [type] $query_id [description]
		 * @param  array  $args     [description]
		 * @return [type]           [description]
		 */
		public function get_filtered_url( $base_url = false, $query_id = null, $provider = '', $args = array() ) {

			$query_args = array();

			foreach ( $args as $arg ) {

				$value     = $arg['value'];
				$query_var = $arg['query_var'];

				switch ( $arg['query_type'] ) {

					case 'tax_query':
						$query_type = 'tax';

						break;

					case 'meta_query':
						$query_type = 'meta';

						break;

					case 'date_query':
						$query_type = 'date';
						$query_var  = false;
						$value      = str_replace( '/', '-', $value );

						break;

					case 'sort':
						$query_var = false;
						break;

					case '_s':
						$query_var = false;
						break;

					default:
						$query_type = $arg['query_type'];
						break;
				}

				switch ( $arg['filter_type'] ) {
					case 'range':
					case 'check-range':
						$query_var .= '!' . $arg['filter_type'];
						break;

					case 'date-range':
					case 'date-period':
						if ( 'meta' === $query_type ) {
							$query_var .= '!date';
						}

						break;

					case 'pagination':
						$query_type = 'pagenum';
						break;

					case 'search':
						if ( 'meta' === $query_type ) {
							$query_type = '_s';
							$value     .= '!meta=' . $query_var;
							$query_var  = false;
						}

						break;


					default:
						if ( ! empty( $arg['suffix'] ) )
							$query_var .= '!' . $arg['suffix'];

						break;
				}

				if ( $query_var ) {
					$value = $query_var . ':' . $value;
				}

				if ( ! isset( $query_args[ $query_type ] ) ) {
					$query_args[ $query_type ] = $value;
				} else {

					if ( ! is_array( $query_args[ $query_type ] ) ) {
						$query_args[ $query_type ] = array( $query_args[ $query_type ] );
					}

					$query_args[ $query_type ][] = $value;
				}

			}

			/**
			 * @todo Merge smae keys and process hierarchy
			 */

			$url_type = jet_smart_filters()->settings->get( 'url_structure_type' );

			if ( ! $base_url ) {
				$base_url = $_SERVER['REQUEST_URI'];
			}

			$name_parts = array( $provider );

			if ( $query_id ) {
				$name_parts[] = $query_id;
			}

			$provider_name = implode( ':', $name_parts );
			$result        = trailingslashit( $base_url );

			switch ( $url_type ) {
				case 'permalink':
					$result .= 'jsf/' . $provider_name . '/';

					if ( isset( $query_args['_s'] ) ) {
						$query_args['search'] = $query_args['_s'];
						unset( $query_args['_s'] );
					}

					foreach ( $query_args as $key => $value ) {
						$result .= urlencode( $key ) . '/' . urlencode( $value );
					}

					break;

				default:

					foreach ( $query_args as $key => $data ) {
						if ( is_array( $data ) ) {
							$query_args[ $key ] = implode( ';', $data );
						}
					}

					$query_args = array_merge( array( 'jsf' => $provider_name ), $query_args );
					$result     = add_query_arg( $query_args, $result );

					break;
			}

			return $result;
		}

	}

}
