<?php

/*
 * Plugin Name: Elementor Contact Form DB
 * Plugin URI:  https://www.sean-barton.co.uk/2017/04/elementor-contact-form-db-free-plugin/
 * Description: A simple plugin to save contact form submissions in the database, designed for the Elementor Form Module
 * Author:      Sean Barton - Tortoise IT
 * Version:     1.7
 * Author URI:  http://www.sean-barton.co.uk
 *
 * Changelog:
 *
 * < V1.0
 * - Initial Version
 *
 * < V1.1
 * - Fixed for latest version of Elementor Pro
 *
 * V1.2 (2018-09-15)
 * - Added export functionality by Form ID and by page submitted on
 * - Removed limiting CSS so that paging and bulk delete is possible
 * - Added settings page housing an option to hide the "nag", the red bar notifying of submissions
 *
 * V1.3 (2019-05-13)
 * - Fixed conflict with new Elementor versions
 * - Added ability to show Export page to non admins (new setting on the settings page)
 * - Fixed issue whereby if more than one email was specified as an action then it would save two records
 *
 * V1.4 (2019-05-21)
 * - Minor preventative security related fixes
 *
 * V1.5 (2019-11-07)
 * - Vastly improved the speed of the exports. Better for databases of more than 1000 submissions. Tested on a DB of 37k
 *
 * V1.6 (2021-01-12)
 * - Added better handling of back end admin pages based on a report of a security exploit. Suggest update to a minimum of this plugin version asap
 *
 * V1.7 (2021-02-12)
 * - Added options to settings page which allow you to change the labels on the admin menu. Better for white labelling
 *
 */

if(!defined( 'WPINC' )) {
	die;
}

define( 'SB_ELEM_CFD_DB_ITEM_NAME', 'Elementor Contact Form DB' );
define( 'SB_ELEM_CFD_DB_VERSION', '1.7' );

add_action( 'plugins_loaded', 'sb_elem_cfd_init' );

function sb_elem_cfd_init() {
	add_action( 'admin_enqueue_scripts', 'sb_elem_cfd_css_enqueue', 9999 );

	add_action( 'elementor_pro/forms/new_record', 'sb_elem_cfd_new_record', 10, 10 );

	add_action( 'add_meta_boxes', 'sb_elem_cfd_register_meta_box' );
	add_action( 'init', 'sb_elem_cfd_pt_init' );
	add_action( 'admin_notices', 'sb_elem_cfd_admin_notice' );
	add_action( 'admin_head', 'sb_elem_cfd_admin_head' );
	add_action( 'admin_menu', 'sb_elem_cfd_submenu' );
	add_action( 'admin_init', 'sb_elem_cfd_download_csv', 1, 1 );

	add_filter( 'manage_elementor_cf_db_posts_columns', 'sb_elem_cfd_columns_head', 100 );
	add_action( 'manage_elementor_cf_db_posts_custom_column', 'sb_elem_cfd_columns_content', 100, 2 );
}

function sb_elem_cfd_submenu() {

	$sb_elem_cfd = get_option( 'sb_elem_cfd' );
	$min_role    = (isset( $sb_elem_cfd['records_min_role'] ) ? $sb_elem_cfd['records_min_role'] : 'administrator');

	add_submenu_page( 'edit.php?post_type=elementor_cf_db', 'Export', 'Export', $min_role, 'sb_elem_cfd', 'sb_elem_cfd_submenu_cb' );
	add_submenu_page( 'edit.php?post_type=elementor_cf_db', 'Settings', 'Settings', 'manage_options', 'sb_elem_cfd_settings', 'sb_elem_cfd_settings_submenu_cb' );

	sb_elem_cfd_disable_add_new();
}

function sb_elem_cfd_disable_add_new() {
	// Hide sidebar link
	global $submenu;
	unset( $submenu['edit.php?post_type=elementor_cf_db'][10] );

}

function sb_elem_cfd_box_start($title) {
	return '<div class="postbox">
                    <h2 class="hndle">' . $title . '</h2>
                    <div class="inside">';
}

function sb_elem_cfd_download_csv() {

	if(isset( $_REQUEST['download_csv'] )) {
		if(!empty( $_POST['sb_elem_cfd_export'] )) {
			if(wp_verify_nonce( $_POST['sb_elem_cfd_export'], 'sb_elem_cfd_export' )) {
				echo '<input name="sb_elem_cfd_export" type="hidden" value="' . wp_create_nonce( 'sb_elem_cfd_export' ) . '" />';

				if(isset( $_REQUEST['form_name'] )) {
					if($rows = sb_elem_cfd_get_export_rows( $_REQUEST['form_name'] )) {

						header( 'Content-Type: application/csv' );
						header( 'Content-Disposition: attachment; filename=' . sanitize_title( $_REQUEST['form_name'] ) . '.csv' );
						header( 'Pragma: no-cache' );
						echo implode( "\n", $rows );
						die;
					}
				}

				if(isset( $_REQUEST['form_id'] )) {
					if($rows = sb_elem_cfd_get_export_rows_by_form_id( $_REQUEST['form_id'] )) {

						header( 'Content-Type: application/csv' );
						header( 'Content-Disposition: attachment; filename=' . sanitize_title( $_REQUEST['form_id'] ) . '.csv' );
						header( 'Pragma: no-cache' );
						echo implode( "\n", $rows );
						die;
					}
				}
			}
		}
	}
}

