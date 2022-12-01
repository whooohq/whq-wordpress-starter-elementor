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

if ( ! class_exists( 'Jet_Elements_Download_Handler' ) ) {

	/**
	 * Define Jet_Elements_Download_Handler class
	 */
	class Jet_Elements_Download_Handler {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Download hook
		 *
		 * @var string
		 */
		private $hook = 'jet_download';

		/**
		 * Encrypt keys
		 *
		 * @var string
		 */
		private $encrypt_key_start = '';
		private $encrypt_key_end   = '';

		private $encrypt_multiplier;
		private $use_encryption;

		/**
		 * Constructor for the class
		 */
		public function init() {

			if ( false === get_option( 'jet_elements_download_button_encrypt_key_start' ) ) {
				add_option( 'jet_elements_download_button_encrypt_key_start', $this->generate_security_key() );
				$this->encrypt_key_start = get_option( 'jet_elements_download_button_encrypt_key_start' );
			} else {
				$this->encrypt_key_start = get_option( 'jet_elements_download_button_encrypt_key_start' );
			}

			if ( false === get_option( 'jet_elements_download_button_encrypt_key_end' ) ) {
				add_option( 'jet_elements_download_button_encrypt_key_end', $this->generate_security_key() );
				$this->encrypt_key_end = get_option( 'jet_elements_download_button_encrypt_key_end' );
			} else {
				$this->encrypt_key_end = get_option( 'jet_elements_download_button_encrypt_key_end' );
			}

			$this->encrypt_multiplier = $this->get_id_multiplier();

			add_action( 'init', array( $this, 'process_download' ), 99 );
		}

		/**
		 * Generate security key
		 *
		 * @var string
		 */
		private function generate_security_key() {
			return absint( (int)( ( time() / random_int( 11, 100 ) ) * random_int( 2, 1000 ) ) );
		}

		private function get_id_multiplier() {
			if ( false === get_option( 'jet_elements_download_button_encrypt_id_multiplier' ) ) {
				add_option( 'jet_elements_download_button_encrypt_id_multiplier', random_int( 2, 1000 ) );
				return get_option( 'jet_elements_download_button_encrypt_id_multiplier' );
			} else {
				return get_option( 'jet_elements_download_button_encrypt_id_multiplier' );
			}
		}

		/**
		 * Returns download hook name
		 *
		 * @return string
		 */
		public function hook() {
			return $this->hook;
		}

		/**
		 * Encrypt download ID
		 *
		 * @return string
		 */
		private function encrypt_id( $id ) {
			$id = (int) $id * $this->encrypt_multiplier;
			return base64_encode( $this->encrypt_key_start . $id . $this->encrypt_key_end );
		}

		/**
		 * Decrypt download ID
		 *
		 * @return string
		 */
		private function decrypt_id( $id ) {
			$encrypt_key_start = $this->encrypt_key_start;
			$encrypt_key_end   = $this->encrypt_key_end;
			$id_raw            = base64_decode( $id );
			$id                = preg_replace( array( '/' . $encrypt_key_start . '/', '/' . $encrypt_key_end . '/' ), '', $id_raw );
			$id               /= $this->encrypt_multiplier;
			return $id;
		}

		/**
		 * Get download link for passed ID.
		 *
		 * @param  integer $id Media post ID.
		 * @return string
		 */
		public function get_download_link( $id = 0, $is_encrypted = '' ) {
			if ( 'true' === $is_encrypted ) {
				$id = $this->encrypt_id( $id );
			}

			return add_query_arg(
				array( $this->hook() => $id ),
				esc_url( home_url( '/' ) )
			);
		}

		/**
		 * Get file size by attachment ID
		 * @param  integer $id [description]
		 * @return [type]      [description]
		 */
		public function get_file_size( $id = 0 ) {

			$file_path = get_attached_file( $id );

			if ( ! $file_path ) {
				return;
			}

			$file_size = filesize( $file_path );

			return size_format( $file_size );
		}

		/**
		 * Check if is download request and handle it.
		 */
		public function process_download() {

			if ( empty( $_GET[ $this->hook() ] ) ) {
				return;
			}

			if ( preg_match("/[a-z]/i", $_GET[ $this->hook() ] ) ) {
				$encrypted_id = $_GET[ $this->hook() ];
				$id           = absint( $this->decrypt_id( $encrypted_id ) );
			} else {
				$id = $_GET[ $this->hook() ];
			}

			if ( ! $id ) {
				return;
			}

			$post = get_post( $id );

			if ( 'attachment' !== $post->post_type ) {
				return;
			}

			$file_path = get_attached_file( $id );

			if ( ! is_file( $file_path ) ) {
				return;
			}

			if ( ini_get( 'zlib.output_compression' ) ) {
				ini_set('zlib.output_compression', 'Off');
			}

			// get the file mime type using the file extension
			switch( strtolower( substr( strrchr( $file_path, '.' ), 1 ) ) ) {
				case 'pdf':
					$mime = 'application/pdf';
					break;
				case 'zip':
					$mime = 'application/zip';
					break;
				case 'jpeg':
				case 'jpg':
					$mime = 'image/jpg';
					break;
				default:
					$mime = 'application/force-download';
					break;
			}

			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', @filemtime( $file_path ) ) . ' GMT' );
			header( 'Cache-Control: private', false );
			header( 'Content-Type: ' . $mime );
			header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Length: ' . @filesize( $file_path ) );
			header( 'Connection: close' );

			$this->readfile_chunked( $file_path );

			die();

		}

		/**
		 * Process chuncked download
		 *
		 * @param  string $file     Filepath
		 * @param  bool   $retbytes
		 * @return mixed
		 */
		public function readfile_chunked( $filename, $retbytes = true ) {

			$chunksize = 1 * ( 1024 * 1024 );
			$buffer    = '';
			$cnt       = 0;
			$handle    = fopen( $filename, 'rb' );

			if ( false === $handle ) {
				return false;
			}

			while ( ! feof( $handle ) ) {
				$buffer = fread( $handle, $chunksize );
				echo $buffer;
				ob_flush();
				flush();
				if ( $retbytes ) {
					$cnt += strlen( $buffer );
				}
			}

			$status = fclose($handle);

			if ( $retbytes && $status ) {
				return $cnt; // return num. bytes delivered like readfile() does.
			}

			return $status;
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
 * Returns instance of Jet_Elements_Download_Handler
 *
 * @return object
 */
function jet_elements_download_handler() {
	return Jet_Elements_Download_Handler::get_instance();
}
