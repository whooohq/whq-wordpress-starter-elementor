<?php
use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Documents\Loop;
use ElementorPro\Plugin;
use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;
use ElementorPro\Modules\ThemeBuilder\Module as ThemeBuilderModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ECS_Conditions_Manager {
  
 	/**
	 * All condition cached by template
	 */
	private $conditions = [];
  private $conditions_manager;
  private $loop_templates = [];
  public function __construct() {
        $this->conditions_manager = ThemeBuilderModule::instance()->get_conditions_manager();
        $this->all_loop_templates();
        $this->cache_conditions();
  }
  //get all the templates that have display conditions
  public function all_loop_templates(){
    	  global $wpdb;
				$templates = $wpdb->get_results( 
					"SELECT $wpdb->term_relationships.object_id as ID FROM $wpdb->term_relationships
						INNER JOIN $wpdb->term_taxonomy ON
							$wpdb->term_relationships.term_taxonomy_id=$wpdb->term_taxonomy.term_taxonomy_id
						INNER JOIN $wpdb->terms ON 
							$wpdb->term_taxonomy.term_id=$wpdb->terms.term_id AND $wpdb->terms.slug='loop'
						INNER JOIN $wpdb->posts ON
							$wpdb->term_relationships.object_id=$wpdb->posts.ID
          WHERE  $wpdb->posts.post_status='publish'"
				);
				$loop_templates=false;
        foreach ( $templates as $template ) {
					$loop_templates[] = $template->ID;
				}
				$this->loop_templates=$loop_templates;
  }
  // get the template id for use
  public function get_template(){
    foreach ($this->conditions as $template_id => $conditions){
      if($this->check_conditions($template_id)) return $template_id;
    }
    return false;
  }
 //not to search everytime through templates we'll doit only once. 
  public function cache_conditions(){

    foreach ($this->loop_templates as $template_id){
      $conditions_manager = $this->conditions_manager;  
      $document = ThemeBuilderModule::instance()->get_document( $template_id );
      $all_conditions = $conditions_manager->get_document_conditions($document);

      if(isset($all_conditions)) $this->conditions[$template_id] = $all_conditions; 
    }
  }
  //check each template if it applies to be shown for the current post
  public function check_conditions($template_id){
    $conditions_manager = $this->conditions_manager;  
    $all_conditions = $this->conditions[$template_id];
    $condition_pass = false;
    foreach($all_conditions as $condition){
        $include = $condition['type'];
        $name = $condition['name'];
        $sub_name = $condition['sub_name'];
        $sub_id = $condition['sub_id'];

        $is_include = 'include' === $include;
     // print_r($condition);

        $condition_instance = $conditions_manager->get_condition($name);
        if ( ! $condition_instance ) {
            continue;
        }

        $condition_pass = $condition_instance->check( [] );
        $sub_condition_instance = null;
        if (!$condition_pass && $name == 'loop' && $sub_name) $condition_pass = true;
      	if ( $condition_pass && $sub_name ) {
					$sub_condition_instance = $conditions_manager->get_condition( $sub_name );
					if ( ! $sub_condition_instance ) {
						continue;
					}

					$args = [
						'id' =>  $sub_id,
					];
          //print_r($sub_condition_instance);
					$condition_pass = $sub_condition_instance->check( $args );
				}
        if($condition_pass && !$is_include) return false;//if it is excluded don't let it show  
        //echo " a trecut "; print_r($condition_pass);
        //if($condition_pass) echo " adevart ;"; else echo" fals; ";
    }
    //echo "am terminat de verificat;";
    return $condition_pass;
  }

}
