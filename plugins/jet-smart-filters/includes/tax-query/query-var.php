<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * JetSmartFilters apply tax auery query var
 */
class Jet_Smart_Filters_Tax_Query_Var {

	public function __construct() {
		add_filter( 'jet-smart-filters/filter-instance/args', array( $this, 'replace_var' ) );
	}

	public function replace_var( $filter_args ) {

		if ( ! empty( $filter_args['query_var'] ) && false !== strpos( $filter_args['query_var'], '_tax_query::' ) ) {
			$filter_args['query_type'] = 'tax_query';
			$filter_args['query_var']  = str_replace( '_tax_query::', '', $filter_args['query_var'] );
		}

		return $filter_args;

	}

}
