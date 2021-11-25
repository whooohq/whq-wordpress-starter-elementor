<?php
/**
 * Overlay template
 */

$thumb_url  = $this->get_thumbnail_url();
$video_url  = $this->get_video_url();

if ( empty( $thumb_url ) && ! filter_var( $settings['show_play_button'], FILTER_VALIDATE_BOOLEAN ) ) {
	return;
}

$this->add_render_attribute( 'overlay', 'class', 'jet-video__overlay' );

if ( ! empty( $thumb_url ) ) {
	$this->add_render_attribute( 'overlay', 'class', 'jet-video__overlay--custom-bg' );
	$this->add_render_attribute( 'overlay', 'style', sprintf( 'background-image: url(%s);', $thumb_url ) );
}

if ( $settings['lightbox'] ) {

	if ( 'self_hosted' === $settings['video_type'] ) {
		$lightbox_url = $video_url;
	} else {
		$lightbox_url = $this->get_lightbox_url();
	}

	$aspect_ratio = ! empty( $settings['aspect_ratio'] ) ? $settings['aspect_ratio'] : '16-9';
	$video_type   = ( 'self_hosted' === $settings['video_type'] ) ? 'hosted' : $settings['video_type'];

	$lightbox_options = array(
		'type'         => 'video',
		'videoType'    => $video_type,
		'url'          => $lightbox_url,
		'modalOptions' => array(
			'id'                => 'jet-video-lightbox-' . $this->get_id(),
			'entranceAnimation' => isset( $settings['lightbox_content_animation'] ) ? $settings['lightbox_content_animation'] : '',
			'videoAspectRatio'  => str_replace( '-', '', $aspect_ratio ),
		),
	);

	if ( 'self_hosted' === $settings['video_type'] ) {
		$lightbox_options['videoParams'] = $this->get_self_hosted_params();
	}

	$this->add_render_attribute( 'overlay', array(
		'data-elementor-open-lightbox' => 'yes',
		'data-elementor-lightbox'      => json_encode( $lightbox_options ),
	) );
}
?>

<div <?php $this->print_render_attribute_string( 'overlay' ); ?>><?php
	if ( filter_var( $settings['show_play_button'], FILTER_VALIDATE_BOOLEAN ) ) {
		include $this->_get_global_template( 'play-button' );
	}
?></div>