function sb_elem_cfd_box_end() {
	return '    <div style="clear: both;">&nbsp;</div></div>
                </div>';
}


function sb_elem_cfd_submenu_cb() {
	global $wpdb;

	$forms = $forms2 = array();

	$sql = 'SELECT DISTINCT(pm.meta_value) AS form_name
			FROM 
				' . $wpdb->posts . ' p 
				JOIN ' . $wpdb->postmeta . ' pm ON (
					p.ID = pm.post_id AND 
					pm.meta_key = "sb_elem_cfd_form_id"
				) 
			WHERE 
				p.post_type = "elementor_cf_db"
				AND p.post_status = "publish"';


	$sql2 = 'SELECT DISTINCT(pm.meta_value) AS submitted_id
			FROM 
				' . $wpdb->posts . ' p 
				JOIN ' . $wpdb->postmeta . ' pm ON (
					p.ID = pm.post_id AND 
					pm.meta_key = "sb_elem_cfd_submitted_on_id"
				) 
			WHERE 
				p.post_type = "elementor_cf_db"
				AND p.post_status = "publish"';

	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
	echo '<h2>' . SB_ELEM_CFD_DB_ITEM_NAME . ' - Version ' . SB_ELEM_CFD_DB_VERSION . '</h2>';

	echo '<div id="poststuff">';

	echo '<div id="post-body" class="metabox-holder columns-2">';

	echo sb_elem_cfd_box_start( 'Export Results' );

	echo '<p>Use this simple form to export your contact data to CSV file. This is fairly crude but we don\'t have names for forms but we do have the page it was submitted from. Elementor has the facility to give a form an ID (in the additional tab of the builder). If set then you can also export by Form ID which is perhaps more useful!</p>';

	if($form_names = $wpdb->get_results( $sql )) {
		foreach($form_names as $form_name) {
			$forms2[$form_name->form_name] = $form_name->form_name;
		}
	}

	if($submitted_ids = $wpdb->get_results( $sql2 )) {
		foreach($submitted_ids as $submitted_id) {
			$forms[$submitted_id->submitted_id] = get_the_title( $submitted_id->submitted_id );
		}
	}

	if(get_posts( 'post_type=elementor_cf_db&posts_per_page=1' )) { //get one record only. we don't need it but just to show there is a single submission

		set_time_limit( 0 );
		//delete_option('sb_elem_cfd_record_update_v15'); //debug

		//updating old data for a faster structure
		if(!get_option( 'sb_elem_cfd_record_update_v15' )) {
			if($posts = get_posts( 'post_type=elementor_cf_db&posts_per_page=4000&meta_key=sb_elem_cfd_submitted_on_id&meta_compare=NOT EXISTS' )) {
				echo 'Found ' . count( $posts ) . ' Items to convert.<br />';

				foreach($posts as $post) {
					if($data = sb_elem_cfd_get_meta( $post->ID )) {
						$forms[$data['extra']['submitted_on_id']] = $data['extra']['submitted_on'];
						update_post_meta( $post->ID, 'sb_elem_cfd_submitted_on_id', $data['extra']['submitted_on_id'] );
					}
				}

				echo '<p>Data Structure Updated. Refresh the page for a faster interface</p>';
			} else {
				update_option( 'sb_elem_cfd_record_update_v15', time() );
			}

		}

		echo '<h3>Select a form to export</h3>';

		echo '<form method="POST" style="width: 48%; float: left;">';
		echo '<p><strong>By Page Submitted</strong></p>';
		echo '<select  style="margin-right: 10px; width: 200px;" name="form_name">';

		ksort( $forms );
		foreach($forms as $form => $label) {
			echo '<option ' . (isset( $_REQUEST['form_name'] ) && $_REQUEST['form_name'] == $form ? 'selected="selected"' : '') . ' value="' . $form . '">' . $label . '</option>';
		}

		echo '</select>';
		echo '<input type="submit" name="" class="button-primary" value="Export Form" />';
		echo '<input name="sb_elem_cfd_export" type="hidden" value="' . wp_create_nonce( 'sb_elem_cfd_export' ) . '" />';
		echo '</form>';

		echo '<form method="POST" style="width: 48%; float: left;">';
		echo '<p><strong>By form_id (additional options in the form module)</strong></p>';
		echo '<select  style="margin-right: 10px; width: 200px;" name="form_id">';

		ksort( $forms2 );
		foreach($forms2 as $form) {
			echo '<option ' . (isset( $_REQUEST['form_id'] ) && $_REQUEST['form_id'] == $form ? 'selected="selected"' : '') . ' value="' . $form . '">' . $form . '</option>';
		}

		echo '</select>';
		echo '<input type="submit" name="" class="button-primary" value="Export Form" />';
		echo '<input name="sb_elem_cfd_export" type="hidden" value="' . wp_create_nonce( 'sb_elem_cfd_export' ) . '" />';
		echo '</form>';

		echo '<div style="clear: both;">&nbsp;</div>';

		if(isset( $_REQUEST['form_name'] )) {

			$rows = sb_elem_cfd_get_export_rows( $_REQUEST['form_name'], 50 );

			echo '<h3>CSV Content (by Submitted Page)</h3>';
			echo '<p>Please review the data below and press "Download CSV File" to start the download. This list will show up to 50 submissions. The export will show the full list.</p>';
			echo '<div style="margin-top: 20px; min-height: 150px; max-height: 350px; overflow: scroll; margin-bottom: 10px; border: 1px solid #EEE; padding: 20px;">' . implode( '<br />', $rows ) . '</div>';

			echo '<form method="POST">';
			echo '<input type="hidden" name="form_name" value="' . $_REQUEST['form_name'] . '" />';
			echo '<input type="submit" name="download_csv" class="button-primary" value="Download CSV File" />';
			echo '<input name="sb_elem_cfd_export" type="hidden" value="' . wp_create_nonce( 'sb_elem_cfd_export' ) . '" />';
			echo '</form>';
		} elseif(isset( $_REQUEST['form_id'] )) {

			$rows = sb_elem_cfd_get_export_rows_by_form_id( $_REQUEST['form_id'], 50 );

			echo '<h3>CSV Content (by Form ID)</h3>';
			echo '<p>Please review the data below and press "Download CSV File" to start the download. This list will show up to 50 submissions. The export will show the full list.</p>';
			echo '<div style="margin-top: 20px; min-height: 150px; max-height: 350px; overflow: scroll; margin-bottom: 10px; border: 1px solid #EEE; padding: 20px;">' . implode( '<br />', $rows ) . '</div>';

			echo '<form method="POST">';
			echo '<input type="hidden" name="form_id" value="' . $_REQUEST['form_id'] . '" />';
			echo '<input type="submit" name="download_csv" class="button-primary" value="Download CSV File" />';
			echo '<input name="sb_elem_cfd_export" type="hidden" value="' . wp_create_nonce( 'sb_elem_cfd_export' ) . '" />';
			echo '</form>';
		}
	} else {
		echo '<p>This page will show a form when you have at least one submission. Until then, enjoy this picture of a cat!</p>';
		echo '<img src="http://placekitten.com/g/500/500" />';
	}

	echo sb_elem_cfd_box_end();

	echo '</div>';

	echo '</div>';
	echo '</div>';
}

