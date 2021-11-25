<?php
/**
 * Testimonials item template
 */
$settings = $this->get_settings();
$stars = $this->render_stars();

?><div class="jet-testimonials__item">
	<div class="jet-testimonials__item-inner">
		<div class="jet-testimonials__content"><?php
			echo $this->_get_testimonials_image();
			echo $this->_render_icon( 'item_icon', '<div class="jet-testimonials__icon"><div class="jet-testimonials__icon-inner">%s</div></div>', '', false );
			echo $this->_loop_item( array( 'item_title' ), '<h5 class="jet-testimonials__title">%s</h5>' );
			echo $this->_loop_item( array( 'item_comment' ), '<div class="jet-testimonials__comment">%s</div>' );
			echo $this->_get_testimonials_name();
			echo $this->_loop_item( array( 'item_position' ), '<div class="jet-testimonials__position"><span>%s</span></div>' );
			echo $this->_loop_item( array( 'item_date' ), '<div class="jet-testimonials__date"><span>%s</span></div>' );
			echo $this->_loop_item( array( 'item_rating' ), '<div class="jet-testimonials__rating" data-rating="%s">' . $stars . '</div>' );
		?></div>
	</div>
</div>

