<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Piotnetforms_Preview_Submissions extends Base_Widget_Piotnetforms {

	public $is_pro = true;

	public function get_type() {
		return 'preview-submissions';
	}

	public function get_class_name() {
		return 'Piotnetforms_Preview_Submissions';
	}

	public function get_title() {
		return 'Preview Submissions';
	}

	public function get_icon() {
		return [
			'type' => 'image',
			'value' => plugin_dir_url( __FILE__ ) . '../../assets/icons/i-preview-submissions.svg',
		];
	}

	public function get_categories() {
		return [ 'piotnetforms' ];
	}

	public function get_keywords() {
		return [ 'button' ];
	}

	public function register_controls() {
	}

	public function render() {
		?>
			<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this widget, Go Pro Now</a></p>
		<?php
	}

	public function live_preview() {
	}

}
