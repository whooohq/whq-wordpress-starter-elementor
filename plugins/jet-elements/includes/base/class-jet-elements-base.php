<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Jet_Elements_Base extends Widget_Base {

	public $_context          = 'render';
	public $_processed_item   = false;
	public $_processed_index  = 0;
	public $_load_level       = 100;
	public $_include_controls = [];
	public $_exclude_controls = [];
	public $_new_icon_prefix  = 'selected_';

	/**
	 * [__construct description]
	 * @param array  $data [description]
	 * @param [type] $args [description]
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$this->_load_level = (int)jet_elements_settings()->get( 'widgets_load_level', 100 );

		$widget_name = $this->get_name();

		$this->_include_controls = apply_filters( "jet-elements/editor/{$widget_name}/include-controls", [], $widget_name, $this );

		$this->_exclude_controls = apply_filters( "jet-elements/editor/{$widget_name}/exclude-controls", [], $widget_name, $this );
	}

	/**
	 * [get_jet_help_url description]
	 * @return [type] [description]
	 */
	public function get_jet_help_url() {
		return false;
	}

	/**
	 * [get_help_url description]
	 * @return [type] [description]
	 */
	public function get_help_url() {

		$url = $this->get_jet_help_url();

		$style_parent_theme = wp_get_theme( get_template() );

		$author_slug = strtolower( preg_replace('/\s+/', '', $style_parent_theme->get('Author') ) );

		if ( ! empty( $url ) ) {
			return add_query_arg(
				array(
					'utm_source'   => $author_slug,
					'utm_medium'   => 'jetelements' . '_' . $this->get_name(),
					'utm_campaign' => 'need-help',
				),
				esc_url( $url )
			);
		}

		return false;
	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __get_global_template( $name = null ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_get_global_template()' );

		return $this->_get_global_template( $name );
	}

	/**
	 * Get globaly affected template
	 *
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function _get_global_template( $name = null ) {

		$template = call_user_func( array( $this, sprintf( '_get_%s_template', $this->_context ) ), $name );

		if ( ! $template ) {
			$template = jet_elements()->get_template( $this->get_name() . '/global/' . $name . '.php' );
		}

		return $template;
	}

	/**
	 * Get front-end template
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function _get_render_template( $name = null ) {
		return jet_elements()->get_template( $this->get_name() . '/render/' . $name . '.php' );
	}

	/**
	 * Get editor template
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function _get_edit_template( $name = null ) {
		return jet_elements()->get_template( $this->get_name() . '/edit/' . $name . '.php' );
	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __get_global_looped_template( $name = null, $setting = null, $callback = null ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_get_global_looped_template()' );

		$this->_get_global_looped_template( $name, $setting, $callback );
	}

	/**
	 * Get global looped template for settings
	 * Required only to process repeater settings.
	 *
	 * @param  string $name     Base template name.
	 * @param  string $setting  Repeater setting that provide data for template.
	 * @param  string $callback Callback for preparing a loop array
	 * @return void
	 */
	public function _get_global_looped_template( $name = null, $setting = null, $callback = null ) {

		$templates = array(
			'start' => $this->_get_global_template( $name . '-loop-start' ),
			'loop'  => $this->_get_global_template( $name . '-loop-item' ),
			'end'   => $this->_get_global_template( $name . '-loop-end' ),
		);

		call_user_func(
			array( $this, sprintf( '_get_%s_looped_template', $this->_context ) ), $templates, $setting, $callback
		);

	}

	/**
	 * Get render mode looped template
	 *
	 * @param  array  $templates [description]
	 * @param  string $setting   [description]
	 * @param  string $callback  Callback for preparing a loop array
	 * @return void
	 */
	public function _get_render_looped_template( $templates = array(), $setting = null, $callback = null ) {

		$loop = $this->get_settings_for_display( $setting );
		$loop = apply_filters( 'jet-elements/widget/loop-items', $loop, $setting, $this );

		if ( empty( $loop ) ) {
			return;
		}

		if ( $callback && is_callable( $callback ) ) {
			$loop = call_user_func( $callback, $loop );
		}

		if ( ! empty( $templates['start'] ) ) {
			include $templates['start'];
		}

		foreach ( $loop as $item ) {

			$this->_processed_item = $item;

			if ( ! empty( $templates['loop'] ) ) {
				include $templates['loop'];
			}
			$this->_processed_index++;
		}

		$this->_processed_item = false;
		$this->_processed_index = 0;

		if ( ! empty( $templates['end'] ) ) {
			include $templates['end'];
		}

	}

	/**
	 * Get edit mode looped template
	 *
	 * @param  array  $templates [description]
	 * @param  [type] $setting   [description]
	 * @return [type]            [description]
	 */
	public function _get_edit_looped_template( $templates = array(), $setting = null ) {
		?>
		<# if ( settings.<?php echo $setting; ?> ) { #>
		<?php
			if ( ! empty( $templates['start'] ) ) {
				include $templates['start'];
			}
		?>
			<# _.each( settings.<?php echo $setting; ?>, function( item ) { #>
			<?php
				if ( ! empty( $templates['loop'] ) ) {
					include $templates['loop'];
				}
			?>
			<# } ); #>
		<?php
			if ( ! empty( $templates['end'] ) ) {
				include $templates['end'];
			}
		?>
		<# } #>
		<?php
	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __loop_item( $keys = array(), $format = '%s' ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_loop_item()' );

		return $this->_loop_item( $keys, $format );
	}

	/**
	 * Get current looped item dependends from context.
	 *
	 * @param  string $key Key to get from processed item
	 * @return mixed
	 */
	public function _loop_item( $keys = array(), $format = '%s' ) {

		return call_user_func( array( $this, sprintf( '_%s_loop_item', $this->_context ) ), $keys, $format );

	}

	/**
	 * Loop edit item
	 *
	 * @param  [type]  $keys       [description]
	 * @param  string  $format     [description]
	 * @param  boolean $nested_key [description]
	 * @return [type]              [description]
	 */
	public function _edit_loop_item( $keys = array(), $format = '%s' ) {

		$settings = $keys[0];

		if ( isset( $keys[1] ) ) {
			$settings .= '.' . $keys[1];
		}

		ob_start();

		echo '<# if ( item.' . $settings . ' ) { #>';
		printf( $format, '{{{ item.' . $settings . ' }}}' );
		echo '<# } #>';

		return ob_get_clean();
	}

	/**
	 * Loop render item
	 *
	 * @param  string  $format     [description]
	 * @param  [type]  $key        [description]
	 * @param  boolean $nested_key [description]
	 * @return [type]              [description]
	 */
	public function _render_loop_item( $keys = array(), $format = '%s' ) {

		$item = $this->_processed_item;

		$key        = $keys[0];
		$nested_key = isset( $keys[1] ) ? $keys[1] : false;

		if ( empty( $item ) || ! isset( $item[ $key ] ) ) {
			return false;
		}

		if ( false === $nested_key || ! is_array( $item[ $key ] ) ) {
			$value = $item[ $key ];
		} else {
			$value = isset( $item[ $key ][ $nested_key ] ) ? $item[ $key ][ $nested_key ] : false;
		}

		if ( ! empty( $value ) ) {
			return sprintf( $format, $value );
		}

	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __glob_inc_if( $name = null, $settings = array() ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_glob_inc_if()' );

		$this->_glob_inc_if( $name, $settings );
	}

	/**
	 * Include global template if any of passed settings is defined
	 *
	 * @param  [type] $name     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function _glob_inc_if( $name = null, $settings = array() ) {

		$template = $this->_get_global_template( $name );

		call_user_func( array( $this, sprintf( '_%s_inc_if', $this->_context ) ), $template, $settings );

	}

	/**
	 * Include render template if any of passed setting is not empty
	 *
	 * @param  [type] $file     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function _render_inc_if( $file = null, $settings = array() ) {

		foreach ( $settings as $setting ) {
			$val = $this->get_settings_for_display( $setting );

			if ( ! empty( $val ) ) {
				include $file;
				return;
			}

		}

	}

	/**
	 * Include render template if any of passed setting is not empty
	 *
	 * @param  [type] $file     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function _edit_inc_if( $file = null, $settings = array() ) {

		$condition = null;
		$sep       = null;

		foreach ( $settings as $setting ) {
			$condition .= $sep . 'settings.' . $setting;
			$sep = ' || ';
		}

		?>

		<# if ( <?php echo $condition; ?> ) { #>

			<?php include $file; ?>

		<# } #>

		<?php
	}

	/**
	 * Open standard wrapper
	 *
	 * @return void
	 */
	public function _open_wrap() {
		printf( '<div class="elementor-%s jet-elements">', $this->get_name() );
	}

	/**
	 * Close standard wrapper
	 *
	 * @return void
	 */
	public function _close_wrap() {
		echo '</div>';
	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __html( $setting = null, $format = '%s' ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_html()' );

		$this->_html( $setting, $format );
	}

	/**
	 * Print HTML markup if passed setting not empty.
	 *
	 * @param  string $setting Passed setting.
	 * @param  string $format  Required markup.
	 * @param  array  $args    Additional variables to pass into format string.
	 * @param  bool   $echo    Echo or return.
	 * @return string|void
	 */
	public function _html( $setting = null, $format = '%s' ) {

		call_user_func( array( $this, sprintf( '_%s_html', $this->_context ) ), $setting, $format );

	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __get_html( $setting = null, $format = '%s' ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_get_html()' );

		return $this->_get_html( $setting, $format );
	}

	/**
	 * Returns HTML markup if passed setting not empty.
	 *
	 * @param  string $setting Passed setting.
	 * @param  string $format  Required markup.
	 * @param  array  $args    Additional variables to pass into format string.
	 * @param  bool   $echo    Echo or return.
	 * @return string|void
	 */
	public function _get_html( $setting = null, $format = '%s' ) {

		ob_start();
		$this->_html( $setting, $format );
		return ob_get_clean();

	}

	/**
	 * Print HTML template
	 *
	 * @param  [type] $setting [description]
	 * @param  [type] $format  [description]
	 * @return [type]          [description]
	 */
	public function _render_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$key     = $setting[1];
			$setting = $setting[0];
		}

		$val = $this->get_settings_for_display( $setting );

		if ( ! is_array( $val ) && '0' === $val ) {
			printf( $format, $val );
		}

		if ( is_array( $val ) && empty( $val[ $key ] ) ) {
			return '';
		}

		if ( ! is_array( $val ) && empty( $val ) ) {
			return '';
		}

		if ( is_array( $val ) ) {
			printf( $format, $val[ $key ] );
		} else {
			printf( $format, $val );
		}

	}

	/**
	 * Print underscore template
	 *
	 * @param  [type] $setting [description]
	 * @param  [type] $format  [description]
	 * @return [type]          [description]
	 */
	public function _edit_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$setting = $setting[0] . '.' . $setting[1];
		}

		echo '<# if ( settings.' . $setting . ' ) { #>';
		printf( $format, '{{{ settings.' . $setting . ' }}}' );
		echo '<# } #>';
	}

	/**
	 * Add icon control
	 *
	 * @param string $id
	 * @param array  $args
	 * @param object $instance
	 */
	public function _add_advanced_icon_control( $id, array $args = array(), $instance = null ) {

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {

			$_id = $id; // old control id
			$id  = $this->_new_icon_prefix . $id;

			$args['type'] = Controls_Manager::ICONS;
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
	 * @param  string $id Old icon control ID.
	 * @return string
	 */
	public function _prepare_icon_id_for_condition( $id ) {

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {
			return $this->_new_icon_prefix . $id . '[value]';
		}

		return $id;
	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __icon( $setting = null, $format = '%s', $icon_class = '' ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_icon()' );

		$this->_icon( $setting, $format, $icon_class );
	}

	/**
	 * Print HTML icon markup
	 *
	 * @param  array $setting
	 * @param  string $format
	 * @param  string $icon_class
	 * @return void
	 */
	public function _icon( $setting = null, $format = '%s', $icon_class = '' ) {
		call_user_func( array( $this, sprintf( '_%s_icon', $this->_context ) ), $setting, $format, $icon_class );
	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __get_icon( $setting = null, $format = '%s', $icon_class = '' ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_get_icon()' );

		return $this->_get_icon( $setting, $format, $icon_class );
	}

	/**
	 * Returns HTML icon markup
	 *
	 * @param  array $setting
	 * @param  string $format
	 * @param  string $icon_class
	 * @return string
	 */
	public function _get_icon( $setting = null, $format = '%s', $icon_class = '' ) {
		return $this->_render_icon( $setting, $format, $icon_class, false );
	}

	/**
	 * @deprecated 2.2.15
	 */
	public function __render_icon( $setting = null, $format = '%s', $icon_class = '', $echo = true ) {
		_deprecated_function( __METHOD__, '2.2.15', __CLASS__ . '::_render_icon()' );

		return $this->_render_icon( $setting, $format, $icon_class, $echo );
	}

	/**
	 * Print HTML icon template
	 *
	 * @param  array  $setting
	 * @param  string $format
	 * @param  string $icon_class
	 * @param  bool   $echo
	 *
	 * @return void|string
	 */
	public function _render_icon( $setting = null, $format = '%s', $icon_class = '', $echo = true ) {

		if ( false === $this->_processed_item ) {
			$settings = $this->get_settings_for_display();
		} else {
			$settings = $this->_processed_item;
		}

		$new_setting = $this->_new_icon_prefix . $setting;

		$migrated = isset( $settings['__fa4_migrated'][ $new_setting ] );
		$is_new   = empty( $settings[ $setting ] ) && class_exists( 'Elementor\Icons_Manager' ) && Icons_Manager::is_migration_allowed();

		$icon_html = '';

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

	/**
	 * [__add_control description]
	 * @param  boolean $control_id   [description]
	 * @param  array   $control_args [description]
	 * @param  integer $load_level   [description]
	 * @return [type]                [description]
	 */
	public function _add_control( $control_id = false, $control_args = [], $load_level = 100 ) {

		if (
			( $this->_load_level < $load_level
			  || 0 === $this->_load_level
			  || in_array( $control_id, $this->_exclude_controls )
			) && !in_array( $control_id, $this->_include_controls )
		) {
			return false;
		}

		if ( function_exists( 'jet_styles_manager' ) && jet_styles_manager()->compatibility ) {
			$control_args = jet_styles_manager()->compatibility->set_control_args(
				$control_args,
				$load_level,
				'jet-elements'
			);
		}

		$this->add_control( $control_id, $control_args );
	}

	/**
	 * [__add_responsive_control description]
	 * @param  boolean $control_id   [description]
	 * @param  array   $control_args [description]
	 * @param  integer $load_level   [description]
	 * @return [type]                [description]
	 */
	public function _add_responsive_control( $control_id = false, $control_args = [], $load_level = 100 ) {

		if (
			( $this->_load_level < $load_level
			  || 0 === $this->_load_level
			  || in_array( $control_id, $this->_exclude_controls )
			) && !in_array( $control_id, $this->_include_controls )
		) {
			return false;
		}

		if ( function_exists( 'jet_styles_manager' ) && jet_styles_manager()->compatibility ) {
			$control_args = jet_styles_manager()->compatibility->set_control_args(
				$control_args,
				$load_level,
				'jet-elements'
			);
		}

		$this->add_responsive_control( $control_id, $control_args );
	}

	/**
	 * [__add_group_control description]
	 * @param  boolean $group_control_type [description]
	 * @param  array   $group_control_args [description]
	 * @param  integer $load_level         [description]
	 * @return [type]                      [description]
	 */
	public function _add_group_control( $group_control_type = false, $group_control_args = [], $load_level = 100 ) {

		if (
			( $this->_load_level < $load_level
			  || 0 === $this->_load_level
			  || in_array( $group_control_args['name'], $this->_exclude_controls )
			) && !in_array( $group_control_args['name'], $this->_include_controls )
		) {
			return false;
		}

		if ( function_exists( 'jet_styles_manager' ) && jet_styles_manager()->compatibility ) {
			$group_control_args = jet_styles_manager()->compatibility->set_group_control_args(
				$group_control_type,
				$group_control_args,
				$load_level,
				'jet-elements'
			);
		}

		$this->add_group_control( $group_control_type, $group_control_args );
	}

	/**
	 * [__add_icon_control description]
	 * @param  [type] $id   [description]
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public function _add_icon_control( $id, array $args = array(), $load_level = 100 ) {

		if (
			( $this->_load_level < $load_level
			  || 0 === $this->_load_level
			  || in_array( $id, $this->_exclude_controls )
			) && !in_array( $id, $this->_include_controls )
		) {
			return false;
		}

		$this->_add_advanced_icon_control( $id, $args );
	}

	/**
	 * [__start_controls_section description]
	 * @param  boolean $controls_section_id   [description]
	 * @param  array   $controls_section_args [description]
	 * @param  integer $load_level            [description]
	 * @return [type]                         [description]
	 */
	public function _start_controls_section( $controls_section_id = false, $controls_section_args = [], $load_level = 25 ) {

		if ( ! $controls_section_id || $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->start_controls_section( $controls_section_id, $controls_section_args );
	}

	/**
	 * [__end_controls_section description]
	 * @param  integer $load_level [description]
	 * @return [type]              [description]
	 */
	public function _end_controls_section( $load_level = 25 ) {

		if ( $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->end_controls_section();
	}

	/**
	 * [__start_controls_tabs description]
	 * @param  boolean $tabs_id    [description]
	 * @param  integer $load_level [description]
	 * @return [type]              [description]
	 */
	public function _start_controls_tabs( $tabs_id = false, $load_level = 25 ) {

		if ( ! $tabs_id || $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->start_controls_tabs( $tabs_id );
	}

	/**
	 * [__end_controls_tabs description]
	 * @param  integer $load_level [description]
	 * @return [type]              [description]
	 */
	public function _end_controls_tabs( $load_level = 25 ) {

		if ( $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->end_controls_tabs();
	}

	/**
	 * [__start_controls_tab description]
	 * @param  boolean $tab_id     [description]
	 * @param  array   $tab_args   [description]
	 * @param  integer $load_level [description]
	 * @return [type]              [description]
	 */
	public function _start_controls_tab( $tab_id = false, $tab_args = [], $load_level = 25 ) {

		if ( ! $tab_id || $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->start_controls_tab( $tab_id, $tab_args );
	}

	/**
	 * [__end_controls_tab description]
	 * @param  integer $load_level [description]
	 * @return [type]              [description]
	 */
	public function _end_controls_tab( $load_level = 25 ) {

		if ( $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->end_controls_tab();
	}

	/**
	 * Start popover
	 *
	 * @param int $load_level
	 * @return void|bool
	 */
	public function _start_popover( $load_level = 25 ) {

		if ( $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->start_popover();
	}

	/**
	 * End popover
	 *
	 * @param int $load_level
	 * @return void|bool
	 */
	public function _end_popover( $load_level = 25 ) {

		if ( $this->_load_level < $load_level || 0 === $this->_load_level ) {
			return false;
		}

		$this->end_popover();
	}

	public function _add_link_attributes( $element, array $url_control, $overwrite = false ) {
		if ( method_exists( $this, 'add_link_attributes' ) ) {
			return $this->add_link_attributes( $element, $url_control, $overwrite );
		}

		$attributes = array();

		if ( ! empty( $url_control['url'] ) ) {
			$attributes['href'] = esc_url( $url_control['url'] );
		}

		if ( ! empty( $url_control['is_external'] ) ) {
			$attributes['target'] = '_blank';
		}

		if ( ! empty( $url_control['nofollow'] ) ) {
			$attributes['rel'] = 'nofollow';
		}

		if ( $attributes ) {
			$this->add_render_attribute( $element, $attributes, $overwrite );
		}

		return $this;
	}

}
