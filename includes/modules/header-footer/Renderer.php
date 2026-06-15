<?php
/**
 * Frontend renderer.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Renders resolved Elementor templates through AlternatePro theme hooks.
 */
final class Renderer {
	/**
	 * Condition resolver.
	 *
	 * @var Conditions
	 */
	private $conditions;

	/**
	 * Page override resolver.
	 *
	 * @var PageOverride
	 */
	private $page_override;

	/**
	 * Assets.
	 *
	 * @var Assets
	 */
	private $assets;

	/**
	 * Rendered flags.
	 *
	 * @var array<string,bool>
	 */
	private $rendered = array();

	/**
	 * Constructor.
	 *
	 * @param Conditions   $conditions Conditions.
	 * @param PageOverride $page_override Page overrides.
	 * @param Assets       $assets Assets.
	 */
	public function __construct( Conditions $conditions, PageOverride $page_override, Assets $assets ) {
		$this->conditions    = $conditions;
		$this->page_override = $page_override;
		$this->assets        = $assets;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'alternatepro_header', array( $this, 'render_header' ), 5 );
		add_action( 'alternatepro_footer', array( $this, 'render_footer' ), 5 );
	}

	/**
	 * Render header.
	 *
	 * @return void
	 */
	public function render_header() {
		$this->render( 'header' );
	}

	/**
	 * Render footer.
	 *
	 * @return void
	 */
	public function render_footer() {
		$this->render( 'footer' );
	}

	/**
	 * Render a template type.
	 *
	 * @param string $type Template type.
	 * @return void
	 */
	private function render( $type ) {
		$type = sanitize_key( $type );

		if ( isset( $this->rendered[ $type ] ) ) {
			return;
		}

		$this->rendered[ $type ] = true;

		$override = $this->page_override->get_override_template_id( $type );

		if ( 0 === $override ) {
			do_action( 'apro_header_footer_template_suppressed', $type, absint( get_queried_object_id() ) );
			return;
		}

		$template_id = null === $override ? $this->conditions->get_template_id( $type ) : $override;
		$template_id = absint( apply_filters( 'apro_header_footer_template_id', $template_id, $type ) );

		if ( ! $template_id || ! Module::is_active_template( $template_id, $type ) || ! $this->can_render_elementor() ) {
			return;
		}

		$this->assets->enqueue_frontend();

		do_action( 'apro_header_footer_before_render', $template_id, $type );

		printf(
			'<div class="apro-header-footer-template apro-header-footer-template--%1$s" data-apro-template-id="%2$d" data-apro-template-type="%1$s">',
			esc_attr( $type ),
			absint( $template_id )
		);

		// Elementor returns complete builder HTML. Escaping here would break valid Elementor markup.
		echo $this->get_elementor_content( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';

		do_action( 'apro_header_footer_after_render', $template_id, $type );
	}

	/**
	 * Check Elementor frontend availability.
	 *
	 * @return bool
	 */
	private function can_render_elementor() {
		return class_exists( '\Elementor\Plugin' )
			&& isset( \Elementor\Plugin::$instance )
			&& isset( \Elementor\Plugin::$instance->frontend )
			&& method_exists( \Elementor\Plugin::$instance->frontend, 'get_builder_content_for_display' );
	}

	/**
	 * Get Elementor builder content.
	 *
	 * @param int $template_id Template ID.
	 * @return string
	 */
	private function get_elementor_content( $template_id ) {
		$template_id = absint( $template_id );

		if ( ! $template_id || ! $this->can_render_elementor() ) {
			return '';
		}

		return (string) \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id, true );
	}
}
