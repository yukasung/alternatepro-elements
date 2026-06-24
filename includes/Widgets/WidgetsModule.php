<?php
/**
 * Elementor widgets module.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Widgets;

use AlternatePro\Elements\Settings\SettingsRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Registers AlternatePro Elementor widgets.
 */
final class WidgetsModule {
	/**
	 * Elementor widget category slug.
	 */
	public const CATEGORY = 'alternatepro-elements';

	/**
	 * Nav Menu frontend style handle.
	 */
	public const NAV_MENU_STYLE = 'apro-nav-menu';

	/**
	 * Nav Menu frontend script handle.
	 */
	public const NAV_MENU_SCRIPT = 'apro-nav-menu-frontend';

	/**
	 * Owl Carousel frontend style handle.
	 */
	public const OWL_CAROUSEL_STYLE = 'apro-owl-carousel';

	/**
	 * Owl Carousel frontend theme style handle.
	 */
	public const OWL_CAROUSEL_THEME_STYLE = 'apro-owl-carousel-theme';

	/**
	 * Owl Carousel frontend script handle.
	 */
	public const OWL_CAROUSEL_SCRIPT = 'apro-owl-carousel';

	/**
	 * Image Carousel frontend style handle.
	 */
	public const IMAGE_CAROUSEL_STYLE = 'apro-image-carousel';

	/**
	 * Image Carousel frontend script handle.
	 */
	public const IMAGE_CAROUSEL_SCRIPT = 'apro-image-carousel-frontend';

	/**
	 * AP Media Carousel frontend style handle.
	 */
	public const MEDIA_CAROUSEL_STYLE = 'apro-media-carousel';

	/**
	 * AP Media Carousel frontend script handle.
	 */
	public const MEDIA_CAROUSEL_SCRIPT = 'apro-media-carousel-frontend';

	/**
	 * AP Slides frontend style handle.
	 */
	public const SLIDES_STYLE = 'apro-slides';

	/**
	 * AP Slides frontend script handle.
	 */
	public const SLIDES_SCRIPT = 'apro-slides-frontend';

	/**
	 * AP Custom CSS editor script handle.
	 */
	public const CUSTOM_CSS_EDITOR_SCRIPT = 'apro-custom-css-editor';

