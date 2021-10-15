<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ecs_serializer {
  
     /**
     * A reference the class responsible for license verification.
     *
     * @var    license
     * @access private
     */
    private $license;
  
    /**
     * Initializes all of the partial classes.
     *
     * @param license  A reference to the class that handles the license
     */
    public function __construct($license ) {
        $this->license = $license;
    }
 
    /**
     * Initializes the function by registering the save function with the
     * admin_post hook so that we can save our options to the database.
     */
    public function init() {
        add_action( 'admin_post', array( $this, 'save' ) );
    }
 
    /**
     * Validates the incoming nonce value, verifies the current user has
     * permission to save the value from the options page and saves the
     * option to the database.
     */
    public function save() {
 
        // First, validate the nonce and verify the user as permission to save.
        if ( ! ( $this->has_valid_nonce() && current_user_can( 'manage_options' ) ) ) {
            // TODO: Display an error message.
        }
        $msg='';
        // If the above are valid, sanitize and save the option.
        if ( null !== wp_unslash( $_POST['elecs-license'] ) ) {
 
            $value = sanitize_text_field( $_POST['elecs-license'] );
            if ($this->license->getRemote_license($value)=="success") 
                  update_option( 'elecs-license-key', $value );
            else {
                  update_option( 'elecs-license-key', '' );
                  $msg="invalid";
              add_action( 'admin_notices', 'sample_admin_notice__error' );
            }
 
        }
        $this->redirect($msg);
 
    }
  
    /**
     * Determines if the nonce variable associated with the options page is set
     * and is valid.
     *
     * @access private
     *
     * @return boolean False if the field isn't set or the nonce value is invalid;
     *                 otherwise, true.
     */
    private function has_valid_nonce() {
 
        // If the field isn't even in the $_POST, then it's invalid.
        if ( ! isset( $_POST['elecs-custom-message'] ) ) { // Input var okay.
            return false;
        }
 
        $field  = wp_unslash( $_POST['elecs-custom-message'] );
        $action = 'elcs-settings-save';
 
        return wp_verify_nonce( $field, $action );
 
    }
 
    /**
     * Redirect to the page from which we came (which should always be the
     * admin page. If the referred isn't set, then we redirect the user to
     * the login page.
     *
     * @access private
     */
    private function redirect($msg='') {
 
        $msg = $msg ? '&msg='.$msg : '';
        // To make the Coding Standards happy, we have to initialize this.
        if ( ! isset( $_POST['_wp_http_referer'] ) ) { // Input var okay.
            $_POST['_wp_http_referer'] = wp_login_url();
        }
 
        // Sanitize the value of the $_POST collection for the Coding Standards.
        $url = sanitize_text_field(
                wp_unslash( $_POST['_wp_http_referer'] ) // Input var okay.
        );
 
        // Finally, redirect back to the admin page.
        wp_safe_redirect( urldecode( $url.$msg ) );
        exit;
 
    }
}
