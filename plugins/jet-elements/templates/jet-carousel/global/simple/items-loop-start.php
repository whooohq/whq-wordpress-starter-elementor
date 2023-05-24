<?php
/**
 * Loop start template
 */
$options = $this->get_advanced_carousel_options();
$dir = is_rtl() ? 'rtl' : 'ltr';

$title_tag  = $this->get_settings_for_display( 'title_html_tag' );
$title_tag  = jet_elements_tools()->validate_html_tag( $title_tag );
$link_title = $this->get_settings_for_display( 'link_title' );

?><div class="jet-carousel" data-slider_options="<?php echo htmlspecialchars( json_encode( $options ) ); ?>" dir="<?php echo $dir; ?>">
	<div class="elementor-slick-slider">
