<?php
/**
 * Header/Footer target search.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

use AlternatePro\Elements\Helpers;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Provides secure admin AJAX search for condition targets.
 */
final class TargetSearch {
	public const AJAX_ACTION  = 'apro_hfb_search_targets';
	public const NONCE_ACTION = 'apro_hfb_target_search';

	private const RESULTS_PER_GROUP = 20;

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_ajax_' . self::AJAX_ACTION, array( $this, 'search' ) );
	}

	/**
	 * Search target content for condition rules.
	 *
	 * @return void
	 */
	public function search() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You are not allowed to search targets.', 'alternatepro-elements' ),
				),
				403
			);
		}

		$rule = isset( $_POST['rule'] ) ? sanitize_key( wp_unslash( $_POST['rule'] ) ) : '';
		$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$term = substr( $term, 0, 100 );

		if ( ! RuleOptions::rule_has_search( $rule ) || strlen( $term ) < 2 ) {
			wp_send_json_success(
				array(
					'results' => array(),
				)
			);
		}

		wp_send_json_success(
			array(
				'results' => $this->get_results( $rule, $term ),
			)
		);
	}

	/**
	 * Return selected target tokens with display labels.
	 *
	 * @param mixed  $value Stored target value.
	 * @param string $rule Rule type.
	 * @return array<int,array<string,string>>
	 */
	public static function selected_targets( $value, $rule ) {
		$targets = array();
		$seen    = array();

		foreach ( Helpers::parse_string_list( $value ) as $token ) {
			$token = sanitize_text_field( $token );

			if ( '' === $token || isset( $seen[ $token ] ) ) {
				continue;
			}

			$seen[ $token ] = true;
			$targets[]      = array(
				'token' => $token,
				'label' => self::label_for_token( $token, $rule ),
			);
		}

		return $targets;
	}

	/**
	 * Get search results for a rule type.
	 *
	 * @param string $rule Rule type.
	 * @param string $term Search term.
	 * @return array<int,array<string,string>>
	 */
	private function get_results( $rule, $term ) {
		$results = array();

		if ( 'specifics' === $rule ) {
			$results = array_merge( $results, $this->search_public_post_types( $term ) );
			$results = array_merge( $results, $this->search_public_taxonomies( $term ) );

			return $results;
		}

		if ( in_array( $rule, array( 'specifics', 'specific_pages' ), true ) ) {
			$results = array_merge( $results, $this->search_posts( $term, 'page', $rule ) );
		}

		if ( in_array( $rule, array( 'specifics', 'specific_posts' ), true ) ) {
			$results = array_merge( $results, $this->search_posts( $term, 'post', $rule ) );
		}

		if ( in_array( $rule, array( 'specifics', 'specific_category' ), true ) ) {
			$results = array_merge( $results, $this->search_categories( $term, $rule ) );
		}

		return array_slice( $results, 0, 12 );
	}

	/**
	 * Search all public post types for the UAE-style specific target rule.
	 *
	 * @param string $term Search term.
	 * @return array<int,array<string,string>>
	 */
	private function search_public_post_types( $term ) {
		$results    = array();
		$post_types = array();

		foreach ( array( 'post', 'page' ) as $built_in_post_type ) {
			$post_type_object = get_post_type_object( $built_in_post_type );

			if ( $post_type_object ) {
				$post_types[ $built_in_post_type ] = $post_type_object;
			}
		}

		$custom_post_types = get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'objects'
		);

		unset( $custom_post_types[ Module::POST_TYPE ] );
		unset( $custom_post_types['attachment'] );

		$post_types = array_merge( $post_types, $custom_post_types );

		foreach ( $post_types as $post_type ) {
			$results = array_merge( $results, $this->search_posts( $term, $post_type->name, 'specifics', self::RESULTS_PER_GROUP ) );
		}

		return $results;
	}

	/**
	 * Search all public taxonomies for the UAE-style specific target rule.
	 *
	 * @param string $term Search term.
	 * @return array<int,array<string,string>>
	 */
	private function search_public_taxonomies( $term ) {
		$results    = array();
		$taxonomies = get_taxonomies(
			array(
				'public' => true,
			),
			'objects'
		);

		unset( $taxonomies['post_format'] );

		foreach ( $taxonomies as $taxonomy ) {
			$results = array_merge( $results, $this->search_terms( $term, $taxonomy->name, 'specifics', self::RESULTS_PER_GROUP ) );
		}

		return $results;
	}

	/**
	 * Search posts or pages.
	 *
	 * @param string $term Search term.
	 * @param string $post_type Post type.
	 * @param string $rule Rule type.
	 * @param int    $limit Maximum results to return.
	 * @return array<int,array<string,string>>
	 */
	private function search_posts( $term, $post_type, $rule, $limit = 6 ) {
		$query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'post_status'    => $this->post_statuses_for_search( $post_type ),
				'posts_per_page' => absint( $limit ),
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'perm'           => 'readable',
				's'              => $term,
			)
		);

		$results = array();

		foreach ( array_map( 'absint', $query->posts ) as $post_id ) {
			$token = 'specifics' === $rule ? 'post-' . $post_id : (string) $post_id;

			$results[] = array(
				'token'  => $token,
				'label'  => self::post_label( $post_id ),
				'group'  => self::post_group( $post_type ),
				'detail' => self::post_detail( $post_id, $post_type ),
			);
		}

		return $results;
	}

	/**
	 * Search categories.
	 *
	 * @param string $term Search term.
	 * @param string $rule Rule type.
	 * @return array<int,array<string,string>>
	 */
	private function search_categories( $term, $rule ) {
		return $this->search_terms( $term, 'category', $rule );
	}

	/**
	 * Search taxonomy terms.
	 *
	 * @param string $term Search term.
	 * @param string $taxonomy Taxonomy.
	 * @param string $rule Rule type.
	 * @param int    $limit Maximum results to return.
	 * @return array<int,array<string,string>>
	 */
	private function search_terms( $term, $taxonomy, $rule, $limit = 6 ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'number'     => absint( $limit ),
				'search'     => $term,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$results = array();

		foreach ( $terms as $term_item ) {
			$term_id   = absint( $term_item->term_id );
			$token     = 'specifics' === $rule ? 'tax-' . $term_id : (string) $term_id;
			$results[] = array(
				'token'  => $token,
				'label'  => $term_item->name,
				'group'  => self::term_group( $term_item ),
				'detail' => self::term_detail( $term_item ),
			);
		}

		return $results;
	}

	/**
	 * Return post statuses that may be searched safely.
	 *
	 * @param string $post_type Post type.
	 * @return string[]
	 */
	private function post_statuses_for_search( $post_type ) {
		$statuses = array( 'publish' );

		if ( 'page' === $post_type && current_user_can( 'edit_pages' ) ) {
			$statuses[] = 'draft';
		}

		if ( 'post' === $post_type && current_user_can( 'edit_posts' ) ) {
			$statuses[] = 'draft';
		}

		if ( 'page' === $post_type && current_user_can( 'read_private_pages' ) ) {
			$statuses[] = 'private';
		}

		if ( 'post' === $post_type && current_user_can( 'read_private_posts' ) ) {
			$statuses[] = 'private';
		}

		return $statuses;
	}

	/**
	 * Return a readable label for a saved token.
	 *
	 * @param string $token Saved target token.
	 * @param string $rule Rule type.
	 * @return string
	 */
	private static function label_for_token( $token, $rule ) {
		$token = sanitize_text_field( $token );
		$rule  = sanitize_key( $rule );

		if ( preg_match( '/^(post|page)-(\d+)$/', $token, $matches ) ) {
			return self::post_label( absint( $matches[2] ), $token );
		}

		if ( preg_match( '/^(tax|term|cat|category)-(\d+)/', $token, $matches ) ) {
			return self::term_label( absint( $matches[2] ), $token );
		}

		if ( absint( $token ) > 0 ) {
			if ( 'specific_category' === $rule ) {
				return self::term_label( absint( $token ), $token );
			}

			return self::post_label( absint( $token ), $token );
		}

		return $token;
	}

	/**
	 * Return a post title or fallback token.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $fallback Fallback label.
	 * @return string
	 */
	private static function post_label( $post_id, $fallback = '' ) {
		$post_id = absint( $post_id );
		$post    = get_post( $post_id );

		if ( ! $post ) {
			return '' !== $fallback ? $fallback : (string) $post_id;
		}

		$title = get_the_title( $post_id );

		return '' !== $title ? $title : sprintf(
			/* translators: %d: Post ID. */
			__( 'Untitled #%d', 'alternatepro-elements' ),
			$post_id
		);
	}

	/**
	 * Return a post type detail label.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $post_type Fallback post type.
	 * @return string
	 */
	private static function post_detail( $post_id, $post_type ) {
		$post_id   = absint( $post_id );
		$post_type = sanitize_key( $post_type );
		$post      = get_post( $post_id );

		if ( $post ) {
			$post_type = $post->post_type;
		}

		$type_name = 'page' === $post_type ? __( 'Page', 'alternatepro-elements' ) : __( 'Post', 'alternatepro-elements' );

		return sprintf(
			/* translators: 1: target type, 2: target ID. */
			__( '%1$s #%2$d', 'alternatepro-elements' ),
			$type_name,
			$post_id
		);
	}

	/**
	 * Return a post type group label for search results.
	 *
	 * @param string $post_type Post type.
	 * @return string
	 */
	private static function post_group( $post_type ) {
		$post_type_object = get_post_type_object( sanitize_key( $post_type ) );

		if ( $post_type_object && isset( $post_type_object->labels->name ) && '' !== $post_type_object->labels->name ) {
			return $post_type_object->labels->name;
		}

		return __( 'Posts', 'alternatepro-elements' );
	}

	/**
	 * Return a term name or fallback token.
	 *
	 * @param int    $term_id Term ID.
	 * @param string $fallback Fallback label.
	 * @return string
	 */
	private static function term_label( $term_id, $fallback = '' ) {
		$term = get_term( absint( $term_id ) );

		if ( ! $term || is_wp_error( $term ) ) {
			return '' !== $fallback ? $fallback : (string) absint( $term_id );
		}

		return $term->name;
	}

	/**
	 * Return a term detail label.
	 *
	 * @param \WP_Term $term Term object.
	 * @return string
	 */
	private static function term_detail( $term ) {
		$taxonomy = get_taxonomy( $term->taxonomy );
		$label    = $taxonomy && isset( $taxonomy->labels->singular_name ) ? $taxonomy->labels->singular_name : __( 'Term', 'alternatepro-elements' );

		return sprintf(
			/* translators: 1: taxonomy label, 2: term ID. */
			__( '%1$s #%2$d', 'alternatepro-elements' ),
			$label,
			absint( $term->term_id )
		);
	}

	/**
	 * Return a taxonomy group label for search results.
	 *
	 * @param \WP_Term $term Term object.
	 * @return string
	 */
	private static function term_group( $term ) {
		$taxonomy = get_taxonomy( $term->taxonomy );

		if ( $taxonomy && isset( $taxonomy->labels->name ) && '' !== $taxonomy->labels->name ) {
			return $taxonomy->labels->name;
		}

		return __( 'Terms', 'alternatepro-elements' );
	}
}
