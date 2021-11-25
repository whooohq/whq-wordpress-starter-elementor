<?php
/**
 * Loop end template
 */
?></div><?php
	if ( filter_var( $this->get_settings_for_display( 'arrows' ), FILTER_VALIDATE_BOOLEAN ) ) {
		echo sprintf( '<div class="jet-carousel__prev-arrow-%s jet-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'prev_arrow', '%s', '', false ) );
		echo sprintf( '<div class="jet-carousel__next-arrow-%s jet-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'next_arrow', '%s', '', false ) );
	}
	if ( filter_var( $this->get_settings_for_display( 'fraction_navigation' ), FILTER_VALIDATE_BOOLEAN ) ) {
		echo '<div class="jet-carousel__fraction-navigation"></div>';
	}
?></div>
