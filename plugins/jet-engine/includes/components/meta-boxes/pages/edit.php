<?php
/**
 * Meta boxes edit page class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Meta_Boxes_Page_Edit' ) ) {

	/**
	 * Define Jet_Engine_Meta_Boxes_Page_Edit class
	 */
	class Jet_Engine_Meta_Boxes_Page_Edit extends Jet_Engine_CPT_Page_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			if ( $this->item_id() ) {
				return 'edit';
			} else {
				return 'add';
			}
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			if ( $this->item_id() ) {
				return esc_html__( 'Edit Meta Box', 'jet-engine' );
			} else {
				return esc_html__( 'Add New Meta Box', 'jet-engine' );
			}
		}

		/**
		 * Returns currently requested items ID.
		 * If this funciton returns an empty result - this is add new item page
		 *
		 * @return [type] [description]
		 */
		public function item_id() {
			return isset( $_GET['id'] ) ? esc_attr( $_GET['id'] ) : false;
		}

		/**
		 * Include meta fields component related assets and templates
		 *
		 * @return [type] [description]
		 */
		public static function enqueue_meta_fields( $args = array() ) {

			wp_enqueue_script(
				'jet-engine-admin-tools',
				jet_engine()->plugin_url( 'assets/js/admin/tools.js' ),
				array(),
				jet_engine()->get_version(),
				true
			);

			wp_enqueue_script(
				'jet-engine-meta-fields',
				jet_engine()->plugin_url( 'includes/components/meta-boxes/assets/js/fields.js' ),
				array( 'cx-vue-ui', 'wp-api-fetch' ),
				jet_engine()->get_version(),
				true
			);

			$title         = ! empty( $args['title'] ) ? $args['title'] : __( 'Meta fields', 'jet-engine' );
			$button        = ! empty( $args['button'] ) ? $args['button'] : __( 'New Meta Field', 'jet-engine' );
			$disabled      = ! empty( $args['disabled'] ) ? $args['disabled'] : array();
			$allowed_types = ! empty( $args['allowed_types'] ) ? $args['allowed_types'] : false;

			wp_localize_script( 'jet-engine-meta-fields', 'JetEngineFieldsConfig', apply_filters( 'jet-engine/meta-fields/config', array(
				'title'               => $title,
				'button'              => $button,
				'post_types'          => Jet_Engine_Tools::get_post_types_for_js(),
				'disabled'            => $disabled,
				'quick_edit_supports' => array( 'text', 'date', 'time', 'datetime-local', 'textarea', 'select', 'radio', 'checkbox', 'number' ),
				'i18n'                => array( 'select_field' => __( 'Select field...', 'jet-engine' ), ),
				'allowed_types'       => $allowed_types,
				'field_types'         => array(
					array(
						'value' => 'text',
						'label' => __( 'Text', 'jet-engine' ),
					),
					array(
						'value' => 'date',
						'label' => __( 'Date', 'jet-engine' ),
					),
					array(
						'value' => 'time',
						'label' => __( 'Time', 'jet-engine' ),
					),
					array(
						'value' => 'datetime-local',
						'label' => __( 'Datetime', 'jet-engine' ),
					),
					array(
						'value' => 'textarea',
						'label' => __( 'Textarea', 'jet-engine' ),
					),
					array(
						'value' => 'wysiwyg',
						'label' => __( 'WYSIWYG', 'jet-engine' ),
					),
					array(
						'value' => 'switcher',
						'label' => __( 'Switcher', 'jet-engine' ),
					),
					array(
						'value' => 'checkbox',
						'label' => __( 'Checkbox', 'jet-engine' ),
					),
					array(
						'value' => 'iconpicker',
						'label' => __( 'Iconpicker', 'jet-engine' ),
					),
					array(
						'value' => 'media',
						'label' => __( 'Media', 'jet-engine' ),
					),
					array(
						'value' => 'gallery',
						'label' => __( 'Gallery', 'jet-engine' ),
					),
					array(
						'value' => 'radio',
						'label' => __( 'Radio', 'jet-engine' ),
					),
					array(
						'value' => 'repeater',
						'label' => __( 'Repeater', 'jet-engine' ),
					),
					array(
						'value' => 'select',
						'label' => __( 'Select', 'jet-engine' ),
					),
					array(
						'value' => 'number',
						'label' => __( 'Number', 'jet-engine' ),
					),
					array(
						'value' => 'colorpicker',
						'label' => __( 'Colorpicker', 'jet-engine' ),
					),
					array(
						'value' => 'posts',
						'label' => __( 'Posts', 'jet-engine' ),
					),
					array(
						'value' => 'html',
						'label' => __( 'HTML', 'jet-engine' ),
					),
				)
			) ) );

			add_action( 'admin_footer', array( __CLASS__, 'add_meta_fields_template' ) );

		}

		/**
		 * Register add controls
		 * @return [type] [description]
		 */
		public function page_specific_assets() {

			$module_data = jet_engine()->framework->get_included_module_data( 'cherry-x-vue-ui.php' );

			$ui = new CX_Vue_UI( $module_data );

			$ui->enqueue_assets();

			self::enqueue_meta_fields();

			wp_enqueue_script(
				'jet-engine-meta-delete-dialog',
				jet_engine()->plugin_url( 'includes/components/meta-boxes/assets/js/delete-dialog.js' ),
				array( 'cx-vue-ui', 'wp-api-fetch', ),
				jet_engine()->get_version(),
				true
			);

			wp_localize_script(
				'jet-engine-meta-delete-dialog',
				'JetEngineCPTDeleteDialog',
				array(
					'types'    => Jet_Engine_Tools::get_post_types_for_js(),
					'api_path' => jet_engine()->api->get_route( 'delete-meta-box' ),
					'redirect' => $this->manager->get_page_link( 'list' ),
				)
			);

			wp_enqueue_script(
				'jet-engine-mb-edit',
				jet_engine()->plugin_url( 'includes/components/meta-boxes/assets/js/edit.js' ),
				array( 'cx-vue-ui', 'wp-api-fetch' ),
				jet_engine()->get_version(),
				true
			);

			$id = $this->item_id();

			if ( $id ) {
				$button_label = __( 'Update Meta Box', 'jet-engine' );
				$redirect     = false;
			} else {
				$button_label = __( 'Add Meta Box', 'jet-engine' );
				$redirect     = $this->manager->get_edit_item_link( '%id%' );
			}

			wp_localize_script(
				'jet-engine-mb-edit',
				'JetEngineMBConfig',
				$this->manager->get_admin_page_config( array(
					'api_path_edit'     => jet_engine()->api->get_route( $this->get_slug() . '-meta-box' ),
					'item_id'           => $id,
					'edit_button_label' => $button_label,
					'redirect'          => $redirect,
					'conditions'        => $this->manager->conditions->get_conditions_data_for_edit(),
					'user_roles'        => Jet_Engine_Tools::get_user_roles_for_js(),
					'sources'           => $this->manager->get_sources(),
					'help_links'        => array(
						array(
							'url'   => 'https://crocoblock.com/knowledge-base/articles/how-to-create-a-custom-meta-field-with-jetengine-custom-meta-field-types-overview/?utm_source=jetengine&utm_medium=meta-box-page&utm_campaign=need-help',
							'label' => __( 'How to create a custom meta field. Custom meta field types overview', 'jet-engine' ),
						),
						array(
							'url'   => 'https://crocoblock.com/knowledge-base/articles/how-to-group-the-custom-meta-fields-for-the-certain-post-types-into-meta-boxes/?utm_source=jetengine&utm_medium=meta-box-page&utm_campaign=need-help',
							'label' => __( 'How to group the custom meta fields for the certain post types into meta boxes', 'jet-engine' ),
						),
						array(
							'url'   => 'https://crocoblock.com/knowledge-base/articles/jetengine-how-to-create-checkbox-type-meta-field-and-display-it-in-listing-grid/?utm_source=jetengine&utm_medium=meta-box-page&utm_campaign=need-help',
							'label' => __( 'How to create Checkbox type meta field and display it in Listing Grid', 'jet-engine' ),
						),
					),
				) )
			);

			wp_enqueue_style(
				'jet-engine-mb-edit',
				jet_engine()->plugin_url( 'includes/components/meta-boxes/assets/css/edit.css' ),
				array(),
				jet_engine()->get_version()
			);

			add_action( 'admin_footer', array( $this, 'add_page_template' ) );

		}

		/**
		 * Print add/edit page template
		 */
		public function add_page_template() {

			ob_start();
			include jet_engine()->plugin_path( 'includes/components/meta-boxes/templates/edit.php' );
			$content = ob_get_clean();

			printf( '<script type="text/x-template" id="jet-mb-form">%s</script>', $content );

			ob_start();
			include jet_engine()->plugin_path( 'includes/components/meta-boxes/templates/delete-dialog.php' );
			$content = ob_get_clean();
			printf( '<script type="text/x-template" id="jet-cpt-delete-dialog">%s</script>', $content );

		}

		/**
		 * Adds template for meta fields component
		 */
		public static function add_meta_fields_template() {

			ob_start();
			include jet_engine()->plugin_path( 'includes/components/meta-boxes/templates/fields.php' );
			$content = ob_get_clean();

			printf( '<script type="text/x-template" id="jet-meta-fields">%s</script>', $content );

		}

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		public function render_page() {
			?>
			<br>
			<div id="jet_mb_form"></div>
			<?php
		}

	}

}
