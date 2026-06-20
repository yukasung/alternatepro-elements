<?php
/**
 * Header/Footer display rule helpers.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

/**
 * Provides shared display rule options, sanitization, and formatting.
 */
final class RuleOptions {
	/**
	 * Return grouped location rule options.
	 *
	 * @return array<string,array{label:string,value:array<string,string>}>
	 */
	public static function location_groups() {
		return array(
			'basic'           => array(
				'label' => __( 'Basic', 'alternatepro-elements' ),
				'value' => array(
					'entire_site'    => __( 'Entire Site', 'alternatepro-elements' ),
					'all_pages'      => __( 'All Pages', 'alternatepro-elements' ),
					'all_posts'      => __( 'All Posts', 'alternatepro-elements' ),
					'archives'       => __( 'Archives', 'alternatepro-elements' ),
					'all_categories' => __( 'All Categories', 'alternatepro-elements' ),
				),
			),
			'special-pages'   => array(
				'label' => __( 'Special Pages', 'alternatepro-elements' ),
				'value' => array(
					'front_page'     => __( 'Front Page', 'alternatepro-elements' ),
					'blog_page'      => __( 'Blog Page', 'alternatepro-elements' ),
					'search_results' => __( 'Search Results', 'alternatepro-elements' ),
					'404_page'       => __( '404 Page', 'alternatepro-elements' ),
				),
			),
			'specific-target' => array(
				'label' => __( 'Specific Target', 'alternatepro-elements' ),
				'value' => array(
					'specifics'         => __( 'Specific Pages / Posts / Taxonomies, etc.', 'alternatepro-elements' ),
					'specific_pages'    => __( 'Specific Pages', 'alternatepro-elements' ),
					'specific_posts'    => __( 'Specific Posts', 'alternatepro-elements' ),
					'specific_category' => __( 'Specific Category', 'alternatepro-elements' ),
				),
			),
			'advanced'        => array(
				'label' => __( 'Advanced', 'alternatepro-elements' ),
				'value' => array(
					'url_path_starts'   => __( 'URL Path Starts With', 'alternatepro-elements' ),
					'url_path_contains' => __( 'URL Path Contains', 'alternatepro-elements' ),
				),
			),
		);
	}

	/**
	 * Return flat location options.
	 *
	 * @return array<string,string>
	 */
	public static function location_options() {
		$options = array();

		foreach ( self::location_groups() as $group ) {
			$options = array_merge( $options, $group['value'] );
		}

		$options['exclude_pages'] = __( 'Exclude Specific Pages', 'alternatepro-elements' );
		$options['exclude_posts'] = __( 'Exclude Specific Posts', 'alternatepro-elements' );

		return $options;
	}

	/**
	 * Return condition types that require a value field.
	 *
	 * @return string[]
	 */
	public static function value_rule_types() {
		return array(
			'specifics',
			'specific_pages',
			'specific_posts',
			'specific_category',
			'url_path_starts',
			'url_path_contains',
		);
	}

	/**
	 * Check whether a rule type uses an extra value field.
	 *
	 * @param string $type Rule type.
	 * @return bool
	 */
	public static function rule_has_value( $type ) {
		return in_array( sanitize_key( $type ), self::value_rule_types(), true );
	}

	/**
	 * Return user role rule groups.
	 *
	 * @return array<string,array{label:string,value:array<string,string>}>
	 */
	public static function user_role_groups() {
		$groups = array(
			'basic'    => array(
				'label' => __( 'Basic', 'alternatepro-elements' ),
				'value' => array(
					'all'        => __( 'All', 'alternatepro-elements' ),
					'logged-in'  => __( 'Logged In', 'alternatepro-elements' ),
					'logged-out' => __( 'Logged Out', 'alternatepro-elements' ),
				),
			),
			'advanced' => array(
				'label' => __( 'Advanced', 'alternatepro-elements' ),
				'value' => array(),
			),
		);

		foreach ( get_editable_roles() as $slug => $data ) {
			$groups['advanced']['value'][ sanitize_key( $slug ) ] = isset( $data['name'] ) ? translate_user_role( $data['name'] ) : $slug;
		}

		return $groups;
	}

	/**
	 * Return flat user role options.
	 *
	 * @return array<string,string>
	 */
	public static function user_role_options() {
		$options = array();

		foreach ( self::user_role_groups() as $group ) {
			$options = array_merge( $options, $group['value'] );
		}

		return $options;
	}

	/**
	 * Return a default display rule.
	 *
	 * @return array<int,array<string,string>>
	 */
	public static function default_display_rules() {
		return array(
			array(
				'type'     => 'entire_site',
				'operator' => 'include',
				'value'    => '',
			),
		);
	}

