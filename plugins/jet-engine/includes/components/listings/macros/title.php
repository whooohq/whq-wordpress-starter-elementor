<?php
namespace Jet_Engine\Macros;

/**
 * Get current object title
 */
class Title extends \Jet_Engine_Base_Macros {

	/**
	 * @inheritDoc
	 */
	public function macros_tag() {
		return 'title';
	}

	/**
	 * @inheritDoc
	 */
	public function macros_name() {
		return esc_html__( 'Title', 'jet-engine' );
	}

	/**
	 * @inheritDoc
	 */
	public function macros_callback( $args = array() ) {

		$object = $this->get_macros_object();

		if ( ! $object ) {
			return '';
		}

		$class  = get_class( $object );
		$result = '';

		switch ( $class ) {
			case 'WP_Post':
				$result = $object->post_title;
				break;

			case 'WP_Term':
				$result = $object->name;
				break;
		}

		return $result;
	}
}