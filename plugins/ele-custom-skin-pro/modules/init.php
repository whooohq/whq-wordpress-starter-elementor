<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'elementor/frontend/before_enqueue_scripts', 'ecspro_enqueue_scripts' );
/**
 * Enqueue plugin scripts only with elementor scripts
 *
 * @return void
 */
function ecspro_enqueue_scripts() {
  wp_enqueue_script(
    'ecspro',
    ELECSP_URL.'assets/js/ecspro.js',
    array( 'jquery', 'elementor-frontend' ),
    ELECSP_VER,
    true
  );
}