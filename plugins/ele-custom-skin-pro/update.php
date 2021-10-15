<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define ('ELECSP_KEY',get_option(  'elecs-license-key', ''));

// see https://code.tutsplus.com/tutorials/a-guide-to-the-wordpress-http-api-automatic-plugin-updates--wp-25181 and see updata.php on your server

class ecs_update
{
    /**
     * The plugin current version
     * @var string
     */
    public $current_version;
 
    /**
     * The plugin remote update path
     * @var string
     */
    public $update_path;
 
    /**
     * Plugin Slug (plugin_directory/plugin_file.php)
     * @var string
     */
    public $plugin_slug;
 
    /**
     * Plugin name (plugin_file)
     * @var string
     */
    public $slug;
 
    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $update_path
     * @param string $plugin_slug
     */
    function __construct($current_version, $update_path, $plugin_slug)
    {
        // Set the class public variables
        $this->current_version = $current_version;
        $this->update_path = $update_path;
        $this->plugin_slug = $plugin_slug;
        list ($t1, $t2) = explode('/', $plugin_slug);
        $this->slug = str_replace('.php', '', $t2);
 
        // define the alternative API for updating checking
        add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));
 
        // Define the alternative response for information checking
        add_filter('plugins_api', array(&$this, 'check_info'), 10, 3);
    }
 
    /**
     * Add our self-hosted autoupdate plugin to the filter transient
     *
     * @param $transient
     * @return object $ transient
     */
    public function check_update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }
 
        // Get the remote version
         $information = $this->getRemote_information();
          
        // If a newer version is available, add the update
        if (version_compare($this->current_version, $information->new_version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->new_version = $information->new_version;
            $obj->url = $information->homepage;//$this->update_path;
            $obj->plugin = $information->download_link;//$this->update_path;
            $obj->package = $information->download_link;//$this->update_path;
            $obj->icons = ['1x'=>$information->icons['1x'],
                          '2x'=>$information->icons['2x']];            
            $transient->response[$this->plugin_slug] = $obj;
        }
        //var_dump($transient);
        return $transient;
    }
 
    /**
     * Add our self-hosted description to the filter
     *
     * @param boolean $false
     * @param array $action
     * @param object $arg
     * @return bool|object
     */
    public function check_info($false, $action, $arg)
    {
      if (!isset($arg->slug)) return false;  
      if ($arg->slug === $this->slug) {
            $information = $this->getRemote_information();
            return $information;
        }
        return false;
    }
 
    /**
     * Return the remote version
     * @return string $remote_version
     */
    public function getRemote_version()
    {
       $information = $this->getRemote_information();
      return $information->new_version;
    }
 
    /**
     * Get information about the remote version
     * @return bool|object
     */
    public function getRemote_information()
    {
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'info','pid' => ELECSP_PID, 'key' => ELECSP_KEY)));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
   //       print_r(unserialize($request['body']));
            return unserialize($request['body']);
        }
        return false;
    }
 
}