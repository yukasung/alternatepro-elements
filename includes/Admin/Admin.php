<?php
/**
 * Admin coordinator.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Admin;

use AlternatePro\Elements\Settings\SettingsRepository;
use AlternatePro\Elements\Settings\SettingsSanitizer;

defined( 'ABSPATH' ) || exit;

/**
 * Coordinates admin services.
 */
final class Admin {
	/**
	 * Settings page.
	 *
	 * @var SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor.
	 *
	 * @param SettingsRepository $settings Settings repository.
	 * @param SettingsSanitizer  $sanitizer Settings sanitizer.
	 */
	public function __construct( SettingsRepository $settings, SettingsSanitizer $sanitizer ) {
		$diagnostics         = new Diagnostics( $settings );
		$this->settings_page = new SettingsPage( $settings, $sanitizer, $diagnostics );
	}

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function init() {
		$this->settings_page->init();
	}
}
