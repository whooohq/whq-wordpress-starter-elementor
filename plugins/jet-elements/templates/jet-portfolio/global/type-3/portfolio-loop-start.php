<?php
/**
 * Features list start template
 */
$cover_icon = $this->_get_icon( 'cover_icon', '<span class="jet-elements-icon">%s</span>' );
?>
<div class="jet-portfolio__list"><?php
	if ( 'justify' === $this->get_settings_for_display( 'layout_type' ) ) {?>
		<div class="grid-sizer"></div><?php } ?>
