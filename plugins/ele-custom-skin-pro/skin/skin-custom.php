<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* ECS PRO CONTROLS
* 
* Let's add the pro controls
**/

add_action( 'ECS_after_control', function($skin){
 
      $skin->add_control(
          'is_display_conditions',
          [
            'label' => __( '<b>Enable Display Conditions</b>', 'ele-custom-skin' ),
            'description' => __( 'Use templates based on Display Conditions set on Loop Templates', 'ele-custom-skin' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_off' => __( 'No', 'ele-custom-skin' ),
            'label_on' => __( 'Yes', 'ele-custom-skin' ),
            'return_value' => 'yes',
            'separator' => 'before',
            'default' => '',
            'frontend_available' => true,
          ]
      );
  
      $skin->add_control(
			'alternating_templates',
			[
				'label' => __( '<b>Alternating templates</b>', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'ele-custom-skin' ),
				'label_on' => __( 'Show', 'ele-custom-skin' ),
        'return_value' => 'yes',
        'separator' => 'before',
				'default' => '',
        'frontend_available' => true,
        'selectors' =>[' '=>' '],

			]
		);
  
    $repeater = new \Elementor\Repeater();
    
    $repeater->add_control(
			'nth',
			[
				'label' => __( '<i><b>n</b></i> th post', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'label_block' => false,
        'separator' => 'after',
				'placeholder' => __( 'nth', 'ele-custom-skin' ),
				'default' => __( '1', 'ele-custom-skin' ),
        'min' => 1,
				'dynamic' => [
					'active' => true,
				],
			]
		);
    
   
    $repeater->add_control(
			'skin_template',
			[
				'label' => __( 'Select a Loop template', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $skin->get_skin_template(),
			]
		);
    
  $skin->add_control(
			'template_list',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
        'default' => [
            [
              'nth' => 1,
              'skin_template' => []
						],
        ],
        'condition' => [
					$skin->get_id().'_alternating_templates' => 'yes',
				],
				'title_field' => '<p style="text-align:center;"> '.__('Template for every ', 'ele-custom-skin').'{{{nth}}}'.__('th post', 'ele-custom-skin').'</p>',
			]
		);

  $skin->add_control(
			'display_title',
			[
				'label' => __( 'Display Mode', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
			]
		);

     $skin->add_control(
			'masonrys',
			[
				'label' => __( 'Masonry', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'ele-custom-skin' ),
				'label_on' => __( 'On', 'ele-custom-skin' ),
        'return_value' => 'yes',
				'default' => '',
        'frontend_available' => true,
        'condition' => [
					$skin->get_id().'_same_height!' => '100%',
          $skin->get_id().'_post_slider!' => 'yes'
				],
			]
		);
  
    $skin->add_control(
			'same_height',
			[
				'label' => __( 'Same Height', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'ele-custom-skin' ),
				'label_on' => __( 'On', 'ele-custom-skin' ),
        'return_value' => '100%',
				'default' => 'auto',
				'selectors' => [
					 '{{WRAPPER}} .ecs-link-wrapper, 
            {{WRAPPER}} .ecs-custom-grid .ecs-post-loop,
            {{WRAPPER}} .ecs-post-loop > .elementor, 
            {{WRAPPER}} .ecs-post-loop > .elementor .elementor-inner, 
            {{WRAPPER}} .ecs-post-loop > .elementor .elementor-inner .elementor-section-wrap, 
            {{WRAPPER}} .ecs-post-loop > .elementor .elementor-section-wrap,
            {{WRAPPER}} .ecs-post-loop > .elementor > .elementor-section,
            {{WRAPPER}} .ecs-post-loop > .elementor > .e-container,
            {{WRAPPER}} .ecs-post-loop > .ecs-link-wrapper > .elementor > .e-container,
            {{WRAPPER}} .ecs-post-loop > .ecs-link-wrapper > .elementor > .elementor-section,
            {{WRAPPER}} .ecs-link-wrapper > .has-post-thumbnail, .ecs-link-wrapper > .has-post-thumbnail > .elementor-section,
            {{WRAPPER}} .ecs-post-loop > .has-post-thumbnail, .ecs-post-loop > .has-post-thumbnail > .elementor-section,
            {{WRAPPER}} .ecs-post-loop > .elementor .elementor-inner .elementor-section-wrap .elementor-top-section,  
            {{WRAPPER}} .ecs-post-loop > .elementor .elementor-section-wrap .elementor-top-section, 
            {{WRAPPER}} .ecs-post-loop > .elementor .elementor-container'
              => 'height: {{VALUE}} ',
          '{{WRAPPER}} .elementor-post' => 'height:auto',
				],
        'condition' => [
					$skin->get_id().'_masonrys!' => 'yes',
				],
			]
		);
  
  
  /**
  *
  * Starting the slider part
  *
  **/
  
    $skin->add_control(
			'post_slider',
			[
				'label' => __( 'Show in Slider', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'ele-custom-skin' ),
				'label_on' => __( 'On', 'ele-custom-skin' ),
        'return_value' => 'yes',
				'default' => '',
        'frontend_available' => true,
         'condition' => [
					$skin->get_id().'_masonrys!' => 'yes',
				],
			]
		);


  $skin->add_control(
			'slider_title',
			[
				'label' => __( 'Slider Options', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
					$skin->get_id().'_post_slider' => 'yes',
				],
			]
		);
  	$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );
  	$skin->add_responsive_control(
			'slides_to_show',
			[
				'label' => __( 'Slides to Show', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'ele-custom-skin' ),
				] + $slides_to_show,
        'condition' => [
					$skin->get_id().'_post_slider' => 'yes',
				],
				'frontend_available' => true,
        'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--e-posts-slides-to-show: {{VALUE}}',
				],
			]
		);
  	$skin->add_responsive_control(
			'slides_to_scroll',
			[
				'label' => __( 'Slides to Scroll', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Set how many slides are scrolled per swipe.', 'ele-custom-skin' ),
				'options' => [
					'' => __( 'Default', 'ele-custom-skin' ),
				] + $slides_to_show,
				'condition' => [
				  $skin->get_id().'_slides_to_show!' => '1',
          $skin->get_id().'_post_slider' => 'yes',
				],
				'frontend_available' => true,
			]
		);
  	$skin->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'both' => __( 'Arrows and Dots', 'ele-custom-skin' ),
					'arrows' => __( 'Arrows', 'ele-custom-skin' ),
					'dots' => __( 'Dots', 'ele-custom-skin' ),
					'none' => __( 'None', 'ele-custom-skin' ),
				],
        'condition' => [
					$skin->get_id().'_post_slider' => 'yes',
				],
				'frontend_available' => true,
			]
		);
  
	/* 
  
  end slider
  
  */
 
  $skin->add_control(
			'key_title',
			[
				'label' => __( 'Dynamic Everywhere', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
			]
		);
  $skin->add_control(
			'use_keywords',
			[
				'label' => __( 'Using Dynamic Keywords', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ele-custom-skin' ),
				'label_off' => __( 'No', 'ele-custom-skin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
  
     $skin->add_control(
			'keywords_note',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<div>Replace all the dynamic &#123;&#123;keywords&#125;&#125; from the Loop Template.</div>',  
			]
		);
  
      $skin->add_control(
          'link_to',
          [
            'label' => __( '<b>Make entire post a link?</b>', 'ele-custom-skin' ),
            'description' => __( 'Make the entire Loop Template clickable.', 'ele-custom-skin' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_off' => __( 'No', 'ele-custom-skin' ),
            'label_on' => __( 'Yes', 'ele-custom-skin' ),
            'return_value' => 'yes',
            'separator' => 'before',
            'default' => '',
          ]
      );
  
  		$skin->add_control(
			'postlink',
			[
				'label' => __( 'Link', 'ele-custom-skin' ),
        'description' => __( 'For this link to be SEO friendly please add a link to title or other widgets inside Loop Template.', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'ele-custom-skin' ),
				'condition' => [
					$skin->get_id().'_link_to' => 'yes',
				],
				'show_label' => false,
			]
		);

} , 10, 3 );


/**
* ECS PRO Make Items Clickable
* 
* Let's add a link to the items
**/

add_action( 'ECS_after_render_post_header', function($skin){

  if($skin->get_instance_value( 'link_to' )){
    $link= $skin->get_settings_for_display( $skin->get_id().'_postlink' );
    $url = $link['url'];
    $target = $link['is_external']? 'data-target = "_blank"' :"";
    $nofollow = $link['nofollow']? 'data-rel = "nofollow"' :"";
    $attributes="";
    if($link['custom_attributes']){
      $attributes = str_replace("|",'="',$link['custom_attributes']);
      $attributes = str_replace(",",'" ',$attributes);
      $attributes .='"';
    }
    echo "<div class=\"ecs-link-wrapper\" data-href=\"$url\" $target $nofollow $attributes>";
  
  }
});

add_action( 'ECS_before_render_post_footer', function($skin){
    if($skin->get_instance_value( 'link_to' )){
      echo "</div>";
    }
});

/**
* ECS PRO Alternative templates
* 
* Let's handle alternatice templates
**/

add_action( 'ECS_action_template', function($template,$skin,$ecs_index){
    $default_template = $template;
    if($skin->get_instance_value( 'is_display_conditions' )) {
      $condition_template = $skin->conditions->get_template();
      $default_template = $condition_template ? $condition_template : $default_template;
    }
    //print_r($skin->conditions->get_template());echo "aici";
    $template_list = $skin->get_instance_value( 'template_list' );
    $skin_count=1;
    $alt_templates=[];
    foreach($template_list as $new_template){
      $alt_templates[$new_template['nth']] = $new_template['skin_template'];
      $skin_count=$new_template['nth'] > $skin_count ? $new_template['nth'] : $skin_count;
    }
    $skin_index= $ecs_index %  $skin_count ? $ecs_index %  $skin_count  : $skin_count ;
    //echo $skin_count."-".$skin_index;
    //print_r($alt_templates);
    $alt_templates[$skin_index] = isset($alt_templates[$skin_index]) ? $alt_templates[$skin_index] : "";
    $template = $alt_templates[$skin_index] ? $alt_templates[$skin_index]  : $default_template;
    return $template;
} , 10, 3 );

/**
* ECS PRO More CONTROLS
* 
* Let's add the some controls after pagination
**/

add_action( 'ECS_after_pagination_controls', function($skin){
      $skin->start_controls_section(
			$skin->get_id().'_section_additional_options',
			[
				'label' => __( 'Slider Options', 'ele-custom-skin' ),
        'condition' => [
					$skin->get_id().'_post_slider' => 'yes',
				],
			]
		);

		$skin->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ele-custom-skin' ),
					'no' => __( 'No', 'ele-custom-skin' ),
				],
				'frontend_available' => true,
			]
		);

		$skin->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ele-custom-skin' ),
					'no' => __( 'No', 'ele-custom-skin' ),
				],
				'frontend_available' => true,
			]
		);

		$skin->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5000,
				'frontend_available' => true,
			]
		);

		$skin->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ele-custom-skin' ),
					'no' => __( 'No', 'ele-custom-skin' ),
				],
				'frontend_available' => true,
			]
		);

		$skin->add_control(
			'effect',
			[
				'label' => __( 'Effect', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'ele-custom-skin' ),
					'fade' => __( 'Fade', 'ele-custom-skin' ),
				],
				'condition' => [
					'slides_to_show' => '1',
				],
				'frontend_available' => true,
			]
		);

		$skin->add_control(
			'speed',
			[
				'label' => __( 'Animation Speed', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$skin->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => [
					'ltr' => __( 'Left', 'ele-custom-skin' ),
					'rtl' => __( 'Right', 'ele-custom-skin' ),
				],
				'frontend_available' => true,
			]
		);

		$skin->end_controls_section();
});

