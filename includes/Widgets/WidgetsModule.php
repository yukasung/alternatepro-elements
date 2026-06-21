<?php
/**
 * Elementor widgets module.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Widgets;

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
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	/**
	 * Register widget assets.
	 *
	 * @return void
	 */
	public function register_assets() {
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

		$widgets_manager->register( new NavMenuWidget() );
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
