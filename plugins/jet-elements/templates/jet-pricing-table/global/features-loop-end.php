<?php
/**
 * Features list end template
 */
?>
	</div>
	<?php if ( $fold_enabled ) : ?>

		<div class="pricing-table__fold-trigger">
			<?php $this->_glob_inc_if( 'fold-button', array(
				$this->_new_icon_prefix . 'button_fold_icon',
				'button_fold_text',
				$this->_new_icon_prefix . 'button_unfold_icon',
				'button_unfold_text',
			) ); ?>
		</div>

	<?php endif;?>
</div>