<?php

namespace Essential_Addons_Elementor\Pro\Traits;

// use \Essential_Addons_Elementor\Classes\Helper;

trait Helper
{
    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Woo_Checkout_Helper;

    /**
     * Compare an installed plugins version
     *
     * @since 3.0.0
     */
    public function version_compare($plugin, $version, $condition)
    {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugin = get_plugin_data(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin, false, false);

        return version_compare($plugin['Version'], $version, $condition);
    }

    // Subscribe to Mailchimp list
    public function mailchimp_subscribe_with_ajax()
    {
        if (!isset($_POST['fields'])) {
            return;
        }

        $api_key = $_POST['apiKey'];
        $list_id = $_POST['listId'];

        parse_str($_POST['fields'], $settings);

        $merge_fields = array(
            'FNAME' => !empty($settings['eael_mailchimp_firstname']) ? $settings['eael_mailchimp_firstname'] : '',
            'LNAME' => !empty($settings['eael_mailchimp_lastname']) ? $settings['eael_mailchimp_lastname'] : '',
        );

        $settings_tags_string = !empty($settings['eael_mailchimp_tags']) ? sanitize_text_field($settings['eael_mailchimp_tags']) : '';
        if(!empty($settings_tags_string)){
            $settings_tags_string = explode(',', $settings_tags_string);
        }
        $body_params = array(
            'email_address' => $settings['eael_mailchimp_email'],
            'status' => 'subscribed',
            'merge_fields' => $merge_fields,
        );

        if(!empty($settings_tags_string)){
            $body_params['tags'] = $settings_tags_string;
        }
        $response = wp_remote_post(
            'https://' . substr($api_key, strpos(
                $api_key,
                '-'
            ) + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($settings['eael_mailchimp_email'])),
            [
                'method' => 'PUT',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
                ],
                'body' => json_encode( $body_params ),
            ]
        );

