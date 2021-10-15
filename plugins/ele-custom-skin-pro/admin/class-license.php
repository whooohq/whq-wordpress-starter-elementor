<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ecs_license
{
 
    /**
     * The plugin remote update path
     * @var string
     */
    public $check_path;
 

  
    /**
     * Product ID (which product should we check license to)
     * @var string
     */
    public $pid;
 
    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $check_path
     * @param string $plugin_slug
     */
    function __construct($check_path, $pid)
    {
        // Set the class public variables
        $this->check_path = $check_path;
        $this->pid = $pid;
     }
 
 
    /**
     * Return the status of the plugin licensing
     * @return boolean $remote_license
     */
    public function getRemote_license($key="")
    {
        $key = $key ? $key : get_option(  'elecs-license-key', '');
        $request = wp_remote_post($this->check_path, array('body' => array('action' => 'license', 'key' => $key, 'pid'=>$this->pid)));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return $request['body'];
        }
        return false;
    }
}