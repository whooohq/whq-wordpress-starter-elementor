<?php
/**
 * Jet_Tabs_Compatibility class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Tabs_Compatibility' ) ) {

	/**
	 * Define Jet_Tabs_Compatibility class
	 */
	class Jet_Tabs_Compatibility {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Jet_Tabs_Compatibility
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			// WPML Compatibility
			if ( defined( 'WPML_ST_VERSION' ) ) {

				add_action( 'init', array( $this, 'load_wpml_modules' ) );

				if ( class_exists( 'WPML_Elementor_Module_With_Items' ) ) {
					$this->load_wpml_modules();
				}

				add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'add_wpml_translatable_nodes' ) );
				add_filter( 'jet-tabs/widgets/template_id',        array( $this, 'set_wpml_translated_template_id' ) );
			}

			// Polylang compatibility
			if ( class_exists( 'Polylang' ) ) {
				add_filter( 'jet-tabs/widgets/template_id', array( $this, 'set_pll_translated_template_id' ) );
			}
		}

		/**
		 * Load wpml required files.
		 *
		 * @return void
		 */
		public function load_wpml_modules() {
			require jet_tabs()->plugin_path( 'includes/compatibility/wpml-modules/jet-wpml-accordion.php' );
			require jet_tabs()->plugin_path( 'includes/compatibility/wpml-modules/jet-wpml-image-accordion.php' );
			require jet_tabs()->plugin_path( 'includes/compatibility/wpml-modules/jet-wpml-tabs.php' );
		}

		/**
		 * Add wpml translation nodes
		 *
		 * @param array $nodes_to_translate
		 *
		 * @return array
		 */
		public function add_wpml_translatable_nodes( $nodes_to_translate ) {

			$nodes_to_translate['jet-accordion'] = array(
				'conditions'        => array( 'widgetType' => 'jet-accordion' ),
				'integration-class' => 'Jet_Tabs_WPML_Accordion',
			);

			$nodes_to_translate['jet-image-accordion'] = array(
				'conditions'        => array( 'widgetType' => 'jet-image-accordion' ),
				'integration-class' => 'Jet_Tabs_WPML_Image_Accordion',
			);

			$nodes_to_translate['jet-switcher'] = array(
				'conditions' => array( 'widgetType' => 'jet-switcher' ),
				'fields'     => array(
					array(
						'field'       => 'disable_label',
						'type'        => esc_html__( 'Jet Switcher: Disable Label', 'jet-tabs' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'disable_item_editor_content',
						'type'        => esc_html__( 'Jet Switcher: Disable Editor Content', 'jet-tabs' ),
						'editor_type' => 'VISUAL',
					),
					array(
						'field'       => 'enable_label',
						'type'        => esc_html__( 'Jet Switcher: Enable Label', 'jet-tabs' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'enable_item_editor_content',
						'type'        => esc_html__( 'Jet Switcher: Enable Editor Content', 'jet-tabs' ),
						'editor_type' => 'VISUAL',
					),
				),
			);

			$nodes_to_translate['jet-tabs'] = array(
				'conditions'        => array( 'widgetType' => 'jet-tabs' ),
				'integration-class' => 'Jet_Tabs_WPML_Tabs',
			);

			return $nodes_to_translate;
		}

		/**
		 * Set WPML translated template.
		 *
		 * @param $template_id
		 *
		 * @return mixed|void
		 */
		public function set_wpml_translated_template_id( $template_id ) {
			$post_type = get_post_type( $template_id );

			return apply_filters( 'wpml_object_id', $template_id, $post_type, true );
		}

		/**
		 * Set Polylang translated template.
		 *
		 * @param $template_id
		 *
		 * @return false|int|null
		 */
		public function set_pll_translated_template_id( $template_id ) {

			if ( function_exists( 'pll_get_post' ) ) {

				$translation_template_id = pll_get_post( $template_id );

				if ( null === $translation_template_id ) {
					// the current language is not defined yet
					return $template_id;
				} elseif ( false === $translation_template_id ) {
					//no translation yet
					return $template_id;
				} elseif ( $translation_template_id > 0 ) {
					// return translated post id
					return $translation_template_id;
				}
			}

			return $template_id;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return Jet_Tabs_Compatibility
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
 * Returns instance of Jet_Tabs_Compatibility
 *
 * @return Jet_Tabs_Compatibility
 */
function jet_tabs_compatibility() {
	return Jet_Tabs_Compatibility::get_instance();
}
