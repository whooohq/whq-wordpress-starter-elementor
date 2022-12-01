<cx-vui-switcher
	name="<?php echo $args['name'] . '-border-switch'; ?>"
	label="<?php echo sprintf( esc_html__( '%s border settings', 'jet-menu' ), $args['label'] ); ?>"
	description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/border' target='_blank'>border</a>"
	:wrapper-css="[ 'equalwidth' ]"
	return-true="true"
	return-false="false"
	v-model="pageOptions['<?php echo $args['name'] . '-border-switch'; ?>']['value']"
>
</cx-vui-switcher>

<cx-vui-component-wrapper
	:wrapper-css="[ 'fullwidth-control', 'group' ]"
	:conditions="[
		{
			input: this.pageOptions['<?php echo $args['name'] . '-border-switch'; ?>']['value'],
			compare: 'equal',
			value: 'true',
		}
	]"
>
	<cx-vui-select
		name="<?php echo $args['name'] . '-border-style'; ?>"
		label="<?php echo sprintf( esc_html__( '%s border style', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/border-style' target='_blank'>border style</a>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions['<?php echo $args['name'] . '-border-style'; ?>']['options']"
		v-model="pageOptions['<?php echo $args['name'] . '-border-style'; ?>']['value']"
	>
	</cx-vui-select>

	<cx-vui-dimensions
		name="<?php echo $args['name'] . '-border-width'; ?>"
		label="<?php echo sprintf( esc_html__( '%s border width', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/border-width' target='_blank'>border width</a>"
		:wrapper-css="[ 'equalwidth' ]"
		v-model="pageOptions['<?php echo $args['name'] . '-border-width'; ?>']['value']"
	>
	</cx-vui-dimensions>

	<cx-vui-colorpicker
		name="<?php echo $args['name'] . '-border-color'; ?>"
		label="<?php echo sprintf( esc_html__( '%s border color', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		v-model="pageOptions['<?php echo $args['name'] . '-border-color'; ?>']['value']"
	></cx-vui-colorpicker>

</cx-vui-component-wrapper>

