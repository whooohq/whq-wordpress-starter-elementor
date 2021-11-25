<?php
/**
 * Price list item template
 */

if ( in_array( $this->_processed_index, $this->_hidden_price_items ) ) {
	return;
}

$item_title_attr = $this->get_item_inline_editing_attributes( 'item_title', 'price_list', $this->_processed_item_index, 'price-list__item-title' );
$item_price_attr = $this->get_item_inline_editing_attributes( 'item_price', 'price_list', $this->_processed_item_index, 'price-list__item-price' );
$item_desc_attr = $this->get_item_inline_editing_attributes( 'item_text', 'price_list', $this->_processed_item_index, 'price-list__item-desc' );

$this->_processed_item_index += 1;
?>
<li class="price-list__item"><?php
	echo $this->_open_price_item_link( 'item_url' );
	echo '<div class="price-list__item-inner">';
	echo $this->_get_price_list_image();
	echo '<div class="price-list__item-content">';
		echo '<div class="price-list__item-title__wrapper">';
			echo $this->_loop_item( array( 'item_title' ), '<h5 ' . $item_title_attr . '>%s</h5>' );
			echo '<div class="price-list__item-separator"></div>';
			echo $this->_loop_item( array( 'item_price' ), '<div ' . $item_price_attr . '>%s</div>' );
		echo '</div>';
		echo $this->_loop_item( array( 'item_text' ), '<div ' . $item_desc_attr . '>%s</div>' );
	echo '</div>';
	echo '</div>';
	echo $this->_close_price_item_link( 'item_url' );
?></li>