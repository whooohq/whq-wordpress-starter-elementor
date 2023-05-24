<?php
/**
 * Min/Max date period info template
 */
?>
<div class="min-max-date-period-info">
<?php
	printf( '%s - <strong>"2020-05-25"</strong><br>', __( 'To set the limit by date, fill in the date in the following format', 'jet-smart-filters' ) );
	printf( '%s - <strong>"today"</strong><br>', __( 'To set the limit by the current date, fill in', 'jet-smart-filters' ) );
	echo __( 'Leave the field empty if there is no limitation needed', 'jet-smart-filters' );
?>
</div>