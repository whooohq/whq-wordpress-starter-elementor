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

if ( ! class_exists( 'Jet_Engine_Elementor_Frontend' ) ) {

	/**
	 * Define Jet_Engine_Elementor_Frontend class
	 */
	class Jet_Engine_Elementor_Frontend {

		private $processed_listing_id = null;
		private $css_added = array();

		private $reset_excerpt_flag = false;

		private $inner_templates = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			/**
			 * Disable to improve performance, requires testing
			 * add_action( 'elementor/frontend/after_enqueue_scripts', array( jet_engine()->frontend, 'frontend_scripts' ) );
			 */

			add_action( 'elementor/frontend/after_enqueue_styles', array( jet_engine()->frontend, 'frontend_styles' ) );
			add_action( 'elementor/preview/enqueue_scripts',       array( jet_engine()->frontend, 'preview_scripts' ) );
			add_action( 'elementor/preview/enqueue_scripts',       array( $this, 'preview_scripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_listing_css' ) );
			add_action( 'jet-engine/locations/enqueue-location-css', array( $this, 'loc_enqueue_listing_css' ) );

			add_action( 'jet-engine/ajax-handlers/before-call-handler', array( $this, 'register_assets_on_ajax' ) );

			add_filter( 'jet-engine/listing/frontend/js-settings', array( $this, 'modify_localize_data' ) );
		}

		/**
		 * Enqueue preview scripts
		 */
		public function preview_scripts() {

			wp_enqueue_script(
				'jet-engine-elementor-preview',
				jet_engine()->plugin_url( 'assets/js/admin/elementor-views/preview.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_engine()->get_version(),
				true
			);

			wp_localize_script( 'jet-engine-elementor-preview', 'JetEngineElementorPreviewConfig', array(
				'i18n' => array(
					'edit' => __( 'Edit Listing Grid Item', 'jet-engine' ),
					'back' => __( 'Back to Edit Parent Post', 'jet-engine' ),
				),
			) );

			$preview_css = '.jet-engine-document-back-handle,.jet-engine-document-handle{position:absolute;top:0;left:0;z-index:100;display:none;align-items:center;justify-content:center;width:25px;height:25px;font-size:11px;color:#fff;background:#fcb92c;transition:0.3s;cursor:pointer}.elementor-editor-active .elementor[data-elementor-type=jet-listing-items]{position:relative}.elementor-editor-active .elementor[data-elementor-type=jet-listing-items]:not(.elementor-edit-mode):hover{box-shadow:none}.elementor-editor-active .jet-engine-document-edit-item.elementor-edit-mode,.elementor-editor-active .jet-listing-grid:hover .jet-engine-document-edit-item:not(.elementor-edit-mode){box-shadow:0 0 0 1px #fcb92c}.elementor-editor-active .jet-engine-document-edit-item.elementor-edit-mode .jet-engine-document-back-handle,.elementor-editor-active .jet-listing-grid:hover .jet-engine-document-edit-item:not(.elementor-edit-mode) .jet-engine-document-handle{display:flex}';

			wp_add_inline_style( 'jet-engine-frontend', $preview_css );

		}

		/**
		 * Ensure inline CSS added on AJAX widget render
		 */
		public function maybe_add_inline_css( $post_id ) {

			if ( ! empty( $_REQUEST['addedPostCSS'] ) && is_array( $_REQUEST['addedPostCSS'] ) && in_array( $post_id, $_REQUEST['addedPostCSS'] ) ) {
				return;
			}

			if ( in_array( $post_id, $this->css_added ) ) {
				return;
			}

			$css_file = \Elementor\Core\Files\CSS\Post::create( $post_id );

			wp_styles()->done[] = 'elementor-frontend';

			if ( 'internal' === get_option( 'elementor_css_print_method' ) ) {
				$css_file->enqueue();
			} else {
				$css = $css_file->get_content();

				if ( ! empty( $css ) ) {
					$meta = $css_file->get_meta();

					if ( ! empty( $meta['fonts'] ) ) {
						foreach ( $meta['fonts'] as $font ) {
							Elementor\Plugin::$instance->frontend->enqueue_font( $font );
						}
					}

					if ( ! empty( $meta['icons'] ) ) {
						$icons_types = Elementor\Icons_Manager::get_icon_manager_tabs();
						foreach ( $meta['icons'] as $icon_font ) {
							if ( ! isset( $icons_types[ $icon_font ] ) ) {
								continue;
							}
							Elementor\Plugin::$instance->frontend->enqueue_font( $icon_font );
						}
					}

					$css_file->print_css();
				}
			}

			$this->css_added[] = $post_id;
		}

		/**
		 * Returns listing content for given listing ID
		 *
		 * @param  $listing_id
		 * @return string
		 */
		public function get_listing_content( $listing_id ) {

			static $is_edit_mode = null;

			if ( null === $is_edit_mode ) {
				$is_edit_mode = Elementor\Plugin::instance()->editor->is_edit_mode();
			}

			$add_inline_css = ! $is_edit_mode && ( wp_doing_ajax() || Jet_Engine_Tools::wp_doing_rest() ) && ! jet_engine()->elementor_views->is_editor_ajax();
			$add_inline_css = apply_filters( 'jet-engine/elementor-views/frontend/add-inline-css', $add_inline_css );

			if ( $add_inline_css ) {
				$this->maybe_add_inline_css( $listing_id );
			}

			$initial_processed_listing_id = $this->processed_listing_id;
			$this->processed_listing_id   = $listing_id;

			$initial_inner_templates = $this->inner_templates;
			$this->inner_templates   = array();

			add_filter( 'elementor/frontend/the_content', array( $this, 'add_link_to_content' ) );

			add_action( 'elementor/frontend/before_get_builder_content', array( $this, 'maybe_reset_excerpt_flag' ), 10, 2 );
			add_action( 'elementor/frontend/before_get_builder_content', array( $this, 'find_inner_templates' ) );

			$content = Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $listing_id, $is_edit_mode );

			if ( $this->reset_excerpt_flag ) {
				Elementor\Plugin::instance()->frontend->start_excerpt_flag( null );
				$this->reset_excerpt_flag = false;
			}

			if ( null === $initial_processed_listing_id ) {
				remove_filter( 'elementor/frontend/the_content', array( $this, 'add_link_to_content' ) );
				remove_action( 'elementor/frontend/before_get_builder_content', array( $this, 'maybe_reset_excerpt_flag' ), 10 );
				remove_action( 'elementor/frontend/before_get_builder_content', array( $this, 'find_inner_templates' ) );
			}

			$inner_templates = array_unique( $this->inner_templates );

			$this->processed_listing_id = $initial_processed_listing_id;
			$this->inner_templates      = $initial_inner_templates;

			return apply_filters( 'jet-engine/elementor-views/frontend/listing-content', $content, $listing_id, $inner_templates );
		}

		/**
		 * Maybe reset excerpt flag so that inner elementor templates can print their styles.
		 *
		 * @param $document
		 * @param $is_excerpt
		 */
		public function maybe_reset_excerpt_flag( $document, $is_excerpt ) {

			//if ( 'internal' !== get_option( 'elementor_css_print_method' ) ) {
			//	return;
			//}

			if ( ! $this->processed_listing_id ) {
				return;
			}

			$post_id = $document->get_post()->ID;

			// Added for nested listings.
			if ( $this->reset_excerpt_flag && (int) $post_id === (int) $this->processed_listing_id ) {
				Elementor\Plugin::instance()->frontend->start_excerpt_flag( null );

				$this->reset_excerpt_flag = false;
			}

			if ( ! $is_excerpt ) {
				return;
			}

			if ( (int) $post_id !== (int) $this->processed_listing_id && ! in_array( $post_id, $this->css_added ) ) {
				Elementor\Plugin::instance()->frontend->end_excerpt_flag( null );

				$this->reset_excerpt_flag = true;
				$this->add_to_css_added( $post_id );
			}
		}

		/**
		 * Find inner templates ids in listing item.
		 *
		 * @param $document
		 */
		public function find_inner_templates( $document ) {

			$doc_name = $document->get_name();
			$doc_id   = $document->get_main_id();

			if ( in_array( $doc_name, array( 'section', 'page' ) ) && ! in_array( $doc_id, $this->inner_templates ) ) {

				$this->inner_templates[] = $doc_id;

				$dynamic_tags = Elementor\Plugin::instance()->dynamic_tags;

				add_action( 'elementor/css-file/post/enqueue', function ( $css_file ) use ( $doc_id, $dynamic_tags ) {

					if ( $css_file instanceof Elementor\Core\DynamicTags\Dynamic_CSS ) {
						return;
					}

					if ( (int) $doc_id !== (int) $css_file->get_post_id() ) {
						return;
					}

					remove_action( 'elementor/css-file/post/enqueue', array( $dynamic_tags, 'after_enqueue_post_css' ) );
				}, 9 );

				add_action( 'elementor/css-file/post/enqueue', function ( $css_file ) use ( $doc_id, $dynamic_tags ) {

					if ( $css_file instanceof Elementor\Core\DynamicTags\Dynamic_CSS ) {
						return;
					}

					if ( (int) $doc_id !== (int) $css_file->get_post_id() ) {
						return;
					}

					add_action( 'elementor/css-file/post/enqueue', array( $dynamic_tags, 'after_enqueue_post_css' ) );
				}, 11 );
			}
		}

		/**
		 * Add listing link to content
		 *
		 * @param $content
		 * @return string
		 */
		public function add_link_to_content( $content ) {

			if ( ! $this->processed_listing_id ) {
				return $content;
			}

			$document = Elementor\Plugin::$instance->documents->get_doc_for_frontend( $this->processed_listing_id );

			if ( ! $document ) {
				return $content;
			}

			$settings = $document->get_settings();

			if ( empty( $settings ) || empty( $settings['listing_link'] ) ) {
				return $content;
			}

			$url = apply_filters(
				'jet-engine/elementor-views/frontend/custom-listing-url',
				false,
				$settings
			);

			if ( ! $url ) {
				$source = ! empty( $settings['listing_link_source'] ) ? $settings['listing_link_source'] : '_permalink';

				if ( '_permalink' === $source ) {
					$url = jet_engine()->listings->data->get_current_object_permalink();
				} elseif ( 'open_map_listing_popup' === $source ) {
					$url = jet_engine()->modules->get_module( 'maps-listings' )->instance->get_action_url();
				} elseif ( 'open_map_listing_popup_hover' === $source ) {
					$url = jet_engine()->modules->get_module( 'maps-listings' )->instance->get_action_url( null, 'hover' );
				} elseif ( 'options_page' === $source ) {
					$option = ! empty( $settings['listing_link_option'] ) ? $settings['listing_link_option'] : false;
					$url    = jet_engine()->listings->data->get_option( $option );
				} elseif ( $source ) {
					$url = jet_engine()->listings->data->get_meta( $source );
				}
			}

			$prefix = isset( $settings['listing_link_prefix'] ) ? $settings['listing_link_prefix'] : '';

			if ( $prefix ) {
				$url = $prefix . $url;
			}

			$overlay_attrs = array(
				'class'    => 'jet-engine-listing-overlay-wrap',
				'data-url' => $url,
			);

			$link_attrs = array(
				'href'  => $url,
				'class' => 'jet-engine-listing-overlay-link',
			);

			$open_in_new = isset( $settings['listing_link_open_in_new'] ) ? $settings['listing_link_open_in_new'] : '';
			$rel_attr    = isset( $settings['listing_link_rel_attr'] ) ? $settings['listing_link_rel_attr'] : '';

			if ( $open_in_new ) {
				$overlay_attrs['data-target'] = '_blank';
				$link_attrs['target']         = '_blank';
			}

			if ( $rel_attr ) {
				$link_attrs['rel'] = $rel_attr;
			}

			$link = sprintf( '<a %s></a>', Jet_Engine_Tools::get_attr_string( $link_attrs ) );

			return sprintf(
				'<div %3$s>%1$s%2$s</div>',
				$content,
				$link,
				Jet_Engine_Tools::get_attr_string( $overlay_attrs )
			);
		}


		/**
		 * Check if current page build with elementor and contain listing - enqueue listing CSS in header
		 * Do this to avoid unstyled content flashing on page load
		 *
		 * @param $post_id
		 */
		public function maybe_enqueue_listing_css( $post_id = null ) {

			if ( ! $post_id ) {
				$post_id = get_the_ID();
			}

			if ( ! $post_id ) {
				return;
			}

			$elementor_data = get_post_meta( $post_id, '_elementor_data', true );

			if ( ! $elementor_data ) {
				return;
			}

			if ( is_array( $elementor_data ) ) {
				$elementor_data = json_encode( $elementor_data );
			}

			preg_match_all( '/[\'\"]lisitng_id[\'\"]\:[\'\"](\d+)[\'\"]/', $elementor_data, $matches );

			if ( empty( $matches[1] ) ) {
				return;
			}

			foreach ( $matches[1] as $listing_id ) {

				if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new Elementor\Core\Files\CSS\Post( $listing_id );
				} else {
					$css_file = new Elementor\Post_CSS_File( $listing_id );
				}

				$css_file->enqueue();
				$this->css_added[] = $listing_id;

				// For nested listings
				$this->maybe_enqueue_listing_css( $listing_id );
			}

			do_action( 'jet-engine/elementor-views/frontend/after_enqueue_listing_css', $this, $post_id, $elementor_data );

		}

		/**
		 * [loc_enqueue_listing_css description]
		 * @param $template_id
		 */
		public function loc_enqueue_listing_css( $template_id ) {
			$this->maybe_enqueue_listing_css( $template_id );
		}

		public function register_assets_on_ajax() {

			if ( isset( $_REQUEST['isEditMode'] ) && filter_var( $_REQUEST['isEditMode'], FILTER_VALIDATE_BOOLEAN ) ) {
				return;
			}

			Elementor\Plugin::instance()->frontend->register_styles();
			Elementor\Plugin::instance()->frontend->register_scripts();
		}

		public function modify_localize_data( $data ) {

			if ( ! empty( $this->css_added ) ) {
				$data['addedPostCSS'] = $this->css_added;
			}

			return $data;
		}

		public function add_to_css_added( $id ) {
			if ( ! in_array( $id, $this->css_added ) ) {
				$this->css_added[] = $id;
			}
		}
	}

}