/**
* ECS PRO STYLE CONTROLS
* 
* Let's add the pro style controls
**/

add_action( 'ECS_after_style_controls', function($skin){
		$skin->start_controls_section(
			'section_style_navigation',
			[
				'label' => __( 'Navigation', 'ele-custom-skin' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
          $skin->get_id().'_post_slider' => 'yes',
				],
			]
		);

		$skin->add_control(
			'heading_style_slides',
			[
				'label' => __( 'Slides', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
          $skin->get_id().'_post_slider' => 'yes',
				],
			]
		);
    
    $skin->add_control(
			'slide_gap',
			[
				'label' => __( 'Slide Gap', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
        'frontend_available' => true,
				'condition' => [
          $skin->get_id().'_post_slider' => 'yes',
				],
			]
		);
    
    $skin->add_control(
			'slider_padding',
			[
				'label' => __( 'Slider Padding', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
        'selectors' => [
          '{{WRAPPER}} .swiper-container' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
          $skin->get_id().'_post_slider' => 'yes',
				],
			]
		);
    

  
      $skin->add_control(
			'heading_style_arrows',
			[
				'label' => __( 'Arrows', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$skin->get_id().'_navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$skin->add_control(
			'arrows_size',
			[
				'label' => __( 'Size', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$skin->get_id().'_navigation' => [ 'arrows', 'both' ],
				],
			]
		);
    
    $skin->add_control(
			'arrows_shift',
			[
				'label' => __( 'Shift', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
        'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -60,
						'max' => 60,
					],
				],
				'selectors' => [
          '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .swiper-container' => 'position:static',
				],
				'condition' => [
					$skin->get_id().'_navigation' => [ 'arrows', 'both' ],
				],
			]
		);

  
  /*
  *
  *
  * Start arrow controllers
  *
  *
  */
  
  
  $skin->start_controls_tabs( 'tabs_arrow_style' );

		$skin->start_controls_tab(
			'tab_arrow_normal',
			[
				'label' => __( 'Normal', 'ele-custom-skin' ),
			]
		);

		$skin->add_control(
			'arrow_color',
			[
				'label' => __( 'Arrow Color', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
	          '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}};',
				],
			]
		);

		$skin->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => Elementor\Core\Schemes\Color::get_type(),
					'value' => Elementor\Core\Schemes\Color::COLOR_4,
				],
				'selectors' => [
					 '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$skin->end_controls_tab();

		$skin->start_controls_tab(
			'tab_arrow_hover',
			[
				'label' => __( 'Hover', 'ele-custom-skin' ),
			]
		);

		$skin->add_control(
			'arrow_hover_color',
			[
				'label' => __( 'Arrow Color', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
	          '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$skin->add_control(
			'arrow_background_hover_color',
			[
				'label' => __( 'Background Color', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$skin->add_control(
			'arrow_hover_border_color',
			[
				'label' => __( 'Border Color', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$skin->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);

		$skin->end_controls_tab();

		$skin->end_controls_tabs();

		$skin->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next',
				'separator' => 'before',
			]
		);

		$skin->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$skin->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next',
			]
		);

		$skin->add_responsive_control(
			'arrow_padding',
			[
				'label' => __( 'Padding', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
  
  
  
  /*
  *
  * END ARROWS  CONTROLERES
  *
  */

  		$skin->add_control(
			'heading_style_dots',
			[
				'label' => __( 'Dots', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$skin->get_id().'_navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$skin->add_control(
			'dots_position',
			[
				'label' => __( 'Position', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'outside' => __( 'Outside', 'ele-custom-skin' ),
					'inside' => __( 'Inside', 'ele-custom-skin' ),
				],
				'prefix_class' => 'elementor-pagination-position-',
				'condition' => [
					$skin->get_id().'_navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$skin->add_control(
			'dots_size',
			[
				'label' => __( 'Size', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$skin->get_id().'_navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$skin->add_control(
			'dots_color',
			[
				'label' => __( 'Color', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
				],
				'condition' => [
					$skin->get_id().'_navigation' => [ 'dots', 'both' ],
				],
			]
		);
  	$skin->end_controls_section();

});

/**
* ECS PRO SLiDER Elements
* 
* Let's add slider elements
**/

add_action( 'ECS_after_slider_elements', function($skin){
    $settings=$skin->settings;
    if (!isset($settings[$skin->get_id().'_navigation'])) return;
    $show_dots = ( in_array( $settings[$skin->get_id().'_navigation'], [ 'dots', 'both' ] ) );
    $show_arrows = ( in_array( $settings[$skin->get_id().'_navigation'], [ 'arrows', 'both' ] ) );
    $slides_count = true ;

     if ( $slides_count ) {
      ?>
				<?php if ( $show_dots ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
				<?php if ( $show_arrows ) : ?>
					<div class="elementor-swiper-button elementor-swiper-button-prev">
						<i class="eicon-chevron-left" aria-hidden="true"></i>
						<span class="elementor-screen-only"><?php _e( 'Previous', 'elementor' ); ?></span>
					</div>
					<div class="elementor-swiper-button elementor-swiper-button-next">
						<i class="eicon-chevron-right" aria-hidden="true"></i>
						<span class="elementor-screen-only"><?php _e( 'Next', 'elementor' ); ?></span>
					</div>
				<?php endif; ?>
			<?php 
     }
});

?>