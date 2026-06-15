<?php
/**
 * Capability helpers.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * Centralizes permission checks.
 */
final class Capabilities {
	/**
	 * Capability required for plugin settings.
	 */
	public const SETTINGS = 'manage_options';

	/**
	 * Check whether the current user can manage plugin settings.
	 *
	 * @return bool
	 */
	public static function can_manage_settings() {
		return current_user_can( self::SETTINGS );
	}

	/**
	 * Check whether the current user can manage a template post.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	public static function can_manage_template( $post_id ) {
		$post_id = absint( $post_id );

		if ( $post_id > 0 ) {
			return current_user_can( 'edit_post', $post_id );
		}

		return current_user_can( 'edit_posts' );
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
