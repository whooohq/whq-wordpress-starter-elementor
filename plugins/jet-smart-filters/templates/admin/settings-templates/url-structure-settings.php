<div
	class="jet-smart-filters-settings-page"
>
	<div class="url-structure-type">
		<div class="url-structure-type__header">
			<div class="cx-vui-title"><?php _e( 'URL Structure Type', 'jet-smart-filters' ); ?></div>
			<div class="cx-vui-subtitle"><?php _e( 'List of URL structure types', 'jet-smart-filters' ); ?></div>
			<cx-vui-radio
				name="url_structure_type"
				v-model="pageOptions.url_structure_type.value"
				:optionsList="pageOptions.url_structure_type.options"
			>
			</cx-vui-radio>
		</div>
		<div class="rewritable-post-types"
			v-if="pageOptions.url_structure_type.value === 'permalink'"
		>
			<div class="rewritable-post-types__header">
				<div class="cx-vui-title"><?php _e( 'Rewritable Post Types', 'jet-smart-filters' ); ?></div>
				<div class="cx-vui-subtitle"><?php _e( 'Post Types and their Taxonomies for which permalinks will be rewritten', 'jet-smart-filters' ); ?></div>
			</div>
			<div class="rewritable-post-types__list">
				<div
					class="rewritable-post-types__item"
					v-for="( value, prop, index ) in pageOptions.rewritable_post_types.options"
				>
					<cx-vui-switcher
						:key="index"
						:name="`rewritable-post-types-${prop}`"
						:label="value"
						:wrapper-css="[ 'equalwidth' ]"
						return-true="true"
						return-false="false"
						v-model="pageOptions.rewritable_post_types.value[prop]"
					>
					</cx-vui-switcher>
				</div>
			</div>
		</div>
	</div>
</div>
