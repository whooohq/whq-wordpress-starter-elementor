<?php
/**
 * Register and hadle jet-engine related shortcodes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Jet_Engine_Shortcodes {

	public function __construct() {
		add_shortcode( 'jet_engine', array( $this, 'do_shortcode' ) );
	}

	/**
	 * Handle shortcode
	 *
	 * @param  array  $atts [description]
	 * @return [type]       [description]
	 */
	public function do_shortcode( $atts = array() ) {

		$atts = shortcode_atts( apply_filters( 'jet-engine/shortcodes/default-atts' ,array(
			'component' => 'meta_field',
			'field'     => false,
			'page'      => false,
			'post_id'   => false,
		) ), $atts, 'jet_engine' );

		$result = '';

		switch ( $atts['component'] ) {

			case 'option':
				if ( ! empty( $atts['page'] ) && ! empty( $atts['field'] ) ) {
					$result = jet_engine()->listings->data->get_option( $atts['page'] . '::' . $atts['field'] );
				}
				break;

			case 'meta_field':
				if ( ! empty( $atts['field'] ) ) {
					$post_id = ! empty( $atts['post_id'] ) ? absint( $atts['post_id'] ) : get_the_ID();
					$result = get_post_meta( $post_id, $atts['field'], true );
				}

				break;

			default:
				$result = apply_filters( 'jet-engine/shortcodes/' . $atts['component'] . '/result', $result, $atts );
		}

		if ( ! empty( $result ) && is_array( $result ) ) {
			$result = implode( ', ', $result );
		}

		return $result;

	}

}