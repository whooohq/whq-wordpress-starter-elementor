<?php
/**
 * Jet Smart Filters Admin class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Admin' ) ) {

	/**
	 * Define Jet_Smart_Filters_Admin class
	 */
	class Jet_Smart_Filters_Admin {

		/**
		 * Post type slug.
		 *
		 * @var string
		 */
		public $post_type = 'jet-smart-filters';

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'jet_smart_filters_admin_data' ) );
			add_action( 'wp_ajax_jet_smart_filters_admin', array( $this, 'filters_admin_action' ) );
			add_action( 'wp_ajax_nopriv_jet_smart_filters_admin', array( $this, 'filters_admin_action' ) );

		}

		/**
		 * Admin action in AJAX request
		 *
		 * @return [type] [description]
		 */
		public function filters_admin_action() {

			$tax        = ! empty( $_REQUEST['taxonomy'] ) ? $_REQUEST['taxonomy'] : false;
			$post_type  = ! empty( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : false;
			$hide_empty = isset( $_REQUEST['hide_empty'] ) ? filter_var( $_REQUEST['hide_empty'], FILTER_VALIDATE_BOOLEAN ) : true;
			$posts_list = '';
			$terms_list = '';

			if ( $tax ) {

				$args = array(
					'taxonomy'   => $tax,
					'hide_empty' => $hide_empty
				);

				$terms = get_terms( $args );
				$terms = wp_list_pluck( $terms, 'name', 'term_id' );

				foreach ( $terms as $terms_id => $term_name ) {
					$terms_list .= '<option value="' . $terms_id . '">' . $term_name . '</option>';
				}

			}

			if ( $post_type ) {

				$args = array(
					'post_type'      => $post_type,
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
				);

				$posts = get_posts( $args );

				if ( ! empty( $posts ) ) {
					$posts = wp_list_pluck( $posts, 'post_title', 'ID' );
				}

				foreach ( $posts as $post_id => $post_title ) {
					$posts_list .= '<option value="' . $post_id . '">' . $post_title . '</option>';
				}

			}

			wp_send_json( array(
				'terms' => $terms_list,
				'posts' => $posts_list,
			) );

		}


		public function jet_smart_filters_admin_data() {

			$screen = get_current_screen();

			if ( $this->post_type !== $screen->id && 'edit-' . $this->post_type !== $screen->id ) {
				return;
			}

			wp_enqueue_script(
				'jet-smart-filters',
				jet_smart_filters()->plugin_url( 'assets/js/admin.js' ),
				array( 'jquery' ),
				jet_smart_filters()->get_version(),
				true
			);

			wp_enqueue_style(
				'jet-smart-filters-admin',
				jet_smart_filters()->plugin_url( 'assets/css/admin/admin.css' ),
				array(),
				jet_smart_filters()->get_version()
			);

			$data = $this->get_localized_admin_data();

			wp_localize_script( 'jet-smart-filters', 'JetSmartFiltersAdminData', $data );

		}

		public function get_localized_admin_data(){

			$post_id = $this->get_post_id();
			$data_exclude_include = array();
			$data_color_image = array();

			if ( !empty( $post_id ) ){
				$data_exclude_include = get_post_meta( $_REQUEST['post'], '_data_exclude_include', true );
				$data_color_image = get_post_meta( $_REQUEST['post'], '_source_color_image_input', true );
			}

			return array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'dataExcludeInclude' => $data_exclude_include,
				'dataColorImage'     => $data_color_image,
			);

		}

		/**
		 * Try to get current post ID from request
		 *
		 * @return [type] [description]
		 */
		public function get_post_id() {

			$post_id = isset( $_GET['post'] ) ? $_GET['post'] : false;

			if ( ! $post_id && isset( $_REQUEST['post_ID'] ) ) {
				$post_id = $_REQUEST['post_ID'];
			}

			return $post_id;

		}
	}

}
