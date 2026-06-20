<?php
/**
 * Theme compatibility layer.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Replaces theme header/footer templates when AlternatePro templates are active.
 */
final class ThemeCompatibility {
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
	 * Constructor.
	 *
	 * @param Conditions   $conditions Conditions.
	 * @param PageOverride $page_override Page overrides.
	 */
	public function __construct( Conditions $conditions, PageOverride $page_override ) {
		$this->conditions    = $conditions;
		$this->page_override = $page_override;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp', array( $this, 'register_theme_overrides' ) );
	}

	/**
	 * Register request-aware theme overrides.
	 *
	 * @return void
	 */
	public function register_theme_overrides() {
		if ( is_admin() ) {
			return;
		}

		if ( $this->should_replace_template( 'header' ) ) {
			add_action( 'get_header', array( $this, 'override_header' ), 0 );
		}

		if ( $this->should_replace_template( 'footer' ) ) {
			add_action( 'get_footer', array( $this, 'override_footer' ), 0 );
		}
	}

	/**
	 * Override the active theme header template.
	 *
	 * @param string|null $name Template name.
	 * @return void
	 */
	public function override_header( $name = null ) {
		require APRO_ELEMENTS_PATH . 'includes/modules/header-footer/templates/theme-header.php';
		$this->discard_original_template( 'header', $name );
	}

	/**
	 * Override the active theme footer template.
	 *
	 * @param string|null $name Template name.
	 * @return void
	 */
	public function override_footer( $name = null ) {
		require APRO_ELEMENTS_PATH . 'includes/modules/header-footer/templates/theme-footer.php';
		$this->discard_original_template( 'footer', $name );
	}

	/**
	 * Check whether a theme template should be replaced.
	 *
	 * @param string $type Template type.
	 * @return bool
	 */
	private function should_replace_template( $type ) {
		$type     = sanitize_key( $type );
		$override = $this->page_override->get_override_template_id( $type );

		if ( 0 === $override ) {
			return true;
		}

		$template_id = null === $override ? $this->conditions->get_template_id( $type ) : $override;

		return $template_id && Module::is_active_template( $template_id, $type );
	}

	/**
	 * Load the original theme template once and discard its output.
	 *
	 * This prevents WordPress from loading the same theme file after the custom
	 * wrapper has already been printed.
	 *
	 * @param string      $type Template type.
	 * @param string|null $name Template name.
	 * @return void
	 */
	private function discard_original_template( $type, $name = null ) {
		$templates = array();
		$name      = (string) $name;
		$type      = sanitize_key( $type );

		if ( '' !== $name ) {
			$templates[] = $type . '-' . $name . '.php';
		}

		$templates[] = $type . '.php';

		if ( 'header' === $type ) {
			remove_all_actions( 'wp_head' );
		} elseif ( 'footer' === $type ) {
			remove_all_actions( 'wp_footer' );
		}

		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}
}
