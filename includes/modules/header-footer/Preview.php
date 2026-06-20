<?php
/**
 * Header/Footer template preview integration.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Allows Elementor to preview template posts without exposing them publicly.
 */
final class Preview {
	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'template_redirect', array( $this, 'block_public_template_access' ) );
		add_filter( 'single_template', array( $this, 'load_canvas_template' ) );
	}

	/**
	 * Prevent direct frontend access to templates for non-editors.
	 *
	 * @return void
	 */
	public function block_public_template_access() {
		if ( ! is_singular( Module::POST_TYPE ) ) {
			return;
		}

		$post_id = get_queried_object_id();

		if ( $post_id && current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		wp_safe_redirect( home_url( '/' ), 302 );
		exit;
	}

	/**
	 * Use Elementor's canvas template for Header/Footer template previews.
	 *
	 * @param string $single_template Current single template path.
	 * @return string
	 */
	public function load_canvas_template( $single_template ) {
		$post = get_post();

		if ( ! $post || Module::POST_TYPE !== $post->post_type ) {
			return $single_template;
		}

		$canvas_template = $this->get_elementor_canvas_template();

		return $canvas_template ? $canvas_template : $single_template;
	}

	/**
	 * Resolve Elementor's canvas template path.
	 *
	 * @return string
	 */
	private function get_elementor_canvas_template() {
		if ( ! defined( 'ELEMENTOR_PATH' ) ) {
			return '';
		}

		$templates = array(
			ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php',
			ELEMENTOR_PATH . 'includes/page-templates/canvas.php',
		);

		foreach ( $templates as $template ) {
			if ( file_exists( $template ) ) {
				return $template;
			}
		}

		return '';
	}
}
