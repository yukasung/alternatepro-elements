<?php
/**
 * AlternatePro Nav Menu Elementor widget.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Widgets;

defined( 'ABSPATH' ) || exit;

/**
 * Renders a selected WordPress navigation menu in Elementor.
 */
final class NavMenuWidget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'alternatepro-nav-menu';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'AP Menu', 'alternatepro-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Get widget categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( WidgetsModule::CATEGORY );
	}

	/**
	 * Get widget search keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'menu', 'nav', 'navigation', 'alternatepro' );
	}

	/**
	 * Get widget style dependencies.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( WidgetsModule::NAV_MENU_STYLE );
	}

	/**
	 * Get widget script dependencies.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( WidgetsModule::NAV_MENU_SCRIPT );
	}

	/**
	 * Adjust wrapper attributes before Elementor prints the widget.
	 *
	 * @return void
	 */
	public function before_render() {
		parent::before_render();

		$settings              = $this->get_settings_for_display();
		$has_elementor_motion  = ! empty( $settings['_animation'] ) && 'none' !== $settings['_animation'];
		$has_elementor_motion |= ! empty( $settings['_animation_tablet'] ) && 'none' !== $settings['_animation_tablet'];
		$has_elementor_motion |= ! empty( $settings['_animation_mobile'] ) && 'none' !== $settings['_animation_mobile'];

		if ( ! $has_elementor_motion ) {
			$this->remove_render_attribute( '_wrapper', 'class', 'elementor-invisible' );
		}
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'menu_name',
			array(
				'label'       => __( 'Menu Name', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Menu', 'alternatepro-elements' ),
				'placeholder' => __( 'Menu', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'menu_id',
			array(
				'label'   => __( 'Menu', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_menu_options(),
				'default' => $this->get_default_menu_id(),
			)
		);

		$this->add_control(
			'menus_help',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => $this->get_menus_help_html(),
				'content_classes' => 'apro-nav-menu-control-help',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => __( 'Horizontal', 'alternatepro-elements' ),
					'vertical'   => __( 'Vertical', 'alternatepro-elements' ),
					'dropdown'   => __( 'Dropdown', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'   => __( 'Alignment', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'left',
				'toggle'  => false,
				'options' => array(
					'left'    => array(
						'title' => __( 'Left', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
					'justify' => array(
						'title' => __( 'Justify', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
			)
		);

		$this->add_control(
			'pointer',
			array(
				'label'   => __( 'Pointer', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => array(
					'none'      => __( 'None', 'alternatepro-elements' ),
					'underline' => __( 'Underline', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'pointer_animation',
			array(
				'label'   => __( 'Animation', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => array(
					'none' => __( 'None', 'alternatepro-elements' ),
					'fade' => __( 'Fade', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'submenu_indicator',
			array(
				'label'       => __( 'Submenu Indicator', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => $this->get_default_submenu_indicator_icon(),
				'recommended' => array(
					'fa-solid' => array(
						'chevron-down',
						'angle-down',
						'caret-down',
						'arrow-down',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->add_control(
			'mobile_dropdown_heading',
			array(
				'label'     => __( 'Mobile Dropdown', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'mobile_dropdown_breakpoint',
			array(
				'label'   => __( 'Breakpoint', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'tablet',
				'options' => $this->get_mobile_dropdown_breakpoint_options(),
			)
		);

		$this->add_control(
			'mobile_dropdown_full_width',
			array(
				'label'        => __( 'Full Width', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'alternatepro-elements' ),
				'label_off'    => __( 'No', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => __( 'Stretch the dropdown of the menu to full width.', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'mobile_dropdown_text_align',
			array(
				'label'   => __( 'Text Align', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'aside',
				'options' => array(
					'aside'  => __( 'Aside', 'alternatepro-elements' ),
					'left'   => __( 'Left', 'alternatepro-elements' ),
					'center' => __( 'Center', 'alternatepro-elements' ),
					'right'  => __( 'Right', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'mobile_dropdown_toggle_button',
			array(
				'label'   => __( 'Toggle Button', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'hamburger',
				'options' => array(
					'hamburger' => __( 'Hamburger', 'alternatepro-elements' ),
					'none'      => __( 'None', 'alternatepro-elements' ),
				),
			)
		);

		$this->start_controls_tabs( 'mobile_dropdown_toggle_icon_tabs' );

		$this->start_controls_tab(
			'mobile_dropdown_toggle_icon_normal_tab',
			array(
				'label' => __( 'Normal', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'mobile_dropdown_toggle_icon_normal',
			array(
				'label'       => __( 'Icon', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => $this->get_default_toggle_icon( 'normal' ),
				'recommended' => $this->get_toggle_icon_recommendations(),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mobile_dropdown_toggle_icon_hover_tab',
			array(
				'label' => __( 'Hover', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'mobile_dropdown_toggle_icon_hover',
			array(
				'label'       => __( 'Icon', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => $this->get_default_toggle_icon( 'hover' ),
				'recommended' => $this->get_toggle_icon_recommendations(),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mobile_dropdown_toggle_icon_active_tab',
			array(
				'label' => __( 'Active', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'mobile_dropdown_toggle_icon_active',
			array(
				'label'       => __( 'Icon', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => $this->get_default_toggle_icon( 'active' ),
				'recommended' => $this->get_toggle_icon_recommendations(),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mobile_dropdown_toggle_align',
			array(
				'label'   => __( 'Toggle Align', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'center',
				'toggle'  => false,
				'options' => array(
					'left'   => array(
						'title' => __( 'Left', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_main_menu_style',
			array(
				'label' => __( 'Main Menu', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'main_menu_typography',
				'label'    => __( 'Typography', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .apro-nav-menu .ap-nav > li > a',
			)
		);

		$this->start_controls_tabs( 'main_menu_color_tabs' );

		$this->start_controls_tab(
			'main_menu_color_normal_tab',
			array(
				'label' => __( 'Normal', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'main_menu_text_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav > li > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'main_menu_color_hover_tab',
			array(
				'label' => __( 'Hover', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'main_menu_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav > li > a:hover, {{WRAPPER}} .apro-nav-menu .ap-nav > li > a:focus-visible' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'main_menu_color_active_tab',
			array(
				'label' => __( 'Active', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'main_menu_text_color_active',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav > li.current-menu-item > a, {{WRAPPER}} .apro-nav-menu .ap-nav > li.current-menu-ancestor > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'main_menu_divider',
			array(
				'label'        => __( 'Divider', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'alternatepro-elements' ),
				'label_off'    => __( 'Off', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'main_menu_pointer_width',
			array(
				'label'      => __( 'Pointer Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-pointer-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_menu_horizontal_padding',
			array(
				'label'      => __( 'Horizontal Padding', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-link-padding-x: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_menu_vertical_padding',
			array(
				'label'      => __( 'Vertical Padding', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-link-padding-y: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_menu_space_between',
			array(
				'label'      => __( 'Space Between', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 120,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dropdown_style',
			array(
				'label' => __( 'Dropdown', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'dropdown_style_help',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'On desktop, this will affect the submenu. On mobile, this will affect the entire menu.', 'alternatepro-elements' ),
			)
		);

		$this->start_controls_tabs( 'dropdown_color_tabs' );

		$this->start_controls_tab(
			'dropdown_color_normal_tab',
			array(
				'label' => __( 'Normal', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'dropdown_text_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_background_color',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdown_color_hover_tab',
			array(
				'label' => __( 'Hover', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'dropdown_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu a:hover, {{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu a:focus-visible' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu a:hover, {{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu a:focus-visible' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdown_color_active_tab',
			array(
				'label' => __( 'Active', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'dropdown_text_color_active',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu .current-menu-item > a, {{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu .current-menu-ancestor > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_background_color_active',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu .current-menu-item > a, {{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu .current-menu-ancestor > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'dropdown_typography',
				'label'     => __( 'Typography', 'alternatepro-elements' ),
				'selector'  => '{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu a',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'dropdown_border',
				'label'     => __( 'Border Type', 'alternatepro-elements' ),
				'selector'  => '{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			array(
				'label'      => __( 'Border Radius', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_box_shadow',
				'label'    => __( 'Box Shadow', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu',
			)
		);

		$this->add_responsive_control(
			'dropdown_horizontal_padding',
			array(
				'label'      => __( 'Horizontal Padding', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-dropdown-item-padding-x: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_vertical_padding',
			array(
				'label'      => __( 'Vertical Padding', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-dropdown-item-padding-y: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dropdown_divider_heading',
			array(
				'label'     => __( 'Divider', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'dropdown_divider_border',
				'label'    => __( 'Border Type', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .apro-nav-menu .ap-nav .sub-menu > li + li',
			)
		);

		$this->add_responsive_control(
			'dropdown_divider_distance',
			array(
				'label'      => __( 'Distance', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-dropdown-divider-distance: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_button_style',
			array(
				'label' => __( 'Toggle Button', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'toggle_button_style_tabs' );

		$this->start_controls_tab(
			'toggle_button_style_normal_tab',
			array(
				'label' => __( 'Normal', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'toggle_button_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-navbar-toggle' => 'color: {{VALUE}};',
					'{{WRAPPER}} .apro-nav-menu .ap-navbar-toggle svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_background_color',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-navbar-toggle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_button_style_hover_tab',
			array(
				'label' => __( 'Hover', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'toggle_button_color_hover',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-navbar-toggle:hover, {{WRAPPER}} .apro-nav-menu .ap-navbar-toggle:focus-visible' => 'color: {{VALUE}};',
					'{{WRAPPER}} .apro-nav-menu .ap-navbar-toggle:hover svg, {{WRAPPER}} .apro-nav-menu .ap-navbar-toggle:focus-visible svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-nav-menu .ap-navbar-toggle:hover, {{WRAPPER}} .apro-nav-menu .ap-navbar-toggle:focus-visible' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_button_size',
			array(
				'label'      => __( 'Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 120,
					),
				),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-toggle-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_button_border_width',
			array(
				'label'      => __( 'Border Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-toggle-border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-nav-menu' => '--ap-nav-toggle-border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$menu_id        = $this->get_render_menu_id( $settings );
		$menu_name      = isset( $settings['menu_name'] ) ? sanitize_text_field( $settings['menu_name'] ) : '';
		$menu_name      = '' !== $menu_name ? $menu_name : __( 'Menu', 'alternatepro-elements' );
		$classes        = $this->get_container_classes( $settings );
		$indicator_html = $this->get_submenu_indicator_html( $settings );
		$container_id   = 'apro-nav-menu-' . $this->get_id();
		$layout         = $this->get_layout( $settings );
		$breakpoint     = $this->get_mobile_dropdown_breakpoint( $settings );
		$button         = $this->get_mobile_dropdown_toggle_button( $settings );

		if ( ! $menu_id ) {
			$this->render_empty_message();

			return;
		}

		$indicator_filter = null;

		if ( '' !== $indicator_html ) {
			$indicator_filter = function ( $title, $menu_item ) use ( $indicator_html ) {
				if ( ! $this->menu_item_has_children( $menu_item ) ) {
					return $title;
				}

				return $title . $indicator_html;
			};

			add_filter( 'nav_menu_item_title', $indicator_filter, 10, 2 );
		}

		try {
			printf(
				'<nav id="%1$s" class="%2$s" aria-label="%3$s" data-ap-nav-menu="1" data-ap-nav-layout="%4$s" data-ap-nav-breakpoint="%5$d" data-ap-nav-breakpoint-key="%6$s" data-ap-nav-toggle-button="%7$s">',
				esc_attr( $container_id ),
				esc_attr( implode( ' ', $classes ) ),
				esc_attr( $menu_name ),
				esc_attr( $layout ),
				absint( $this->get_mobile_dropdown_breakpoint_value( $settings ) ),
				esc_attr( $breakpoint ),
				esc_attr( $button )
			);

			$this->render_toggle_button( $settings, $container_id . '-menu' );

			wp_nav_menu(
				array(
					'menu'        => $menu_id,
					'container'   => false,
					'menu_class'  => 'ap-nav',
					'menu_id'     => $container_id . '-menu',
					'fallback_cb' => '__return_empty_string',
					'depth'       => 0,
				)
			);

			echo '</nav>';
		} finally {
			if ( null !== $indicator_filter ) {
				remove_filter( 'nav_menu_item_title', $indicator_filter, 10 );
			}
		}
	}

	/**
	 * Get a valid menu ID for frontend rendering.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return int
	 */
	private function get_render_menu_id( array $settings ) {
		$menu_id = isset( $settings['menu_id'] ) ? absint( $settings['menu_id'] ) : 0;

		if ( $menu_id && wp_get_nav_menu_object( $menu_id ) ) {
			return $menu_id;
		}

		$menus = wp_get_nav_menus();

		if ( empty( $menus ) ) {
			return 0;
		}

		return absint( $menus[0]->term_id );
	}

	/**
	 * Get sanitized container classes from settings.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string[]
	 */
	private function get_container_classes( array $settings ) {
		$layout       = $this->get_layout( $settings );
		$alignment    = $this->sanitize_choice( isset( $settings['alignment'] ) ? $settings['alignment'] : '', array( 'left', 'center', 'right', 'justify' ), 'left' );
		$pointer      = $this->sanitize_choice( isset( $settings['pointer'] ) ? $settings['pointer'] : '', array( 'none', 'underline' ), 'underline' );
		$animation    = $this->get_pointer_animation( $settings );
		$indicator    = empty( $this->get_submenu_indicator_icon( $settings ) ) ? 'none' : 'icon';
		$breakpoint   = $this->get_mobile_dropdown_breakpoint( $settings );
		$full_width   = $this->get_mobile_dropdown_full_width( $settings );
		$text_align   = $this->get_mobile_dropdown_text_align( $settings );
		$button       = $this->get_mobile_dropdown_toggle_button( $settings );
		$toggle_align = $this->get_mobile_dropdown_toggle_align( $settings );
		$divider      = $this->get_main_menu_divider( $settings );

		return array(
			'apro-nav-menu',
			'ap-nav-layout-' . sanitize_html_class( $layout ),
			'ap-nav-align-' . sanitize_html_class( $alignment ),
			'ap-nav-pointer-' . sanitize_html_class( $pointer ),
			'ap-nav-animation-' . sanitize_html_class( $animation ),
			'ap-nav-submenu-indicator-' . sanitize_html_class( $indicator ),
			'ap-nav-mobile-breakpoint-' . sanitize_html_class( $breakpoint ),
			'ap-nav-mobile-full-width-' . sanitize_html_class( $full_width ),
			'ap-nav-mobile-text-align-' . sanitize_html_class( $text_align ),
			'ap-nav-toggle-button-' . sanitize_html_class( $button ),
			'ap-nav-toggle-align-' . sanitize_html_class( $toggle_align ),
			'ap-nav-divider-' . sanitize_html_class( $divider ),
		);
	}

	/**
	 * Get the sanitized layout setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_layout( array $settings ) {
		$value = isset( $settings['layout'] ) ? $settings['layout'] : '';

		return $this->sanitize_choice( $value, array( 'horizontal', 'vertical', 'dropdown' ), 'horizontal' );
	}

	/**
	 * Get the sanitized pointer animation setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_pointer_animation( array $settings ) {
		$value = isset( $settings['pointer_animation'] ) ? $settings['pointer_animation'] : '';

		if ( '' === $value && isset( $settings['animation'] ) ) {
			$value = $settings['animation'];
		}

		return $this->sanitize_choice( $value, array( 'none', 'fade' ), 'fade' );
	}

	/**
	 * Get the sanitized main menu divider setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_main_menu_divider( array $settings ) {
		$value = isset( $settings['main_menu_divider'] ) ? $settings['main_menu_divider'] : '';

		return $this->sanitize_choice( $value, array( 'yes', 'no' ), 'no' );
	}

	/**
	 * Render the configured mobile menu toggle button.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $controls_id Controlled menu ID.
	 * @return void
	 */
	private function render_toggle_button( array $settings, $controls_id ) {
		if ( 'none' === $this->get_mobile_dropdown_toggle_button( $settings ) ) {
			return;
		}

		$normal_icon = $this->get_toggle_button_icon_html( $settings, 'normal' );
		$hover_icon  = $this->get_toggle_button_icon_html( $settings, 'hover' );
		$active_icon = $this->get_toggle_button_icon_html( $settings, 'active' );

		if ( '' === $normal_icon ) {
			return;
		}

		printf(
			'<div class="ap-navbar-toggle-wrap"><button class="ap-navbar-toggle" type="button" aria-controls="%1$s" aria-expanded="false" aria-label="%2$s">%3$s%4$s%5$s</button></div>',
			esc_attr( $controls_id ),
			esc_attr__( 'Toggle navigation menu', 'alternatepro-elements' ),
			$normal_icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized by get_toggle_button_icon_html().
			$hover_icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized by get_toggle_button_icon_html().
			$active_icon // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized by get_toggle_button_icon_html().
		);
	}

	/**
	 * Render a configured toggle button icon into safe HTML.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $state Toggle state.
	 * @return string
	 */
	private function get_toggle_button_icon_html( array $settings, $state ) {
		$state = sanitize_key( (string) $state );
		$key   = 'mobile_dropdown_toggle_icon_' . $state;
		$icon  = isset( $settings[ $key ] ) ? $settings[ $key ] : array();

		if ( ! is_array( $icon ) || empty( $icon['value'] ) || empty( $icon['library'] ) ) {
			$icon = $this->get_default_toggle_icon( $state );
		} else {
			$icon = array(
				'value'   => $this->sanitize_icon_value( $icon['value'] ),
				'library' => sanitize_key( (string) $icon['library'] ),
			);
		}

		if ( empty( $icon['value'] ) || empty( $icon['library'] ) || ! class_exists( '\Elementor\Icons_Manager' ) ) {
			return '';
		}

		ob_start();
		\Elementor\Icons_Manager::render_icon(
			$icon,
			array(
				'aria-hidden' => 'true',
				'class'       => 'ap-navbar-toggle-icon-glyph',
			)
		);
		$icon_html = ob_get_clean();
		$icon_html = wp_kses( $icon_html, $this->get_icon_allowed_html() );

		if ( '' === trim( $icon_html ) ) {
			return '';
		}

		return sprintf(
			'<span class="ap-navbar-toggle-icon ap-navbar-toggle-icon-%1$s" aria-hidden="true">%2$s</span>',
			esc_attr( $state ),
			$icon_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized above.
		);
	}

	/**
	 * Get the sanitized mobile dropdown full width setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_mobile_dropdown_full_width( array $settings ) {
		$value = isset( $settings['mobile_dropdown_full_width'] ) ? $settings['mobile_dropdown_full_width'] : '';

		return $this->sanitize_choice( $value, array( 'yes', 'no' ), 'no' );
	}

	/**
	 * Get the sanitized mobile dropdown text alignment setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_mobile_dropdown_text_align( array $settings ) {
		$value = isset( $settings['mobile_dropdown_text_align'] ) ? $settings['mobile_dropdown_text_align'] : '';

		return $this->sanitize_choice( $value, array( 'aside', 'left', 'center', 'right' ), 'aside' );
	}

	/**
	 * Get the sanitized mobile dropdown toggle button setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_mobile_dropdown_toggle_button( array $settings ) {
		$value = isset( $settings['mobile_dropdown_toggle_button'] ) ? $settings['mobile_dropdown_toggle_button'] : '';

		return $this->sanitize_choice( $value, array( 'hamburger', 'none' ), 'hamburger' );
	}

	/**
	 * Get the sanitized mobile dropdown toggle alignment setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_mobile_dropdown_toggle_align( array $settings ) {
		$value = isset( $settings['mobile_dropdown_toggle_align'] ) ? $settings['mobile_dropdown_toggle_align'] : '';

		return $this->sanitize_choice( $value, array( 'left', 'center', 'right' ), 'center' );
	}

	/**
	 * Get the sanitized mobile dropdown breakpoint setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_mobile_dropdown_breakpoint( array $settings ) {
		$options = $this->get_mobile_dropdown_breakpoint_options();
		$value   = isset( $settings['mobile_dropdown_breakpoint'] ) ? $settings['mobile_dropdown_breakpoint'] : '';

		return $this->sanitize_choice( $value, array_keys( $options ), 'tablet' );
	}

	/**
	 * Get the current mobile dropdown breakpoint width.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return int
	 */
	private function get_mobile_dropdown_breakpoint_value( array $settings ) {
		$breakpoint = $this->get_mobile_dropdown_breakpoint( $settings );
		$values     = $this->get_mobile_dropdown_breakpoint_values();

		return isset( $values[ $breakpoint ] ) ? absint( $values[ $breakpoint ] ) : 0;
	}

	/**
	 * Get available mobile dropdown breakpoint options.
	 *
	 * @return array<string,string>
	 */
	private function get_mobile_dropdown_breakpoint_options() {
		$options     = array(
			'none' => __( 'None', 'alternatepro-elements' ),
		);
		$allowed     = array( 'mobile', 'mobile_extra', 'tablet' );
		$breakpoints = $this->get_elementor_active_breakpoints();

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			$breakpoint_key = sanitize_key( (string) $breakpoint_key );

			if ( ! in_array( $breakpoint_key, $allowed, true ) || ! is_object( $breakpoint ) ) {
				continue;
			}

			if ( ! method_exists( $breakpoint, 'get_label' ) || ! method_exists( $breakpoint, 'get_value' ) ) {
				continue;
			}

			$options[ $breakpoint_key ] = $this->format_mobile_dropdown_breakpoint_label(
				sanitize_text_field( (string) $breakpoint->get_label() ),
				absint( $breakpoint->get_value() )
			);
		}

		foreach ( $this->get_fallback_mobile_dropdown_breakpoint_options() as $breakpoint_key => $label ) {
			if ( ! isset( $options[ $breakpoint_key ] ) ) {
				$options[ $breakpoint_key ] = $label;
			}
		}

		return $options;
	}

	/**
	 * Get available mobile dropdown breakpoint values.
	 *
	 * @return array<string,int>
	 */
	private function get_mobile_dropdown_breakpoint_values() {
		$values      = array(
			'none' => 0,
		);
		$allowed     = array( 'mobile', 'mobile_extra', 'tablet' );
		$breakpoints = $this->get_elementor_active_breakpoints();

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			$breakpoint_key = sanitize_key( (string) $breakpoint_key );

			if ( ! in_array( $breakpoint_key, $allowed, true ) || ! is_object( $breakpoint ) ) {
				continue;
			}

			if ( ! method_exists( $breakpoint, 'get_value' ) ) {
				continue;
			}

			$values[ $breakpoint_key ] = absint( $breakpoint->get_value() );
		}

		foreach ( $this->get_fallback_mobile_dropdown_breakpoint_values() as $breakpoint_key => $value ) {
			if ( ! isset( $values[ $breakpoint_key ] ) ) {
				$values[ $breakpoint_key ] = absint( $value );
			}
		}

		return $values;
	}

	/**
	 * Get Elementor active breakpoints when the manager is available.
	 *
	 * @return array<string,object>
	 */
	private function get_elementor_active_breakpoints() {
		if ( ! class_exists( '\Elementor\Plugin' ) || ! isset( \Elementor\Plugin::$instance->breakpoints ) ) {
			return array();
		}

		if ( ! method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints' ) ) {
			return array();
		}

		$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

		return is_array( $breakpoints ) ? $breakpoints : array();
	}

	/**
	 * Get fallback breakpoint options for non-standard Elementor contexts.
	 *
	 * @return array<string,string>
	 */
	private function get_fallback_mobile_dropdown_breakpoint_options() {
		return array(
			'mobile' => $this->format_mobile_dropdown_breakpoint_label( __( 'Mobile Portrait', 'alternatepro-elements' ), 767 ),
			'tablet' => $this->format_mobile_dropdown_breakpoint_label( __( 'Tablet Portrait', 'alternatepro-elements' ), 1024 ),
		);
	}

	/**
	 * Get fallback breakpoint values for non-standard Elementor contexts.
	 *
	 * @return array<string,int>
	 */
	private function get_fallback_mobile_dropdown_breakpoint_values() {
		return array(
			'mobile' => 767,
			'tablet' => 1024,
		);
	}

	/**
	 * Format a breakpoint select label.
	 *
	 * @param string $label Breakpoint label.
	 * @param int    $value Breakpoint value.
	 * @return string
	 */
	private function format_mobile_dropdown_breakpoint_label( $label, $value ) {
		return sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value in pixels. */
			__( '%1$s (%2$s %3$dpx)', 'alternatepro-elements' ),
			$label,
			'>',
			absint( $value )
		);
	}

	/**
	 * Get the default toggle icon for a state.
	 *
	 * @param string $state Toggle state.
	 * @return array<string,string>
	 */
	private function get_default_toggle_icon( $state ) {
		$state = sanitize_key( (string) $state );

		if ( 'active' === $state ) {
			return array(
				'value'   => 'fas fa-times',
				'library' => 'fa-solid',
			);
		}

		return array(
			'value'   => 'fas fa-bars',
			'library' => 'fa-solid',
		);
	}

	/**
	 * Get recommended toggle icons for Elementor icon picker.
	 *
	 * @return array<string,string[]>
	 */
	private function get_toggle_icon_recommendations() {
		return array(
			'fa-solid' => array(
				'bars',
				'ellipsis-h',
				'grip-lines',
				'times',
				'plus',
			),
		);
	}

	/**
	 * Get the default submenu indicator icon.
	 *
	 * @return array<string,string>
	 */
	private function get_default_submenu_indicator_icon() {
		return array(
			'value'   => 'fas fa-chevron-down',
			'library' => 'fa-solid',
		);
	}

	/**
	 * Get a sanitized Elementor icon setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<string,mixed>
	 */
	private function get_submenu_indicator_icon( array $settings ) {
		$icon = isset( $settings['submenu_indicator'] ) ? $settings['submenu_indicator'] : array();

		if ( is_string( $icon ) ) {
			$legacy_value = sanitize_key( $icon );

			return 'none' === $legacy_value ? array() : $this->get_default_submenu_indicator_icon();
		}

		if ( ! is_array( $icon ) ) {
			return array();
		}

		$value   = isset( $icon['value'] ) ? $this->sanitize_icon_value( $icon['value'] ) : '';
		$library = isset( $icon['library'] ) ? sanitize_key( (string) $icon['library'] ) : '';

		if ( empty( $value ) || '' === $library ) {
			return array();
		}

		return array(
			'value'   => $value,
			'library' => $library,
		);
	}

	/**
	 * Sanitize an Elementor icon value.
	 *
	 * @param mixed $value Icon value.
	 * @return mixed
	 */
	private function sanitize_icon_value( $value ) {
		if ( is_array( $value ) ) {
			$sanitized = array();

			foreach ( $value as $key => $item ) {
				$key = sanitize_key( (string) $key );

				if ( '' === $key ) {
					continue;
				}

				if ( 'id' === $key ) {
					$sanitized[ $key ] = absint( $item );
					continue;
				}

				if ( is_scalar( $item ) ) {
					$sanitized[ $key ] = sanitize_text_field( (string) $item );
				}
			}

			return $sanitized;
		}

		return is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
	}

	/**
	 * Render the selected submenu indicator icon into safe HTML.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_submenu_indicator_html( array $settings ) {
		$icon = $this->get_submenu_indicator_icon( $settings );

		if ( empty( $icon ) || ! class_exists( '\Elementor\Icons_Manager' ) ) {
			return '';
		}

		ob_start();
		\Elementor\Icons_Manager::render_icon(
			$icon,
			array(
				'aria-hidden' => 'true',
				'class'       => 'ap-nav-submenu-indicator-icon',
			)
		);
		$icon_html = ob_get_clean();
		$icon_html = wp_kses( $icon_html, $this->get_icon_allowed_html() );

		if ( '' === trim( $icon_html ) ) {
			return '';
		}

		return '<span class="ap-nav-submenu-indicator" aria-hidden="true">' . $icon_html . '</span>';
	}

	/**
	 * Check if a menu item has child menu entries.
	 *
	 * @param object $menu_item Menu item object.
	 * @return bool
	 */
	private function menu_item_has_children( $menu_item ) {
		$classes = isset( $menu_item->classes ) ? (array) $menu_item->classes : array();

		return in_array( 'menu-item-has-children', $classes, true );
	}

	/**
	 * Allowed icon markup emitted by Elementor Icons_Manager.
	 *
	 * @return array<string,array<string,bool>>
	 */
	private function get_icon_allowed_html() {
		$allowed = wp_kses_allowed_html( 'post' );

		$allowed['i'] = array(
			'aria-hidden' => true,
			'class'       => true,
		);

		$allowed['svg'] = array(
			'aria-hidden' => true,
			'class'       => true,
			'fill'        => true,
			'focusable'   => true,
			'height'      => true,
			'role'        => true,
			'viewbox'     => true,
			'viewBox'     => true,
			'width'       => true,
			'xmlns'       => true,
		);

		$allowed['path'] = array(
			'd'    => true,
			'fill' => true,
		);

		return $allowed;
	}

	/**
	 * Sanitize a control value against an allowlist.
	 *
	 * @param mixed    $value Raw value.
	 * @param string[] $allowed Allowed values.
	 * @param string   $fallback Fallback value.
	 * @return string
	 */
	private function sanitize_choice( $value, array $allowed, $fallback ) {
		$value = sanitize_key( (string) $value );

		return in_array( $value, $allowed, true ) ? $value : $fallback;
	}

	/**
	 * Get selectable WordPress menus.
	 *
	 * @return array<string,string>
	 */
	private function get_menu_options() {
		$menus   = wp_get_nav_menus();
		$options = array();

		foreach ( $menus as $menu ) {
			$options[ (string) absint( $menu->term_id ) ] = sanitize_text_field( $menu->name );
		}

		if ( empty( $options ) ) {
			$options['0'] = __( 'No menus found', 'alternatepro-elements' );
		}

		return $options;
	}

	/**
	 * Get the default menu option.
	 *
	 * @return string
	 */
	private function get_default_menu_id() {
		$options = $this->get_menu_options();
		$first   = key( $options );

		return false === $first ? '0' : (string) $first;
	}

	/**
	 * Get the Menus screen helper HTML.
	 *
	 * @return string
	 */
	private function get_menus_help_html() {
		return sprintf(
			'%1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a> %4$s',
			esc_html__( 'Go to the', 'alternatepro-elements' ),
			esc_url( admin_url( 'nav-menus.php' ) ),
			esc_html__( 'Menus screen', 'alternatepro-elements' ),
			esc_html__( 'to manage your menus.', 'alternatepro-elements' )
		);
	}

	/**
	 * Render an editor-only empty state.
	 *
	 * @return void
	 */
	private function render_empty_message() {
		if ( ! $this->is_editor_context() ) {
			return;
		}

		printf(
			'<div class="apro-nav-menu-empty">%s</div>',
			esc_html__( 'Select a WordPress menu in the widget settings.', 'alternatepro-elements' )
		);
	}

	/**
	 * Check whether Elementor is rendering inside the editor.
	 *
	 * @return bool
	 */
	private function is_editor_context() {
		return class_exists( '\Elementor\Plugin' )
			&& isset( \Elementor\Plugin::$instance )
			&& isset( \Elementor\Plugin::$instance->editor )
			&& method_exists( \Elementor\Plugin::$instance->editor, 'is_edit_mode' )
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();
	}
}
