<?php
/**
 * Elementor views manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Blocks_Views_Render' ) ) {

	/**
	 * Define Jet_Engine_Blocks_Views_Render class
	 */
	class Jet_Engine_Blocks_Views_Render {

		private $contents = array();
		private $enqueued_css = array();
		private $printed_css = array();
		private $current_listing = null;

		public function __construct() {
			add_action( 'enqueue_block_assets', array( jet_engine()->frontend, 'frontend_styles' ) );

			add_action( 'wp_footer', array( $this, 'print_css' ) );
			add_action( 'jet-engine/listing/grid/after', array( $this, 'print_preview_css' ) );
		}

		/**
		 * Print preview CSS
		 *
		 * @return [type] [description]
		 */
		public function print_preview_css() {
			$this->print_css();
		}

		public function print_css() {
			foreach ( $this->enqueued_css as $post_id => $css ) {
				if ( ! empty( $css ) && ! in_array( $post_id, $this->printed_css ) ) {
					echo $css;
					$this->printed_css[] = $post_id;
				}
			}
		}

		/**
		 * Returns listing content for given listing ID
		 *
		 * @return [type] [description]
		 */
		public function get_listing_content( $listing_id ) {
			$content = $this->get_raw_content( $listing_id );
			$this->enqueue_listing_css( $listing_id );
			return do_shortcode( $this->parse_content( $content, $listing_id ) );
		}

		public function fix_context( $context ) {

			$object = jet_engine()->listings->data->get_current_object();

			if ( $object && 'WP_Post' === get_class( $object ) ) {
				$context['postId']   = $object->ID;
				$context['postType'] = $object->post_type;
			}

			return $context;

		}

		/**
		 * Returns current listing ID
		 *
		 * @return [type] [description]
		 */
		public function get_current_listing_id() {
			return $this->current_listing;
		}

		/**
		 * Prse listing item content
		 *
		 * @param  [type] $content [description]
		 * @return [type]          [description]
		 */
		public function parse_content( $content, $listing_id ) {

			add_filter( 'render_block_context', array( $this, 'fix_context' ) );

			$this->current_listing = $listing_id;
			$parsed = do_blocks( $content );
			$this->current_listing = false;

			remove_filter( 'render_block_context', array( $this, 'fix_context' ) );

			return $parsed;

		}

		public function enqueue_listing_css( $listing_id, $print = false ) {

			if ( isset( $this->enqueued_css[ $listing_id ] ) ) {
				return;
			}

			$css    = get_post_meta( $listing_id, '_jet_engine_listing_css', true );
			$result = '';
			$style  = '';

			if ( class_exists( '\JET_SM\Gutenberg\Style_Manager' ) ) {
				$style = \JET_SM\Gutenberg\Style_Manager::get_instance()->get_blocks_style( $listing_id );
			}
			
			$css .= $style;

			if ( $css ) {
				$css    = str_replace( 'selector', '.jet-listing-grid--' . $listing_id, $css );
				$result = '<style class="listing-css-' . $listing_id . '">' . $css . '</style>';
			}

			if ( $print ) {
				echo $result;
				$this->printed_css[] = $listing_id;
			} else {
				$this->enqueued_css[ $listing_id ] = $result;
			}

			if ( class_exists( '\JET_SM\Gutenberg\Style_Manager' ) ) {
				\JET_SM\Gutenberg\Style_Manager::get_instance()->render_blocks_fonts( $listing_id );
			}

		}



		/**
		 * Returns raw listing content
		 *
		 * @param  [type] $listing_id [description]
		 * @return [type]             [description]
		 */
		public function get_raw_content( $listing_id ) {

			if ( ! isset( $this->contents[ $listing_id ] ) ) {
				$post = get_post( $listing_id );
				$this->contents[ $listing_id ] = $post->post_content;
			}

			return $this->contents[ $listing_id ];
		}

	}

}
