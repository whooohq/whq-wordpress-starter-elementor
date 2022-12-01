<?php
/**
 * Pagination filter class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Pagination_Filter' ) ) {
	/**
	 * Define Jet_Smart_Filters_Pagination_Filter class
	 */
	class Jet_Smart_Filters_Pagination_Filter {

		/**
		 * Get provider ID
		 */
		public function get_id() {

			return 'pagination';
		}

		/**
		 * Render pagination sample
		 */
		public function render_pagination_sample( $controls ) {

			$pages          = 10;
			$page           = 4;
			$nav            = filter_var( $controls['nav'], FILTER_VALIDATE_BOOLEAN );
			$pages_mid_size = $controls['pages_mid_size'];
			$pages_end_size = $controls['pages_end_size'];
			$pages_show_all = ( 0 === $pages_mid_size ) ? true : false;
			$dots           = true;
			$item_html      = jet_smart_filters()->utils->template_parse( 'for-js/pagination-item.php' );
			$dots_html      = jet_smart_filters()->utils->template_parse( 'for-js/pagination-item-dots.php' );

			echo '<div class="jet-filters-pagination">';
				if ( $nav ) {
					echo '<div class="jet-filters-pagination__item prev-next prev">';
						$value = $controls['prev'];
						eval( '?>' . $item_html . '<?php ' );
					echo '</div>';
				}
				for ( $i = 1; $i <= $pages ; $i++ ) {
					$current = ( $page === $i ) ? ' jet-filters-pagination__current' : '';
					$show_dots =  ( $pages_end_size < $i && $i < $page - $pages_mid_size ) || ( $pages_end_size <= ( $pages - $i ) && $i > $page + $pages_mid_size );

					if ( !$show_dots || $pages_show_all ) {
						$dots = true;
						echo '<div class="jet-filters-pagination__item' . $current . '">';
							$value = $i;
							eval( '?>' . $item_html . '<?php ' );
						echo '</div>';
					} elseif ( $dots ) {
						$dots = false;
						echo '<div class="jet-filters-pagination__item">';
							eval( '?>' . $dots_html . '<?php ' );
						echo '</div>';
					}
				}
				if ( $nav ) {
					echo '<div class="jet-filters-pagination__item prev-next next">';
						$value = $controls['next'];
						eval( '?>' . $item_html . '<?php ' );
					echo '</div>';
				}
			echo '</div>';
		}
	}
}
