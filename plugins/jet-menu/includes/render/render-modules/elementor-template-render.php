<?php
namespace Jet_Menu\Render;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Elementor_Template_Render extends Base_Render {

	/**
	 * [$name description]
	 * @var string
	 */
	protected $name = 'elementor-template-render';

	/**
	 * [$depended_scripts description]
	 * @var array
	 */
	public $depended_scripts = [];

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init() {}

	/**
	 * [get_name description]
	 * @return [type] [description]
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	public function render() {

		if ( ! jet_menu_tools()->has_elementor() ) {
			echo __( 'Elementor not instaled', 'jet-menu' );

			return false;
		}

		if ( $this->is_editor() ) {
			echo __( 'Elementor editor content is not available in the Blocks Editor', 'jet-menu' );

			return false;
		}

		$template_id = $this->get( 'template_id', false );
		$with_css = $this->get( 'with_css', false );

		if ( ! $template_id ) {
			return false;
		}

		do_action( 'jet-menu/render/elementor-render/get-content/before', $template_id, $this->get_settings() );

		$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, $with_css );

		do_action( 'jet-menu/render/elementor-render/get-content/after', $template_id, $this->get_settings() );

		echo do_shortcode( $content );

    }

	/**
	 * @return array
	 */
	public function get_render_data() {

		$template_id = $this->get( 'template_id', false );

		$template_scripts = [];
		$template_styles = [];

		$fonts_link = $this->get_elementor_template_fonts_url( $template_id );

		if ( $fonts_link ) {
			$template_styles[ 'jet-menu-google-fonts-css-' . $template_id ] = $fonts_link;
		}

		\Elementor\Plugin::instance()->frontend->register_scripts();

		do_action( 'jet_plugins/frontend/register_scripts' );

		$this->get_elementor_template_scripts( $template_id );

		$script_depends = array_unique( $this->depended_scripts );

		foreach ( $script_depends as $script ) {
			$template_scripts[ $script ] = $this->get_script_uri_by_handler( $script );
		}

		return [
			'template_content' => $this->get_content(),
			'template_scripts' => $template_scripts,
			'template_styles'  => $template_styles,
		];
	}

	/**
	 * @param $template_id
	 *
	 * @return false|string
	 */
	public function get_elementor_template_fonts_url( $template_id ) {

		$post_css = new \Elementor\Core\Files\CSS\Post( $template_id );

		$post_meta = $post_css->get_meta();

		if ( ! isset( $post_meta['fonts'] ) ) {
			return false;
		}

		$google_fonts = $post_meta['fonts'];

		$google_fonts = array_unique( $google_fonts );

		if ( empty( $google_fonts ) ) {
			return false;
		}

		$google_fonts = array_filter( $google_fonts, function( $font ) {
			return jet_menu()->settings_manager->options_manager->fonts_loader->is_google_font( $font );
		} );

		if ( empty( $google_fonts ) ) {
			return false;
		}

		foreach ( $google_fonts as &$font ) {
			$font = str_replace( ' ', '+', $font ) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
		}

		$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $google_fonts ) );

		$subsets = [
			'ru_RU' => 'cyrillic',
			'bg_BG' => 'cyrillic',
			'he_IL' => 'hebrew',
			'el'    => 'greek',
			'vi'    => 'vietnamese',
			'uk'    => 'cyrillic',
			'cs_CZ' => 'latin-ext',
			'ro_RO' => 'latin-ext',
			'pl_PL' => 'latin-ext',
		];

		$locale = get_locale();

		if ( isset( $subsets[ $locale ] ) ) {
			$fonts_url .= '&subset=' . $subsets[ $locale ];
		}

		return $fonts_url;
	}

	/**
	 * [get_elementor_template_scripts_url description]
	 * @param  [type] $template_id [description]
	 * @return [type]              [description]
	 */
	public function get_elementor_template_scripts( $template_id ) {

		if ( ! $template_id ) {
			return;
		}

		$document = \Elementor\Plugin::$instance->documents->get( $template_id );

		if ( ! $document ) {
			return;
		}

		$elements_data = $document->get_elements_raw_data();

		if ( empty( $elements_data ) ) {
			return;
		}

		$this->find_widgets_script_handlers( $elements_data );
	}

	/**
	 * [find_widgets_script_handlers description]
	 * @param  [type] $elements_data [description]
	 * @return [type]                [description]
	 */
	public function find_widgets_script_handlers( $elements_data ) {

		foreach ( $elements_data as $element_data ) {

			if ( 'widget' === $element_data['elType'] ) {
				$widget = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

				$widget_script_depends = $widget->get_script_depends();

				if ( ! empty( $widget_script_depends ) ) {
					foreach ( $widget_script_depends as $key => $script_handler ) {
						$this->depended_scripts[] = $script_handler;
					}
				}

			} else {
				$element = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

				$childrens = $element->get_children();

				foreach ( $childrens as $key => $children ) {
					$children_data[$key] = $children->get_raw_data();

					$this->find_widgets_script_handlers( $children_data );
				}
			}
		}
	}

	/**
	 * [get_script_uri_by_handler description]
	 * @param  [type] $handler [description]
	 * @return [type]          [description]
	 */
	public function get_script_uri_by_handler( $handler ) {
		global $wp_scripts;

		if ( isset( $wp_scripts->registered[ $handler ] ) ) {

			$src = $wp_scripts->registered[ $handler ]->src;

			if ( ! preg_match( '|^(https?:)?//|', $src ) && ! ( $wp_scripts->content_url && 0 === strpos( $src, $wp_scripts->content_url ) ) ) {
				$src = $wp_scripts->base_url . $src;
			}

			return $src;
		}

		return false;
	}

}