        if (!is_wp_error($response)) {
            $response = json_decode(wp_remote_retrieve_body($response));

            if (!empty($response)) {
                if ($response->status == 'subscribed') {
                    wp_send_json([
                        'status' => 'subscribed',
                    ]);
                } else {
                    wp_send_json([
                        'status' => $response->title,
                    ]);
                }
            }
        }
    }

    public function ajax_post_search()
    {
        if (!isset($_POST['_nonce']) && !wp_verify_nonce($_POST['_nonce'], 'eael_ajax_post_search_nonce_action')) {
            return;
        }

        $html = '';
        $args = array(
            'post_type' => esc_attr($_POST['post_type']),
            'post_status' => 'publish',
            's' => esc_attr($_POST['key']),
        );

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $html .= '<div class="ajax-search-result-post">
                    <h6><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h6>
                </div>';
            }
        }

        echo $html;
        die();
    }

    public function connect_remote_db()
    {
        // check ajax referer
        check_ajax_referer('essential-addons-elementor', 'security');

        $result = [
            'connected' => false,
            'tables' => [],
        ];

        if (empty($_REQUEST['host']) || empty($_REQUEST['username']) || empty($_REQUEST['password']) || empty($_REQUEST['database'])) {
            wp_send_json($result);
        }

        $conn = new \mysqli($_REQUEST['host'], $_REQUEST['username'], $_REQUEST['password'], $_REQUEST['database']);

        if ($conn->connect_error) {
            wp_send_json($result);
        } else {
            $query = $conn->query("show tables");

            if ($query) {
                $tables = $query->fetch_all();

                $result['connected'] = true;
                $result['tables'] = wp_list_pluck($tables, 0);
            }

            $conn->close();
        }

        wp_send_json($result);
    }

    /**
     * Show the split layout.
     */
    public static function woo_checkout_render_split_template_($checkout, $settings)
    {

        $ea_woo_checkout_btn_next_data = $settings['ea_woo_checkout_tabs_btn_next_text'];
	    if ( get_option( 'woocommerce_enable_coupons' ) === 'yes' && $settings['ea_woo_checkout_coupon_hide'] !== 'yes' ) {
		    $enable_coupon = 1;
	    } else {
		    $enable_coupon = '';
        }
?>
        <div class="layout-split-container" data-coupon="<?php echo $enable_coupon; ?>">
            <div class="info-area">
                <ul class="split-tabs">
                    <?php
                    $step1_class = 'first active';
                    $enable_login_reminder = false;

                    if ((\Elementor\Plugin::$instance->editor->is_edit_mode() && 'yes' === $settings['ea_section_woo_login_show']) || (!is_user_logged_in() && 'yes' === get_option('woocommerce_enable_checkout_login_reminder'))) {
                        $enable_login_reminder = true;
                        $step1_class = '';
                    ?>
                        <li id="step-0" data-step="0" class="split-tab first active"><?php echo $settings['ea_woo_checkout_tab_login_text']; ?></li>
                    <?php
                    }
                    if ( get_option( 'woocommerce_enable_coupons' ) === 'yes' && $settings['ea_woo_checkout_coupon_hide'] !== 'yes' ) { ?>
                        <li id="step-1" class="split-tab <?php echo $step1_class; ?>" data-step="1"><?php echo
                                                                                                        $settings['ea_woo_checkout_tab_coupon_text']; ?></li>
                        <li id="step-2" class="split-tab" data-step="2"><?php echo $settings['ea_woo_checkout_tab_billing_shipping_text']; ?></li>
                        <li id="step-3" class="split-tab last" data-step="3"><?php echo $settings['ea_woo_checkout_tab_payment_text']; ?></li>
                    <?php } else { ?>
                        <li id="step-1" class="split-tab <?php echo $step1_class; ?>" data-step="1"><?php echo $settings['ea_woo_checkout_tab_billing_shipping_text']; ?></li>
                        <li id="step-2" class="split-tab last" data-step="2"><?php echo $settings['ea_woo_checkout_tab_payment_text']; ?></li>
                    <?php } ?>
                </ul>

                <div class="split-tabs-content">
                    <?php
                    // If checkout registration is disabled and not logged in, the user cannot checkout.
                    if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
                        echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'essential-addons-elementor')));
                        return;
                    }
                    ?>

                    <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

                    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

                        <?php if ($checkout->get_checkout_fields()) : ?>

                            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                            <div class="col2-set" id="customer_details">
                                <div class="col-1">
                                    <?php do_action('woocommerce_checkout_billing'); ?>
                                </div>

                                <div class="col-2">
                                    <?php do_action('woocommerce_checkout_shipping'); ?>
                                </div>
                            </div>

                            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                        <?php endif; ?>

                        <?php do_action('woocommerce_checkout_order_review'); ?>

                    </form>

                    <?php do_action('woocommerce_after_checkout_form', $checkout); ?>

                    <div class="steps-buttons">
                        <button class="ea-woo-checkout-btn-prev"><?php echo $settings['ea_woo_checkout_tabs_btn_prev_text']; ?></button>
                        <button class="ea-woo-checkout-btn-next" data-text="<?php echo htmlspecialchars(json_encode($ea_woo_checkout_btn_next_data), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $settings['ea_woo_checkout_tabs_btn_next_text']; ?></button>
                        <button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="ea_place_order" value="<?php echo $settings['ea_woo_checkout_place_order_text']; ?>" data-value="<?php echo $settings['ea_woo_checkout_place_order_text']; ?>" style="display:none;
                        "><?php echo $settings['ea_woo_checkout_place_order_text']; ?></button>
                    </div>
                </div>
            </div>

            <div class="table-area">
                <div class="ea-woo-checkout-order-review">
                    <?php self::checkout_order_review_default($settings); ?>
                </div>
            </div>
        </div>
    <?php }

    /**
     * validate woocommerce post code
     *
     * @since  3.6.4
     */
    public function eael_woo_checkout_post_code_validate()
    {
        $data = $_POST['data'];
        $validate = true;
        if (isset($data['postcode'])) {

            $format = wc_format_postcode($data['postcode'], $data['country']);
            if ('' !== $format && !\WC_Validation::is_postcode($data['postcode'], $data['country'])) {
                $validate = false;
            }
        }
        wp_send_json($validate);
    }

    /**
     * Show the multi step layout.
     */
    public static function woo_checkout_render_multi_steps_template_($checkout, $settings)
    {

        $ea_woo_checkout_btn_next_data = $settings['ea_woo_checkout_tabs_btn_next_text'];
	    if ( get_option( 'woocommerce_enable_coupons' ) === 'yes' && $settings['ea_woo_checkout_coupon_hide'] !== 'yes' ) {
		    $enable_coupon = 1;
	    } else {
		    $enable_coupon = '';
	    }
    ?>
        <div class="layout-multi-steps-container" data-coupon="<?php echo $enable_coupon; ?>">
            <ul class="ms-tabs">
                <?php
                $step1_class = 'first active';
                $enable_login_reminder = false;

                if ((\Elementor\Plugin::$instance->editor->is_edit_mode() && 'yes' === $settings['ea_section_woo_login_show']) || (!is_user_logged_in() && 'yes' === get_option('woocommerce_enable_checkout_login_reminder'))) {
                    $enable_login_reminder = true;
                    $step1_class = '';
                ?>
                    <li class="ms-tab first active" id="step-0" data-step="0"><?php echo
                                                                                    $settings['ea_woo_checkout_tab_login_text']; ?></li>
                <?php }
                if ( get_option( 'woocommerce_enable_coupons' ) === 'yes' && $settings['ea_woo_checkout_coupon_hide'] !== 'yes' ) { ?>
                    <li class="ms-tab <?php echo $step1_class; ?>" id="step-1" data-step="1"><?php echo
                                                                                                    $settings['ea_woo_checkout_tab_coupon_text']; ?></li>
                    <li class="ms-tab" id="step-2" data-step="2"><?php echo $settings['ea_woo_checkout_tab_billing_shipping_text']; ?></li>
                    <li class="ms-tab last" id="step-3" data-step="3"><?php echo $settings['ea_woo_checkout_tab_payment_text']; ?></li>
                <?php } else { ?>
                    <li class="ms-tab <?php echo $step1_class; ?>" id="step-1" data-step="1"><?php echo
                                                                                                    $settings['ea_woo_checkout_tab_billing_shipping_text']; ?></li>
                    <li class="ms-tab last" id="step-2" data-step="2"><?php echo $settings['ea_woo_checkout_tab_payment_text']; ?></li>
                <?php }
                ?>
            </ul>

            <div class="ms-tabs-content-wrap">
                <div class="ms-tabs-content">
                    <?php
                    // If checkout registration is disabled and not logged in, the user cannot checkout.
                    if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
                        echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'essential-addons-elementor')));
                        return;
                    }
                    ?>

                    <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

                    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

                        <?php if ($checkout->get_checkout_fields()) : ?>

                            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                            <div class="col2-set" id="customer_details">
                                <div class="col-1">
                                    <?php do_action('woocommerce_checkout_billing'); ?>
                                </div>

                                <div class="col-2">
                                    <?php do_action('woocommerce_checkout_shipping'); ?>
                                </div>
                            </div>

                            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                        <?php endif; ?>

                        <?php do_action('woocommerce_checkout_order_review'); ?>

                    </form>

                    <?php do_action('woocommerce_after_checkout_form', $checkout); ?>

                    <div class="steps-buttons">
                        <button class="ea-woo-checkout-btn-prev"><?php echo $settings['ea_woo_checkout_tabs_btn_prev_text']; ?></button>
                        <button class="ea-woo-checkout-btn-next" data-text="<?php echo htmlspecialchars(json_encode($ea_woo_checkout_btn_next_data), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $settings['ea_woo_checkout_tabs_btn_next_text']; ?></button>
                        <button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="ea_place_order" value="<?php echo $settings['ea_woo_checkout_place_order_text']; ?>" data-value="<?php echo $settings['ea_woo_checkout_place_order_text']; ?>" style="display:none;"><?php echo $settings['ea_woo_checkout_place_order_text']; ?></button>
                    </div>
                </div>

                <div class="table-area">
                    <div class="ea-woo-checkout-order-review">
                        <?php self::checkout_order_review_default($settings); ?>
                    </div>
                </div>
            </div>
        </div>
