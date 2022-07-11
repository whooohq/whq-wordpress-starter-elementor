<?php
/**
 * Single date template
 */
?>
<td class="jet-calendar-week__day<?php echo $padclass; ?>">
	<div class="jet-calendar-week__day-wrap">
		<div class="jet-calendar-week__day-header">
			<div class="jet-calendar-week__day-date"><?php echo $num; ?></div>
		</div>
		<?php
			if ( ! empty( $posts ) || ! empty( $current_multiday_events ) ) {
				echo '<div class="jet-calendar-week__day-mobile-wrap">';
					echo '<div class="jet-calendar-week__day-mobile-overlay"></div>';
					echo '<div class="jet-calendar-week__day-mobile-trigger"></div>';
				echo '</div>';
			}
		?>
		<div class="jet-calendar-week__day-content">
		<?php
			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {

					$this->maybe_set_listing( $settings['lisitng_id'] );
					$content = jet_engine()->frontend->get_listing_item( $post );

					$result = sprintf(
						'<div class="jet-calendar-week__day-event jet-listing-dynamic-post-%2$s" data-post-id="%2$s">%1$s</div>',
						$content,
						$post->ID
					);

					echo $result;

					if ( isset( $this->posts_cache[ $post->ID ] ) ) {
						$this->posts_cache[ $post->ID ] = $result;
					}

				}
			}

			if ( ! empty( $current_multiday_events ) ) {
				foreach ( $current_multiday_events as $post_id ) {
					if ( ! empty( $this->posts_cache[ $post_id ] ) ) {
						echo $this->posts_cache[ $post_id ];
					} else {

						$post = get_post( $post_id );

						if ( $post ) {
							$this->maybe_set_listing( $settings['lisitng_id'] );
							$content = jet_engine()->frontend->get_listing_item( $post );

							$result = sprintf(
								'<div class="jet-calendar-week__day-event jet-listing-dynamic-post-%2$s" data-post-id="%2$s">%1$s</div>',
								$content,
								$post->ID
							);

							echo $result;
							$this->posts_cache[ $post->ID ] = $result;
						}


					}
				}
			}

		?>
		</div>
	</div>
</td>