	/**
	 * Settings repository.
	 *
	 * @var SettingsRepository
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param SettingsRepository|null $settings Settings repository.
	 */
	public function __construct( ?SettingsRepository $settings = null ) {
		$this->settings = $settings ? $settings : new SettingsRepository();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	/**
	 * Register widget assets.
	 *
	 * @return void
	 */
	public function register_assets() {
		$this->register_nav_menu_assets();
		$this->register_image_carousel_assets();
		$this->register_media_carousel_assets();
		$this->register_slides_assets();
	}

	/**
	 * Enqueue widget editor assets.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets() {
		if ( ! $this->has_enabled_widgets() ) {
			return;
		}

		wp_enqueue_script(
			self::CUSTOM_CSS_EDITOR_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/js/custom-css-editor.js',
			array(),
			$this->asset_version( 'assets/js/custom-css-editor.js' ),
			true
		);
	}

	/**
	 * Check whether any AlternatePro Elementor widget is enabled.
	 *
	 * @return bool
	 */
	private function has_enabled_widgets() {
		$defaults = $this->settings->defaults();
		$widgets  = isset( $defaults['widgets'] ) && is_array( $defaults['widgets'] ) ? array_keys( $defaults['widgets'] ) : array();

		foreach ( $widgets as $widget ) {
			if ( $this->settings->is_widget_enabled( $widget ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Register Nav Menu widget assets.
	 *
	 * @return void
	 */
	private function register_nav_menu_assets() {
		wp_register_style(
			self::NAV_MENU_STYLE,
			APRO_ELEMENTS_URL . 'assets/css/nav-menu.css',
			array(),
			$this->asset_version( 'assets/css/nav-menu.css' )
		);

		wp_register_script(
			self::NAV_MENU_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/js/nav-menu.js',
			array(),
			$this->asset_version( 'assets/js/nav-menu.js' ),
			true
		);
	}

	/**
	 * Register Image Carousel widget assets.
	 *
	 * @return void
	 */
	private function register_image_carousel_assets() {
		wp_register_style(
			self::OWL_CAROUSEL_STYLE,
			APRO_ELEMENTS_URL . 'assets/vendor/owl-carousel/owl.carousel.min.css',
			array(),
			$this->asset_version( 'assets/vendor/owl-carousel/owl.carousel.min.css' )
		);

		wp_register_style(
			self::OWL_CAROUSEL_THEME_STYLE,
			APRO_ELEMENTS_URL . 'assets/vendor/owl-carousel/owl.theme.default.min.css',
			array( self::OWL_CAROUSEL_STYLE ),
			$this->asset_version( 'assets/vendor/owl-carousel/owl.theme.default.min.css' )
		);

		wp_register_style(
			self::IMAGE_CAROUSEL_STYLE,
			APRO_ELEMENTS_URL . 'assets/css/image-carousel.css',
			array( self::OWL_CAROUSEL_THEME_STYLE ),
			$this->asset_version( 'assets/css/image-carousel.css' )
		);

		wp_register_script(
			self::OWL_CAROUSEL_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/vendor/owl-carousel/owl.carousel.min.js',
			array( 'jquery' ),
			$this->asset_version( 'assets/vendor/owl-carousel/owl.carousel.min.js' ),
			true
		);

		wp_register_script(
			self::IMAGE_CAROUSEL_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/js/image-carousel.js',
			array( 'jquery', self::OWL_CAROUSEL_SCRIPT ),
			$this->asset_version( 'assets/js/image-carousel.js' ),
			true
		);
	}

	/**
	 * Register AP Media Carousel widget assets.
	 *
	 * @return void
	 */
	private function register_media_carousel_assets() {
		wp_register_style(
			self::MEDIA_CAROUSEL_STYLE,
			APRO_ELEMENTS_URL . 'assets/css/media-carousel.css',
			array(),
			$this->asset_version( 'assets/css/media-carousel.css' )
		);

		wp_register_script(
			self::MEDIA_CAROUSEL_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/js/media-carousel.js',
			array(),
			$this->asset_version( 'assets/js/media-carousel.js' ),
			true
		);
	}

	/**
	 * Register AP Slides widget assets.
	 *
	 * @return void
	 */
	private function register_slides_assets() {
		wp_register_style(
			self::SLIDES_STYLE,
			APRO_ELEMENTS_URL . 'assets/css/slides.css',
			array( self::OWL_CAROUSEL_THEME_STYLE ),
			$this->asset_version( 'assets/css/slides.css' )
		);

		wp_register_script(
			self::SLIDES_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/js/slides.js',
			array( 'jquery', self::OWL_CAROUSEL_SCRIPT ),
			$this->asset_version( 'assets/js/slides.js' ),
			true
		);
	}

	/**
	 * Register the Elementor category.
	 *
	 * @param object $elements_manager Elementor elements manager.
	 * @return void
	 */
	public function register_category( $elements_manager ) {
		if ( ! method_exists( $elements_manager, 'add_category' ) ) {
			return;
		}

		$elements_manager->add_category(
			self::CATEGORY,
			array(
				'title' => __( 'AlternatePro Elements', 'alternatepro-elements' ),
				'icon'  => 'eicon-navigation-horizontal',
			)
		);
	}

	/**
	 * Register widgets with Elementor.
	 *
	 * @param object $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {
		if ( ! class_exists( '\Elementor\Widget_Base' ) || ! method_exists( $widgets_manager, 'register' ) ) {
			return;
		}

		$this->register_widget_if_enabled( $widgets_manager, 'site_logo', SiteLogoWidget::class );
		$widgets_manager->register( new NavMenuWidget() );
		$this->register_widget_if_enabled( $widgets_manager, 'image_carousel', ImageCarouselWidget::class );
		$this->register_widget_if_enabled( $widgets_manager, 'media_carousel', MediaCarouselWidget::class );
		$this->register_widget_if_enabled( $widgets_manager, 'slides', SlidesWidget::class );
	}

	/**
	 * Register a widget when its admin toggle is enabled.
	 *
	 * @param object $widgets_manager Elementor widgets manager.
	 * @param string $widget_key Widget settings key.
	 * @param string $widget_class Widget class name.
	 * @return void
	 */
	private function register_widget_if_enabled( $widgets_manager, $widget_key, $widget_class ) {
		if ( ! $this->settings->is_widget_enabled( $widget_key ) || ! class_exists( $widget_class ) ) {
			return;
		}

		$widgets_manager->register( new $widget_class() );
	}

	/**
	 * Get asset version.
	 *
	 * @param string $relative Relative asset path.
	 * @return string
	 */
	private function asset_version( $relative ) {
		$file = APRO_ELEMENTS_PATH . ltrim( $relative, '/' );

		if ( file_exists( $file ) ) {
			return (string) filemtime( $file );
		}

		return APRO_ELEMENTS_VERSION;
	}
}
