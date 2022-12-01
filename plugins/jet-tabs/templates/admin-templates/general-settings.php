<div
	class="jet-tricks-settings-page jet-tricks-settings-page__general"
>
	<cx-vui-select
		name="widgets-load-level"
		label="<?php _e( 'Editor Load Level', 'jet-tricks' ); ?>"
		description="<?php _e( 'Choose a certain set of options in the widget’s Style tab by moving the slider, and improve your Elementor editor performance by selecting appropriate style settings fill level (from None to Full level)', 'jet-tricks' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions.widgets_load_level.options"
		v-model="pageOptions.widgets_load_level.value">
	</cx-vui-select>
</div>
