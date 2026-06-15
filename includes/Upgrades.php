<?php
/**
 * Versioned data upgrades.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

use AlternatePro\Elements\Settings\SettingsRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Runs safe, idempotent upgrades.
 */
final class Upgrades {
	/**
	 * Schema version option.
	 */
	public const SCHEMA_OPTION = 'apro_elements_schema_version';

	/**
	 * Maybe run upgrades.
	 *
	 * @return void
	 */
	public function maybe_upgrade() {
		$stored = (string) get_option( self::SCHEMA_OPTION, '0' );

		if ( version_compare( $stored, APRO_ELEMENTS_SCHEMA_VERSION, '>=' ) ) {
			return;
		}

		$settings = new SettingsRepository();
		$settings->ensure_defaults();

		update_option( self::SCHEMA_OPTION, APRO_ELEMENTS_SCHEMA_VERSION, false );
	}
}
