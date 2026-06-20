<?php
/**
 * Header/Footer module assets.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Registers and conditionally enqueues assets.
 */
final class Assets {
	public const ADMIN_STYLE  = 'apro-header-footer-admin';
	public const ADMIN_SCRIPT = 'apro-header-footer-admin';
	public const FRONT_STYLE  = 'apro-header-footer-frontend';
	public const FRONT_SCRIPT = 'apro-header-footer-frontend';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
	}

	/**
	 * Register frontend assets.
	 *
	 * @return void
	 */
	public function register_frontend() {
		wp_register_style(
			self::FRONT_STYLE,
			APRO_ELEMENTS_URL . 'assets/css/header-footer-frontend.css',
			array(),
			$this->asset_version( 'assets/css/header-footer-frontend.css' )
		);

		wp_register_script(
			self::FRONT_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/js/header-footer-frontend.js',
			array(),
			$this->asset_version( 'assets/js/header-footer-frontend.js' ),
			true
		);
	}

	/**
	 * Enqueue frontend assets when a template is rendered.
	 *
	 * @return void
	 */
	public function enqueue_frontend() {
		$this->register_frontend();

		wp_enqueue_style( self::FRONT_STYLE );
		wp_enqueue_script( self::FRONT_SCRIPT );
	}

	/**
	 * Enqueue admin assets only on relevant screens.
	 *
	 * @return void
	 */
	public function enqueue_admin() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || ! $this->is_module_admin_screen( $screen ) ) {
			return;
		}

		wp_enqueue_style(
			self::ADMIN_STYLE,
			APRO_ELEMENTS_URL . 'assets/css/header-footer-admin.css',
			array(),
			$this->asset_version( 'assets/css/header-footer-admin.css' )
		);

		wp_enqueue_script(
			self::ADMIN_SCRIPT,
			APRO_ELEMENTS_URL . 'assets/js/header-footer-admin.js',
			array(),
			$this->asset_version( 'assets/js/header-footer-admin.js' ),
			true
		);

		wp_localize_script(
			self::ADMIN_SCRIPT,
			'aproHeaderFooterRules',
			array(
				'valueRuleTypes' => RuleOptions::value_rule_types(),
			)
		);
	}

	/**
	 * Check whether current admin screen belongs to this module.
	 *
	 * @param \WP_Screen $screen Current screen.
	 * @return bool
	 */
	private function is_module_admin_screen( $screen ) {
		if ( Module::POST_TYPE === $screen->post_type ) {
			return true;
		}

		return in_array( $screen->post_type, array( 'page', 'post' ), true ) && in_array( $screen->base, array( 'post' ), true );
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
