<?php

class WCML_Setup_Multi_Currency_UI extends WCML_Templates_Factory {

	private $woocommerce_wpml;
	private $next_step_url;

	/**
	 * WCML_Setup_Multi_Currency_UI constructor.
	 *
	 * @param woocommerce_wpml $woocommerce_wpml
	 * @param string           $next_step_url
	 */
	public function __construct( $woocommerce_wpml, $next_step_url ) {
		// @todo Cover by tests, required for wcml-3037.
		parent::__construct();

		$this->woocommerce_wpml = $woocommerce_wpml;
		$this->next_step_url    = $next_step_url;
	}

	public function get_model() {

		$model = [
			'strings'           => [
				'step_id'     => 'currency_step',
				'heading'     => __( 'Enable Multiple Currencies', 'woocommerce-multilingual' ),
				'description' => __( 'This will allow you to set prices for products in different currencies. The prices can be determined based on a given exchange rate or set explicitly for specific products.', 'woocommerce-multilingual' ),
				'label_mco'   => __( 'Enable the multicurrency mode', 'woocommerce-multilingual' ),
				'continue'    => __( 'Continue', 'woocommerce-multilingual' ),
				'later'       => __( 'Later', 'woocommerce-multilingual' ),
			],
			'multi_currency_on' => $this->woocommerce_wpml->settings['enable_multi_currency'] == WCML_MULTI_CURRENCIES_INDEPENDENT,
			'continue_url'      => $this->next_step_url,
		];

		return $model;

	}

	protected function init_template_base_dir() {
		$this->template_paths = [
			WCML_PLUGIN_PATH . '/templates/',
		];
	}

	public function get_template() {
		return '/setup/multi-currency.twig';
	}


}
