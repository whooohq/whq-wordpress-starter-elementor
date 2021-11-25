<?php
/**
 * Ajax Handlers class
 */

namespace Jet_Elementor_Extension;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Ajax_Handlers.
 *
 * @since 1.0.0
 */
class Ajax_Handlers {

	/**
	 * Ajax_Handlers constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_jet_query_control_options', array( $this, 'get_query_control_options' ) );
		}
	}

	/**
	 * Get Query control options list.
	 */
	public function get_query_control_options() {
		$data = $_REQUEST;

		if ( ! isset( $data['query_type'] ) ) {
			wp_send_json_error();
			return;
		}

		$results = array();

		switch ( $data['query_type'] ) {
			case 'post':

				$default_query_args = array(
					'post_type'           => 'any',
					'post_status'         => 'publish',
					'posts_per_page'      => - 1,
					'suppress_filters'    => false,
					'ignore_sticky_posts' => true,
					'orderby'             => 'title',
					'order'               => 'ASC',
				);

				$query_args = ! empty( $data['query'] ) ? $data['query'] : array();
				$query_args = wp_parse_args( $query_args, $default_query_args );

				if ( ! empty( $data['q'] ) ) {
					$query_args['s_title'] = $data['q'];
				}

				if ( ! empty( $data['ids'] ) ) {
					$query_args['post__in'] = $data['ids'];
				}

				add_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10, 2 );

				$posts = get_posts( $query_args );

				remove_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10 );

				foreach ( $posts as $post ) {
					$results[] = array(
						'id'   => $post->ID,
						'text' => $post->post_title,
					);
				}

				break;

			case 'elementor_templates':
				$document_types = \Elementor\Plugin::instance()->documents->get_document_types( array(
					'show_in_library' => true,
				) );

				$default_query_args = array(
					'post_type'        => \Elementor\TemplateLibrary\Source_Local::CPT,
					'post_status'      => 'publish',
					'posts_per_page'   => - 1,
					'suppress_filters' => false,
					'orderby'          => 'title',
					'order'            => 'ASC',
					'meta_query'       => array(
						array(
							'key'     => \Elementor\Core\Base\Document::TYPE_META_KEY,
							'value'   => array_keys( $document_types ),
							'compare' => 'IN',
						),
					),
				);

				$query_args = ! empty( $data['query'] ) ? $data['query'] : array();
				$query_args = wp_parse_args( $query_args, $default_query_args );

				if ( ! empty( $data['q'] ) ) {
					$query_args['s_title'] = $data['q'];
				}

				if ( ! empty( $data['ids'] ) ) {
					$query_args['post__in'] = $data['ids'];
				}

				add_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10, 2 );

				$posts = get_posts( $query_args );

				remove_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10 );

				foreach ( $posts as $post ) {
					$results[] = array(
						'id'   => $post->ID,
						'text' => sprintf( '%1$s (%2$s)', $post->post_title,  \Elementor\TemplateLibrary\Source_Local::get_template_type( $post->ID ) ),
					);
				}

				break;

			case 'tax':

				$default_terms_args = array(
					'hide_empty' => false,
				);

				$terms_args = ! empty( $data['query'] ) ? $data['query'] : array();
				$terms_args = wp_parse_args( $terms_args, $default_terms_args );

				if ( ! empty( $data['q'] ) ) {
					$terms_args['search'] = $data['q'];
				}

				if ( empty( $terms_args['taxonomy'] ) ) {
					$terms_args['taxonomy'] = get_taxonomies( array( 'show_in_nav_menus' => true ), 'names' );
				}

				if ( ! empty( $data['ids'] ) ) {
					$terms_args['include'] = $data['ids'];
				}

				$terms = get_terms( $terms_args );

				global $wp_taxonomies;

				foreach ( $terms as $term ) {
					$results[] = array(
						'id'   => $term->term_id,
						'text' => sprintf( '%1$s: %2$s', $wp_taxonomies[ $term->taxonomy ]->label, $term->name ),
					);
				}

				break;
		}

		$data = array(
			'results' => $results,
		);

		wp_send_json_success( $data );
	}

	/**
	 * Force query to look in post title while searching.
	 *
	 * @param  string $where
	 * @param  object $query
	 * @return string
	 */
	public function force_search_by_title( $where, $query ) {

		$args = $query->query;

		if ( ! isset( $args['s_title'] ) ) {
			return $where;
		}

		global $wpdb;

		$search = esc_sql( $wpdb->esc_like( $args['s_title'] ) );
		$where .= " AND {$wpdb->posts}.post_title LIKE '%$search%'";

		return $where;
	}
}
