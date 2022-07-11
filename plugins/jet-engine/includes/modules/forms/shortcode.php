<?php
/**
 * Shortcode
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Forms_Shortcode' ) ) {

	class Jet_Engine_Forms_Shortcode {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		public function __construct() {
			add_filter( 'jet-engine/dashboard/config', array( $this, 'modify_dashboard_config' ) );
			add_action( 'jet-engine/dashboard/assets', array( $this, 'enqueue_deps_scripts' ) );
			add_action( 'jet-engine/dashboard/shortcode-generator/custom-controls', array( $this, 'register_controls' ) );
			add_filter( 'jet-engine/shortcodes/default-atts', array( $this, 'add_forms_default_atts' ) );
			add_filter( 'jet-engine/shortcodes/forms/result', array( $this, 'do_shortcode' ), 10, 2 );

			if ( is_admin() ) {
				add_action( 'init', array( $this, 'init_admin_columns_hooks' ) );
			}
		}

		public function init_admin_columns_hooks() {
			add_filter( 'manage_' . jet_engine()->forms->slug() . '_posts_columns',       array( $this, 'edit_columns' ) );
			add_action( 'manage_' . jet_engine()->forms->slug() . '_posts_custom_column', array(  $this, 'manage_columns' ), 10, 2 );
		}

		public function modify_dashboard_config( $config = array() ) {

			$config['api_path_search'] = jet_engine()->api->get_route( 'search-posts' );

			$config['components_list'][] = array(
				'value' => 'forms',
				'label' => __( 'Forms', 'jet-engine' ),
			);

			return $config;
		}

		public function enqueue_deps_scripts() {
			wp_enqueue_script( 'wp-api-fetch' );
		}

		public function register_controls() {
			?>
			<cx-vui-f-select
				:label="'<?php _e( 'Select Form', 'jet-engine' ); ?>'"
				:wrapper-css="[ 'equalwidth' ]"
				:remote="true"
				:remote-callback="getForms"
				:size="'fullwidth'"
				:conditions="[
					{
						input: this.shortcode.component,
						compare: 'equal',
						value: 'forms',
					}
				]"
				v-model="shortcode.form_id"
			></cx-vui-f-select>
			<cx-vui-select
				:label="'<?php _e( 'Fields Layout', 'jet-engine' ); ?>'"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="[
					{
						value: 'row',
						label: '<?php _e( 'Row', 'jet-engine' ); ?>',
					},
					{
						value: 'column',
						label: '<?php _e( 'Column', 'jet-engine' ); ?>',
					}
				]"
				:conditions="[
					{
						input: this.shortcode.component,
						compare: 'equal',
						value: 'forms',
					}
				]"
				v-model="shortcode.fields_layout"
			></cx-vui-select>
			<cx-vui-select
				:label="'<?php _e( 'Label HTML tag', 'jet-engine' ); ?>'"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="[
					{
						value: 'div',
						label: '<?php _e( 'DIV', 'jet-engine' ); ?>',
					},
					{
						value: 'label',
						label: '<?php _e( 'LABEL', 'jet-engine' ); ?>',
					}
				]"
				:conditions="[
					{
						input: this.shortcode.component,
						compare: 'equal',
						value: 'forms',
					}
				]"
				v-model="shortcode.fields_label_tag"
			></cx-vui-select>
			<cx-vui-select
				:label="'<?php _e( 'Submit Type', 'jet-engine' ); ?>'"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="[
					{
						value: 'reload',
						label: '<?php _e( 'Reload', 'jet-engine' ); ?>',
					},
					{
						value: 'ajax',
						label: '<?php _e( 'AJAX', 'jet-engine' ); ?>',
					}
				]"
				:conditions="[
					{
						input: this.shortcode.component,
						compare: 'equal',
						value: 'forms',
					}
				]"
				v-model="shortcode.submit_type"
			></cx-vui-select>
			<cx-vui-switcher
				label="<?php _e( 'Cache Form Output', 'jet-engine' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="shortcode.cache_form"
				:conditions="[
					{
						input: this.shortcode.component,
						compare: 'equal',
						value: 'forms',
					}
				]"
			></cx-vui-switcher>
			<?php
		}

		public function add_forms_default_atts( $atts = array() ) {
			$form_atts = array(
				'_form_id'         => '',
				'fields_layout'    => 'row',
				'fields_label_tag' => 'div',
				'submit_type'      => 'reload',
				'cache_form'       => false,
			);

			return array_merge( $atts, $form_atts );
		}

		public function do_shortcode( $result = '', $atts = array() ) {

			if ( empty( $atts['_form_id'] ) ) {
				return $result;
			}

			jet_engine()->frontend->frontend_styles();
			jet_engine()->frontend->frontend_scripts();

			$render  = jet_engine()->listings->get_render_instance( 'booking-form', $atts );;
			$content = $render->get_content();

			// Ensure enqueue form script after getting content.
			wp_enqueue_script( 'jet-engine-frontend-forms' );

			return sprintf( '<div class="jet-form-block">%s</div>', $content );
		}

		public function edit_columns( $columns = array() ) {

			$columns['form-shortcode'] = esc_html__( 'Shortcode', 'jet-engine' );

			return $columns;
		}

		public function manage_columns( $column, $post_id ) {

			if ( 'form-shortcode' !== $column ) {
				return;
			}

			$shortcode = sprintf( '[jet_engine component="forms" _form_id="%d"]', $post_id );

			printf(
				'<input type="text" readonly value="%s" style="%s" />',
				esc_attr( $shortcode ),
				'width:100%'
			);
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

	}

}