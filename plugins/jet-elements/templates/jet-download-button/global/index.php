<?php
	$settings = $this->get_settings_for_display();
	$position = isset( $settings['download_icon_position'] ) ? $settings['download_icon_position'] : 'left';
	$rel      = $this->_get_html( 'download_link_rel', ' rel="%s"' );

?><a class="elementor-button elementor-size-md jet-download jet-download-icon-position-<?php echo $position; ?>" href="<?php echo jet_elements_download_handler()->get_download_link( $settings['download_file'] ); ?>" <?php echo $rel; ?>><?php

	$icon_format = '<span class="jet-download__icon jet-download-icon-' . $position . ' jet-elements-icon">%s</span>';

	$this->_icon( 'download_icon', $icon_format );

	$label    = $this->_get_html( 'download_label' );
	$sublabel = $this->_get_html( 'download_sub_label' );

	if ( $label || $sublabel ) {

		echo '<span class="jet-download__text">';

		printf(
			'<span class="jet-download__label">%s</span>',
			$this->_format_label( $label, $settings['download_file'] )
		);

		printf(
			'<small class="jet-download__sub-label">%s</small>',
			$this->_format_label( $sublabel, $settings['download_file'] )
		);

		echo '</span>';
	}

?></a>
