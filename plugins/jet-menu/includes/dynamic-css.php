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

if ( ! class_exists( 'Jet_Menu_Dynamic_CSS' ) ) {

	/**
	 * Define Jet_Menu_Dynamic_CSS class
	 */
	class Jet_Menu_Dynamic_CSS {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Fonts holder.
		 *
		 * @var array
		 */
		private $fonts = array();

		/**
		 * Returns google fonts list.
		 *
		 * @return array
		 */
		public function get_fonts_list() {

			if ( empty( $this->fonts ) ) {
				$this->fonts = $this->get_fonts();
				$this->fonts = array_merge( array( '0' => esc_html__( 'Select Font...', 'jet-menu' ) ), $this->fonts );
			}

			return $this->fonts;
		}

		/**
		 * Retrieve array with font-family (for select element).
		 *
		 * @since  1.0.0
		 * @param  string $type Font type.
		 * @return array
		 */
		public function get_fonts( $type = '' ) {

			if ( ! empty( $this->fonts[ $type ] ) ) {
				return $this->fonts[ $type ];
			}

			if ( ! empty( $this->fonts ) ) {
				return $this->fonts;
			}

			$this->prepare_fonts( $type );

			return ! empty( $type ) && isset( $this->fonts[ $type ] ) ? $this->fonts[ $type ] : $this->fonts;
		}

		/**
		 * Prepare fonts.
		 *
		 * @since 1.0.0
		 */
		public function prepare_fonts() {

			$fonts_data = $this->get_fonts_data();

			foreach ( $fonts_data as $type => $file ) {

				$fonts = $this->read_font_file( $file );

				if ( is_array( $fonts ) ) {
					$this->fonts = array_merge( $this->fonts, $this->satizite_font_family( $fonts ) );
				}
			}

			/**
			 * Filter array of prepared fonts.
			 * You can add new fonts from here
			 *
			 * @var   array         $this->fonts
			 * @param CX_Customizer $this
			 */
			$this->fonts = apply_filters( 'jet_menu/fonts_list', $this->fonts, $this );
		}

		/**
		 * Retrieve array with fonts file path.
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_fonts_data() {

			/**
			 * Filter array of fonts data.
			 *
			 * @since 1.0.0
			 * @param array  $data Set of fonts data.
			 * @param object $this Cherry_Customiser instance.
			 */
			return apply_filters( 'jet_menu/fonts_data', array(
				'standard' => jet_menu()->plugin_path( 'assets/fonts/standard.json' ),
				'google'   => jet_menu()->plugin_path( 'assets/fonts/google.json' ),
			), $this );
		}

		/**
		 * Retrieve a data from font's file.
		 *
		 * @since  1.0.0
		 * @param  string $file          File path.
		 * @return array        Fonts data.
		 */
		public function read_font_file( $file ) {

			if ( ! file_exists( $file ) ) {
				return false;
			}

			// Read the file.
			ob_start();
			include $file;
			$json = ob_get_clean();

			if ( ! $json ) {
				return new WP_Error( 'reading_error', 'Error when reading file' );
			}

			$content = json_decode( $json, true );

			return $content['items'];
		}

		/**
		 * Retrieve a set with `font-family` ( 'foo' => 'foo' ).
		 *
		 * @since  1.0.0
		 * @param  array $data All fonts data.
		 * @return array
		 */
		public function satizite_font_family( $data ) {

			$keys   = array_map( array( $this, '_build_keys' ), $data );
			$values = array_map( array( $this, '_build_values' ), $data );

			array_filter( $keys );
			array_filter( $values );

			return array_combine( $keys, $values );
		}

		/**
		 * Function _build_keys.
		 *
		 * @since 1.0.0
		 */
		public function _build_keys( $item ) {

			if ( empty( $item['family'] ) ) {
				return false;
			}

			return sprintf( '%1$s, %2$s', $item['family'], $item['category'] );
		}

		/**
		 * Function _build_values.
		 *
		 * @since 1.0.0
		 */
		public function _build_values( $item ) {

			if ( empty( $item['family'] ) ) {
				return false;
			}

			return $item['family'];
		}

		/**
		 * Add single font styles
		 */
		public function add_single_font_styles( $font, $selector ) {

			$enbaled = $this->get_option( $font . '-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$font_settings = array(
				'font-size'      => 'px',
				'font-family'    => '',
				'font-weight'    => '',
				'text-transform' => '',
				'font-style'     => '',
				'line-height'    => 'em',
				'letter-spacing' => 'em',
			);

			foreach ( $font_settings as $setting => $units ) {

				$value = $this->get_option( $font . '-' . $setting );

				if ( '' === $value || false === $value || 'false' === $value ) {
					continue;
				}

				if ( 'font-family' === $setting && 0 === $value ) {
					continue;
				}

				jet_menu()->dynamic_css_manager->add_style(
					$selector,
					array(
						$setting => $value . $units,
					)
				);
			}

		}

		/**
		 * Add single background option.
		 *
		 * @param [type] $options  [description]
		 * @param [type] $selector [description]
		 */
		public function add_single_bg_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$type = $this->get_option( $option . '-bg-type' );

			$settings = array(
				'color',
				'image',
				'position',
				'attachment',
				'repeat',
				'size',
			);

			$is_gradient = $this->get_option( $option . '-gradient-switch' );

			foreach ( $settings as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value || 'false' === $value ) {
					continue;
				}

				if ( 'image' === $setting && 'true' !== $is_gradient ) {
					$value = wp_get_attachment_image_url( $value, 'full' );
					$value = sprintf( 'url("%s")', esc_url( $value ) );
				}

				jet_menu()->dynamic_css_manager->add_style(
					$selector,
					array(
						'background-' . $setting => $value,
					)
				);

			}

			if ( 'true' === $is_gradient ) {
				$color_start = $this->get_option( $option . '-color' );
				$color_end   = $this->get_option( $option . '-second-color' );
				$direction   = $this->get_option( $option . '-direction', 'horizontal' );

				if ( ! $color_start || ! $color_end ) {
					return;
				}

				jet_menu()->dynamic_css_manager->add_style(
					$selector,
					array(
						'background-image' => sprintf(
							'linear-gradient( to %1$s, %2$s, %3$s )',
							$direction, $color_start, $color_end
						),
					)
				);

			}

		}

		/**
		 * [add_dimensions_css description]
		 * @param array $args [description]
		 */
		public function add_dimensions_css( $args = array() ) {

			$defaults = array(
				'selector'  => '',
				'rule'      => '',
				'values'    => array(),
				'important' => false,
			);

			$args = wp_parse_args( $args, $defaults );

			$value     = $args['values'];
			$selector  = $args['selector'];
			$rule      = $args['rule'];
			$important = ( true === $args['important'] ) ? ' !important' : '';

			$properties = array(
				'top'    => 'top-left',
				'right'  => 'top-right',
				'bottom' => 'bottom-right',
				'left'   => 'bottom-left',
			);

			foreach ( $properties as $position => $radius_position ) {

				if ( isset( $value[ $position ] ) && '' !== $value[ $position ] ) {

					$prop = $value[ $position ] . $value['units'] . $important;

					if ( false !== strpos( $rule, 'radius' ) ) {
						jet_menu()->dynamic_css_manager->add_style(
							$selector,
							array(
								sprintf( $rule, $radius_position ) => $prop,
							)
						);
					} else {
						jet_menu()->dynamic_css_manager->add_style(
							$selector,
							array(
								sprintf( $rule, $position ) => $prop,
							)
						);
					}
				}
			}
		}

		/**
		 * Add single border option.
		 *
		 * @param [type] $options  [description]
		 * @param [type] $selector [description]
		 */
		public function add_single_border_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-border-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$type = $this->get_option( $option . '-bg-type' );

			$settings = array(
				'border-style',
				'border-width',
				'border-color',
			);

			foreach ( $settings as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value || 'false' === $value ) {
					continue;
				}

				if ( 'border-width' === $setting ) {

					jet_menu_dynmic_css()->add_dimensions_css(
						array(
							'selector' => $selector,
							'rule'     => 'border-%s-width',
							'values'   => $value,
						)
					);

					continue;
				}

				jet_menu()->dynamic_css_manager->add_style(
					$selector,
					array(
						$setting => $value,
					)
				);

			}

		}

		/**
		 * [add_single_shadow_styles description]
		 * @param [type] $option   [description]
		 * @param [type] $selector [description]
		 */
		public function add_single_shadow_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-box-shadow-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$result = '';

			foreach ( array( 'box-shadow-h', 'box-shadow-v', 'box-shadow-blur' ) as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value || 'false' === $value ) {
					$value = 0;
				}

				$result .= $value . 'px ';
			}

			$spread = $this->get_option( $option . '-box-shadow-spread' );

			if ( '' !== $spread && false !== $spread && 'false' !== $spread ) {
				$result .= $spread . 'px ';
			}

			$color = $this->get_option( $option . '-box-shadow-color' );

			if ( '' !== $color && false !== $color ) {
				$result .= $color;
			}

			$inset = $this->get_option( $option . '-box-shadow-inset' );

			if ( 'true' === $inset ) {
				$result .= ' inset';
			}

			jet_menu()->dynamic_css_manager->add_style(
				$selector,
				array(
					'box-shadow' => $result,
				)
			);

		}

		/**
		 * add single position
		 */
		public function add_single_position( $option, $selector ) {

			$v_pos = $this->get_option( sprintf( $option, 'ver' ) );
			$h_pos = $this->get_option( sprintf( $option, 'hor' ) );

			$order_map = array(
				'left'  => -1,
				'right' => 2,
			);

			$styles = array();

			switch ( $v_pos ) {

				case 'top':
					$styles = array(
						'flex'  => '0 0 100%',
						'width' => 0,
						'order' => -2,
					);
					break;

				case 'center':
					$styles = array(
						'align-self' => 'center',
					);
					break;

				case 'bottom':
					$styles = array(
						'flex'  => '0 0 100%',
						'width' => 0,
						'order' => 2,
					);
					break;
			}

			switch ( $h_pos ) {

				case 'left':
				case 'right':

					if ( in_array( $v_pos, array( 'top', 'bottom' ) ) ) {
						$styles['text-align'] = $h_pos;
					} else {
						$styles['order'] = $order_map[ $h_pos ];
					}
					break;

				case 'center':
					if ( in_array( $v_pos, array( 'top', 'bottom' ) ) ) {
						$styles['text-align'] = 'center';
					}
					break;

			}

			if ( 'jet-menu-sub-arrow-%s-position' === $option && 'right' === $h_pos ) {
				$styles['margin-left'] = 'auto !important';
			}

			if ( ! empty( $styles ) ) {
				jet_menu()->dynamic_css_manager->add_style( $selector, $styles );
			}

		}

		/**
		 * Get option wrapper
		 *
		 * @param  string  $option  [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get_option( $option = '', $default = false ) {
			return jet_menu()->settings_manager->options_manager->get_option( $option, $default );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Menu_Dynamic_CSS
 *
 * @return object
 */
function jet_menu_dynmic_css() {
	return Jet_Menu_Dynamic_CSS::get_instance();
}
