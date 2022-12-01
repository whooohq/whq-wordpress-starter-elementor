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

			// Relations
			if ( jet_engine()->relations ) {
				$this->relations_hooks();
			}

			// Post meta conditions
			add_filter( 'jet-engine/meta-boxes/conditions/post-has-terms/check-terms', array( $this, 'set_translated_check_terms' ), 10, 2 );

		}

		public function relations_hooks() {

			add_filter( 'jet-engine/relations/types/posts/get-items', array( $this, 'filtered_relations_posts_items' ), 10, 2 );
			add_filter( 'jet-engine/relations/raw-args',              array( $this, 'translate_relations_labels' ) );

			if ( is_admin() ) {
				add_action( 'icl_make_duplicate', array( $this, 'sync_relations_on_make_duplicate' ), 10, 4 );
			}

			if ( is_admin() || wpml_is_rest_request() ) {
				add_action( 'icl_pro_translation_completed', array( $this, 'sync_relations_on_translation_completed' ), 10, 3 );
			}
		}

		public function sync_relations_on_make_duplicate( $original_id, $lang, $post_array, $translated_id ) {
			$this->sync_relations_items( $original_id, $translated_id, $lang );
		}

		public function sync_relations_on_translation_completed( $translated_id, $fields, $job ) {
			$original_id = ! empty( $job->original_doc_id ) ? $job->original_doc_id : false;
			$lang        = ! empty( $job->language_code ) ? $job->language_code : null;

			if ( empty( $original_id ) ) {
				return;
			}

			$this->sync_relations_items( $original_id, $translated_id, $lang );
		}

		public function sync_relations_items( $original_id, $translated_id, $lang ) {

			$post_type = get_post_type( $original_id );
			$rel_type  = jet_engine()->relations->types_helper->type_name_by_parts( 'posts', $post_type );

			$active_relations = jet_engine()->relations->get_active_relations();

			$relations = array_filter( $active_relations, function( $relation ) use ( $rel_type ) {

				if ( $rel_type === $relation->get_args( 'parent_object' ) ) {
					return true;
				}

				if ( $rel_type === $relation->get_args( 'child_object' ) ) {
					return true;
				}

				return false;
			} );

			if ( empty( $relations ) ) {
				return;
			}

			foreach ( $relations as $rel_id => $relation ) {

				$is_parent = $rel_type === $relation->get_args( 'parent_object' );

				if ( $is_parent ) {
					$rel_items = $relation->get_children( $original_id, 'ids' );
					$obj_data  = jet_engine()->relations->types_helper->type_parts_by_name( $relation->get_args( 'child_object' ) );
					$is_single = $relation->is_single_child();
				} else {
					$rel_items = $relation->get_parents( $original_id, 'ids' );
					$obj_data  = jet_engine()->relations->types_helper->type_parts_by_name( $relation->get_args( 'parent_object' ) );
					$is_single = $relation->is_single_parent();
				}

				$rel_items    = array_reverse( $rel_items );
				$obj_type     = $obj_data[0];
				$obj_sub_type = $obj_data[1];

				foreach ( $rel_items as $rel_item ) {

					if ( in_array( $obj_type, array( 'posts', 'terms' ) ) ) {
						$new_rel_item = apply_filters( 'wpml_object_id', $rel_item, $obj_sub_type, true, $lang );
					} else {
						$new_rel_item = $rel_item;
					}

					if ( $is_single && $new_rel_item == $rel_item ) {
						continue;
					}

					if ( $is_parent ) {
						$relation->update( $translated_id, $new_rel_item );

						$meta     = $relation->get_all_meta( $original_id, $rel_item );
						$new_meta = $relation->get_all_meta( $translated_id, $new_rel_item );
						$new_meta = array_merge( $meta, $new_meta );

						if ( ! empty( $new_meta ) ) {
							$relation->update_all_meta( $new_meta, $translated_id, $new_rel_item );
						}

					} else {
						$relation->update( $new_rel_item, $translated_id );

						$meta     = $relation->get_all_meta( $rel_item, $original_id );
						$new_meta = $relation->get_all_meta( $new_rel_item, $translated_id );
						$new_meta = array_merge( $meta, $new_meta );

						if ( ! empty( $new_meta ) ) {
							$relation->update_all_meta( $meta, $new_rel_item, $translated_id );
						}
					}
				}
			}
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

		public function filtered_relations_posts_items( $items, $post_type ) {

			if ( ! is_post_type_translated( $post_type ) ) {
				return $items;
			}

			global $sitepress;

			$current_lang = $sitepress->get_current_language();

			$items = array_filter( $items, function ( $item ) use ( $sitepress, $post_type, $current_lang ) {
				$lang = $sitepress->get_language_for_element( $item['value'], 'post_' . $post_type );
				return $current_lang === $lang;
			} );

			return $items;
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

			$wpml_default_lang = apply_filters( 'wpml_default_language', null );

			$lang = method_exists( $sitepress, 'get_current_language' ) ? $sitepress->get_current_language() : null;

			$name = "Admin Label - {$label}";

			if ( 160 < strlen( $name ) ) {
				$name = substr( $name, 0, 100 ) . '... - ' . md5( $label );
			}

			if ( $lang === $wpml_default_lang ) {
				do_action( 'wpml_register_single_string', 'Jet Engine Admin Labels', $name, $label );
			}

			$label = apply_filters( 'wpml_translate_single_string', $label, 'Jet Engine Admin Labels', $name, $lang );

			return $label;
		}

		public function translate_relations_labels( $args ) {

			if ( empty( $args['labels'] ) ) {
				return $args;
			}

			global $sitepress;

			$relation_name = ! empty( $args['labels']['name'] ) ? $args['labels']['name'] : esc_html__( 'Relation Label', 'jet-engine' );
			$lang          = method_exists( $sitepress, 'get_current_language' ) ? $sitepress->get_current_language() : null;

			foreach ( $args['labels'] as $key => $label ) {

				if ( 'name' === $key ) {
					continue;
				}

				if ( empty( $label ) ) {
					continue;
				}

				do_action( 'wpml_register_single_string', 'Jet Engine Relations Labels', $relation_name . ' - ' . $label, $label );
				$args['labels'][ $key ] = apply_filters( 'wpml_translate_single_string', $label, 'Jet Engine Relations Labels', $relation_name . ' - ' . $label, $lang );
			}

			return $args;
		}

		public function set_translated_check_terms( $terms, $tax ) {

			return array_map( function ( $term ) use ( $tax ) {
				return apply_filters( 'wpml_object_id', $term, $tax, true );
			}, $terms );
		}

	}

}

new Jet_Engine_WPML_Package();
