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

if ( ! class_exists( 'Jet_Elements_SVG_Manager' ) ) {

	/**
	 * Define Jet_Elements_SVG_Manager class
	 */
	class Jet_Elements_SVG_Manager {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			$svg_enabled = jet_elements_settings()->get( 'svg_uploads', 'enabled' );

			if ( 'enabled' !== $svg_enabled ) {
				return;
			}

			add_filter( 'upload_mimes', array( $this, 'allow_svg' ) );
			add_action( 'admin_head', array( $this, 'fix_svg_thumb_display' ) );
			add_filter( 'wp_generate_attachment_metadata', array( $this, 'generate_svg_media_files_metadata' ), 10, 2 );
			add_filter( 'wp_prepare_attachment_for_js', array( $this, 'wp_prepare_attachment_for_js' ), 10, 3 );
		}

		/**
		 * Allow SVG images uploading
		 *
		 * @return array
		 */
		public function allow_svg( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		}

		/**
		 * Fix thumbnails display
		 *
		 * @return void
		 */
		public function fix_svg_thumb_display() {
			?>
			<style type="text/css">
				td.media-icon img[src$=".svg"],
				img[src$=".svg"].attachment-post-thumbnail,
				td .media-icon img[src*='.svg'] {
					width: 100% !important;
					height: auto !important;
				}
			</style>
			<?php
		}
		
		/**
		 * Generate SVG metadata
		 *
		 * @return string
		 */
		function generate_svg_media_files_metadata( $metadata, $attachment_id ){
			if( get_post_mime_type( $attachment_id ) == 'image/svg+xml' ){
				$svg_path = get_attached_file( $attachment_id );
				$dimensions = $this->svg_dimensions( $svg_path );
				$metadata['width'] = $dimensions->width;
				$metadata['height'] = $dimensions->height;
			}
			return $metadata;
		}
		
		/**
		 * Prepares an attachment post object for JS
		 *
		 * @return array
		 */
		public function wp_prepare_attachment_for_js( $response, $attachment, $meta ){
			if( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ){
				$svg_path = get_attached_file( $attachment->ID );
				if( ! file_exists( $svg_path ) ){
					$svg_path = $response['url'];
				}
				$dimensions = $this->svg_dimensions( $svg_path );
				$response['sizes'] = array(
					'full' => array(
						'url' => $response['url'],
						'width' => $dimensions->width,
						'height' => $dimensions->height,
						'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait'
					)
				);
			}
			return $response;
		}
		
		/**
		 * Get the width and height of the SVG
		 *
		 * @return object
		 */
		public function svg_dimensions( $svg ){
			$svg = function_exists( 'simplexml_load_file' ) ? simplexml_load_file( $svg ) : null;
			$width = 0;
			$height = 0;
			if( $svg ){
				$attributes = $svg->attributes();
				if( isset( $attributes->width, $attributes->height ) ){
					$width = floatval( $attributes->width );
					$height = floatval( $attributes->height );
				}elseif( isset( $attributes->viewBox ) ){
					$sizes = explode( " ", $attributes->viewBox );
					if( isset( $sizes[2], $sizes[3] ) ){
						$width = floatval( $sizes[2] );
						$height = floatval( $sizes[3] );
					}
				}
			}
			return (object)array( 'width' => $width, 'height' => $height );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
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
 * Returns instance of Jet_Elements_SVG_Manager
 *
 * @return object
 */
function jet_elements_svg_manager() {
	return Jet_Elements_SVG_Manager::get_instance();
}
