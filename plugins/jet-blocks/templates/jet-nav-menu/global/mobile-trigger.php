<?php
/**
 * Mobile menu trigger template
 */
?>

<div class="jet-nav__mobile-trigger jet-nav-mobile-trigger-align-<?php echo esc_attr( $trigger_align ); ?>">
	<?php $this->__icon( 'mobile_trigger_icon', '<span class="jet-nav__mobile-trigger-open jet-blocks-icon">%s</span>' ); ?>
	<?php $this->__icon( 'mobile_trigger_close_icon', '<span class="jet-nav__mobile-trigger-close jet-blocks-icon">%s</span>' ); ?>
</div>