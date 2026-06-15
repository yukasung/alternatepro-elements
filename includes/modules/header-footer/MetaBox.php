<?php
/**
 * Template metaboxes.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Registers and saves template settings.
 */
final class MetaBox {
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
		add_action( 'save_post_' . Module::POST_TYPE, array( $this, 'save' ) );
	}

	/**
	 * Add metaboxes.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'apro_template_settings',
			__( 'AlternatePro Template Settings', 'alternatepro-elements' ),
			array( $this, 'render' ),
			Module::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Render the metabox.
	 *
	 * @param \WP_Post $post Post object.
	 * @return void
	 */
	public function render( $post ) {
		wp_nonce_field( 'apro_template_meta', 'apro_template_meta_nonce' );

		$type       = get_post_meta( $post->ID, Module::TYPE_META, true );
		$status     = get_post_meta( $post->ID, Module::STATUS_META, true );
		$priority   = get_post_meta( $post->ID, Module::PRIORITY_META, true );
		$language   = get_post_meta( $post->ID, Module::LANGUAGE_META, true );
		$conditions = Conditions::decode_conditions( get_post_meta( $post->ID, Module::CONDITIONS_META, true ) );

		if ( '' === $type ) {
			$type = 'header';
		}

		if ( '' === $status ) {
			$status = 'inactive';
		}

		if ( '' === $priority ) {
			$priority = 10;
		}

		if ( '' === $language ) {
			$language = 'all';
		}

		if ( empty( $conditions ) && 'auto-draft' === $post->post_status ) {
			$conditions = array(
				array(
					'type'     => 'entire_site',
					'operator' => 'include',
					'value'    => '',
				),
			);
		}

		$enabled_conditions = array();
		$condition_values   = array();

		foreach ( $conditions as $condition ) {
			$condition_type = isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : '';

			if ( $condition_type ) {
				$enabled_conditions[ $condition_type ] = true;
				$condition_values[ $condition_type ]   = isset( $condition['value'] ) ? (string) $condition['value'] : '';
			}
		}
		?>
		<div class="apro-hfb-grid">
			<p>
				<label for="apro-template-type"><strong><?php esc_html_e( 'Template Type', 'alternatepro-elements' ); ?></strong></label>
				<select id="apro-template-type" name="apro_template_type">
					<?php foreach ( Module::template_types() as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $type, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="apro-template-status"><strong><?php esc_html_e( 'Status', 'alternatepro-elements' ); ?></strong></label>
				<select id="apro-template-status" name="apro_template_status">
					<?php foreach ( Module::statuses() as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="apro-template-priority"><strong><?php esc_html_e( 'Priority', 'alternatepro-elements' ); ?></strong></label>
				<input id="apro-template-priority" type="number" min="0" step="1" name="apro_template_priority" value="<?php echo esc_attr( absint( $priority ) ); ?>">
			</p>

			<p>
				<label for="apro-template-language"><strong><?php esc_html_e( 'Language', 'alternatepro-elements' ); ?></strong></label>
				<select id="apro-template-language" name="apro_template_language">
					<?php foreach ( $this->language_resolver->get_admin_options() as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $language, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>

		<hr>

		<h3><?php esc_html_e( 'Display Conditions', 'alternatepro-elements' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Exclude rules override include rules. Specific rules are preferred over broad rules, then higher priority wins.', 'alternatepro-elements' ); ?></p>

		<div class="apro-hfb-conditions">
			<?php foreach ( Module::condition_types() as $value => $label ) : ?>
				<?php
				$is_enabled = ! empty( $enabled_conditions[ $value ] );
				$has_value  = in_array( $value, array( 'specific_pages', 'specific_posts', 'url_path_starts', 'url_path_contains', 'exclude_pages', 'exclude_posts' ), true );
				?>
				<div class="apro-hfb-condition">
					<label>
						<input type="checkbox" name="apro_conditions[<?php echo esc_attr( $value ); ?>][enabled]" value="1" <?php checked( $is_enabled ); ?>>
						<span><?php echo esc_html( $label ); ?></span>
					</label>

					<?php if ( $has_value ) : ?>
						<textarea name="apro_conditions[<?php echo esc_attr( $value ); ?>][value]" rows="2" placeholder="<?php esc_attr_e( 'IDs or URL paths, separated by commas or new lines', 'alternatepro-elements' ); ?>"><?php echo esc_textarea( isset( $condition_values[ $value ] ) ? $condition_values[ $value ] : '' ); ?></textarea>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Save metabox data.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function save( $post_id ) {
		$post_id = absint( $post_id );

		if ( ! isset( $_POST['apro_template_meta_nonce'] ) || ! check_admin_referer( 'apro_template_meta', 'apro_template_meta_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$type     = isset( $_POST['apro_template_type'] ) ? sanitize_key( wp_unslash( $_POST['apro_template_type'] ) ) : 'header';
		$status   = isset( $_POST['apro_template_status'] ) ? sanitize_key( wp_unslash( $_POST['apro_template_status'] ) ) : 'inactive';
		$priority = isset( $_POST['apro_template_priority'] ) ? absint( wp_unslash( $_POST['apro_template_priority'] ) ) : 10;
		$language = isset( $_POST['apro_template_language'] ) ? sanitize_key( wp_unslash( $_POST['apro_template_language'] ) ) : 'all';

		if ( ! isset( Module::template_types()[ $type ] ) ) {
			$type = 'header';
		}

		if ( ! isset( Module::statuses()[ $status ] ) ) {
			$status = 'inactive';
		}

		$conditions = isset( $_POST['apro_conditions'] ) ? Conditions::sanitize_conditions( wp_unslash( $_POST['apro_conditions'] ) ) : array();

		update_post_meta( $post_id, Module::TYPE_META, $type );
		update_post_meta( $post_id, Module::STATUS_META, $status );
		update_post_meta( $post_id, Module::PRIORITY_META, $priority );
		update_post_meta( $post_id, Module::LANGUAGE_META, $language ? $language : 'all' );
		update_post_meta( $post_id, Module::CONDITIONS_META, wp_json_encode( $conditions ) );
	}
}
