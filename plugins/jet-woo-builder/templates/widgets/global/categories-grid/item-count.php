<?php
/**
 * JetWooBuilder Categories Grid widget loop item count template.
 */

$count       = $category->count;
$before_text = wp_kses_post( $this->get_attr( 'count_before_text' ) );
$after_text  = wp_kses_post( $this->get_attr( 'count_after_text' ) );
$before      = ! is_rtl() ? $before_text : $after_text;
$after       = ! is_rtl() ? $after_text : $before_text;

if ( 'yes' !== $this->get_attr( 'show_count' ) ) {
	return;
}

printf( '<span class="jet-woo-category-count">%2$s%1$s%3$s</span>', $count, $before, $after );
