<?php
/**
 * Page-level header/footer overrides.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Adds layout overrides to pages and posts.
 */
final class PageOverride {
	/**
	 * Language resolver.
	 *
	 * @var LanguageResolver
	 */
	private $language_resolver;

	/**
	 * Constructor.
	 *
	 * @param LanguageResolver $language_resolver Language resolver.
	 */
	public function __construct( LanguageResolver $language_resolver ) {
		$this->language_resolver = $language_resolver;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_filter( 'apro_header_footer_suppress_theme_fallback', array( $this, 'filter_suppress_theme_fallback' ), 10, 2 );
	}

	/**
	 * Add override metabox to posts and pages.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		foreach ( array( 'page', 'post' ) as $post_type ) {
			add_meta_box(
				'apro_page_layout',
				__( 'AlternatePro Layout', 'alternatepro-elements' ),
				array( $this, 'render' ),
				$post_type,
				'side',
				'default'
			);
		}
	}

	/**
	 * Render the metabox.
	 *
	 * @param \WP_Post $post Post object.
	 * @return void
	 */
	public function render( $post ) {
		wp_nonce_field( 'apro_page_layout', 'apro_page_layout_nonce' );

		$header_value = get_post_meta( $post->ID, Module::PAGE_HEADER_META, true );
		$footer_value = get_post_meta( $post->ID, Module::PAGE_FOOTER_META, true );

		if ( '' === $header_value ) {
			$header_value = 'default';
		}

		if ( '' === $footer_value ) {
			$footer_value = 'default';
		}

		$this->render_select( 'header', Module::PAGE_HEADER_META, $header_value );
		$this->render_select( 'footer', Module::PAGE_FOOTER_META, $footer_value );
	}

	/**
	 * Save override data.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function save( $post_id ) {
		$post_id   = absint( $post_id );
		$post_type = get_post_type( $post_id );

		if ( ! in_array( $post_type, array( 'page', 'post' ), true ) ) {
			return;
		}

		if ( ! isset( $_POST['apro_page_layout_nonce'] ) || ! check_admin_referer( 'apro_page_layout', 'apro_page_layout_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$header = isset( $_POST[ Module::PAGE_HEADER_META ] ) ? $this->sanitize_override_value( wp_unslash( $_POST[ Module::PAGE_HEADER_META ] ), 'header' ) : 'default';
		$footer = isset( $_POST[ Module::PAGE_FOOTER_META ] ) ? $this->sanitize_override_value( wp_unslash( $_POST[ Module::PAGE_FOOTER_META ] ), 'footer' ) : 'default';

		update_post_meta( $post_id, Module::PAGE_HEADER_META, $header );
		update_post_meta( $post_id, Module::PAGE_FOOTER_META, $footer );
	}

	/**
	 * Get override template for the current request.
	 *
	 * Returns null for default resolver, 0 for no custom template, or a template ID.
	 *
	 * @param string $type Template type.
	 * @return int|null
	 */
	public function get_override_template_id( $type ) {
		if ( ! is_singular( array( 'page', 'post' ) ) ) {
			return null;
		}

		$post_id = absint( get_queried_object_id() );

		if ( ! $post_id ) {
			return null;
		}

		$key   = 'footer' === $type ? Module::PAGE_FOOTER_META : Module::PAGE_HEADER_META;
		$value = get_post_meta( $post_id, $key, true );

		if ( '' === $value || 'default' === $value ) {
			return null;
		}

		if ( 'none' === $value ) {
			return 0;
		}

		$template_id = absint( $value );

		if ( Module::is_active_template( $template_id, $type ) ) {
			return $template_id;
		}

		return null;
	}

	/**
	 * Let compatible themes suppress fallback output when page override is set to none.
	 *
	 * @param bool   $suppress Current suppress state.
	 * @param string $type Template type.
	 * @return bool
	 */
	public function filter_suppress_theme_fallback( $suppress, $type ) {
		return $suppress || 0 === $this->get_override_template_id( sanitize_key( $type ) );
	}

	/**
	 * Render a template override select.
	 *
	 * @param string $type Type.
	 * @param string $name Field name.
	 * @param string $selected Selected value.
	 * @return void
	 */
	private function render_select( $type, $name, $selected ) {
		$label     = 'footer' === $type ? __( 'Footer Template', 'alternatepro-elements' ) : __( 'Header Template', 'alternatepro-elements' );
		$templates = $this->get_active_templates( $type );
		?>
		<p>
			<label for="<?php echo esc_attr( $name ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label>
			<select id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" class="widefat">
				<option value="default" <?php selected( $selected, 'default' ); ?>><?php esc_html_e( 'Default', 'alternatepro-elements' ); ?></option>
				<option value="none" <?php selected( $selected, 'none' ); ?>><?php esc_html_e( 'None', 'alternatepro-elements' ); ?></option>
				<?php foreach ( $templates as $template_id => $template_label ) : ?>
					<option value="<?php echo esc_attr( $template_id ); ?>" <?php selected( (string) $selected, (string) $template_id ); ?>><?php echo esc_html( $template_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	/**
	 * Get active templates for select fields.
	 *
	 * @param string $type Template type.
	 * @return array<int,string>
	 */
	private function get_active_templates( $type ) {
		$query = new WP_Query(
			array(
				'post_type'      => Module::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => Module::TYPE_META,
						'value' => sanitize_key( $type ),
					),
					array(
						'key'   => Module::STATUS_META,
						'value' => 'active',
					),
				),
			)
		);

		$templates = array();

		foreach ( array_map( 'absint', $query->posts ) as $template_id ) {
			$language                 = get_post_meta( $template_id, Module::LANGUAGE_META, true );
			$templates[ $template_id ] = sprintf(
				'%1$s (%2$s)',
				get_the_title( $template_id ),
				$this->language_resolver->get_label( $language ? $language : 'all' )
			);
		}

		return $templates;
	}

	/**
	 * Sanitize one override value.
	 *
	 * @param mixed  $value Raw value.
	 * @param string $type Template type.
	 * @return string
	 */
	private function sanitize_override_value( $value, $type ) {
		$value = sanitize_text_field( $value );

		if ( in_array( $value, array( 'default', 'none' ), true ) ) {
			return $value;
		}

		$template_id = absint( $value );

		if ( Module::is_active_template( $template_id, $type ) ) {
			return (string) $template_id;
		}

		return 'default';
	}
}
