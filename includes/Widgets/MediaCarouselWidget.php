<?php
/**
 * AP Media Carousel Elementor widget.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Widgets;

defined( 'ABSPATH' ) || exit;

/**
 * Provides the AP Media Carousel widget foundation for Elementor Free.
 */
final class MediaCarouselWidget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ap-media-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'AP Media Carousel', 'alternatepro-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-media-carousel';
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
		return array( 'ap', 'apro', 'media carousel', 'media', 'carousel', 'slider', 'image', 'video', 'alternatepro' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @return void
	 */
	private function register_content_controls() {
		$repeater = new \Elementor\Repeater();

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
				'label'   => __( 'Slides', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(),
					array(),
					array(),
					array(),
					array(),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register navigation style controls.
	 *
	 * @return void
	 */
	private function register_navigation_style_controls() {
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

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => __( 'Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-arrows-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-arrows-color: {{VALUE}};',
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
				'default'              => 'outside',
				'options'              => array(
					'inside'  => __( 'Inside', 'alternatepro-elements' ),
					'outside' => __( 'Outside', 'alternatepro-elements' ),
				),
				'selectors_dictionary' => array(
					'inside'  => '--ap-media-carousel-pagination-position: inside;',
					'outside' => '--ap-media-carousel-pagination-position: outside;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ap-media-carousel' => '{{VALUE}}',
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
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-pagination-spacing: {{SIZE}}{{UNIT}};',
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
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-pagination-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-pagination-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => __( 'Active Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-pagination-active-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_icon_heading',
			array(
				'label'     => __( 'Play Icon', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'play_icon_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-play-icon-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'play_icon_size',
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
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-play-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'play_icon_shadow',
				'label'    => __( 'Shadow', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .ap-media-carousel__play-icon',
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
		$this->start_controls_section(
			'section_slides_style',
			array(
				'label' => __( 'Slides', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'slides_space_between',
			array(
				'label'      => __( 'Space Between', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel__slides' => 'display: flex; gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'slides_background_color',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel__slide' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_border_width',
			array(
				'label'      => __( 'Border Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel__slide' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_border_radius',
			array(
				'label'      => __( 'Border Radius', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel__slide' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'slides_border_color',
			array(
				'label'     => __( 'Border Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel__slide' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_padding',
			array(
				'label'      => __( 'Padding', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel__slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
		$this->register_navigation_style_controls();
	}

	/**
	 * Render the widget placeholder.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$label    = empty( $settings['slides_name'] ) ? __( 'Slides', 'alternatepro-elements' ) : $settings['slides_name'];

		echo '<div class="ap-media-carousel" role="region" aria-label="' . esc_attr( $label ) . '">';
		echo '<div class="ap-media-carousel__slides">';
		echo '<div class="ap-media-carousel__slide ap-media-carousel-widget-placeholder">' . esc_html__( 'AP Media Carousel Widget', 'alternatepro-elements' ) . '</div>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render the editor content template.
	 *
	 * @return void
	 */
	protected function content_template() {}
}
