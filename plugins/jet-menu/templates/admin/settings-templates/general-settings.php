<div
	class="jet-menu-settings-page jet-menu-settings-page__general"
>
	<div class="jet-menu-settings-page__presets-manager cx-vui-component">
		<cx-vui-button
			button-style="accent-border"
			size="mini"
			@click="openPresetManager"
		>
			<span slot="label"><?php _e( 'Preset Manager', 'jet-menu' ); ?></span>
		</cx-vui-button>

		<cx-vui-button
			button-style="accent-border"
			size="mini"
			:url="exportUrl"
			tag-name="a"
		>
			<span slot="label"><?php _e( 'Export Options', 'jet-menu' ); ?></span>
		</cx-vui-button>

		<cx-vui-button
			button-style="accent-border"
			size="mini"
			@click="importVisible=true"
		>
			<span slot="label"><?php _e( 'Import Options', 'jet-menu' ); ?></span>
		</cx-vui-button>

		<cx-vui-button
			button-style="default-border"
			size="mini"
			@click="resetCheckPopup=true"
		>
			<span slot="label"><?php _e( 'Reset Options', 'jet-menu' ); ?></span>
		</cx-vui-button>
	</div>

	<?php include jet_menu()->plugin_path( 'templates/admin/options-page-popups.php' ); ?>

	<cx-vui-switcher
		name="svg-uploads"
		label="<?php _e( 'SVG images upload status', 'jet-menu' ); ?>"
		description="<?php _e( 'Enable or disable SVG images uploading', 'jet-menu' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="enabled"
		return-false="disabled"
		v-model="pageOptions['svg-uploads']['value']">
	</cx-vui-switcher>

	<cx-vui-switcher
		name="use-template-cache"
		label="<?php _e( 'Template Content Cache', 'jet-menu' ); ?>"
		description="<?php _e( 'Do you want to use the cache for the Elementor\'s templates?', 'jet-menu' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="true"
		return-false="false"
		v-model="pageOptions['use-template-cache']['value']">
	</cx-vui-switcher>

    <cx-vui-switcher
        name="plugin-nextgen-edition"
        label="<?php _e( 'Revamp Menu', 'jet-menu' ); ?>"
        description="<?php _e( 'Once this option is enabled you start building from scratch. To get back to the old menu switch this toggle off', 'jet-menu' ); ?>"
        :wrapper-css="[ 'equalwidth' ]"
        return-true="true"
        return-false="false"
        v-model="pageOptions['plugin-nextgen-edition']['value']"
        v-on:input="nextgenEditionTrigger">
    </cx-vui-switcher>

	<?php

	$template = get_template();

	if ( file_exists( jet_menu()->plugin_path( "integration/themes/{$template}" ) ) ) {

		$disable_integration_option = 'jet-menu-disable-integration-' . $template;

		?><cx-vui-switcher
        name="<?php echo $disable_integration_option; ?>"
        label="<?php _e( 'Use current theme integration?', 'jet-menu' ); ?>"
        :wrapper-css="[ 'equalwidth' ]"
        return-true="true"
        return-false="false"
        v-model="pageOptions['<?php echo $disable_integration_option; ?>']['value']">
        </cx-vui-switcher><?php
	}?>

    <cx-vui-switcher
        name="jet-menu-cache-css"
        label="<?php _e( 'Cache menu CSS', 'jet-menu' ); ?>"
        :wrapper-css="[ 'equalwidth' ]"
        return-true="true"
        return-false="false"
        v-model="pageOptions['jet-menu-cache-css']['value']">
    </cx-vui-switcher>
</div>
