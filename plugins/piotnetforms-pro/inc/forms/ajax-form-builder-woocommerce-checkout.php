<?php
	add_action( 'wp_ajax_piotnetforms_ajax_form_builder_woocommerce_checkout', 'piotnetforms_ajax_form_builder_woocommerce_checkout' );
	add_action( 'wp_ajax_nopriv_piotnetforms_ajax_form_builder_woocommerce_checkout', 'piotnetforms_ajax_form_builder_woocommerce_checkout' );

	function set_val_woocommerce_checkout_piotnetforms(&$array,$path,$val) {
		for($i=&$array; $key=array_shift($path); $i=&$i[$key]) {
			if(!isset($i[$key])) $i[$key] = array();
		}
		$i = $val;
	}

	function piotnetforms_merge_string_woocommerce_checkout(&$string,$string_add) {
		$string = $string . $string_add;
	}

	function piotnetforms_unset_string_woocommerce_checkout(&$string) {
		$string = '';
	}

	function piotnetforms_set_string_woocommerce_checkout(&$string,$string_set) {
		$string = $string_set;
	}

	function replace_email_woocommerce_checkout_piotnetforms($content, $fields, $payment_status = 'succeeded', $payment_id = '', $succeeded = 'succeeded', $pending = 'pending', $failed = 'failed', $submit_id = 0 ) {
		$message = $content;

		$message_all_fields = '';

		if (!empty($fields)) {

			// all fields
			foreach ($fields as $field) {
				$field_value = $field['value'];
				$field_label = isset($field['label']) ? $field['label'] : '';
				if (isset($field['value_label'])) {
					$field_value = $field['value_label'];
				}

				$repeater_id = $field['repeater_id'];
				$repeater_id_string = '';
				$repeater_id_array = array_reverse( explode(',', rtrim($repeater_id, ',')) );
				foreach ($repeater_id_array as $repeater) {
					$repeater_array = explode('|', $repeater);
					array_pop($repeater_array);
					$repeater_id_string .= join(",",$repeater_array);
				}
				$repeater_index = $field['repeater_index']; 
				$repeater_index_1 = $repeater_index + 1;
				$repeater_label = '<span data-id="' . esc_attr( $repeater_id_string ) . '"><strong>' . $field['repeater_label'] . ' ' . $repeater_index_1 . ': </strong></span><br>';

				$repeater_remove_this_field = false;
				if (isset($field['repeater_remove_this_field'])) {
					$repeater_remove_this_field = true;
				}
				
				if (!empty($repeater_id) && !empty($repeater_label) && $repeater_remove_this_field == false) {
					if (strpos($message_all_fields, $repeater_label) !== false) {
						$message_all_fields .= '<div class="piotnetforms-woocommerce_checkout-submission__item"><label class="piotnetforms-woocommerce_checkout-submission__item-label">' . $field_label . ': </label>' . '<span class="piotnetforms-woocommerce_checkout-submission__item-value">' . $field_value . '</span>' . '</div>';
					} else {
						$message_all_fields .= $repeater_label;
						$message_all_fields .= '<div class="piotnetforms-woocommerce_checkout-submission__item"><label class="piotnetforms-woocommerce_checkout-submission__item-label">' . $field_label . ': </label>' . '<span class="piotnetforms-woocommerce_checkout-submission__item-value">' . $field_value . '</span>' . '</div>';
					}
					// if ($field['repeater_index'] != ($field['repeater_length'] - 1)) {
					// 	$message .=  '</div>';
					// }
				} else {
					if (strpos($field['name'], 'piotnetforms-end-repeater') === false) {
						$message_all_fields .= '<div class="piotnetforms-woocommerce_checkout-submission__item"><label class="piotnetforms-woocommerce_checkout-submission__item-label">' . $field_label . ': </label>' . '<span class="piotnetforms-woocommerce_checkout-submission__item-value">' . $field_value . '</span>' . '</div>';
					}
				}

			}

			$message = str_replace( '[all-fields]', $message_all_fields, $message );

			// each field

			$repeater_content = '';
			$repeater_id_one = '';
			foreach ($fields as $field) {
				$field_value = $field['value'];
				$field_label = isset($field['label']) ? $field['label'] : '';
				if (isset($field['value_label'])) {
					$field_value = $field['value_label'];
				}

				$search_remove_line_if_field_empty = '[field id="' . $field['name'] . '"]' . '[remove_line_if_field_empty]';

				if (empty($field_value)) {
					$lines = explode("\n", $message);
					$lines_found = array();

					foreach($lines as $num => $line){
					    $pos = strpos($line, $search_remove_line_if_field_empty);
					    if($pos !== false) {
					    	$lines_found[] = $line;
					    }
					}

					if (!empty($lines_found)) {
						foreach ($lines_found as $line) {
							$message = str_replace( [ $line . "\n", "\n" . $line ], '', $message );
						}
					}
				}

				$search = '[field id="' . $field['name'] . '"]';
				$message = str_replace($search, $field_value, $message);

				$repeater_id = $field['repeater_id'];
				$repeater_id_string = '';
				$repeater_id_array = array_reverse( explode(',', rtrim($repeater_id, ',')) );
				foreach ($repeater_id_array as $repeater) {
					$repeater_array = explode('|', $repeater);
					array_pop($repeater_array);
					$repeater_id_string .= join(",",$repeater_array);
				}
				$repeater_index = $field['repeater_index']; 
				$repeater_index_1 = $repeater_index + 1;
				$repeater_label = '<span data-id="' . esc_attr( $repeater_id_string ) . '"><strong>' . $field['repeater_label'] . ' ' . $repeater_index_1 . ': </strong></span><br>';

				$repeater_remove_this_field = false;
				if (isset($field['repeater_remove_this_field'])) {
					$repeater_remove_this_field = true;
				}
				
				if (!empty($repeater_id) && !empty($repeater_label) && $repeater_remove_this_field == false) {
					if (strpos($repeater_content, $repeater_label) !== false) {
						$string_add = '<div class="piotnetforms-woocommerce_checkout-submission__item"><label class="piotnetforms-woocommerce_checkout-submission__item-label">' . $field_label . ': </label>' . '<span class="piotnetforms-woocommerce_checkout-submission__item-value">' . $field_value . '</span>' . '</div>';
						piotnetforms_merge_string_woocommerce_checkout($repeater_content,$string_add);
					} else {
						$string_add = $repeater_label . '<div class="piotnetforms-woocommerce_checkout-submission__item"><label class="piotnetforms-woocommerce_checkout-submission__item-label">' . $field_label . ': </label>' . '<span class="piotnetforms-woocommerce_checkout-submission__item-value">' . $field_value . '</span>' . '</div>';
						piotnetforms_merge_string_woocommerce_checkout($repeater_content,$string_add);
					}
					if (substr_count($field['repeater_id'],'|') == 2) {
						piotnetforms_set_string_woocommerce_checkout($repeater_id_one,$field['repeater_id_one']);
					}
				}

				if (empty($repeater_id)) {
					if (!empty($repeater_id_one) && !empty($repeater_content)) {
						$search_repeater = '[repeater id="' . $repeater_id_one . '"]';
						$message = str_replace($search_repeater, $repeater_content, $message);
						piotnetforms_unset_string_woocommerce_checkout($repeater_content);
						piotnetforms_unset_string_woocommerce_checkout($repeater_id_one);
					}
				}
				
			}
		}

		$search_remove_line_if_field_empty = '"]' . '[remove_line_if_field_empty]'; // fix alert [

		$lines = explode("\n", $message);
		$lines_found = array();

		foreach($lines as $num => $line){
		    $pos = strpos($line, $search_remove_line_if_field_empty);
		    if($pos !== false) {
		    	$lines_found[] = $line;
		    }
		}

		if (!empty($lines_found)) {
			foreach ($lines_found as $line) {
				$message = str_replace( [ $line . "\n", "\n" . $line ], '', $message );
			}
		}

		$message = str_replace( [ "[remove_line_if_field_empty]" ], '', $message );

		$message = str_replace( [ "\r\n", "\n", "\r", "[remove_line_if_field_empty]" ], '<br />', $message );

		if ($payment_status == 'succeeded') {
			$message = str_replace( '[payment_status]', $succeeded, $message );
		}

		if ($payment_status == 'pending') {
			$message = str_replace( '[payment_status]', $pending, $message );
		}

		if ($payment_status == 'failed') {
			$message = str_replace( '[payment_status]', $failed, $message );
		}

		if (!empty($payment_id)) {
			$message = str_replace( '[payment_id]', $payment_id, $message );
		}

		if (!empty($submit_id)) {
			$message = str_replace( '[submit_id]', $submit_id, $message );
		}

		return $message;
	}

	function get_field_name_shortcode_woocommerce_checkout_piotnetforms($content) {
		$field_name = str_replace('[field id="', '', $content);
		$field_name = str_replace('[repeater id="', '', $field_name); // fix alert ]
		$field_name = str_replace('"]', '', $field_name);
		return trim($field_name);
	}

	function piotnetforms_get_field_value_woocommerce_checkout($field_name,$fields, $payment_status = 'succeeded', $payment_id = '', $succeeded = 'succeeded', $pending = 'pending', $failed = 'failed' ) {

		$field_name_first = $field_name;

		if (strpos($field_name, '[repeater id') !== false) { // ] [ [ fix alert
			$field_name = str_replace('id="', "id='", $field_name);
			$field_name = str_replace('"]', "']", $field_name);
			$message = $field_name;
			$repeater_content = '';
			$repeater_id_one = '';
			foreach ($fields as $field) {
				$field_value = $field['value'];
				$field_label = isset($field['label']) ? $field['label'] : '';
				if (isset($field['value_label'])) {
					$field_value = $field['value_label'];
				}
				
				$search = '[field id="' . $field['name'] . '"]';
				$message = str_replace($search, $field_value, $message);

				$repeater_id = $field['repeater_id'];
				$repeater_id_string = '';
				$repeater_id_array = array_reverse( explode(',', rtrim($repeater_id, ',')) );
				foreach ($repeater_id_array as $repeater) {
					$repeater_array = explode('|', $repeater);
					array_pop($repeater_array);
					$repeater_id_string .= join(",",$repeater_array);
				}
				$repeater_index = $field['repeater_index']; 
				$repeater_index_1 = $repeater_index + 1;
				$repeater_label = $field['repeater_label'] . ' ' . $repeater_index_1 . '</div>';

				$repeater_remove_this_field = false;
				if (isset($field['repeater_remove_this_field'])) {
					$repeater_remove_this_field = true;
				}
				
				if (!empty($repeater_id) && !empty($repeater_label) && $repeater_remove_this_field == false) {
					if (strpos($repeater_content, $repeater_label) !== false) {
						$string_add = '<div class="piotnetforms-woocommerce_checkout-submission__item"><label class="piotnetforms-woocommerce_checkout-submission__item-label">' . $field_label . ': </label>' . '<span class="piotnetforms-woocommerce_checkout-submission__item-value">' . $field_value . '</span>' . '</div>';
						piotnetforms_merge_string_woocommerce_checkout($repeater_content,$string_add);
					} else {
						$string_add = $repeater_label . '<div class="piotnetforms-woocommerce_checkout-submission__item"><label class="piotnetforms-woocommerce_checkout-submission__item-label">' . $field_label . ': </label>' . '<span class="piotnetforms-woocommerce_checkout-submission__item-value">' . $field_value . '</span>' . '</div>';
						piotnetforms_merge_string_woocommerce_checkout($repeater_content,$string_add);
					}
					if (substr_count($field['repeater_id'],'|') == 2) {
						piotnetforms_set_string_woocommerce_checkout($repeater_id_one,$field['repeater_id_one']);
					}
				}

				if (empty($repeater_id)) {
					if (!empty($repeater_id_one) && !empty($repeater_content)) {
						$search_repeater = "[repeater id='" . $repeater_id_one . "']";
						$message = str_replace($search_repeater, $repeater_content, $message);

						piotnetforms_unset_string_woocommerce_checkout($repeater_content);
						piotnetforms_unset_string_woocommerce_checkout($repeater_id_one);
					}
				}
			}

			$field_value = $message;
		} else {
			$field_name = get_field_name_shortcode_woocommerce_checkout_piotnetforms($field_name);
			$field_value = '';
			foreach ($fields as $key_field=>$field) {
				if ($fields[$key_field]['name'] == $field_name) {
					// if (!empty($fields[$key_field]['value'])) {
					// 	$field_value = $fields[$key_field]['value'];
					// }

					if (isset($fields[$key_field]['calculation_results'])) {
						$field_value = $fields[$key_field]['calculation_results'];
					} else {
						$field_value = $fields[$key_field]['value'];
					}
				}
			}
		}

		if (strpos($field_name_first, '[payment_status]') !== false || strpos($field_name_first, '[payment_id]') !== false) {
			if ($payment_status == 'succeeded') {
				$field_value = str_replace( '[payment_status]', $succeeded, $field_name_first );
			}

			if ($payment_status == 'pending') {
				$field_value = str_replace( '[payment_status]', $pending, $field_name_first );
			}

			if ($payment_status == 'failed') {
				$field_value = str_replace( '[payment_status]', $failed, $field_name_first );
			}

			if (!empty($payment_id) && strpos($field_name_first, '[payment_id]') !== false) {
				$field_value = str_replace( '[payment_id]', $payment_id, $field_name_first );
			}
		}
		
		return trim($field_value);
	}

	function piotnetforms_ajax_form_builder_woocommerce_checkout() {
		if ( !empty($_POST['fields']) && !empty($_POST['form_id']) && !empty($_POST['post_id']) && !empty($_POST['product_id']) ) {
			$post_id = $_POST['post_id'];
			$form_id = $_POST['form_id'];
			$fields = stripslashes($_POST['fields']);
			$fields = json_decode($fields, true);
			$fields = array_unique($fields, SORT_REGULAR);

			$form = array();

            $data     = json_decode( get_post_meta( $post_id, '_piotnetforms_data', true ), true );
            $form['settings'] = $data['widgets'][ $form_id ]['settings'];

			if (!empty($form['settings']['remove_empty_form_input_fields'])) {
				$fields_new = array();
			    foreach ($fields as $field) {
			    	if (!empty($field['value'])) {
			    		$fields_new[] = $field;
			    	}
			    }
			    $fields = $fields_new;
			}

			// Filter Hook
					
			$fields = apply_filters( 'piotnetforms/form_builder/fields', $fields );
			if (!empty($form['settings']['woocommerce_add_to_cart_price'])) {
        		if (strpos($_POST['product_id'], 'field id') !== false) {
        			$product_id = intval( piotnetforms_get_field_value_woocommerce_checkout( str_replace('\"', '"', $_POST['product_id']),$fields ) );
    			} else {
    				$product_id = intval( $_POST['product_id'] );
    			}
        		
        		$cart_item_data = array();
        		$cart_item_data['fields'] = array();

        		$fields_cart = $fields;

        		if (!empty($form['settings']['woocommerce_add_to_cart_custom_order_item_meta_enable'])) {
        			$fields_cart = array();
					foreach ($form['settings']['woocommerce_add_to_cart_custom_order_item_list'] as $item) {
						if (!empty($item['woocommerce_add_to_cart_custom_order_item_field_shortcode'])) {
							foreach ($fields as $key_field=>$field) {
								if (strpos($item['woocommerce_add_to_cart_custom_order_item_field_shortcode'], '[repeater id') !== false) { // fix alert ]
									if ($fields[$key_field]['repeater_id_one'] == get_field_name_shortcode_woocommerce_checkout_piotnetforms( $item['woocommerce_add_to_cart_custom_order_item_field_shortcode'] )) {
										if (!isset($fields_cart[$fields[$key_field]['repeater_id_one']])) {
											$fields_cart[$fields[$key_field]['repeater_id_one']] = array(
												'label' => $fields[$key_field]['repeater_label'],
												'name' => $fields[$key_field]['repeater_id_one'],
												'value' => str_replace( '\n', '<br>', piotnetforms_get_field_value_woocommerce_checkout( '[repeater id="' . $fields[$key_field]['repeater_id_one'] . '"]',$fields,$payment_status, $payment_id) ),
											);
										}
									}
								} else {
									if ($fields[$key_field]['name'] == get_field_name_shortcode_woocommerce_checkout_piotnetforms( $item['woocommerce_add_to_cart_custom_order_item_field_shortcode'] )) {
										if (empty($item['woocommerce_add_to_cart_custom_order_item_remove_if_field_empty'])) {
											$fields_cart[] = $field;
										} else {
											if (!empty($field['value'])) {
												$fields_cart[] = $field;
											}
										}
									}
								}
							}
						}
					}
				}

				foreach ($fields as $key_field=>$field) {
					if ($fields[$key_field]['name'] == get_field_name_shortcode_woocommerce_checkout_piotnetforms( $form['settings']['woocommerce_add_to_cart_price'] )) {
						if (isset($fields[$key_field]['calculation_results'])) {
							$cart_item_data['piotnetforms_custom_price'] = $fields[$key_field]['calculation_results'];
						} else {
							$cart_item_data['piotnetforms_custom_price'] = $fields[$key_field]['value'];
						}
					}
				}

				$piotnetforms_booking = array();

    			foreach ($fields_cart as $key_field=>$field) {
					$field_value = $fields_cart[$key_field]['value'];
					if (isset($fields_cart[$key_field]['value_label'])) {
						$field_value = $fields_cart[$key_field]['value_label'];
					}

					if (strpos($fields_cart[$key_field]['name'], 'piotnetforms-end-repeater') === false) {
						$cart_item_data['fields'][] = array(
							'label' => $fields_cart[$key_field]['label'],
							'name' => $fields_cart[$key_field]['name'],
							'value' => $field_value,
						);
					}
					

					if (!empty($form['settings']['booking_enable'])) {
						if ($fields_cart[$key_field]['name'] == get_field_name_shortcode_woocommerce_checkout_piotnetforms( $form['settings']['booking_shortcode'] )) {
							if (!empty($fields_cart[$key_field]['booking'])) {
								$booking = $fields_cart[$key_field]['booking'];
								foreach ($booking as $booking_key => $booking_item) {
									$booking_item = json_decode($booking_item, true);
									if ( !empty($booking_item['piotnetforms_booking_date_field']) ) {
										$date = date( "Y-m-d", strtotime( replace_email_woocommerce_checkout_piotnetforms($booking_item['piotnetforms_booking_date_field'], $fields) ) );
										$booking_item['piotnetforms_booking_date'] = $date;
									}
									$piotnetforms_booking = array_merge($piotnetforms_booking, array($booking_item) );
								}
							}
						}
					}
				}

				if (!empty($piotnetforms_booking)) {
					$cart_item_data['fields'][] = array(
						'label' => 'piotnetforms_booking',
						'name' => 'piotnetforms_booking',
						'value' => json_encode($piotnetforms_booking),
					);

					$cart_item_data['fields'][] = array(
						'label' => 'piotnetforms_booking_fields',
						'name' => 'piotnetforms_booking_fields',
						'value' => json_encode($fields),
					);
				}

				if (!empty($form['settings']['piotnetforms_woocommerce_checkout_redirect'])) {
					$redirect_url = $form['settings']['piotnetforms_woocommerce_checkout_redirect'];
					$cart_item_data['fields'][] = array(
						'label' => 'piotnetforms_woocommerce_checkout_redirect',
						'name' => 'piotnetforms_woocommerce_checkout_redirect',
						'value' => $redirect_url,
					);
				}

				$cart_item_data['fields'] = array_unique($cart_item_data['fields'], SORT_REGULAR);

				global $woocommerce;

				//$woocommerce->cart->empty_cart();

				$product_cart_id = $woocommerce->cart->generate_cart_id( $product_id, 0, array(), $cart_item_data );
				$cart_item_key = $woocommerce->cart->find_product_in_cart( $product_cart_id );

				foreach( WC()->cart->get_cart() as $cart_item ){
				    if( $product_id == $cart_item['product_id'] ) {
				    	$woocommerce->cart->remove_cart_item( $cart_item['key'] );
				    }
				}

				$woocommerce->cart->add_to_cart( $product_id, 1, 0, array(), $cart_item_data );

				echo '1';

        	}
		}

		wp_die();
	}
?>
