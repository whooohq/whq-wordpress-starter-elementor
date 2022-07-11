<?php
/**
 * Active filter item content
 */

?>
/% if ($label) { %/
	<div class="jet-active-filter__label">/% $label %/</div>
/% } %/
/% if ($value) { %/
	<div class="jet-active-filter__val">/% $value %/</div>
/% } %/
<div class="jet-active-filter__remove">&times;</div>