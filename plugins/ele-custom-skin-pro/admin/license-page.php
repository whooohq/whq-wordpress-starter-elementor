<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ecs_license_page {
 
        /**
     * This function renders the contents of the page associated with the Submenu
     * that invokes the render method. In the context of this plugin, this is the
     * Submenu class.
     */
    public function render() {
      //if the licnese is not valid or not existign
       if (!get_option(  'elecs-license-key', '')) include_once( 'views/license-form.php' );
      // else tell the license is active and valid.
        else include_once( 'views/license-valid.php' );
    }
  
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
     * @param Submenu_Page $submenu_page A reference to the class that renders the
     *                                                                   page for the plugin.
     */
    public function __construct($license ) {
        $this->license = $license;
    }
}