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
	 * Get widget style dependencies.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( WidgetsModule::MEDIA_CAROUSEL_STYLE );
	}

	/**
	 * Get widget script dependencies.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( WidgetsModule::MEDIA_CAROUSEL_SCRIPT );
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
		$repeater      = new \Elementor\Repeater();
		$default_image = $this->get_placeholder_image_default();

		$repeater->add_control(
			'slide_type',
			array(
				'label'   => __( 'Type', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'image',
				'toggle'  => false,
				'options' => array(
					'image' => array(
						'title' => __( 'Image', 'alternatepro-elements' ),
						'icon'  => 'eicon-image',
					),
					'video' => array(
						'title' => __( 'Video', 'alternatepro-elements' ),
						'icon'  => 'eicon-video-camera',
					),
				),
			)
		);

		$repeater->add_control(
			'slide_image',
			array(
				'label'       => __( 'Image', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_types' => array( 'image' ),
				'default'     => $default_image,
			)
		);

		$repeater->add_control(
			'slide_video_link',
			array(
				'label'       => __( 'Video Link', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your video link', 'alternatepro-elements' ),
				'description' => __( 'YouTube or Vimeo link', 'alternatepro-elements' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'slide_type' => 'video',
				),
			)
		);

		$repeater->add_control(
			'slide_link_to',
			array(
				'label'     => __( 'Link', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'alternatepro-elements' ),
					'file'   => __( 'Media File', 'alternatepro-elements' ),
					'custom' => __( 'Custom URL', 'alternatepro-elements' ),
				),
				'condition' => array(
					'slide_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'slide_custom_link',
			array(
				'label'       => __( 'Custom URL', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'Type or paste your URL', 'alternatepro-elements' ),
				'condition'   => array(
					'slide_link_to' => 'custom',
					'slide_type'    => 'image',
				),
			)
		);

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
					array(
						'slide_image'   => $default_image,
						'slide_type'    => 'image',
						'slide_link_to' => 'none',
					),
					array(
						'slide_image'   => $default_image,
						'slide_type'    => 'image',
						'slide_link_to' => 'none',
					),
					array(
						'slide_image'   => $default_image,
						'slide_type'    => 'image',
						'slide_link_to' => 'none',
					),
					array(
						'slide_image'   => $default_image,
						'slide_type'    => 'image',
						'slide_link_to' => 'none',
					),
					array(
						'slide_image'   => $default_image,
						'slide_type'    => 'image',
						'slide_link_to' => 'none',
					),
				),
			)
		);

		$this->add_carousel_options_controls();
		$this->end_controls_section();
	}

	/**
	 * Add carousel content option controls.
	 *
	 * @return void
	 */
	private function add_carousel_options_controls() {
		$this->add_control(
			'effect',
			array(
				'label'     => __( 'Effect', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'slide',
				'separator' => 'before',
				'options'   => array(
					'slide' => __( 'Slide', 'alternatepro-elements' ),
					'fade'  => __( 'Fade', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'label'                => __( 'Slides Per View', 'alternatepro-elements' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'default'              => 'default',
				'tablet_default'       => 'default',
				'mobile_default'       => 'default',
				'options'              => $this->get_slide_count_options( true ),
				'selectors_dictionary' => $this->get_slides_per_view_selectors_dictionary(),
				'selectors'            => array(
					'{{WRAPPER}} .ap-media-carousel' => '{{VALUE}}',
				),
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

		$this->add_responsive_control(
			'carousel_height',
			array(
				'label'      => __( 'Height', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'carousel_width',
			array(
				'label'      => __( 'Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
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

		for ( $count = 1; $count <= 6; $count++ ) {
			$options[ (string) $count ] = (string) $count;
		}

		return $options;
	}

	/**
	 * Get CSS selector values for slides per view.
	 *
	 * @return array<string,string>
	 */
	private function get_slides_per_view_selectors_dictionary() {
		$dictionary = array(
			'default' => '',
		);

		for ( $count = 1; $count <= 6; $count++ ) {
			$dictionary[ (string) $count ] = '--ap-media-carousel-slide-width: ' . $this->get_slide_width_expression( $count ) . ';';
		}

		return $dictionary;
	}

	/**
	 * Get the CSS width expression for a visible slide count.
	 *
	 * @param int $count Visible slide count.
	 * @return string
	 */
	private function get_slide_width_expression( $count ) {
		if ( 1 >= $count ) {
			return '100%';
		}

		$gaps = implode( ' - ', array_fill( 0, $count - 1, 'var(--ap-media-carousel-slides-gap)' ) );

		return 'calc((100% - ' . $gaps . ') / ' . $count . ')';
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
					'size' => 32,
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
					'inside'  => '--ap-media-carousel-pagination-bottom: 16px;',
					'outside' => '--ap-media-carousel-pagination-bottom: -24px;',
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
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
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
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-slides-gap: {{SIZE}}{{UNIT}};',
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
	 * Get slides for the current render.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<int,array<string,mixed>>
	 */
	private function get_render_slides( array $settings ) {
		$slides = array();

		if ( ! empty( $settings['slides'] ) && is_array( $settings['slides'] ) ) {
			$slides = array_values( array_filter( $settings['slides'], 'is_array' ) );
		}

		if ( empty( $slides ) ) {
			return array_fill( 0, 5, array() );
		}

		return $slides;
	}

	/**
	 * Get the default image control value.
	 *
	 * @return array<string,string>
	 */
	private function get_placeholder_image_default() {
		return array(
			'url' => $this->get_placeholder_image_src(),
		);
	}

	/**
	 * Get the default placeholder image URL.
	 *
	 * @return string
	 */
	private function get_placeholder_image_src() {
		if ( class_exists( '\Elementor\Utils' ) ) {
			return \Elementor\Utils::get_placeholder_image_src();
		}

		return '';
	}

	/**
	 * Get image data for a repeater slide.
	 *
	 * @param array<string,mixed> $slide           Slide settings.
	 * @param string              $placeholder_src Default placeholder URL.
	 * @return array<string,mixed>
	 */
	private function get_slide_image( array $slide, $placeholder_src ) {
		$image = isset( $slide['slide_image'] ) && is_array( $slide['slide_image'] ) ? $slide['slide_image'] : array();
		$id    = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
		$url   = isset( $image['url'] ) ? esc_url_raw( (string) $image['url'] ) : '';

		if ( ! $id && '' === $url ) {
			$url = esc_url_raw( $placeholder_src );
		}

		return array(
			'id'  => $id,
			'url' => $url,
		);
	}

	/**
	 * Get safe image HTML for a repeater slide.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @return string
	 */
	private function get_slide_image_html( array $image ) {
		$id    = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
		$url   = isset( $image['url'] ) ? esc_url_raw( (string) $image['url'] ) : '';
		$alt   = $this->get_slide_image_alt( $image );
		$attrs = array(
			'alt'      => $alt,
			'class'    => 'ap-media-carousel__image',
			'decoding' => 'async',
			'loading'  => 'lazy',
		);

		if ( $id ) {
			$html = wp_get_attachment_image( $id, 'full', false, $attrs );

			if ( '' !== $html ) {
				return $html;
			}
		}

		if ( '' === $url ) {
			return '<span class="ap-media-carousel__image ap-media-carousel__image--empty" aria-hidden="true"></span>';
		}

		return sprintf(
			'<img class="ap-media-carousel__image" src="%1$s" alt="%2$s" loading="lazy" decoding="async" />',
			esc_url( $url ),
			esc_attr( $alt )
		);
	}

	/**
	 * Get image alt text for a repeater slide.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @return string
	 */
	private function get_slide_image_alt( array $image ) {
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
	 * Get frontend carousel options for the widget script.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<string,int>
	 */
	private function get_frontend_carousel_options( array $settings ) {
		$slides_to_scroll = isset( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 1;

		if ( 1 > $slides_to_scroll ) {
			$slides_to_scroll = 1;
		}

		return array(
			'slidesToScroll' => $slides_to_scroll,
		);
	}

	/**
	 * Check whether a repeater slide is configured as a video.
	 *
	 * @param array<string,mixed> $slide Slide settings.
	 * @return bool
	 */
	private function is_video_slide( array $slide ) {
		$slide_type = isset( $slide['slide_type'] ) ? sanitize_key( (string) $slide['slide_type'] ) : 'image';

		return 'video' === $slide_type;
	}

	/**
	 * Get link attributes for a repeater slide.
	 *
	 * @param array<string,mixed> $slide Slide settings.
	 * @param array<string,mixed> $image Image data.
	 * @return array<string,string>
	 */
	private function get_slide_link( array $slide, array $image ) {
		$slide_type = isset( $slide['slide_type'] ) ? sanitize_key( (string) $slide['slide_type'] ) : 'image';
		$link_to    = isset( $slide['slide_link_to'] ) ? sanitize_key( (string) $slide['slide_link_to'] ) : 'none';
		$link       = array(
			'url'    => '',
			'target' => '',
			'rel'    => '',
		);

		if ( 'image' !== $slide_type ) {
			return $link;
		}

		if ( 'file' === $link_to ) {
			$id          = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
			$url         = isset( $image['url'] ) ? esc_url_raw( (string) $image['url'] ) : '';
			$link['url'] = $id ? (string) wp_get_attachment_url( $id ) : $url;

			return $link;
		}

		if ( 'custom' !== $link_to || empty( $slide['slide_custom_link'] ) || ! is_array( $slide['slide_custom_link'] ) ) {
			return $link;
		}

		$custom_link = $slide['slide_custom_link'];
		$link['url'] = isset( $custom_link['url'] ) ? esc_url_raw( (string) $custom_link['url'] ) : '';

		if ( '' === $link['url'] ) {
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
	 * Render the widget placeholder.
	 *
	 * @return void
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
		$label           = empty( $settings['slides_name'] ) ? __( 'Slides', 'alternatepro-elements' ) : $settings['slides_name'];
		$slides          = $this->get_render_slides( $settings );
		$slide_count     = count( $slides );
		$placeholder_src = $this->get_placeholder_image_src();
		$options         = wp_json_encode( $this->get_frontend_carousel_options( $settings ) );

		echo '<div class="ap-media-carousel ap-media-carousel-widget-placeholder" role="region" aria-label="' . esc_attr( $label ) . '" data-ap-media-carousel="true" data-ap-media-carousel-options="' . esc_attr( $options ? $options : '{}' ) . '">';
		echo '<div class="ap-media-carousel__viewport">';
		echo '<div class="ap-media-carousel__slides">';

		foreach ( $slides as $index => $slide ) {
			$slide_label = sprintf(
				/* translators: 1: slide number, 2: total slides. */
				__( 'Slide %1$d of %2$d', 'alternatepro-elements' ),
				$index + 1,
				$slide_count
			);

			echo '<div class="ap-media-carousel__slide" role="group" aria-roledescription="' . esc_attr__( 'slide', 'alternatepro-elements' ) . '" aria-label="' . esc_attr( $slide_label ) . '">';

			$image      = $this->get_slide_image( $slide, $placeholder_src );
			$image_html = $this->get_slide_image_html( $image );
			$link       = $this->get_slide_link( $slide, $image );

			if ( '' !== $link['url'] ) {
				echo '<a class="ap-media-carousel__link" href="' . esc_url( $link['url'] ) . '"';

				if ( '' !== $link['target'] ) {
					echo ' target="' . esc_attr( $link['target'] ) . '"';
				}

				if ( '' !== $link['rel'] ) {
					echo ' rel="' . esc_attr( $link['rel'] ) . '"';
				}

				echo '>';
			}

			echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by get_slide_image_html().

			if ( $this->is_video_slide( $slide ) ) {
				echo '<span class="ap-media-carousel__play-icon" aria-hidden="true"></span>';
			}

			if ( '' !== $link['url'] ) {
				echo '</a>';
			}

			echo '</div>';
		}

		echo '</div>';
		echo '</div>';

		if ( 1 < $slide_count ) {
			echo '<button class="ap-media-carousel__arrow ap-media-carousel__arrow--prev" type="button" aria-label="' . esc_attr__( 'Previous slide', 'alternatepro-elements' ) . '" data-ap-media-carousel-action="previous" disabled="disabled">&#8249;</button>';
			echo '<button class="ap-media-carousel__arrow ap-media-carousel__arrow--next" type="button" aria-label="' . esc_attr__( 'Next slide', 'alternatepro-elements' ) . '" data-ap-media-carousel-action="next">&#8250;</button>';
			echo '<div class="ap-media-carousel__pagination" role="tablist" aria-label="' . esc_attr__( 'Choose slide', 'alternatepro-elements' ) . '">';

			for ( $index = 0; $index < $slide_count; $index++ ) {
				$is_active = 0 === $index;
				$dot_class = $is_active ? 'ap-media-carousel__dot ap-media-carousel__dot--active' : 'ap-media-carousel__dot';
				$dot_label = sprintf(
					/* translators: %d: slide number. */
					__( 'Go to slide %d', 'alternatepro-elements' ),
					$index + 1
				);
				echo '<button class="' . esc_attr( $dot_class ) . '" type="button" role="tab" aria-label="' . esc_attr( $dot_label ) . '" aria-current="' . esc_attr( $is_active ? 'true' : 'false' ) . '" aria-selected="' . esc_attr( $is_active ? 'true' : 'false' ) . '" data-ap-media-carousel-index="' . esc_attr( (string) $index ) . '"></button>';
			}

			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Render the editor content template.
	 *
	 * @return void
	 */
	protected function content_template() {}
}