	/**
	 * Filter conditions by operator.
	 *
	 * @param array<int,array<string,mixed>> $conditions Conditions.
	 * @param string                         $operator Operator.
	 * @return array<int,array<string,string>>
	 */
	public static function conditions_for_operator( array $conditions, $operator ) {
		$operator = 'exclude' === $operator ? 'exclude' : 'include';
		$rules    = array();

		foreach ( $conditions as $condition ) {
			$type               = isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : '';
			$condition_operator = self::condition_operator( $condition );

			if ( 'exclude_pages' === $type ) {
				$type               = 'specific_pages';
				$condition_operator = 'exclude';
			} elseif ( 'exclude_posts' === $type ) {
				$type               = 'specific_posts';
				$condition_operator = 'exclude';
			}

			if ( '' === $type || $condition_operator !== $operator ) {
				continue;
			}

			$rules[] = array(
				'type'     => $type,
				'operator' => $operator,
				'value'    => isset( $condition['value'] ) ? (string) $condition['value'] : '',
			);
		}

		return $rules;
	}

	/**
	 * Sanitize posted location rules.
	 *
	 * @param mixed  $raw Raw input.
	 * @param string $operator Rule operator.
	 * @return array<int,array<string,string>>
	 */
	public static function sanitize_location_rules( $raw, $operator ) {
		if ( ! is_array( $raw ) ) {
			return array();
		}

		$operator = 'exclude' === $operator ? 'exclude' : 'include';
		$allowed  = self::location_options();
		$rules    = isset( $raw['rule'] ) && is_array( $raw['rule'] ) ? $raw['rule'] : array();
		$values   = isset( $raw['value'] ) && is_array( $raw['value'] ) ? $raw['value'] : array();
		$clean    = array();
		$seen     = array();

		foreach ( $rules as $index => $rule ) {
			$type = sanitize_key( $rule );

			if ( ! isset( $allowed[ $type ] ) ) {
				continue;
			}

			$value = self::rule_has_value( $type ) && isset( $values[ $index ] ) ? sanitize_textarea_field( $values[ $index ] ) : '';

			if ( self::rule_has_value( $type ) && '' === trim( $value ) ) {
				continue;
			}

			$key = $operator . ':' . $type . ':' . $value;

			if ( isset( $seen[ $key ] ) ) {
				continue;
			}

			$seen[ $key ] = true;
			$clean[]      = array(
				'type'     => $type,
				'operator' => $operator,
				'value'    => $value,
			);
		}

		return $clean;
	}

	/**
	 * Sanitize legacy checkbox condition input.
	 *
	 * @param mixed $raw Raw input.
	 * @return array<int,array<string,string>>
	 */
	public static function sanitize_legacy_conditions( $raw ) {
		if ( ! is_array( $raw ) ) {
			return array();
		}

		$allowed = self::location_options();
		$clean   = array();

		foreach ( $raw as $type => $data ) {
			$type = sanitize_key( $type );

			if ( ! isset( $allowed[ $type ] ) || ! is_array( $data ) || empty( $data['enabled'] ) ) {
				continue;
			}

			$operator = in_array( $type, array( 'exclude_pages', 'exclude_posts' ), true ) ? 'exclude' : 'include';
			$value    = isset( $data['value'] ) ? sanitize_textarea_field( $data['value'] ) : '';

			$clean[] = array(
				'type'     => $type,
				'operator' => $operator,
				'value'    => $value,
			);
		}

		return $clean;
	}

	/**
	 * Sanitize posted user role rules.
	 *
	 * @param mixed $raw Raw input.
	 * @return string[]
	 */
	public static function sanitize_user_roles( $raw ) {
		if ( ! is_array( $raw ) ) {
			return array();
		}

		$allowed = self::user_role_options();
		$clean   = array();

		foreach ( $raw as $role ) {
			$role = sanitize_key( $role );

			if ( '' === $role || ! isset( $allowed[ $role ] ) ) {
				continue;
			}

			$clean[] = $role;
		}

		return array_values( array_unique( $clean ) );
	}

	/**
	 * Decode stored user roles.
	 *
	 * @param mixed $raw Raw value.
	 * @return string[]
	 */
	public static function decode_user_roles( $raw ) {
		if ( ! is_array( $raw ) ) {
			return array();
		}

		return self::sanitize_user_roles( $raw );
	}

	/**
	 * Return a public condition label.
	 *
	 * @param string $type Rule type.
	 * @return string
	 */
	public static function location_label( $type ) {
		$type    = sanitize_key( $type );
		$options = self::location_options();

		return isset( $options[ $type ] ) ? $options[ $type ] : $type;
	}

	/**
	 * Return a public user role label.
	 *
	 * @param string $role User role rule.
	 * @return string
	 */
	public static function user_role_label( $role ) {
		$role    = sanitize_key( $role );
		$options = self::user_role_options();

		return isset( $options[ $role ] ) ? $options[ $role ] : $role;
	}

	/**
	 * Get condition operator.
	 *
	 * @param array<string,mixed> $condition Condition.
	 * @return string
	 */
	private static function condition_operator( array $condition ) {
		return isset( $condition['operator'] ) && 'exclude' === $condition['operator'] ? 'exclude' : 'include';
	}
}
