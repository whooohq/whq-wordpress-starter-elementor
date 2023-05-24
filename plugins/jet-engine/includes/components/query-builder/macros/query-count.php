<?php
namespace Jet_Engine\Query_Builder\Macros;

use Jet_Engine\Query_Builder\Manager;

class Query_Count_Macro extends \Jet_Engine_Base_Macros {

	/**
	 * @inheritDoc
	 */
	public function macros_tag() {
		return 'query_count';
	}

	/**
	 * @inheritDoc
	 */
	public function macros_name() {
		return esc_html__( 'Query Results Count', 'jet-engine' );
	}

	/**
	 * @inheritDoc
	 */
	public function macros_args() {
		return array(
			'query_id' => array(
				'label'   => __( 'Query', 'jet-engine' ),
				'type'    => 'select',
				'options' => Manager::instance()->get_queries_for_options(),
			),
			'count_type' => array(
				'label'   => esc_html__( 'Returned Count', 'jet-engine' ),
				'type'    => 'select',
				'default' => 'total',
				'options' => array(
					'total'   => esc_html__( 'Total query results count', 'jet-engine' ),
					'visible' => esc_html__( 'Currently visible query results count (per page)', 'jet-engine' ),
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function macros_callback( $args = array() ) {
		$query_id   = ! empty( $args['query_id'] ) ? $args['query_id'] : false;
		$count_type = ! empty( $args['count_type'] ) ? $args['count_type'] : false;;

		return Manager::instance()->get_query_count_html( $query_id, $count_type );
	}
}