<?php
/**
 * Display condition resolver.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

use AlternatePro\Elements\Helpers;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Resolves the best matching template for the current request.
 */
final class Conditions {
	/**
	 * Language resolver.
	 *
	 * @var LanguageResolver
	 */
	private $language_resolver;

	/**
	 * Per-request cache.
	 *
	 * @var array<string,int>
	 */
	private $cache = array();

	/**
	 * Constructor.
	 *
	 * @param LanguageResolver $language_resolver Language resolver.
	 */
	public function __construct( LanguageResolver $language_resolver ) {
		$this->language_resolver = $language_resolver;
	}

	/**
	 * Resolve a template ID for a type.
	 *
	 * @param string $type Template type.
	 * @return int
	 */
	public function get_template_id( $type ) {
		$type     = sanitize_key( $type );
		$language = $this->language_resolver->get_current_language();
		$cache_id = $type . ':' . $language . ':' . Helpers::current_path() . ':' . get_queried_object_id();

		if ( isset( $this->cache[ $cache_id ] ) ) {
			return $this->cache[ $cache_id ];
		}

		$template_ids = $this->query_active_templates( $type );
		$match        = $this->find_best_match( $template_ids, $language );

		if ( ! $match && 'all' !== $language ) {
			$match = $this->find_best_match( $template_ids, 'all' );
		}

		$this->cache[ $cache_id ] = absint( $match );

		return $this->cache[ $cache_id ];
	}

	/**
	 * Decode stored JSON conditions.
	 *
	 * @param mixed $raw Raw condition value.
	 * @return array<int,array<string,mixed>>
	 */
	public static function decode_conditions( $raw ) {
		if ( is_array( $raw ) ) {
			return $raw;
		}

		$raw = (string) $raw;

		if ( '' === $raw ) {
			return array();
		}

		$decoded = json_decode( $raw, true );

		if ( ! is_array( $decoded ) ) {
			return array();
		}

		return $decoded;
	}

	/**
	 * Sanitize condition input from the admin form.
	 *
	 * @param mixed $raw Raw condition input.
	 * @return array<int,array<string,string>>
	 */
	public static function sanitize_conditions( $raw ) {
		if ( ! is_array( $raw ) ) {
			return array();
		}

		$allowed = Module::condition_types();
		$clean   = array();

		foreach ( $raw as $type => $data ) {
			$type = sanitize_key( $type );

			if ( ! isset( $allowed[ $type ] ) || ! is_array( $data ) || empty( $data['enabled'] ) ) {
				continue;
			}

			$operator = in_array( $type, array( 'exclude_pages', 'exclude_posts' ), true ) ? 'exclude' : 'include';
			$value    = isset( $data['value'] ) ? sanitize_textarea_field( wp_unslash( $data['value'] ) ) : '';

			$clean[] = array(
				'type'     => $type,
				'operator' => $operator,
				'value'    => $value,
			);
		}

		return $clean;
	}

	/**
	 * Return a brief admin summary.
	 *
	 * @param array<int,array<string,mixed>> $conditions Conditions.
	 * @return string
	 */
	public static function summarize_conditions( array $conditions ) {
		if ( empty( $conditions ) ) {
			return __( 'Entire Site', 'alternatepro-elements' );
		}

		$labels = Module::condition_types();
		$names  = array();

		foreach ( $conditions as $condition ) {
			$type = isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : '';

			if ( isset( $labels[ $type ] ) ) {
				$names[] = $labels[ $type ];
			}
		}

		return $names ? implode( ', ', $names ) : __( 'Entire Site', 'alternatepro-elements' );
	}

