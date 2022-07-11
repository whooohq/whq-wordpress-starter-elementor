<?php
/**
 * JetWooBuilder Categories Grid widget loop item description template.
 */

$after_text  = wp_kses_post( $this->get_attr( 'desc_after_text' ) );
$description = jet_woo_builder_tools()->trim_text( $category->description, $this->get_attr( 'desc_length' ), 'word', $after_text );

if ( empty( $description ) ) {
	return;
}
?>

<div class="jet-woo-category-excerpt"><?php echo $description; ?></div>