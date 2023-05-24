<?php

if ( empty( $args ) ) {
	return;
}

?>
<div class="jet-filter-items-dropdown">
	<div class="jet-filter-items-dropdown__label" <?php echo jet_smart_filters()->data->get_tabindex_attr(); ?>><?php echo isset( $args['dropdown_placeholder'] ) ? $args['dropdown_placeholder'] : '' ?></div>
	<div class="jet-filter-items-dropdown__body">
		<?php include jet_smart_filters()->get_template( 'filters/' . $this->filter_type . '.php' ); ?>
	</div>
</div>