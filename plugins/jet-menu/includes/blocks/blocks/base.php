<?php
namespace Jet_Menu\Blocks;

abstract class Base {

	/**
	 * @var string
	 */
	protected $namespace = 'jet-menu/';

	/**
	 * @var null
	 */
	public $block_manager = null;

	/**
	 * @var null
	 */
	public $controls_manager = null;

	/**
	 * @var null
	 */
	public $css_scheme = null;

	/**
	 * Base constructor.
	 */
	public function __construct() {

		$attributes = $this->get_attributes();

		/**
		 * Set default blocks attributes to avoid errors
		 */
		$attributes['className'] = [
			'type'    => 'string',
			'default' => '',
		];

		if ( $this->is_style_manager_enable() ) {
			$this->set_css_scheme();
			$this->set_style_manager_instance();
			$this->add_style_manager_options();
		}

		register_block_type(
			$this->namespace . $this->get_name(),
			array(
				'attributes'      => $attributes,
				'render_callback' => [ $this, 'render_callback' ],
				'script'          => $this->get_script_depends(),
				'style'           => $this->get_style_depends(),
				'editor_script'   => $this->get_editor_script_depends(),
				'editor_style'    => $this->get_editor_style_depends(),
			)
		);

		$this->init();
	}

	/**
	 * Return filter name
	 *
	 * @return String
	 */
	abstract public function get_name();

	/**
	 * Return attributes array
	 *
	 * @return array
	 */
	abstract public function get_attributes();

	/**
	 * Return callback
	 *
	 * @return html
	 */
	abstract public function render_callback();

	/**
	 * @return string
	 */
	public function init() {}

	/**
	 * @return string
	 */
	public function get_script_depends() {
		return '';
	}

	/**
	 * @return string
	 */
	public function get_style_depends() {
		return '';
	}

	/**
	 * @return string
	 */
	public function get_editor_script_depends() {
		return 'jet-menu';
	}

	/**
	 * @return string
	 */
	public function get_editor_style_depends() {
		return 'jet-menu';
	}

	/**
	 * Is editor context
	 *
	 * @return boolean
	 */
	public function is_editor() {
		return isset( $_REQUEST['context'] ) && $_REQUEST['context'] === 'edit' ? true : false;
	}

	/**
	 * @return bool
	 */
	public function is_style_manager_enable() {
		return class_exists( 'JET_SM\Gutenberg\Block_Manager' ) && class_exists( 'JET_SM\Gutenberg\Block_Manager' );
	}

	/**
	 * Set css classes
	 *
	 * @return boolean
	 */
	public function set_css_scheme(){
		$this->css_scheme = [];
	}

	/**
	 * Set style manager class instance
	 *
	 * @return boolean
	 */
	public function set_style_manager_instance(){
		$name              = $this->namespace . $this->get_name();

		$this->block_manager = \JET_SM\Gutenberg\Block_Manager::get_instance();
		$this->controls_manager = new \JET_SM\Gutenberg\Controls_Manager( $name );
	}

}
