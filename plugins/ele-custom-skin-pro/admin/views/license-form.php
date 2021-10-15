<div class="wrap">
 
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 
    <form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
 <?php if(isset($_GET['msg'])) {
  if ($_GET['msg']=='invalid'){ 
      ?>
      <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Something went wrong! Make sure you wrote the corect Key and try again! You can also <a href="'.esc_html( admin_url( 'options-general.php?page=elecsp-admin-page' ) ).'&swtichsrv=1"> switch </a> to another server <small>(current server is:'.get_option(  'elecs-server-check', '').')</small>!', 'elecspro' ); ?></p>
    </div>
  <?php }
} ?>
        <div id="universal-message-container">
            <h2>License Key</h2>
 
            <div class="options">
                <p>
                    <label>Please fill your License key below:</label>
                    <br />
                    <input type="text" size="45" name="elecs-license" value="<?php echo esc_attr( get_option(  'elecs-license-key', '')); ?>" />
                </p>
        </div><!-- #universal-message-container -->
 
        <?php
            wp_nonce_field( 'elecs-settings-save', 'elecs-custom-message' );
            submit_button('Activate License!');
        ?>
 
    </form>
 
</div><!-- .wrap -->
<?php if(isset($_GET['swtichsrv'])) {
  if ($_GET['swtichsrv']){  ?>
  <script>
    jQuery('body').load( "<?php echo esc_html( admin_url( 'options-general.php?page=elecsp-admin-page' ) ).'&swtichsrv=0'; ?>" );
  </script>
<?php  }} ?>