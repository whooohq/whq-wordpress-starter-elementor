<div
	class="jet-elements-settings-page jet-elements-settings-page__general"
>
	<cx-vui-switcher
		name="svg_uploads"
		label="<?php _e( 'SVG images upload status', 'jet-elements' ); ?>"
		description="<?php _e( 'Enable or disable SVG images uploading', 'jet-elements' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="enabled"
		return-false="disabled"
		v-model="pageOptions.svg_uploads.value">
	</cx-vui-switcher>

	<cx-vui-switcher
		name="jet_templates"
		label="<?php _e( 'Use Jet Templates', 'jet-elements' ); ?>"
		description="<?php _e( 'Add Jet page templates and blocks to Elementor templates library.', 'jet-elements' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="enabled"
		return-false="disabled"
		v-model="pageOptions.jet_templates.value">
	</cx-vui-switcher>

	<cx-vui-select
		name="widgets_load_level"
		label="<?php _e( 'Editor Load Level', 'jet-elements' ); ?>"
		description="<?php _e( 'Choose a certain set of options in the widget’s Style tab by moving the slider, and improve your Elementor editor performance by selecting appropriate style settings fill level (from None to Full level)', 'jet-elements' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions.widgets_load_level.options"
		v-model="pageOptions.widgets_load_level.value">
	</cx-vui-select>
</div>
