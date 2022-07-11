<?php
/**
 * JetWooBuilder Categories Grid widget loop item thumbnail template.
 */

$size = $this->get_attr( 'thumb_size' );
$thumbnail = jet_woo_builder_template_functions()->get_category_thumbnail( $category->term_id, $this->get_attr( 'thumb_size' ) );

if ( null === $thumbnail ) {
	return;
}
?>

<div class="jet-woo-category-thumbnail">
	<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" <?php echo $target_attr; ?> rel="bookmark">
		<?php echo $thumbnail; ?>
	</a>
	<div class="jet-woo-category-img-overlay"></div>
	<div class="jet-woo-category-img-overlay__hover"></div>
</div>