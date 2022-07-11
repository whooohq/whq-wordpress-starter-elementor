<?php
/**
 * WPML compatibility package
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_WPML_Package' ) ) {

	class Jet_Engine_WPML_Package {

		public function __construct() {
			
			add_filter( 'wpml_elementor_widgets_to_translate',              array( $this, 'add_translatable_nodes' ) );
			add_filter( 'jet-engine/listings/frontend/rendered-listing-id', array( $this, 'set_translated_object' ) );
			add_filter( 'jet-engine/forms/render/form-id',                  array( $this, 'set_translated_object' ) );
			add_filter( 'jet-engine/profile-builder/template-id',           array( $this, 'set_translated_object' ) );
			add_filter( 'jet-engine/relations/get_related_posts',           array( $this, 'set_translated_related_posts' ) );

			// Translate CPT Name
			if ( jet_engine()->cpt ) {
				$cpt_items = jet_engine()->cpt->get_items();

				if ( ! empty( $cpt_items ) ) {
					foreach ( $cpt_items as $post_type ) {
						add_filter( "post_type_labels_{$post_type['slug']}", array( $this, 'translate_cpt_name' ) );
					}
				}
			}

			// Translate Admin Labels
			add_filter( 'jet-engine/compatibility/translate-string', array( $this, 'translate_admin_labels' ) );

		}

		/**
		 * Set translated object ID to show
		 *
		 * @param int $obj_id
		 *
		 * @return int
		 */
		public function set_translated_object( $obj_id ) {

			global $sitepress;

			$new_id = $sitepress->get_object_id( $obj_id );

			if ( $new_id ) {
				return $new_id;
			}

			return $obj_id;
		}

		/**
		 * Set translated related posts
		 *
		 * @param  mixed $ids
		 * @return mixed
		 */
		public function set_translated_related_posts( $ids ) {

			if ( is_array( $ids ) ) {
				foreach ( $ids as $id ) {
					$ids[ $id ] = apply_filters( 'wpml_object_id', $id, get_post_type( $id ), true );
				}
			} else {
				$ids = apply_filters( 'wpml_object_id', $ids, get_post_type( $ids ), true );
			}

			return $ids;
		}

		/**
		 * Add translation strings
		 */
		public function add_translatable_nodes( $nodes ) {

			$nodes['jet-listing-grid'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-grid'
				),
				'fields'     => array(
					array(
						'field'       => 'not_found_message',
						'type'        => esc_html__( 'Listing Grid: Not found message', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-field'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-field'
				),
				'fields'     => array(
					array(
						'field'       => 'date_format',
						'type'        => esc_html__( 'Field: Date format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'num_dec_point',
						'type'        => esc_html__( 'Field: Separator for the decimal point (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'num_thousands_sep',
						'type'        => esc_html__( 'Field: Thousands separator (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'dynamic_field_format',
						'type'        => esc_html__( 'Field: Field format (if used)', 'jet-engine' ),
						'editor_type' => 'AREA',
					),
				),
			);

			$nodes['jet-listing-dynamic-link'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-link'
				),
				'fields'     => array(
					array(
						'field'       => 'link_label',
						'type'        => esc_html__( 'Link: Label (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'added_to_store_text',
						'type'        => esc_html__( 'Link: Added to store text (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-meta'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-meta'
				),
				'fields'     => array(
					array(
						'field'       => 'prefix',
						'type'        => esc_html__( 'Meta: Prefix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'suffix',
						'type'        => esc_html__( 'Meta: Suffix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'zero_comments_format',
						'type'        => esc_html__( 'Meta: Zero Comments Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'one_comment_format',
						'type'        => esc_html__( 'Meta: One Comments Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'more_comments_format',
						'type'        => esc_html__( 'Meta: More Comments Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'date_format',
						'type'        => esc_html__( 'Meta: Date Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-terms'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-terms'
				),
				'fields'     => array(
					array(
						'field'       => 'terms_prefix',
						'type'        => esc_html__( 'Terms: Prefix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'terms_suffix',
						'type'        => esc_html__( 'Terms: Suffix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-repeater'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-repeater'
				),
				'fields'     => array(
					array(
						'field'       => 'dynamic_field_format',
						'type'        => esc_html__( 'Repeater: Field format (if used)', 'jet-engine' ),
						'editor_type' => 'AREA',
					),
				),
			);

			return $nodes;

		}

		/**
		 * Translate CPT Name
		 *
		 * @param  object $labels
		 * @return object
		 */
		public function translate_cpt_name( $labels ) {
			do_action( 'wpml_register_single_string', 'Jet Engine CPT Labels', "Jet Engine CPT Name ({$labels->name})", $labels->name );
			$labels->name = apply_filters( 'wpml_translate_single_string', $labels->name, 'Jet Engine CPT Labels', "Jet Engine CPT Name ({$labels->name})" );

			return $labels;
		}

		/**
		 * Translate Admin Labels
		 *
		 * @param  string $label
		 * @return string
		 */
		public function translate_admin_labels( $label ) {

			global $sitepress;

			$lang = method_exists( $sitepress, 'get_current_language' ) ? $sitepress->get_current_language() : null;

			do_action( 'wpml_register_single_string', 'Jet Engine Admin Labels', "Admin Label - {$label}", $label );
			$label = apply_filters( 'wpml_translate_single_string', $label, 'Jet Engine Admin Labels', "Admin Label - {$label}", $lang );

			return $label;
		}

	}

}

new Jet_Engine_WPML_Package();