function sb_elem_cfd_settings_submenu_cb() {

	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
	echo '<h2>' . SB_ELEM_CFD_DB_ITEM_NAME . ' - Version ' . SB_ELEM_CFD_DB_VERSION . '</h2>';

	echo '<div id="poststuff">';

	echo '<div id="post-body" class="metabox-holder columns-2">';

	if(isset( $_POST['sb_elem_cfd_save'] )) {
		if(!empty( $_POST['sb_elem_cfd_save_settings'] )) {
			if(wp_verify_nonce( $_POST['sb_elem_cfd_save_settings'], 'sb_elem_cfd_save_settings' )) {
				update_option( 'sb_elem_cfd', array_map( 'sanitize_text_field', @$_POST['sb_elem_cfd'] ) );
				echo '<div id="message" class="updated fade"><p>Settings saved successfully</p></div>';
			}
		}
	}

	$sb_elem_cfd = get_option( 'sb_elem_cfd' );

	echo sb_elem_cfd_box_start( 'Settings' );

	echo '<p>This simple form will provide some handy switches and settings for the plugin.</p>';


	echo '<form method="POST">';
	echo '<table class="form-table widefat">';

	echo '<tr>
				<td>Disable Admin Nag?</td>
                <td>
                	<input type="checkbox" name="sb_elem_cfd[disable_admin_nag]" ' . checked( 1, (isset( $sb_elem_cfd['disable_admin_nag'] ) ? 1 : 0), false ) . ' value="1" />
				</td>
				<td>
					<small>The admin nag is the red box that shows at the top of your admin pages when there is a contact submission to review. If you would prefer to use the plugin as a backup only then just check this box to turn the nag off..</small>
				</td>
            </tr>';

	ob_start();
	wp_dropdown_roles( isset( $sb_elem_cfd['records_min_role'] ) ? $sb_elem_cfd['records_min_role'] : 'administrator' );
	$role_options = ob_get_clean();

	$select = '<select name="sb_elem_cfd[records_min_role]">' . $role_options . '</select>';

	echo '<tr>
				<td>Minimum role to view records</td>
                <td>' . $select . '</td>
                <td><small>The minimum role needed to export the records. Normally administrator but some sites may use editor or other roles. Note that this settings page is only ever usable by administrators.</small></td>
            </tr>';

	echo '<tr>
				<td>Menu / Plural Label</td>
                <td><input type="text" name="sb_elem_cfd[title_plural]" value="' . esc_attr((isset( $sb_elem_cfd['title_plural'] ) ? $sb_elem_cfd['title_plural']:"Elementor DB")) . '" /></td>
                <td><small>The name of the menu item to show. Good for white labelling for clients</small></td>
            </tr>';

	echo '<tr>
				<td>Secondary / Singular Label</td>
                <td><input type="text" name="sb_elem_cfd[title_singular]" value="' . esc_attr((isset( $sb_elem_cfd['title_singular'] ) ? $sb_elem_cfd['title_singular']:"Elementor DB")) . '" /></td>
                <td><small>The secondary (singular) name of the post type to show. Good for white labelling for clients</small></td>
            </tr>';

	echo '</table>';

	echo '<p>';
	echo '<input name="sb_elem_cfd_save_settings" type="hidden" value="' . wp_create_nonce( 'sb_elem_cfd_save_settings' ) . '" />';
	echo '<input type="submit" name="sb_elem_cfd_save" class="button-primary" value="Save Settings" />';
	echo '</p>';

	echo '</form>';

	echo sb_elem_cfd_box_end();

	echo '</div>';

	echo '</div>';
	echo '</div>';
}

