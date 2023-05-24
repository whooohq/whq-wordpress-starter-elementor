<div
	class="jet-blocks-settings-page jet-blocks-settings-page__integrations"
>
	<cx-vui-switcher
		name="captcha-enable"
		label="<?php _e( 'Enable reCAPTCHA v3', 'jet-blocks' ); ?>"
		description="<?php _e( 'Use reCAPTCHA v3 form verification', 'jet-blocks' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:return-true="'true'"
		:return-false="'false'"
		v-model="pageOptions.captcha.value.enable"
	>
	</cx-vui-switcher>

	<cx-vui-component-wrapper
		:wrapper-css="[ 'fullwidth-control' ]"
		:conditions="[
			{
				input: pageOptions.captcha.value.enable,
				compare: 'equal',
				value: 'true',
			}
		]"
	>
		<cx-vui-input
			name="captcha-site-key"
			label="<?php _e( 'Site Key:', 'jet-blocks' ); ?>"
			description="<?php _e( 'Register reCAPTCHA v3 keys here.', 'jet-blocks' ); ?>"
			description="<?php
				echo sprintf( esc_html__( 'Register reCAPTCHA v3 keys %1$s.', 'jet-blocks' ),
					htmlspecialchars( '<a href="https://www.google.com/recaptcha/admin/create" target="_blank">here</a>', ENT_QUOTES )
				);
			?>"
			:wrapper-css="[ 'equalwidth' ]"
			size="fullwidth"
			v-model="pageOptions.captcha.value.site_key"
		>
		</cx-vui-input>

		<cx-vui-input
			name="captcha-secret-key"
			label="<?php _e( 'Secret Key:', 'jet-blocks' ); ?>"
			description="<?php
				echo sprintf( esc_html__( 'Register reCAPTCHA v3 keys %1$s.', 'jet-blocks' ),
					htmlspecialchars( '<a href="https://www.google.com/recaptcha/admin/create" target="_blank">here</a>', ENT_QUOTES )
				);
			?>"
			:wrapper-css="[ 'equalwidth' ]"
			size="fullwidth"
			v-model="pageOptions.captcha.value.secret_key"
		>
		</cx-vui-input>

	</cx-vui-component-wrapper>
</div>
