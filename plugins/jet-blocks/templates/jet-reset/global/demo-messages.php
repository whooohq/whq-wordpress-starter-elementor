<?php
	$settings   = $this->get_settings_for_display();

	if ( 'yes' === $settings['login_link'] ) {
		$login_text = isset( $settings['form_login_button_text'] ) ? wp_kses_post( $settings['form_login_button_text'] ) : '';
		$login_link = '<a class="jet-reset__login-link" href="#">' . $login_text . '</a>';
	} else {
		$login_link = '';
	}
?>

<p class="jet-reset__demo-messages">
	<?php echo esc_html__( 'Demo messages for styling.', 'jet-blocks' );?>
</p>

<div class="jet-reset__success-message">
	<p>
		<?php printf( __( 'Your password has been reset. %s', 'jet-blocks' ), $login_link ); ?>
	</p>
</div>

<p class="jet-reset__error-message">
	<?php echo esc_html__( 'That username is not recognised.', 'jet-blocks' ); ?>
</p>

<?php if ( 'yes' === $settings['use_password_requirements'] ): ?>

<div class="jet-reset__fields-wrapper jet-reset-password-requirements">
	<?php include $this->__get_global_template( 'requirements' ); ?>
</div>

<?php endif; ?>