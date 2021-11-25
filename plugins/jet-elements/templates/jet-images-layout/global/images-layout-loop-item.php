<?php
/**
 * Images list item template
 */
$settings  = $this->get_settings_for_display();
$col_class = '';
$tag       = 'a';

if ( 'grid' === $settings['layout_type'] ) {
	$col_class = jet_elements_tools()->col_classes( array(
		'desk' => $this->_get_html( 'columns' ),
		'tab'  => $this->_get_html( 'columns_tablet' ),
		'mob'  => $this->_get_html( 'columns_mobile' ),
	) );
}

$link_instance = 'link-instance-' . $this->item_counter;

$link_type = $this->_loop_item( array( 'item_link_type' ), '%s' );

$this->add_render_attribute( $link_instance, 'class', array(
	'jet-images-layout__link',
	// Ocean Theme lightbox compatibility
	class_exists( 'OCEANWP_Theme_Class' ) ? 'no-lightbox' : '',
) );

if ( 'lightbox' === $link_type ) {
	$this->add_render_attribute( $link_instance, 'href', $this->_loop_item( array( 'item_image', 'url' ), '%s' ) );
	$this->add_render_attribute( $link_instance, 'data-elementor-open-lightbox', 'yes' );
	$this->add_render_attribute( $link_instance, 'data-elementor-lightbox-slideshow', $this->get_id()  );
} else if ( 'no_link' === $link_type ) {
	$tag = 'div';
} else {
	$target = $this->_loop_item( array( 'item_target' ), '%s' );
	$target = ! empty( $target ) ? $target : '_self';
	$rel    = $this->_loop_item( array( 'item_rel' ), '%s' );
	$rel    = ! empty( $rel ) ? $rel : '';

	$this->add_render_attribute( $link_instance, 'href', $this->_loop_item( array( 'item_url' ), '%s' ) );
	$this->add_render_attribute( $link_instance, 'target', $target );
	$this->add_render_attribute( $link_instance, 'rel', $rel );
}

$this->item_counter++;

?>
<div class="jet-images-layout__item <?php echo $col_class ?>">
	<div class="jet-images-layout__inner">
		<div class="jet-images-layout__image-loader"><span></span></div>
		<<?php echo $tag?> <?php echo $this->get_render_attribute_string( $link_instance ); ?>>
			<div class="jet-images-layout__image">
				<?php echo $this->_loop_image_item(); ?>
			</div>
			<div class="jet-images-layout__content"><?php
				echo $this->_render_icon( 'item_icon', '<div class="jet-images-layout__icon"><div class="jet-images-layout-icon-inner">%s</div></div>', '', false );

				$title_tag = $this->_get_html( 'title_html_tag', '%s' );
				$title_tag = jet_elements_tools()->validate_html_tag( $title_tag );

				echo $this->_loop_item( array( 'item_title' ), '<' . $title_tag . ' class="jet-images-layout__title">%s</' . $title_tag . '>' );
				echo $this->_loop_item( array( 'item_desc' ), '<div class="jet-images-layout__desc">%s</div>' );
			?></div>
		</<?php echo $tag?>>
	</div>
</div>
