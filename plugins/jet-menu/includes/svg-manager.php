<?php
namespace Jet_Menu;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define SVG_Manager class
 */
class SVG_Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * @var string[]
	 */
	private $ui_icons = array(
		'menu'       => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 15H15V13H5V15ZM5 5V7H15V5H5ZM5 11H15V9H5V11Z" fill="currentColor"/></svg>',
		'no-alt'     => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.95 6.46L11.41 10L14.95 13.54L13.54 14.95L10 11.42L6.47 14.95L5.05 13.53L8.58 10L5.05 6.47L6.47 5.05L10 8.58L13.54 5.05L14.95 6.46Z" fill="currentColor"/></svg>',
		'arrow-right' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 6L14 10.03L8 14V6Z" fill="currentColor"/></svg>',
		'arrow-down' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 8L9.97 14L6 8H14Z" fill="currentColor"/></svg>',
		'arrow-left' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 14L6 9.97L12 6V14Z" fill="currentColor"/></svg>',
		'ellipsis'   => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 10C5 11.1 4.1 12 3 12C1.9 12 1 11.1 1 10C1 8.9 1.9 8 3 8C4.1 8 5 8.9 5 10ZM17 8C15.9 8 15 8.9 15 10C15 11.1 15.9 12 17 12C18.1 12 19 11.1 19 10C19 8.9 18.1 8 17 8ZM10 8C8.9 8 8 8.9 8 10C8 11.1 8.9 12 10 12C11.1 12 12 11.1 12 10C12 8.9 11.1 8 10 8Z" fill="currentColor"/></svg>',
	);

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

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		$svg_enabled = jet_menu()->settings_manager->options_manager->get_option( 'svg-uploads', 'enabled' );

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
	 * @return string[]
	 */
	public function get_ui_icons() {
	    return $this->ui_icons;
	}

	/**
	 * @param string $icon
	 * @param array $classes
	 *
	 * @return array|string|string[]|null
	 */
	public function get_svg_html( $icon = '', $classes = array() ) {
		$icons = $this->get_ui_icons();

		$classes   = (array) $classes;
		$classes[] = 'svg-icon';

		if ( array_key_exists( $icon, $icons ) ) {
			$repl = sprintf( '<svg class="%s" aria-hidden="true" role="img" focusable="false" ', join( ' ', $classes ) );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icons[ $icon ] ) ); // Add extra attributes to SVG code.
			$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
			$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

			return $svg;
		}

		return false;
	}
}

