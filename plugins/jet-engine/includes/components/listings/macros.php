<?php
/**
 * Macros manager class.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Listings_Macros' ) ) {

	/**
	 * Define Jet_Engine_Listings_Macros class
	 */
	class Jet_Engine_Listings_Macros {

		private $macros_context      = null;
		private $fallback            = null;
		private $macros_list         = null;
		private $escaped_macros_list = null;

		/**
		 * Return available macros list.
		 *
		 * @param bool $sorted
		 * @param bool $escape
		 *
		 * @return array
		 */
		public function get_all( $sorted = false, $escape = false ) {

			if ( null === $this->macros_list ) {

				require_once jet_engine()->plugin_path( 'includes/base/base-macros.php' );

				$this->register_core_macros();

				do_action( 'jet-engine/register-macros' );

				$macros_list = apply_filters( 'jet-engine/listings/macros-list', array() );

				$this->macros_list = $macros_list;

			}

			$macros_list = $this->macros_list;

			if ( $sorted ) {

				uasort( $macros_list, function( $a, $b ) {

					$name_a = ( is_array( $a ) && isset( $a['label'] ) ) ? $a['label'] : $this->to_string( $a );
					$name_b = ( is_array( $b ) && isset( $b['label'] ) ) ? $b['label'] : $this->to_string( $b );

					if ( $name_a == $name_b ) {
						return 0;
					}

					return ( $name_a < $name_b ) ? -1 : 1;

				} );

			}

			if ( $escape ) {

				if ( null === $this->escaped_macros_list ) {

					foreach ( $macros_list as $key => $macros ) {
						if ( ! empty( $macros['args'] ) ) {
							foreach ( $macros['args'] as $arg => $data ) {

								if ( ! empty( $data['options'] ) && is_callable( $data['options'] ) ) {
									$data['options'] = call_user_func( $data['options'] );
									$macros['args'][ $arg ] = $data;
									$macros_list[ $key ] = $macros;
								}

								if ( ! empty( $data['groups'] ) && is_callable( $data['groups'] ) ) {
									$data['groups'] = call_user_func( $data['groups'] );
									$macros['args'][ $arg ] = $data;
									$macros_list[ $key ] = $macros;
								}

							}
						}
					}

					$this->escaped_macros_list = $macros_list;

				}

				$macros_list = $this->escaped_macros_list;

			}

			return $macros_list;

		}

		public function register_core_macros() {

			foreach ( glob( jet_engine()->plugin_path( 'includes/components/listings/macros/' ) . '*.php' ) as $file ) {
				require_once $file;

				$file_name  = basename( $file, '.php' );
				$class_name = ucwords( str_replace( '-', ' ', $file_name ) );
				$class_name = str_replace( ' ', '_', $class_name );
				$class_name = sprintf( 'Jet_Engine\Macros\%s', $class_name );

				if ( class_exists( $class_name ) ) {
					new $class_name;
				}
			}
		}

		public function set_macros_context( $context = null ) {
			$this->macros_context = $context;
		}

		public function get_macros_context( $context = null ) {
			return $this->macros_context;
		}

		public function set_fallback( $fallback = null ) {
			$this->fallback = $fallback;
		}

		public function get_fallback( $fallback = null ) {
			return $this->fallback;
		}

		/**
		 * Is $str is array - returns 0, in other cases returns $str
		 *
		 * @param  mixed $str
		 * @return mixed
		 */
		public function to_string( $str ) {

			if ( is_array( $str ) ) {
				return 0;
			} else {
				return $str;
			}

		}

		/**
		 * Get macros list for options.
		 *
		 * @return array
		 */
		public function get_macros_list_for_options() {

			$all = $this->get_all();
			$result = array();

			foreach ( $all as $key => $data ) {
				if ( is_array( $data ) ) {
					$result[ $key ] = ! empty( $data['label'] ) ? $data['label'] : $key;
				} else {
					$result[ $key ] = $key;
				}
			}

			return $result;

		}

		/**
		 * Return verbosed macros list
		 *
		 * @return string
		 */
		public function verbose_macros_list() {

			$macros = $this->get_all();
			$result = '';
			$sep    = '';

			foreach ( $macros as $key => $data ) {
				$result .= $sep . '%' . $key . '%';
				$sep     = ', ';
			}

			return $result;

		}

		/**
		 * Return current macros object
		 *
		 * @return object|null
		 */
		public function get_macros_object() {

			if ( ! $this->macros_context || 'default_object' === $this->macros_context ) {
				$object = jet_engine()->listings->data->get_current_object();
			} else {
				$object = jet_engine()->listings->data->get_object_by_context( $this->macros_context );
			}

			return $object;

		}

		/**
		 * Can be used for meta query. Returns values of passed mata key for current post/term.
		 *
		 * !!! Do not delete. Used in the macros classes.
		 *
		 * @param  mixed  $field_value Field value.
		 * @param  string $meta_key    Metafield to get value from.
		 * @return mixed
		 */
		public function get_current_meta( $field_value = null, $meta_key = null ) {

			if ( ! $meta_key && ! empty( $field_value ) ) {
				$meta_key = $field_value;
			}

			if ( ! $meta_key ) {
				return '';
			}

			$object = $this->get_macros_object();

			if ( ! $object ) {
				return '';
			}

			$class  = get_class( $object );
			$result = '';

			switch ( $class ) {

				case 'WP_Post':
					return get_post_meta( $object->ID, $meta_key, true );

				case 'WP_Term':
					return get_term_meta( $object->term_id, $meta_key, true );

				case 'WP_User':
					return get_user_meta( $object->ID, $meta_key, true );

			}

		}

		/**
		 * Call macros callback by macros name and args array
		 *
		 * @param  [type] $macros [description]
		 * @param  array  $args   [description]
		 * @return [type]         [description]
		 */
		public function call_macros_func( $macros, $args = array() ) {

			$all_macros = $this->get_all();

			if ( empty( $all_macros[ $macros ] ) ) {
				return;
			}

			$macros_data   = $all_macros[ $macros ];
			$prepared_args = array( false );
			$custom_args   = array();

			if ( is_callable( $macros_data ) ) {
				return call_user_func_array( $macros_data, $prepared_args );
			}

			if ( ! empty( $macros_data['args'] ) ) {

				foreach ( array_keys( $macros_data['args'] ) as $arg ) {
					$custom_args[] = isset( $args[ $arg ] ) ? $args[ $arg ] : null;
				}

			}

			$prepared_args[] = implode( '|', $custom_args );

			return call_user_func_array( $macros_data['cb'], $prepared_args );

		}

		/**
		 * Do macros inside string
		 *
		 * @param  [type] $string      [description]
		 * @param  [type] $field_value [description]
		 * @return [type]              [description]
		 */
		public function do_macros( $string = '', $field_value = null ) {

			$macros = $this->get_all();

			return preg_replace_callback(
				'/%([a-z_-]+)(\|[a-zA-Z0-9_\-\,\.\+\:\/\s\(\)|]+)?%(\{.+\})?/',
				function( $matches ) use ( $macros, $field_value ) {

					$found = $matches[1];

					if ( ! isset( $macros[ $found ] ) ) {
						return $matches[0];
					}

					$cb = $macros[ $found ];

					if ( is_array( $cb ) && isset( $cb['cb'] ) ) {
						$cb = ! empty( $cb['cb'] ) ? $cb['cb'] : false;

						if ( ! $cb ) {
							return $matches[0];
						}
					}

					if ( ! is_callable( $cb ) ) {
						return $matches[0];
					}

					$args   = isset( $matches[2] ) ? ltrim( $matches[2], '|' ) : false;					
					$config = isset( $matches[3] ) ? json_decode( $matches[3], true ) : false;

					if ( $config ) {
						
						if ( ! empty( $config['context'] ) ) {
							$this->set_macros_context( $config['context'] );
						}

						if ( ! empty( $config['fallback'] ) ) {
							$this->set_fallback( $config['fallback'] );
						}

					}
					
					$result = call_user_func( $cb, $field_value, $args );
					$fallback = $this->get_fallback();

					if ( $fallback && empty( $result ) ) {
						$result = $fallback;
					}

					$this->set_fallback( null );
					$this->set_macros_context( null );

					if ( is_array( $result ) ) {
						return implode( ',', $result );
					} else {
						return $result;
					}

				}, $string
			);

		}

	}

}
