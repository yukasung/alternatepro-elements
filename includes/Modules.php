<?php
/**
 * Module loader.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

use AlternatePro\Elements\Modules\HeaderFooter\Module as HeaderFooterModule;

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
	 * @return void
	 */
	public function init() {
		$this->modules['header-footer'] = new HeaderFooterModule();

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