function sb_elem_cfd_get_export_rows($submitted_id, $limit = - 1) {
	$rows = array();
	$args = 'post_type=elementor_cf_db&meta_key=sb_elem_cfd_submitted_on_id&posts_per_page=' . $limit . '&meta_value=' . $submitted_id;

	if($posts = get_posts( $args )) {

		$first_post = current( $posts );

		$row = '';
		$row .= '"Date","Submitted On","Form ID","Submitted By",';

		if($data = sb_elem_cfd_get_meta( $first_post->ID )) {
			foreach($data['data'] as $field) {
				$row .= '"' . $field['label'] . '",';
			}
		}

		$rows[] = rtrim( $row, ',' );

		foreach($posts as $post) {
			if($data = sb_elem_cfd_get_meta( $post->ID )) {
				$row = '';

				$form_id = get_post_meta( $post->ID, 'sb_elem_cfd_form_id', true );
				$row     .= '"' . $post->post_date . '","' . $data['extra']['submitted_on'] . '","' . $form_id . '","' . $data['extra']['submitted_by'] . '",';

				foreach($data['data'] as $field) {
					$row .= '"' . addslashes( $field['value'] ) . '",';
				}

				$rows[] = rtrim( $row, ',' );
			}
		}
	}

	return $rows;
}

function sb_elem_cfd_get_meta($sub_id) {
	global $wpdb;

	$return = false;

	$sql = 'SELECT meta_value
			FROM ' . $wpdb->postmeta . '
			WHERE
				meta_key = "sb_elem_cfd"
				AND post_id = ' . $sub_id;

	if($meta = $wpdb->get_var( $sql )) {
		$return = unserialize( $meta );
	}

	return $return;
}

function sb_elem_cfd_get_export_rows_by_form_id($form_id, $limit = - 1) {

	$rows = array();

	if($posts = get_posts( 'post_type=elementor_cf_db&posts_per_page=' . $limit . '&meta_key=sb_elem_cfd_form_id&meta_value=' . $form_id )) {
		$row = '';
		$row .= '"Date","Submitted On","Form ID","Submitted By",';

		//labels. loop once
		$first_post = current( $posts );
		$data       = sb_elem_cfd_get_meta( $first_post->ID );

		foreach($data['data'] as $field) {
			$row .= '"' . $field['label'] . '",';
		}

		$rows[] = rtrim( $row, ',' );

		//fields
		foreach($posts as $post) {
			$data = sb_elem_cfd_get_meta( $post->ID );

			$row = '';
			$row .= '"' . $post->post_date . '","' . $data['extra']['submitted_on'] . '","' . $form_id . '","' . $data['extra']['submitted_by'] . '",';

			foreach($data['data'] as $field) {
				$row .= '"' . addslashes( $field['value'] ) . '",';
			}

			$rows[] = rtrim( $row, ',' );
		}
	}

	return $rows;
}

function sb_elem_cfd_css_enqueue() {
	global $current_screen;

	if($current_screen->id == 'elementor_cf_db') {
		wp_enqueue_script( 'sb_elem_cfd_js', plugins_url( '/script.js', __FILE__ ) );
	}
}

function sb_elem_cfd_columns_head($defaults) {
	unset( $defaults['date'] );
	//unset($defaults['cb']);
	unset( $defaults['title'] );

	$defaults['cf_elementor_title'] = 'View';
	$defaults['form_id']            = 'Form ID';
	$defaults['email']              = 'Email';
	$defaults['read']               = 'Read/Unread';
	$defaults['cloned']             = 'Cloned';
	$defaults['sub_on']             = 'Submitted On';
	$defaults['sub_date']           = 'Submission Date';

	return $defaults;
}

