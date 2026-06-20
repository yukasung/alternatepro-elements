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
	 * Per-request cache.
	 *
	 * @var array<string,int>
	 */
	private $cache = array();

	/**
	 * Resolve a template ID for a type.
	 *
	 * @param string $type Template type.
	 * @return int
	 */
	public function get_template_id( $type ) {
		$type     = sanitize_key( $type );
		$cache_id = $type . ':' . Helpers::current_path() . ':' . get_queried_object_id();

		if ( isset( $this->cache[ $cache_id ] ) ) {
			return $this->cache[ $cache_id ];
		}

		$template_ids = $this->query_active_templates( $type );
		$match        = $this->find_best_match( $template_ids );

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
		return RuleOptions::sanitize_legacy_conditions( $raw );
	}

	/**
	 * Return a brief admin summary.
	 *
	 * @param array<int,array<string,mixed>> $conditions Conditions.
	 * @return string
	 */
	public static function summarize_conditions( array $conditions ) {
		if ( empty( $conditions ) ) {
			return __( 'No display rules', 'alternatepro-elements' );
		}

		$include_names = array();
		$exclude_names = array();

		foreach ( $conditions as $condition ) {
			$type = isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : '';

			if ( '' === $type ) {
				continue;
			}

			if ( 'exclude' === self::condition_operator_value( $condition ) ) {
				$exclude_names[] = RuleOptions::location_label( $type );
			} else {
				$include_names[] = RuleOptions::location_label( $type );
			}
		}

		if ( empty( $include_names ) && empty( $exclude_names ) ) {
			return __( 'No display rules', 'alternatepro-elements' );
		}

		$summary = array();

		if ( ! empty( $include_names ) ) {
			$summary[] = sprintf(
				/* translators: %s: Display condition names. */
				__( 'Display: %s', 'alternatepro-elements' ),
				implode( ', ', array_unique( $include_names ) )
			);
		}

		if ( ! empty( $exclude_names ) ) {
			$summary[] = sprintf(
				/* translators: %s: Exclusion condition names. */
				__( 'Exclude: %s', 'alternatepro-elements' ),
				implode( ', ', array_unique( $exclude_names ) )
			);
		}

		return implode( ' | ', $summary );
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
	 * Find best matching template.
	 *
	 * @param int[] $template_ids Template IDs.
	 * @return int
	 */
	private function find_best_match( array $template_ids ) {
		$best_id        = 0;
		$best_score     = -1;
		$best_priority  = -1;
		$best_timestamp = -1;

		foreach ( $template_ids as $template_id ) {
			if ( ! $this->user_rules_match( get_post_meta( $template_id, Module::USER_ROLES_META, true ) ) ) {
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
			return false;
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

			case 'blog_page':
				return is_home();

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

			case 'all_categories':
				return is_category();

			case 'specific_category':
				return is_category() && in_array( $id, Helpers::parse_id_list( $value ), true );

			case 'specifics':
				return $this->specific_targets_match( $value, $id );

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
	 * Check specific target tokens.
	 *
	 * Supported tokens mirror the UAE-style specific target field while also
	 * accepting plain IDs for simple local testing.
	 *
	 * @param mixed $value Raw value.
	 * @param int   $current_id Current queried object ID.
	 * @return bool
	 */
	private function specific_targets_match( $value, $current_id ) {
		foreach ( Helpers::parse_string_list( $value ) as $target ) {
			$target = strtolower( $target );

			if ( absint( $target ) > 0 && is_singular() && absint( $target ) === $current_id ) {
				return true;
			}

			if ( preg_match( '/^(post|page)-(\d+)$/', $target, $matches ) && is_singular() && absint( $matches[2] ) === $current_id ) {
				return true;
			}

			if ( preg_match( '/^(tax|term|cat|category)-(\d+)$/', $target, $matches ) && ( is_category() || is_tag() || is_tax() ) && absint( $matches[2] ) === $current_id ) {
				return true;
			}

			if ( preg_match( '/^(tax|term|cat|category)-(\d+)-single-([a-z0-9_-]+)$/', $target, $matches ) && is_singular() ) {
				$term_id  = absint( $matches[2] );
				$taxonomy = sanitize_key( $matches[3] );

				if ( $term_id > 0 && '' !== $taxonomy && has_term( $term_id, $taxonomy, $current_id ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check stored user role display rules.
	 *
	 * @param mixed $rules Stored user role rules.
	 * @return bool
	 */
	private function user_rules_match( $rules ) {
		$rules = RuleOptions::decode_user_roles( $rules );

		if ( empty( $rules ) ) {
			return true;
		}

		foreach ( $rules as $rule ) {
			switch ( $rule ) {
				case 'all':
					return true;

				case 'logged-in':
					if ( is_user_logged_in() ) {
						return true;
					}
					break;

				case 'logged-out':
					if ( ! is_user_logged_in() ) {
						return true;
					}
					break;

				default:
					if ( is_user_logged_in() ) {
						$user = wp_get_current_user();

						if ( isset( $user->roles ) && is_array( $user->roles ) && in_array( $rule, $user->roles, true ) ) {
							return true;
						}
					}
					break;
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
			'specific_category' => 90,
			'specifics'         => 90,
			'front_page'        => 80,
			'404_page'          => 80,
			'blog_page'         => 78,
			'search_results'    => 75,
			'url_path_starts'   => 70,
			'url_path_contains' => 65,
			'all_categories'    => 65,
			'archives'          => 60,
			'all_pages'         => 50,
			'all_posts'         => 50,
			'entire_site'       => 10,
		);

		return isset( $scores[ $type ] ) ? $scores[ $type ] : 0;
	}

	/**
	 * Get condition operator for static contexts.
	 *
	 * @param array<string,mixed> $condition Condition.
	 * @return string
	 */
	private static function condition_operator_value( array $condition ) {
		return isset( $condition['operator'] ) && 'exclude' === $condition['operator'] ? 'exclude' : 'include';
	}
}
