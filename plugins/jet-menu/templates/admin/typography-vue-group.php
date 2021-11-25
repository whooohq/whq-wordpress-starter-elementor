<cx-vui-switcher
	name="<?php echo $args['name'] . '-switch'; ?>"
	label="<?php echo sprintf( esc_html__( '%s typography settings', 'jet-menu' ), $args['label'] ); ?>"
	description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://www.w3schools.com/css/css_font.asp' target='_blank'>typography</a>"
	:wrapper-css="[ 'equalwidth' ]"
	return-true="true"
	return-false="false"
	v-model="pageOptions['<?php echo $args['name'] . '-switch'; ?>']['value']"
>
</cx-vui-switcher>

<cx-vui-component-wrapper
	:wrapper-css="[ 'fullwidth-control', 'group' ]"
	:conditions="[
		{
			input: this.pageOptions['<?php echo $args['name'] . '-switch'; ?>']['value'],
			compare: 'equal',
			value: 'true',
		}
	]"
>
	<cx-vui-select
		name="<?php echo $args['name'] . '-font-family'; ?>"
		label="<?php echo sprintf( esc_html__( '%s font family', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions['<?php echo $args['name'] . '-font-family'; ?>']['options']"
		v-model="pageOptions['<?php echo $args['name'] . '-font-family'; ?>']['value']"
	>
	</cx-vui-select>

	<cx-vui-select
		name="<?php echo $args['name'] . '-subset'; ?>"
		label="<?php echo sprintf( esc_html__( '%s subset', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://www.w3schools.com/cssref/pr_font_font-style.asp' target='_blank'>font style</a"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions['<?php echo $args['name'] . '-subset'; ?>']['options']"
		v-model="pageOptions['<?php echo $args['name'] . '-subset'; ?>']['value']"
	>
	</cx-vui-select>

	<cx-vui-input
		name="<?php echo $args['name'] . '-font-size'; ?>"
		label="<?php echo sprintf( esc_html__( '%s font size(px)', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://www.w3schools.com/cssref/pr_font_font-size.asp' target='_blank'>font size</a>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		type="number"
		:min="8"
		:max="70"
		v-model="pageOptions['<?php echo $args['name'] . '-font-size'; ?>']['value']"
	>
	</cx-vui-input>

	<cx-vui-input
		name="<?php echo $args['name'] . '-line-height'; ?>"
		label="<?php echo sprintf( esc_html__( '%s line height(em)', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about ', 'jet-menu' ); ?> <a href='https://www.w3schools.com/cssref/pr_dim_line-height.asp' target='_blank'>line height</a>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		type="number"
		:min="0.1"
		:max="10"
		:step="0.1"
		v-model="pageOptions['<?php echo $args['name'] . '-line-height'; ?>']['value']"
	>
	</cx-vui-input>

	<cx-vui-input
		name="<?php echo $args['name'] . '-letter-spacing'; ?>"
		label="<?php echo sprintf( esc_html__( '%s letter spacing', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://www.w3schools.com/cssref/pr_text_letter-spacing.asp' target='_blank'>letter spacing</a>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		type="number"
		:min="-10"
		:max="10"
		v-model="pageOptions['<?php echo $args['name'] . '-letter-spacing'; ?>']['value']"
	>
	</cx-vui-input>

	<cx-vui-select
		name="<?php echo $args['name'] . '-font-weight'; ?>"
		label="<?php echo sprintf( esc_html__( '%s font weight', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://www.w3schools.com/cssref/pr_font_weight.asp' target='_blank'>font weight</a>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions['<?php echo $args['name'] . '-font-weight'; ?>']['options']"
		v-model="pageOptions['<?php echo $args['name'] . '-font-weight'; ?>']['value']"
	>
	</cx-vui-select>

	<cx-vui-select
		name="<?php echo $args['name'] . '-text-transform'; ?>"
		label="<?php echo sprintf( esc_html__( '%s text transform', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://www.w3schools.com/cssref/pr_text_text-transform.asp' target='_blank'>text transform</a>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions['<?php echo $args['name'] . '-text-transform'; ?>']['options']"
		v-model="pageOptions['<?php echo $args['name'] . '-text-transform'; ?>']['value']"
	>
	</cx-vui-select>

	<cx-vui-select
		name="<?php echo $args['name'] . '-font-style'; ?>"
		label="<?php echo sprintf( esc_html__( '%s font style', 'jet-menu' ), $args['label'] ); ?>"
		description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://www.w3schools.com/cssref/pr_font_font-style.asp' target='_blank'>font style</a>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions['<?php echo $args['name'] . '-font-style'; ?>']['options']"
		v-model="pageOptions['<?php echo $args['name'] . '-font-style'; ?>']['value']"
	>
	</cx-vui-select>

</cx-vui-component-wrapper>

