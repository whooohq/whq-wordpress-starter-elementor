<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
    include_once $file;
}
//include_once( plugin_dir_path( __FILE__ ) . 'shared/class-deserializer.php' );
add_action( 'plugins_loaded', 'elecs_custom_admin_settings' );
/**
 * Starts the plugin.
 *
 * @since 1.0.0
 */
function elecs_custom_admin_settings() {
    
    $elecs_plugin_remote_path = ecssrv().'license.php';
    $license = new ecs_license($elecs_plugin_remote_path, ELECSP_PID);
    $serializer = new ecs_serializer($license);
    $serializer->init();
    $plugin = new ecs_submenu( new ecs_license_page($license));
    $plugin->init();
 
}


function ecs_admin_notice_pro(){
    if (defined('ELECS_VER')) return;
    $user_id = get_current_user_id();
    $image = '';
    $user = wp_get_current_user();
    if ( in_array( 'administrator', (array) $user->roles ) ) {
    echo '<div class="notice notice-error " style="padding-right: 38px; position: relative;">
          <p> '.$image.' Ele Custom Skin <b>FREE</b> must also need to be installed and active in order for the Pro to work.</p>
        <a href="?ele_custom_skin_notice_dismissed"><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></a>
        </div>';
    }
}
add_action('admin_notices', 'ecs_admin_notice_pro');