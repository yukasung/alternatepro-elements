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
	private const SETTINGS_META_BOX = 'apro_template_settings';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . Module::POST_TYPE, array( $this, 'save' ) );
		add_filter( 'get_user_option_meta-box-order_' . Module::POST_TYPE, array( $this, 'force_meta_box_order' ) );
	}

	/**
	 * Add metaboxes.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			self::SETTINGS_META_BOX,
			__( 'AlternatePro Template Settings', 'alternatepro-elements' ),
			array( $this, 'render' ),
			Module::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Keep the template settings metabox in the main editor column.
	 *
	 * WordPress stores metabox positions per user, so a box moved to the side
	 * column can stay there across reloads. This metabox contains wide controls
	 * and condition controls, matching Header/Footer builder plugins that keep
	 * template settings in the main edit area instead of in the sidebar.
	 *
	 * @param mixed $order User metabox order.
	 * @return array<string,string>
	 */
	public function force_meta_box_order( $order ) {
		if ( ! is_array( $order ) ) {
			$order = array();
		}

		foreach ( array( 'normal', 'side', 'advanced' ) as $context ) {
			$ids = isset( $order[ $context ] ) ? explode( ',', (string) $order[ $context ] ) : array();
			$ids = array_filter( array_map( 'trim', $ids ) );
			$ids = array_diff( $ids, array( self::SETTINGS_META_BOX ) );

			$order[ $context ] = implode( ',', $ids );
		}

		$normal_ids = array_filter( array_map( 'trim', explode( ',', $order['normal'] ) ) );
		array_unshift( $normal_ids, self::SETTINGS_META_BOX );

		$order['normal'] = implode( ',', array_unique( $normal_ids ) );

		return $order;
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
		$status     = metadata_exists( 'post', $post->ID, Module::STATUS_META )
			? get_post_meta( $post->ID, Module::STATUS_META, true )
			: '';
		$priority   = get_post_meta( $post->ID, Module::PRIORITY_META, true );
		$conditions = Conditions::decode_conditions( get_post_meta( $post->ID, Module::CONDITIONS_META, true ) );

		if ( '' === $type ) {
			$type = 'header';
		}

		if ( '' === $status ) {
			$status = 'active';
		}

		if ( '' === $priority ) {
			$priority = 10;
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

		$display_rules = RuleOptions::conditions_for_operator( $conditions, 'include' );
		$exclude_rules = RuleOptions::conditions_for_operator( $conditions, 'exclude' );

		if ( empty( $display_rules ) ) {
			$display_rules = RuleOptions::default_display_rules();
		}
		?>
		<div class="apro-hfb-options">
			<div class="apro-hfb-option-row">
				<div class="apro-hfb-option-label">
					<label for="apro-template-type"><?php esc_html_e( 'Type of Template', 'alternatepro-elements' ); ?></label>
				</div>
				<div class="apro-hfb-option-control">
					<select id="apro-template-type" name="apro_template_type">
						<?php foreach ( Module::template_types() as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $type, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="apro-hfb-option-row apro-hfb-option-row--conditions">
				<div class="apro-hfb-option-label">
					<label><?php esc_html_e( 'Display On', 'alternatepro-elements' ); ?></label>
					<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Add locations for where this template should appear.', 'alternatepro-elements' ); ?>"></span>
				</div>
				<div class="apro-hfb-option-control">
					<?php $this->render_location_rule_builder( 'apro_display_rules', $display_rules, 'display', __( 'Add Display Rule', 'alternatepro-elements' ), true ); ?>
				</div>
			</div>

			<div class="apro-hfb-option-row apro-hfb-option-row--exclusions <?php echo empty( $exclude_rules ) ? 'apro-is-hidden' : ''; ?>">
				<div class="apro-hfb-option-label">
					<label><?php esc_html_e( 'Do Not Display On', 'alternatepro-elements' ); ?></label>
					<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Add locations for where this template should not appear.', 'alternatepro-elements' ); ?>"></span>
				</div>
				<div class="apro-hfb-option-control">
					<?php $this->render_location_rule_builder( 'apro_exclusion_rules', $exclude_rules, 'exclude', __( 'Add Exclusion Rule', 'alternatepro-elements' ), false ); ?>
				</div>
			</div>

			<div class="apro-hfb-option-row">
				<div class="apro-hfb-option-label">
					<label for="apro-template-status"><?php esc_html_e( 'Status', 'alternatepro-elements' ); ?></label>
				</div>
				<div class="apro-hfb-option-control">
					<select id="apro-template-status" name="apro_template_status">
						<?php foreach ( Module::statuses() as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="apro-hfb-option-row">
				<div class="apro-hfb-option-label">
					<label for="apro-template-priority"><?php esc_html_e( 'Priority', 'alternatepro-elements' ); ?></label>
				</div>
				<div class="apro-hfb-option-control">
					<input id="apro-template-priority" type="number" min="0" step="1" name="apro_template_priority" value="<?php echo esc_attr( absint( $priority ) ); ?>">
				</div>
			</div>

			</div>
			<?php
	}

	/**
	 * Render a location rule builder.
	 *
	 * @param string                         $input_name Input name.
	 * @param array<int,array<string,mixed>> $rules Saved rules.
	 * @param string                         $rule_kind Rule kind.
	 * @param string                         $button_label Button label.
	 * @param bool                           $show_exclusion_button Whether to show exclusion reveal button.
	 * @return void
	 */
	private function render_location_rule_builder( $input_name, array $rules, $rule_kind, $button_label, $show_exclusion_button ) {
		if ( empty( $rules ) ) {
			$rules = array(
				array(
					'type'  => '',
					'value' => '',
				),
			);
		}
		?>
		<div class="apro-hfb-rule-builder apro-hfb-rule-builder--locations apro-hfb-rule-builder--<?php echo esc_attr( $rule_kind ); ?>" data-rule-kind="<?php echo esc_attr( $rule_kind ); ?>" data-name-prefix="<?php echo esc_attr( $input_name ); ?>">
			<div class="apro-hfb-rule-list">
				<?php foreach ( $rules as $index => $rule ) : ?>
					<?php $this->render_location_rule( $input_name, $rule, absint( $index ) ); ?>
				<?php endforeach; ?>
			</div>

			<div class="apro-hfb-rule-actions">
				<button type="button" class="button apro-hfb-add-rule"><?php echo esc_html( $button_label ); ?></button>
				<?php if ( $show_exclusion_button ) : ?>
					<button type="button" class="button apro-hfb-show-exclusions"><?php esc_html_e( 'Add Exclusion Rule', 'alternatepro-elements' ); ?></button>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render one location rule row.
	 *
	 * @param string              $input_name Input name.
	 * @param array<string,mixed> $rule Saved rule.
	 * @param int                 $index Rule index.
	 * @return void
	 */
	private function render_location_rule( $input_name, array $rule, $index ) {
		$type       = isset( $rule['type'] ) ? sanitize_key( $rule['type'] ) : '';
		$value      = isset( $rule['value'] ) ? (string) $rule['value'] : '';
		$has_value  = RuleOptions::rule_has_value( $type );
		$has_search = RuleOptions::rule_has_search( $type );
		$targets    = $has_search ? TargetSearch::selected_targets( $value, $type ) : array();
		$value_name = sprintf( '%1$s[value][%2$d]', $input_name, absint( $index ) );
		$rule_name  = sprintf( '%1$s[rule][%2$d]', $input_name, absint( $index ) );
		?>
		<div class="apro-hfb-rule" data-rule-index="<?php echo esc_attr( absint( $index ) ); ?>">
			<button type="button" class="button-link apro-hfb-remove-rule" aria-label="<?php esc_attr_e( 'Remove rule', 'alternatepro-elements' ); ?>">
				<span class="dashicons dashicons-dismiss"></span>
			</button>

			<div class="apro-hfb-rule-select-wrap">
				<select name="<?php echo esc_attr( $rule_name ); ?>" class="apro-hfb-rule-select">
					<option value=""><?php esc_html_e( 'Select', 'alternatepro-elements' ); ?></option>
					<?php foreach ( RuleOptions::location_groups() as $group ) : ?>
						<optgroup label="<?php echo esc_attr( $group['label'] ); ?>">
							<?php foreach ( $group['value'] as $value_key => $label ) : ?>
								<option value="<?php echo esc_attr( $value_key ); ?>" <?php selected( $type, $value_key ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</optgroup>
						<?php endforeach; ?>
				</select>
			</div>

			<div class="apro-hfb-rule-value-wrap <?php echo $has_value ? '' : 'apro-is-hidden'; ?>">
				<div class="apro-hfb-target-picker <?php echo $has_search ? '' : 'apro-is-hidden'; ?>">
					<div class="apro-hfb-target-selection">
						<div class="apro-hfb-target-chips">
							<?php foreach ( $targets as $target ) : ?>
								<span class="apro-hfb-target-chip" data-token="<?php echo esc_attr( $target['token'] ); ?>">
									<button type="button" class="apro-hfb-target-chip-remove" aria-label="<?php esc_attr_e( 'Remove selected target', 'alternatepro-elements' ); ?>" data-token="<?php echo esc_attr( $target['token'] ); ?>">&times;</button>
									<span class="apro-hfb-target-chip-label"><?php echo esc_html( $target['label'] ); ?></span>
								</span>
							<?php endforeach; ?>
						</div>
						<input type="search" class="apro-hfb-target-search" autocomplete="off" aria-label="<?php esc_attr_e( 'Search pages, posts, and categories', 'alternatepro-elements' ); ?>" placeholder="<?php esc_attr_e( 'Search pages / posts / categories', 'alternatepro-elements' ); ?>">
					</div>
					<div class="apro-hfb-target-results" hidden></div>
				</div>
				<textarea name="<?php echo esc_attr( $value_name ); ?>" rows="2" class="apro-hfb-rule-value <?php echo $has_search ? 'apro-hfb-rule-value--hidden' : ''; ?>" placeholder="<?php esc_attr_e( 'Enter values separated by commas', 'alternatepro-elements' ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
				<p class="description apro-hfb-rule-value-description <?php echo $has_search ? 'apro-is-hidden' : ''; ?>"><?php esc_html_e( 'Enter values separated by commas.', 'alternatepro-elements' ); ?></p>
			</div>
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

		if ( ! isset( Module::template_types()[ $type ] ) ) {
			$type = 'header';
		}

		if ( ! isset( Module::statuses()[ $status ] ) ) {
			$status = 'inactive';
		}

		$display_rules = isset( $_POST['apro_display_rules'] ) ? RuleOptions::sanitize_location_rules( wp_unslash( $_POST['apro_display_rules'] ), 'include' ) : array();
		$exclude_rules = isset( $_POST['apro_exclusion_rules'] ) ? RuleOptions::sanitize_location_rules( wp_unslash( $_POST['apro_exclusion_rules'] ), 'exclude' ) : array();
		$conditions    = array_merge( $display_rules, $exclude_rules );

		if ( empty( $conditions ) && isset( $_POST['apro_conditions'] ) ) {
			$conditions = Conditions::sanitize_conditions( wp_unslash( $_POST['apro_conditions'] ) );
		}

		update_post_meta( $post_id, Module::TYPE_META, $type );
		update_post_meta( $post_id, Module::STATUS_META, $status );
		update_post_meta( $post_id, Module::PRIORITY_META, $priority );
		update_post_meta( $post_id, Module::CONDITIONS_META, wp_json_encode( $conditions ) );
		delete_post_meta( $post_id, '_apro_user_roles' );
		delete_post_meta( $post_id, '_apro_language' );
	}
}