// SHOW THE FEATURED IMAGE
function sb_elem_cfd_columns_content($column_name, $post_id) {
	$contact = get_post( $post_id );
	$data    = get_post_meta( $post_id, 'sb_elem_cfd', true );

	if($column_name == 'cf_elementor_title') {
		echo '<a href="' . admin_url( 'post.php?action=edit&post=' . $post_id ) . '">View Submission</a>';
	} elseif($column_name == 'read') {
		if($read = get_post_meta( $post_id, 'sb_elem_cfd_read', true )) {
			echo '<span style="color: green;">' . $read['by_name'] . '<br />' . date( 'Y-m-d H:i', $read['on'] ) . '</span>';
		} else {
			echo '<span class="dashicons dashicons-email-alt"></span>';
		}
	} elseif($column_name == 'sub_on') {
		if($data['extra']['submitted_on']) {
			echo '<a href="' . get_permalink( $data['extra']['submitted_on_id'] ) . '">' . $data['extra']['submitted_on'] . '</a>';
		}
	} elseif($column_name == 'sub_date') {
		echo $contact->post_date;
	} elseif($column_name == 'cloned') {
		if($cloned = get_post_meta( $post_id, 'sb_elem_cfd_cloned', true )) {
			$cloned_count = count( $cloned );

			echo '<span class="dashicons dashicons-yes"></span> (' . $cloned_count . ')';
		} else {
			echo '<span class="dashicons dashicons-no-alt"></span>';
		}
	} elseif($column_name == 'email') {
		if($email = get_post_meta( $post_id, 'sb_elem_cfd_email', true )) {
			$email = '<a href="mailto:' . $email . '" target="_blank">' . $email . '</a>';
		} else {
			$email = '-';
		}
		echo $email;
	} elseif($column_name == 'form_id') {
		if(!$form_id = get_post_meta( $post_id, 'sb_elem_cfd_form_id', true )) {
			$form_id = '-';
		}

		echo $form_id;
	}
}

function sb_elem_cfd_admin_head() {
	global $current_user;

	// Hide link on listing page
	if((isset( $_GET['post_type'] ) && $_GET['post_type'] == 'elementor_cf_db') || (isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'elementor_cf_db')) {
		echo '<style type="text/css">
	    .page-title-action, #favorite-actions, .add-new-h2 { display:none; }
	    </style>';
	}

	if(isset( $_GET['sb-action'] )) {
		$action = $_GET['sb-action'];

		if($action == 'mark-all-read') {
			$args = array(
				'posts_per_page' => - 1,
				'meta_key'       => 'sb_elem_cfd_read',
				'meta_value'     => 0,
				'post_type'      => 'elementor_cf_db',
				'post_status'    => 'publish',
			);

			if($other_contacts = get_posts( $args )) {
				foreach($other_contacts as $other_contact) {
					$read = array(
						'by_name' => $current_user->display_name,
						'by'      => $current_user->ID,
						'on'      => time()
					);
					update_post_meta( $other_contact->ID, 'sb_elem_cfd_read', $read );
				}
			}
		}
	}
}

