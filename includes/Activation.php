<?php
/**
 * Activation and deactivation behavior.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

use AlternatePro\Elements\Modules\HeaderFooter\PostType;
use AlternatePro\Elements\Settings\SettingsRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Handles activation-only setup.
 */
final class Activation {
	/**
	 * Run activation tasks.
	 *
	 * @return void
	 */
	public static function activate() {
		if ( class_exists( PostType::class ) ) {
			PostType::register();
		}

		$settings = new SettingsRepository();
		$settings->ensure_defaults();

		update_option( Upgrades::SCHEMA_OPTION, APRO_ELEMENTS_SCHEMA_VERSION, false );

		flush_rewrite_rules();
	}

	/**
	 * Run deactivation tasks.
	 *
	 * User-created data and settings are intentionally preserved.
	 *
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
