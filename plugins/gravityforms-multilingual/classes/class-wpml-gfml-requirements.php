<?php

class WPML_GFML_Requirements {

	/** @var array */
	private $missing;

	/** @var bool */
	private $missing_one;

	/**
	 * WPML_GFML_Requirements constructor.
	 */
	public function __construct() {
		$this->missing     = [];
		$this->missing_one = false;
		add_action( 'admin_notices', [ $this, 'missing_plugins_warning' ] );
		add_action( 'plugins_loaded', [ $this, 'plugins_loaded_action' ], 999999 );
	}

	private function check_required_plugins() {
		$this->missing     = [];
		$this->missing_one = false;

		if ( ! defined( 'ICL_SITEPRESS_VERSION' )
				 || ICL_PLUGIN_INACTIVE
				 || version_compare( ICL_SITEPRESS_VERSION, '2.0.5', '<' )
		) {
			$this->missing['WPML'] = [
				'url'  => 'http://wpml.org',
				'slug' => 'sitepress-multilingual-cms',
			];

			$this->missing_one = true;
		}

		if ( ! class_exists( 'GFForms' ) ) {
			$this->missing['Gravity Forms'] = [
				'url'  => 'http://gravityforms.com',
				'slug' => 'gravity-forms',
			];

			$this->missing_one = true;
		}

		if ( ! defined( 'WPML_TM_VERSION' ) ) {
			$this->missing['WPML Translation Management'] = [
				'url'  => 'http://wpml.org',
				'slug' => 'wpml-translation-management',
			];

			$this->missing_one = true;
		}

		if ( ! defined( 'WPML_ST_VERSION' ) ) {
			$this->missing['WPML String Translation'] = [
				'url'  => 'http://wpml.org',
				'slug' => 'wpml-string-stranslation',
			];

			$this->missing_one = true;
		}
	}

	public function plugins_loaded_action() {
		$this->check_required_plugins();

		if ( ! $this->missing_one ) {
			do_action( 'wpml_gfml_has_requirements' );
		}
	}

	/**
	 * Missing plugins warning.
	 */
	public function missing_plugins_warning() {
		if ( $this->missing ) {
			$missing       = '';
			$missing_slugs = [];
			$counter       = 0;
			foreach ( $this->missing as $title => $data ) {
				$url             = $data['url'];
				$missing_slugs[] = 'wpml-missing-' . sanitize_title_with_dashes( $data['slug'] );
				$counter ++;
				if ( count( $this->missing ) === $counter ) {
					$sep = '';
				} elseif ( count( $this->missing ) - 1 === $counter ) {
					$sep = ' ' . __( 'and', 'wpml-translation-management' ) . ' ';
				} else {
					$sep = ', ';
				}
				$missing .= '<a href="' . $url . '">' . $title . '</a>' . $sep;
			}

			$missing_slugs_classes = implode( ' ', $missing_slugs );
			?>
			<div class="message error wpml-admin-notice wpml-gfml-inactive <?php echo esc_attr( $missing_slugs_classes ); ?>">
				<p>
					<?php
					echo wp_kses_post(
						// translators: 1: Links to missed plugins.
						sprintf( __( 'Gravity Forms Multilingual is enabled but not effective. It requires %s in order to work.', 'wpml-translation-management' ), $missing )
					);
					?>
				</p>
			</div>
			<?php
		}
	}
}
