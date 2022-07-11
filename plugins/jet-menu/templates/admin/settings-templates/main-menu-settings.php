<div class="jet-menu-settings-page jet-menu-settings-page__main-menu">
    <cx-vui-collapse
        :collapsed="false"
    >
        <div
            class="cx-vui-subtitle"
            slot="title"><?php _e( 'Layout', 'jet-menu' ) ?>
        </div>
        <div
            class="cx-vui-panel"
            slot="content"
        >
            <cx-vui-select
                name="jet-mega-menu-layout"
                label="<?php _e( 'Layout', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-layout']['options']"
                v-model="pageOptions['jet-mega-menu-layout']['value']"
            >
            </cx-vui-select>

            <cx-vui-select
                name="jet-mega-menu-dropdown-layout"
                label="<?php _e( 'Dropdown Layout', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-dropdown-layout']['options']"
                v-model="pageOptions['jet-mega-menu-dropdown-layout']['value']"
                :conditions="[
					{
						input: this.pageOptions['jet-mega-menu-layout']['value'],
						compare: 'equal',
						value: 'dropdown',
					}
				]"
            >
            </cx-vui-select>

            <cx-vui-select
                name="jet-mega-menu-dropdown-position"
                label="<?php _e( 'Dropdown Position', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-dropdown-position']['options']"
                v-model="pageOptions['jet-mega-menu-dropdown-position']['value']"
                :conditions="[
					{
						input: this.pageOptions['jet-mega-menu-layout']['value'],
						compare: 'equal',
						value: 'dropdown',
					}
				]"
            >
            </cx-vui-select>

            <cx-vui-select
                name="jet-mega-menu-sub-menu-position"
                label="<?php _e( 'Sub Position', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-sub-menu-position']['options']"
                v-model="pageOptions['jet-mega-menu-sub-menu-position']['value']"
                :conditions="[
                    {
                        input: this.pageOptions['jet-mega-menu-layout']['value'],
                        compare: 'in',
                        value: ['horizontal', 'vertical'],
                    }
                ]"
            >
            </cx-vui-select>

            <cx-vui-select
                name="jet-mega-menu-sub-animation"
                label="<?php _e( 'Sub Animation', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-sub-animation']['options']"
                v-model="pageOptions['jet-mega-menu-sub-animation']['value']"
            >
            </cx-vui-select>

            <cx-vui-select
                name="jet-mega-menu-sub-menu-event"
                label="<?php _e( 'Sub Trigger', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-sub-menu-event']['options']"
                v-model="pageOptions['jet-mega-menu-sub-menu-event']['value']"
            >
            </cx-vui-select>

            <cx-vui-select
                name="jet-mega-menu-sub-menu-trigger"
                label="<?php _e( 'Sub Target', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-sub-menu-trigger']['options']"
                v-model="pageOptions['jet-mega-menu-sub-menu-trigger']['value']"
            >
            </cx-vui-select>

            <cx-vui-input
                name="jet-mega-menu-dropdown-breakpoint"
                label="<?php _e( 'Breakpoint', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="number"
                :min="0"
                :max="2000"
                :step="1"
                v-model="pageOptions['jet-mega-menu-dropdown-breakpoint']['value']">
            </cx-vui-input>

            <cx-vui-switcher
                name="jet-mega-menu-roll-up"
                label="<?php _e( 'Menu RollUp', 'jet-menu' ); ?>"
                description="<?php _e( 'Enable this option in order to reduce the menu size by groupping extra menu items and hiding them under the suspension dots.', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="true"
                return-false="false"
                v-model="pageOptions['jet-mega-menu-roll-up']['value']"
            >
            </cx-vui-switcher>

            <cx-vui-select
                name="jet-mega-menu-roll-up-type"
                label="<?php _e( 'RollUp Type', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-roll-up-type']['options']"
                v-model="pageOptions['jet-mega-menu-roll-up-type']['value']"
            >
            </cx-vui-select>

            <cx-vui-input
                name="jet-mega-menu-roll-up-text"
                label="<?php _e( 'RollUp Text', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="text"
                v-model="pageOptions['jet-mega-menu-roll-up-text']['value']"
                :conditions="[
                    {
                        input: pageOptions['jet-mega-menu-roll-up-type']['value'],
                        compare: 'equal',
                        value: 'text',
                    }
                ]"
            >
            </cx-vui-input>

            <cx-vui-wp-media
                label="<?php _e( 'RollUp Svg Icon', 'jet-menu' ); ?>"
                name="jet-mega-menu-roll-up-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-mega-menu-roll-up-icon']['value']"
                :conditions="[
                    {
                        input: pageOptions['jet-mega-menu-roll-up-type']['value'],
                        compare: 'equal',
                        value: 'icon',
                    }
                ]"
            ></cx-vui-wp-media>

            <cx-vui-wp-media
                label="<?php _e( 'Dropdown Icon', 'jet-menu' ); ?>"
                name="jet-mega-menu-dropdown-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-mega-menu-dropdown-icon']['value']"
            ></cx-vui-wp-media>

            <cx-vui-wp-media
                label="<?php _e( 'Toggle Icon', 'jet-menu' ); ?>"
                name="jet-mega-menu-toggle-default-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-mega-menu-toggle-default-icon']['value']"
                :conditions="[
                {
                    input: pageOptions['jet-mega-menu-layout']['value'],
                    compare: 'equal',
                    value: 'dropdown',
                }
            ]"
            ></cx-vui-wp-media>

            <cx-vui-wp-media
                label="<?php _e( 'Toggle Opened Icon', 'jet-menu' ); ?>"
                name="jet-mega-menu-toggle-opened-icon"
                return-type="string"
                :multiple="false"
                :wrapper-css="[ 'equalwidth' ]"
                v-model="pageOptions['jet-mega-menu-toggle-opened-icon']['value']"
                :conditions="[
                {
                    input: pageOptions['jet-mega-menu-layout']['value'],
                    compare: 'equal',
                    value: 'dropdown',
                }
            ]"
            ></cx-vui-wp-media>

            <cx-vui-switcher
                name="jet-mega-menu-use-mobile-render"
                label="<?php _e( 'Use Mobile Render', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                return-true="true"
                return-false="false"
                v-model="pageOptions['jet-mega-menu-use-mobile-render']['value']"
            >
            </cx-vui-switcher>

            <cx-vui-select
                name="jet-mega-menu-mobile-device"
                label="<?php _e( 'Mobile Device', 'jet-menu' ); ?>"
                description="<a href='<?php echo \Jet_Dashboard\Dashboard::get_instance()->get_dashboard_page_url( 'settings-page', 'jet-menu-mobile-menu-settings' ); ?>'><?php echo __( 'Go to Mobile Settings', 'jet-menu' ); ?></a>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                :options-list="pageOptions['jet-mega-menu-mobile-device']['options']"
                v-model="pageOptions['jet-mega-menu-mobile-device']['value']"
                :conditions="[
					{
						input: this.pageOptions['jet-mega-menu-use-mobile-render']['value'],
						compare: 'equal',
						value: 'true',
					}
				]"
            >
            </cx-vui-select>

        </div>

    </cx-vui-collapse>

    <cx-vui-collapse
        :collapsed="false"
    >
        <div
            class="cx-vui-subtitle"
            slot="title"><?php _e( 'Main Menu', 'jet-menu' ) ?>
        </div>
        <div
            class="cx-vui-panel"
            slot="content"
        >
            <cx-vui-input
                name="jet-mega-menu-container-width"
                label="<?php _e( 'Container Width', 'jet-menu' ); ?>"
                :wrapper-css="[ 'equalwidth' ]"
                size="fullwidth"
                type="number"
                :min="200"
                :max="1980"
                :step="1"
                v-model="pageOptions['jet-mega-menu-container-width']['value']">
            </cx-vui-input>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control', 'states' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Levels', 'jet-menu' ); ?></label>
                <cx-vui-tabs
                    class="horizontal-tabs"
                    :in-panel="true"
                    layout="horizontal"
                >
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-top-level"
                        label="<?php echo _e( 'Top', 'jet-menu' ); ?>"
                        key="jet-mega-menu-top-level"
                    ><?php
	                    jet_menu()->settings_manager->options_manager->render_typography_options( array(
		                    'name'     => 'jet-mega-menu-top-typography',
		                    'label'    => esc_html__( 'Items', 'jet-menu' ),
	                    ) );
                    ?>
                        <cx-vui-input
                            name="jet-mega-menu-items-ver-padding"
                            label="<?php _e( 'Items Vertical Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-items-ver-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-items-hor-padding"
                            label="<?php _e( 'Items Horizontal Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-items-hor-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-items-gap"
                            label="<?php _e( 'Items Space', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-items-gap']['value']">
                        </cx-vui-input>
                    </cx-vui-tabs-panel>
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-sub-level"
                        label="<?php echo _e( 'Sub', 'jet-menu' ); ?>"
                        key="jet-mega-menu-sub-level"
                    ><?php
	                    jet_menu()->settings_manager->options_manager->render_typography_options( array(
		                    'name'     => 'jet-mega-menu-sub-typography',
		                    'label'    => esc_html__( 'Items', 'jet-menu' ),
	                    ) );
                    ?>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-sub-bg-color"
                            label="<?php _e( 'Container Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-sub-bg-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-input
                            name="jet-mega-menu-sub-items-ver-padding"
                            label="<?php _e( 'Items Vertical Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-sub-items-ver-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-sub-items-hor-padding"
                            label="<?php _e( 'Items Horizontal Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-sub-items-hor-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-sub-items-gap"
                            label="<?php _e( 'Items Space', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-sub-items-gap']['value']">
                        </cx-vui-input>
                    </cx-vui-tabs-panel>
                </cx-vui-tabs>
            </cx-vui-component-wrapper>

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
                        name="jet-mega-menu-normal-state"
                        label="<?php echo _e( 'Normal', 'jet-menu' ); ?>"
                        key="jet-mega-menu-normal-state"
                    >
                        <cx-vui-colorpicker
                            name="jet-mega-menu-icon-color"
                            label="<?php _e( 'Icon Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-icon-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-title-color"
                            label="<?php _e( 'Title Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-title-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-badge-color"
                            label="<?php _e( 'Badge Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-badge-color']['value']"
                        >
                        </cx-vui-colorpicker>
                    </cx-vui-tabs-panel>
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-hover-state"
                        label="<?php echo _e( 'Hover', 'jet-menu' ); ?>"
                        key="jet-mega-menu-hover-state"
                    >
                        <cx-vui-colorpicker
                            name="jet-mega-menu-hover-icon-color"
                            label="<?php _e( 'Icon Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-hover-icon-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-hover-title-color"
                            label="<?php _e( 'Title Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-hover-title-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-hover-badge-color"
                            label="<?php _e( 'Badge Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-hover-badge-color']['value']"
                        >
                        </cx-vui-colorpicker>
                    </cx-vui-tabs-panel>
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-top-active-state"
                        label="<?php echo _e( 'Active', 'jet-menu' ); ?>"
                        key="jet-mega-menu-active-state"
                    >
                        <cx-vui-colorpicker
                            name="jet-mega-menu-active-icon-color"
                            label="<?php _e( 'Icon Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-active-icon-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-active-title-color"
                            label="<?php _e( 'Title Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-active-title-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-active-badge-color"
                            label="<?php _e( 'Badge Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-active-badge-color']['value']"
                        >
                        </cx-vui-colorpicker>
                    </cx-vui-tabs-panel>
                </cx-vui-tabs>
            </cx-vui-component-wrapper>

        </div>
    </cx-vui-collapse>

    <cx-vui-collapse
        :collapsed="false"
    >
        <div
            class="cx-vui-subtitle"
            slot="title"><?php _e( 'Dropdown', 'jet-menu' ) ?>
        </div>
        <div
            class="cx-vui-panel"
            slot="content"
        >
            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control', 'states' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Levels', 'jet-menu' ); ?></label>
                <cx-vui-tabs
                    class="horizontal-tabs"
                    :in-panel="true"
                    layout="horizontal"
                >
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-dropdown-top-level"
                        label="<?php echo _e( 'Top', 'jet-menu' ); ?>"
                        key="jet-mega-menu-dropdown-top-level"
                    ><?php
				        jet_menu()->settings_manager->options_manager->render_typography_options( array(
					        'name'     => 'jet-mega-menu-dropdown-top-typography',
					        'label'    => esc_html__( 'Items', 'jet-menu' ),
				        ) );
                        ?><cx-vui-input
                            name="jet-mega-menu-dropdown-top-items-ver-padding"
                            label="<?php _e( 'Items Vertical Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-dropdown-top-items-ver-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-dropdown-top-items-hor-padding"
                            label="<?php _e( 'Items Horizontal Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-dropdown-top-items-hor-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-dropdown-top-items-gap"
                            label="<?php _e( 'Items Space', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-dropdown-top-items-gap']['value']">
                        </cx-vui-input>
                    </cx-vui-tabs-panel>
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-dropdown-sub-level"
                        label="<?php echo _e( 'Sub', 'jet-menu' ); ?>"
                        key="jet-mega-menu-dropdown-sub-level"
                    ><?php
				        jet_menu()->settings_manager->options_manager->render_typography_options( array(
					        'name'     => 'jet-mega-menu-dropdown-sub-typography',
					        'label'    => esc_html__( 'Items', 'jet-menu' ),
				        ) );
                        ?><cx-vui-input
                            name="jet-mega-menu-dropdown-sub-items-ver-padding"
                            label="<?php _e( 'Items Vertical Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-dropdown-sub-items-ver-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-dropdown-sub-items-hor-padding"
                            label="<?php _e( 'Items Horizontal Padding', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-dropdown-sub-items-hor-padding']['value']">
                        </cx-vui-input>
                        <cx-vui-input
                            name="jet-mega-menu-dropdown-sub-items-gap"
                            label="<?php _e( 'Items Space', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            size="fullwidth"
                            type="number"
                            :min="0"
                            :max="50"
                            :step="1"
                            v-model="pageOptions['jet-mega-menu-dropdown-sub-items-gap']['value']">
                        </cx-vui-input>
                    </cx-vui-tabs-panel>
                </cx-vui-tabs>
            </cx-vui-component-wrapper>

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
                        name="jet-mega-menu-dropdown-normal-state"
                        label="<?php echo _e( 'Normal', 'jet-menu' ); ?>"
                        key="jet-mega-menu-dropdown-normal-state"
                    >
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-icon-color"
                            label="<?php _e( 'Icon Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-icon-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-title-color"
                            label="<?php _e( 'Title Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-title-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-badge-color"
                            label="<?php _e( 'Badge Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-badge-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-item-bg-color"
                            label="<?php _e( 'Background Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-item-bg-color']['value']"
                        >
                        </cx-vui-colorpicker>
                    </cx-vui-tabs-panel>
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-dropdown-hover-state"
                        label="<?php echo _e( 'Hover', 'jet-menu' ); ?>"
                        key="jet-mega-menu-dropdown-hover-state"
                    >
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-hover-icon-color"
                            label="<?php _e( 'Icon Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-hover-icon-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-hover-title-color"
                            label="<?php _e( 'Title Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-hover-title-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-hover-badge-color"
                            label="<?php _e( 'Badge Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-hover-badge-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-hover-item-bg-color"
                            label="<?php _e( 'Background Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-hover-item-bg-color']['value']"
                        >
                        </cx-vui-colorpicker>
                    </cx-vui-tabs-panel>
                    <cx-vui-tabs-panel
                        name="jet-mega-menu-dropdown-active-state"
                        label="<?php echo _e( 'Active', 'jet-menu' ); ?>"
                        key="jet-mega-menu-dropdown-active-state"
                    >
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-active-icon-color"
                            label="<?php _e( 'Icon Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-active-icon-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-active-title-color"
                            label="<?php _e( 'Title Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-active-title-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-active-badge-color"
                            label="<?php _e( 'Badge Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-active-badge-color']['value']"
                        >
                        </cx-vui-colorpicker>
                        <cx-vui-colorpicker
                            name="jet-mega-menu-dropdown-active-item-bg-color"
                            label="<?php _e( 'Background Color', 'jet-menu' ); ?>"
                            :wrapper-css="[ 'equalwidth' ]"
                            v-model="pageOptions['jet-mega-menu-dropdown-active-item-bg-color']['value']"
                        >
                        </cx-vui-colorpicker>
                    </cx-vui-tabs-panel>
                </cx-vui-tabs>
            </cx-vui-component-wrapper>

            <cx-vui-component-wrapper
                :wrapper-css="[ 'fullwidth-control', 'states' ]"
            >
                <label class="cx-vui-component__label"><?php _e( 'Dropdown Toggle', 'jet-menu' ); ?></label>
                <cx-vui-input
                    name="jet-mega-menu-dropdown-toggle-size"
                    label="<?php _e( 'Toggle Size', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="20"
                    :max="100"
                    :step="1"
                    v-model="pageOptions['jet-mega-menu-dropdown-toggle-size']['value']">
                </cx-vui-input>
                <cx-vui-input
                    name="jet-mega-menu-dropdown-toggle-distance"
                    label="<?php _e( 'Toggle Size', 'jet-menu' ); ?>"
                    :wrapper-css="[ 'equalwidth' ]"
                    size="fullwidth"
                    type="number"
                    :min="-100"
                    :max="100"
                    :step="1"
                    v-model="pageOptions['jet-mega-menu-dropdown-toggle-distance']['value']">
                </cx-vui-input>
            </cx-vui-component-wrapper>

        </div>
    </cx-vui-collapse>
</div>
