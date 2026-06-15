<?php
/**
 * Admin list table columns.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Adds useful template columns.
 */
final class AdminColumns {
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
		add_filter( 'manage_' . Module::POST_TYPE . '_posts_columns', array( $this, 'columns' ) );
		add_action( 'manage_' . Module::POST_TYPE . '_posts_custom_column', array( $this, 'render_column' ), 10, 2 );
		add_filter( 'manage_edit-' . Module::POST_TYPE . '_sortable_columns', array( $this, 'sortable_columns' ) );
		add_action( 'pre_get_posts', array( $this, 'sort_columns' ) );
	}

	/**
	 * Add columns.
	 *
	 * @param array<string,string> $columns Columns.
	 * @return array<string,string>
	 */
	public function columns( $columns ) {
		$new = array();

		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;

			if ( 'title' === $key ) {
				$new['apro_template_type'] = __( 'Template Type', 'alternatepro-elements' );
				$new['apro_status']        = __( 'Status', 'alternatepro-elements' );
				$new['apro_language']      = __( 'Language', 'alternatepro-elements' );
				$new['apro_priority']      = __( 'Priority', 'alternatepro-elements' );
				$new['apro_conditions']    = __( 'Conditions', 'alternatepro-elements' );
				$new['apro_hook_info']     = __( 'Hook Info', 'alternatepro-elements' );
			}
		}

		return $new;
	}

	/**
	 * Render a column.
	 *
	 * @param string $column Column key.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public function render_column( $column, $post_id ) {
		$post_id = absint( $post_id );

		switch ( $column ) {
			case 'apro_template_type':
				echo esc_html( Module::label_for( Module::template_types(), get_post_meta( $post_id, Module::TYPE_META, true ) ) );
				break;

			case 'apro_status':
				echo esc_html( Module::label_for( Module::statuses(), get_post_meta( $post_id, Module::STATUS_META, true ) ) );
				break;

			case 'apro_language':
				$language = get_post_meta( $post_id, Module::LANGUAGE_META, true );
				$language = ! empty( $language ) ? $language : 'all';
				echo esc_html( $this->language_resolver->get_label( $language ) );
				break;

			case 'apro_priority':
				echo esc_html( absint( get_post_meta( $post_id, Module::PRIORITY_META, true ) ) );
				break;

			case 'apro_conditions':
				echo esc_html( Conditions::summarize_conditions( Conditions::decode_conditions( get_post_meta( $post_id, Module::CONDITIONS_META, true ) ) ) );
				break;

			case 'apro_hook_info':
				$type = get_post_meta( $post_id, Module::TYPE_META, true );
				echo 'footer' === $type ? esc_html( 'alternatepro_footer' ) : esc_html( 'alternatepro_header' );
				break;
		}
	}

	/**
	 * Sortable columns.
	 *
	 * @param array<string,string> $columns Columns.
	 * @return array<string,string>
	 */
	public function sortable_columns( $columns ) {
		$columns['apro_template_type'] = 'apro_template_type';
		$columns['apro_status']        = 'apro_status';
		$columns['apro_language']      = 'apro_language';
		$columns['apro_priority']      = 'apro_priority';

		return $columns;
	}

	/**
	 * Apply sorting.
	 *
	 * @param \WP_Query $query Query.
	 * @return void
	 */
	public function sort_columns( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || Module::POST_TYPE !== $query->get( 'post_type' ) ) {
			return;
		}

		$orderby = $query->get( 'orderby' );
		$map     = array(
			'apro_template_type' => Module::TYPE_META,
			'apro_status'        => Module::STATUS_META,
			'apro_language'      => Module::LANGUAGE_META,
			'apro_priority'      => Module::PRIORITY_META,
		);

		if ( ! isset( $map[ $orderby ] ) ) {
			return;
		}

		$query->set( 'meta_key', $map[ $orderby ] );
		$query->set( 'orderby', 'apro_priority' === $orderby ? 'meta_value_num' : 'meta_value' );
	}
}
