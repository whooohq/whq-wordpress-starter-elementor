<?php
/**
 * Loop item template
 */
?>
<figure class="jet-banner jet-effect-<?php $this->_html( 'animation_effect', '%s' ); ?>"><?php
	$target = $this->_get_html( 'banner_link_target', ' target="%s"' );
	$rel = $this->_get_html( 'banner_link_rel', ' rel="%s"' );

	$this->_html( 'banner_link', '<a href="%s" class="jet-banner__link"' . $target . $rel . '>' );
		echo '<div class="jet-banner__overlay"></div>';
		echo $this->_get_banner_image();
		echo '<figcaption class="jet-banner__content">';
			echo '<div class="jet-banner__content-wrap">';
				$title_tag = $this->_get_html( 'banner_title_html_tag', '%s' );
				$title_tag = jet_elements_tools()->validate_html_tag( $title_tag );

				$this->_html( 'banner_title', '<' . $title_tag  . ' class="jet-banner__title">%s</' . $title_tag  . '>' );
				$this->_html( 'banner_text', '<div class="jet-banner__text">%s</div>' );
			echo '</div>';
		echo '</figcaption>';
	$this->_html( 'banner_link', '</a>' );
?></figure>
