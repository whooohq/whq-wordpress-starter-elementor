<?php
/**
 * Video main template
 */
$settings  = $this->get_settings_for_display();
$video_url = $this->get_video_url();

if ( empty( $video_url ) ) {
	return;
}

$video_html = $this->get_video_html();

if ( empty( $video_html ) ) {
	echo $video_url;

	return;
}

$data_settings = array(
	'lightbox' => filter_var( $settings['lightbox'], FILTER_VALIDATE_BOOLEAN ),
	'autoplay' => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
);

$this->add_render_attribute( 'wrapper', 'class', 'jet-video' );
$this->add_render_attribute( 'wrapper', 'data-settings', esc_attr( json_encode( $data_settings ) ) );

if ( jet_elements_tools()->is_fa5_migration() ) {
	$this->add_render_attribute( 'wrapper', 'class', 'jet-video--fa5-compat' );
}

if ( ! empty( $settings['aspect_ratio'] ) ) {
	$this->add_render_attribute( 'wrapper', 'class', 'jet-video-aspect-ratio' );
	$this->add_render_attribute( 'wrapper', 'class', 'jet-video-aspect-ratio--' . esc_attr( $settings['aspect_ratio'] ) );
}

if ( $settings['lightbox'] ) {
	$this->add_render_attribute( 'wrapper', 'class', 'jet-video--lightbox' );

	if ( 'self_hosted' === $settings['video_type'] ) {
		$this->add_render_attribute( 'wrapper', 'class', 'jet-video-aspect-ratio' );
		$this->add_render_attribute( 'wrapper', 'class', 'jet-video-aspect-ratio--16-9' );
	}
}
?>

<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>><?php
	if ( ! $settings['lightbox'] ) {
		echo $video_html;
	}

	include $this->_get_global_template( 'overlay' );
?></div>
