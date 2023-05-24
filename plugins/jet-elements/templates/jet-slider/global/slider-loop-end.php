<?php
/**
 * Slider wrap end template
 */
?></div>
</div>
<?php
    if ( filter_var( $this->get_settings_for_display( 'fraction_pagination' ), FILTER_VALIDATE_BOOLEAN ) ) {
		echo '<div class="jet-slider__fraction-pagination"></div>';
	}
?>