<?php
/**
 * Header/Footer template editor screen integration.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Keeps Header/Footer templates in Elementor mode on the edit screen.
 */
final class EditorScreen {
	private const ELEMENTOR_EDIT_MODE_META = '_elementor_edit_mode';
	private const ELEMENTOR_BUILDER_MODE   = 'builder';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'load-post.php', array( $this, 'prepare_current_template' ) );
		add_action( 'load-post-new.php', array( $this, 'prepare_current_template' ) );
		add_action( 'save_post_' . Module::POST_TYPE, array( $this, 'force_elementor_mode_on_save' ), 20, 3 );
		add_filter( 'admin_body_class', array( $this, 'force_active_body_class' ), 99 );
	}

	/**
	 * Mark the current template as Elementor-built before the edit screen renders.
	 *
	 * @return void
	 */
	public function prepare_current_template() {
		$post_id = $this->get_current_template_id();

		if ( $post_id ) {
			$this->set_elementor_mode( $post_id );
		}
	}

	/**
	 * Keep templates in Elementor mode after save.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @param bool     $update Whether this is an existing post being updated.
	 * @return void
	 */
	public function force_elementor_mode_on_save( $post_id, $post, $update ) {
		unset( $post, $update );

		$this->set_elementor_mode( $post_id );
	}

	/**
	 * Force Elementor's active edit-screen class for this post type.
	 *
	 * @param string $classes Space-separated admin body classes.
	 * @return string
	 */
	public function force_active_body_class( $classes ) {
		if ( ! $this->is_template_edit_screen() ) {
			return $classes;
		}

		$classes = preg_replace( '/\belementor-editor-inactive\b/', '', $classes );

		if ( false === strpos( $classes, 'elementor-editor-active' ) ) {
			$classes .= ' elementor-editor-active';
		}

		return trim( (string) $classes );
	}

	/**
	 * Get the current Header/Footer template ID from the edit request.
	 *
	 * @return int
	 */
	private function get_current_template_id() {
		$post_id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( $post_id && Module::POST_TYPE === get_post_type( $post_id ) && current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		return 0;
	}

	/**
	 * Check whether the current admin screen is the template edit screen.
	 *
	 * @return bool
	 */
	private function is_template_edit_screen() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		return $screen && Module::POST_TYPE === $screen->post_type && in_array( $screen->base, array( 'post' ), true );
	}

	/**
	 * Set Elementor builder mode for a template post.
	 *
	 * @param int $post_id Template post ID.
	 * @return void
	 */
	private function set_elementor_mode( $post_id ) {
		$post_id = absint( $post_id );

		if ( ! $post_id || wp_is_post_revision( $post_id ) || Module::POST_TYPE !== get_post_type( $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, self::ELEMENTOR_EDIT_MODE_META, self::ELEMENTOR_BUILDER_MODE );
	}
}
