<div
	id="jet-menu-settings-fields"
	class="jet-menu-settings-fields"
>
	<div
		class="jet-menu-settings-fields__list"
		:class="{ 'loading-state': savingState }"
	>
		<div
			class="jet-menu-settings-fields__item"
			v-for="(item, slug) in locationSettings"
		>
			<div
				class="jet-menu-settings-fields__item-label"
			>
				{{ item.label }}
			</div>

			<div class="jet-menu-settings-fields__item-control enable-control">
				<span class="label"><?php _e( 'Enable JetMenu for current location', 'jet-menu' ); ?></span>
				<cx-vui-switcher
					:name="`${slug}-enable`"
					:wrapper-css="[ 'equalwidth' ]"
					return-true="true"
					return-false="false"
					:prevent-wrap="true"
					v-model="item.enabled"
				>
				</cx-vui-switcher>
			</div>

			<div class="jet-menu-settings-fields__item-control preset-control" v-if="isPresetsVisible">
				<span class="label"><?php _e( 'Select options preset', 'jet-menu' ); ?></span>
				<cx-vui-select
					:name="`${slug}-preset`"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:prevent-wrap="true"
					:options-list="optionPresetList"
					v-model="item.preset"
				>
				</cx-vui-select>
			</div>

			<div class="jet-menu-settings-fields__item-control mobile-layout-control" v-if="isMobileLayoutVisible">
				<span class="label"><?php _e( 'Select menu for mobile layout', 'jet-menu' ); ?></span>
				<cx-vui-select
					:name="`${slug}-mobile-menu`"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:prevent-wrap="true"
					:options-list="optionMenuList"
					v-model="item.mobile"
				>
				</cx-vui-select>
			</div>

		</div>
	</div>
</div>
