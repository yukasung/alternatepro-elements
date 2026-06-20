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
		$groups = array(
			'basic'         => array(
				'label' => __( 'Basic', 'alternatepro-elements' ),
				'value' => array(
					'entire_site'   => __( 'Entire Website', 'alternatepro-elements' ),
					'all_singulars' => __( 'All Singulars', 'alternatepro-elements' ),
					'archives'      => __( 'All Archives', 'alternatepro-elements' ),
				),
			),
			'special-pages' => array(
				'label' => __( 'Special Pages', 'alternatepro-elements' ),
				'value' => array(
					'404_page'       => __( '404 Page', 'alternatepro-elements' ),
					'search_results' => __( 'Search Page', 'alternatepro-elements' ),
					'blog_page'      => __( 'Blog / Posts Page', 'alternatepro-elements' ),
					'front_page'     => __( 'Front Page', 'alternatepro-elements' ),
					'date_archive'   => __( 'Date Archive', 'alternatepro-elements' ),
					'author_archive' => __( 'Author Archive', 'alternatepro-elements' ),
				),
			),
		);

		$groups = array_merge( $groups, self::post_type_location_groups() );

		$groups['specific-target'] = array(
			'label' => __( 'Specific Target', 'alternatepro-elements' ),
			'value' => array(
				'specifics' => __( 'Specific Pages / Posts / Taxonomies, etc.', 'alternatepro-elements' ),
			),
		);

		return $groups;
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

		$options['all_pages']         = __( 'All Pages', 'alternatepro-elements' );
		$options['all_posts']         = __( 'All Posts', 'alternatepro-elements' );
		$options['all_categories']    = __( 'All Categories Archive', 'alternatepro-elements' );
		$options['specific_pages']    = __( 'Specific Pages', 'alternatepro-elements' );
		$options['specific_posts']    = __( 'Specific Posts', 'alternatepro-elements' );
		$options['specific_category'] = __( 'Specific Category', 'alternatepro-elements' );
		$options['exclude_pages']     = __( 'Exclude Specific Pages', 'alternatepro-elements' );
		$options['exclude_posts']     = __( 'Exclude Specific Posts', 'alternatepro-elements' );

		return $options;
	}

	/**
	 * Return UAE-style location groups for public post types and taxonomies.
	 *
	 * @return array<string,array{label:string,value:array<string,string>}>
	 */
	private static function post_type_location_groups() {
		$groups     = array();
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		unset( $post_types['attachment'] );

		foreach ( $post_types as $post_type ) {
			$post_type_name = sanitize_key( $post_type->name );

			if ( '' === $post_type_name ) {
				continue;
			}

			$group_key = self::post_type_group_key( $post_type_name );
			$values    = array(
				self::post_type_rule_key( $post_type_name ) => sprintf(
					/* translators: %s: Post type plural label. */
					__( 'All %s', 'alternatepro-elements' ),
					self::post_type_plural_label( $post_type )
				),
			);

			if ( 'page' !== $post_type_name ) {
				$values[ self::post_type_archive_rule_key( $post_type_name ) ] = sprintf(
					/* translators: %s: Post type plural label. */
					__( 'All %s Archive', 'alternatepro-elements' ),
					self::post_type_plural_label( $post_type )
				);
			}

			foreach ( self::public_taxonomies_for_post_type( $post_type_name ) as $taxonomy ) {
				$values[ self::taxonomy_archive_rule_key( $taxonomy->name ) ] = sprintf(
					/* translators: %s: Taxonomy plural label. */
					__( 'All %s Archive', 'alternatepro-elements' ),
					self::taxonomy_plural_label( $taxonomy )
				);
			}

			$groups[ $group_key ] = array(
				'label' => self::post_type_plural_label( $post_type ),
				'value' => $values,
			);
		}

		return $groups;
	}

	/**
	 * Return public taxonomies attached to a post type.
	 *
	 * @param string $post_type Post type.
	 * @return \WP_Taxonomy[]
	 */
	private static function public_taxonomies_for_post_type( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$public     = array();

		foreach ( $taxonomies as $taxonomy ) {
			if ( empty( $taxonomy->public ) || 'post_format' === $taxonomy->name ) {
				continue;
			}

			$public[] = $taxonomy;
		}

		return $public;
	}

	/**
	 * Return a stable group key for a post type.
	 *
	 * @param string $post_type Post type.
	 * @return string
	 */
	private static function post_type_group_key( $post_type ) {
		return 'post-type-' . sanitize_key( $post_type );
	}

	/**
	 * Return a condition key for post type singulars.
	 *
	 * @param string $post_type Post type.
	 * @return string
	 */
	private static function post_type_rule_key( $post_type ) {
		if ( 'post' === $post_type ) {
			return 'all_posts';
		}

		if ( 'page' === $post_type ) {
			return 'all_pages';
		}

		return 'post_type_' . sanitize_key( $post_type );
	}

	/**
	 * Return a condition key for post type archives.
	 *
	 * @param string $post_type Post type.
	 * @return string
	 */
	private static function post_type_archive_rule_key( $post_type ) {
		return 'post_type_archive_' . sanitize_key( $post_type );
	}

	/**
	 * Return a condition key for taxonomy archives.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @return string
	 */
	private static function taxonomy_archive_rule_key( $taxonomy ) {
		if ( 'category' === $taxonomy ) {
			return 'all_categories';
		}

		return 'taxonomy_archive_' . sanitize_key( $taxonomy );
	}

	/**
	 * Return a post type plural label.
	 *
	 * @param \WP_Post_Type $post_type Post type object.
	 * @return string
	 */
	private static function post_type_plural_label( $post_type ) {
		return isset( $post_type->labels->name ) && '' !== $post_type->labels->name ? $post_type->labels->name : $post_type->name;
	}

	/**
	 * Return a taxonomy plural label.
	 *
	 * @param \WP_Taxonomy $taxonomy Taxonomy object.
	 * @return string
	 */
	private static function taxonomy_plural_label( $taxonomy ) {
		return isset( $taxonomy->labels->name ) && '' !== $taxonomy->labels->name ? $taxonomy->labels->name : $taxonomy->name;
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
	 * Return condition types that support admin target search.
	 *
	 * @return string[]
	 */
	public static function searchable_rule_types() {
		return array(
			'specifics',
			'specific_pages',
			'specific_posts',
			'specific_category',
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
	 * Check whether a rule type supports admin target search.
	 *
	 * @param string $type Rule type.
	 * @return bool
	 */
	public static function rule_has_search( $type ) {
		return in_array( sanitize_key( $type ), self::searchable_rule_types(), true );
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
