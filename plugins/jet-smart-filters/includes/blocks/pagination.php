<?php
/**
 * Pagination Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Pagination' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Pagination class
	 */
	class Jet_Smart_Filters_Block_Pagination extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block namepagination
		 *
		 * @return string
		 */
		public function get_name() {
			return 'pagination';
		}
	public function set_css_scheme(){
			$this->css_scheme = apply_filters(
				'jet-smart-filters/widgets/pagination/css-scheme',
				[
					'pagination'              => '.jet-filters-pagination',
					'pagination-item'         => '.jet-filters-pagination__item',
					'pagination-link'         => '.jet-filters-pagination__link',
					'pagination-link-current' => '.jet-filters-pagination__current .jet-filters-pagination__link',
					'pagination-dots'         => '.jet-filters-pagination__dots',
				]
			);
		}

		public function add_style_manager_options(){

			$this->controls_manager->start_section(
				'style_controls',
				[
					'id'          => 'pagination_style',
					'initialOpen' => true,
					'title'       => esc_html__( 'Pagination', 'jet-smart-filters' ),
					'condition' => [
						'enable_prev_next' => true,
					],
				]
			);

			$this->controls_manager->add_control([
				'id'           => 'pagination_background_color',
				'type'         => 'color-picker',
				'separator'    => 'after',
				'label'        => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination'] => 'background-color: {{VALUE}};',
				),
			]);

			$this->controls_manager->add_control([
				'id'           => 'pagination_border',
				'type'         => 'border',
				'label'        => esc_html__( 'Border', 'jet-smart-filters' ),
				'separator'    => 'after',
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination'] => 'border-style:{{STYLE}};border-width:{{WIDTH}};border-radius:{{RADIUS}};border-color:{{COLOR}};',
				),
			]);

			$this->controls_manager->add_control([
				'id'         => 'pagination_padding',
				'type'       => 'dimensions',
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'units'      => array( 'px', '%' ),
				'css_selector'  => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination'] => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
				),
			]);

			$this->controls_manager->end_section();

			$this->controls_manager->start_section(
				'style_controls',
				[
					'id'          => 'pagination_items_style',
					'initialOpen' => true,
					'title'       => esc_html__( 'Items', 'jet-smart-filters' )
				]
			);

			$this->controls_manager->add_control([
				'id'         => 'pagination_items_typography',
				'type'       => 'typography',
				'css_selector' => [
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] . ', {{WRAPPER}} ' . $this->css_scheme['pagination-dots'] => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
				],
			]);

			$this->controls_manager->start_tabs(
				'style_controls',
				[
					'id' => 'tabs_pagination_items_style',
					'separator'  => 'both',
				]
			);

			$this->controls_manager->start_tab(
				'style_controls',
				[
					'id'    => 'pagination_items_normal',
					'title' => esc_html__( 'Normal', 'jet-smart-filters' ),
				]
			);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_color',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $this->css_scheme['pagination-dots'] => 'color: {{VALUE}}',
				),
			]);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_bg_color',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $this->css_scheme['pagination-dots'] => 'background-color: {{VALUE}}',
				),
			]);
			$this->controls_manager->end_tab();

			$this->controls_manager->start_tab(
				'style_controls',
				[
					'id'    => 'pagination_items_hover',
					'title' => esc_html__( 'Hover', 'jet-smart-filters' ),
				]
			);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_color_hover',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] . ':hover' => 'color: {{VALUE}}',
				),
			]);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_bg_color_hover',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] . ':hover' => 'background-color: {{VALUE}}',
				),
			]);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_hover_border_color',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] . ':hover' => 'border-color: {{VALUE}};',
				),
			]);
			$this->controls_manager->end_tab();

			$this->controls_manager->start_tab(
				'style_controls',
				[
					'id'    => 'pagination_items_active',
					'title' => esc_html__( 'Current', 'jet-smart-filters' ),
				]
			);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_bg_color_active',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link-current'] => 'color: {{VALUE}}',
				),
			]);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_color_active',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link-current'] => 'background-color: {{VALUE}}',
				),
			]);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_active_border_color',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link-current'] => 'border-color: {{VALUE}};',
				),
			]);
			$this->controls_manager->end_tab();

			$this->controls_manager->start_tab(
				'style_controls',
				[
					'id'    => 'pagination_items_dots',
					'title' => esc_html__( 'Dots', 'jet-smart-filters' ),
				]
			);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_color_dots',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-dots'] => 'color: {{VALUE}}',
				),
			]);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_bg_color_dots',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-dots'] => 'background-color: {{VALUE}}',
				),
			]);
			$this->controls_manager->add_control([
				'id'           => 'pagination_items_dots_border_color',
				'type'         => 'color-picker',
				'label'        => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'css_selector' => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-item'] . ' ' . $this->css_scheme['pagination-dots'] => 'border-color: {{VALUE}};',
				),
			]);
			$this->controls_manager->end_tab();

			$this->controls_manager->end_tabs();

			$this->controls_manager->add_control([
				'id'         => 'pagination_items_padding',
				'type'       => 'dimensions',
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'units'      => [ 'px', '%', 'em' ],
				'css_selector'  => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
					'{{WRAPPER}} ' . $this->css_scheme['pagination-dots'] => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
				),
			]);
			$this->controls_manager->add_control([
				'id'        => 'pagination_items_horizontal_gap',
				'type'      => 'range',
				'label'     => esc_html__( 'Horizontal Gap Between Items', 'jet-smart-filters' ),
				'separator' => 'before',
				'css_selector' => [
					'{{WRAPPER}} ' . $this->css_scheme['pagination-item'] => 'margin-right: calc({{VALUE}}{{UNIT}}/2); margin-left: calc({{VALUE}}{{UNIT}}/2);',
					'{{WRAPPER}} ' . $this->css_scheme['pagination']      => 'margin-right: calc(-{{VALUE}}{{UNIT}}/2); margin-left: calc(-{{VALUE}}{{UNIT}}/2);',
				],
				'attributes' => [
					'default' => [
						'value' => 4,
						'unit' => 'px'
					]
				],
				'units' => [
					[
						'value' => 'px',
						'intervals' => [
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						]
					],
				],
			]);
			$this->controls_manager->add_control([
				'id'        => 'pagination_items_vertical_gap',
				'type'      => 'range',
				'label'     => esc_html__( 'Vertical Gap Between Items', 'jet-smart-filters' ),
				'separator'    => 'none',
				'css_selector' => [
					'{{WRAPPER}} ' . $this->css_scheme['pagination-item'] => 'margin-top: calc({{VALUE}}{{UNIT}}/2); margin-bottom: calc({{VALUE}}{{UNIT}}/2)',
					'{{WRAPPER}} ' . $this->css_scheme['pagination']      => 'margin-top: calc(-{{VALUE}}{{UNIT}}/2); margin-bottom: calc(-{{VALUE}}{{UNIT}}/2)',
				],
				'attributes' => [
					'default' => [
						'value' => 4,
						'unit' => 'px'
					]
				],
				'units' => [
					[
						'value' => 'px',
						'intervals' => [
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						]
					],
				],
			]);
			$this->controls_manager->add_control([
				'id'         => 'pagination_items_border',
				'type'       => 'border',
				'separator'  => 'before',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'css_selector'  => array(
					'{{WRAPPER}} ' . $this->css_scheme['pagination-link'] . ', {{WRAPPER}} ' . $this->css_scheme['pagination-dots'] => 'border-style: {{STYLE}}; border-width: {{WIDTH}}; border-radius: {{RADIUS}}; border-color:{{COLOR}}',
				),
			]);

			$this->controls_manager->add_control([
				'id'        => 'pagination_items_alignment',
				'type'      => 'choose',
				'label'     => esc_html__( 'Alignment', 'jet-smart-filters' ),
				'separator'    => 'before',
				'options'   =>[
					'left'    => [
						'shortcut' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'dashicons-editor-alignleft',
					],
					'center'    => [
						'shortcut' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'dashicons-editor-aligncenter',
					],
					'right'    => [
						'shortcut' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'dashicons-editor-alignright',
					],
				],
				'css_selector' => [
					'{{WRAPPER}} ' . $this->css_scheme['pagination'] => 'text-align: {{VALUE}}',
				],
				'attributes' => [
					'default' => [
						'value' => 'left',
					]
				],
			]);

			$this->controls_manager->end_section();
		}


		/**
		 * Return callback
		 *
		 * @return html
		 */
		public function render_callback( $settings = array() ) {

			jet_smart_filters()->set_filters_used();

			if ( empty( $settings['content_provider'] ) || $settings['content_provider'] === 'not-selected' ) {
				return $this->is_editor() ? __( 'Please select a provider', 'jet-smart-filters' ) : false;
			}

			$base_class = 'jet-smart-filters-' . $this->get_name();
			$provider   = $settings['content_provider'];
			$query_id   = 'default';
			$apply_type = $settings['apply_type'];

			$controls_enabled = isset( $settings['enable_prev_next'] ) ? $settings['enable_prev_next'] : '';

			if ( $controls_enabled ) {
				$controls = array(
					'nav'  => true,
					'prev' => $settings['prev_text'],
					'next' => $settings['next_text'],
				);
			} else {
				$controls['nav'] = false;
			}

			$controls['pages_mid_size']  = ! empty( $settings['pages_center_offset'] ) ? absint( $settings['pages_center_offset'] ) : 0;
			$controls['pages_end_size']  =  ! empty( $settings['pages_end_offset'] ) ? absint( $settings['pages_end_offset'] ) : 0;

			ob_start();

			printf(
				'<div
					class="%1$s"
					data-apply-provider="%2$s"
					data-content-provider="%2$s"
					data-query-id="%3$s"
					data-controls="%4$s"
					data-apply-type="%5$s"
				>',
				$base_class,
				$provider,
				$query_id,
				htmlspecialchars( json_encode( $controls ) ),
				$apply_type
			);

			if ( $this->is_editor() ) {
				$pagination_filter_type = jet_smart_filters()->filter_types->get_filter_types( 'pagination' );
				$pagination_filter_type->render_pagination_sample( $controls );
			}

			echo '</div>';

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}
