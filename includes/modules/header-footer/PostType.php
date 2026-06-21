<?php
/**
 * Header/Footer template post type.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

use AlternatePro\Elements\Admin\SettingsPage;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the template CPT and meta.
 */
final class PostType {
	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
		add_filter( 'elementor/utils/get_public_post_types', array( $this, 'add_to_elementor_post_types' ) );
	}

	/**
	 * Register CPT and post meta.
	 *
	 * @return void
	 */
	public static function register() {
		$labels = array(
			'name'               => __( 'AlternatePro Templates', 'alternatepro-elements' ),
			'singular_name'      => __( 'Header Footer Template', 'alternatepro-elements' ),
			'menu_name'          => __( 'Header Footer Templates', 'alternatepro-elements' ),
			'all_items'          => __( 'Header Footer Templates', 'alternatepro-elements' ),
			'add_new'            => __( 'Add New', 'alternatepro-elements' ),
			'add_new_item'       => __( 'Add New Template', 'alternatepro-elements' ),
			'edit_item'          => __( 'Edit Template', 'alternatepro-elements' ),
			'new_item'           => __( 'New Template', 'alternatepro-elements' ),
			'view_item'          => __( 'View Template', 'alternatepro-elements' ),
			'search_items'       => __( 'Search Templates', 'alternatepro-elements' ),
			'not_found'          => __( 'No templates found.', 'alternatepro-elements' ),
			'not_found_in_trash' => __( 'No templates found in Trash.', 'alternatepro-elements' ),
		);

		register_post_type(
			Module::POST_TYPE,
			array(
				'labels'              => $labels,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => SettingsPage::SLUG,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'query_var'           => Module::POST_TYPE,
				'rewrite'             => false,
				'has_archive'         => false,
				'capability_type'     => 'post',
				'supports'            => array( 'title', 'editor', 'revisions', 'elementor' ),
				'can_export'          => true,
			)
		);

		add_post_type_support( Module::POST_TYPE, 'elementor' );

		self::register_meta();
	}

	/**
	 * Add the CPT to Elementor's editable post types when Elementor asks.
	 *
	 * @param array<string,string> $post_types Post type labels.
	 * @return array<string,string>
	 */
	public function add_to_elementor_post_types( $post_types ) {
		$post_types[ Module::POST_TYPE ] = __( 'Header Footer Template', 'alternatepro-elements' );

		return $post_types;
	}

	/**
	 * Register meta fields for REST and sanitization.
	 *
	 * @return void
	 */
	private static function register_meta() {
		register_post_meta(
			Module::POST_TYPE,
			Module::TYPE_META,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 'header',
				'sanitize_callback' => 'sanitize_key',
				'auth_callback'     => array( __CLASS__, 'can_edit_meta' ),
			)
		);

		register_post_meta(
			Module::POST_TYPE,
			Module::STATUS_META,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 'inactive',
				'sanitize_callback' => 'sanitize_key',
				'auth_callback'     => array( __CLASS__, 'can_edit_meta' ),
			)
		);

		register_post_meta(
			Module::POST_TYPE,
			Module::PRIORITY_META,
			array(
				'type'              => 'integer',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 10,
				'sanitize_callback' => 'absint',
				'auth_callback'     => array( __CLASS__, 'can_edit_meta' ),
			)
		);

		register_post_meta(
			Module::POST_TYPE,
			Module::CONDITIONS_META,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
				'auth_callback'     => array( __CLASS__, 'can_edit_meta' ),
			)
		);

		foreach ( array( 'post', 'page' ) as $post_type ) {
			register_post_meta(
				$post_type,
				Module::PAGE_HEADER_META,
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'default'           => 'default',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => array( __CLASS__, 'can_edit_meta' ),
				)
			);

			register_post_meta(
				$post_type,
				Module::PAGE_FOOTER_META,
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'default'           => 'default',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => array( __CLASS__, 'can_edit_meta' ),
				)
			);
		}
	}

	/**
	 * Check meta editing permission.
	 *
	 * @param bool   $allowed Allowed.
	 * @param string $meta_key Meta key.
	 * @param int    $post_id Post ID.
	 * @return bool
	 */
	public static function can_edit_meta( $allowed, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', absint( $post_id ) );
	}
}
