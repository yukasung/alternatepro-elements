<?php
/**
 * Module loader.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

use AlternatePro\Elements\Modules\HeaderFooter\Module as HeaderFooterModule;
use AlternatePro\Elements\Settings\SettingsRepository;
use AlternatePro\Elements\Widgets\WidgetsModule;

defined( 'ABSPATH' ) || exit;

/**
 * Loads feature modules.
 */
final class Modules {
	/**
	 * Singleton instance.
	 *
	 * @var Modules|null
	 */
	private static $instance = null;

	/**
	 * Loaded modules.
	 *
	 * @var array<string,object>
	 */
	private $modules = array();

	/**
	 * Settings repository.
	 *
	 * @var SettingsRepository|null
	 */
	private $settings = null;

	/**
	 * Get singleton.
	 *
	 * @return Modules
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize modules.
	 *
	 * @param SettingsRepository|null $settings Settings repository.
	 * @return void
	 */
	public function init( ?SettingsRepository $settings = null ) {
		$this->settings = $settings ? $settings : new SettingsRepository();
		$this->modules  = array();

		$this->modules['widgets'] = new WidgetsModule();

		if ( $this->settings->is_module_enabled( 'header_footer' ) ) {
			$this->modules['header-footer'] = new HeaderFooterModule();
		}

		foreach ( $this->modules as $module ) {
			if ( method_exists( $module, 'init' ) ) {
				$module->init();
			}
		}
	}

	/**
	 * Return loaded modules.
	 *
	 * @return array<string,object>
	 */
	public function all() {
		return $this->modules;
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
