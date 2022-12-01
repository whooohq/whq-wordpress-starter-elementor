<?php

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * JetElements date/time control.
 *
 * A base control for creating date time control. Displays a date/time picker
 * based on the Flatpickr library @see https://chmln.github.io/flatpickr/ .
 *
 * @since 2.1.0
 */
class Jet_Elements_Control_Date_Time extends Elementor\Control_Date_Time {

	/**
	 * Get date time control type.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'jet_dynamic_date_time';
	}

	/**
	 * Get date time control default settings.
	 *
	 * @since 2.1.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(),
			array(
				'dynamic' => array(
					'categories' => array( TagsModule::POST_META_CATEGORY ),
				)
			)
		);
	}

	/**
	 * Render date time control output in the editor.
	 *
	 * @since 2.1.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper">
				<input id="<?php echo $control_uid; ?>" placeholder="{{ data.placeholder }}" class="elementor-date-time-picker flatpickr elementor-control-tag-area" type="text" data-setting="{{ data.name }}">
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
