<?php
/**
 * Password requirements messages
 */
$requirements = $settings['password_requirements'];
?>

<?php if ( count( $requirements ) > 0 ) : ?>
<h3 class="jet-password-requirements__title"><?php echo esc_html__( 'Password Requirements:', 'jet-blocks' );?></h3>
<ul class="jet-password-requirements">
    <?php if ( in_array( "length", $requirements ) ) : ?>
        <li class="jet-password-requirements__item jet-password-requirements-length"></i><?php echo esc_html__( 'At least 8 characters', 'jet-blocks' );?></li>
    <?php endif; ?>
    <?php if ( in_array( "lowercase", $requirements ) ) : ?>
        <li class="jet-password-requirements__item jet-password-requirements-lowercase"><?php echo esc_html__( 'At least 1 lowercase letter', 'jet-blocks' );?></li>
    <?php endif; ?>
    <?php if ( in_array( "uppercase", $requirements ) ) : ?>
        <li class="jet-password-requirements__item jet-password-requirements-uppercase"><?php echo esc_html__( 'At least 1 uppercase letter', 'jet-blocks' );?></li>
    <?php endif; ?>
    <?php if ( in_array( "number", $requirements ) ) : ?>
        <li class="jet-password-requirements__item jet-password-requirements-number"><?php echo esc_html__( 'At least 1 numerical number', 'jet-blocks' );?></li>
    <?php endif; ?>
    <?php if ( in_array( "special", $requirements ) ) : ?>
        <li class="jet-password-requirements__item jet-password-requirements-special"><?php echo esc_html__( 'At least 1 special character', 'jet-blocks' );?></li>
    <?php endif; ?>
</ul>
<?php endif; ?>