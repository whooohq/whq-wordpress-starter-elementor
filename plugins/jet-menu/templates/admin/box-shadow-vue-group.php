<cx-vui-switcher
	name="<?php echo $args['name'] . '-box-shadow-switch'; ?>"
	label="<?php echo sprintf( esc_html__( '%s box shadow settings', 'jet-menu' ), $args['label'] ); ?>"
	description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/box-shadow' target='_blank'>box shadow</a>"
	:wrapper-css="[ 'equalwidth' ]"
	return-true="true"
	return-false="false"
	v-model="pageOptions['<?php echo $args['name'] . '-box-shadow-switch'; ?>']['value']"
>
</cx-vui-switcher>

<cx-vui-component-wrapper
	:wrapper-css="[ 'fullwidth-control', 'group' ]"
	:conditions="[
		{
			input: this.pageOptions['<?php echo $args['name'] . '-box-shadow-switch'; ?>']['value'],
			compare: 'equal',
			value: 'true',
		}
	]"
>

	<cx-vui-switcher
		name="<?php echo $args['name'] . '-box-shadow-inset'; ?>"
		label="<?php echo sprintf( esc_html__( '%s shadow inset', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="true"
		return-false="false"
		v-model="pageOptions['<?php echo $args['name'] . '-box-shadow-inset'; ?>']['value']"
	>
	</cx-vui-switcher>

	<cx-vui-colorpicker
		name="<?php echo $args['name'] . '-box-shadow-color'; ?>"
		label="<?php echo sprintf( esc_html__( '%s shadow color', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		v-model="pageOptions['<?php echo $args['name'] . '-box-shadow-color'; ?>']['value']"
	></cx-vui-colorpicker>

	<cx-vui-input
		name="<?php echo $args['name'] . '-box-shadow-h'; ?>"
		label="<?php echo sprintf( esc_html__( '%s - position of the horizontal shadow', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		type="number"
		:min="-50"
		:max="50"
		v-model="pageOptions['<?php echo $args['name'] . '-box-shadow-h'; ?>']['value']"
	>
	</cx-vui-input>

	<cx-vui-input
		name="<?php echo $args['name'] . '-box-shadow-v'; ?>"
		label="<?php echo sprintf( esc_html__( '%s - position of the vertical shadow', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		type="number"
		:min="-50"
		:max="50"
		v-model="pageOptions['<?php echo $args['name'] . '-box-shadow-v'; ?>']['value']"
	>
	</cx-vui-input>

	<cx-vui-input
		name="<?php echo $args['name'] . '-box-shadow-blur'; ?>"
		label="<?php echo sprintf( esc_html__( '%s - shadow blur distance', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		type="number"
		:min="-50"
		:max="50"
		v-model="pageOptions['<?php echo $args['name'] . '-box-shadow-blur'; ?>']['value']"
	>
	</cx-vui-input>

	<cx-vui-input
		name="<?php echo $args['name'] . '-box-shadow-spread'; ?>"
		label="<?php echo sprintf( esc_html__( '%s - shadow size', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		type="number"
		:min="-50"
		:max="50"
		v-model="pageOptions['<?php echo $args['name'] . '-box-shadow-spread'; ?>']['value']"
	>
	</cx-vui-input>

</cx-vui-component-wrapper>

