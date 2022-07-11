<?php
/**
 * Taxonomy Tiles widget item template.
 */

$settings      = $this->get_settings_for_display();
$title         = jet_woo_builder_tools()->trim_text( $taxonomy->name, $settings['title_length'], 'word', '...' );
$title_tag     = isset( $settings['title_html_tag'] ) ? jet_woo_builder_tools()->sanitize_html_tag( $settings['title_html_tag'] ) : 'h5';
$description   = jet_woo_builder_tools()->trim_text( $taxonomy->description, $settings['desc_length'], 'symbols', '...' );
$before_text   = wp_kses_post( $settings['count_before_text'] );
$after_text    = wp_kses_post( $settings['count_after_text'] );
$count_before  = ! is_rtl() ? $before_text : $after_text;
$count_after   = ! is_rtl() ? $after_text : $before_text;
$thumbnail_key = apply_filters( 'jet-woo-builder/jet-woo-taxonomy-tiles/tax_thumbnail', 'thumbnail_id', $taxonomy );
$target_attr   = 'yes' === $settings['open_new_tab'] ? 'target="_blank"' : '';
?>

<div class="jet-woo-taxonomy-item">
	<div class="jet-woo-taxonomy-item__box" <?php $this->__get_tax_bg( $taxonomy, $thumbnail_key ); ?>>
		<div class="jet-woo-taxonomy-item__box-content">
			<div class="jet-woo-taxonomy-item__box-inner">
				<?php
				if ( '' !== $title ) {
					echo '<' . $title_tag . ' class="jet-woo-taxonomy-item__box-title">' . $title . '</' . $title_tag . '>';
				}

				if ( 'yes' === $settings['show_taxonomy_count'] ) {
					echo sprintf( '<div class="jet-woo-taxonomy-item__box-count">%2$s%1$s%3$s</div>', $taxonomy->count, $count_before, $count_after );
				}

				if ( '' !== $description ) {
					echo sprintf( '<div class="jet-woo-taxonomy-item__box-description">%s</div>', $description );
				}
				?>
			</div>
		</div>
		<a href="<?php echo esc_url( get_category_link( $taxonomy->term_id ) ) ?>" class="jet-woo-taxonomy-item__box-link" <?php echo $target_attr; ?>></a>
	</div>
</div>