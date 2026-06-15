<?php
/**
 * Runtime requirement checks.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * Handles compatibility checks and admin notices.
 */
final class Requirements {
	/**
	 * Minimum versions.
	 */
	public const MIN_WP        = '6.5';
	public const MIN_PHP       = '7.4';
	public const MIN_ELEMENTOR = '3.5.0';

	/**
	 * Singleton instance.
	 *
	 * @var Requirements|null
	 */
	private static $instance = null;

	/**
	 * Get singleton.
	 *
	 * @return Requirements
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Check requirements that are needed before modules can load safely.
	 *
	 * @return bool
	 */
	public function passes_core_requirements() {
		return $this->has_required_php() && $this->has_required_wordpress();
	}

	/**
	 * Check PHP version.
	 *
	 * @return bool
	 */
	public function has_required_php() {
		return version_compare( PHP_VERSION, self::MIN_PHP, '>=' );
	}

	/**
	 * Check WordPress version.
	 *
	 * @return bool
	 */
	public function has_required_wordpress() {
		global $wp_version;

		return isset( $wp_version ) && version_compare( $wp_version, self::MIN_WP, '>=' );
	}

	/**
	 * Whether Elementor appears to be loaded.
	 *
	 * @return bool
	 */
	public function is_elementor_loaded() {
		return did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' );
	}

	/**
	 * Whether the loaded Elementor version is compatible.
	 *
	 * @return bool
	 */
	public function has_required_elementor_version() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}

		return version_compare( ELEMENTOR_VERSION, self::MIN_ELEMENTOR, '>=' );
	}

	/**
	 * Print admin notices.
	 *
	 * @return void
	 */
	public function admin_notices() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( ! $this->has_required_php() ) {
			$this->notice(
				sprintf(
					/* translators: 1: required PHP version, 2: current PHP version. */
					__( 'AlternatePro Elements requires PHP %1$s or newer. Current version: %2$s.', 'alternatepro-elements' ),
					esc_html( self::MIN_PHP ),
					esc_html( PHP_VERSION )
				),
				'error'
			);
		}

		if ( ! $this->has_required_wordpress() ) {
			global $wp_version;

			$this->notice(
				sprintf(
					/* translators: 1: required WordPress version, 2: current WordPress version. */
					__( 'AlternatePro Elements requires WordPress %1$s or newer. Current version: %2$s.', 'alternatepro-elements' ),
					esc_html( self::MIN_WP ),
					esc_html( isset( $wp_version ) ? $wp_version : __( 'unknown', 'alternatepro-elements' ) )
				),
				'error'
			);
		}

		if ( ! $this->is_elementor_loaded() ) {
			$this->notice(
				__( 'AlternatePro Header Footer Builder works with Elementor Free. Please install and activate Elementor to edit and render templates.', 'alternatepro-elements' ),
				'warning'
			);

			return;
		}

		if ( ! $this->has_required_elementor_version() ) {
			$this->notice(
				sprintf(
					/* translators: %s: required Elementor version. */
					__( 'AlternatePro Elements recommends Elementor %s or newer for Header/Footer Builder compatibility.', 'alternatepro-elements' ),
					esc_html( self::MIN_ELEMENTOR )
				),
				'warning'
			);
		}
	}

	/**
	 * Render a notice.
	 *
	 * @param string $message Notice message.
	 * @param string $type Notice type.
	 * @return void
	 */
	private function notice( $message, $type = 'warning' ) {
		printf(
			'<div class="notice notice-%1$s"><p>%2$s</p></div>',
			esc_attr( $type ),
			wp_kses_post( $message )
		);
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
