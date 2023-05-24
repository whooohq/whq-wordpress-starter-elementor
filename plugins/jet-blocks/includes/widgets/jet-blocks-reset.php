<?php
/**
 * Class: Jet_Blocks_Reset
 * Name: Reset Password Form
 * Slug: jet-reset
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Blocks_Reset extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-reset';
	}

	public function get_title() {
		return esc_html__( 'Reset Password Form', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jet-blocks-icon-login';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-add-a-login-form-to-the-header-built-with-elementor/';
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'jet-blocks/reset/css-scheme',
			array(
				'instance'        => '.jet-reset',
				'form'            => '.jet-reset__form',
				'title'           => '.jet-reset__form-title',
				'text'            => '.jet-reset__form-text',
				'error_message'   => '.jet-reset__error-message',
				'success_message' => '.jet-reset__success-message',
				'button'          => '.jet-reset__button',
				'submit'          => '.jet-reset__submit',
			)
		);

		$this->start_controls_section(
			'section_form',
			array(
				'label' => esc_html__( 'Forms', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'form_title',
			array(
				'label'       => esc_html__( 'Title', 'jet-blocks' ),
				'description' => esc_html__( 'Customise the form title.', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Reset Password', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'email_username_field_label',
			array(
				'label'       => esc_html__( 'Username/Email Label', 'jet-blocks' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Email Address or Username', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'new_password_label',
			array(
				'label'       => esc_html__( 'New Password Label', 'jet-blocks' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'New Password', 'jet-blocks' ),
			)
		);

		$this->add_control(
			're_enter_password_label',
			array(
				'label'       => esc_html__( 'Re-enter Password Label', 'jet-blocks' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Re-enter Password', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'lost_form_text',
			array(
				'label'       => esc_html__( 'Lost Password Form Text', 'jet-blocks' ),
				'description' => esc_html__( 'Customise the main lost password form text. Allowed tags are: <a> with "href" and "title" attributes, <br>, <em>, <strong>, <p> with "style" attribute.', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Please enter your email address or username. You will receive a link to create a new password via email.', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'reset_form_text',
			array(
				'label'       => esc_html__( 'Reset Password Form Text', 'jet-blocks' ),
				'description' => esc_html__( 'Customise the new password form text. Allowed tags are: <a> with "href" and "title" attributes, <br>, <em>, <strong>, <p> with "style" attribute. Use the following code to show a minimum password length: %x', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Please enter a new password. Minimum %x characters.', 'jet-blocks' ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'use_password_requirements',
			array(
				'label'        => 'Use Strong Password Validation',
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'password_requirements',
			array(
				'label'       => esc_html__( 'Password Requirements', 'jet-blocks' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'default'     => array( 'length', 'lowercase', 'uppercase', 'number', 'special' ),
				'multiple'    => true,
				'options'     => array(
					'length'    => esc_html__( 'Length', 'jet-blocks' ),
					'lowercase' => esc_html__( 'Lowercase', 'jet-blocks' ),
					'uppercase' => esc_html__( 'Uppercase', 'jet-blocks' ),
					'number'    => esc_html__( 'Number', 'jet-blocks' ),
					'special'   => esc_html__( 'Special character', 'jet-blocks' ),
				),
				'condition' => array(
					'use_password_requirements' => 'yes',
				),
			)
		);

		$this->add_control(
			'minimum_password_length',
			array(
				'label'       => esc_html__( 'Minimum Password Length', 'jet-blocks' ),
				'description' => esc_html__( 'Set a minimum password length. Recommended: 8', 'jet-blocks' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '8',
				'condition'   => array(
					'use_password_requirements!' => 'yes',
				),
			)
		);

		$this->add_control(
			'success_reset_redirect',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Redirect After Password Reset', 'jet-blocks' ),
				'default'    => 'current',
				'options'    => array(
					'home'    => esc_html__( 'Home page', 'jet-blocks' ),
					'current' => esc_html__( 'Stay on the current page', 'jet-blocks' ),
					'static'  => esc_html__( 'Static page', 'jet-blocks' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'static_success_reset_redirect',
			array(
				'label'       => esc_html__( 'Success reset redirect', 'jet-blocks' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'post',
				'query'       => array(
					'post_type' => 'page',
				),
				'condition' => array(
					'success_reset_redirect' => 'static',
				),
			)
		);

		$this->add_control(
			'login_link',
			array(
				'label'   => esc_html__( 'Login link', 'jet-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'login_redirect',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Login page url', 'jet-blocks' ),
				'default'    => 'default',
				'options'    => array(
					'default' => esc_html__( 'Default login page', 'jet-blocks' ),
					'static'  => esc_html__( 'Static page', 'jet-blocks' ),
				),
				'condition'   => array(
					'login_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'static_login_redirect',
			array(
				'label'       => esc_html__( 'Select Login page', 'jet-blocks' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'post',
				'query'       => array(
					'post_type' => 'page',
				),
				'condition' => array(
					'login_redirect' => 'static',
					'login_link'     => 'yes',
				),
			)
		);


		$this->__end_controls_section();

		$this->start_controls_section(
			'section_email',
			array(
				'label' => esc_html__( 'Email settings', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'email_text',
			array(
				'label'       => esc_html__( 'Text', 'jet-blocks' ),
				'description' => esc_html__( 'Customise the new password form text. Use the following codes to show the relevant info in the email:
					Username: {username}
					Reset URL: {reset_link}', 'jet-blocks' ),
				'type'        => Controls_Manager::WYSIWYG,
				'rows'        => 20,
				'default'     => esc_html__( 'Someone requested that the password be reset for the following account:
					Username: {username}
					If this was a mistake, just ignore this email and nothing will happen.
					To reset your password, visit the following address:
					{reset_link}', 'jet-blocks' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'reset_link_label',
			array(
				'label'       => esc_html__( 'Reset Link Label', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			)
		);

		$this->add_control(
			'email_subject',
			array(
				'label'       => esc_html__( 'Email Subject', 'jet-blocks' ),
				'description' => esc_html__( 'Customise the email subject line.', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Account Password Reset', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'custom_sender',
			array(
				'label'     => esc_html__( 'Custom mail sender', 'jet-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'separator' => 'before',
			)
		);

		$this->__add_control(
			'custom_sender_heading',
			array(
				'label'     => esc_html__( 'Both must be completed to work.', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'custom_sender' => 'yes',
				),
			)
		);

		$this->add_control(
			'from_sender',
			array(
				'label'       => esc_html__( 'Sender', 'jet-blocks' ),
				'description' => esc_html__( 'Customise the name the email is sent from.', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => array(
					'custom_sender' => 'yes',
				),
			)
		);

		$this->add_control(
			'from_email',
			array(
				'label'       => esc_html__( 'Email', 'jet-blocks' ),
				'description' => esc_html__( 'Customise the email address the email is sent from.', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => array(
					'custom_sender' => 'yes',
				),
			)
		);

		$this->__end_controls_section();

		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Buttons settings', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'form_button_text',
			array(
				'label'   => esc_html__( 'Reset button text', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Reset Password', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'form_login_button_text',
			array(
				'label'     => esc_html__( 'Login button text', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Sign in', 'jet-blocks' ),
				'condition' => array(
					'login_link' => 'yes',
				),
			)
		);

		$this->__end_controls_section();

		//Styles

		$this->__start_controls_section(
			'form_style',
			array(
				'label'      => esc_html__( 'Form', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'form_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'form_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'form_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'form_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['form'],
			),
			50
		);

		$this->__add_responsive_control(
			'form_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form'],
			),
			100
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'form_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			50
		);

		$this->__add_control(
			'form_title_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'form_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'form_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'form_title_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			75
		);

		$this->__add_responsive_control(
			'form_title_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_title_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			100
		);

		$this->__add_responsive_control(
			'form_title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-blocks-text-align-control',
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'form_text_style',
			array(
				'label'      => esc_html__( 'Text', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['text'],
			),
			50
		);

		$this->__add_control(
			'form_text_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'form_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'form_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'form_text_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['text'],
			),
			75
		);

		$this->__add_responsive_control(
			'form_text_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['text'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_text_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['text'],
			),
			100
		);

		$this->__add_responsive_control(
			'form_text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['text'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-blocks-text-align-control',
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'login_labels_style',
			array(
				'label'      => esc_html__( 'Labels', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'labels_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' label' => 'background-color: {{VALUE}}',
				),
			),
			50
		);

		$this->__add_control(
			'labels_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' label' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'labels_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form'] . ' label',
			),
			50
		);

		$this->__add_responsive_control(
			'labels_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'labels_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'labels_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['form'] . ' label',
			),
			75
		);

		$this->__add_responsive_control(
			'labels_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'labels_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form'] . ' label',
			),
			100
		);

		$this->__add_responsive_control(
			'labels_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' label' => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-blocks-text-align-control',
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'fields_style',
			array(
				'label'      => esc_html__( 'Fields', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'input_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' input.input' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'input_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' input.input' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form'] . ' input.input',
			),
			50
		);

		$this->__add_responsive_control(
			'input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' input.input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'input_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['form'] . ' input.input',
			),
			50
		);

		$this->__add_responsive_control(
			'input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] . ' input.input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form'] . ' input.input',
			),
			100
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'submit_style',
			array(
				'label'      => esc_html__( 'Submit', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__start_controls_tabs( 'tabs_form_submit_style' );

		$this->__start_controls_tab(
			'form_submit_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'submit_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'submit_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'form_submit_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'submit_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]:hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'submit_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]:hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'submit_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'submit_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]:hover' => 'border-color: {{VALUE}};',
				),
			),
			75
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'submit_typography',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]',
				'fields_options' => array(
					'typography' => array(
						'separator' => 'before',
					),
				),
			),
			50
		);

		$this->__add_responsive_control(
			'submit_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'submit_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'login_submit_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]',
			),
			75
		);

		$this->__add_responsive_control(
			'submit_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'submit_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . '[type="submit"]',
			),
			100
		);

		$this->__add_responsive_control(
			'submit_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justify', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['submit'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-blocks-text-align-control',
			),
			50
		);

		$this->__add_control(
			'submit_alignment_justify',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'style',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['submit'] . ' button' => 'width: 100%;',
				),
				'condition'  => array(
					'submit_alignment' => 'justify',
				),
			),
			25
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'login_link_style',
			array(
				'label'      => esc_html__( 'Login Link', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'login_link' => 'yes',
				),
			)
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'login_link_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ' .jet-reset__login-link',
			),
			50
		);

		$this->__add_control(
			'login_link_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' .jet-reset__login-link' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'login_link_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' .jet-reset__login-link:hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'login_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' .jet-reset__login-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'message_style',
			array(
				'label'      => esc_html__( 'Messages', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'message_error_style',
			array(
				'label' => esc_html__( 'Error', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->__add_control(
			'message_error_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['error_message'] => 'background-color: {{VALUE}}',
				),
			),
			50
		);

		$this->__add_control(
			'message_error_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['error_message'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'message_error_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['error_message'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'message_error_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['error_message'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'message_error_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['error_message'],
			),
			75
		);

		$this->__add_responsive_control(
			'message_error_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['error_message'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'message_error_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['error_message'],
			),
			100
		);

		$this->__add_control(
			'message_success_style',
			array(
				'label'     => esc_html__( 'Success', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->__add_control(
			'message_success_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['success_message'] => 'background-color: {{VALUE}}',
				),
			),
			50
		);

		$this->__add_control(
			'message_success_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['success_message'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'message_success_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['success_message'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'message_success_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['success_message'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'message_success_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['success_message'],
			),
			75
		);

		$this->__add_responsive_control(
			'message_success_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['success_message'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'message_success_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['success_message'],
			),
			100
		);

		$this->__add_responsive_control(
			'message_success_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['success_message'] => 'text-align: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['error_message'] => 'text-align: {{VALUE}};',
				),
				'separator' => 'before',
				'classes' => 'jet-blocks-text-align-control',
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'password_requirements_style',
			array(
				'label'      => esc_html__( 'Password Requirements', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => array(
					'use_password_requirements' => 'yes'
				),
			)
		);

		$this->__add_control(
			'password_requirem_wrapper',
			array(
				'label'     => esc_html__( 'Container', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
			),
			25
		);

		$this->__add_responsive_control(
			'password_requirements_wrapper_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-reset-password-requirements' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_control(
			'password_requirements_title',
			array(
				'label'     => esc_html__( 'Title', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
			),
			25
		);

		$this->__add_control(
			'password_requirements_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-password-requirements__title' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'password_requirements_title',
				'selector' => '{{WRAPPER}} .jet-password-requirements__title',
			),
			50
		);

		$this->__add_control(
			'password_requirements_items',
			array(
				'label'     => esc_html__( 'Items', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
			),
			25
		);

		$this->__add_control(
			'password_requirements_items_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-password-requirements li' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'password_requirements_items_success_color',
			array(
				'label'     => esc_html__( 'Success Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00d30b',
				'selectors' => array(
					'{{WRAPPER}} .jet-password-requirements li.success' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'password_requirements_error_color',
			array(
				'label'     => esc_html__( 'Error Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'red',
				'selectors' => array(
					'{{WRAPPER}} .jet-password-requirements li.error' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'password_requirements_items_typography',
				'selector' => '{{WRAPPER}} .jet-password-requirements li',
			),
			50
		);

		$this->__add_responsive_control(
			'password_requirements_items_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-password-requirements li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';
		$settings        = $this->get_settings_for_display();
		$errors          = isset( $_REQUEST['errors'] ) ? $_REQUEST['errors'] : array() ;
		$url             = isset( $_REQUEST['reset_url'] ) ? esc_url( $_REQUEST['reset_url'] ) : '' ;
		$action          = isset( $_REQUEST['jet_reset_action'] ) ? $_REQUEST['jet_reset_action'] : '';

		if ( 'jet_reset_lost_pass' === $action ) {

			$this->lost_pass_handler( $action );

		}

		$this->get_template_html();
	}

	public function get_login_url( $settings ) {
		$redirect_page = ( isset( $settings['login_redirect'] ) && 'static' !== $settings['login_redirect'] ) ? $settings['login_redirect'] : $settings['static_login_redirect'];

		if ( 'default' === $redirect_page ) {
			$redirect_page_url = wp_login_url();
		} else {
			$redirect_page_url = get_permalink( $redirect_page );
		}

		return $redirect_page_url;
	}

	public function get_lost_password_url() {
		return get_permalink();
	}

	public function get_success_redirect_url( $settings ){
		$redirect_page = ( isset( $settings['success_reset_redirect'] ) && 'static' !== $settings['success_reset_redirect'] ) ? $settings['success_reset_redirect'] : $settings['static_success_reset_redirect'];

		if ( 'home' === $redirect_page ) {
			$redirect_page_url = esc_url( home_url( '/' ) );
		} else if ( 'current' === $redirect_page ) {
			$redirect_page_url = get_permalink( 0 );
		} else {
			$redirect_page_url = get_permalink( $redirect_page );
		}

		return $redirect_page_url;
	}

	public function get_template_html() {

		$errors          = isset( $_REQUEST['errors'] ) ? $_REQUEST['errors'] : array() ;
		$url             = isset( $_REQUEST['reset_url'] ) ? esc_url( $_REQUEST['reset_url'] ) : '' ;
		$email_confirmed = isset( $_POST['email_confirmed'] ) ? intval( $_POST['email_confirmed'] ) : false ;

		if ( ! $email_confirmed && isset( $_GET['jetresetpass'] ) && ( isset( $_GET['jet_reset_action'] ) && $_GET['jet_reset_action'] == 'rp' ) ) {

			$key      = sanitize_text_field( $_GET['key'] );
			$user_id  = sanitize_text_field( $_GET['uid'] );
			$userdata = get_userdata( absint( $user_id ) );
			$login    = $userdata ? $userdata->user_login : '';
			$user     = check_password_reset_key( $key, $login );

			if ( is_wp_error( $user ) ) {

				if ( $user->get_error_code() === 'expired_key' ) {

					$errors['expired_key'] = esc_html__( 'That key has expired. Please reset your password again.', 'jet-blocks' );

				} else {

					$code = $user->get_error_code();
					if ( empty( $code ) ) {
						$code = '00';
					}
					$errors['invalid_key'] = esc_html__( 'That key is no longer valid. Please reset your password again. Code: ' . $code, 'jet-blocks' );

				}

				return include $this->__get_global_template( 'lost-password-form' );

			} else {

				return include $this->__get_global_template( 'reset-password-form' );

			}

		} elseif ( isset( $_GET['password_reset'] ) ) {

			return include $this->__get_global_template( 'lost-password-reset-complete' );

		} else {

			return include $this->__get_global_template( 'lost-password-form' );

		}
	}

	public function lost_pass_handler( $action = '' ) {

		if ( 'jet_reset_lost_pass' !== $action ) {
			return;
		}

		if ( ! $this->verify_nonce_request( 'jet_reset_lost_pass' ) ) {
			$this->reset_wp_error( '<strong>ERROR</strong>: ' . esc_html__( 'something went wrong with that!', 'jet-blocks' ) );
		}

		$errors    = array();
		$user_info = trim( $_POST['jet_reset_user_info'] );

		if ( isset( $user_info ) && ! empty( $user_info ) ) {

			if ( strpos( $user_info, '@' ) ) {
				$user_data = get_user_by( 'email', $user_info );
				if ( empty( $user_data ) ) {
					$errors['no_email'] = esc_html__( 'That email address is not recognised.', 'jet-blocks' );
				}
			} else {
				$user_data = get_user_by( 'login', $user_info );
				if ( empty( $user_data ) ) {
					$errors['no_login'] = esc_html__( 'That username is not recognised.', 'jet-blocks' );
				}
			}

		} else {

			$errors['invalid_input'] = esc_html__( 'Please enter a username or email address.', 'jet-blocks' );

		}

		if ( ! empty( $errors ) ) {
			$_REQUEST['errors'] = $errors;
			return $errors;
		}

		$this->lost_pass_callback( $user_info );
	}

	public function lost_pass_callback( $user_info ) {
		$settings  = $this->get_settings_for_display();
		$errors    = array();

		if ( empty( $user_info ) ) {
			$this->reset_wp_error( '<strong>ERROR</strong>: ' . esc_html__( 'Please add your email address or username!', 'jet-blocks' ) );
			exit;

		} elseif ( strpos( $user_info, '@' ) ) {

			$user_data = get_user_by( 'email', $user_info );
			if ( empty( $user_data ) ) {
				$this->reset_wp_error( '<strong>ERROR</strong>: ' . esc_html__( 'No email address found!', 'jet-blocks' ) );
				exit;
			}

		} else {

			$user_data = get_user_by( 'login', $user_info );
			if ( empty( $user_data ) ) {
				$this->reset_wp_error( '<strong>ERROR</strong>: ' . esc_html__( 'No username found!', 'jet-blocks' ) );
				exit;
			}

		}

		$user_id    = $user_data->ID;
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key        = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			$this->reset_wp_error( '<strong>ERROR</strong>: ' . esc_html__( 'key not found!', 'jet-blocks' ) );
		}

		add_filter( 'wp_mail_content_type', array( $this, 'html_emails' ) );

		$reset_url = esc_url_raw(
			add_query_arg(
				array(
					'jetresetpass'     => 'true',
					'jet_reset_action' => 'rp',
					'key'              => $key,
					'uid'              => $user_id
				),
				$this->get_lost_password_url()
			)
		);

		$reset_url_label  = $reset_url;
		$reset_link_label = isset( $settings['reset_link_label'] ) ? $settings['reset_link_label'] : '';

		if ( '' != $reset_link_label ) {
			$reset_url_label = $reset_link_label;
		}

		$reset_link = '<a href="' . $reset_url . '">' . $reset_url_label . '</a>';
		$site_title = get_bloginfo( 'name' );
		$email_body = isset( $settings['email_text'] ) ? $settings['email_text'] : '' ;
		$from_name 	= isset( $settings['from_sender'] ) ? esc_html( trim( $settings['from_sender'] ) ) : '' ;
		$from_email = isset( $settings['from_email'] ) ? esc_html( trim( $settings['from_email'] ) ) : '';
		$message    = '';
		$subject    = isset( $settings['email_subject'] ) ? $settings['email_subject'] : esc_html__( 'Account Password Reset', 'jet-blocks' );
		$headers[]  = 'Content-Type: text/html; charset=UTF-8';
		$email_sent = false;

		if ( empty( $email_body ) ) {

			ob_start(); ?>

			<p><?php esc_html_e( 'Someone requested that the password be reset for the following account:', 'jet-blocks' ); ?></p>
			<p><?php printf( esc_html__( 'Username: %s', 'jet-blocks' ), $user_login ); ?></p>
			<p><?php esc_html_e( 'If this was a mistake, just ignore this email and nothing will happen.', 'jet-blocks' ); ?></p>
			<p><?php esc_html_e( 'To reset your password, visit the following address:', 'jet-blocks' ); ?></p>
			<p><?php echo $reset_link; ?></p>
			<?php

			$message = ob_get_clean();

		} else {

			$email_body_user = str_replace( "{username}", $user_login, $email_body );
			$email_body_link = str_replace( "{reset_link}", $reset_link, $email_body_user );
			$email_body      = wpautop( $email_body_link );
			$message         = $email_body;

		}

		if ( ! empty( $from_name ) && ! empty( $from_email ) ) {
			$headers[] = 'From: ' . $from_name . '<'  . $from_email . '>';
		}

		if ( wp_mail( $user_email, wp_specialchars_decode( $subject ), $message, $headers ) ) {
			$email_sent               = true;
			$_POST['email_confirmed'] = 1;
		} else {
			$_POST['email_confirmed']          = 0;
			$errors['password_email_not_sent'] = esc_html__( 'The e-mail could not be sent.', 'jet-blocks' );
		}

		$_REQUEST['errors']    = $errors;
		$_REQUEST['reset_url'] = $reset_url;

		remove_filter( 'wp_mail_content_type', array( $this, 'html_emails' ) );

		return;
	}

	public function html_emails() {
		return 'text/html';
	}

	public function get_allowed_html_tags() {
		$allowed_tags = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'p' => array(
				'style' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);
		return apply_filters( 'get_allowed_html_tags', $allowed_tags );
	}

	public function verify_nonce_request( $action = '', $query_arg = 'jet_reset_nonce' ) {

		// Check the nonce
		$result = isset( $_REQUEST[$query_arg] ) ? wp_verify_nonce( $_REQUEST[$query_arg], $action ) : false;

		// Nonce check failed
		if ( empty( $result ) || empty( $action ) ) {
			$result = false;
		}

		// Do extra things
		do_action( 'jet-blocks/reset/verify_nonce_request', $action, $result );

		return $result;
	}

	public function reset_wp_error( $message, $args = array() ) {
		$error      = new \WP_Error( 'jet_reset_error', $message );
		$site_title = get_bloginfo( 'name' );
		wp_die( $error, $site_title . ' - Error', $args );
	}
}
