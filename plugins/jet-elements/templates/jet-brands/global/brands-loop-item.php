<?php
/**
 * Features list item template
 */
?>
<div class="brands-list__item"><?php
	echo $this->_open_brand_link( 'item_url' );
	echo $this->_get_brand_image( 'item_image' );
	echo $this->_loop_item( array( 'item_name' ), '<h5 class="brands-list__item-name">%s</h5>' );
	echo $this->_loop_item( array( 'item_desc' ), '<div class="brands-list__item-desc">%s</div>' );
	echo $this->_close_brand_link( 'item_url' );
?></div>