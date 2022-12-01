<?php
/**
 * Loop item title
 */

if ( 'yes' !== $this->get_attr( 'show_title' ) ) {
	$this->render_meta( 'title_related', 'jet-title-fields', array( 'before', 'after' ) );
	return;
}

$title_length   = -1;
$title_ending   = $this->get_attr( 'title_trimmed_ending_text' );
$title_html_tag = ! empty( $this->get_attr( 'title_html_tag' ) ) ? $this->get_attr( 'title_html_tag' ) : 'h4';

if ( filter_var( $this->get_attr( 'title_trimmed' ), FILTER_VALIDATE_BOOLEAN ) ) {
	$title_length = $this->get_attr( 'title_length' );
}

$this->render_meta( 'title_related', 'jet-title-fields', array( 'before' ) );

if ( 'yes'  === $this->get_attr( 'open_new_tab' ) ) {
	$target = "_blank";
} else {
	$target = '';
}

jet_elements_post_tools()->get_post_title( array(
	'class'  => 'entry-title',
	'html'   => '<' . $title_html_tag . ' %1$s><a href="%2$s" target="' . $target . '">%4$s</a></' . $title_html_tag . '>',
	'length' => $title_length,
	'ending' => $title_ending,
	'echo'   => true,
) );

$this->render_meta( 'title_related', 'jet-title-fields', array( 'after' ) );
