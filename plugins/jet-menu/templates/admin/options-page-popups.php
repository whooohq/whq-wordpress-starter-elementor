<cx-vui-popup
	class="preset-manager"
	v-model="presetManagerVisible"
	:footer="false"
	body-width="800px"
>
	<div slot="title">
		<div class="cx-vui-popup__header-label"><?php _e( 'Preset Manager', 'jet-menu' ); ?></div>
	</div>
	<div class="cx-vui-popup__content-inner" slot="content">
		<div class="cx-vui-component cx-vui-component--equalwidth">
			<div class="cx-vui-component__meta">
				<label class="cx-vui-component__label"><?php _e( 'Create Preset', 'jet-menu' ); ?></label>
				<div class="cx-vui-component__desc"><?php
					_e( 'Create new preset from current options configuration', 'jet-menu' );
				?></div>
			</div>
			<div class="cx-vui-component__control inline-control">
				<cx-vui-input
					name="new-preset-name"
					:wrapper-css="[ 'equalwidth' ]"
					placeholder="<?php _e( 'Preset name', 'jet-menu' ); ?>"
					size="fullwidth"
					type="text"
					:prevent-wrap="true"
					v-model="newPresetName">
				</cx-vui-input>
				<cx-vui-button
					button-style="accent-border"
					size="mini"
					:loading="creatingState"
					@click="createPreset"
					v-if="newPresetName!==''"
				>
					<span slot="label"><?php _e( 'Create', 'jet-menu' ); ?></span>
				</cx-vui-button>
			</div>
		</div>

		<div
			class="cx-vui-component cx-vui-component--equalwidth"
			v-if="0 !== optionPresetList.length"
		>
			<div class="cx-vui-component__meta">
				<label class="cx-vui-component__label"><?php _e( 'Update Preset', 'jet-menu' ); ?></label>
				<div class="cx-vui-component__desc"><?php
					_e( 'Save current options configuration to existing preset', 'jet-menu' );
				?></div>
			</div>
			<div class="cx-vui-component__control inline-control">
				<cx-vui-select
					name="update-preset-name"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:prevent-wrap="true"
					:options-list="optionPresetList"
					v-model="updatePresetId"
				>
				</cx-vui-select>
				<cx-vui-button
					button-style="accent-border"
					size="mini"
					:loading="updatingState"
					@click="updatePreset"
					v-if="updatePresetId!==''"
				>
					<span slot="label"><?php _e( 'Update', 'jet-menu' ); ?></span>
				</cx-vui-button>
			</div>
		</div>

		<div
			class="cx-vui-component cx-vui-component--equalwidth"
			v-if="0 !== optionPresetList.length"
		>
			<div class="cx-vui-component__meta">
				<label class="cx-vui-component__label"><?php _e( 'Apply This Preset Globally', 'jet-menu' ); ?></label>
				<div class="cx-vui-component__desc"><?php
					_e( 'Load preset to use it for all menu locations', 'jet-menu' );
				?></div>
			</div>
			<div class="cx-vui-component__control inline-control">
				<cx-vui-select
					name="apply-preset-name"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:prevent-wrap="true"
					:options-list="optionPresetList"
					v-model="applyPresetId"
				>
				</cx-vui-select>
				<cx-vui-button
					button-style="accent-border"
					size="mini"
					:loading="applyState"
					@click="applyPreset"
					v-if="applyPresetId!==''"
				>
					<span slot="label"><?php _e( 'Apply', 'jet-menu' ); ?></span>
				</cx-vui-button>
			</div>
		</div>

		<div
			class="cx-vui-component cx-vui-component--equalwidth"
			v-if="0 !== optionPresetList.length"
		>
			<div class="cx-vui-component__meta">
				<label class="cx-vui-component__label"><?php _e( 'Delete Preset', 'jet-menu' ); ?></label>
				<div class="cx-vui-component__desc"><?php
					_e( 'Delete existing preset', 'jet-menu' );
				?></div>
			</div>
			<div class="cx-vui-component__control inline-control">
				<cx-vui-select
					name="remove-preset-name"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:prevent-wrap="true"
					:options-list="optionPresetList"
					v-model="removePresetId"
				>
				</cx-vui-select>
				<cx-vui-button
					button-style="accent-border"
					size="mini"
					:loading="removingState"
					@click="removePreset"
					v-if="removePresetId!==''"
				>
					<span slot="label"><?php _e( 'Remove', 'jet-menu' ); ?></span>
				</cx-vui-button>
			</div>
		</div>

	</div>
</cx-vui-popup>

<cx-vui-popup
	v-model="resetCheckPopup"
	@on-ok="resetOptions"
	body-width="400px"
>
	<div slot="title">
		<div class="cx-vui-popup__header-label"><?php _e( 'Options Reset', 'jet-menu' ); ?></div>
	</div>
	<div class="cx-vui-popup__content-inner" slot="content">
		<p><?php _e( 'All menu options will be reseted to defaults. Please export current options to prevent data lost. Are you sure you want to continue?', 'jet-menu' ); ?></p>
	</div>
</cx-vui-popup>

<cx-vui-popup
	class="import-options"
	v-model="importVisible"
	:footer="false"
	body-width="400px"
>
	<div slot="title">
		<div class="cx-vui-popup__header-label"><?php _e( 'Import Options', 'jet-menu' ); ?></div>
	</div>
	<div class="cx-vui-popup__content-inner" slot="content">
		<input
			class="jet-menu-import-file"
			type="file"
			accept="application/json"
			multiple="false"
			ref="import-file-input"
		>
		<cx-vui-button
			button-style="accent-border"
			size="mini"
			:loading="importState"
			@click="importOptions"
		>
			<span slot="label"><?php _e( 'Import', 'jet-menu' ); ?></span>
		</cx-vui-button>
	</div>
</cx-vui-popup>
