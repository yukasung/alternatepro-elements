<?php
/**
 * Header/Footer Builder module.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Coordinates Header/Footer Builder services.
 */
final class Module {
	public const POST_TYPE       = 'apro_template';
	public const TYPE_META       = '_apro_template_type';
	public const STATUS_META     = '_apro_template_status';
	public const PRIORITY_META   = '_apro_template_priority';
	public const CONDITIONS_META = '_apro_display_conditions';
	public const USER_ROLES_META = '_apro_user_roles';

	public const PAGE_HEADER_META = '_apro_page_header_template';
	public const PAGE_FOOTER_META = '_apro_page_footer_template';

	/**
	 * Initialize module services.
	 *
	 * @return void
	 */
	public function init() {
		$conditions    = new Conditions();
		$assets        = new Assets();
		$page_override = new PageOverride();

		( new PostType() )->init();
		( new MetaBox() )->init();
		$page_override->init();
		( new AdminColumns() )->init();
		( new TargetSearch() )->init();
		$assets->init();
		( new Renderer( $conditions, $page_override, $assets ) )->init();
	}

	/**
	 * Return supported template types.
	 *
	 * @return array<string,string>
	 */
	public static function template_types() {
		return array(
			'header' => __( 'Header', 'alternatepro-elements' ),
			'footer' => __( 'Footer', 'alternatepro-elements' ),
		);
	}

	/**
	 * Return supported statuses.
	 *
	 * @return array<string,string>
	 */
	public static function statuses() {
		return array(
			'active'   => __( 'Active', 'alternatepro-elements' ),
			'inactive' => __( 'Inactive', 'alternatepro-elements' ),
		);
	}

	/**
	 * Return display condition labels.
	 *
	 * @return array<string,string>
	 */
	public static function condition_types() {
		return RuleOptions::location_options();
	}

	/**
	 * Check whether a template can be rendered.
	 *
	 * @param int         $post_id Template post ID.
	 * @param string|null $type Optional required type.
	 * @return bool
	 */
	public static function is_active_template( $post_id, $type = null ) {
		$post_id = absint( $post_id );

		if ( ! $post_id || self::POST_TYPE !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
			return false;
		}

		if ( 'active' !== get_post_meta( $post_id, self::STATUS_META, true ) ) {
			return false;
		}

		if ( null !== $type && sanitize_key( $type ) !== get_post_meta( $post_id, self::TYPE_META, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the public label for a value.
	 *
	 * @param array<string,string> $labels Labels.
	 * @param string               $value Value.
	 * @return string
	 */
	public static function label_for( array $labels, $value ) {
		$value = sanitize_key( $value );

		return isset( $labels[ $value ] ) ? $labels[ $value ] : $value;
	}
}
