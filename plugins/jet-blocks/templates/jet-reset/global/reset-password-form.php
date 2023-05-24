<?php
$settings                = $this->get_settings_for_display();
$allowed_tags            = $this->get_allowed_html_tags();
$form_title              = isset( $settings['form_title'] ) ? wp_kses_post( $settings['form_title'] ) : '';
$new_password_label      = isset( $settings['new_password_label'] ) ? wp_kses_post( $settings['new_password_label'] ) : '';
$re_enter_password_label = isset( $settings['re_enter_password_label'] ) ? wp_kses_post( $settings['re_enter_password_label'] ) : '';
$reset_form_text         = isset( $settings['reset_form_text'] ) ? wp_kses_post( $settings['reset_form_text'] ) : '';
$reset_form_text_output  = wpautop( wp_kses( $reset_form_text, $allowed_tags ) );
$minimum_password_length = ( isset( $settings['minimum_password_length'] ) && 'yes' != $settings['use_password_requirements'] ) ? $settings['minimum_password_length'] : 8;
$reset_form_button_text  = isset( $settings['form_button_text'] ) ? wp_kses_post( $settings['form_button_text'] ) : '';
$redirect_page_url       = esc_url( $this->get_success_redirect_url( $settings ) );
$complete_template       = '';
$btn_justify             = 'justify' === $settings['submit_alignment'] ? 'jet-reset__button-full' : '';
$pw_strong_validation    = isset( $settings['use_password_requirements'] ) && 'yes' === $settings['use_password_requirements'] ? 'pw-validation' : '';

if ( get_permalink( 0 ) === $redirect_page_url ) {
	$complete_template = '?password_reset=true';
	$redirect_page_url = $redirect_page_url . $complete_template;
}
?>

<div class="jet-reset">

	<?php if ( ! empty( $errors ) ) : ?>

		<?php if ( is_array( $errors ) ) : ?>

			<?php foreach ( $errors as $error ) : ?>
				<p class="jet-reset__error-message">
					<?php echo $error; ?>
				</p>
			<?php endforeach; ?>

		<?php endif; ?>

	<?php endif; ?>

	<form id="resetpasswordform" class="jet-reset__form" name="resetpasswordform" method="post">

		<input name="jet-reset-success-redirect" id="jet-reset-success-redirect" class="input" type="hidden" value="<?php echo $redirect_page_url;?>">

		<?php if ( ! empty( $form_title ) ):?>

			<legend class="jet-reset__form-title"><?php echo $form_title; ?></legend>

		<?php endif;?>

		<?php if ( ! empty( $reset_form_text_output ) ):?>

			<div class="jet-reset__form-text">

				<?php printf( $reset_form_text_output, $minimum_password_length )?>

			</div>

		<?php endif;?>

		<div class="jet-reset__fields-wrapper">

			<p class="jet-reset__field">

				<label for="jet_reset_new_user_pass"><?php echo $new_password_label; ?></label>
				<p class="jet-reset__field-wrapper">
					<?php if ( ! empty( $minimum_password_length ) && 'yes' != $settings['use_password_requirements'] ) { ?>
						<input name="jet_reset_new_user_pass" id="jet_reset_new_user_pass" class="input <?php echo $pw_strong_validation; ?>" type="password" pattern=".{<?php echo $minimum_password_length; ?>,}" required>
					<?php } else { ?>
						<input name="jet_reset_new_user_pass" id="jet_reset_new_user_pass" class="input <?php echo $pw_strong_validation; ?>" type="password" required>
					<?php } ?>

					<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="e-font-icon-svg e-far-eye password-visibility__icon password-visibility__icon--show show" viewBox="0 0 576 512">
						<path d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="e-font-icon-svg e-far-eye-slash password-visibility__icon password-visibility__icon--hide" viewBox="0 0 640 512">
						<path d="M634 471L36 3.51A16 16 0 0 0 13.51 6l-10 12.49A16 16 0 0 0 6 41l598 467.49a16 16 0 0 0 22.49-2.49l10-12.49A16 16 0 0 0 634 471zM296.79 146.47l134.79 105.38C429.36 191.91 380.48 144 320 144a112.26 112.26 0 0 0-23.21 2.47zm46.42 219.07L208.42 260.16C210.65 320.09 259.53 368 320 368a113 113 0 0 0 23.21-2.46zM320 112c98.65 0 189.09 55 237.93 144a285.53 285.53 0 0 1-44 60.2l37.74 29.5a333.7 333.7 0 0 0 52.9-75.11 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64c-36.7 0-71.71 7-104.63 18.81l46.41 36.29c18.94-4.3 38.34-7.1 58.22-7.1zm0 288c-98.65 0-189.08-55-237.93-144a285.47 285.47 0 0 1 44.05-60.19l-37.74-29.5a333.6 333.6 0 0 0-52.89 75.1 32.35 32.35 0 0 0 0 29.19C89.72 376.41 197.08 448 320 448c36.7 0 71.71-7.05 104.63-18.81l-46.41-36.28C359.28 397.2 339.89 400 320 400z"></path>
					</svg>
				</p>
			</p>

			<p class="jet-reset__field">
				<label for="jet_reset_new_user_pass_again"><?php echo $re_enter_password_label; ?></label>
				<p class="jet-reset__field-wrapper">
					<?php if ( ! empty( $minimum_password_length ) ) { ?>
						<input name="jet_reset_new_user_pass_again" id="jet_reset_new_user_pass_again" class="input" type="password" pattern=".{<?php echo $minimum_password_length; ?>,}" required>
					<?php } else { ?>
						<input name="jet_reset_new_user_pass_again" id="jet_reset_new_user_pass_again" class="input" type="password" required>
					<?php } ?>
				</p>
			</p>

		</div>

		<?php if ( 'yes' === $settings['use_password_requirements'] ): ?>

			<div class="jet-reset__fields-wrapper jet-reset-password-requirements">
				<?php include $this->__get_global_template( 'requirements' ); ?>
			</div>

		<?php endif; ?>

		<div class="jet-reset__submit">

			<?php wp_nonce_field( 'jet_reset_pass_reset', 'jet_reset_nonce' ); ?>
			<input type="hidden" name="submitted" id="submitted" value="true">
			<input type="hidden" name="jet_reset_action" id="jet_reset_post_action" value="jet_reset_pass_reset">
			<button type="submit" id="reset-pass-submit" name="reset-pass-submit" class="button button-primary jet-reset__button <?php echo $btn_justify; ?>"><?php echo $reset_form_button_text; ?></button>

		</div>

	</form>

</div>