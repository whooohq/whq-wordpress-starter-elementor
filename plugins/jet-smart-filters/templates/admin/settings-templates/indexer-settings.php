<div
	class="jet-smart-filters-settings-page jet-smart-filters-settings-page__general"
>
	<cx-vui-switcher
		name="use_indexed_filters"
		label="<?php _e( 'Use Indexed filters', 'jet-smart-filters' ); ?>"
		description="<?php _e( 'Enable indexed filters functionality', 'jet-smart-filters' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="true"
		return-false="false"
		v-model="pageOptions.use_indexed_filters.value">
	</cx-vui-switcher>

	<cx-vui-switcher
		v-if="pageOptions.use_indexed_filters.value === 'true'"
		name="use_auto_indexing"
		label="<?php _e( 'Use auto re-indexing', 'jet-smart-filters' ); ?>"
		description="<?php _e( 'Auto re-indexing data when changing posts or filters', 'jet-smart-filters' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="true"
		return-false="false"
		v-model="pageOptions.use_auto_indexing.value">
	</cx-vui-switcher>

	<div
		class="avaliable-controls cx-vui-component"
		v-if="pageOptions.use_indexed_filters.value === 'true'"
	>
		<div class="avaliable-controls__header">
			<div class="cx-vui-title"><?php _e( 'Index Post Types', 'jet-smart-filters' ); ?></div>
			<div class="cx-vui-subtitle"><?php _e( 'List post types that will be indexed', 'jet-smart-filters' ); ?></div>
		</div>
		<div class="avaliable-controls__list">
			<div
				class="avaliable-controls__item"
				v-for="( value, prop, index ) in pageOptions.avaliable_post_types.options"
			>
				<cx-vui-switcher
					:key="index"
					:name="`avaliable-post-types-${prop}`"
					:label="value"
					:wrapper-css="[ 'equalwidth' ]"
					return-true="true"
					return-false="false"
					v-model="pageOptions.avaliable_post_types.value[prop]"
				>
				</cx-vui-switcher>
			</div>
		</div>
	</div>

</div>
