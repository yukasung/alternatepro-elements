<?php
/**
 * Main plugin coordinator.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin singleton.
 */
final class Plugin {
	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Whether the plugin has initialized.
	 *
	 * @var bool
	 */
	private $initialized = false;

	/**
	 * Get the plugin instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialize plugin services.
	 *
	 * @return void
	 */
	private function init() {
		if ( $this->initialized ) {
			return;
		}

		$this->initialized = true;

		load_plugin_textdomain( 'alternatepro-elements', false, dirname( APRO_ELEMENTS_BASENAME ) . '/languages' );

		Requirements::instance()->init();

		if ( ! Requirements::instance()->passes_core_requirements() ) {
			return;
		}

		Modules::instance()->init();
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}

	/**
	 * Prevent cloning.
	 *
	 * @return void
	 */
	private function __clone() {}
}
