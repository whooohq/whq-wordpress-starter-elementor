<div
	class="jet-menu-settings-page jet-menu-settings-page__mobile-menu"
>

	<cx-vui-collapse
		:collapsed="true"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Options', 'jet-menu' ); ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>
			<cx-vui-select
				name="jet-menu-mobile-layout"
				label="<?php _e( 'Layout', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-layout']['options']"
				v-model="pageOptions['jet-menu-mobile-layout']['value']">
			</cx-vui-select>

			<cx-vui-select
				name="jet-menu-mobile-toggle-position"
				label="<?php _e( 'Toggle position', 'jet-menu' ); ?>"
				description="<?php _e( 'Choose toggle global position on window screen', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-toggle-position']['options']"
				v-model="pageOptions['jet-menu-mobile-toggle-position']['value']"
				:conditions="[
					{
						input: this.pageOptions['jet-menu-mobile-layout']['value'],
						compare: 'equal',
						value: 'slide-out',
					}
				]"
				>
			</cx-vui-select>

			<cx-vui-select
				name="jet-menu-mobile-container-position"
				label="<?php _e( 'Container aligment', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-container-position']['options']"
				v-model="pageOptions['jet-menu-mobile-container-position']['value']">
			</cx-vui-select>

			<cx-vui-select
				name="jet-menu-mobile-sub-trigger"
				label="<?php _e( 'Show Sub Menu Trigger', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-sub-trigger']['options']"
				v-model="pageOptions['jet-menu-mobile-sub-trigger']['value']"
			>
			</cx-vui-select>

			<cx-vui-select
				name="jet-menu-mobile-sub-open-layout"
				label="<?php _e( 'Show Sub Menu Layout', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-sub-open-layout']['options']"
				v-model="pageOptions['jet-menu-mobile-sub-open-layout']['value']"
			>
			</cx-vui-select>

			<cx-vui-switcher
				name="jet-menu-mobile-close-after-navigate"
				label="<?php _e( 'Close After Navigation', 'jet-menu' ); ?>"
				description="<?php _e( 'Close Menu Panel After Item Link Navigation', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-menu-mobile-close-after-navigate']['value']">
			</cx-vui-switcher>

			<cx-vui-select
				name="jet-menu-mobile-header-template"
				label="<?php _e( 'Header content', 'jet-menu' ); ?>"
				description="<?php _e( 'Choose elementor template', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-header-template']['options']"
				v-model="pageOptions['jet-menu-mobile-header-template']['value']">
			</cx-vui-select>

			<cx-vui-select
				name="jet-menu-mobile-before-template"
				label="<?php _e( 'Before items content', 'jet-menu' ); ?>"
				description="<?php _e( 'Choose elementor template', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-before-template']['options']"
				v-model="pageOptions['jet-menu-mobile-before-template']['value']">
			</cx-vui-select>

			<cx-vui-select
				name="jet-menu-mobile-after-template"
				label="<?php _e( 'After items content', 'jet-menu' ); ?>"
				description="<?php _e( 'Choose elementor template', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-mobile-after-template']['options']"
				v-model="pageOptions['jet-menu-mobile-after-template']['value']">
			</cx-vui-select>

            <cx-vui-wp-media
                label="<?php _e( 'Toggle Icon', 'jet-menu' ); ?>"
                name="jet-menu-mobile-toggle-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-menu-mobile-toggle-icon']['value']"
            ></cx-vui-wp-media>

            <cx-vui-wp-media
                label="<?php _e( 'Toggle Opened Icon', 'jet-menu' ); ?>"
                name="jet-menu-mobile-toggle-opened-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-menu-mobile-toggle-opened-icon']['value']"
            ></cx-vui-wp-media>

			<cx-vui-input
				name="jet-menu-mobile-toggle-text"
				label="<?php _e( 'Toggle text', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="text"
				v-model="pageOptions['jet-menu-mobile-toggle-text']['value']">
			</cx-vui-input>

			<cx-vui-switcher
				name="jet-menu-mobile-toggle-loader"
				label="<?php _e( 'Show toggle button loader?', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-menu-mobile-toggle-loader']['value']">
			</cx-vui-switcher>

            <cx-vui-wp-media
                label="<?php _e( 'Close icon', 'jet-menu' ); ?>"
                name="jet-menu-mobile-container-close-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-menu-mobile-container-close-icon']['value']"
            ></cx-vui-wp-media>

            <cx-vui-wp-media
                label="<?php _e( 'Menu back icon', 'jet-menu' ); ?>"
                name="jet-menu-mobile-container-back-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-menu-mobile-container-back-icon']['value']"
            ></cx-vui-wp-media>

			<cx-vui-input
				name="jet-menu-mobile-back-text"
				label="<?php _e( 'Back text', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="text"
				v-model="pageOptions['jet-menu-mobile-back-text']['value']">
			</cx-vui-input>

            <cx-vui-wp-media
                label="<?php _e( 'Dropdown icon', 'jet-menu' ); ?>"
                name="jet-mobile-items-dropdown-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-mobile-items-dropdown-icon']['value']"
            ></cx-vui-wp-media>

            <cx-vui-wp-media
                label="<?php _e( 'Dropdown opened icon', 'jet-menu' ); ?>"
                name="jet-mobile-items-dropdown-opened-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-mobile-items-dropdown-opened-icon']['value']"
            ></cx-vui-wp-media>

			<cx-vui-switcher
				name="jet-menu-mobile-use-breadcrumb"
				label="<?php _e( 'Use breadcrumbs?', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-menu-mobile-use-breadcrumb']['value']">
			</cx-vui-switcher>

            <cx-vui-wp-media
                label="<?php _e( 'Breadcrumbs divider icon', 'jet-menu' ); ?>"
                name="jet-menu-mobile-breadcrumb-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-menu-mobile-breadcrumb-icon']['value']"
                :conditions="[
                    {
                        input: this.pageOptions['jet-menu-mobile-use-breadcrumb']['value'],
                        compare: 'equal',
                        value: 'true',
                    }
                ]"
            ></cx-vui-wp-media>

            <cx-vui-switcher
                name="jet-mobile-items-icon-enabled"
                label="<?php _e( 'Use items icon?', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="true"
                return-false="false"
                v-model="pageOptions['jet-mobile-items-icon-enabled']['value']"
            >
            </cx-vui-switcher>

            <cx-vui-switcher
                name="jet-mobile-items-badge-enabled"
                label="<?php _e( 'Use items badge?', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="true"
                return-false="false"
                v-model="pageOptions['jet-mobile-items-badge-enabled']['value']"
            >
            </cx-vui-switcher>

            <cx-vui-switcher
                name="jet-mobile-items-desc-enable"
                label="<?php _e( 'Show item description?', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="true"
                return-false="false"
                v-model="pageOptions['jet-mobile-items-desc-enable']['value']"
            >
            </cx-vui-switcher>

		</div>
	</cx-vui-collapse>

	<cx-vui-collapse
		:collapsed="true"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Toggle Styles', 'jet-menu' ); ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>

			<cx-vui-colorpicker
				name="jet-menu-mobile-toggle-color"
				label="<?php _e( 'Icon color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-menu-mobile-toggle-color']['value']"
			></cx-vui-colorpicker>

			<cx-vui-input
				name="jet-menu-mobile-toggle-size"
				label="<?php _e( 'Icon size(px)', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="number"
				:min="6"
				max="100"
				:step="1"
				v-model="pageOptions['jet-menu-mobile-toggle-size']['value']">
			</cx-vui-input>

			<cx-vui-colorpicker
				name="jet-menu-mobile-toggle-text-color"
				label="<?php _e( 'Text color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-menu-mobile-toggle-text-color']['value']"
				:conditions="[
					{
						input: this.pageOptions['jet-menu-mobile-toggle-text']['value'],
						compare: 'not_equal',
						value: '',
					}
				]"
			></cx-vui-colorpicker>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'container' ]"
				:conditions="[
					{
						input: this.pageOptions['jet-menu-mobile-toggle-text']['value'],
						compare: 'not_equal',
						value: '',
					}
				]"
			>
			<?php

				jet_menu()->settings_manager->options_manager->render_typography_options( array(
					'name'     => 'jet-menu-mobile-toggle-text',
					'label'    => esc_html__( 'Toggle text', 'jet-menu' ),
				) );

			?>
			</cx-vui-component-wrapper>

			<cx-vui-colorpicker
				name="jet-menu-mobile-toggle-bg"
				label="<?php _e( 'Background color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-menu-mobile-toggle-bg']['value']"
			></cx-vui-colorpicker>

			<?php

				jet_menu()->settings_manager->options_manager->render_border_options( array(
					'name'     => 'jet-menu-mobile-toggle',
					'label'    => esc_html__( 'Toggle', 'jet-menu' ),
				) );

				jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
					'name'     => 'jet-menu-mobile-toggle',
					'label'    => esc_html__( 'Toggle', 'jet-menu' ),
				) );

			?>

			<cx-vui-dimensions
				name="jet-menu-mobile-toggle-padding"
				label="<?php _e( 'Padding', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				:units="[
					{
						unit: 'px',
						min: 0,
						max: 100,
						step: 1
					}
				]"
				v-model="pageOptions['jet-menu-mobile-toggle-padding']['value']"
			>
			</cx-vui-dimensions>

			<cx-vui-dimensions
				name="jet-menu-mobile-toggle-border-radius"
				label="<?php _e( 'Border radius', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				:units="[
					{
						unit: 'px',
						min: 0,
						max: 100,
						step: 1
					},
					{
						unit: '%',
						min: 0,
						max: 100,
						step: 1
					}
				]"
				v-model="pageOptions['jet-menu-mobile-toggle-border-radius']['value']"
			>
			</cx-vui-dimensions>

		</div>
	</cx-vui-collapse>

	<cx-vui-collapse
		:collapsed="true"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Container Styles', 'jet-menu' ) ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>

			<cx-vui-colorpicker
				name="jet-menu-mobile-container-close-color"
				label="<?php _e( 'Close/Back button color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-menu-mobile-container-close-color']['value']"
			></cx-vui-colorpicker>

			<cx-vui-input
				name="jet-menu-mobile-container-close-size"
				label="<?php _e( 'Close/Back icon size(px)', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="number"
				:min="6"
				:max="100"
				:step="1"
				v-model="pageOptions['jet-menu-mobile-container-close-size']['value']">
			</cx-vui-input>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'container' ]"
				:conditions="[
					{
						input: this.pageOptions['jet-menu-mobile-back-text']['value'],
						compare: 'not_equal',
						value: '',
					}
				]"
			>
			<cx-vui-colorpicker
				name="jet-menu-mobile-container-back-text-color"
				label="<?php _e( 'Back button text color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-menu-mobile-container-back-text-color']['value']"
			></cx-vui-colorpicker>
			<?php

				jet_menu()->settings_manager->options_manager->render_typography_options( array(
					'name'     => 'jet-menu-mobile-back-text',
					'label'    => esc_html__( 'Back button text', 'jet-menu' ),
				) );

			?>
			</cx-vui-component-wrapper>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'container' ]"
				:conditions="[
					{
						input: this.pageOptions['jet-menu-mobile-use-breadcrumb']['value'],
						compare: 'equal',
						value: 'true',
					}
				]"
			>
				<cx-vui-colorpicker
					name="jet-menu-mobile-breadcrumbs-text-color"
					label="<?php _e( 'Breadcrumbs text color', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					v-model="pageOptions['jet-menu-mobile-breadcrumbs-text-color']['value']"
				></cx-vui-colorpicker>

				<cx-vui-colorpicker
					name="jet-menu-mobile-breadcrumbs-icon-color"
					label="<?php _e( 'Breadcrumbs divider color', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					v-model="pageOptions['jet-menu-mobile-breadcrumbs-icon-color']['value']"
				></cx-vui-colorpicker>

				<cx-vui-input
					name="jet-menu-mobile-breadcrumbs-icon-size"
					label="<?php _e( 'Breadcrumbs divider icon size(px)', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					type="number"
					:min="6"
					:max="100"
					:step="1"
					v-model="pageOptions['jet-menu-mobile-breadcrumbs-icon-size']['value']">
				</cx-vui-input>

				<?php

					jet_menu()->settings_manager->options_manager->render_typography_options( array(
						'name'     => 'jet-menu-mobile-breadcrumbs-text',
						'label'    => esc_html__( 'Breadcrums text', 'jet-menu' ),
					) );

				?>
			</cx-vui-component-wrapper>

			<cx-vui-input
				name="jet-menu-mobile-container-width"
				label="<?php _e( 'Width(px)', 'jet-menu' ); ?>"
				description="<?php _e( 'Max width 100% of device viewport width. Default 400px', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="number"
				:min="300"
				:max="1000"
				:step="1"
				v-model="pageOptions['jet-menu-mobile-container-width']['value']">
			</cx-vui-input>

			<cx-vui-colorpicker
				name="jet-menu-mobile-container-bg"
				label="<?php _e( 'Background color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-menu-mobile-container-bg']['value']"
			></cx-vui-colorpicker>

			<?php

				jet_menu()->settings_manager->options_manager->render_border_options( array(
					'name'     => 'jet-menu-mobile-container',
					'label'    => esc_html__( 'Container', 'jet-menu' ),
				) );

				jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
					'name'     => 'jet-menu-mobile-container',
					'label'    => esc_html__( 'Container', 'jet-menu' ),
				) );

			?>

			<cx-vui-dimensions
				name="jet-menu-mobile-container-padding"
				label="<?php _e( 'Container padding', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				:units="[
					{
						unit: 'px',
						min: 0,
						max: 500,
						step: 1
					}
				]"
				v-model="pageOptions['jet-menu-mobile-container-padding']['value']"
			>
			</cx-vui-dimensions>

			<cx-vui-dimensions
				name="jet-menu-mobile-container-border-radius"
				label="<?php _e( 'Container border radius', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				:units="[
					{
						unit: 'px',
						min: 0,
						max: 100,
						step: 1
					},
					{
						unit: '%',
						min: 0,
						max: 100,
						step: 1
					}
				]"
				v-model="pageOptions['jet-menu-mobile-container-border-radius']['value']"
			>
			</cx-vui-dimensions>


		</div>
	</cx-vui-collapse>

	<cx-vui-collapse
		:collapsed="true"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Items Styles', 'jet-menu' ) ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'states' ]"
			>
				<label class="cx-vui-component__label"><?php _e( 'States', 'jet-menu' ); ?></label>

				<cx-vui-tabs
					class="horizontal-tabs"
					:in-panel="true"
					layout="horizontal"
				>
					<cx-vui-tabs-panel
						name="jet-mobile-items-normal-state"
						label="<?php echo _e( 'Normal', 'jet-menu' ); ?>"
						key="jet-mobile-items-normal-state"
					>
						<cx-vui-colorpicker
							name="jet-mobile-items-label-color"
							label="<?php _e( 'Label Color', 'jet-menu' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							v-model="pageOptions['jet-mobile-items-label-color']['value']"
						></cx-vui-colorpicker>

						<cx-vui-colorpicker
							name="jet-mobile-items-desc-color"
							label="<?php _e( 'Description Color', 'jet-menu' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							v-model="pageOptions['jet-mobile-items-desc-color']['value']"
							:conditions="[
								{
									input: this.pageOptions['jet-mobile-items-desc-enable']['value'],
									compare: 'equal',
									value: 'true',
								}
							]"
						></cx-vui-colorpicker>

					</cx-vui-tabs-panel>
					<cx-vui-tabs-panel
						name="jet-mobile-items-active-state"
						label="<?php echo _e( 'Active', 'jet-menu' ); ?>"
						key="jet-mobile-items-active-state"
					>
						<cx-vui-colorpicker
							name="jet-mobile-items-label-color-active"
							label="<?php _e( 'Label Color', 'jet-menu' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							v-model="pageOptions['jet-mobile-items-label-color-active']['value']"
						></cx-vui-colorpicker>
						<cx-vui-colorpicker
							name="jet-mobile-items-desc-color-active"
							label="<?php _e( 'Description Color', 'jet-menu' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							v-model="pageOptions['jet-mobile-items-desc-color-active']['value']"
							:conditions="[
								{
									input: this.pageOptions['jet-mobile-items-desc-enable']['value'],
									compare: 'equal',
									value: 'true',
								}
							]"
						></cx-vui-colorpicker>
					</cx-vui-tabs-panel>
				</cx-vui-tabs>
			</cx-vui-component-wrapper>

			<?php

				jet_menu()->settings_manager->options_manager->render_typography_options( array(
					'name'     => 'jet-mobile-items-label',
					'label'    => esc_html__( 'Items label', 'jet-menu' ),
				) );

			?>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'group' ]"
				:conditions="[
					{
						input: this.pageOptions['jet-mobile-items-desc-enable']['value'],
						compare: 'equal',
						value: 'true',
					}
				]"
			>

				<?php

					jet_menu()->settings_manager->options_manager->render_typography_options( array(
						'name'     => 'jet-mobile-items-desc',
						'label'    => esc_html__( 'Items description', 'jet-menu' ),
					) );

				?>

			</cx-vui-component-wrapper>

			<cx-vui-switcher
				name="jet-mobile-items-divider-enabled"
				label="<?php _e( 'Use items divider?', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-mobile-items-divider-enabled']['value']">
			</cx-vui-switcher>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'group' ]"
				:conditions="[
					{
						input: this.pageOptions['jet-mobile-items-divider-enabled']['value'],
						compare: 'equal',
						value: 'true',
					}
				]"
			>
				<cx-vui-colorpicker
					name="jet-mobile-items-divider-color"
					label="<?php _e( 'Divider Color', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					v-model="pageOptions['jet-mobile-items-divider-color']['value']"
				>
				</cx-vui-colorpicker>

				<cx-vui-input
					name="jet-mobile-items-divider-width"
					label="<?php _e( 'Divider width(px)', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					type="number"
					:min="1"
					:max="10"
					:step="1"
					v-model="pageOptions['jet-mobile-items-divider-width']['value']"
				>
				</cx-vui-input>

			</cx-vui-component-wrapper>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'group' ]"
				:conditions="[
					{
						input: this.pageOptions['jet-mobile-items-icon-enabled']['value'],
						compare: 'equal',
						value: 'true',
					}
				]"
			>
				<cx-vui-colorpicker
					name="jet-mobile-items-icon-color"
					label="<?php _e( 'Icon Color', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					v-model="pageOptions['jet-mobile-items-icon-color']['value']"
				>
				</cx-vui-colorpicker>

				<cx-vui-input
					name="jet-mobile-items-icon-size"
					label="<?php _e( 'Icon size(px)', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					type="number"
					:min="1"
					:max="10"
					:step="1"
					v-model="pageOptions['jet-mobile-items-icon-size']['value']"
				>
				</cx-vui-input>

				<cx-vui-select
					name="jet-mobile-items-icon-ver-position"
					label="<?php _e( 'Icon vertical position', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:options-list="pageOptions['jet-mobile-items-icon-ver-position']['options']"
					v-model="pageOptions['jet-mobile-items-icon-ver-position']['value']">
				</cx-vui-select>

				<cx-vui-dimensions
					name="jet-mobile-items-icon-margin"
					label="<?php _e( 'Icon margin', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					:units="[
						{
							unit: 'px',
							min: 0,
							max: 50,
							step: 1
						}
					]"
					v-model="pageOptions['jet-mobile-items-icon-margin']['value']"
				>
				</cx-vui-dimensions>

			</cx-vui-component-wrapper>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'group' ]"
				:conditions="[
					{
						input: this.pageOptions['jet-mobile-items-badge-enabled']['value'],
						compare: 'equal',
						value: 'true',
					}
				]"
			>
				<cx-vui-colorpicker
					name="jet-mobile-items-badge-color"
					label="<?php _e( 'Badge color', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					v-model="pageOptions['jet-mobile-items-badge-color']['value']"
				>
				</cx-vui-colorpicker>

				<?php

					jet_menu()->settings_manager->options_manager->render_typography_options( array(
						'name'     => 'jet-mobile-items-badge',
						'label'    => esc_html__( 'Badge', 'jet-menu' ),
					) );

				?>

				<cx-vui-colorpicker
					name="jet-mobile-items-badge-bg-color"
					label="<?php _e( 'Badge background color', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					v-model="pageOptions['jet-mobile-items-badge-bg-color']['value']"
				>
				</cx-vui-colorpicker>

				<cx-vui-select
					name="jet-mobile-items-badge-ver-position"
					label="<?php _e( 'Badge vertical position', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:options-list="pageOptions['jet-mobile-items-badge-ver-position']['options']"
					v-model="pageOptions['jet-mobile-items-badge-ver-position']['value']">
				</cx-vui-select>

				<cx-vui-dimensions
					name="jet-mobile-items-badge-padding"
					label="<?php _e( 'Badge padding', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					:units="[
						{
							unit: 'px',
							min: 0,
							max: 50,
							step: 1
						}
					]"
					v-model="pageOptions['jet-mobile-items-badge-padding']['value']"
				>
				</cx-vui-dimensions>

				<cx-vui-dimensions
					name="jet-mobile-items-badge-border-radius"
					label="<?php _e( 'Badge border radius', 'jet-menu' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					:units="[
						{
							unit: 'px',
							min: 0,
							max: 100,
							step: 1
						},
						{
							unit: '%',
							min: 0,
							max: 100,
							step: 1
						}
					]"
					v-model="pageOptions['jet-mobile-items-badge-border-radius']['value']"
				>
				</cx-vui-dimensions>

			</cx-vui-component-wrapper>

		</div>
	</cx-vui-collapse>

	<cx-vui-collapse
		:collapsed="true"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Sub Menu Icon Styles', 'jet-menu' ) ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>
			<cx-vui-colorpicker
				name="jet-mobile-items-dropdown-color"
				label="<?php _e( 'Dropdown color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-mobile-items-dropdown-color']['value']"
			></cx-vui-colorpicker>

			<cx-vui-input
				name="jet-mobile-items-dropdown-size"
				label="<?php _e( 'Dropdown size(px)', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="number"
				:min="6"
				:max="100"
				:step="1"
				v-model="pageOptions['jet-mobile-items-dropdown-size']['value']">
			</cx-vui-input>
		</div>
	</cx-vui-collapse>

	<cx-vui-collapse
		:collapsed="true"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Advanced Styles', 'jet-menu' ) ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>
			<cx-vui-colorpicker
				name="jet-mobile-loader-color"
				label="<?php _e( 'Loader color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-mobile-loader-color']['value']"
			></cx-vui-colorpicker>

			<cx-vui-colorpicker
				name="jet-menu-mobile-cover-bg"
				label="<?php _e( 'Cover background color', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				v-model="pageOptions['jet-menu-mobile-cover-bg']['value']"
			></cx-vui-colorpicker>

		</div>
	</cx-vui-collapse>
</div>
