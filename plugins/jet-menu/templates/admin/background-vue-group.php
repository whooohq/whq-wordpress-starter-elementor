<cx-vui-switcher
	name="<?php echo $args['name'] . '-switch'; ?>"
	label="<?php echo sprintf( esc_html__( '%s background settings', 'jet-menu' ), $args['label'] ); ?>"
	description="<?php esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/background' target='_blank'>background</a> "
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
	<cx-vui-colorpicker
		name="<?php echo $args['name'] . '-color'; ?>"
		label="<?php echo sprintf( esc_html__( '%s background color', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		v-model="pageOptions['<?php echo $args['name'] . '-color'; ?>']['value']"
	></cx-vui-colorpicker>

	<cx-vui-switcher
		name="<?php echo $args['name'] . '-gradient-switch'; ?>"
		label="<?php echo sprintf( esc_html__( '%s gradient background', 'jet-menu' ), $args['label'] ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		return-true="true"
		return-false="false"
		v-model="pageOptions['<?php echo $args['name'] . '-gradient-switch'; ?>']['value']"
	>
	</cx-vui-switcher>

	<cx-vui-component-wrapper
		:wrapper-css="[ 'cx-vui-component-group', 'fullwidth-control' ]"
		:conditions="[
			{
				input: this.pageOptions['<?php echo $args['name'] . '-gradient-switch'; ?>']['value'],
				compare: 'equal',
				value: 'true',
			}
		]"
	>
		<cx-vui-colorpicker
			name="<?php echo $args['name'] . '-second-color'; ?>"
			label="<?php echo sprintf( esc_html__( '%s background second color', 'jet-menu' ), $args['label'] ); ?>"
			:wrapper-css="[ 'equalwidth' ]"
			v-model="pageOptions['<?php echo $args['name'] . '-second-color'; ?>']['value']"
		></cx-vui-colorpicker>

		<cx-vui-select
			name="<?php echo $args['name'] . '-direction'; ?>"
			label="<?php echo sprintf( esc_html__( '%s background gradient direction', 'jet-menu' ), $args['label'] ); ?>"
			:wrapper-css="[ 'equalwidth' ]"
			size="fullwidth"
			:options-list="pageOptions['<?php echo $args['name'] . '-direction'; ?>']['options']"
			v-model="pageOptions['<?php echo $args['name'] . '-direction'; ?>']['value']"
		>
		</cx-vui-select>

	</cx-vui-component-wrapper>

	<cx-vui-component-wrapper
		:wrapper-css="[ 'fullwidth-control', 'container' ]"
		:conditions="[
			{
				input: this.pageOptions['<?php echo $args['name'] . '-gradient-switch'; ?>']['value'],
				compare: 'not_equal',
				value: 'true',
			}
		]"
	>
		<cx-vui-wp-media
			name="<?php echo $args['name'] . '-image'; ?>"
			label="<?php echo sprintf( esc_html__( '%s background image', 'jet-menu' ), $args['label'] ); ?>"
			:wrapper-css="[ 'equalwidth' ]"
			v-model="pageOptions['<?php echo $args['name'] . '-image'; ?>']['value']"
			return-type="string"
			:multiple="false"
		></cx-vui-wp-media>

		<cx-vui-select
			name="<?php echo $args['name'] . '-position'; ?>"
			label="<?php echo sprintf( esc_html__( '%s background image position', 'jet-menu' ), $args['label'] ); ?>"
			description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/background-position' target='_blank'>background position</a>"
			:wrapper-css="[ 'equalwidth' ]"
			size="fullwidth"
			:options-list="pageOptions['<?php echo $args['name'] . '-position'; ?>']['options']"
			v-model="pageOptions['<?php echo $args['name'] . '-position'; ?>']['value']"
		>
		</cx-vui-select>

		<cx-vui-select
			name="<?php echo $args['name'] . '-attachment'; ?>"
			label="<?php echo sprintf( esc_html__( '%s background attachment', 'jet-menu' ), $args['label'] ); ?>"
			description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/background-attachment' target='_blank'>background attachment</a>"
			:wrapper-css="[ 'equalwidth' ]"
			size="fullwidth"
			:options-list="pageOptions['<?php echo $args['name'] . '-attachment'; ?>']['options']"
			v-model="pageOptions['<?php echo $args['name'] . '-attachment'; ?>']['value']"
		>
		</cx-vui-select>

		<cx-vui-select
			name="<?php echo $args['name'] . '-repeat'; ?>"
			label="<?php echo sprintf( esc_html__( '%s background repeat', 'jet-menu' ), $args['label'] ); ?>"
			description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?><a href='https://developer.mozilla.org/en-US/docs/Web/CSS/background-repeat' target='_blank'>background repeat</a>"
			:wrapper-css="[ 'equalwidth' ]"
			size="fullwidth"
			:options-list="pageOptions['<?php echo $args['name'] . '-repeat'; ?>']['options']"
			v-model="pageOptions['<?php echo $args['name'] . '-repeat'; ?>']['value']"
		>
		</cx-vui-select>

		<cx-vui-select
			name="<?php echo $args['name'] . '-size'; ?>"
			label="<?php echo sprintf( esc_html__( '%s background size', 'jet-menu' ), $args['label'] ); ?>"
			description="<?php echo esc_html__( 'Read more about', 'jet-menu' ); ?> <a href='https://developer.mozilla.org/en-US/docs/Web/CSS/background-size' target='_blank'>background size</a>"
			:wrapper-css="[ 'equalwidth' ]"
			size="fullwidth"
			:options-list="pageOptions['<?php echo $args['name'] . '-size'; ?>']['options']"
			v-model="pageOptions['<?php echo $args['name'] . '-size'; ?>']['value']"
		>
		</cx-vui-select>
	</cx-vui-component-wrapper>

</cx-vui-component-wrapper>