<?php }

	public function fetch_search_result() {

		if ( empty( $_POST[ 'nonce' ] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		if ( !wp_verify_nonce( $_POST[ 'nonce' ], 'essential-addons-elementor' ) ) {
			$err_msg = __( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		$search   = sanitize_text_field( trim( $_POST[ 's' ] ) );
		$response = [];
		if ( !empty( $_POST[ 'settings' ][ 'show_category' ] ) && strlen( $search ) > 2 ) {

			if ( !empty( $_POST[ 'settings' ][ 'post_type' ] ) ) {
				$args[ 'post_types' ] = $_POST[ 'settings' ][ 'post_type' ];
			}

			if ( !empty( $_POST[ 'settings' ][ 'cat_id' ] ) ) {
				$args[ 'include' ] = [ $_POST[ 'settings' ][ 'cat_id' ] ];
			}
			$args[ 'search' ]         = $search;
			$response[ 'cate_lists' ] = $this->get_terms_data( $args );
		}

		$post_args = [
			's'                      => $search,
			'posts_per_page'         => !empty( $_POST[ 'settings' ][ 'post_per_page' ] ) ? intval( $_POST[ 'settings' ][ 'post_per_page' ] ) : 5,
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'no_found_rows'          => true,
			'post_status'            => 'publish',
		];

		if ( !empty( $_POST[ 'settings' ][ 'offset' ] ) ) {
			$post_args[ 'offset' ] = $_POST[ 'settings' ][ 'offset' ];
		}

		if ( !empty( $_POST[ 'settings' ][ 'post_type' ] ) ) {
			$post_args[ 'post_type' ] = $_POST[ 'settings' ][ 'post_type' ];
		}

		if ( !empty( $_POST[ 'settings' ][ 'cat_id' ] ) ) {
			$term = get_term( $_POST[ 'settings' ][ 'cat_id' ] );
			if ( !is_wp_error( $term ) ) {
				$post_args[ 'tax_query' ][] = [
					'taxonomy' => $term->taxonomy,
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				];
			}
		}

        if ( !empty( $_POST[ 'settings' ][ 'include' ] ) ) {
            $post_args = $this->manage_include_exclude_category( $_POST[ 'settings' ][ 'include' ], $post_args );
        }

        if ( !empty( $_POST[ 'settings' ][ 'exclude' ] ) ) {
            $post_args = $this->manage_include_exclude_category( $_POST[ 'settings' ][ 'exclude' ], $post_args, 'exclude' );
        }

		$query                   = new \WP_Query( $post_args );
		$post_lists              = '';
		$response[ 'more_data' ] = $query->post_count >= $post_args[ 'posts_per_page' ];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_lists .= sprintf( '<a href="%s" class="eael-advanced-search-content-item">', esc_url( get_the_permalink() ) );
				if ( has_post_thumbnail() && !empty( $_POST[ 'settings' ][ 'show_content_image' ] ) ) {
					$image      = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
					$post_lists .= sprintf( '<div class="item-thumb"><img src="%s"></div>', current( $image ) );
				}

				$post_lists .= '<div class="item-content"><h4>' . strip_tags( get_the_title() ) . '</h4><p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ), 30 ) . '</p></div>';
				$post_lists .= '</a>';
			}
			wp_reset_postdata();
			$response[ 'post_lists' ] = $post_lists;
		}

		$show_popular_keyword = !empty( $_POST[ 'settings' ][ 'show_popular_keyword' ] );

		if ( strlen( $search ) > 4 || $show_popular_keyword ) {
			$update           = !empty( $response[ 'post_lists' ] );
			$popular_keywords = $this->manage_popular_keyword( $search, $show_popular_keyword, $update );
			if ( $show_popular_keyword ) {
				$response[ 'popular_keyword' ] = $popular_keywords;
			}
		}

		wp_send_json_success( $response );

	}

    /**
     * manage_include_exclude_category
     * @param array $term_ids
     * @param array $post_args
     * @param string $type
     * @retrun array
     */
    public function manage_include_exclude_category( $term_ids, $post_args, $type = 'include' ){
        $operator = $type === 'include' ? 'IN' : 'NOT IN';

        foreach ( $term_ids as $term_id ){
            $term = get_term( $term_id );
            $post_args['tax_query'][$term->taxonomy.'_'.$type]['taxonomy'] = $term->taxonomy;
            $post_args['tax_query'][$term->taxonomy.'_'.$type]['field']    = 'term_id';
            $post_args['tax_query'][$term->taxonomy.'_'.$type]['operator'] = $operator;
            $post_args['tax_query'][$term->taxonomy.'_'.$type]['terms'][]  = $term->term_id;
        }

        return $post_args;
    }
	/**
	 * manage_popular_keyword
	 * @param string $key
	 * @param bool $status
	 * @param bool $update
	 * @return string|null
	 */
	public function manage_popular_keyword( $key, $status = false, $update = true ) {

		$popular_keywords = (array)get_option( 'eael_adv_search_popular_keyword', true );

		if ( strlen( $key ) >= 4 ) {

			$key = str_replace( ' ', '_', strtolower( $key ) );
			if ( !empty( $popular_keywords ) ) {
				if ( !empty( $popular_keywords[ $key ] ) ) {
					$popular_keywords[ $key ] = $popular_keywords[ $key ] + 1;
				} else {
					$popular_keywords[ $key ] = 1;
				}
			}

			if ( $update ) {
				update_option( 'eael_adv_search_popular_keyword', $popular_keywords );
			}
		}

		if ( $status ) {
			arsort( $popular_keywords );
			$lists = null;
			$rank  = !empty( $_POST[ 'settings' ][ 'show_popular_keyword_rank' ] ) ? intval( $_POST[ 'settings' ][ 'show_popular_keyword_rank' ] ) : 5;
			foreach ( array_slice( $popular_keywords, 1, intval( $_POST[ 'settings' ][ 'total_number_of_popular_search' ] ) ) as $key => $item ) {
				if ( $item <= $rank ) {
					continue;
				}
				$keywords = ucfirst( str_replace( '_', ' ', $key ) );
				$lists    .= sprintf( '<a href="javascript:void(0)" data-keyword="%1$s" class="eael-popular-keyword-item">%1$s</a>', $keywords, $key );
			}
			return $lists;
		}
		return null;
	}

	/**
	 * get_terms_data
	 * @param $args array
	 * @return string|null
	 */
	public function get_terms_data( $args ) {

		$args = wp_parse_args( $args, [
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC'
		] );

		if ( !empty( $args[ 'post_types' ] ) && is_array( $args[ 'post_types' ] ) ) {
			$taxonomies = get_object_taxonomies( $args[ 'post_types' ] );
			if ( !empty( $taxonomies ) ) {
				$args[ 'taxonomy' ] = $taxonomies;
			}
		}

		$terms      = get_terms( $args );
		$term_lists = '';
		if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$term_lists .= sprintf( '<li><a href="%s">%s</a></li>', esc_url( get_term_link( $term ) ), $term->name );
			}
			$term_lists = sprintf( "<ul>%s</ul>", $term_lists );
		}
		return $term_lists;
	}

	/**
	 * eael_valid_select_query
	 * Check this sql query only contain select query that's mean
	 * if query have update, delete or insert instruction this method return false
	 *
	 * @param string $query raw sql query
	 *
	 * @return false|int
	 * @since 5.0.1
	 */
	public function eael_valid_select_query( $query ) {
		$valid = preg_match(
			'/^\s*(?:'
			. 'SELECT.*?\s+FROM'
			. ')\s+((?:[0-9a-zA-Z$_.`-]|[\xC2-\xDF][\x80-\xBF])+)/is',
			$query
		);

		return $valid;
	}

    /**
     * Adds extra protocols to wp allowed tags
     * @param array $protocols
     * @return array
     * @since 4.4.11
     */
    public static function eael_wp_allowed_tags( $protocols = array() ){
        $eael_wp_allowed_protocols = wp_allowed_protocols();
        if(is_array($protocols) && count($protocols)){
            foreach ($protocols as $protocol){
                $eael_wp_allowed_protocols[] = $protocol;
            }
        }

        return array_unique($eael_wp_allowed_protocols);
    }
}
