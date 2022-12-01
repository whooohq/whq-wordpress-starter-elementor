<?php
/**
 * Elementor views manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Blocks_Views_Editor' ) ) {

	/**
	 * Define Jet_Engine_Blocks_Views_Editor class
	 */
	class Jet_Engine_Blocks_Views_Editor {

		public function __construct() {

			add_action( 'enqueue_block_editor_assets', array( $this, 'blocks_assets' ), -1 );

			add_action( 'add_meta_boxes', array( $this, 'add_css_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta' ) );

		}

		public function save_meta( $post_id ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( isset( $_POST['_jet_engine_listing_css'] ) ) {
				$css = esc_attr( $_POST['_jet_engine_listing_css'] );
				update_post_meta( $post_id, '_jet_engine_listing_css', $css );
			}

			$settings_keys = array(
				'jet_engine_listing_source',
				'jet_engine_listing_post_type',
				'jet_engine_listing_tax',
				'jet_engine_listing_repeater_source',
				'jet_engine_listing_repeater_field',
				'jet_engine_listing_repeater_option',
			);

			$settings_to_store    = array();
			$el_settings_to_store = array();

			foreach ( $settings_keys as $key ) {
				if ( isset( $_POST[ $key ] ) ) {
					$store_key = str_ireplace( 'jet_engine_listing_', '', $key );

					if ( false !== strpos( $store_key, 'repeater_' ) ) {
						$el_settings_to_store[ $store_key ] = esc_attr( $_POST[ $key ] ); // repeater settings store only to `_elementor_page_settings`
					} else {
						$settings_to_store[ $store_key ] = esc_attr( $_POST[ $key ] );
						$el_settings_to_store[ 'listing_' . $store_key ] = esc_attr( $_POST[ $key ] );
					}
				}
			}

			if ( ! empty( $settings_to_store ) ) {

				$listing_settings = get_post_meta( $post_id, '_listing_data', true );
				$elementor_page_settings = get_post_meta( $post_id, '_elementor_page_settings', true );

				if ( empty( $listing_settings ) ) {
					$listing_settings = array();
				}

				if ( empty( $elementor_page_settings ) ) {
					$elementor_page_settings = array();
				}

				$listing_settings        = array_merge( $listing_settings, $settings_to_store );
				$elementor_page_settings = array_merge( $elementor_page_settings, $el_settings_to_store );

				update_post_meta( $post_id, '_listing_data', $listing_settings );
				update_post_meta( $post_id, '_elementor_page_settings', $elementor_page_settings );

			}

			do_action( 'jet-engine/blocks/editor/save-settings', $post_id );

		}

		/**
		 * Add listing item CSS metabox
		 */
		public function add_css_meta_box() {

			add_meta_box(
				'jet_engine_lisitng_settings',
				__( 'Listing Item Settings', 'jet-engine' ),
				array( $this, 'render_settings_box' ),
				jet_engine()->listings->post_type->slug(),
				'side'
			);

			add_meta_box(
				'jet_engine_lisitng_css',
				__( 'Listing Items CSS', 'jet-engine' ),
				array( $this, 'render_css_box' ),
				jet_engine()->listings->post_type->slug(),
				'side'
			);

		}

		/**
		 * Render box settings HTML
		 *
		 * @return [type] [description]
		 */
		public function render_settings_box( $post ) {

			$settings      = get_post_meta( $post->ID, '_listing_data', true );
			$page_settings = get_post_meta( $post->ID, '_elementor_page_settings', true );

			if ( empty( $settings ) ) {
				$settings = array();
			}

			$source = ! empty( $settings['source'] ) ? $settings['source'] : 'posts';

			$controls = array(
				'jet_engine_listing_source' => array(
					'label'   => __( 'Listing Source', 'jet-engine' ),
					'options' => jet_engine()->listings->post_type->get_listing_item_sources(),
					'value'   => $source,
				),
				'jet_engine_listing_post_type' => array(
					'label'   => __( 'Listing Post Type', 'jet-engine' ),
					'options' => jet_engine()->listings->get_post_types_for_options(),
					'value'   => ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post',
					'source'  => array( 'posts', 'repeater' ),
				),
				'jet_engine_listing_tax' => array(
					'label'   => __( 'Listing Taxonomy', 'jet-engine' ),
					'options' => jet_engine()->listings->get_taxonomies_for_options(),
					'value'   => ! empty( $settings['tax'] ) ? $settings['tax'] : 'category',
					'source'  => array( 'terms' ),
				),
				'jet_engine_listing_repeater_source' => array(
					'label'   => __( 'Repeater source', 'jet-engine' ),
					'options' => jet_engine()->listings->repeater_sources(),
					'value'   => ! empty( $page_settings['repeater_source'] ) ? $page_settings['repeater_source'] : 'jet_engine',
					'source'  => array( 'repeater' ),
				),
				'jet_engine_listing_repeater_field' => array(
					'label'       => __( 'Repeater field', 'jet-engine' ),
					'description' => __( 'If JetEngine, or ACF, or etc selected as source.', 'jet-engine' ),
					'value'       => ! empty( $page_settings['repeater_field'] ) ? $page_settings['repeater_field'] : '',
					'source'      => array( 'repeater' ),
				),
				'jet_engine_listing_repeater_option' => array(
					'label'       => __( 'Repeater option', 'jet-engine' ),
					'description' => __( 'If <b>JetEngine Options Page</b> selected as source.', 'jet-engine' ),
					'groups'      => jet_engine()->options_pages->get_options_for_select( 'repeater' ),
					'value'       => ! empty( $page_settings['repeater_option'] ) ? $page_settings['repeater_option'] : '',
					'source'      => array( 'repeater' ),
				),
			);

			$controls = apply_filters( 'jet-engine/blocks/editor/controls/settings', $controls, $settings, $post );

			echo '<style>
				.jet-engine-base-control select,
				.jet-engine-base-control input {
					box-sizing: border-box;
					margin: 0;
				}
				.jet-engine-base-control .components-base-control__field {
					margin: 0 0 10px;
				}
				.jet-engine-base-control .components-base-control__label {
					display: block;
					font-weight: bold;
					padding: 0 0 5px;
				}
				.jet-engine-base-control .components-base-control__help {
					font-size: 12px;
					font-style: normal;
					color: #757575;
					margin: 5px 0 0;
				}
				.jet-engine-condition-setting {
					display: none;
				}
				.jet-engine-condition-setting-show {
					display: block;
				}
			</style>';
			echo '<div class="components-base-control jet-engine-base-control">';

				foreach ( $controls as $control_name => $control_args ) {

					$field_classes = array(
						'components-base-control__field',
					);

					if ( ! empty( $control_args['source'] ) ) {
						$field_classes[] = 'jet-engine-condition-setting';

						if ( ! is_array( $control_args['source'] ) ) {
							$control_args['source'] = explode( ',', $control_args['source'] );
						}

						foreach ( $control_args['source'] as $_source ) {
							$field_classes[] = 'jet-engine-condition-source-' . $_source;
						}

						if ( in_array( $source , $control_args['source'] ) ) {
							$field_classes[] = 'jet-engine-condition-setting-show';
						}
					}

					echo '<div class="' . join( ' ', $field_classes ) . '">';
						echo '<label class="components-base-control__label" for="' . $control_name . '">';
							echo $control_args['label'];
						echo '</label>';

						if ( ! empty( $control_args['groups'] ) || ! empty( $control_args['options'] ) ) {
							echo '<select id="' . $control_name . '" name="' . $control_name . '" class="components-select-control__input">';

								if ( ! empty( $control_args['groups'] ) ) {

									foreach ( $control_args['groups'] as $group_key => $group ) {

										if ( empty( $group ) ) {
											continue;
										}

										if ( ! empty( $group['options'] ) ) {
											echo '<optgroup label="' . $group['label'] . '">';

											foreach ( $group['options'] as $option_key => $option_label ) {
												printf( '<option value="%1$s"%3$s>%2$s</option>',
													$option_key,
													$option_label,
													selected( $control_args['value'], $option_key, false )
												);
											}

											echo '</optgroup>';

										} elseif ( is_string( $group ) ) {
											printf( '<option value="%1$s"%3$s>%2$s</option>',
												$group_key,
												$group,
												selected( $control_args['value'], $group_key, false )
											);
										}
									}

								} else {
									foreach ( $control_args['options'] as $option_key => $option_label ) {
										printf( '<option value="%1$s"%3$s>%2$s</option>',
											$option_key,
											$option_label,
											selected( $control_args['value'], $option_key, false )
										);
									}
								}

							echo '</select>';
						} else {
							echo '<input id="' . $control_name . '" name="' . $control_name . '" class="components-text-control__input" value="' . $control_args['value'] . '">';
						}

						if ( ! empty( $control_args['description'] ) ) {
							echo '<p class="components-base-control__help">' . $control_args['description'] . '</p>';
						}

					echo '</div>';
				}

				do_action( 'jet-engine/blocks/editor/settings-meta-box', $post );

				echo '<p>';
					_e( 'You need to reload page after saving to apply new settings', 'jet-engine' );
				echo '</p>';
			echo '</div>';

			echo "<script>
					jQuery( '[name=\"jet_engine_listing_source\"]' ).on( 'change', function() {
						var sourceSelect = jQuery( this ), source = sourceSelect.val();
						sourceSelect.closest( '.jet-engine-base-control' ).find( '.jet-engine-condition-setting' ).each( function() {
							if ( jQuery( this ).hasClass( 'jet-engine-condition-source-' + source ) ) {
								jQuery( this ).addClass( 'jet-engine-condition-setting-show' );
							} else {
								jQuery( this ).removeClass( 'jet-engine-condition-setting-show' );
							}
						} );
					} );
				</script>";
		}

		/**
		 * Render CSS metabox
		 *
		 * @return [type] [description]
		 */
		public function render_css_box( $post ) {

			$css = get_post_meta( $post->ID, '_jet_engine_listing_css', true );

			if ( ! $css ) {
				$css = '';
			}

			?>
			<div class="jet-eingine-listing-css">
				<p><?php
					_e( 'When targeting your specific element, add <code>selector</code> before the tags and classes you want to exclusively target, i.e: <code>selector a { color: red;}</code>', 'jet-engine' );
				?></p>
				<textarea class="components-textarea-control__input jet_engine_listing_css" name="_jet_engine_listing_css" rows="16" style="width:100%"><?php
					echo $css;
				?></textarea>
			</div>
			<?php

		}

		/**
		 * Get meta fields for post type
		 *
		 * @return array
		 */
		public function get_meta_fields() {

			if ( jet_engine()->meta_boxes ) {
				return jet_engine()->meta_boxes->get_fields_for_select( 'plain', 'blocks' );
			} else {
				return array();
			}

		}

		/**
		 * Get meta fields for post type
		 *
		 * @return array
		 */
		public function get_repeater_fields() {

			if ( jet_engine()->meta_boxes ) {
				$groups = jet_engine()->meta_boxes->get_fields_for_select( 'repeater', 'blocks' );
			} else {
				$groups = array();
			}

			if ( jet_engine()->options_pages ) {
				$groups[] = array(
					'label'  => __( 'Other', 'jet-engine' ),
					'values' => array(
						array(
							'value' => 'options_page',
							'label' => __( 'Options' ),
						),
					),
				);
			}

			$extra_fields = apply_filters( 'jet-engine/listings/dynamic-repeater/fields', array() );

			if ( ! empty( $extra_fields ) ) {

				foreach ( $extra_fields as $key => $data ) {

					if ( ! is_array( $data ) ) {

						$groups[] = array(
							'label'  => $data,
							'values' => array(
								array(
									'value' => $key,
									'label' => $data,
								),
							),
						);

						continue;
					}

					$values = array();

					if ( ! empty( $data['options'] ) ) {
						foreach ( $data['options'] as $val => $label ) {
							$values[] = array(
								'value' => $val,
								'label' => $label,
							);
						}
					}

					$groups[] = array(
						'label'  => $data['label'],
						'values' => $values,
					);
				}
			}

			return $groups;

		}

		/**
		 * Get meta fields for post type
		 *
		 * @return array
		 */
		public function get_dynamic_sources( $for = 'media' ) {

			if ( 'media' === $for ) {

				$default = array(
					'label'  => __( 'General', 'jet-engine' ),
					'values' => array(
						array(
							'value' => 'post_thumbnail',
							'label' => __( 'Post thumbnail', 'jet-engine' ),
						),
						array(
							'value' => 'user_avatar',
							'label' => __( 'User avatar (works only for user listing and pages)', 'jet-engine' ),
						),
					),
				);

			} else {

				$default = array(
					'label'  => __( 'General', 'jet-engine' ),
					'values' => array(
						array(
							'value' => '_permalink',
							'label' => __( 'Permalink', 'jet-engine' ),
						),
						array(
							'value' => 'delete_post_link',
							'label' => __( 'Delete current post link', 'jet-engine' ),
						),
					),
				);

				if ( jet_engine()->modules->is_module_active( 'profile-builder' ) ) {
					$default['values'][] = array(
						'value' => 'profile_page',
						'label' => __( 'Profile Page', 'jet-engine' ),
					);
				}

			}

			$result      = array();
			$meta_fields = array();

			if ( jet_engine()->meta_boxes ) {
				$meta_fields = jet_engine()->meta_boxes->get_fields_for_select( $for, 'blocks' );
			}

			if ( jet_engine()->options_pages ) {
				$default['values'][] = array(
					'value' => 'options_page',
					'label' => __( 'Options', 'jet-engine' ),
				);
			}

			$result = apply_filters(
				'jet-engine/blocks-views/editor/dynamic-sources/fields',
				array_merge( array( $default ), $meta_fields ),
				$for
			);

			if ( 'media' === $for ) {
				$hook_name = 'jet-engine/listings/dynamic-image/fields';
			} else {
				$hook_name = 'jet-engine/listings/dynamic-link/fields';
			}

			$extra_fields = apply_filters( $hook_name, array(), $for );

			if ( ! empty( $extra_fields ) ) {

				foreach ( $extra_fields as $key => $data ) {

					if ( ! is_array( $data ) ) {

						$result[] = array(
							'label'  => $data,
							'values' => array(
								array(
									'value' => $key,
									'label' => $data,
								),
							),
						);

						continue;
					}

					$values = array();

					if ( ! empty( $data['options'] ) ) {
						foreach ( $data['options'] as $val => $label ) {
							$values[] = array(
								'value' => $val,
								'label' => $label,
							);
						}
					}

					$result[] = array(
						'label'  => $data['label'],
						'values' => $values,
					);
				}

			}

			return $result;

		}

		/**
		 * Get registered options fields
		 *
		 * @return array
		 */
		public function get_options_fields( $type = 'plain' ) {
			if ( jet_engine()->options_pages ) {
				return jet_engine()->options_pages->get_options_for_select( $type, 'blocks' );
			} else {
				return array();
			}
		}

		/**
		 * Returns filter callbacks list
		 *
		 * @return [type] [description]
		 */
		public function get_filter_callbacks() {

			$callbacks = jet_engine()->listings->get_allowed_callbacks();
			$result    = array( array(
				'value' => '',
				'label' => '--',
			) );

			foreach ( $callbacks as $function => $label ) {
				$result[] = array(
					'value' => $function,
					'label' => $label,
				);
			}

			return $result;

		}

		public function get_filter_callbacks_args() {

			$result     = array();
			$disallowed = array( 'checklist_divider_color' );

			foreach ( jet_engine()->listings->get_callbacks_args() as $key => $args ) {

				if ( in_array( $key, $disallowed ) ) {
					continue;
				}

				$args['prop'] = $key;

				if ( ! empty( $args['description'] ) ) {
					$args['description'] = wp_kses_post( $args['description'] );
				}

				if ( 'select' === $args['type'] ) {

					$options = $args['options'];
					$args['options'] = array();

					foreach ( $options as $value => $label ) {
						$args['options'][] = array(
							'value' => $value,
							'label' => $label,
						);
					}
				}

				// Convert `slider` control to `number` control.
				if ( 'slider' === $args['type'] ) {
					$args['type'] = 'number';

					if ( ! empty( $args['range'] ) ) {

						$first_unit = $this->get_first_key( $args['range'] );

						foreach ( array( 'min', 'max', 'step' ) as $range_arg ) {
							if ( isset( $args['range'][ $first_unit ][ $range_arg ] ) ) {
								$args[ $range_arg ] = $args['range'][ $first_unit ][ $range_arg ];
							}
						}

						unset( $args['range'] );
					}
				}

				$args['condition'] = $args['condition']['filter_callback'];

				$result[] = $args;
			}

			return $result;
		}

		public function get_first_key( $array = array() ) {

			if ( function_exists( 'array_key_first' ) ) {
				return array_key_first( $array );
			} else {
				$keys = array_keys( $array );
				return $keys[0];
			}

		}

		/**
		 * Returns all taxonomies list for options
		 *
		 * @return [type] [description]
		 */
		public function get_taxonomies_for_options() {

			$result     = array();
			$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

			foreach ( $taxonomies as $taxonomy ) {

				if ( empty( $taxonomy->object_type ) || ! is_array( $taxonomy->object_type ) ) {
					continue;
				}

				foreach ( $taxonomy->object_type as $object ) {
					if ( empty( $result[ $object ] ) ) {
						$post_type = get_post_type_object( $object );

						if ( ! $post_type ) {
							continue;
						}

						$result[ $object ] = array(
							'label'  => $post_type->labels->name,
							'values' => array(),
						);
					}

					$result[ $object ]['values'][] = array(
						'value' => $taxonomy->name,
						'label' => $taxonomy->labels->name,
					);

				};
			}

			return array_values( $result );

		}

		/**
		 * Register plugin sidebar
		 *
		 * @return [type] [description]
		 */
		public function blocks_assets() {

			//if ( 'jet-engine' !== get_post_type() ) {
			//	return;
			//}

			do_action( 'jet-engine/blocks-views/editor-script/before' );

			wp_enqueue_script(
				'jet-engine-blocks-views',
				jet_engine()->plugin_url( 'assets/js/admin/blocks-views/blocks.js' ),
				array( 'wp-components', 'wp-element', 'wp-blocks', 'wp-block-editor', 'lodash' ),
				jet_engine()->get_version(),
				true
			);

			wp_enqueue_style(
				'jet-engine-blocks-views',
				jet_engine()->plugin_url( 'assets/css/admin/blocks-views.css' ),
				array(),
				jet_engine()->get_version()
			);

			do_action( 'jet-engine/blocks-views/editor-script/after' );

			global $post;

			$settings = array();
			$post_id  = false;

			if ( $post ) {
				$settings = get_post_meta( $post->ID, '_elementor_page_settings', true );
				$post_id  = $post->ID;
			}

			if ( empty( $settings ) ) {
				$settings = array();
			}

			$source     = ! empty( $settings['listing_source'] ) ? $settings['listing_source'] : 'posts';
			$post_type  = ! empty( $settings['listing_post_type'] ) ? $settings['listing_post_type'] : 'post';
			$tax        = ! empty( $settings['listing_tax'] ) ? $settings['listing_tax'] : 'category';
			$rep_source = ! empty( $settings['repeater_source'] ) ? esc_attr( $settings['repeater_source'] ) : '';
			$rep_field  = ! empty( $settings['repeater_field'] ) ? esc_attr( $settings['repeater_field'] ) : '';
			$rep_option = ! empty( $settings['repeater_option'] ) ? esc_attr( $settings['repeater_option'] ) : '';

			jet_engine()->listings->data->set_listing( jet_engine()->listings->get_new_doc( array(
				'listing_source'    => $source,
				'listing_post_type' => $post_type,
				'listing_tax'       => $tax,
				'repeater_source'   => $rep_source,
				'repeater_field'    => $rep_field,
				'repeater_option'   => $rep_option,
				'is_main'           => true,
			), $post_id ) );

			$current_object_id = $this->get_current_object();
			$field_sources     = jet_engine()->listings->data->get_field_sources();
			$sources           = array();

			foreach ( $field_sources as $value => $label ) {
				$sources[] = array(
					'value' => $value,
					'label' => $label,
				);
			}

			$link_sources = $this->get_dynamic_sources( 'plain' );
			$link_sources = apply_filters( 'jet-engine/blocks-views/dynamic-link-sources', $link_sources );

			$media_sources = $this->get_dynamic_sources( 'media' );
			$media_sources = apply_filters( 'jet-engine/blocks-views/dynamic-media-sources', $media_sources );

			/**
			 * Format:
			 * array(
			 *  	'block-type-name' => array(
			 *  		array(
			 * 				'prop' => 'prop-name-to-set',
			 * 				'label' => 'control-label',
			 * 				'condition' => array(
			 * 					'prop' => array( 'value' ),
			 * 				)
			 * 			)
			 *  	)
			 *  )
			 */
			$custom_controls = apply_filters( 'jet-engine/blocks-views/custom-blocks-controls', array() );
			$custom_panles   = array();

			$config = apply_filters( 'jet-engine/blocks-views/editor-data', array(
				'isJetEnginePostType'   => 'jet-engine' === get_post_type(),
				'settings'              => $settings,
				'object_id'             => $current_object_id,
				'fieldSources'          => $sources,
				'imageSizes'            => jet_engine()->listings->get_image_sizes( 'blocks' ),
				'metaFields'            => $this->get_meta_fields(),
				'repeaterFields'        => $this->get_repeater_fields(),
				'mediaFields'           => $media_sources,
				'linkFields'            => $link_sources,
				'optionsFields'         => $this->get_options_fields( 'plain' ),
				'mediaOptionsFields'    => $this->get_options_fields( 'media' ),
				'userRoles'             => Jet_Engine_Tools::get_user_roles_for_js(),
				'repeaterOptionsFields' => $this->get_options_fields( 'repeater' ),
				'filterCallbacks'       => $this->get_filter_callbacks(),
				'filterCallbacksArgs'   => $this->get_filter_callbacks_args(),
				'taxonomies'            => $this->get_taxonomies_for_options(),
				'queriesList'           => \Jet_Engine\Query_Builder\Manager::instance()->get_queries_for_options( true ),
				'objectFields'          => jet_engine()->listings->data->get_object_fields( 'blocks' ),
				'postTypes'             => Jet_Engine_Tools::get_post_types_for_js(),
				'legacy'                => array(
					'is_disabled' => jet_engine()->listings->legacy->is_disabled(),
					'message'     => jet_engine()->listings->legacy->get_notice(),
				),
				'glossariesList'        => jet_engine()->glossaries->get_glossaries_for_js(),
				'atts'                  => array(
					'dynamicField'    => jet_engine()->blocks_views->block_types->get_block_atts( 'dynamic-field' ),
					'dynamicLink'     => jet_engine()->blocks_views->block_types->get_block_atts( 'dynamic-link' ),
					'dynamicImage'    => jet_engine()->blocks_views->block_types->get_block_atts( 'dynamic-image' ),
					'dynamicRepeater' => jet_engine()->blocks_views->block_types->get_block_atts( 'dynamic-repeater' ),
					'listingGrid'     => jet_engine()->blocks_views->block_types->get_block_atts( 'listing-grid' ),
				),
				'customPanles'          => $custom_panles,
				'customControls'        => $custom_controls,
				'injections'            => apply_filters( 'jet-engine/blocks-views/listing-injections-config', array(
					'enabled' => false,
				) ),
				'relationsTypes'        => array(
					array(
						'value' => 'grandparents',
						'label' => __( 'Grandparent Posts', 'jet-engine' ),
					),
					array(
						'value' => 'grandchildren',
						'label' => __( 'Grandchildren Posts', 'jet-engine' ),
					),
				),
				'listingOptions'   => jet_engine()->listings->get_listings_for_options( 'blocks' ),
				'hideOptions'      => jet_engine()->listings->get_widget_hide_options( 'blocks' ),
				'activeModules'    => jet_engine()->modules->get_active_modules(),
				'blocksWithIdAttr' => jet_engine()->blocks_views->block_types->get_blocks_with_id_attr(),
			) );

			wp_localize_script(
				'jet-engine-blocks-views',
				'JetEngineListingData',
				apply_filters( 'jet-engine/blocks-views/editor/config', $config )
			);

		}

		/**
		 * Returns information about current object
		 *
		 * @param  [type] $source [description]
		 * @return [type]         [description]
		 */
		public function get_current_object() {

			if ( 'jet-engine' !== get_post_type() ) {
				return get_the_ID();
			}

			$source    = jet_engine()->listings->data->get_listing_source();
			$object_id = null;

			switch ( $source ) {

				case 'posts':
				case 'repeater':

					$post_type = jet_engine()->listings->data->get_listing_post_type();

					$posts = get_posts( array(
						'post_type'        => $post_type,
						'numberposts'      => 1,
						'orderby'          => 'date',
						'order'            => 'DESC',
						'suppress_filters' => false,
					) );

					if ( ! empty( $posts ) ) {
						$post = $posts[0];
						jet_engine()->listings->data->set_current_object( $post );
						$object_id = $post->ID;
					}

					break;

				case 'terms':

					$tax   = jet_engine()->listings->data->get_listing_tax();
					$terms = get_terms( array(
						'taxonomy'   => $tax,
						'hide_empty' => false,
					) );

					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$term = $terms[0];
						jet_engine()->listings->data->set_current_object( $term );
						$object_id = $term->term_id;
					}

					break;

				case 'users':

					$object_id = get_current_user_id();
					jet_engine()->listings->data->set_current_object( wp_get_current_user() );

					break;

				default:

					$object_id = apply_filters(
						'jet-engine/blocks-views/editor/config/object/' . $source,
						false,
						$this
					);

					break;

			}

			return $object_id;

		}

	}

}
