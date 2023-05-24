<?php
/**
 * Loop start template
 */
$options   = $this->get_advanced_carousel_options();
$title_tag = $this->get_settings_for_display( 'title_html_tag' );
$title_tag = jet_elements_tools()->validate_html_tag( $title_tag );
$dir       = is_rtl() ? 'rtl' : 'ltr';

?><div class="jet-carousel" data-slider_options="<?php echo htmlspecialchars( json_encode( $options ) ); ?>" dir="<?php echo $dir; ?>">
	<div class="elementor-slick-slider">
