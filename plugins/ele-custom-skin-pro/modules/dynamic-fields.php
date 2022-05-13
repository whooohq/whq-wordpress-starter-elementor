<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*-----------------------------------------------------------------------------------*/
/* Replacing the curly brakets tags with the actual values
/*-----------------------------------------------------------------------------------*/
function ecs_parse_content($t,$post=NULL,$content="",$parent_settings=[]){
	global $wp_query;
  global $ecs_index;
  $index = $ecs_index;
	if ($post!=NULL) global $post;
	if ($post->ID) $var=$post; 
		else $var=get_queried_object();
/**  Set custom vars **/
	$post_id=$post->ID;
	if($post_id) $permalink=get_permalink($post_id);
	$post_title=$var->name . $var->post_title;
	$name=isset(get_queried_object()->name) ? get_queried_object()->name : NULL;
	if ($var->term_id) $description=do_shortcode(wpautop($var->description)); else {
		if (isset($var->description)) $var->description=NULL; /// to work only with terms descriptions
	} 
	if(!is_single() && !$content) $content=$var->post_content; // if it is an elementor format it would not work... please research a little bit | nu merge sa se cheme elementor de id cand este in ea...
	$post_excerpt = get_the_excerpt() ? get_the_excerpt() : ecs_get_my_excerpt($content);
	// add your own custom vars
  $custom_vars=[];
  $custom_vars['_parent_settings']=$parent_settings;
	$custom_vars=apply_filters( 'ecs_vars', $custom_vars ); 
  foreach($custom_vars as $key=>$value){
		 $$key=$value;
	}
/** end seting custom vars **/
// replacing the keystrings from the template with the actual values. (ie for $content you have {content})
	preg_match_all('~\{\{(.*?)\}\}~si',$t,$matches);//get all the placeholders to replace them with values from.
	if ( isset($matches[1])) {
		$value="";
		foreach ($matches[1] as $key) {
			$value=isset($$key) ? $$key : $var->$key; //echo "<br/> ".$key." "; print_r($var->$key);
			if ($value=="") { //echo "<br/> ".$key." "; print_r($custom_field);
				//Daca nu a gasit nici o proprietate a obeictului cauta custom field
				if ($post->ID) {
					$custom_field=get_post_meta( $post->ID, $key, true); //echo "<br/>..".$key." :"; print_r($custom_field);
				}
				$value=$custom_field ? $custom_field : "";//pune custom field sau sa stearga keya daca nu are valoare 
				if ($value=="" && function_exists("wc_get_product_terms")) $value = array_shift( wc_get_product_terms( $post->ID, $key, array( 'fields' => 'names' ) ) ); // iau custom product attribute
				if ($value=="" && function_exists('get_field') && $var->term_id) $value = get_field($key, $var->taxonomy.'_'.$var->term_id);// iau custom field de la taxonomie
				if ($value=="") $value=$wp_query->query_vars[$key]; //get query_vars
			}
			$t = str_replace('{{'.$key.'}}',$value,$t);
		}
	}
	return $t;
}

add_filter( 'ecs_dynamic_filter', 'ecs_parse_content', 10, 4 );

if (!function_exists("parse_content")) {
  
  function parse_content($t,$post=NULL,$content=""){
     return ecs_parse_content($t,$post,$content);
  }

}

function ecs_get_my_excerpt($text){
    $raw_excerpt='';
		$text = strip_shortcodes( $text );
		$text = str_replace(']]>', ']]&gt;', $text);
		/* Remove unwanted JS code */
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		/* Strip HTML tags, but allow certain tags */
		$text = strip_tags($text);
		$excerpt_length = 55;
		$excerpt_more = '[...]';
		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode(' ', $words);
		}
	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}