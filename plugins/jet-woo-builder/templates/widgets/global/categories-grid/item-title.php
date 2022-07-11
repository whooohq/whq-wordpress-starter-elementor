<?php
/**
 * JetWooBuilder Categories Grid widget loop item title template.
 */

$title         = jet_woo_builder_tools()->trim_text(
	$category->name,
	$this->get_attr( 'title_length' ),
	$this->get_attr( 'title_trim_type' ),
	'...'
);
$title_tag     = ! empty( $this->get_attr( 'title_html_tag' ) ) ? jet_woo_builder_tools()->sanitize_html_tag( $this->get_attr( 'title_html_tag' ) ) : 'h5';
$title_tooltip = '';

if ( -1 !== $this->get_attr( 'title_length' ) && 'yes' === $this->get_attr( 'title_tooltip' ) ) {
	$title_tooltip = 'title="' . $category->name . '"';
}

if ( 'yes' !== $this->get_attr( 'show_title' ) ) {
	return;
}

echo '<' . $title_tag . ' class="jet-woo-category-title" ' .$title_tooltip . '>';
echo '<a href="' . jet_woo_builder_tools()->get_term_permalink( $category->term_id ) . '" class="jet-woo-category-title__link" ' . $target_attr . '>' . $title . '</a>';
echo '</' . $title_tag . '>';
