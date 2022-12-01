<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Jet_Blocks_Base extends Widget_Base {

	public $__context          = 'render';
	public $__processed_item   = false;
	public $__processed_index  = 0;
	public $__load_level       = 100;
	public $__include_controls = array();
	public $__exclude_controls = array();
	public $__new_icon_prefix  = 'selected_';

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		$this->__load_level = (int) jet_blocks_settings()->get( 'widgets_load_level', 100 );

		$widget_name = $this->get_name();

		$this->__include_controls = apply_filters( "jet-blocks/editor/{$widget_name}/include-controls", array(), $widget_name, $this );

		$this->__exclude_controls = apply_filters( "jet-blocks/editor/{$widget_name}/exclude-controls", array(), $widget_name, $this );
	}

	public function get_jet_help_url() {
		return false;
	}

	public function get_help_url() {

		$url = $this->get_jet_help_url();

		if ( ! empty( $url ) ) {
			return add_query_arg(
				array(
					'utm_source'   => 'jetblocks',
					'utm_medium'   => $this->get_name(),
					'utm_campaign' => 'need-help',
				),
				esc_url( $url )
			);
		}

		return false;
	}

	/**
	 * Get globaly affected template
	 *
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get_global_template( $name = null ) {

		$template = call_user_func( array( $this, sprintf( '__get_%s_template', $this->__context ) ) );

		if ( ! $template ) {
			$template = jet_blocks()->get_template( $this->get_name() . '/global/' . $name . '.php' );
		}

		return $template;
	}

	/**
	 * Get front-end template
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get_render_template( $name = null ) {
		return jet_blocks()->get_template( $this->get_name() . '/render/' . $name . '.php' );
	}

	/**
	 * Get editor template
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get_edit_template( $name = null ) {
		return jet_blocks()->get_template( $this->get_name() . '/edit/' . $name . '.php' );
	}

	/**
	 * Get global looped template for settings
	 * Required only to process repeater settings.
	 *
	 * @param  string $name    Base template name.
	 * @param  string $setting Repeater setting that provide data for template.
	 * @return void
	 */
	public function __get_global_looped_template( $name = null, $setting = null ) {

		$templates = array(
			'start' => $this->__get_global_template( $name . '-loop-start' ),
			'loop'  => $this->__get_global_template( $name . '-loop-item' ),
			'end'   => $this->__get_global_template( $name . '-loop-end' ),
		);

		call_user_func(
			array( $this, sprintf( '__get_%s_looped_template', $this->__context ) ), $templates, $setting
		);

	}

	/**
	 * Get render mode looped template
	 *
	 * @param  array  $templates [description]
	 * @param  [type] $setting   [description]
	 * @return [type]            [description]
	 */
	public function __get_render_looped_template( $templates = array(), $setting = null ) {

		$loop = $this->get_settings( $setting );

		if ( empty( $loop ) ) {
			return;
		}

		if ( ! empty( $templates['start'] ) ) {
			include $templates['start'];
		}

		foreach ( $loop as $item ) {

			$this->__processed_item = $item;
			if ( ! empty( $templates['start'] ) ) {
				include $templates['loop'];
			}
			$this->__processed_index++;
		}

		$this->__processed_item = false;
		$this->__processed_index = 0;

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
	public function __get_edit_looped_template( $templates = array(), $setting = null ) {
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
	 * Get current looped item dependends from context.
	 *
	 * @param  string $key Key to get from processed item
	 * @return mixed
	 */
	public function __loop_item( $keys = array(), $format = '%s' ) {

		return call_user_func( array( $this, sprintf( '__%s_loop_item', $this->__context ) ), $keys, $format );

	}

	/**
	 * Loop edit item
	 *
	 * @param  [type]  $keys       [description]
	 * @param  string  $format     [description]
	 * @param  boolean $nested_key [description]
	 * @return [type]              [description]
	 */
	public function __edit_loop_item( $keys = array(), $format = '%s' ) {

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
	public function __render_loop_item( $keys = array(), $format = '%s' ) {

		$item = $this->__processed_item;

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
	 * Include global template if any of passed settings is defined
	 *
	 * @param  [type] $name     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __glob_inc_if( $name = null, $settings = array() ) {

		$template = $this->__get_global_template( $name );

		call_user_func( array( $this, sprintf( '__%s_inc_if', $this->__context ) ), $template, $settings );

	}

	/**
	 * Include render template if any of passed setting is not empty
	 *
	 * @param  [type] $file     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __render_inc_if( $file = null, $settings = array() ) {

		foreach ( $settings as $setting ) {
			$val = $this->get_settings( $setting );

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
	public function __edit_inc_if( $file = null, $settings = array() ) {

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
	public function __open_wrap() {
		printf( '<div class="elementor-%s jet-blocks">', $this->get_name() );
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
	 * Print HTML markup if passed setting not empty.
	 *
	 * @param  string $setting Passed setting.
	 * @param  string $format  Required markup.
	 * @param  array  $args    Additional variables to pass into format string.
	 * @param  bool   $echo    Echo or return.
	 * @return string|void
	 */
	public function __html( $setting = null, $format = '%s' ) {

		call_user_func( array( $this, sprintf( '__%s_html', $this->__context ) ), $setting, $format );

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
	public function __get_html( $setting = null, $format = '%s' ) {

		ob_start();
		$this->__html( $setting, $format );
		return ob_get_clean();

	}

	/**
	 * Print HTML template
	 *
	 * @param  [type] $setting [description]
	 * @param  [type] $format  [description]
	 * @return [type]          [description]
	 */
	public function __render_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$key     = $setting[1];
			$setting = $setting[0];
		}

		$val = $this->get_settings( $setting );

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
	public function __edit_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$setting = $setting[0] . '.' . $setting[1];
		}

		echo '<# if ( settings.' . $setting . ' ) { #>';
		printf( $format, '{{{ settings.' . $setting . ' }}}' );
		echo '<# } #>';
	}

	public function __add_control( $control_id = false, $control_args = array(), $load_level = 100 ) {

		if (
			( $this->__load_level < $load_level
			  || 0 === $this->__load_level
			  || in_array( $control_id, $this->__exclude_controls )
			) && !in_array( $control_id, $this->__include_controls )
		) {
			return false;
		}

		if ( function_exists( 'jet_styles_manager' ) && jet_styles_manager()->compatibility ) {
			$control_args = jet_styles_manager()->compatibility->set_control_args(
				$control_args,
				$load_level,
				'jet-blocks'
			);
		}

		$this->add_control( $control_id, $control_args );
	}

	public function __add_responsive_control( $control_id = false, $control_args = array(), $load_level = 100 ) {

		if (
			( $this->__load_level < $load_level
			  || 0 === $this->__load_level
			  || in_array( $control_id, $this->__exclude_controls )
			) && !in_array( $control_id, $this->__include_controls )
		) {
			return false;
		}

		if ( function_exists( 'jet_styles_manager' ) && jet_styles_manager()->compatibility ) {
			$control_args = jet_styles_manager()->compatibility->set_control_args(
				$control_args,
				$load_level,
				'jet-blocks'
			);
		}

		$this->add_responsive_control( $control_id, $control_args );
	}

	public function __add_group_control( $group_control_type = false, $group_control_args = array(), $load_level = 100 ) {

		if (
			( $this->__load_level < $load_level
			  || 0 === $this->__load_level
			  || in_array( $group_control_args['name'], $this->__exclude_controls )
			) && !in_array( $group_control_args['name'], $this->__include_controls )
		) {
			return false;
		}

		if ( function_exists( 'jet_styles_manager' ) && jet_styles_manager()->compatibility ) {
			$group_control_args = jet_styles_manager()->compatibility->set_group_control_args(
				$group_control_type,
				$group_control_args,
				$load_level,
				'jet-blocks'
			);
		}

		$this->add_group_control( $group_control_type, $group_control_args );
	}

	public function __start_controls_section( $controls_section_id = false, $controls_section_args = array(), $load_level = 25 ) {

		if ( ! $controls_section_id || $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->start_controls_section( $controls_section_id, $controls_section_args );
	}

	public function __end_controls_section( $load_level = 25 ) {

		if ( $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->end_controls_section();
	}

	public function __start_controls_tabs( $tabs_id = false, $load_level = 25 ) {

		if ( ! $tabs_id || $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->start_controls_tabs( $tabs_id );
	}

	public function __end_controls_tabs( $load_level = 25 ) {

		if ( $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->end_controls_tabs();
	}


	public function __start_controls_tab( $tab_id = false, $tab_args = array(), $load_level = 25 ) {

		if ( ! $tab_id || $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->start_controls_tab( $tab_id, $tab_args );
	}

	public function __end_controls_tab( $load_level = 25 ) {

		if ( $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->end_controls_tab();
	}

	public function __start_popover( $load_level = 25 ) {

		if ( $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->start_popover();
	}

	public function __end_popover( $load_level = 25 ) {

		if ( $this->__load_level < $load_level || 0 === $this->__load_level ) {
			return false;
		}

		$this->end_popover();
	}

	/**
	 * Add icon control
	 *
	 * @param string $id
	 * @param array  $args
	 * @param object $instance
	 */
	public function __add_advanced_icon_control( $id, array $args = array(), $instance = null ) {

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {

			$_id = $id; // old control id
			$id  = $this->__new_icon_prefix . $id;

			$args['type'] = Controls_Manager::ICONS;
			$args['fa4compatibility'] = $_id;

			unset( $args['file'] );
			unset( $args['default'] );

			if ( isset( $args['fa5_default'] ) ) {
				$args['default'] = $args['fa5_default'];
			}
		} else {
			$args['type'] = Controls_Manager::ICON;
		}

		unset( $args['fa5_default'] );

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
	public function __prepare_icon_id_for_condition( $id ) {

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {
			return $this->__new_icon_prefix . $id . '[value]';
		}

		return $id;
	}

	/**
	 * Print HTML icon markup
	 *
	 * @param  array $setting
	 * @param  string $format
	 * @param  string $icon_class
	 * @return void
	 */
	public function __icon( $setting = null, $format = '%s', $icon_class = '' ) {
		call_user_func( array( $this, sprintf( '__%s_icon', $this->__context ) ), $setting, $format, $icon_class );
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
	public function __render_icon( $setting = null, $format = '%s', $icon_class = '', $echo = true ) {

		if ( false === $this->__processed_item ) {
			$settings = $this->get_settings_for_display();
		} else {
			$settings = $this->__processed_item;
		}

		$new_setting = $this->__new_icon_prefix . $setting;

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
	 * Returns HTML icon markup
	 *
	 * @param  array $setting
	 * @param  string $format
	 * @param  string $icon_class
	 * @return string
	 */
	public function __get_icon( $setting = null, $format = '%s', $icon_class = '' ) {
		return $this->__render_icon( $setting, $format, $icon_class, false );
	}
}