	/**
	 * Query active templates for a type.
	 *
	 * @param string $type Template type.
	 * @return int[]
	 */
	private function query_active_templates( $type ) {
		$query = new WP_Query(
			array(
				'post_type'      => Module::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => Module::TYPE_META,
						'value' => $type,
					),
					array(
						'key'   => Module::STATUS_META,
						'value' => 'active',
					),
				),
			)
		);

		return array_map( 'absint', $query->posts );
	}

	/**
	 * Find best match within a language scope.
	 *
	 * @param int[]  $template_ids Template IDs.
	 * @param string $language Language code to match.
	 * @return int
	 */
	private function find_best_match( array $template_ids, $language ) {
		$language       = sanitize_key( $language );
		$best_id        = 0;
		$best_score     = -1;
		$best_priority  = -1;
		$best_timestamp = -1;

		foreach ( $template_ids as $template_id ) {
			$template_language = get_post_meta( $template_id, Module::LANGUAGE_META, true );
			$template_language = $template_language ? sanitize_key( $template_language ) : 'all';

			if ( $template_language !== $language ) {
				continue;
			}

			$conditions = self::decode_conditions( get_post_meta( $template_id, Module::CONDITIONS_META, true ) );
			$score      = $this->match_score( $conditions );

			if ( false === $score ) {
				continue;
			}

			$priority  = absint( get_post_meta( $template_id, Module::PRIORITY_META, true ) );
			$timestamp = absint( get_post_modified_time( 'U', true, $template_id ) );

			if (
				$score > $best_score ||
				( $score === $best_score && $priority > $best_priority ) ||
				( $score === $best_score && $priority === $best_priority && $timestamp > $best_timestamp )
			) {
				$best_id        = $template_id;
				$best_score     = $score;
				$best_priority  = $priority;
				$best_timestamp = $timestamp;
			}
		}

		return absint( $best_id );
	}

	/**
	 * Return match score, or false when not matched.
	 *
	 * @param array<int,array<string,mixed>> $conditions Conditions.
	 * @return int|false
	 */
	private function match_score( array $conditions ) {
		if ( empty( $conditions ) ) {
			return 10;
		}

		$best_include_score = false;

		foreach ( $conditions as $condition ) {
			if ( 'exclude' === $this->condition_operator( $condition ) && $this->condition_matches( $condition ) ) {
				return false;
			}
		}

		foreach ( $conditions as $condition ) {
			if ( 'include' !== $this->condition_operator( $condition ) ) {
				continue;
			}

			if ( $this->condition_matches( $condition ) ) {
				$best_include_score = max( (int) $best_include_score, $this->condition_specificity( $this->condition_type( $condition ) ) );
			}
		}

		return false === $best_include_score ? false : $best_include_score;
	}

	/**
	 * Check if one condition matches the current request.
	 *
	 * @param array<string,mixed> $condition Condition.
	 * @return bool
	 */
	private function condition_matches( array $condition ) {
		$type  = $this->condition_type( $condition );
		$value = isset( $condition['value'] ) ? $condition['value'] : '';
		$id    = absint( get_queried_object_id() );

		switch ( $type ) {
			case 'entire_site':
				return true;

			case 'front_page':
				return is_front_page();

			case 'all_pages':
				return is_page();

			case 'specific_pages':
			case 'exclude_pages':
				return is_page() && in_array( $id, Helpers::parse_id_list( $value ), true );

			case 'all_posts':
				return is_singular( 'post' );

			case 'specific_posts':
			case 'exclude_posts':
				return is_singular( 'post' ) && in_array( $id, Helpers::parse_id_list( $value ), true );

			case 'archives':
				return is_archive();

			case 'search_results':
				return is_search();

			case '404_page':
				return is_404();

			case 'url_path_starts':
				return $this->path_matches( $value, 'starts' );

			case 'url_path_contains':
				return $this->path_matches( $value, 'contains' );
		}

		return false;
	}

	/**
	 * Check URL path conditions.
	 *
	 * @param mixed  $value Raw value.
	 * @param string $mode Match mode.
	 * @return bool
	 */
	private function path_matches( $value, $mode ) {
		$path  = Helpers::current_path();
		$items = Helpers::parse_string_list( $value );

		foreach ( $items as $item ) {
			$item = '/' . ltrim( $item, '/' );

			if ( 'starts' === $mode && 0 === strpos( $path, $item ) ) {
				return true;
			}

			if ( 'contains' === $mode && false !== strpos( $path, trim( $item, '/' ) ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get condition type.
	 *
	 * @param array<string,mixed> $condition Condition.
	 * @return string
	 */
	private function condition_type( array $condition ) {
		return isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : '';
	}

	/**
	 * Get condition operator.
	 *
	 * @param array<string,mixed> $condition Condition.
	 * @return string
	 */
	private function condition_operator( array $condition ) {
		return isset( $condition['operator'] ) && 'exclude' === $condition['operator'] ? 'exclude' : 'include';
	}

	/**
	 * Condition specificity score.
	 *
	 * @param string $type Condition type.
	 * @return int
	 */
	private function condition_specificity( $type ) {
		$scores = array(
			'specific_pages'    => 90,
			'specific_posts'    => 90,
			'front_page'        => 80,
			'404_page'          => 80,
			'search_results'    => 75,
			'url_path_starts'   => 70,
			'url_path_contains' => 65,
			'archives'          => 60,
			'all_pages'         => 50,
			'all_posts'         => 50,
			'entire_site'       => 10,
		);

		return isset( $scores[ $type ] ) ? $scores[ $type ] : 0;
	}
}
