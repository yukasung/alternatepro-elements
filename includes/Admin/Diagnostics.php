<?php
/**
 * Read-only diagnostics.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Admin;

use AlternatePro\Elements\Modules;
use AlternatePro\Elements\Modules\HeaderFooter\Module as HeaderFooterModule;
use AlternatePro\Elements\Settings\SettingsRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Provides safe diagnostics data.
 */
final class Diagnostics {
	/**
	 * Settings repository.
	 *
	 * @var SettingsRepository
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param SettingsRepository $settings Settings repository.
	 */
	public function __construct( SettingsRepository $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Get diagnostics rows.
	 *
	 * @return array<string,string>
	 */
	public function rows() {
		global $wp_version;

		$theme     = wp_get_theme();
		$settings  = $this->settings->get();
		$modules   = isset( $settings['modules'] ) ? $settings['modules'] : array();
		$widgets   = isset( $settings['widgets'] ) ? $settings['widgets'] : array();
		$elementor = defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : __( 'Not loaded', 'alternatepro-elements' );

		return array(
			__( 'Plugin Version', 'alternatepro-elements' ) => APRO_ELEMENTS_VERSION,
			__( 'Schema Version', 'alternatepro-elements' ) => (string) get_option( 'apro_elements_schema_version', '0' ),
			__( 'WordPress Version', 'alternatepro-elements' ) => isset( $wp_version ) ? $wp_version : __( 'Unknown', 'alternatepro-elements' ),
			__( 'PHP Version', 'alternatepro-elements' )  => PHP_VERSION,
			__( 'Elementor Version', 'alternatepro-elements' ) => $elementor,
			__( 'Active Theme', 'alternatepro-elements' ) => $theme->get( 'Name' ),
			__( 'Enabled Modules', 'alternatepro-elements' ) => $this->enabled_keys( $modules ),
			__( 'Enabled Widgets', 'alternatepro-elements' ) => $this->enabled_keys( $widgets ),
			__( 'Loaded Modules', 'alternatepro-elements' ) => implode( ', ', array_keys( Modules::instance()->all() ) ),
			__( 'Active Header Templates', 'alternatepro-elements' ) => (string) $this->count_active_templates( 'header' ),
			__( 'Active Footer Templates', 'alternatepro-elements' ) => (string) $this->count_active_templates( 'footer' ),
		);
	}

	/**
	 * Get enabled keys as a display string.
	 *
	 * @param array<string,bool> $values Values.
	 * @return string
	 */
	private function enabled_keys( array $values ) {
		$enabled = array();

		foreach ( $values as $key => $enabled_value ) {
			if ( $enabled_value ) {
				$enabled[] = sanitize_key( $key );
			}
		}

		return empty( $enabled ) ? __( 'None', 'alternatepro-elements' ) : implode( ', ', $enabled );
	}

	/**
	 * Count active templates by type.
	 *
	 * @param string $type Template type.
	 * @return int
	 */
	private function count_active_templates( $type ) {
		if ( ! post_type_exists( HeaderFooterModule::POST_TYPE ) ) {
			return 0;
		}

		$query = new \WP_Query(
			array(
				'post_type'              => HeaderFooterModule::POST_TYPE,
				'post_status'            => 'publish',
				'fields'                 => 'ids',
				'posts_per_page'         => 1,
				'no_found_rows'          => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_query'             => array(
					'relation' => 'AND',
					array(
						'key'   => HeaderFooterModule::TYPE_META,
						'value' => sanitize_key( $type ),
					),
					array(
						'key'   => HeaderFooterModule::STATUS_META,
						'value' => 'active',
					),
				),
			)
		);

		return absint( $query->found_posts );
	}
}
