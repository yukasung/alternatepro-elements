<?php
/**
 * Versioned data upgrades.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

use AlternatePro\Elements\Modules\HeaderFooter\Module as HeaderFooterModule;
use AlternatePro\Elements\Modules\HeaderFooter\RuleOptions;
use AlternatePro\Elements\Settings\SettingsRepository;
use WP_Query;

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

		if ( version_compare( $stored, '2', '<' ) ) {
			self::delete_header_footer_language_meta();
		}

		if ( version_compare( $stored, '3', '<' ) ) {
			self::backfill_empty_header_footer_conditions();
		}

		update_option( self::SCHEMA_OPTION, APRO_ELEMENTS_SCHEMA_VERSION, false );
	}

	/**
	 * Remove legacy Header/Footer template language meta.
	 *
	 * @return void
	 */
	public static function delete_header_footer_language_meta() {
		delete_post_meta_by_key( '_apro_language' );
	}

	/**
	 * Backfill active templates that predate explicit display conditions.
	 *
	 * @return int Number of templates updated.
	 */
	public static function backfill_empty_header_footer_conditions() {
		$query = new WP_Query(
			array(
				'post_type'      => HeaderFooterModule::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'meta_query'     => array(
					array(
						'key'   => HeaderFooterModule::STATUS_META,
						'value' => 'active',
					),
				),
			)
		);

		$updated = 0;

		foreach ( array_map( 'absint', $query->posts ) as $template_id ) {
			$conditions = get_post_meta( $template_id, HeaderFooterModule::CONDITIONS_META, true );

			if ( ! self::is_empty_header_footer_conditions( $conditions ) ) {
				continue;
			}

			update_post_meta(
				$template_id,
				HeaderFooterModule::CONDITIONS_META,
				wp_json_encode( RuleOptions::default_display_rules() )
			);

			++$updated;
		}

		return $updated;
	}

	/**
	 * Check whether a stored conditions value is empty.
	 *
	 * @param mixed $conditions Stored conditions meta.
	 * @return bool
	 */
	private static function is_empty_header_footer_conditions( $conditions ) {
		if ( is_array( $conditions ) ) {
			return empty( $conditions );
		}

		$conditions = trim( (string) $conditions );

		if ( '' === $conditions ) {
			return true;
		}

		$decoded = json_decode( $conditions, true );

		return is_array( $decoded ) && empty( $decoded );
	}
}
