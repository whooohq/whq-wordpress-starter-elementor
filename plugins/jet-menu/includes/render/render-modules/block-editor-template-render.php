<?php
namespace Jet_Menu\Render;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Block_Editor_Template_Render extends Base_Render {

	/**
	 * [$name description]
	 * @var string
	 */
	protected $name = 'block-editor-template-render';

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
		$template_id = $this->get( 'template_id' );

		$template_obj = get_post( $template_id );
		$raw_template_content = $template_obj->post_content;

		if ( empty( $raw_template_content ) ) {
			return false;
		}

		$blocks_template_content = apply_filters( 'jet-menu/render/block-editor/content', do_blocks( $raw_template_content ), $template_id ) ;

		$this->maybe_enqueue_css();

		echo do_shortcode( $blocks_template_content );

	}

	/**
	 * @return array
	 */
	public function get_render_data() {

		$template_id = $this->get( 'template_id', false );

		$template_scripts = [];
		$template_styles = [];

		return [
			'template_content' => $this->get_content(),
			'template_scripts' => $template_scripts,
			'template_styles'  => $template_styles,
		];
	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	public function maybe_enqueue_css() {
		
		if ( ! class_exists( '\JET_SM\Gutenberg\Style_Manager' ) ) {
			return;
		}

		$template_id = $this->get( 'template_id' );
		
		\JET_SM\Gutenberg\Style_Manager::get_instance()->render_blocks_style( $template_id );
		\JET_SM\Gutenberg\Style_Manager::get_instance()->render_blocks_fonts( $template_id );

	}
}
