<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

abstract class Jet_Woo_Builder_Base extends Widget_Base {

	public $__context         = 'render';
	public $__processed_item  = false;
	public $__processed_index = 0;
	public $__temp_query      = null;
	public $__product_data    = false;
	public $__new_icon_prefix = 'selected_';

	/**
	 * Current product.
	 *
	 * Holder for current product instance.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @var bool
	 */
	private $current_product = false;

	/**
	 * Returns JetWooBuilder help url
	 *
	 * @return false
	 */
	public function get_jet_help_url() {
		return false;
	}

	/**
	 * Returns help url
	 *
	 * @return false
	 */
	public function get_help_url() {

		$url = $this->get_jet_help_url();

		if ( ! empty( $url ) ) {
			return add_query_arg(
				array(
					'utm_source'   => 'need-help',
					'utm_medium'   => $this->get_name(),
					'utm_campaign' => 'jetwoobuilder',
				),
				esc_url( $url )
			);
		}

		return false;

	}

	/**
	 * Get widgets templates.
	 *
	 * @param null $name
	 *
	 * @return bool|string
	 */
	public function get_template( $name = null ) {

		$template = $this->get_legacy_template();

		if ( ! $template ) {
			$template = jet_woo_builder()->get_template( 'widgets/' . $name );
		}

		return $template;

	}

	/**
	 * Get widgets legacy templates.
	 *
	 * @return bool|string
	 */
	public function get_legacy_template() {
		return jet_woo_builder()->get_template( $this->get_name() . '/global/index.php' );
	}

	/**
	 * Set product.
	 *
	 * Set current product data.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $product_data Product data.
	 *
	 * @return void
	 */
	public function set_current_product( $product_data = [] ) {
		$this->current_product = $product_data;
	}

	/**
	 * Get product.
	 *
	 * Get current product data.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array|bool
	 */
	public function get_current_product() {
		return $this->current_product;
	}

	/**
	 * Reset product.
	 *
	 * Reset current product data.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return false
	 */
	public function reset_current_product() {
		return $this->current_product = false;
	}

	/**
	 * Open standard wrapper
	 *
	 * @return void
	 */
	public function __open_wrap() {
		printf( '<div class="elementor-%s jet-woo-builder">', $this->get_name() );
	}

	/**
	 * Close standard wrapper
	 *
	 * @return void
	 */
	public function __close_wrap() {
		echo '</div>';
	}

	/**
	 * Set editor product
	 *
	 * @return bool
	 */
	public function __set_editor_product() {

		if ( ! jet_woo_builder()->elementor_views->in_elementor() && ! wp_doing_ajax() ) {
			return true;
		}

		global $post, $wp_query, $product;

		$this->__temp_query   = $wp_query;
		$this->__product_data = $this->get_current_product();

		if ( $this->__product_data === true ) {
			return true;
		}

		if ( ! empty( $this->__product_data ) ) {
			$wp_query = $this->__product_data['query'];
			$post     = $this->__product_data['post'];
			$product  = $this->__product_data['product'];

			return true;
		}

		if ( 'product' === $post->post_type ) {
			$product = wc_get_product( $post );

			$this->__product_data = array(
				'query'   => $wp_query,
				'post'    => $post,
				'product' => $product,
			);

			$this->set_current_product( $this->__product_data );

			return true;
		}

		$sample_product = get_post_meta( $post->ID, '_sample_product', true );

		$args = array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'pending', 'draft', 'future' ),
			'posts_per_page' => 1,
		);

		if ( ! empty( $sample_product ) ) {
			$args['p'] = $sample_product;
		}

		$wp_query = new \WP_Query( $args );

		if ( $wp_query->have_posts() ) {
			foreach ( $wp_query->posts as $post ) {
				setup_postdata( $post );
				$product = wc_get_product( $post );
			}

			$this->__product_data = array(
				'query'   => $wp_query,
				'post'    => $post,
				'product' => $product,
			);

			$this->set_current_product( $this->__product_data );

			return true;
		} else {
			esc_html_e( 'Please add at least one product with "publish", "pending", "draft" or "future" status', 'jet-woo-builder' );

			return false;
		}

	}

	/**
	 * Restore previous data to avoid conflicts.
	 *
	 * @return void
	 */
	public function __reset_editor_product() {
		if ( ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) || wp_doing_ajax() ) {
			global $wp_query;
			$wp_query = $this->__temp_query;
			wp_reset_postdata();
		}
	}

	/**
	 * Add icon control
	 *
	 * @param string $id
	 * @param array  $args
	 * @param null   $instance
	 */
	public function __add_advanced_icon_control( $id = '', array $args = array(), $instance = null ) {

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {
			$_id = $id; // old control id
			$id  = $this->__new_icon_prefix . $id;

			$args['type']             = Controls_Manager::ICONS;
			$args['fa4compatibility'] = $_id;

			unset( $args['file'] );
			unset( $args['default'] );

			if ( isset( $args['fa5_default'] ) ) {
				$args['default'] = $args['fa5_default'];

				unset( $args['fa5_default'] );
			}
		} else {
			$args['type'] = Controls_Manager::ICON;
			unset( $args['fa5_default'] );
		}

		if ( null !== $instance ) {
			$instance->add_control( $id, $args );
		} else {
			$this->add_control( $id, $args );
		}

	}

	/**
	 * Prepare icon control ID for condition.
	 *
	 * @param string $id
	 *
	 * @return string
	 */
	public function __prepare_icon_id_for_condition( $id ) {

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {
			return $this->__new_icon_prefix . $id . '[value]';
		}

		return $id;
	}

	/**
	 * Print HTML icon template
	 *
	 * @param string $setting
	 * @param string $format
	 * @param string $icon_class
	 * @param bool   $echo
	 * @param        $settings
	 *
	 * @return void|string
	 */
	public function __render_icon( $setting = '', $format = '%s', $icon_class = '', $echo = true, $settings = [] ) {

		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		$new_setting = $this->__new_icon_prefix . $setting;
		$migrated    = isset( $settings['__fa4_migrated'][ $new_setting ] );
		$is_new      = empty( $settings[ $setting ] ) && class_exists( 'Elementor\Icons_Manager' ) && Icons_Manager::is_migration_allowed();
		$icon_html   = '';

		if ( $is_new || $migrated ) {
			$attr = array( 'aria-hidden' => 'true' );

			if ( ! empty( $icon_class ) ) {
				$attr['class'] = $icon_class;
			}

			if ( isset( $settings[ $new_setting ] ) ) {
				ob_start();

				Icons_Manager::render_icon( $settings[ $new_setting ], $attr );

				$icon_html = ob_get_clean();
			}
		} else if ( ! empty( $settings[ $setting ] ) ) {
			if ( empty( $icon_class ) ) {
				$icon_class = $settings[ $setting ];
			} else {
				$icon_class .= ' ' . $settings[ $setting ];
			}

			$icon_html = sprintf( '<i class="%s" aria-hidden="true"></i>', $icon_class );
		}

		if ( empty( $icon_html ) ) {
			return;
		}

		if ( ! $echo ) {
			return sprintf( $format, $icon_html );
		}

		printf( $format, $icon_html );

	}

}
