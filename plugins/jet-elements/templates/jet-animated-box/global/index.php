<?php
/**
 * Loop item template
 */

$widget_id     = $this->get_id();
$settings      = $this->get_settings();
$title_tag     = $this->_get_html( 'title_html_tag', '%s' );
$title_tag     = jet_elements_tools()->validate_html_tag( $title_tag );
$sub_title_tag = $this->_get_html( 'sub_title_html_tag', '%s' );
$sub_title_tag = jet_elements_tools()->validate_html_tag( $sub_title_tag );

$animation_class = $settings['animation_effect'];

switch ( $settings['switch_event_type'] ) {
	case 'scratch':
		$animation_class = 'jet-box-scratch-effect back-events-inactive';
	break;

	case 'fold':
		$animation_class = 'jet-box-fold-effect';
	break;

	case 'peel':
		$animation_class = 'jet-box-peel-effect';
	break;

	case 'slide-out':
		$slide_direction =  isset( $settings['slide_out_direction'] ) ? $settings['slide_out_direction'] : '';
		$animation_class = 'jet-box-slide-out-effect slide-out-' . $slide_direction;
	break;
}

?><div id="jet-animated-box-<?php echo $widget_id; ?>" class="jet-animated-box <?php echo $animation_class; ?>" <?php echo $this->generate_setting_json(); ?>>
	<div id="jet-animated-box__front-<?php echo $widget_id; ?>" class="jet-animated-box__front">
		<div class="jet-animated-box__inner"><?php

			if ( 'default' === $settings['front_side_content_type'] ) {
				$this->_icon( 'front_side_icon', '<div class="jet-animated-box__icon jet-animated-box__icon--front"><div class="jet-animated-box-icon-inner"><span class="jet-elements-icon">%s</span></div></div>' );
				?><div class="jet-animated-box__content"><?php
					$this->_html( 'front_side_title', '<' . $title_tag . ' class="jet-animated-box__title jet-animated-box__title--front">%s</' . $title_tag . '>' );
					$this->_html( 'front_side_subtitle', '<' . $sub_title_tag . ' class="jet-animated-box__subtitle jet-animated-box__subtitle--front">%s</' . $sub_title_tag . '>' );
					$this->_html( 'front_side_description', '<p class="jet-animated-box__description jet-animated-box__description--front">%s</p>' );
				?></div><?php
			} else {
				echo $this->get_template_content( $settings['front_side_template_id'] );
			}
		?></div>
		<div class="jet-animated-box__overlay"></div><?php

		if( 'toggle' === $settings['switch_event_type'] ) {
			?><div class="jet-animated-box__toggle jet-animated-box__toggle--front"><?php
				$this->_icon( 'front_side_toggle_icon', '<div class="jet-animated-box__toggle-icon"><span class="jet-elements-icon">%s</span></div>' );
			?></div><?php
		}
	?></div>
	<div id="jet-animated-box__back-<?php echo $widget_id; ?>" class="jet-animated-box__back">
		<div class="jet-animated-box__inner"><?php

		if ( 'default' === $settings['back_side_content_type'] ) {
			$this->_icon( 'back_side_icon', '<div class="jet-animated-box__icon jet-animated-box__icon--back"><div class="jet-animated-box-icon-inner"><span class="jet-elements-icon">%s</span></div></div>' );
			?><div class="jet-animated-box__content"><?php
				$this->_html( 'back_side_title', '<' . $title_tag . ' class="jet-animated-box__title jet-animated-box__title--back">%s</' . $title_tag . '>' );
				$this->_html( 'back_side_subtitle', '<' . $sub_title_tag . ' class="jet-animated-box__subtitle jet-animated-box__subtitle--back">%s</' . $sub_title_tag . '>' );
				$this->_html( 'back_side_description', '<p class="jet-animated-box__description jet-animated-box__description--back">%s</p>' );
				$this->_glob_inc_if( 'action-button', array( 'back_side_button_link', 'back_side_button_text' ) );
			?></div><?php
		} else {
			echo $this->get_template_content( $settings['back_side_template_id'] );
		}

		?></div>
		<div class="jet-animated-box__overlay"></div><?php

		if( 'toggle' === $settings['switch_event_type'] ) {
			?><div class="jet-animated-box__toggle jet-animated-box__toggle--back"><?php
				$this->_icon( 'back_side_toggle_icon', '<div class="jet-animated-box__toggle-icon"><span class="jet-elements-icon">%s</span></div>' );
			?></div><?php
		}
	?></div>
</div>
