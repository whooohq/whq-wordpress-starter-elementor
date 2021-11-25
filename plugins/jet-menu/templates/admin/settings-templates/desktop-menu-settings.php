<div
	class="jet-menu-settings-page jet-menu-settings-page__desktop-menu"
>
	<cx-vui-collapse
		:collapsed="false"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Options', 'jet-menu' ) ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>
			<cx-vui-select
				name="jet-menu-animation"
				label="<?php _e( 'Animation', 'jet-menu' ); ?>"
				description="<?php _e( 'Choose an animation effect for sub menu', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-animation']['options']"
				v-model="pageOptions['jet-menu-animation']['value']">
			</cx-vui-select>

			<cx-vui-switcher
				name="jet-menu-roll-up"
				label="<?php _e( 'Menu RollUp', 'jet-menu' ); ?>"
				description="<?php _e( 'Enable this option in order to reduce the menu size by groupping extra menu items and hiding them under the suspension dots.', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-menu-roll-up']['value']">
			</cx-vui-switcher>

			<cx-vui-switcher
				name="jet-menu-mega-ajax-loading"
				label="<?php _e( 'Use ajax loading', 'jet-menu' ); ?>"
				description="<?php _e( 'Use ajax loading for mega content', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-menu-mega-ajax-loading']['value']">
			</cx-vui-switcher>

			<cx-vui-select
				name="jet-menu-show-for-device"
				label="<?php _e( 'Device view', 'jet-menu' ); ?>"
				description="<?php _e( 'Choose which menu view you want to display', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-show-for-device']['options']"
				v-model="pageOptions['jet-menu-show-for-device']['value']">
			</cx-vui-select>

			<cx-vui-input
				name="jet-menu-mouseleave-delay"
				label="<?php _e( 'Mouse leave delay(ms)', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="number"
				:min="0"
				:max="10000"
				:step="100"
				v-model="pageOptions['jet-menu-mouseleave-delay']['value']">
			</cx-vui-input>

			<cx-vui-select
				name="jet-mega-menu-width-type"
				label="<?php _e( 'Mega menu base width', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-mega-menu-width-type']['options']"
				v-model="pageOptions['jet-mega-menu-width-type']['value']">
			</cx-vui-select>

			<cx-vui-input
				name="jet-mega-menu-selector-width-type"
				label="<?php _e( 'Mega menu width selector', 'jet-menu' ); ?>"
				description="<?php _e( 'Enter css selector whose width will be equal to the width of the container mega menu', 'jet-menu' ); ?>"
				size="fullwidth"
				:wrapper-css="[ 'equalwidth' ]"
				type="text"
				v-model="pageOptions['jet-mega-menu-selector-width-type']['value']"
				:conditions="[
					{
						input: this.pageOptions['jet-mega-menu-width-type']['value'],
						compare: 'equal',
						value: 'selector',
					}
				]"
			>
			</cx-vui-input>

			<cx-vui-select
				name="jet-menu-open-sub-type"
				label="<?php _e( 'Sub menu open trigger', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-open-sub-type']['options']"
				v-model="pageOptions['jet-menu-open-sub-type']['value']">
			</cx-vui-select>

		</div>
	</cx-vui-collapse>

	<cx-vui-collapse
		:collapsed="false"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Menu container styles', 'jet-menu' ) ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>
			<cx-vui-select
				name="jet-menu-container-alignment"
				label="<?php _e( 'Menu items alignment', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="pageOptions['jet-menu-container-alignment']['options']"
				v-model="pageOptions['jet-menu-container-alignment']['value']">
			</cx-vui-select>

			<cx-vui-input
				name="jet-menu-min-width"
				label="<?php _e( 'Menu container min width (px)', 'jet-menu' ); ?>"
				description="<?php _e( 'Set 0 to automatic width detection', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				type="number"
				:min="0"
				:max="900"
				:step="1"
				v-model="pageOptions['jet-menu-min-width']['value']">
			</cx-vui-input>

			<cx-vui-dimensions
				name="jet-menu-mega-padding"
				label="<?php _e( 'Menu container padding', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				:units="[
					{
						unit: 'px',
						min: 0,
						max: 500,
						step: 1
					},
					{
						unit: '%',
						min: 0,
						max: 100,
						step: 1
					}
				]"
				v-model="pageOptions['jet-menu-mega-padding']['value']"
			>
			</cx-vui-dimensions>

			<?php

			jet_menu()->settings_manager->options_manager->render_background_options( array(
				'name'     => 'jet-menu-container',
				'label'    => esc_html__( 'Menu container', 'jet-menu' ),
				'defaults' => array(
					'color' => '#ffffff',
				),
			) );

			jet_menu()->settings_manager->options_manager->render_border_options( array(
				'name'     => 'jet-menu-container',
				'label'    => esc_html__( 'Menu container', 'jet-menu' ),
			) );

			jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
				'name'     => 'jet-menu-container',
				'label'    => esc_html__( 'Menu container', 'jet-menu' ),
			) );

			?><cx-vui-dimensions
				name="jet-menu-mega-border-radius"
				label="<?php _e( 'Menu container border radius', 'jet-menu' ); ?>"
				description="<?php
					echo sprintf( esc_html__( 'Read more %1$s', 'jet-menu' ),
						htmlspecialchars( "<a href='https://developer.mozilla.org/en-US/docs/Web/CSS/border-radius' target='_blank'>border radius</a>", ENT_QUOTES )
					);
				?>"
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
				v-model="pageOptions['jet-menu-mega-border-radius']['value']"
			>
			</cx-vui-dimensions>

			<cx-vui-switcher
				name="jet-menu-inherit-first-radius"
				label="<?php _e( 'First item inherit border radius', 'jet-menu' ); ?>"
				description="<?php _e( 'Inherit border radius for the first menu item from main container', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-menu-inherit-first-radius']['value']">
			</cx-vui-switcher>

			<cx-vui-switcher
				name="jet-menu-inherit-last-radius"
				label="<?php _e( 'Last item inherit border radius', 'jet-menu' ); ?>"
				description="<?php _e( 'Inherit border radius for the last menu item from main container', 'jet-menu' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions['jet-menu-inherit-last-radius']['value']">
			</cx-vui-switcher>
		</div>
	</cx-vui-collapse>

	<cx-vui-collapse
		:collapsed="false"
	>
		<div
			class="cx-vui-subtitle"
			slot="title"><?php _e( 'Submenu container styles', 'jet-menu' ) ?></div>
		<div
			class="cx-vui-panel"
			slot="content"
		>
			<cx-vui-component-wrapper
				:wrapper-css="[ 'fullwidth-control', 'states', 'sub-panel' ]"
			>
				<cx-vui-tabs
					class="horizontal-tabs"
					:in-panel="true"
					layout="horizontal"
				><?php

					$tabs = array(
						'simple' => array(
							'label'  => esc_html__( 'Simple Submenu Panel', 'jet-menu' ),
							'prefix' => '-simple'
						),
						'mega' => array(
							'label'  => esc_html__( 'Mega Submenu Panel', 'jet-menu' ),
							'prefix' => '-mega'
						),
					);

					foreach ( $tabs as $tab => $state ) {

						$label = $state['label'];
						$prefix = $state['prefix'];

						?><cx-vui-tabs-panel
							name="<?php echo 'sub-panel-' . $tab . '-styles'; ?>"
							label="<?php echo $label; ?>"
							key="<?php echo 'sub-panel-' . $tab . '-styles'; ?>"
						><?php

							if ( 'simple' === $tab ) {?>
								<cx-vui-input
									name="jet-menu-sub-panel-width-simple"
									label="<?php _e( 'Panel width(px)', 'jet-menu' ); ?>"
									:wrapper-css="[ 'equalwidth' ]"
									size="fullwidth"
									type="number"
									:min="100"
									:max="400"
									:step="1"
									v-model="pageOptions['jet-menu-sub-panel-width-simple']['value']">
								</cx-vui-input><?php
							}

							jet_menu()->settings_manager->options_manager->render_background_options( array(
								'name'  => 'jet-menu-sub-panel' . $prefix,
								'label' => esc_html__( 'Panel', 'jet-menu' ),
							) );

							jet_menu()->settings_manager->options_manager->render_border_options( array(
								'name'     => 'jet-menu-sub-panel' . $prefix,
								'label'    => esc_html__( 'Panel', 'jet-menu' ),
							) );

							jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
								'name'     => 'jet-menu-sub-panel' . $prefix,
								'label'    => esc_html__( 'Panel', 'jet-menu' ),
							) );

						?>
						<cx-vui-dimensions
							name="<?php echo 'jet-menu-sub-panel-border-radius' . $prefix; ?>"
							label="<?php _e( 'Panel border radius', 'jet-menu' ); ?>"
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
							v-model="pageOptions['<?php echo 'jet-menu-sub-panel-border-radius' . $prefix; ?>']['value']"
						>
						</cx-vui-dimensions>

						<cx-vui-dimensions
							name="<?php echo 'jet-menu-sub-panel-padding' . $prefix; ?>"
							label="<?php _e( 'Panel padding', 'jet-menu' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:units="[
								{
									unit: 'px',
									min: 0,
									max: 100,
									step: 1
								}
							]"
							v-model="pageOptions['<?php echo 'jet-menu-sub-panel-padding' . $prefix; ?>']['value']"
						>
						</cx-vui-dimensions>

						<cx-vui-dimensions
							name="<?php echo 'jet-menu-sub-panel-margin' . $prefix; ?>"
							label="<?php _e( 'Panel margin', 'jet-menu' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:units="[
								{
									unit: 'px',
									min: -50,
									max: 100,
									step: 1
								}
							]"
							v-model="pageOptions['<?php echo 'jet-menu-sub-panel-margin' . $prefix; ?>']['value']"
						>
						</cx-vui-dimensions>

						</cx-vui-tabs-panel><?php
					}

				?></cx-vui-tabs>

			</cx-vui-component-wrapper>
		</div>
	</cx-vui-collapse>

    <cx-vui-collapse
        :collapsed="false"
    >
        <div
            class="cx-vui-subtitle"
            slot="title"><?php _e( 'Top items styles', 'jet-menu' ) ?></div>
        <div
            class="cx-vui-panel"
            slot="content"
        >
            <cx-vui-input
                name="jet-menu-item-max-width"
                label="<?php _e( 'Top level item max width (%)', 'jet-menu' ); ?>"
                description="<?php _e( 'Set 0 to automatic width detection', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="number"
                :min="0"
                :max="100"
                :step="1"
                v-model="pageOptions['jet-menu-item-max-width']['value']">
            </cx-vui-input>

            <cx-vui-switcher
                name="jet-show-top-menu-desc"
                label="<?php _e( 'Show top item description', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="true"
                return-false="false"
                v-model="pageOptions['jet-show-top-menu-desc']['value']"
            >
            </cx-vui-switcher>

			<?php
			jet_menu()->settings_manager->options_manager->render_typography_options( array(
				'name'     => 'jet-top-menu',
				'label'    => esc_html__( 'Top level menu', 'jet-menu' ),
			) );

			jet_menu()->settings_manager->options_manager->render_typography_options( array(
				'name'     => 'jet-top-menu-desc',
				'label'    => esc_html__( 'Top level menu description', 'jet-menu' ),
			) );
			?>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Items icon styles', 'jet-menu' ); ?></label>

                <cx-vui-input
                    name="jet-menu-top-icon-size"
                    label="<?php _e( 'Icon size', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="10"
                    :max="50"
                    :step="1"
                    v-model="pageOptions['jet-menu-top-icon-size']['value']">
                </cx-vui-input>

                <cx-vui-dimensions
                    name="jet-menu-top-icon-margin"
                    label="<?php _e( 'Icon margin', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    v-model="pageOptions['jet-menu-top-icon-margin']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-select
                    name="jet-menu-top-icon-ver-position"
                    label="<?php echo _e( 'Icon vertical position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-top-icon-ver-position']['options']"
                    v-model="pageOptions['jet-menu-top-icon-ver-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-select
                    name="jet-menu-top-icon-hor-position"
                    label="<?php echo _e( 'Icon horizontal position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-top-icon-hor-position']['options']"
                    v-model="pageOptions['jet-menu-top-icon-hor-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-input
                    name="jet-menu-top-icon-order"
                    label="<?php _e( 'Icon order', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="-10"
                    :max="10"
                    :step="1"
                    v-model="pageOptions['jet-menu-top-icon-order']['value']">
                </cx-vui-input>

            </cx-vui-component-wrapper>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Items badge styles', 'jet-menu' ); ?></label>

                <cx-vui-colorpicker
                    name="jet-menu-top-badge-text-color"
                    label="<?php _e( 'Badge Text color', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    v-model="pageOptions['jet-menu-top-badge-text-color']['value']"
                ></cx-vui-colorpicker><?php

	            jet_menu()->settings_manager->options_manager->render_typography_options( array(
		            'name'     => 'jet-menu-top-badge',
		            'label'    => esc_html__( 'Badge', 'jet-menu' ),
	            ) );

	            jet_menu()->settings_manager->options_manager->render_background_options( array(
		            'name'  => 'jet-menu-top-badge-bg',
		            'label' => esc_html__( 'Badge', 'jet-menu' ),
	            ) );

	            jet_menu()->settings_manager->options_manager->render_border_options( array(
		            'name'     => 'jet-menu-top-badge',
		            'label'    => esc_html__( 'Badge', 'jet-menu' ),
	            ) );

	            jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
		            'name'     => 'jet-menu-top-badge',
		            'label'    => esc_html__( 'Badge', 'jet-menu' ),
	            ) );

	            ?><cx-vui-dimensions
                    name="jet-menu-top-badge-border-radius"
                    label="<?php _e( 'Panel border radius', 'jet-menu' ); ?>"
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
                    v-model="pageOptions['jet-menu-top-badge-border-radius']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-dimensions
                    name="jet-menu-top-badge-padding"
                    label="<?php _e( 'Badge padding', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    :units="[
                        {
                            unit: 'px',
                            min: 0,
                            max: 100,
                            step: 1
                        }
                    ]"
                    v-model="pageOptions['jet-menu-top-badge-padding']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-dimensions
                    name="jet-menu-top-badge-margin"
                    label="<?php _e( 'Badge margin', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    :units="[
                        {
                            unit: 'px',
                            min: -50,
                            max: 100,
                            step: 1
                        }
                    ]"
                    v-model="pageOptions['jet-menu-top-badge-margin']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-select
                    name="jet-menu-top-badge-ver-position"
                    label="<?php echo _e( 'Badge vertical position (may be overridden with order)', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-top-badge-ver-position']['options']"
                    v-model="pageOptions['jet-menu-top-badge-ver-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-select
                    name="jet-menu-top-badge-hor-position"
                    label="<?php echo _e( 'Badge horizontal position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-top-badge-hor-position']['options']"
                    v-model="pageOptions['jet-menu-top-badge-hor-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-input
                    name="jet-menu-top-badge-order"
                    label="<?php _e( 'Badge order', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="-10"
                    :max="10"
                    :step="1"
                    v-model="pageOptions['jet-menu-top-badge-order']['value']">
                </cx-vui-input>

                <cx-vui-switcher
                    name="jet-menu-top-badge-hide"
                    label="<?php _e( 'Hide badge on mobile', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    return-true="true"
                    return-false="false"
                    v-model="pageOptions['jet-menu-top-badge-hide']['value']"
                >
                </cx-vui-switcher>

            </cx-vui-component-wrapper>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Drop-down arrow styles', 'jet-menu' ); ?></label>

                <cx-vui-select
                    name="jet-menu-top-arrow-type"
                    label="<?php _e( 'Top items dropdown type', 'jet-menu' ); ?>"
                    size="fullwidth"
                    :wrapper-css="[ 'equalwidth' ]"
                    :options-list="[
                        {
                            label: 'Font icon',
                            value: 'icon',
                        },
                         {
                            label: 'Svg',
                            value: 'svg',
                        },
                    ]"
                    v-model="pageOptions['jet-menu-top-arrow-type']['value']"
                >
                </cx-vui-select>

                <cx-vui-iconpicker
                    name="jet-menu-top-arrow"
                    label="<?php _e( 'Top items dropdown icon', 'jet-menu' ); ?>"
                    icon-base="fa"
                    :icons="arrowsIcons"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    v-model="pageOptions['jet-menu-top-arrow']['value']"
                    :conditions="[
                    {
                        input: pageOptions['jet-menu-top-arrow-type']['value'],
                        compare: 'equal',
                        value: 'icon',
                    }
                ]"
                ></cx-vui-iconpicker>

                <cx-vui-wp-media
                    name="jet-menu-top-arrow-svg"
                    label="<?php _e( 'Top items dropdown SVG icon', 'jet-menu' ); ?>"
                    return-type="string"
                    :multiple="false"
                    :wrapper-css="[ 'equalwidth' ]"
                    v-model="pageOptions['jet-menu-top-arrow-svg']['value']"
                    :conditions="[
                        {
                            input: pageOptions['jet-menu-top-arrow-type']['value'],
                            compare: 'equal',
                            value: 'svg',
                        }
                    ]"
                ></cx-vui-wp-media>

                <cx-vui-input
                    name="jet-menu-top-arrow-size"
                    label="<?php _e( 'Arrow size', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="10"
                    :max="150"
                    :step="1"
                    v-model="pageOptions['jet-menu-top-arrow-size']['value']"
                >
                </cx-vui-input>

                <cx-vui-dimensions
                    name="jet-menu-top-arrow-margin"
                    label="<?php _e( 'Arrow margin', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    :units="[
                        {
                            unit: 'px',
                            min: -50,
                            max: 100,
                            step: 1
                        }
                    ]"
                    v-model="pageOptions['jet-menu-top-arrow-margin']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-select
                    name="jet-menu-top-arrow-ver-position"
                    label="<?php echo _e( 'Arrow vertical position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-top-arrow-ver-position']['options']"
                    v-model="pageOptions['jet-menu-top-arrow-ver-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-select
                    name="jet-menu-top-arrow-hor-position"
                    label="<?php echo _e( 'Arrow horizontal position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-top-arrow-hor-position']['options']"
                    v-model="pageOptions['jet-menu-top-arrow-hor-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-input
                    name="jet-menu-top-arrow-order"
                    label="<?php _e( 'Arrow order', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="-10"
                    :max="10"
                    :step="1"
                    v-model="pageOptions['jet-menu-top-arrow-order']['value']"
                >
                </cx-vui-input>

            </cx-vui-component-wrapper>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control', 'states' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Top items states', 'jet-menu' ); ?></label>

                <cx-vui-tabs
                    class="horizontal-tabs"
                    :in-panel="true"
                    layout="horizontal"
                ><?php
					$tabs = array(
						'default' => array(
							'label'  => esc_html__( 'Default', 'jet-menu' ),
							'prefix' => ''
						),
						'hover' => array(
							'label'  => esc_html__( 'Hover', 'jet-menu' ),
							'prefix' => '-hover'
						),
						'active' => array(
							'label'  => esc_html__( 'Active', 'jet-menu' ),
							'prefix' => '-active'
						),
					);

					foreach ( $tabs as $tab => $state ) {

						$label = $state['label'];
						$prefix = $state['prefix'];

						?><cx-vui-tabs-panel
                        name="<?php echo 'menu-items-' . $tab . '-styles'; ?>"
                        label="<?php echo $label; ?>"
                        key="<?php echo 'menu-items-' . $tab . '-styles'; ?>"
                        >
                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-item-text-color' . $prefix; ?>"
                                label="<?php _e( 'Item Text Color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-item-text-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-item-desc-color' . $prefix; ?>"
                                label="<?php _e( 'Item Description Color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-item-desc-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-top-icon-color' . $prefix; ?>"
                                label="<?php _e( 'Item Icon Color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-top-icon-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-top-arrow-color' . $prefix; ?>"
                                label="<?php _e( 'Item Arrow Color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-top-arrow-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

						<?php

						jet_menu()->settings_manager->options_manager->render_background_options( array(
							'name'  => 'jet-menu-item' . $prefix,
							'label' => esc_html__( 'Item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_border_options( array(
							'name'     => 'jet-menu-item' . $prefix,
							'label'    => esc_html__( 'Item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_border_options( array(
							'name'     => 'jet-menu-first-item' . $prefix,
							'label'    => esc_html__( 'First Item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_border_options( array(
							'name'     => 'jet-menu-last-item' . $prefix,
							'label'    => esc_html__( 'Last Item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
							'name'     => 'jet-menu-item' . $prefix,
							'label'    => esc_html__( 'Item', 'jet-menu' ),
						) );
						?>

                        <cx-vui-dimensions
                            name="<?php echo 'jet-menu-item-border-radius' . $prefix; ?>"
                            label="<?php _e( 'Item border radius', 'jet-menu' ); ?>"
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
                            v-model="pageOptions['<?php echo 'jet-menu-item-border-radius' . $prefix; ?>']['value']"
                        >
                        </cx-vui-dimensions>

                        <cx-vui-dimensions
                            name="<?php echo 'jet-menu-item-padding' . $prefix; ?>"
                            label="<?php _e( 'Item Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            :units="[
                                {
                                    unit: 'px',
                                    min: 0,
                                    max: 100,
                                    step: 1
                                }
                            ]"
                            v-model="pageOptions['<?php echo 'jet-menu-item-padding' . $prefix; ?>']['value']"
                        >
                        </cx-vui-dimensions>

                        <cx-vui-dimensions
                                name="<?php echo 'jet-menu-item-margin' . $prefix; ?>"
                                label="<?php _e( 'Item Margin', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                :units="[
								{
									unit: 'px',
									min: 0,
									max: 100,
									step: 1
								}
							]"
                                v-model="pageOptions['<?php echo 'jet-menu-item-margin' . $prefix; ?>']['value']"
                        >
                        </cx-vui-dimensions>

                        </cx-vui-tabs-panel><?php
					}
					?>

                </cx-vui-tabs>
            </cx-vui-component-wrapper>

        </div>
    </cx-vui-collapse>

    <cx-vui-collapse
        :collapsed="false"
    >
        <div
            class="cx-vui-subtitle"
            slot="title"><?php _e( 'Sub items styles', 'jet-menu' ) ?></div>
        <div
            class="cx-vui-panel"
            slot="content"
        >
            <cx-vui-switcher
                name="jet-show-sub-menu-desc"
                label="<?php _e( 'Show sub item description', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="true"
                return-false="false"
                v-model="pageOptions['jet-show-sub-menu-desc']['value']"
            >
            </cx-vui-switcher>

			<?php
			jet_menu()->settings_manager->options_manager->render_typography_options( array(
				'name'     => 'jet-sub-menu',
				'label'    => esc_html__( 'Sub level menu', 'jet-menu' ),
			) );

			jet_menu()->settings_manager->options_manager->render_typography_options( array(
				'name'     => 'jet-sub-menu-desc',
				'label'    => esc_html__( 'Sub level menu description', 'jet-menu' ),
			) );
			?>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Items icon styles', 'jet-menu' ); ?></label>

                <cx-vui-input
                    name="jet-menu-sub-icon-size"
                    label="<?php _e( 'Icon size', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="10"
                    :max="50"
                    :step="1"
                    v-model="pageOptions['jet-menu-sub-icon-size']['value']"
                >
                </cx-vui-input>

                <cx-vui-dimensions
                    name="jet-menu-sub-icon-margin"
                    label="<?php _e( 'Icon margin', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    v-model="pageOptions['jet-menu-sub-icon-margin']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-select
                    name="jet-menu-sub-icon-ver-position"
                    label="<?php echo _e( 'Icon vertical position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-sub-icon-ver-position']['options']"
                    v-model="pageOptions['jet-menu-sub-icon-ver-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-select
                    name="jet-menu-sub-icon-hor-position"
                    label="<?php echo _e( 'Icon horizontal position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-sub-icon-hor-position']['options']"
                    v-model="pageOptions['jet-menu-sub-icon-hor-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-input
                    name="jet-menu-sub-icon-order"
                    label="<?php _e( 'Icon order', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="-10"
                    :max="10"
                    :step="1"
                    v-model="pageOptions['jet-menu-sub-icon-order']['value']"
                >
                </cx-vui-input>

            </cx-vui-component-wrapper>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Items badge styles', 'jet-menu' ); ?></label>

                <cx-vui-colorpicker
                    name="jet-menu-sub-badge-text-color"
                    label="<?php _e( 'Badge Text color', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    v-model="pageOptions['jet-menu-sub-badge-text-color']['value']"
                ></cx-vui-colorpicker><?php

		        jet_menu()->settings_manager->options_manager->render_typography_options( array(
			        'name'     => 'jet-menu-sub-badge',
			        'label'    => esc_html__( 'Badge', 'jet-menu' ),
		        ) );

		        jet_menu()->settings_manager->options_manager->render_background_options( array(
			        'name'  => 'jet-menu-sub-badge-bg',
			        'label' => esc_html__( 'Badge', 'jet-menu' ),
		        ) );

		        jet_menu()->settings_manager->options_manager->render_border_options( array(
			        'name'     => 'jet-menu-sub-badge',
			        'label'    => esc_html__( 'Badge', 'jet-menu' ),
		        ) );

		        jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
			        'name'     => 'jet-menu-sub-badge',
			        'label'    => esc_html__( 'Badge', 'jet-menu' ),
		        ) );

		        ?><cx-vui-dimensions
                    name="jet-menu-sub-badge-border-radius"
                    label="<?php _e( 'Panel border radius', 'jet-menu' ); ?>"
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
                    v-model="pageOptions['jet-menu-sub-badge-border-radius']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-dimensions
                    name="jet-menu-sub-badge-padding"
                    label="<?php _e( 'Badge padding', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    :units="[
                        {
                            unit: 'px',
                            min: 0,
                            max: 100,
                            step: 1
                        }
                    ]"
                    v-model="pageOptions['jet-menu-sub-badge-padding']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-dimensions
                    name="jet-menu-sub-badge-margin"
                    label="<?php _e( 'Badge margin', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    :units="[
                        {
                            unit: 'px',
                            min: -50,
                            max: 100,
                            step: 1
                        }
                    ]"
                    v-model="pageOptions['jet-menu-sub-badge-margin']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-select
                    name="jet-menu-sub-badge-ver-position"
                    label="<?php echo _e( 'Badge vertical position (may be overridden with order)', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-sub-badge-ver-position']['options']"
                    v-model="pageOptions['jet-menu-sub-badge-ver-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-select
                    name="jet-menu-sub-badge-hor-position"
                    label="<?php echo _e( 'Badge horizontal position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-sub-badge-hor-position']['options']"
                    v-model="pageOptions['jet-menu-sub-badge-hor-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-input
                    name="jet-menu-sub-badge-order"
                    label="<?php _e( 'Badge order', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="-10"
                    :max="10"
                    :step="1"
                    v-model="pageOptions['jet-menu-sub-badge-order']['value']">
                </cx-vui-input>

                <cx-vui-switcher
                    name="jet-menu-sub-badge-hide"
                    label="<?php _e( 'Hide badge on mobile', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    return-true="true"
                    return-false="false"
                    v-model="pageOptions['jet-menu-sub-badge-hide']['value']"
                >
                </cx-vui-switcher>

            </cx-vui-component-wrapper>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Drop-down arrow styles', 'jet-menu' ); ?></label>

                <cx-vui-select
                    name="jet-menu-sub-arrow-type"
                    label="<?php _e( 'Dropdown type', 'jet-menu' ); ?>"
                    size="fullwidth"
                    :wrapper-css="[ 'equalwidth' ]"
                    :options-list="[
                        {
                            label: 'Font icon',
                            value: 'icon',
                        },
                         {
                            label: 'Svg',
                            value: 'svg',
                        },
                    ]"
                    v-model="pageOptions['jet-menu-sub-arrow-type']['value']"
                >
                </cx-vui-select>

                <cx-vui-iconpicker
                    name="jet-menu-sub-arrow"
                    label="<?php _e( 'Dropdown icon', 'jet-menu' ); ?>"
                    icon-base="fa"
                    :icons="arrowsIcons"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    v-model="pageOptions['jet-menu-sub-arrow']['value']"
                    :conditions="[
                        {
                            input: pageOptions['jet-menu-sub-arrow-type']['value'],
                            compare: 'equal',
                            value: 'icon',
                        }
                    ]"
                ></cx-vui-iconpicker>

                <cx-vui-wp-media
                    name="jet-menu-sub-arrow-svg"
                    label="<?php _e( 'Dropdown SVG icon', 'jet-menu' ); ?>"
                    return-type="string"
                    :multiple="false"
                    :wrapper-css="[ 'equalwidth' ]"
                    v-model="pageOptions['jet-menu-sub-arrow-svg']['value']"
                    :conditions="[
                        {
                            input: pageOptions['jet-menu-sub-arrow-type']['value'],
                            compare: 'equal',
                            value: 'svg',
                        }
                    ]"
                ></cx-vui-wp-media>

                <cx-vui-input
                    name="jet-menu-sub-arrow-size"
                    label="<?php _e( 'Arrow size', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="10"
                    :max="150"
                    :step="1"
                    v-model="pageOptions['jet-menu-sub-arrow-size']['value']">
                </cx-vui-input>

                <cx-vui-dimensions
                    name="jet-menu-sub-arrow-margin"
                    label="<?php _e( 'Arrow margin', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    :units="[
                        {
                            unit: 'px',
                            min: -50,
                            max: 100,
                            step: 1
                        }
                    ]"
                    v-model="pageOptions['jet-menu-sub-arrow-margin']['value']"
                >
                </cx-vui-dimensions>

                <cx-vui-select
                    name="jet-menu-sub-arrow-ver-position"
                    label="<?php echo _e( 'Arrow vertical position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-sub-arrow-ver-position']['options']"
                    v-model="pageOptions['jet-menu-sub-arrow-ver-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-select
                    name="jet-menu-sub-arrow-hor-position"
                    label="<?php echo _e( 'Arrow horizontal position', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    :options-list="pageOptions['jet-menu-sub-arrow-hor-position']['options']"
                    v-model="pageOptions['jet-menu-sub-arrow-hor-position']['value']"
                >
                </cx-vui-select>

                <cx-vui-input
                    name="jet-menu-sub-arrow-order"
                    label="<?php _e( 'Arrow order', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="-10"
                    :max="10"
                    :step="1"
                    v-model="pageOptions['jet-menu-sub-arrow-order']['value']">
                </cx-vui-input>

            </cx-vui-component-wrapper>


            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control', 'states' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Sub items states', 'jet-menu' ); ?></label>

                <cx-vui-tabs
                    class="horizontal-tabs"
                    :in-panel="true"
                    layout="horizontal"
                >

					<?php
					$tabs = array(
						'default' => array(
							'label'  => esc_html__( 'Default', 'jet-menu' ),
							'prefix' => ''
						),
						'hover' => array(
							'label'  => esc_html__( 'Hover', 'jet-menu' ),
							'prefix' => '-hover'
						),
						'active' => array(
							'label'  => esc_html__( 'Active', 'jet-menu' ),
							'prefix' => '-active'
						),
					);

					foreach ( $tabs as $tab => $state ) {

						$label = $state['label'];
						$prefix = $state['prefix'];

						?><cx-vui-tabs-panel
                        name="<?php echo 'sub-menu-items-' . $tab . '-styles'; ?>"
                        label="<?php echo $label; ?>"
                        key="<?php echo 'sub-menu-items-' . $tab . '-styles'; ?>"
                        >
                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-sub-text-color' . $prefix; ?>"
                                label="<?php _e( 'Sub item text color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-sub-text-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-sub-desc-color' . $prefix; ?>"
                                label="<?php _e( 'Sub item description color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-sub-desc-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-sub-icon-color' . $prefix; ?>"
                                label="<?php _e( 'Sub item icon color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-sub-icon-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

                        <cx-vui-colorpicker
                                name="<?php echo 'jet-menu-sub-arrow-color' . $prefix; ?>"
                                label="<?php _e( 'Sub item arrow color', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="pageOptions['<?php echo 'jet-menu-sub-arrow-color' . $prefix; ?>']['value']"
                        ></cx-vui-colorpicker>

						<?php

						jet_menu()->settings_manager->options_manager->render_background_options( array(
							'name'  => 'jet-menu-sub' . $prefix,
							'label' => esc_html__( 'Sub item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_border_options( array(
							'name'     => 'jet-menu-sub' . $prefix,
							'label'    => esc_html__( 'Sub item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_border_options( array(
							'name'     => 'jet-menu-sub-first' . $prefix,
							'label'    => esc_html__( 'First sub item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_border_options( array(
							'name'     => 'jet-menu-sub-last' . $prefix,
							'label'    => esc_html__( 'Last sub item', 'jet-menu' ),
						) );

						jet_menu()->settings_manager->options_manager->render_box_shadow_options( array(
							'name'     => 'jet-menu-sub' . $prefix,
							'label'    => esc_html__( 'Sub item', 'jet-menu' ),
						) );
						?>

                        <cx-vui-dimensions
                                name="<?php echo 'jet-menu-sub-border-radius' . $prefix; ?>"
                                label="<?php _e( 'Sub item border radius', 'jet-menu' ); ?>"
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
                                v-model="pageOptions['<?php echo 'jet-menu-sub-border-radius' . $prefix; ?>']['value']"
                        >
                        </cx-vui-dimensions>

                        <cx-vui-dimensions
                                name="<?php echo 'jet-menu-sub-padding' . $prefix; ?>"
                                label="<?php _e( 'Sub item padding', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                :units="[
									{
										unit: 'px',
										min: 0,
										max: 100,
										step: 1
									}
								]"
                                v-model="pageOptions['<?php echo 'jet-menu-sub-padding' . $prefix; ?>']['value']"
                        >
                        </cx-vui-dimensions>

                        <cx-vui-dimensions
                                name="<?php echo 'jet-menu-sub-margin' . $prefix; ?>"
                                label="<?php _e( 'Sub item margin', 'jet-menu' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                :units="[
									{
										unit: 'px',
										min: 0,
										max: 100,
										step: 1
									}
								]"
                                v-model="pageOptions['<?php echo 'jet-menu-sub-margin' . $prefix; ?>']['value']"
                        >
                        </cx-vui-dimensions>

                        </cx-vui-tabs-panel><?php
					}
					?>

                </cx-vui-tabs>
            </cx-vui-component-wrapper>

        </div>
    </cx-vui-collapse>

</div>