function sb_elem_cfd_admin_notice() {
	if(!current_user_can( 'administrator' )) {
		return;
	}

	$sb_elem_cfd = get_option( 'sb_elem_cfd' );

	if(isset( $sb_elem_cfd['disable_admin_nag'] ) && $sb_elem_cfd['disable_admin_nag']) {
		return;
	}

	$args = array(
		'posts_per_page' => - 1,
		'meta_key'       => 'sb_elem_cfd_read',
		'meta_value'     => 0,
		'post_type'      => 'elementor_cf_db',
		'post_status'    => 'publish',
	);

	if($other_contacts = get_posts( $args )) {
		//Use notice-warning for a yellow/orange, and notice-info for a blue left border.
		$class   = 'notice notice-error is-dismissible';
		$message = __( 'You have ' . count( $other_contacts ) . ' unread contact form submissions. Click <a href="' . admin_url( 'edit.php?post_type=elementor_cf_db' ) . '">here</a> to visit them or click <a href="' . admin_url( 'edit.php?post_type=elementor_cf_db&sb-action=mark-all-read' ) . '">here</a> to mark all as read', 'sb_elem_cfd' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
}

function sb_elem_cfd_register_meta_box() {
	add_meta_box( 'sb_elem_cfd', esc_html__( 'Form Submission', 'sb_elem_cfd' ), 'sb_elem_cfd_meta_box_callback', 'elementor_cf_db', 'normal', 'high' );
	add_meta_box( 'sb_elem_cfd_extra', esc_html__( 'Extra Information', 'sb_elem_cfd' ), 'sb_elem_cfd_meta_box_callback_extra', 'elementor_cf_db', 'normal', 'high' );
	add_meta_box( 'sb_elem_cfd_actions', esc_html__( 'Actions', 'sb_elem_cfd' ), 'sb_elem_cfd_meta_box_callback_actions', 'elementor_cf_db', 'normal', 'high' );

	//if ( current_user_can( 'administrator' ) ) {
	//	add_meta_box( 'sb_elem_cfd_debug', esc_html__( 'Debug/Server Info', 'sb_elem_cfd' ), 'sb_elem_cfd_meta_box_callback_debug', 'elementor_cf_db', 'normal', 'high' );
	//}
}

function sb_elem_cfd_meta_box_callback() {
	global $current_user;

	$submission = get_post( get_the_ID() );

	if(!$read = get_post_meta( get_the_ID(), 'sb_elem_cfd_read', true )) {
		$read = array('by_name' => $current_user->display_name, 'by' => $current_user->ID, 'on' => time());
		update_post_meta( get_the_ID(), 'sb_elem_cfd_read', $read );
	}

	$class   = 'notice notice-info';
	$message = 'First read by ' . $read['by_name'] . ' at ' . date( 'Y-m-d H:i', $read['on'] );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	if($data = get_post_meta( get_the_ID(), 'sb_elem_cfd', true )) {

		if($fields = $data['data']) {
			echo '<table class="widefat">
                        <thead>
                        <tr>
                            <th>Label</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                        <tbody>';

			foreach($fields as $field) {
				$value = $field['value'];

				if(is_email( $value )) {
					$value = '<a href="mailto:' . $value . '" target="_blank">' . $value . '</a>';
				}

				echo '<tr>
                            <td><strong>' . $field['label'] . '</strong></td>
                            <td>' . wpautop( sanitize_text_field( $value ) ) . '</td>
                        </tr>';
			}

			echo '<tr>
                            <td><strong>Date of Submission</strong></td>
                            <td>' . $submission->post_date . '</td>
                        </tr>';

			echo '</tbody>
                </table>';
		}
	}

}

function sb_elem_cfd_meta_box_callback_extra() {
	$other_submissions = '';

	if($data = get_post_meta( get_the_ID(), 'sb_elem_cfd', true )) {
		if($extra = $data['extra']) {
			echo '<table class="widefat">
                        <thead>
                        <tr>
                            <th>Label</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                        <tbody>';

			foreach($extra as $key => $value) {

				switch($key) {
					case 'submitted_on_id':
					case 'submitted_by_id':
						continue(2); //we don't really care about these ones
						break;
					case 'submitted_on':
						if($extra['submitted_on_id']) {
							$value = $value . ' (<a href="' . get_permalink( $extra['submitted_on_id'] ) . '" target="_blank">View Page</a> | <a href="' . admin_url( 'post.php?action=edit&post=' . $extra['submitted_on_id'] ) . '" target="_blank">Edit Page</a>)';
						} else {
							$value = '<em>Unknown</em>';
						}
						break;
					case 'submitted_by':
						if($extra['submitted_by_id']) {
							$value = $value . ' (<a href="' . admin_url( 'user-edit.php?user_id=' . $extra['submitted_by_id'] ) . '" target="_blank">View User Profiile</a>';

							$args = array(
								'posts_per_page' => - 1,
								'meta_key'       => 'sb_elem_cfd_submitted_by',
								'meta_value'     => $extra['submitted_by_id'],
								'post_type'      => 'elementor_cf_db',
								'post_status'    => 'publish',
							);

							if($other_contacts = get_posts( $args )) {
								$value             .= ' | <a style="cursor: pointer;" onclick="jQuery(\'.other_submissions\').slideToggle();">View ' . count( $other_contacts ) . ' more submissions by this user</a>';
								$other_submissions .= '<div style="display: none;" class="other_submissions">
                                                            <h3>Other submissions made by the same person</h3>';
								$other_submissions .= '<table class="widefat">';

								foreach($other_contacts as $other_contact) {
									$other_submissions .= '<tr><td><a href="' . admin_url( 'post.php?action=edit&post=' . $other_contact->ID ) . '">' . $other_contact->post_title . '</a></td></tr>';
								}

								$other_submissions .= '</table></div>';
							}

							$value .= ')';
						} else {
							$value = '<em>Not a registered user</em>';
						}

						break;
				}

				$key_label = ucwords( str_replace( '_', ' ', $key ) );

				echo '<tr>
                            <td><strong>' . $key_label . '</strong></td>
                            <td>' . $value . '</td>
                        </tr>';
			}

			echo '</tbody>
                </table>';

			echo $other_submissions;
		}

	}

}

function sb_elem_cfd_meta_box_callback_actions() {
	$submission = get_post( get_the_ID() );
	$data       = get_post_meta( get_the_ID(), 'sb_elem_cfd', true );

	if(isset( $_POST['sb_elem_cfd_map_to'] )) {
		$map_to       = $_POST['sb_elem_cfd_map_to'];
		$map_to_other = $_POST['sb_elem_cfd_map_to_other'];

		if($fields = $data['data']) {
			$mapped_fields = array();
			$custom_fields = array();

			foreach($fields as $field) {
				$mapped_fields[$field['label']] = $field['value'];
			}

			$db_ins = array(
				'post_title'   => 'Cloned from contact form',
				'post_content' => 'Cloned from contact form',
				'post_status'  => 'draft',
				'post_type'    => $_POST['sb_elem_cfd_pt'],
			);

			if(isset( $_POST['sb_elem_cfd_date'] )) {
				$db_ins['post_date'] = $_POST['sb_elem_cfd_date'];
			}

			$found = 0;

			foreach($map_to as $key => $field) {
				if($field) {
					$found ++;

					if($field == 'custom_field') {
						if($map_to_other[$key]) {
							$custom_fields[$map_to_other[$key]] = $mapped_fields[$key];
						}
					} else {
						$db_ins[$field] = $mapped_fields[$key];
					}
				}
			}

			if($found) {
				// Insert the post into the database
				if($post_id = wp_insert_post( $db_ins )) {
					if(!is_wp_error( $post_id )) {
						foreach($custom_fields as $key => $value) {
							update_post_meta( $post_id, $key, $value );
						}

						echo '<div id="message" class="updated fade">
                                    <p>Successfully copied the content of this contact form submission to another post type. Click here to <a href="' . get_permalink( $post_id ) . '">View</a> or <a href="' . admin_url( 'post.php?action=edit&post=' . $post_id ) . '">Edit</a></p>
                                </div>';

						if(!$cloned = get_post_meta( $_GET['post'], 'sb_elem_cfd_cloned', true )) {
							$cloned = array();
						}

						$cloned[$post_id] = time();

						update_post_meta( $_GET['post'], 'sb_elem_cfd_cloned', $cloned );

					} else {
						echo '<div id="message" class="error fade">
                                    <p>Oops something went wrong. This error message may be helpful: ' . print_r( $post_id, true ) . '</p>
                                </div>';
					}
				}
			} else {
				echo '<div id="message" class="error fade">
                            <p>You need to choose at least one field to map against for the clone to work.</p>
                        </div>';
			}

			//echo '<pre>';
			//print_r($db_ins);
			//print_r($custom_fields);
			//print_r($data['data']);
			//print_r($_POST);
			//echo '</pre>';
		}
	}

	$map_to_options = array();
	$maps           = array(
		'post_title'   => 'Title',
		'post_content' => 'Content',
		'custom_field' => 'Custom Field'
	);

	foreach($maps as $key => $value) {
		$map_to_options[] = '<option value="' . $key . '">' . $value . '</option>';
	}

	$types        = get_post_types();
	$type_options = array();

	foreach($types as $type2) {
		$type_obj2 = get_post_type_object( $type2 );

		if(!$type_obj2->public) {
			continue;
		}

		$type_options[] = '<option value="' . $type2 . '">' . $type_obj2->labels->name . '</option>';
	}

	echo '<p>';

	if($email = get_post_meta( get_the_ID(), 'sb_elem_cfd_email', true )) {
		echo '<a style="margin-right: 10px;" class="button-primary" target="_blank" href="mailto:' . $email . '">Reply via Email</a>';
	}

	echo '<a onclick="jQuery(\'.sb_elem_cfd_convert\').slideToggle();" class="button-secondary">Copy to another Post Type</a>';

	echo '</p>';

	///////////////////////////////////

	echo '<div style="display: none; overflow: scroll;" class="sb_elem_cfd_convert">';

	echo '<h3>Copy to another post type</h3>';

	echo '<p><label>Select Post Type: <select name="sb_elem_cfd_pt">' . implode( '', $type_options ) . '</select></label></p>';
	echo '<p>Select Field Mappings:</p>';

	echo '<table class="widefat">';

	foreach($data['fields_original']['form_fields'] as $field) {
		echo '<tr>
                    <td>' . $field['field_label'] . '</td>
                    <td>
                        <select name="sb_elem_cfd_map_to[' . $field['field_label'] . ']"><option value="">-- Unused --</option>' . implode( '', $map_to_options ) . '</select>
                        <span style="margin-left: 20px; display: inline-block;">(If "Custom Field" selected, enter field name: <input type="text" name="sb_elem_cfd_map_to_other[' . $field['field_label'] . ']" />)</span>
                    </td>
                </tr>';
	}

	echo '</table>';

	echo '<p><label><input type="checkbox" name="sb_elem_cfd_date" value="' . $submission->post_date . '" />&nbsp;Keep date of original submission? (' . $submission->post_date . ')</label></p>';
	echo '<p><input type="submit" class="button-primary sb_elem_cfd_copy" value="Copy" /></p>';

	//echo '<pre>';
	//print_r($data['fields_original']['form_fields']);
	//echo '</pre>';

	echo '</div>';

	if($cloned = get_post_meta( $_GET['post'], 'sb_elem_cfd_cloned', true )) {
		echo '<h3>Clone History</h3>';

		echo '<table class="widefat">
                    <thead>
                        <tr>
                            <th>New Post Title</th>
                            <th>Post Type</th>
                            <th>Date Cloned</th>
                            <th>Actions</th>
                        </tr>
                    </thead>';

		foreach($cloned as $cloned_id => $date) {
			if($cloned_post = get_post( $cloned_id )) {
				$type_obj  = get_post_type_object( $cloned_post->post_type );
				$type_name = $type_obj->labels->name;

				echo '<tr>
                            <td>' . $cloned_post->post_title . '</td>
                            <td>' . $type_name . '</td>
                            <td>' . date( 'Y-m-d H:i', $date ) . '</td>
                            <td><a href="' . get_permalink( $cloned_id ) . '">View</a> | <a href="' . admin_url( 'post.php?action=edit&post=' . $post_id ) . '">Edit</a></td>
                        </tr>';
			}
		}

		echo '</table>';

	}
}

function sb_elem_cfd_meta_box_callback_debug() {

	if($data = get_post_meta( get_the_ID(), 'sb_elem_cfd', true )) {
		echo '<div style="display: none; overflow: scroll;" class="sb_elem_cfd_debug">';

		echo '<pre>';
		print_r( $data );
		echo '</pre>';

		echo '</div>';

		echo '<p><a onclick="jQuery(\'.sb_elem_cfd_debug\').slideToggle();" class="button-secondary">Reveal Debug/Server Information</a></p>';
	}

}

function sb_elem_cfd_pt_init() {
	$sb_elem_cfd = get_option( 'sb_elem_cfd' );
	$title_singular    = (isset( $sb_elem_cfd['title_singular'] ) ? $sb_elem_cfd['title_singular'] : _x( 'Elementor DB', 'post type singular name', 'sb-elementor' ));
	$title_plural    = (isset( $sb_elem_cfd['title_plural'] ) ? $sb_elem_cfd['title_plural'] : $title_singular);

	$labels = array(
		'name'               => $title_plural,
		'singular_name'      => $title_singular,
		'menu_name'          => $title_plural,
		'name_admin_bar'     => $title_plural,
		'add_new'            => _x( 'Add New', 'Elementor DB', 'sb-elementor' ),
		'add_new_item'       => __( 'Add New', 'sb-elementor' ),
		'new_item'           => __( 'New', 'sb-elementor' ) . ' ' . $title_singular,
		'edit_item'          => __( 'Edit', 'sb-elementor' ) . ' ' . $title_singular,
		'view_item'          => __( 'View', 'sb-elementor' ) . ' ' . $title_singular,
		'all_items'          => __( 'All', 'sb-elementor' ) . ' ' . $title_singular,
		'search_items'       => __( 'Search', 'sb-elementor' ) . ' ' . $title_singular,
		'parent_item_colon'  => __( 'Parent:', 'sb-elementor' ) . ' ' . $title_singular,
		'not_found'          => __( 'No contact form submissions found.', 'sb-elementor' ),
		'not_found_in_trash' => __( 'No contact form submissions found in Trash.', 'sb-elementor' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'For storing Elementor contact form submissions.', 'sb-elementor' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-admin-comments',
		'supports'           => array('title')
	);

	register_post_type( 'elementor_cf_db', $args );
}

function sb_elem_cfd_new_record($record, $form_class) {

	if($fields = $record->get_formatted_data()) {
		$data  = array();
		$email = false;

		foreach($fields as $label => $value) {

			if(stripos( $label, 'email' ) !== false) {
				$email = $value;
			}

			$data[] = array('label' => $label, 'value' => sanitize_text_field( $value ));
		}

		$this_page    = get_post( $_POST['post_id'] );
		$this_user    = false;
		$current_user = get_current_user_id();

		if($this_user_id = ($current_user ? $current_user : 0)) {
			if($this_user = get_userdata( $this_user_id )) {
				$this_user = $this_user->display_name;
			}
		}

		$extra = array(
			'submitted_on'    => $this_page->post_title,
			'submitted_on_id' => $this_page->ID,
			'submitted_by'    => $this_user,
			'submitted_by_id' => $this_user_id
		);

		$db_ins = array(
			'post_title'  => $record->get_form_settings( 'form_name' ) . ' - ' . date( 'Y-m-d H:i:s' ),
			'post_status' => 'publish',
			'post_type'   => 'elementor_cf_db',
		);

		// Insert the post into the database
		if($post_id = wp_insert_post( $db_ins )) {
			update_post_meta( $post_id, 'sb_elem_cfd', array(
				'data'            => $data,
				'extra'           => $extra,
				'fields_original' => array('form_fields' => $record->get_form_settings( 'form_fields' )),
				'record_original' => $record,
				'post'            => array_map( 'sanitize_text_field', $_POST ),
				'server'          => $_SERVER
			) );

			if($this_user_id) {
				update_post_meta( $post_id, 'sb_elem_cfd_submitted_by', $this_user_id );
			}

			update_post_meta( $post_id, 'sb_elem_cfd_read', 0 );
			update_post_meta( $post_id, 'sb_elem_cfd_email', $email );
			update_post_meta( $post_id, 'sb_elem_cfd_form_id', $record->get_form_settings( 'form_name' ) );
			update_post_meta( $post_id, 'sb_elem_cfd_submitted_on_id', $this_page->ID );

		}
	}
}
