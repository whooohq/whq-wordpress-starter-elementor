<?php
/**
 * Filter items search template
 */

if ( empty( $args ) || ! $args['search_enabled'] ) {
	return;
}

$search_placeholder = $args['search_placeholder'];

?>
<div class="jet-filter-items-search">
	<input
		class="jet-filter-items-search__input"
		type="search"
		autocomplete="off"
		<?php echo $search_placeholder ? 'placeholder="' . $search_placeholder .'"' : '' ?>
	>
	<div class="jet-filter-items-search__clear"></div>
</div>