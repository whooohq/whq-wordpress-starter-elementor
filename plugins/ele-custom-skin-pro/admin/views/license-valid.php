<div class="wrap">
 
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        The license is valid.
  
    <form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
 
                    <input type="hidden" name="elecs-license" value="" />
 
        <?php
            wp_nonce_field( 'elecs-settings-save', 'elecs-custom-message' );
            submit_button('Deactivate License!');
        ?>
 
    </form>
  
  </div><!-- .wrap -->