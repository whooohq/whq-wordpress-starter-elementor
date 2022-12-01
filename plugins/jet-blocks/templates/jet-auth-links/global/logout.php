<?php
/**
 * Login Link template
 */
if ( ! $settings['show_logout_link'] ) {
	return;
}

if ( ! is_user_logged_in() && ! jet_blocks_integration()->in_elementor() ) {
	return;
}

$prefix           = $this->__get_html( 'logout_prefix', '<div class="jet-auth-links__prefix">%s</div>' );
$current_user     = wp_get_current_user();
$username_format  = ! empty( $settings['display_user_name'] ) ? $settings['display_user_name'] : 'default';

switch( $username_format ) {
	case 'username':
		$display_username = $current_user->user_login;
		break;
	case 'firstname':
		$display_username = $current_user->user_firstname;
		break;
	case 'lastname':
		$display_username = $current_user->user_lastname;
		break;
	case 'nickname':
		$display_username = get_user_meta( $current_user->ID, 'nickname', true );
		break;
	case 'firstlast':
		$display_username = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		break;
	case 'lastfirst':
		$display_username = $current_user->user_lastname . ' ' . $current_user->user_firstname;
		break;

	default:
		$display_username = $current_user->display_name;
}

?>
<div class="jet-auth-links__section jet-auth-links__logout">
	<?php printf( $prefix, $display_username ); ?>
	<a class="jet-auth-links__item" href="<?php echo $this->__logout_url(); ?>"><?php
		$this->__icon( 'logout_link_icon', '<span class="jet-auth-links__item-icon jet-blocks-icon">%s</span>' );
		$this->__html( 'logout_link_text', '<span class="jet-auth-links__item-text">%s</span>' );
	?></a>
</div>