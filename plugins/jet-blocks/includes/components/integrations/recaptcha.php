<?php
namespace Jet_Blocks\Integrations;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ReCaptcha {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.3.5
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$ready_to_use description]
	 * @var [type]
	 */
	public $ready_to_use = false;

	/**
	 * [$captcha_script_hanle description]
	 * @var string
	 */
	public $captcha_script_handle = 'recaptchav3';

	/**
	 * [$api description]
	 * @var string
	 */
	private $api = 'https://www.google.com/recaptcha/api/siteverify';

	/**
	 * Constructor for the class
	 */
	function __construct() {

		add_action( 'jet-blocks/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'jet-blocks/frontend/localize-data', array( $this, 'modify_localized_data' ) );
	}

	/**
	 * [is_captcha_ready_to_use description]
	 * @return boolean [description]
	 */
	public function is_captcha_ready_to_use() {
		$captcha_config = jet_blocks_settings()->get( 'captcha', array(
			'enable'     => 'false',
			'site_key'   => '',
			'secret_key' => '',
		) );

		return filter_var( $captcha_config['enable'], FILTER_VALIDATE_BOOLEAN ) && ! empty( $captcha_config['site_key'] ) && ! empty( $captcha_config['secret_key'] ) ? true : false;
	}

	/**
	 * [enqueue_scripts description]
	 * @return [type] [description]
	 */
	public function enqueue_scripts() {
		$captcha_config = jet_blocks_settings()->get( 'captcha' );

		if ( $this->is_captcha_ready_to_use() ) {
			wp_enqueue_script(
				$this->captcha_script_handle,
				sprintf( 'https://www.google.com/recaptcha/api.js?render=%s>', $captcha_config['site_key'] ),
				array(),
				null,
				true
			);
		}
	}

	/**
	 * [add_captcha_dept description]
	 * @param [type] $deps [description]
	 */
	public function add_captcha_dept( $deps ) {

		if ( $this->is_captcha_ready_to_use() ) {
			$deps[] = $this->captcha_script_handle;
		}

		return $deps;
	}

	/**
	 * [modify_localized_data description]
	 * @param  [type] $localized_data [description]
	 * @return [type]                 [description]
	 */
	public function modify_localized_data( $localized_data ) {
		$localized_data['recaptchaConfig'] = jet_blocks_settings()->get( 'captcha' );

		return $localized_data;
	}

	/**
	 * [maybe_verify description]
	 * @param  [type] $token [description]
	 * @return [type]        [description]
	 */
	public function maybe_verify( $token ) {

		$captcha_config = jet_blocks_settings()->get( 'captcha' );

		if ( ! $captcha_config['enable'] ) {
			return true;
		}

		if ( empty( $token ) ) {
			return false;
		}

		if ( empty( $captcha_config['secret_key'] ) ) {
			return false;
		}

		$response = wp_remote_post( $this->api, array(
			'body' => array(
				'secret'   => $captcha_config['secret_key'],
				'response' => $token,
			),
		) );

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		if ( ! $body || empty( $body['success'] ) ) {
			return false;
		} else {
			return $body['success'];
		}
	}

	/**
	 * Returns the instance.
	 *
	 * @since 1.3.5
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
