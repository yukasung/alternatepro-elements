<?php
/**
 * AP Site Logo Elementor widget.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Widgets;

use AlternatePro\Elements\Controls\ApCustomCssControl;
use AlternatePro\Elements\Traits\WidgetSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Provides the AP Site Logo widget foundation for Elementor Free.
 */
final class SiteLogoWidget extends \Elementor\Widget_Base {
	use ApCustomCssControl;
	use WidgetSettings;

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ap-site-logo';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'AP Site Logo', 'alternatepro-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-site-logo';
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
		return array( 'ap', 'apro', 'site logo', 'logo', 'identity', 'alternatepro' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_site_logo',
			array(
				'label' => __( 'Site Logo', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'site_logo',
			array(
				'label'       => __( 'Site Logo', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'media_types' => array( 'image' ),
				'default'     => $this->get_default_site_logo(),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'site_logo',
				'label'     => __( 'Image Resolution', 'alternatepro-elements' ),
				'default'   => 'full',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					'site_logo[url]!' => '',
				),
			)
		);

		$this->add_control(
			'caption_source',
			array(
				'label'     => __( 'Caption', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'       => __( 'None', 'alternatepro-elements' ),
					'attachment' => __( 'Attachment Caption', 'alternatepro-elements' ),
					'site_title' => __( 'Site Title', 'alternatepro-elements' ),
					'custom'     => __( 'Custom Caption', 'alternatepro-elements' ),
				),
				'condition' => array(
					'site_logo[url]!' => '',
				),
			)
		);

		$this->add_control(
			'custom_caption',
			array(
				'label'       => __( 'Custom Caption', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your logo caption', 'alternatepro-elements' ),
				'condition'   => array(
					'site_logo[url]!' => '',
					'caption_source'  => 'custom',
				),
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'     => __( 'Link', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'site_url',
				'options'   => array(
					'none'     => __( 'None', 'alternatepro-elements' ),
					'site_url' => __( 'Site URL', 'alternatepro-elements' ),
					'custom'   => __( 'Custom URL', 'alternatepro-elements' ),
				),
				'condition' => array(
					'site_logo[url]!' => '',
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
					'site_logo[url]!' => '',
					'link_to'         => 'custom',
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
		$this->register_ap_custom_css_controls(
			array(
				'placeholder' => "selector .ap-site-logo {\n\t/* CSS */\n}",
				'description' => __( 'Use selector to scope rules to this AP Site Logo widget.', 'alternatepro-elements' ),
			)
		);
	}

	/**
	 * Register widget style controls.
	 *
	 * @return void
	 */
	private function register_style_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_alignment',
			array(
				'label'     => __( 'Alignment', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .ap-site-logo' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .ap-site-logo-widget-placeholder' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'      => __( 'Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px', 'vw' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_max_width',
			array(
				'label'      => __( 'Max Width', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px', 'vw' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => __( 'Height', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ap-site-logo img' => 'height: {{SIZE}}{{UNIT}}; object-fit: contain;',
				),
			)
		);

		$this->start_controls_tabs( 'image_style_tabs' );

		$this->start_controls_tab(
			'image_style_normal_tab',
			array(
				'label' => __( 'Normal', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'image_opacity',
			array(
				'label'     => __( 'Opacity', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ap-site-logo img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_css_filters',
				'label'    => __( 'CSS Filters', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .ap-site-logo img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_style_hover_tab',
			array(
				'label' => __( 'Hover', 'alternatepro-elements' ),
			)
		);

		$this->add_control(
			'image_hover_opacity',
			array(
				'label'     => __( 'Opacity', 'alternatepro-elements' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ap-site-logo:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_hover_css_filters',
				'label'    => __( 'CSS Filters', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .ap-site-logo:hover img',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'label'     => __( 'Border Type', 'alternatepro-elements' ),
				'selector'  => '{{WRAPPER}} .ap-site-logo img',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'alternatepro-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ap-site-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'label'    => __( 'Box Shadow', 'alternatepro-elements' ),
				'selector' => '{{WRAPPER}} .ap-site-logo img',
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
		$settings   = $this->get_settings_for_display();
		$image_html = $this->get_logo_html( $settings );

		$this->render_ap_custom_css( $settings );

		if ( '' === $image_html ) {
			echo '<div class="ap-site-logo-widget-placeholder">' . esc_html__( 'AP Site Logo Widget', 'alternatepro-elements' ) . '</div>';

			return;
		}

		$caption         = $this->get_logo_caption( $settings );
		$link_attributes = $this->get_logo_link_attributes( $settings );
		$has_caption     = '' !== $caption;
		$tag             = $has_caption ? 'figure' : 'div';

		printf( '<%s class="ap-site-logo">', esc_html( $tag ) );

		if ( ! empty( $link_attributes ) ) {
			printf(
				'<a class="ap-site-logo__link" href="%1$s"%2$s%3$s>',
				esc_url( $link_attributes['href'] ),
				isset( $link_attributes['target'] ) ? ' target="' . esc_attr( $link_attributes['target'] ) . '"' : '',
				isset( $link_attributes['rel'] ) ? ' rel="' . esc_attr( $link_attributes['rel'] ) . '"' : ''
			);
		}

		echo wp_kses_post( $image_html );

		if ( ! empty( $link_attributes ) ) {
			echo '</a>';
		}

		if ( $has_caption ) {
			printf(
				'<figcaption class="ap-site-logo__caption">%s</figcaption>',
				wp_kses_post( $caption )
			);
		}

		printf( '</%s>', esc_html( $tag ) );
	}

	/**
	 * Render editor preview template.
	 *
	 * @return void
	 */
	protected function content_template() {}

	/**
	 * Get the default site logo control value.
	 *
	 * @return array<string,int|string>
	 */
	private function get_default_site_logo() {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$url     = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

		if ( $logo_id && $url ) {
			return array(
				'id'  => $logo_id,
				'url' => $url,
			);
		}

		if ( class_exists( '\Elementor\Utils' ) ) {
			return array(
				'id'  => '',
				'url' => \Elementor\Utils::get_placeholder_image_src(),
			);
		}

		return array(
			'id'  => '',
			'url' => '',
		);
	}

	/**
	 * Get the logo image HTML.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_logo_html( array $settings ) {
		if ( empty( $settings['site_logo'] ) || ! is_array( $settings['site_logo'] ) || empty( $settings['site_logo']['url'] ) ) {
			return '';
		}

		return \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'site_logo' );
	}

	/**
	 * Get the configured logo caption.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_logo_caption( array $settings ) {
		$source = isset( $settings['caption_source'] ) ? $settings['caption_source'] : '';
		$source = $this->sanitize_choice( $source, array( 'none', 'attachment', 'site_title', 'custom' ), 'none' );

		if ( 'attachment' === $source ) {
			$logo_id = $this->get_logo_attachment_id( $settings );

			return $logo_id ? (string) wp_get_attachment_caption( $logo_id ) : '';
		}

		if ( 'site_title' === $source ) {
			return get_bloginfo( 'name' );
		}

		if ( 'custom' === $source && isset( $settings['custom_caption'] ) ) {
			return (string) $settings['custom_caption'];
		}

		return '';
	}

	/**
	 * Get the logo attachment ID.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return int
	 */
	private function get_logo_attachment_id( array $settings ) {
		if ( empty( $settings['site_logo'] ) || ! is_array( $settings['site_logo'] ) || empty( $settings['site_logo']['id'] ) ) {
			return 0;
		}

		return absint( $settings['site_logo']['id'] );
	}

	/**
	 * Get logo link attributes.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<string,string>
	 */
	private function get_logo_link_attributes( array $settings ) {
		$link_to = isset( $settings['link_to'] ) ? $settings['link_to'] : '';
		$link_to = $this->sanitize_choice( $link_to, array( 'none', 'site_url', 'custom' ), 'site_url' );

		if ( 'none' === $link_to ) {
			return array();
		}

		if ( 'site_url' === $link_to ) {
			return array(
				'href' => home_url( '/' ),
			);
		}

		if ( empty( $settings['custom_link'] ) || ! is_array( $settings['custom_link'] ) || empty( $settings['custom_link']['url'] ) ) {
			return array();
		}

		$attributes = array(
			'href' => (string) $settings['custom_link']['url'],
		);

		if ( ! empty( $settings['custom_link']['is_external'] ) ) {
			$attributes['target'] = '_blank';
			$attributes['rel']    = 'noopener';
		}

		if ( ! empty( $settings['custom_link']['nofollow'] ) ) {
			$attributes['rel'] = isset( $attributes['rel'] ) ? $attributes['rel'] . ' nofollow' : 'nofollow';
		}

		return $attributes;
	}
}
