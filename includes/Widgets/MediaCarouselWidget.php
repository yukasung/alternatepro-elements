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
		return array( 'elementor-icons', WidgetsModule::MEDIA_CAROUSEL_STYLE );
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
		$this->register_additional_options_controls();
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
	 * Register additional carousel option controls.
	 *
	 * @return void
	 */
	private function register_additional_options_controls() {
		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => __( 'Additional Options', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => __( 'Arrows', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'alternatepro-elements' ),
				'label_off'    => __( 'Hide', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'   => __( 'Pagination', 'alternatepro-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'dots',
				'options' => array(
					'none' => __( 'None', 'alternatepro-elements' ),
					'dots' => __( 'Dots', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'transition_duration',
			array(
				'label'     => __( 'Transition Duration', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 5000,
				'step'      => 50,
				'default'   => 500,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-transition-duration: {{VALUE}}ms;',
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
				'separator'    => 'before',
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
				'default'      => '',
			)
		);

		$this->add_control(
			'overlay',
			array(
				'label'     => __( 'Overlay', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'icon',
				'separator' => 'before',
				'options'   => array(
					'none' => __( 'None', 'alternatepro-elements' ),
					'icon' => __( 'Icon', 'alternatepro-elements' ),
				),
			)
		);

		$this->add_control(
			'overlay_icon',
			array(
				'label'     => __( 'Icon', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => 'search',
				'toggle'    => false,
				'options'   => array(
					'search' => array(
						'title' => __( 'Search', 'alternatepro-elements' ),
						'icon'  => 'eicon-search',
					),
					'plus'   => array(
						'title' => __( 'Plus', 'alternatepro-elements' ),
						'icon'  => 'eicon-plus-circle',
					),
					'eye'    => array(
						'title' => __( 'View', 'alternatepro-elements' ),
						'icon'  => 'eicon-eye',
					),
					'link'   => array(
						'title' => __( 'Link', 'alternatepro-elements' ),
						'icon'  => 'eicon-link',
					),
				),
				'condition' => array(
					'overlay' => 'icon',
				),
			)
		);

		$this->add_control(
			'overlay_animation',
			array(
				'label'     => __( 'Animation', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'none' => __( 'None', 'alternatepro-elements' ),
					'fade' => __( 'Fade', 'alternatepro-elements' ),
				),
				'condition' => array(
					'overlay' => 'icon',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'label'     => __( 'Image Resolution', 'alternatepro-elements' ),
				'default'   => 'full',
				'exclude'   => array( 'custom' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_fit',
			array(
				'label'                => __( 'Image Fit', 'alternatepro-elements' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'default'              => 'cover',
				'options'              => array(
					'cover'   => __( 'Cover', 'alternatepro-elements' ),
					'contain' => __( 'Contain', 'alternatepro-elements' ),
					'auto'    => __( 'Auto', 'alternatepro-elements' ),
				),
				'selectors_dictionary' => array(
					'cover'   => '--ap-media-carousel-image-fit: cover;',
					'contain' => '--ap-media-carousel-image-fit: contain;',
					'auto'    => '--ap-media-carousel-image-fit: fill;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ap-media-carousel' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'lazy_load',
			array(
				'label'        => __( 'Lazy Load', 'alternatepro-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'alternatepro-elements' ),
				'label_off'    => __( 'No', 'alternatepro-elements' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();
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
	 * Register overlay style controls.
	 *
	 * @return void
	 */
	private function register_overlay_style_controls() {
		$this->start_controls_section(
			'section_overlay_style',
			array(
				'label' => __( 'Overlay', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_background_color',
			array(
				'label'     => __( 'Background Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6ec1e4',
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-overlay-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'overlay_text_color',
			array(
				'label'     => __( 'Text Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-overlay-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'overlay_icon_size',
			array(
				'label'      => __( 'Icon Size', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 120,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-overlay-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register lightbox style controls.
	 *
	 * @return void
	 */
	private function register_lightbox_style_controls() {
		$this->start_controls_section(
			'section_lightbox_style',
			array(
				'label' => __( 'Lightbox', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'lightbox_color',
			array(
				'label'     => __( 'Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6ec1e4',
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-lightbox-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'lightbox_ui_color',
			array(
				'label'     => __( 'UI Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-lightbox-ui-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'lightbox_ui_hover_color',
			array(
				'label'     => __( 'UI Hover Color', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-lightbox-ui-hover-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'lightbox_video_width',
			array(
				'label'      => __( 'Video Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'size' => 86,
					'unit' => '%',
				),
				'range'      => array(
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-media-carousel' => '--ap-media-carousel-lightbox-video-width: {{SIZE}}vw;',
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
		$this->register_overlay_style_controls();
		$this->register_lightbox_style_controls();
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
	 * @param array<string,mixed> $image    Image data.
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_slide_image_html( array $image, array $settings ) {
		$id      = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
		$url     = isset( $image['url'] ) ? esc_url_raw( (string) $image['url'] ) : '';
		$alt     = $this->get_slide_image_alt( $image );
		$loading = $this->get_image_loading_attribute( $settings );
		$attrs   = array(
			'alt'      => $alt,
			'class'    => 'ap-media-carousel__image',
			'decoding' => 'async',
			'loading'  => $loading,
		);

		if ( $id ) {
			$html = wp_get_attachment_image( $id, $this->get_image_size( $settings ), false, $attrs );

			if ( '' !== $html ) {
				return $html;
			}
		}

		if ( '' === $url ) {
			return '<span class="ap-media-carousel__image ap-media-carousel__image--empty" aria-hidden="true"></span>';
		}

		return sprintf(
			'<img class="ap-media-carousel__image" src="%1$s" alt="%2$s" loading="%3$s" decoding="async" />',
			esc_url( $url ),
			esc_attr( $alt ),
			esc_attr( $loading )
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
	 * @return array<string,mixed>
	 */
	private function get_frontend_carousel_options( array $settings ) {
		$slides_to_scroll = isset( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 1;

		if ( 1 > $slides_to_scroll ) {
			$slides_to_scroll = 1;
		}

		return array(
			'arrows'             => $this->is_switcher_enabled( $settings, 'show_arrows', true ),
			'autoplay'           => $this->is_switcher_enabled( $settings, 'autoplay', false ),
			'loop'               => $this->is_switcher_enabled( $settings, 'infinite_loop', false ),
			'pagination'         => $this->get_pagination_type( $settings ),
			'slidesToScroll'     => $slides_to_scroll,
			'transitionDuration' => $this->get_transition_duration( $settings ),
		);
	}

	/**
	 * Check whether an Elementor switcher setting is enabled.
	 *
	 * @param array<string,mixed> $settings      Widget settings.
	 * @param string              $key           Setting key.
	 * @param bool                $default_value Default state.
	 * @return bool
	 */
	private function is_switcher_enabled( array $settings, $key, $default_value = false ) {
		if ( ! array_key_exists( $key, $settings ) ) {
			return (bool) $default_value;
		}

		return 'yes' === $settings[ $key ];
	}

	/**
	 * Get pagination type.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_pagination_type( array $settings ) {
		$pagination = isset( $settings['pagination'] ) ? sanitize_key( (string) $settings['pagination'] ) : 'dots';

		return in_array( $pagination, array( 'none', 'dots' ), true ) ? $pagination : 'dots';
	}

	/**
	 * Get transition duration in milliseconds.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return int
	 */
	private function get_transition_duration( array $settings ) {
		$duration = isset( $settings['transition_duration'] ) ? absint( $settings['transition_duration'] ) : 500;

		return min( max( $duration, 0 ), 5000 );
	}

	/**
	 * Get overlay mode.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_overlay_mode( array $settings ) {
		$overlay = isset( $settings['overlay'] ) ? sanitize_key( (string) $settings['overlay'] ) : 'icon';

		return in_array( $overlay, array( 'none', 'icon' ), true ) ? $overlay : 'icon';
	}

	/**
	 * Get overlay icon key.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_overlay_icon_key( array $settings ) {
		$icon = isset( $settings['overlay_icon'] ) ? sanitize_key( (string) $settings['overlay_icon'] ) : 'search';

		return in_array( $icon, array( 'search', 'plus', 'eye', 'link' ), true ) ? $icon : 'search';
	}

	/**
	 * Get overlay animation key.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_overlay_animation( array $settings ) {
		$animation = isset( $settings['overlay_animation'] ) ? sanitize_key( (string) $settings['overlay_animation'] ) : 'fade';

		return in_array( $animation, array( 'none', 'fade' ), true ) ? $animation : 'fade';
	}

	/**
	 * Get selected image size.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_image_size( array $settings ) {
		$size = isset( $settings['image_size'] ) ? sanitize_key( (string) $settings['image_size'] ) : 'full';

		return '' !== $size ? $size : 'full';
	}

	/**
	 * Get image loading attribute.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_image_loading_attribute( array $settings ) {
		return $this->is_switcher_enabled( $settings, 'lazy_load', false ) ? 'lazy' : 'eager';
	}

	/**
	 * Get widget wrapper classes.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_carousel_class_names( array $settings ) {
		$classes = array(
			'ap-media-carousel',
			'ap-media-carousel-widget-placeholder',
			'ap-media-carousel--overlay-' . $this->get_overlay_mode( $settings ),
			'ap-media-carousel--overlay-animation-' . $this->get_overlay_animation( $settings ),
		);

		return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
	}

	/**
	 * Get the accessible label for the media carousel viewport.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_slides_aria_label( array $settings ) {
		$label = isset( $settings['slides_name'] ) ? sanitize_text_field( $settings['slides_name'] ) : '';

		return '' !== $label ? $label : __( 'Slides', 'alternatepro-elements' );
	}

	/**
	 * Get overlay icon HTML.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $preferred_icon Preferred icon key.
	 * @return string
	 */
	private function get_overlay_icon_html( array $settings, $preferred_icon = '' ) {
		$icon         = '' === $preferred_icon ? $this->get_overlay_icon_key( $settings ) : sanitize_key( $preferred_icon );
		$icon_classes = array(
			'search' => 'eicon-search',
			'plus'   => 'eicon-plus-circle',
			'eye'    => 'eicon-eye',
			'link'   => 'eicon-link',
			'zoom'   => 'eicon-zoom-in-bold',
		);

		if ( ! isset( $icon_classes[ $icon ] ) ) {
			$icon = $this->get_overlay_icon_key( $settings );
		}

		return '<span class="ap-media-carousel__overlay-icon ap-media-carousel__overlay-icon--' . esc_attr( $icon ) . ' ' . esc_attr( $icon_classes[ $icon ] ) . '" aria-hidden="true"></span>';
	}

	/**
	 * Get overlay HTML.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param bool                $has_lightbox Whether the overlay should open a lightbox.
	 * @return string
	 */
	private function get_overlay_html( array $settings, $has_lightbox ) {
		if ( 'icon' !== $this->get_overlay_mode( $settings ) ) {
			return '';
		}

		$icon_html = $this->get_overlay_icon_html( $settings );

		if ( $has_lightbox ) {
			return '<span class="ap-media-carousel__overlay"><button class="ap-media-carousel__overlay-trigger" type="button" aria-label="' . esc_attr__( 'Open media in lightbox', 'alternatepro-elements' ) . '" data-ap-media-carousel-lightbox-trigger="true">' . $icon_html . '</button></span>';
		}

		return '<span class="ap-media-carousel__overlay" aria-hidden="true">' . $icon_html . '</span>';
	}

	/**
	 * Get video hover overlay HTML.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_video_hover_overlay_html( array $settings ) {
		if ( 'icon' !== $this->get_overlay_mode( $settings ) ) {
			return '';
		}

		return '<span class="ap-media-carousel__overlay ap-media-carousel__video-hover-overlay" aria-hidden="true">' . $this->get_overlay_icon_html( $settings, 'zoom' ) . '</span>';
	}

	/**
	 * Get video play icon HTML.
	 *
	 * @param bool $has_lightbox Whether the play icon should open a lightbox.
	 * @param bool $has_hover_overlay Whether the play icon has a video hover overlay.
	 * @return string
	 */
	private function get_video_play_icon_html( $has_lightbox, $has_hover_overlay = false ) {
		if ( ! $has_lightbox ) {
			return '';
		}

		$class_names = 'ap-media-carousel__play-icon';

		if ( $has_hover_overlay ) {
			$class_names .= ' ap-media-carousel__play-icon--has-hover-overlay';
		}

		return '<button class="' . esc_attr( $class_names ) . '" type="button" aria-label="' . esc_attr__( 'Open video in lightbox', 'alternatepro-elements' ) . '" data-ap-media-carousel-lightbox-trigger="true"></button>';
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
	 * Get a video URL from a repeater slide.
	 *
	 * @param array<string,mixed> $slide Slide settings.
	 * @return string
	 */
	private function get_slide_video_url( array $slide ) {
		return isset( $slide['slide_video_link'] ) ? esc_url_raw( (string) $slide['slide_video_link'] ) : '';
	}

	/**
	 * Get the full image source used by the lightbox.
	 *
	 * @param array<string,mixed> $image Image data.
	 * @return string
	 */
	private function get_lightbox_image_src( array $image ) {
		$id = isset( $image['id'] ) ? absint( $image['id'] ) : 0;

		if ( $id ) {
			$attachment_url = wp_get_attachment_url( $id );

			if ( $attachment_url ) {
				return esc_url_raw( $attachment_url );
			}
		}

		return isset( $image['url'] ) ? esc_url_raw( (string) $image['url'] ) : '';
	}

	/**
	 * Get lightbox data for a slide.
	 *
	 * @param array<string,mixed> $slide Slide settings.
	 * @param array<string,mixed> $image Image data.
	 * @return array{type:string,src:string}
	 */
	private function get_lightbox_item( array $slide, array $image ) {
		if ( $this->is_video_slide( $slide ) ) {
			return array(
				'type' => 'video',
				'src'  => $this->get_slide_video_url( $slide ),
			);
		}

		return array(
			'type' => 'image',
			'src'  => $this->get_lightbox_image_src( $image ),
		);
	}

	/**
	 * Get lightbox data attributes for a slide.
	 *
	 * @param array{type:string,src:string} $lightbox_item Lightbox item data.
	 * @return string
	 */
	private function get_lightbox_attributes( array $lightbox_item ) {
		if ( empty( $lightbox_item['src'] ) ) {
			return '';
		}

		return ' data-ap-media-carousel-lightbox-type="' . esc_attr( $lightbox_item['type'] ) . '" data-ap-media-carousel-lightbox-src="' . esc_url( $lightbox_item['src'] ) . '"';
	}

	/**
	 * Get translated lightbox labels for the frontend runtime.
	 *
	 * @return string
	 */
	private function get_lightbox_label_attributes() {
		$labels = array(
			'close'           => __( 'Close lightbox', 'alternatepro-elements' ),
			'dialog'          => __( 'Media lightbox', 'alternatepro-elements' ),
			'exit-fullscreen' => __( 'Exit fullscreen', 'alternatepro-elements' ),
			'fallback'        => __( 'Open video', 'alternatepro-elements' ),
			'fullscreen'      => __( 'Fullscreen', 'alternatepro-elements' ),
			'next'            => __( 'Next media', 'alternatepro-elements' ),
			'previous'        => __( 'Previous media', 'alternatepro-elements' ),
			'share'           => __( 'Share media', 'alternatepro-elements' ),
			'video'           => __( 'Video', 'alternatepro-elements' ),
			'zoom'            => __( 'Zoom media', 'alternatepro-elements' ),
			'zoom-out'        => __( 'Zoom out', 'alternatepro-elements' ),
		);
		$html   = '';

		foreach ( $labels as $key => $label ) {
			$html .= ' data-ap-media-carousel-lightbox-' . esc_attr( $key ) . '-label="' . esc_attr( $label ) . '"';
		}

		return $html;
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
		$label           = $this->get_slides_aria_label( $settings );
		$slides          = $this->get_render_slides( $settings );
		$slide_count     = count( $slides );
		$placeholder_src = $this->get_placeholder_image_src();
		$class_names     = $this->get_carousel_class_names( $settings );
		$options         = wp_json_encode( $this->get_frontend_carousel_options( $settings ) );

		echo '<div class="' . esc_attr( $class_names ) . '" data-ap-media-carousel="true" data-ap-media-carousel-options="' . esc_attr( $options ? $options : '{}' ) . '"' . $this->get_lightbox_label_attributes() . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by get_lightbox_label_attributes().
		echo '<div class="ap-media-carousel__viewport" role="region" aria-roledescription="' . esc_attr__( 'carousel', 'alternatepro-elements' ) . '" aria-label="' . esc_attr( $label ) . '">';
		echo '<div class="ap-media-carousel__slides">';

		foreach ( $slides as $index => $slide ) {
			$image         = $this->get_slide_image( $slide, $placeholder_src );
			$image_html    = $this->get_slide_image_html( $image, $settings );
			$link          = $this->get_slide_link( $slide, $image );
			$is_video      = $this->is_video_slide( $slide );
			$lightbox_item = $this->get_lightbox_item( $slide, $image );
			$slide_label   = sprintf(
				/* translators: 1: slide number, 2: total slides. */
				__( 'Slide %1$d of %2$d', 'alternatepro-elements' ),
				$index + 1,
				$slide_count
			);

			echo '<div class="ap-media-carousel__slide" role="group" aria-roledescription="' . esc_attr__( 'slide', 'alternatepro-elements' ) . '" aria-label="' . esc_attr( $slide_label ) . '"' . $this->get_lightbox_attributes( $lightbox_item ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by get_lightbox_attributes().

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

			if ( '' !== $link['url'] ) {
				echo '</a>';
			}

			if ( $is_video ) {
				$has_video_lightbox = '' !== $lightbox_item['src'];
				$has_hover_overlay  = $has_video_lightbox && 'icon' === $this->get_overlay_mode( $settings );

				echo $this->get_video_play_icon_html( $has_video_lightbox, $has_hover_overlay ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by get_video_play_icon_html().
				echo $has_hover_overlay ? $this->get_video_hover_overlay_html( $settings ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by get_video_hover_overlay_html().
			} else {
				echo $this->get_overlay_html( $settings, '' !== $lightbox_item['src'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by get_overlay_html().
			}

			echo '</div>';
		}

		echo '</div>';
		echo '</div>';

		if ( 1 < $slide_count ) {
			if ( $this->is_switcher_enabled( $settings, 'show_arrows', true ) ) {
				echo '<button class="ap-media-carousel__arrow ap-media-carousel__arrow--prev" type="button" aria-label="' . esc_attr__( 'Previous slide', 'alternatepro-elements' ) . '" data-ap-media-carousel-action="previous" disabled="disabled">&#8249;</button>';
				echo '<button class="ap-media-carousel__arrow ap-media-carousel__arrow--next" type="button" aria-label="' . esc_attr__( 'Next slide', 'alternatepro-elements' ) . '" data-ap-media-carousel-action="next">&#8250;</button>';
			}

			if ( 'dots' === $this->get_pagination_type( $settings ) ) {
				echo '<div class="ap-media-carousel__pagination" role="tablist" aria-label="' . esc_attr__( 'Choose slide', 'alternatepro-elements' ) . '" data-ap-media-carousel-dot-label="' . esc_attr__( 'Go to slide', 'alternatepro-elements' ) . '"></div>';
			}
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
