<?php
/**
 * Shared AP Custom CSS control helpers.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Adds reusable AP Custom CSS controls to Elementor widgets.
 */
trait ApCustomCssControl {
	/**
	 * Get widget control stack with AP Custom CSS listed last.
	 *
	 * @param bool $with_common_controls Whether to include Elementor common controls.
	 * @return array<string,mixed>
	 */
	public function get_stack( $with_common_controls = true ) {
		$stack = parent::get_stack( $with_common_controls );

		return $this->move_ap_custom_css_section_to_stack_end( $stack );
	}

	/**
	 * Register the shared AP Custom CSS control section.
	 *
	 * @param array<string,mixed> $args Optional control arguments.
	 * @return void
	 */
	protected function register_ap_custom_css_controls( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'placeholder' => "selector {\n\t/* CSS */\n}",
				'description' => __( 'Use selector to scope rules to this widget.', 'alternatepro-elements' ),
			)
		);

		$this->start_controls_section(
			$this->get_ap_custom_css_section_id(),
			array(
				'label' => __( 'AP Custom CSS', 'alternatepro-elements' ),
				'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			)
		);

		$this->add_control(
			$this->get_ap_custom_css_control_id(),
			array(
				'label'       => __( 'AP Custom CSS', 'alternatepro-elements' ),
				'type'        => \Elementor\Controls_Manager::CODE,
				'language'    => 'css',
				'rows'        => 18,
				'placeholder' => (string) $args['placeholder'],
				'description' => (string) $args['description'],
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Move AP Custom CSS section controls to the bottom of the stack.
	 *
	 * @param array<string,mixed> $stack Elementor controls stack.
	 * @return array<string,mixed>
	 */
	protected function move_ap_custom_css_section_to_stack_end( array $stack ) {
		$section_id = $this->get_ap_custom_css_section_id();

		if ( empty( $stack['controls'] ) || ! is_array( $stack['controls'] ) || ! isset( $stack['controls'][ $section_id ] ) ) {
			return $stack;
		}

		$ap_custom_css_controls = array();

		foreach ( $stack['controls'] as $control_id => $control ) {
			$is_ap_custom_css_section = $section_id === $control_id;
			$is_ap_custom_css_control = is_array( $control ) && isset( $control['section'] ) && $section_id === $control['section'];

			if ( ! $is_ap_custom_css_section && ! $is_ap_custom_css_control ) {
				continue;
			}

			$ap_custom_css_controls[ $control_id ] = $control;
			unset( $stack['controls'][ $control_id ] );
		}

		$stack['controls'] = array_merge( $stack['controls'], $ap_custom_css_controls );

		return $stack;
	}

	/**
	 * Render AP Custom CSS for the current widget instance.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return void
	 */
	protected function render_ap_custom_css( array $settings ) {
		$control_id = $this->get_ap_custom_css_control_id();
		$custom_css = $this->prepare_ap_custom_css( $settings[ $control_id ] ?? '' );

		if ( '' === $custom_css ) {
			return;
		}

		?>
		<style id="<?php echo esc_attr( 'ap-custom-css-' . sanitize_html_class( $this->get_id() ) ); ?>">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized AP Custom CSS must remain raw style text.
			echo $custom_css;
			?>
		</style>
		<?php
	}

	/**
	 * Prepare user-authored widget CSS for safe inline output.
	 *
	 * @param mixed $css Raw CSS setting value.
	 * @return string
	 */
	protected function prepare_ap_custom_css( $css ) {
		if ( ! is_string( $css ) ) {
			return '';
		}

		$css = str_replace( "\0", '', $css );
		$css = wp_strip_all_tags( $css );
		$css = preg_replace( '/@import\b[^;]*;?/i', '', $css );
		$css = preg_replace( '/expression\s*\([^)]*\)/i', '', $css );
		$css = preg_replace( '/url\s*\(\s*[^)]*\)/i', '', $css );
		$css = preg_replace( '/-moz-binding\s*:[^;]*;?/i', '', $css );
		$css = preg_replace( '/behavior\s*:[^;]*;?/i', '', $css );
		$css = preg_replace( '/javascript\s*:/i', '', $css );
		$css = preg_replace( '/vbscript\s*:/i', '', $css );

		if ( ! is_string( $css ) ) {
			return '';
		}

		$css = trim( $css );

		if ( '' === $css ) {
			return '';
		}

		$widget_selector = '.elementor-element-' . sanitize_html_class( $this->get_id() );
		$css             = preg_replace( '/\bselector\b/', $widget_selector, $css );

		return is_string( $css ) ? $css : '';
	}

	/**
	 * Get the shared AP Custom CSS section ID.
	 *
	 * @return string
	 */
	protected function get_ap_custom_css_section_id() {
		return 'section_ap_custom_css';
	}

	/**
	 * Get the shared AP Custom CSS control ID.
	 *
	 * @return string
	 */
	protected function get_ap_custom_css_control_id() {
		return 'ap_custom_css';
	}
}
