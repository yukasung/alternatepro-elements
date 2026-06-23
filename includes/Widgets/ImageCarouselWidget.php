<?php
/**
 * AP Image Carosel Elementor widget.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Widgets;

use AlternatePro\Elements\Controls\ApCustomCssControl;
use AlternatePro\Elements\Traits\WidgetSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Renders an Owl Carousel powered image carousel in Elementor.
 */
final class ImageCarouselWidget extends \Elementor\Widget_Base {
	use ApCustomCssControl;
	use WidgetSettings;

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'alternatepro-image-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'AP Image Carosel', 'alternatepro-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slider-push';
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
		return array( 'ap', 'apro', 'ap image carosel', 'image', 'carousel', 'carosel', 'slider', 'gallery', 'owl', 'alternatepro' );
	}

	/**
	 * Get widget style dependencies.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( WidgetsModule::IMAGE_CAROUSEL_STYLE );
	}

	/**
	 * Get widget script dependencies.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( WidgetsModule::IMAGE_CAROUSEL_SCRIPT );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_carousel_controls();
		$this->register_style_controls();
		$this->register_ap_custom_css_controls(
			array(
				'placeholder' => "selector .apro-image-carousel {\n\t/* CSS */\n}",
				'description' => __( 'Use selector to scope rules to this AP Image Carosel widget.', 'alternatepro-elements' ),
			)
		);
	}

	/**
	 * Register content controls.
	 *
	 * @return void
	 */
	private function register_content_controls() {
		$this->start_controls_section(
			'section_image_carousel',
			array(
				'label' => __( 'Image Carousel', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'carousel_label',
			array(
				'label'       => __( 'Carousel Name', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Image Carousel', 'alternatepro-elements' ),
				'placeholder' => __( 'Image Carousel', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'gallery',
			array(
				'label'      => __( 'Images', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::GALLERY,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'label'   => __( 'Image Resolution', 'alternatepro-elements' ),
				'default' => 'full',
				'exclude' => array( 'custom' ),
			)
		);

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'          => __( 'Slides to Show', 'alternatepro-elements' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'default'        => 'default',
				'tablet_default' => 'default',
				'mobile_default' => 'default',
				'options'        => $this->get_slide_count_options( true ),
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'          => __( 'Slides to Scroll', 'alternatepro-elements' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'default'        => 'default',
				'tablet_default' => 'default',
				'mobile_default' => 'default',
				'options'        => $this->get_slide_count_options( true ),
				'description'    => __( 'Set how many slides are scrolled per swipe.', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'image_stretch',
			array(
				'label'   => __( 'Image Stretch', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => array(
					'no'  => __( 'No', 'alternatepro-elements' ),
					'yes' => __( 'Yes', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'   => __( 'Navigation', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => __( 'None', 'alternatepro-elements' ),
					'arrows' => __( 'Arrows', 'alternatepro-elements' ),
					'dots'   => __( 'Dots', 'alternatepro-elements' ),
					'both'   => __( 'Arrows and Dots', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'   => __( 'Link', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => array(
					'none'   => __( 'None', 'alternatepro-elements' ),
					'file'   => __( 'Media File', 'alternatepro-elements' ),
					'custom' => __( 'Custom URL', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'custom_link',
			array(
				'label'       => __( 'Custom URL', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'Type or paste your URL', 'alternatepro-elements' ),
				'condition'   => array(
					'link_to' => 'custom',
				),
			)
		);

		$this->add_control(
			'caption_source',
			array(
				'label'   => __( 'Caption', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'        => __( 'None', 'alternatepro-elements' ),
					'caption'     => __( 'Attachment Caption', 'alternatepro-elements' ),
					'title'       => __( 'Attachment Title', 'alternatepro-elements' ),
					'description' => __( 'Attachment Description', 'alternatepro-elements' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register carousel behavior controls.
	 *
	 * @return void
	 */
	private function register_carousel_controls() {
		$this->start_controls_section(
			'section_carousel',
			array(
				'label' => __( 'Additional Options', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'loop',
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
			'autoplay_timeout',
			array(
				'label'     => __( 'Autoplay Timeout', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1000,
				'max'       => 10000,
				'step'      => 250,
				'default'   => 5000,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'smart_speed',
			array(
				'label'   => __( 'Animation Speed', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 100,
				'max'     => 2000,
				'step'    => 50,
				'default' => 450,
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
				'condition'    => array(
					'autoplay' => 'yes',
				),
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
				'condition'    => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @return void
	 */
	private function register_style_controls() {
		$this->register_image_style_controls();
		$this->register_caption_style_controls();
		$this->register_arrow_style_controls();
		$this->register_dot_style_controls();
	}

	/**
	 * Register image style controls.
	 *
	 * @return void
	 */
	private function register_image_style_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_vertical_align',
			array(
				'label'     => __( 'Vertical Align', 'alternatepro-elements' ),
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
					'{{WRAPPER}} .apro-image-carousel .owl-stage' => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'image_spacing_mode',
			array(
				'label'   => __( 'Spacing', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Default', 'alternatepro-elements' ),
					'custom'  => __( 'Custom', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => __( 'Custom Spacing', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'condition'  => array(
					'image_spacing_mode' => 'custom',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'label'    => __( 'Border Type', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .apro-image-carousel__image',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .apro-image-carousel__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => __( 'Height', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'separator'  => 'before',
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 900,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-image-carousel__image' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_object_fit',
			array(
				'label'     => __( 'Object Fit', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'      => __( 'Cover', 'alternatepro-elements' ),
					'contain'    => __( 'Contain', 'alternatepro-elements' ),
					'fill'       => __( 'Fill', 'alternatepro-elements' ),
					'none'       => __( 'None', 'alternatepro-elements' ),
					'scale-down' => __( 'Scale Down', 'alternatepro-elements' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel__image' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register caption style controls.
	 *
	 * @return void
	 */
	private function register_caption_style_controls() {
		$this->start_controls_section(
			'section_caption_style',
			array(
				'label'     => __( 'Caption', 'alternatepro-elements' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array(
					'caption_source!' => 'none',
				),
			)
		);

		$this->add_control(
			'caption_align',
			array(
				'label'     => __( 'Alignment', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
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
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel__caption' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'label'    => __( 'Typography', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .apro-image-carousel__caption',
			)
		);

		$this->add_control(
			'caption_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel__caption' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'caption_background_color',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel__caption' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'caption_padding',
			array(
				'label'      => __( 'Padding', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .apro-image-carousel__caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register arrow style controls.
	 *
	 * @return void
	 */
	private function register_arrow_style_controls() {
		$this->start_controls_section(
			'section_arrow_style',
			array(
				'label'     => __( 'Arrows', 'alternatepro-elements' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_responsive_control(
			'arrow_button_size',
			array(
				'label'      => __( 'Button Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 24,
						'max' => 96,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-arrow-button-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'arrow_icon_size',
			array(
				'label'      => __( 'Icon Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 64,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-arrow-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'arrow_style_tabs' );

		$this->start_controls_tab(
			'arrow_style_normal_tab',
			array(
				'label' => __( 'Normal', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'arrow_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-arrow-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrow_background_color',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-arrow-background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrow_style_hover_tab',
			array(
				'label' => __( 'Hover', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'arrow_color_hover',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-arrow-hover-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrow_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-arrow-hover-background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register dot style controls.
	 *
	 * @return void
	 */
	private function register_dot_style_controls() {
		$this->start_controls_section(
			'section_dot_style',
			array(
				'label'     => __( 'Dots', 'alternatepro-elements' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_responsive_control(
			'dot_size',
			array(
				'label'      => __( 'Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 4,
						'max' => 24,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-dot-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dot_spacing',
			array(
				'label'      => __( 'Spacing', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-dot-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-dot-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dot_active_color',
			array(
				'label'     => __( 'Active Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .apro-image-carousel' => '--ap-image-carousel-dot-active-color: {{VALUE}};',
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
		$settings = $this->get_settings_for_display();
		$images   = $this->get_gallery_images( $settings );

		$this->render_ap_custom_css( $settings );

		if ( empty( $images ) ) {
			$this->render_empty_message();

			return;
		}

		$carousel_id  = 'apro-image-carousel-' . $this->get_id();
		$label        = isset( $settings['carousel_label'] ) ? sanitize_text_field( $settings['carousel_label'] ) : '';
		$label        = '' !== $label ? $label : __( 'Image carousel', 'alternatepro-elements' );
		$options      = $this->get_carousel_options( $settings, count( $images ) );
		$options_json = wp_json_encode( $options );

		printf(
			'<div id="%1$s" class="%2$s" data-apro-image-carousel="1" data-apro-image-carousel-options="%3$s" role="region" aria-label="%4$s">',
			esc_attr( $carousel_id ),
			esc_attr( implode( ' ', $this->get_container_classes( $settings ) ) ),
			esc_attr( false !== $options_json ? $options_json : '{}' ),
			esc_attr( $label )
		);

		echo '<div class="apro-image-carousel__track owl-carousel owl-theme">';

		foreach ( $images as $index => $image ) {
			$this->render_slide( $image, $settings, $index, count( $images ) );
		}

		echo '</div>';

		echo '</div>';
	}

	/**
	 * Render a single carousel slide.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @param array<string,mixed> $settings Widget settings.
	 * @param int                 $index Zero-based slide index.
	 * @param int                 $total Total slides.
	 * @return void
	 */
	private function render_slide( array $image, array $settings, $index, $total ) {
		$image_html = $this->get_image_html( $image, $settings );

		if ( '' === $image_html ) {
			return;
		}

		$caption = $this->get_image_caption( $image, $settings );
		$link    = $this->get_image_link( $image, $settings );

		printf(
			'<div class="apro-image-carousel__slide" role="group" aria-roledescription="%1$s" aria-label="%2$s">',
			esc_attr__( 'slide', 'alternatepro-elements' ),
			esc_attr(
				sprintf(
					/* translators: 1: Current slide number, 2: Total slide count. */
					__( '%1$d of %2$d', 'alternatepro-elements' ),
					absint( $index ) + 1,
					absint( $total )
				)
			)
		);

		echo '<figure class="apro-image-carousel__figure">';

		if ( ! empty( $link['url'] ) ) {
			printf(
				'<a class="apro-image-carousel__link" href="%1$s"%2$s%3$s>',
				esc_url( $link['url'] ),
				! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '"' : '',
				! empty( $link['rel'] ) ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''
			);
		}

		echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by get_image_html().

		if ( ! empty( $link['url'] ) ) {
			echo '</a>';
		}

		if ( '' !== $caption ) {
			printf(
				'<figcaption class="apro-image-carousel__caption">%s</figcaption>',
				wp_kses_post( $caption )
			);
		}

		echo '</figure>';
		echo '</div>';
	}

	/**
	 * Render empty editor message.
	 *
	 * @return void
	 */
	private function render_empty_message() {
		if ( ! class_exists( '\Elementor\Plugin' ) || ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		echo '<div class="apro-image-carousel apro-image-carousel--empty">';
		echo esc_html__( 'Choose images to build this carousel.', 'alternatepro-elements' );
		echo '</div>';
	}

	/**
	 * Get sanitized container classes.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string[]
	 */
	private function get_container_classes( array $settings ) {
		return array(
			'apro-image-carousel',
			'apro-image-carousel--stretch-' . sanitize_html_class( $this->get_image_stretch( $settings ) ),
			'apro-image-carousel--navigation-' . sanitize_html_class( $this->get_navigation( $settings ) ),
		);
	}

	/**
	 * Get sanitized gallery image data.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<int,array<string,mixed>>
	 */
	private function get_gallery_images( array $settings ) {
		$gallery = isset( $settings['gallery'] ) && is_array( $settings['gallery'] ) ? $settings['gallery'] : array();
		$images  = array();

		foreach ( $gallery as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$id  = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
			$url = isset( $item['url'] ) ? esc_url_raw( $item['url'] ) : '';

			if ( ! $id && '' === $url ) {
				continue;
			}

			$images[] = array(
				'id'  => $id,
				'url' => $url,
			);
		}

		return $images;
	}

	/**
	 * Get safe image HTML.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_image_html( array $image, array $settings ) {
		$id    = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
		$url   = isset( $image['url'] ) ? esc_url_raw( $image['url'] ) : '';
		$alt   = $this->get_image_alt( $image );
		$attrs = array(
			'alt'      => $alt,
			'class'    => 'apro-image-carousel__image',
			'decoding' => 'async',
			'loading'  => 'lazy',
		);

		if ( $id ) {
			$html = wp_get_attachment_image( $id, $this->get_image_size( $settings ), false, $attrs );

			if ( '' !== $html ) {
				return $html;
			}
		}

		if ( '' === $url ) {
			return '';
		}

		return sprintf(
			'<img class="apro-image-carousel__image" src="%1$s" alt="%2$s" loading="lazy" decoding="async">',
			esc_url( $url ),
			esc_attr( $alt )
		);
	}

	/**
	 * Get image size setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_image_size( array $settings ) {
		$size = isset( $settings['image_size'] ) ? sanitize_key( (string) $settings['image_size'] ) : 'large';

		return '' !== $size ? $size : 'large';
	}

	/**
	 * Get image alt text.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @return string
	 */
	private function get_image_alt( array $image ) {
		$id = isset( $image['id'] ) ? absint( $image['id'] ) : 0;

		if ( ! $id ) {
			return '';
		}

		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

		if ( '' === $alt ) {
			$alt = get_the_title( $id );
		}

		return sanitize_text_field( (string) $alt );
	}

	/**
	 * Get image caption text.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_image_caption( array $image, array $settings ) {
		$id     = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
		$source = isset( $settings['caption_source'] ) ? $settings['caption_source'] : '';
		$source = $this->sanitize_choice( $source, array( 'none', 'caption', 'title', 'description' ), 'none' );

		if ( ! $id || 'none' === $source ) {
			return '';
		}

		if ( 'title' === $source ) {
			return sanitize_text_field( get_the_title( $id ) );
		}

		if ( 'description' === $source ) {
			$attachment = get_post( $id );

			return $attachment ? wp_kses_post( $attachment->post_content ) : '';
		}

		return wp_kses_post( (string) wp_get_attachment_caption( $id ) );
	}

	/**
	 * Get link attributes for an image.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<string,string>
	 */
	private function get_image_link( array $image, array $settings ) {
		$id      = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
		$url     = isset( $image['url'] ) ? esc_url_raw( $image['url'] ) : '';
		$link_to = isset( $settings['link_to'] ) ? $settings['link_to'] : '';
		$link_to = $this->sanitize_choice( $link_to, array( 'none', 'file', 'attachment', 'custom' ), 'none' );
		$link    = array(
			'url'    => '',
			'target' => '',
			'rel'    => '',
		);

		if ( 'file' === $link_to ) {
			$link['url'] = $id ? (string) wp_get_attachment_url( $id ) : $url;

			return $link;
		}

		if ( 'attachment' === $link_to && $id ) {
			$link['url'] = (string) get_attachment_link( $id );

			return $link;
		}

		if ( 'custom' !== $link_to || empty( $settings['custom_link'] ) || ! is_array( $settings['custom_link'] ) ) {
			return $link;
		}

		$custom_link = $settings['custom_link'];
		$link['url'] = isset( $custom_link['url'] ) ? esc_url_raw( $custom_link['url'] ) : '';

		if ( empty( $link['url'] ) ) {
			return $link;
		}

		if ( ! empty( $custom_link['is_external'] ) ) {
			$link['target'] = '_blank';
			$link['rel']    = 'noopener';
		}

		if ( ! empty( $custom_link['nofollow'] ) ) {
			$link['rel'] = trim( $link['rel'] . ' nofollow' );
		}

		return $link;
	}

	/**
	 * Get Owl Carousel options.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param int                 $image_count Number of rendered images.
	 * @return array<string,mixed>
	 */
	private function get_carousel_options( array $settings, $image_count ) {
		$desktop_items  = $this->get_responsive_size( $settings, 'slides_to_show', '', 5, 1, 10 );
		$tablet_items   = $this->get_responsive_size( $settings, 'slides_to_show', 'tablet', 3, 1, 10 );
		$mobile_items   = $this->get_responsive_size( $settings, 'slides_to_show', 'mobile', 1, 1, 10 );
		$desktop_scroll = $this->get_responsive_scroll( $settings, '', $desktop_items );
		$tablet_scroll  = $this->get_responsive_scroll( $settings, 'tablet', $tablet_items );
		$mobile_scroll  = $this->get_responsive_scroll( $settings, 'mobile', $mobile_items );
		$desktop_gap    = $this->get_responsive_image_spacing( $settings, '', 20 );
		$tablet_gap     = $this->get_responsive_image_spacing( $settings, 'tablet', 20 );
		$mobile_gap     = $this->get_responsive_image_spacing( $settings, 'mobile', 12 );
		$loop           = $this->get_switcher_value( $settings, 'loop' ) && $image_count > $desktop_items;
		$navigation     = $this->get_navigation( $settings );

		return array(
			'items'              => $desktop_items,
			'slideBy'            => min( $desktop_scroll, $desktop_items ),
			'margin'             => $desktop_gap,
			'loop'               => $loop,
			'autoplay'           => $this->get_switcher_value( $settings, 'autoplay' ),
			'autoplayTimeout'    => $this->get_numeric_setting( $settings, 'autoplay_timeout', 5000, 1000, 10000 ),
			'autoplayHoverPause' => $this->get_switcher_value( $settings, 'pause_on_hover' ),
			'pauseOnInteraction' => $this->get_switcher_value( $settings, 'pause_on_interaction' ),
			'smartSpeed'         => $this->get_numeric_setting( $settings, 'smart_speed', 450, 100, 2000 ),
			'nav'                => in_array( $navigation, array( 'arrows', 'both' ), true ),
			'dots'               => in_array( $navigation, array( 'dots', 'both' ), true ),
			'rtl'                => is_rtl(),
			'prevLabel'          => __( 'Previous slide', 'alternatepro-elements' ),
			'nextLabel'          => __( 'Next slide', 'alternatepro-elements' ),
			'dotLabel'           => __( 'Go to slide', 'alternatepro-elements' ),
			'responsive'         => array(
				0    => array(
					'items'   => $mobile_items,
					'slideBy' => min( $mobile_scroll, $mobile_items ),
					'margin'  => $mobile_gap,
				),
				768  => array(
					'items'   => $tablet_items,
					'slideBy' => min( $tablet_scroll, $tablet_items ),
					'margin'  => $tablet_gap,
				),
				1025 => array(
					'items'   => $desktop_items,
					'slideBy' => min( $desktop_scroll, $desktop_items ),
					'margin'  => $desktop_gap,
				),
			),
		);
	}

	/**
	 * Get a responsive slides-to-scroll value.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $device Device suffix.
	 * @param int                 $fallback Fallback slide count.
	 * @return int
	 */
	private function get_responsive_scroll( array $settings, $device, $fallback ) {
		$setting_key = '' === $device ? 'slides_to_scroll' : 'slides_to_scroll_' . sanitize_key( $device );
		$value       = isset( $settings[ $setting_key ] ) ? $settings[ $setting_key ] : 'default';

		if ( is_array( $value ) && isset( $value['size'] ) ) {
			$value = $value['size'];
		}

		if ( 'default' === $value || '' === $value || ! is_numeric( $value ) ) {
			return $fallback;
		}

		return max( 1, min( 10, absint( $value ) ) );
	}

	/**
	 * Get a responsive image spacing value.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $device Device suffix.
	 * @param int                 $fallback Fallback spacing.
	 * @return int
	 */
	private function get_responsive_image_spacing( array $settings, $device, $fallback ) {
		$mode = isset( $settings['image_spacing_mode'] ) ? $settings['image_spacing_mode'] : 'default';
		$mode = $this->sanitize_choice( $mode, array( 'default', 'custom' ), 'default' );
		$key  = 'custom' === $mode ? 'image_spacing' : 'slide_spacing';

		return $this->get_responsive_size( $settings, $key, $device, $fallback, 0, 80 );
	}

	/**
	 * Get a responsive Elementor slider value.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $key Base setting key.
	 * @param string              $device Device suffix.
	 * @param int                 $fallback Fallback value.
	 * @param int                 $min Minimum value.
	 * @param int                 $max Maximum value.
	 * @return int
	 */
	private function get_responsive_size( array $settings, $key, $device, $fallback, $min, $max ) {
		$setting_key = '' === $device ? $key : $key . '_' . sanitize_key( $device );
		$value       = isset( $settings[ $setting_key ] ) ? $settings[ $setting_key ] : null;

		if ( is_array( $value ) && isset( $value['size'] ) ) {
			$value = $value['size'];
		}

		if ( ! is_numeric( $value ) ) {
			$value = $fallback;
		}

		return max( $min, min( $max, absint( $value ) ) );
	}

	/**
	 * Get sanitized image stretch setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_image_stretch( array $settings ) {
		$value = isset( $settings['image_stretch'] ) ? $settings['image_stretch'] : '';

		return $this->sanitize_choice( $value, array( 'yes', 'no' ), 'no' );
	}

	/**
	 * Get sanitized navigation setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_navigation( array $settings ) {
		if ( isset( $settings['navigation'] ) ) {
			return $this->sanitize_choice( $settings['navigation'], array( 'none', 'arrows', 'dots', 'both' ), 'none' );
		}

		$show_arrows = $this->get_switcher_value( $settings, 'show_arrows' );
		$show_dots   = $this->get_switcher_value( $settings, 'show_dots' );

		if ( $show_arrows && $show_dots ) {
			return 'both';
		}

		if ( $show_arrows ) {
			return 'arrows';
		}

		if ( $show_dots ) {
			return 'dots';
		}

		return 'none';
	}

	/**
	 * Get slide count select options.
	 *
	 * @param bool $include_default Whether to include the Default option.
	 * @return array<string,string>
	 */
	private function get_slide_count_options( $include_default ) {
		$options = array();

		if ( $include_default ) {
			$options['default'] = __( 'Default', 'alternatepro-elements' );
		}

		for ( $count = 1; $count <= 10; $count++ ) {
			$options[ (string) $count ] = (string) $count;
		}

		return $options;
	}
}
