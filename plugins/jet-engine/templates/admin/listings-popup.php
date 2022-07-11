<?php
/**
 * Template type popup
 */
?>
<div class="jet-listings-popup" style="display: none;">
	<div class="jet-listings-popup__overlay"></div>
	<div class="jet-listings-popup__content">
		<div class="jet-listings-popup__close">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"/></g></svg>
		</div>
		<h3 class="jet-listings-popup__heading"><?php
			esc_html_e( 'Setup Listing Item', 'jet-engine' );
		?></h3>
		<form class="jet-listings-popup__form" id="templates_type_form" method="POST" action="<?php echo $action; ?>" >
			<div class="jet-listings-popup__form-row">
				<label for="listing_source"><?php esc_html_e( 'Listing source:', 'jet-engine' ); ?></label>
				<select id="listing_source" name="listing_source"><?php
					foreach ( $sources as $source_key => $source_label ) {
						printf( '<option value="%1$s">%2$s</option>', $source_key, $source_label );
					}
				?></select>
			</div>
			<div class="jet-listings-popup__form-row jet-template-listing jet-template-posts jet-template-repeater jet-template-act">
				<label for="listing_post_type"><?php esc_html_e( 'From post type:', 'jet-engine' ); ?></label>
				<select id="listing_post_type" name="listing_post_type"><?php
					foreach ( jet_engine()->listings->get_post_types_for_options() as $key => $value ) {
						printf( '<option value="%1$s">%2$s</option>', $key, $value );
					}
				?></select>
			</div>
			<div class="jet-listings-popup__form-row jet-template-listing jet-template-terms">
				<label for="listing_tax"><?php esc_html_e( 'From taxonomy:', 'jet-engine' ); ?></label>
				<select id="listing_tax" name="listing_tax"><?php
					foreach ( jet_engine()->listings->get_taxonomies_for_options() as $key => $value ) {
						printf( '<option value="%1$s">%2$s</option>', $key, $value );
					}
				?></select>
			</div>
			<div class="jet-listings-popup__form-row jet-template-listing jet-template-query">
				<label for="query_id"><?php esc_html_e( 'Query:', 'jet-engine' ); ?></label>
				<select id="query_id" name="query_id">
					<?php
						foreach ( \Jet_Engine\Query_Builder\Manager::instance()->get_queries_for_options() as $query_id => $query_name ) {
							printf( '<option value="%1$s">%2$s</option>', $query_id, $query_name );
						}
					?>
				</select>
			</div>
			<div class="jet-listings-popup__form-row jet-template-listing jet-template-repeater">
				<label for="repeater_source"><?php esc_html_e( 'Repeater source:', 'jet-engine' ); ?></label>
				<select id="repeater_source" name="repeater_source"><?php
					foreach ( jet_engine()->listings->repeater_sources() as $source_id => $source_name ) {
						printf( '<option value="%1$s">%2$s</option>', $source_id, $source_name );
					}
				?></select>
			</div>
			<div class="jet-listings-popup__form-row jet-template-listing jet-template-repeater">
				<div class="jet-listings-popup__form-cols">
					<div class="jet-listings-popup__form-col">
						<label for="repeater_field">
							<?php esc_html_e( 'Repeater field:', 'jet-engine' ); ?><br>
							<small><?php _e( 'if JetEngine, or ACF, or etc selected as source', 'jet-engine' ); ?></small>
						</label>
						<input type="text" id="repeater_field" name="repeater_field" placeholder="<?php esc_html_e( 'Set repeater field name', 'jet-engine' ); ?>">
					</div>
					<div class="jet-listings-popup__form-delimiter">
						- <?php _e( 'or', 'jet-engine' ); ?> -
					</div>
					<div class="jet-listings-popup__form-col">
						<label for="repeater_option">
							<?php esc_html_e( 'Repeater option:', 'jet-engine' ); ?><br>
							<small><?php _e( 'if <b>JetEngine Options Page</b> selected as source', 'jet-engine' ); ?></small>
						</label>
						<select id="repeater_option" name="repeater_option">
							<option value="">--</option>
							<?php
							foreach ( jet_engine()->options_pages->get_options_for_select( 'repeater' ) as $group ) {

								if ( empty( $group ) || empty( $group['options'] ) ) {
									continue;
								}

								echo '<optgroup label="' . $group['label'] . '">';
								foreach ( $group['options'] as $opt_key => $opt_name ) {
									printf( '<option value="%1$s">%2$s</option>', $opt_key, $opt_name );
								}
								echo '</optgroup>';
							}
						?></select>
					</div>
				</div>
			</div>
			<?php do_action( 'jet-engine/templates/listing-options' ); ?>
			<div class="jet-listings-popup__form-row">
				<label for="template_name"><?php esc_html_e( 'Listing item name:', 'jet-engine' ); ?></label>
				<input type="text" id="template_name" name="template_name" placeholder="<?php esc_html_e( 'Set listing name', 'jet-engine' ); ?>">
			</div>
			<div class="jet-listings-popup__form-row">
				<label for="listing_view_type"><?php esc_html_e( 'Listing view:', 'jet-engine' ); ?></label>
				<select id="listing_view_type" name="listing_view_type"><?php
					foreach ( $views as $view_key => $view_label ) {
						printf( '<option value="%1$s">%2$s</option>', $view_key, $view_label );
					}
				?></select>
			</div>
			<div class="jet-listings-popup__form-actions">
				<button type="submit" id="templates_type_submit" class="button button-primary button-hero"><?php
					esc_html_e( 'Create Listing Item', 'jet-engine' );
				?></button>
			</div>
		</form>
	</div>
</div>
