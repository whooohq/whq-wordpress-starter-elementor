<div
	class="jet-smart-filters-settings-page jet-smart-filters-settings-page__general"
>
	<div class="avaliable-controls">
		<div class="avaliable-controls__header">
			<div class="cx-vui-title"><?php _e( 'Use filters for widgets', 'jet-smart-filters' ); ?></div>
			<div class="cx-vui-subtitle"><?php _e( 'List of content widgets that available for filtering', 'jet-smart-filters' ); ?></div>
		</div>

		<div class="avaliable-controls__list">
			<div
				class="avaliable-controls__item"
				v-for="( value, prop, index ) in pageOptions.avaliable_providers.options"
			>
				<cx-vui-switcher
					:key="index"
					:name="`avaliable-providers-${prop}`"
					:label="value"
					:wrapper-css="[ 'equalwidth' ]"
					return-true="true"
					return-false="false"
					v-model="pageOptions.avaliable_providers.value[prop]"
				>
				</cx-vui-switcher>
			</div>
		</div>
	</div>

</div>
