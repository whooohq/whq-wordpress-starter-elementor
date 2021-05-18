jQuery(document).ready(function($) {
    
    var sb_elementor_cfd_div = jQuery('#poststuff').detach();
    
    sb_elementor_cfd_div.insertAfter('form#post');
    
    jQuery('form#post').remove();
    
    jQuery( ".sb_elem_cfd_convert" ).wrap( '<form class="sb_elem_cfd_copy_form" method="POST"></form>' );
});