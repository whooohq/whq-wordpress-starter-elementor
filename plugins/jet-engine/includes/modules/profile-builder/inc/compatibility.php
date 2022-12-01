<?php
namespace Jet_Engine\Modules\Profile_Builder;

class Compatibility {

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		// Rank Math SEO compatibility hooks.
		if ( defined( 'RANK_MATH_VERSION' ) ) {

			add_filter( 'rank_math/frontend/title',     array( Module::instance()->frontend, 'set_document_title_on_single_user_page' ) );
			add_filter( 'rank_math/frontend/canonical', array( Module::instance()->frontend, 'modify_canonical_url' ) );
		}

		// Yoast SEO compatibility hooks.
		if ( defined( 'WPSEO_VERSION' ) ) {

			add_filter( 'wpseo_opengraph_title', array( Module::instance()->frontend, 'set_document_title_on_single_user_page' ) );
			add_filter( 'wpseo_twitter_title',   array( Module::instance()->frontend, 'set_document_title_on_single_user_page' ) );

			add_filter( 'wpseo_canonical',     array( Module::instance()->frontend, 'modify_canonical_url' ) );
			add_filter( 'wpseo_opengraph_url', array( Module::instance()->frontend, 'modify_canonical_url' ) );
		}

		// SEOPress compatibility hooks.
		if ( defined( 'SEOPRESS_VERSION' ) ) {

			add_filter( 'seopress_titles_title',     array( Module::instance()->frontend, 'set_document_title_on_single_user_page' ) );
			add_filter( 'seopress_titles_canonical', array( $this, 'modify_seopress_canonical' ) );
		}
	}

	public function modify_seopress_canonical( $canonical ) {

		if ( ! Module::instance()->query->is_single_user_page() ) {
			return $canonical;
		}

		return sprintf( '<link rel="canonical" href="%s" />', htmlspecialchars( urldecode( wp_get_canonical_url() ) ) );
	}

}
