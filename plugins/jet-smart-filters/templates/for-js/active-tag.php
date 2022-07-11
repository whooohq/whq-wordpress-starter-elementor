<?php
/**
 * Active tag item content
 */

?>
/% if ($label) { %/
	<div class="jet-active-tag__label">/% $label %/: </div>
/% } %/
/% if ($value) { %/
	<div class="jet-active-tag__val">/% $value %/</div>
/% } %/
<div class="jet-active-tag__remove">&times;</div>