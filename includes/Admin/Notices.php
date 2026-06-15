<?php
/**
 * Admin notice rendering.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Renders safe admin notices.
 */
final class Notices {
	/**
	 * Render a notice.
	 *
	 * @param string $message Notice message.
	 * @param string $type Notice type.
	 * @return void
	 */
	public static function render( $message, $type = 'warning' ) {
		printf(
			'<div class="notice notice-%1$s"><p>%2$s</p></div>',
			esc_attr( sanitize_html_class( $type ) ),
			wp_kses_post( $message )
		);
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
