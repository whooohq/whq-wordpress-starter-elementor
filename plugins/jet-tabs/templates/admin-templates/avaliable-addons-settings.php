<div
	class="jet-tabs-settings-page jet-tabs-settings-page__avaliable-addons"
>
	<div class="jet-tabs-settings-page__avaliable-controls">
		<div class="cx-vui-title"><?php _e( 'Avaliable Widgets', 'jet-tabs' ); ?></div>
		<div
			class="jet-tabs-settings-page__avaliable-control"
			v-for="(option, index) in pageOptions.avaliable_widgets.options">
			<cx-vui-switcher
				:key="index"
				:name="`avaliable-widget-${option.value}`"
				:label="option.label"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions.avaliable_widgets.value[option.value]"
			>
			</cx-vui-switcher>
		</div>
	</div>
</div>
