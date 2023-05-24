<?php
	$settings                   = $this->get_settings_for_display();
	$allowed_tags               = $this->get_allowed_html_tags();
	$form_title                 = isset ( $settings['form_title'] ) ? wp_kses_post ( $settings['form_title'] ) : '';
	$email_username_field_label = isset ( $settings['email_username_field_label'] ) ? wp_kses_post( $settings['email_username_field_label'] ) : '';
	$lost_form_text             = isset ( $settings['lost_form_text'] ) ? wp_kses_post( $settings['lost_form_text'] ) : '';
	$lost_form_text_output      = wpautop( wp_kses( $lost_form_text, $allowed_tags ) );
	$minimum_password_length    = isset ( $settings['minimum_password_length'] ) ? $settings['minimum_password_length'] : '';
	$form_button_text           = isset ( $settings['form_button_text'] ) ? wp_kses_post( $settings['form_button_text'] ) : '';
	$btn_justify                = '';

	if ( isset( $settings['submit_alignment'] ) ) {
		$btn_justify = 'justify' === $settings['submit_alignment'] ? 'jet-reset__button-full' : '';
	}
?>

<div class="jet-reset">

	<?php if ( ! empty( $errors ) ) : ?>

		<?php if ( is_array( $errors ) ) : ?>

			<?php foreach ( $errors as $error ) : ?>
				<p class="jet-reset__error-message">
					<span><?php echo $error; ?></span>
				</p>
			<?php endforeach; ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( $email_confirmed ) : ?>

		<p class="jet-reset__success-message">
			<?php echo esc_html__( 'An email has been sent. Please check your inbox.' , 'jet-blocks' ); ?>
		</p>

	<?php endif; ?>

	<form id="lostpasswordform" class="jet-reset__form" name="lostpasswordform" method="post">

		<?php if ( ! empty( $form_title ) ):?>

			<legend class="jet-reset__form-title"><?php echo $form_title; ?></legend>

		<?php endif;?>

		<?php if ( ! empty( $lost_form_text_output ) ):?>

			<div class="jet-reset__form-text">
				<?php echo $lost_form_text_output;?>
			</div>

		<?php endif;?>

		<p class="jet_reset__user-info">

			<label for="jet_reset_user_info"><?php echo $email_username_field_label; ?></label>
			<input class="input" type="text" name="jet_reset_user_info" id="jet_reset_user_info">

		</p>

		<div class="jet-reset__submit">

			<?php wp_nonce_field( 'jet_reset_lost_pass', 'jet_reset_nonce' ); ?>
			<input type="hidden" name="submitted" id="submitted" value="true">
			<input type="hidden" name="jet_reset_action" id="jet_reset_post_action" value="jet_reset_lost_pass">
			<button type="submit" id="jet-reset-pass-submit" name="jet-reset-pass-submit" class="button button-primary jet-reset__button <?php echo $btn_justify; ?>"><?php echo $form_button_text; ?></button>

		</div>

	</form>

	<?php
		if ( jet_blocks()->elementor()->editor->is_edit_mode() ){
			include $this->__get_global_template( 'demo-messages' );
		}
	?>

</div>