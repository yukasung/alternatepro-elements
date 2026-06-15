<?php
/**
 * Main plugin coordinator.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

use AlternatePro\Elements\Admin\Admin;
use AlternatePro\Elements\Settings\SettingsRepository;
use AlternatePro\Elements\Settings\SettingsSanitizer;

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
	 * Service container.
	 *
	 * @var Container|null
	 */
	private $container = null;

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
		$this->container   = new Container();

		load_plugin_textdomain( 'alternatepro-elements', false, dirname( APRO_ELEMENTS_BASENAME ) . '/languages' );

		$sanitizer = new SettingsSanitizer();
		$settings  = new SettingsRepository( $sanitizer );

		$this->container->set( 'settings_sanitizer', $sanitizer );
		$this->container->set( 'settings', $settings );
		$this->container->set( 'requirements', Requirements::instance() );
		$this->container->set( 'upgrades', new Upgrades() );
		$this->container->set( 'admin', new Admin( $settings, $sanitizer ) );
		$this->container->set( 'modules', Modules::instance() );

		Requirements::instance()->init();

		if ( ! Requirements::instance()->passes_core_requirements() ) {
			return;
		}

		$settings->ensure_defaults();

		$upgrades = $this->container->get( 'upgrades' );

		if ( $upgrades instanceof Upgrades ) {
			$upgrades->maybe_upgrade();
		}

		$admin = $this->container->get( 'admin' );

		if ( $admin instanceof Admin ) {
			$admin->init();
		}

		$modules = $this->container->get( 'modules' );

		if ( $modules instanceof Modules ) {
			$modules->init( $settings );
		}
	}

	/**
	 * Get the service container.
	 *
	 * @return Container|null
	 */
	public function container() {
		return $this->container;
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
