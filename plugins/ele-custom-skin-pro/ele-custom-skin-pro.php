<?php
/*
 * Plugin Name: Ele Custom Skin Pro
 * Version: 3.1.0
 * Description: Elementor Custom Skin Pro adds more functionality to the Ele Custom Skin: alternating templates inside a loop, dynamic anywhere, custom dynamic values and many more.
 * Plugin URI: https://dudaster.com
 * Author: Dudaster.com
 * Author URI: https://dudaster.com
 * Text Domain: ele-custom-skin-pro
 * Domain Path: /languages
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Elementor tested up to: 3.2.0
 * Elementor Pro tested up to: 3.2.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'ELECSP_DIR', plugin_dir_path( __FILE__ ));
define ('ELECSP_VER','3.1.0');
define ('ELECSP_PID','ecsprou');
define( 'ELECSP_URL', plugin_dir_url( __FILE__ ));
define ('ELECSP_SRV','https://dudaster.com/');
define ('ELECSP_SBK','http://dudaster.1002.ro/');
add_action('elementor/widgets/widgets_registered','ele_custom_skin_pro');


function ele_custom_skin_pro(){ 
	require_once ELECSP_DIR.'skin/skin-custom.php';
}

add_action('init', 'elecs_activate_au');
function elecs_activate_au()
{ 
//some people are having problems connecting to dudaster.com
  if (isset($_GET['swtichsrv']) && $_GET['swtichsrv']) elecs_switch_server();
      
  require_once (ELECSP_DIR.'update.php');
  $elecs_plugin_current_version = ELECSP_VER;
  $elecs_plugin_remote_path = ecssrv().'update.php';
  $elecs_plugin_slug = plugin_basename(__FILE__);
  new ecs_update ($elecs_plugin_current_version, $elecs_plugin_remote_path, $elecs_plugin_slug);
}


function ecssrv(){
  return get_option(  'elecs-server-check', '') ? get_option(  'elecs-server-check', '' ) : ELECSP_SRV;
}

function elecs_switch_server(){
  if (get_option(  'elecs-server-check', '') == ELECSP_SBK){
    update_option( 'elecs-server-check', ELECSP_SRV );
  } else {
    update_option( 'elecs-server-check', ELECSP_SBK );    
  }
}

require_once (ELECSP_DIR.'license.php');

function elecspro_action_links( $links ) {
	$links = array_merge($links, array(
		'<a href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=theme&elementor_library_type=loop' ) ) . '">' . __( 'Add Loop Template', 'ele-custom-skin' ) . '</a>',
	));
  
  if (!get_option(  'elecs-license-key', '')) $links = array_merge($links, array(
      '<a href="' . esc_url( admin_url( '/options-general.php?page=elecsp-admin-page' ) ) . '" aria-label="' . esc_attr__( 'Activate License!', 'ele-custom-skin' ) . '" style="color:red;font-weight:bold;">' . esc_html__( 'Activate License!', 'ele-custom-skin' ) . '</a>'
	));
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'elecspro_action_links' );

foreach ( glob( plugin_dir_path( __FILE__ ) . 'modules/*.php' ) as $file ) {
      include_once $file;
}