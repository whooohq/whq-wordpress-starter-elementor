<?php
/**
 * Tools Class
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Tricks_Tools' ) ) {

	/**
	 * Define Jet_Tricks_Tools class
	 */
	class Jet_Tricks_Tools {

		public static $new_icon_prefix  = 'selected_';

		/**
		 * Returns HTML icon markup
		 *
		 * @param  array  $setting
		 * @param  array  $settings
		 * @param  string $format
		 * @param  string $icon_class
		 * @return string
		 */
		public static function get_icon( $setting = null, $settings = null, $format = '%s', $icon_class = '' ) {
			return self::render_icon( $setting, $settings, $format, $icon_class, false );
		}

		/**
		 * Print HTML icon template
		 *
		 * @param  array  $setting
		 * @param  array  $settings
		 * @param  string $format
		 * @param  string $icon_class
		 * @param  bool   $echo
		 *
		 * @return void|string
		 */
		public static function render_icon( $setting = null, $settings = null, $format = '%s', $icon_class = '', $echo = true ) {

			if ( null === $settings ) {
				return;
			}

			$new_setting = self::$new_icon_prefix . $setting;

			$migrated = isset( $settings['__fa4_migrated'][ $new_setting ] );
			$is_new = ( empty( $settings[ $setting ] ) || 'false' === $settings[ $setting ] )
			          && class_exists( 'Elementor\Icons_Manager' ) && Elementor\Icons_Manager::is_migration_allowed();

			$icon_html = '';

			if ( $is_new || $migrated ) {

				$attr = array( 'aria-hidden' => 'true' );

				if ( ! empty( $icon_class ) ) {
					$attr['class'] = $icon_class;
				}

				if ( isset( $settings[ $new_setting ] ) ) {
					ob_start();
					Elementor\Icons_Manager::render_icon( $settings[ $new_setting ], $attr );

					$icon_html = ob_get_clean();
				}

			} else if ( ! empty( $settings[ $setting ] ) ) {

				if ( empty( $icon_class ) ) {
					$icon_class = $settings[ $setting ];
				} else {
					$icon_class .= ' ' . $settings[ $setting ];
				}

				$icon_html = sprintf( '<i class="%s" aria-hidden="true"></i>', $icon_class );
			}

			if ( empty( $icon_html ) ) {
				return;
			}

			if ( ! $echo ) {
				return sprintf( $format, $icon_html );
			}

			printf( $format, $icon_html );
		}

	}

}
