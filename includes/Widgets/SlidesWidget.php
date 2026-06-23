<?php
/**
 * AP Slides Elementor widget.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Widgets;

use AlternatePro\Elements\Controls\ApCustomCssControl;
use AlternatePro\Elements\Traits\WidgetSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Provides the AP Slides widget foundation for Elementor Free.
 */
final class SlidesWidget extends \Elementor\Widget_Base {
	use ApCustomCssControl;
	use WidgetSettings;

	private const DEFAULT_SLIDE_BACKGROUND_COLOR = '#8f3fb0';

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ap-slides';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'AP Slides', 'alternatepro-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slides';
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
		return array( 'ap', 'apro', 'ap slides', 'slides', 'slider', 'carousel', 'alternatepro' );
	}

	/**
	 * Get widget style dependencies.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( WidgetsModule::SLIDES_STYLE );
	}

	/**
	 * Get widget script dependencies.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( WidgetsModule::SLIDES_SCRIPT );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs( 'slide_item_tabs' );

		$repeater->start_controls_tab(
			'slide_item_background_tab',
			array(
				'label' => __( 'Background', 'alternatepro-elements' ),
			)
		);

		$repeater->add_control(
			'slide_background_color',
			array(
				'label'   => __( 'Color', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => self::DEFAULT_SLIDE_BACKGROUND_COLOR,
			)
		);

		$repeater->add_control(
			'slide_background_image',
			array(
				'label'       => __( 'Image', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_types' => array( 'image' ),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'slide_item_content_tab',
			array(
				'label' => __( 'Content', 'alternatepro-elements' ),
			)
		);

		$repeater->add_control(
			'slide_heading',
			array(
				'label'       => __( 'Title', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Slide 1 Heading', 'alternatepro-elements' ),
				'placeholder' => __( 'Slide Heading', 'alternatepro-elements' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'slide_description',
			array(
				'label'       => __( 'Description', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'alternatepro-elements' ),
				'placeholder' => __( 'Slide description', 'alternatepro-elements' ),
				'rows'        => 5,
			)
		);

		$repeater->add_control(
			'slide_button_text',
			array(
				'label'       => __( 'Button Text', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Click Here', 'alternatepro-elements' ),
				'placeholder' => __( 'Click Here', 'alternatepro-elements' ),
			)
		);

		$repeater->add_control(
			'slide_link',
			array(
				'label'       => __( 'Link', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'Type or paste your URL', 'alternatepro-elements' ),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'slide_item_style_tab',
			array(
				'label' => __( 'Style', 'alternatepro-elements' ),
			)
		);

		$repeater->add_control(
			'slide_custom_style',
			array(
				'label'        => __( 'Custom', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'alternatepro-elements' ),
				'label_off'    => __( 'No', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$repeater->add_control(
			'slide_custom_style_note',
			array(
				'type'      => \Elementor\Controls_Manager::RAW_HTML,
				'raw'       => esc_html__( 'Set custom style that will only affect this specific slide.', 'alternatepro-elements' ),
				'condition' => array(
					'slide_custom_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_horizontal_position',
			array(
				'label'     => __( 'Horizontal Position', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition' => array(
					'slide_custom_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_vertical_position',
			array(
				'label'     => __( 'Vertical Position', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Top', 'alternatepro-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Middle', 'alternatepro-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'alternatepro-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition' => array(
					'slide_custom_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_text_align',
			array(
				'label'     => __( 'Text Align', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'alternatepro-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'alternatepro-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'alternatepro-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'condition' => array(
					'slide_custom_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_content_color',
			array(
				'label'     => __( 'Content Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'condition' => array(
					'slide_custom_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_text_shadow',
			array(
				'label'     => __( 'Text Shadow', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::TEXT_SHADOW,
				'condition' => array(
					'slide_custom_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->start_controls_section(
			'section_slides',
			array(
				'label' => __( 'Slides', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'slides_name',
			array(
				'label'       => __( 'Slides Name', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Slides', 'alternatepro-elements' ),
				'placeholder' => __( 'Slides', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Slides', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'slide_background_color'    => self::DEFAULT_SLIDE_BACKGROUND_COLOR,
						'slide_heading'             => __( 'Slide 1 Heading', 'alternatepro-elements' ),
						'slide_description'         => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'alternatepro-elements' ),
						'slide_button_text'         => __( 'Click Here', 'alternatepro-elements' ),
						'slide_custom_style'        => 'yes',
						'slide_horizontal_position' => 'center',
						'slide_vertical_position'   => 'center',
						'slide_text_align'          => 'center',
					),
					array(
						'slide_background_color'    => self::DEFAULT_SLIDE_BACKGROUND_COLOR,
						'slide_heading'             => __( 'Slide 2 Heading', 'alternatepro-elements' ),
						'slide_description'         => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'alternatepro-elements' ),
						'slide_button_text'         => __( 'Click Here', 'alternatepro-elements' ),
						'slide_custom_style'        => 'yes',
						'slide_horizontal_position' => 'center',
						'slide_vertical_position'   => 'center',
						'slide_text_align'          => 'center',
					),
					array(
						'slide_background_color'    => self::DEFAULT_SLIDE_BACKGROUND_COLOR,
						'slide_heading'             => __( 'Slide 3 Heading', 'alternatepro-elements' ),
						'slide_description'         => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'alternatepro-elements' ),
						'slide_button_text'         => __( 'Click Here', 'alternatepro-elements' ),
						'slide_custom_style'        => 'yes',
						'slide_horizontal_position' => 'center',
						'slide_vertical_position'   => 'center',
						'slide_text_align'          => 'center',
					),
				),
				'title_field' => '{{{ slide_heading }}}',
			)
		);

		$this->add_responsive_control(
			'slide_height',
			array(
				'label'      => __( 'Height', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min'  => 100,
						'max'  => 1200,
						'step' => 10,
					),
					'vh'  => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					),
					'rem' => array(
						'min'  => 10,
						'max'  => 80,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 400,
				),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'div',
				'options' => $this->get_html_tag_options(),
			)
		);

		$this->add_control(
			'description_html_tag',
			array(
				'label'   => __( 'Description HTML Tag', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'div',
				'options' => $this->get_html_tag_options(),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label' => __( 'Slider Options', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'   => __( 'Navigation', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'arrows_dots',
				'options' => array(
					'none'        => __( 'None', 'alternatepro-elements' ),
					'arrows'      => __( 'Arrows', 'alternatepro-elements' ),
					'dots'        => __( 'Dots', 'alternatepro-elements' ),
					'arrows_dots' => __( 'Arrows and Dots', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'alternatepro-elements' ),
				'label_off'    => __( 'No', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => __( 'Pause on Hover', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'alternatepro-elements' ),
				'label_off'    => __( 'No', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'        => __( 'Pause on Interaction', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'alternatepro-elements' ),
				'label_off'    => __( 'No', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'   => __( 'Autoplay Speed', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1000,
				'max'     => 10000,
				'step'    => 100,
				'default' => 5000,
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'        => __( 'Infinite Loop', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'alternatepro-elements' ),
				'label_off'    => __( 'No', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'transition',
			array(
				'label'   => __( 'Transition', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => array(
					'slide' => __( 'Slide', 'alternatepro-elements' ),
					'fade'  => __( 'Fade', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'transition_speed',
			array(
				'label'   => __( 'Transition Speed (ms)', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 100,
				'max'     => 3000,
				'step'    => 50,
				'default' => 500,
			)
		);

		$this->add_control(
			'content_animation',
			array(
				'label'   => __( 'Content Animation', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'up',
				'options' => array(
					'none'  => __( 'None', 'alternatepro-elements' ),
					'up'    => __( 'Up', 'alternatepro-elements' ),
					'down'  => __( 'Down', 'alternatepro-elements' ),
					'left'  => __( 'Left', 'alternatepro-elements' ),
					'right' => __( 'Right', 'alternatepro-elements' ),
					'zoom'  => __( 'Zoom', 'alternatepro-elements' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			array(
				'label' => __( 'Slides', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'slides_content_width',
			array(
				'label'      => __( 'Content Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px', 'vw' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 100,
						'max' => 1600,
					),
					'vw' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 66,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides__content' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				),
			)
		);

		$this->add_responsive_control(
			'slides_content_padding',
			array(
				'label'      => __( 'Padding', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_horizontal_position',
			array(
				'label'     => __( 'Horizontal Position', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'alternatepro-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__slide' => 'display: flex; justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_vertical_position',
			array(
				'label'     => __( 'Vertical Position', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Top', 'alternatepro-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Middle', 'alternatepro-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'alternatepro-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__slide' => 'display: flex; align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_text_align',
			array(
				'label'     => __( 'Text Align', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'alternatepro-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'alternatepro-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'alternatepro-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'slides_text_shadow',
				'selector' => '{{WRAPPER}} .ap-slides__content',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Title', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'      => __( 'Spacing', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 120,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 28,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'title_typography',
				'label'          => __( 'Typography', 'alternatepro-elements' ),
				'selector'       => '{{WRAPPER}} .ap-slides__title',
				'fields_options' => array(
					'typography'  => array(
						'default' => 'custom',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 48,
						),
					),
					'font_weight' => array(
						'default' => '700',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.2,
						),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description_style',
			array(
				'label' => __( 'Description', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'description_spacing',
			array(
				'label'      => __( 'Spacing', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 120,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 36,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'description_typography',
				'label'          => __( 'Typography', 'alternatepro-elements' ),
				'selector'       => '{{WRAPPER}} .ap-slides__description',
				'fields_options' => array(
					'typography'  => array(
						'default' => 'custom',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 20,
						),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.5,
						),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'                => __( 'Size', 'alternatepro-elements' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'default'              => 'small',
				'options'              => array(
					'small'       => __( 'Small', 'alternatepro-elements' ),
					'medium'      => __( 'Medium', 'alternatepro-elements' ),
					'large'       => __( 'Large', 'alternatepro-elements' ),
					'extra_large' => __( 'Extra Large', 'alternatepro-elements' ),
				),
				'selectors_dictionary' => array(
					'small'       => 'display: inline-block; font-size: 13px; line-height: 1; padding: 10px 20px; text-decoration: none;',
					'medium'      => 'display: inline-block; font-size: 15px; line-height: 1; padding: 12px 24px; text-decoration: none;',
					'large'       => 'display: inline-block; font-size: 17px; line-height: 1; padding: 14px 28px; text-decoration: none;',
					'extra_large' => 'display: inline-block; font-size: 19px; line-height: 1; padding: 16px 32px; text-decoration: none;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ap-slides__button' => '{{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'button_typography',
				'label'          => __( 'Typography', 'alternatepro-elements' ),
				'selector'       => '{{WRAPPER}} .ap-slides__button',
				'fields_options' => array(
					'typography'  => array(
						'default' => 'custom',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 15,
						),
					),
					'font_weight' => array(
						'default' => '600',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1,
						),
					),
				),
			)
		);

		$this->add_control(
			'button_border_width',
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
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides__button' => 'border-style: solid; border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 3,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab(
			'button_style_normal_tab',
			array(
				'label' => __( 'Normal', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'label'    => __( 'Background Type', 'alternatepro-elements' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ap-slides__button',
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover_tab',
			array(
				'label' => __( 'Hover', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'label'    => __( 'Background Type', 'alternatepro-elements' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ap-slides__button:hover',
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-slides__button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation_style',
			array(
				'label' => __( 'Navigation', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'arrows_heading',
			array(
				'label' => __( 'Arrows', 'alternatepro-elements' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'arrows_position',
			array(
				'label'                => __( 'Position', 'alternatepro-elements' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'default'              => 'inside',
				'options'              => array(
					'inside'  => __( 'Inside', 'alternatepro-elements' ),
					'outside' => __( 'Outside', 'alternatepro-elements' ),
				),
				'selectors_dictionary' => array(
					'inside'  => '--ap-slides-arrow-offset: 28px;',
					'outside' => '--ap-slides-arrow-offset: -52px;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ap-slides' => '{{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => __( 'Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 44,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides' => '--ap-slides-arrows-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ap-slides' => '--ap-slides-arrows-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_heading',
			array(
				'label'     => __( 'Pagination', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'pagination_position',
			array(
				'label'                => __( 'Position', 'alternatepro-elements' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'default'              => 'inside',
				'options'              => array(
					'inside'  => __( 'Inside', 'alternatepro-elements' ),
					'outside' => __( 'Outside', 'alternatepro-elements' ),
				),
				'selectors_dictionary' => array(
					'inside'  => '--ap-slides-pagination-bottom: 24px;',
					'outside' => '--ap-slides-pagination-bottom: -32px;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ap-slides' => '{{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'      => __( 'Space Between Dots', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides' => '--ap-slides-pagination-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_size',
			array(
				'label'      => __( 'Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 4,
						'max' => 32,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-slides' => '--ap-slides-pagination-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.18)',
				'selectors' => array(
					'{{WRAPPER}} .ap-slides' => '--ap-slides-pagination-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => __( 'Active Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .ap-slides' => '--ap-slides-pagination-active-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_ap_custom_css_controls(
			array(
				'placeholder' => "selector .ap-slides {\n\t/* CSS */\n}",
				'description' => __( 'Use selector to scope rules to this AP Slides widget.', 'alternatepro-elements' ),
			)
		);
	}

	/**
	 * Get safe HTML tag options for text controls.
	 *
	 * @return array<string,string>
	 */
	private function get_html_tag_options() {
		return array(
			'h1'   => 'H1',
			'h2'   => 'H2',
			'h3'   => 'H3',
			'h4'   => 'H4',
			'h5'   => 'H5',
			'h6'   => 'H6',
			'div'  => 'div',
			'span' => 'span',
			'p'    => 'p',
		);
	}

	/**
	 * Get a safe HTML tag from the configured tag controls.
	 *
	 * @param mixed  $tag Raw tag value.
	 * @param string $fallback Fallback tag.
	 * @return string
	 */
	private function get_safe_html_tag( $tag, $fallback = 'div' ) {
		$tag = strtolower( sanitize_key( (string) $tag ) );

		return array_key_exists( $tag, $this->get_html_tag_options() ) ? $tag : $fallback;
	}

	/**
	 * Get the accessible label for the slides widget wrapper.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_slides_aria_label( array $settings ) {
		$label = isset( $settings['slides_name'] ) ? sanitize_text_field( $settings['slides_name'] ) : '';

		return '' !== $label ? $label : __( 'Slides', 'alternatepro-elements' );
	}

	/**
	 * Get slides for the current render.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<int,array<string,mixed>>
	 */
	private function get_render_slides( array $settings ) {
		if ( ! empty( $settings['slides'] ) && is_array( $settings['slides'] ) ) {
			$slides = array_values( array_filter( $settings['slides'], 'is_array' ) );

			if ( ! empty( $slides ) ) {
				return $slides;
			}
		}

		return array(
			array(
				'slide_background_color' => self::DEFAULT_SLIDE_BACKGROUND_COLOR,
				'slide_heading'          => __( 'Slide 1 Heading', 'alternatepro-elements' ),
				'slide_description'      => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'alternatepro-elements' ),
				'slide_button_text'      => __( 'Click Here', 'alternatepro-elements' ),
			),
		);
	}

	/**
	 * Get inline background styles for a rendered slide.
	 *
	 * @param array<string,mixed> $slide Slide settings.
	 * @return string
	 */
	private function get_slide_background_style( array $slide ) {
		$styles = array();
		$color  = isset( $slide['slide_background_color'] ) ? (string) sanitize_hex_color( (string) $slide['slide_background_color'] ) : '';

		if ( '' === $color ) {
			$color = self::DEFAULT_SLIDE_BACKGROUND_COLOR;
		}

		if ( $color ) {
			$styles[] = 'background-color: ' . $color . ';';
		}

		if ( ! empty( $slide['slide_background_image']['url'] ) ) {
			$image_url = esc_url_raw( (string) $slide['slide_background_image']['url'] );

			if ( '' !== $image_url ) {
				$styles[] = 'background-image: url("' . $image_url . '");';
				$styles[] = 'background-size: cover;';
				$styles[] = 'background-position: center;';
			}
		}

		return implode( ' ', $styles );
	}

	/**
	 * Render a slide text element.
	 *
	 * @param string $tag HTML tag.
	 * @param string $class_name Element class.
	 * @param mixed  $text Raw text.
	 * @param bool   $multiline Whether to preserve line breaks.
	 * @return void
	 */
	private function render_slide_text( $tag, $class_name, $text, $multiline = false ) {
		$text = $multiline ? sanitize_textarea_field( (string) $text ) : sanitize_text_field( (string) $text );

		if ( '' === $text ) {
			return;
		}

		printf(
			'<%1$s class="%2$s">%3$s</%1$s>',
			tag_escape( $tag ),
			esc_attr( $class_name ),
			$multiline ? wp_kses_post( nl2br( esc_html( $text ) ) ) : esc_html( $text )
		);
	}

	/**
	 * Render the slide button.
	 *
	 * @param array<string,mixed> $slide Slide settings.
	 * @return void
	 */
	private function render_slide_button( array $slide ) {
		$button_text = isset( $slide['slide_button_text'] ) ? sanitize_text_field( $slide['slide_button_text'] ) : '';

		if ( '' === $button_text ) {
			return;
		}

		$link = isset( $slide['slide_link'] ) && is_array( $slide['slide_link'] ) ? $slide['slide_link'] : array();
		$url  = ! empty( $link['url'] ) ? esc_url( (string) $link['url'] ) : '';

		if ( '' === $url ) {
			printf(
				'<span class="ap-slides__button">%s</span>',
				esc_html( $button_text )
			);

			return;
		}

		$rel = array();

		if ( ! empty( $link['is_external'] ) ) {
			$rel[] = 'noopener';
		}

		if ( ! empty( $link['nofollow'] ) ) {
			$rel[] = 'nofollow';
		}

		printf(
			'<a class="ap-slides__button" href="%1$s"%2$s%3$s>%4$s</a>',
			esc_url( $url ),
			! empty( $link['is_external'] ) ? ' target="_blank"' : '',
			! empty( $rel ) ? ' rel="' . esc_attr( implode( ' ', array_unique( $rel ) ) ) . '"' : '',
			esc_html( $button_text )
		);
	}

	/**
	 * Get the configured navigation display mode.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_navigation_mode( array $settings ) {
		$navigation = isset( $settings['navigation'] ) ? $settings['navigation'] : 'arrows_dots';

		return $this->sanitize_choice( $navigation, array( 'none', 'arrows', 'dots', 'arrows_dots' ), 'arrows_dots' );
	}

	/**
	 * Get the configured transition mode.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_transition_mode( array $settings ) {
		return $this->sanitize_choice( $settings['transition'] ?? 'slide', array( 'slide', 'fade' ), 'slide' );
	}

	/**
	 * Get the configured content animation mode.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_content_animation_mode( array $settings ) {
		return $this->sanitize_choice( $settings['content_animation'] ?? 'up', array( 'none', 'up', 'down', 'left', 'right', 'zoom' ), 'up' );
	}

	/**
	 * Get Owl Carousel options for AP Slides animation.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param int                 $slide_count Slide count.
	 * @return array<string,mixed>
	 */
	private function get_slider_options( array $settings, $slide_count ) {
		$transition       = $this->get_transition_mode( $settings );
		$transition_speed = $this->get_numeric_setting( $settings, 'transition_speed', 500, 100, 3000 );
		$autoplay         = $this->get_switcher_value( $settings, 'autoplay' ) && $slide_count > 1;
		$loop             = $this->get_switcher_value( $settings, 'infinite_loop', true ) && $slide_count > 1;

		$options = array(
			'items'               => 1,
			'slideBy'             => 1,
			'margin'              => 0,
			'loop'                => $loop,
			'rewind'              => false,
			'autoplay'            => $autoplay,
			'autoplayTimeout'     => $this->get_numeric_setting( $settings, 'autoplay_speed', 5000, 1000, 10000 ),
			'autoplaySpeed'       => $transition_speed,
			'autoplayHoverPause'  => $this->get_switcher_value( $settings, 'pause_on_hover', true ),
			'pauseOnInteraction'  => $this->get_switcher_value( $settings, 'pause_on_interaction', true ),
			'smartSpeed'          => $transition_speed,
			'nav'                 => false,
			'dots'                => false,
			'rtl'                 => is_rtl(),
			'transition'          => $transition,
			'contentAnimation'    => $this->get_content_animation_mode( $settings ),
			'slideCount'          => $slide_count,
			'prevLabel'           => __( 'Previous slide', 'alternatepro-elements' ),
			'nextLabel'           => __( 'Next slide', 'alternatepro-elements' ),
			'dotLabel'            => __( 'Go to slide', 'alternatepro-elements' ),
			'animationEngineName' => 'OwlCarousel2',
		);

		if ( 'fade' === $transition ) {
			$options['animateIn']  = 'ap-slides-owl-fade-in';
			$options['animateOut'] = 'ap-slides-owl-fade-out';
		}

		return $options;
	}

	/**
	 * Render a single slide.
	 *
	 * @param array<string,mixed> $slide Slide settings.
	 * @param int                 $index Slide index.
	 * @param int                 $slide_count Slide count.
	 * @param string              $title_tag Title HTML tag.
	 * @param string              $description_tag Description HTML tag.
	 * @param array<string,mixed> $settings Widget settings.
	 * @return void
	 */
	private function render_slide( array $slide, $index, $slide_count, $title_tag, $description_tag, array $settings ) {
		$is_active        = 0 === $index;
		$background_style = $this->get_slide_background_style( $slide );
		$classes          = array( 'ap-slides__slide' );

		if ( $is_active ) {
			$classes[] = 'ap-slides__slide--active';
		}

		printf(
			'<div class="%1$s" data-ap-slides-slide role="group" aria-roledescription="%2$s" aria-label="%3$s" aria-hidden="%4$s"%5$s>',
			esc_attr( implode( ' ', $classes ) ),
			esc_attr__( 'slide', 'alternatepro-elements' ),
			esc_attr(
				sprintf(
					/* translators: 1: Current slide number, 2: Total slide count. */
					__( '%1$d of %2$d', 'alternatepro-elements' ),
					$index + 1,
					$slide_count
				)
			),
			$is_active ? 'false' : 'true',
			'' !== $background_style ? ' style="' . esc_attr( $background_style ) . '"' : ''
		);

		echo '<div class="ap-slides__content">';

		$this->render_slide_text( $title_tag, 'ap-slides__title', $slide['slide_heading'] ?? '' );
		$this->render_slide_text( $description_tag, 'ap-slides__description', $slide['slide_description'] ?? '', true );
		$this->render_slide_button( $slide );

		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render navigation controls.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param int                 $slide_count Slide count.
	 * @return void
	 */
	private function render_navigation( array $settings, $slide_count ) {
		$navigation = $this->get_navigation_mode( $settings );

		if ( 'none' === $navigation || $slide_count < 2 ) {
			return;
		}

		if ( in_array( $navigation, array( 'arrows', 'arrows_dots' ), true ) ) {
			printf(
				'<button class="ap-slides__arrow ap-slides__arrow--prev" type="button" data-ap-slides-prev aria-label="%s">&#8249;</button>',
				esc_attr__( 'Previous slide', 'alternatepro-elements' )
			);

			printf(
				'<button class="ap-slides__arrow ap-slides__arrow--next" type="button" data-ap-slides-next aria-label="%s">&#8250;</button>',
				esc_attr__( 'Next slide', 'alternatepro-elements' )
			);
		}

		if ( in_array( $navigation, array( 'dots', 'arrows_dots' ), true ) ) {
			echo '<div class="ap-slides__pagination" role="tablist" aria-label="' . esc_attr__( 'Slides', 'alternatepro-elements' ) . '">';

			for ( $index = 0; $index < $slide_count; $index++ ) {
				printf(
					'<button class="%1$s" type="button" data-ap-slides-dot="%2$d" role="tab" aria-selected="%3$s" aria-label="%4$s"></button>',
					esc_attr( 0 === $index ? 'ap-slides__dot ap-slides__dot--active' : 'ap-slides__dot' ),
					(int) $index,
					0 === $index ? 'true' : 'false',
					esc_attr(
						sprintf(
							/* translators: %d: Slide number. */
							__( 'Go to slide %d', 'alternatepro-elements' ),
							$index + 1
						)
					)
				);
			}

			echo '</div>';
		}
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
		$slides          = $this->get_render_slides( $settings );
		$slide_count     = count( $slides );
		$title_tag       = $this->get_safe_html_tag( $settings['title_html_tag'] ?? 'div', 'div' );
		$description_tag = $this->get_safe_html_tag( $settings['description_html_tag'] ?? 'div', 'div' );
		$slider_options  = $this->get_slider_options( $settings, $slide_count );
		$classes         = array(
			'ap-slides',
			'ap-slides--transition-' . sanitize_html_class( $slider_options['transition'] ),
			'ap-slides--content-animation-' . sanitize_html_class( $slider_options['contentAnimation'] ),
		);

		$this->render_ap_custom_css( $settings );

		printf(
			'<div class="%1$s" data-ap-slides="1" data-ap-slides-options="%2$s" data-ap-slides-count="%3$d" role="region" aria-label="%4$s">',
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( wp_json_encode( $slider_options ) ),
			(int) $slide_count,
			esc_attr( $this->get_slides_aria_label( $settings ) )
		);

		echo '<div class="ap-slides__track owl-carousel owl-theme" data-ap-slides-track>';

		foreach ( $slides as $index => $slide ) {
			$this->render_slide( $slide, $index, $slide_count, $title_tag, $description_tag, $settings );
		}

		echo '</div>';

		$this->render_navigation( $settings, $slide_count );

		echo '</div>';
	}

	/**
	 * Render editor content template.
	 *
	 * @return void
	 */
	protected function content_template() {}
}
