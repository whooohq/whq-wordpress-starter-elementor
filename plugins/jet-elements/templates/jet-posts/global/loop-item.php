<?php
/**
 * Posts loop start template
 */
?><div class="jet-posts__item">
	<div class="jet-posts__inner-box"<?php $this->add_box_bg(); ?>><?php

		include $this->get_template( 'item-thumb' );

		$this->render_post_terms();

		echo '<div class="jet-posts__inner-content">';

			include $this->get_template( 'item-title' );
			include $this->get_template( 'item-meta' );
			include $this->get_template( 'item-content' );
			include $this->get_template( 'item-more' );

		echo '</div>';

	?></div>
</div>
