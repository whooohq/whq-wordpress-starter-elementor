<?php
/**
 * Active tag item content
 */

?>
/% if ($label) { %/
	<div class="jet-active-tag__label">/% $label %/<span class="jet-active-tag__label-separator">:</span></div>
/% } %/
/% if ($value) { %/
	<div class="jet-active-tag__val">/% $value %/</div>
/% } %/
<div class="jet-active-tag__remove">&times;</div>