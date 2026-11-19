<?php
/**
 * Custom Post Type (rmh_tool), Silo/Cluster Taxonomies (5 SaaS + 2 Hosting),
 * Permalink Rewrites (/hosting/wordpress/small-business/), and Anti-Thin-Content Indexing Gates.
 *
 * @package ReadMeHubCoreDynamic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the locked Launch Silo & Cluster map (Part 0, Part 4 & Stage 0 owner confirmation).
 * Gated verticals (Personal Finance CAT-038–040, Insurance CAT-041–043, broad VPN/hosting head terms)
 * are intentionally excluded.
 *
 * @return array<string, array<string, mixed>>
 */
function rmh_get_locked_silo_map(): array {
	return array(
		'saas'    => array(
			'label'    => 'SaaS & AI Tools',
			'url'      => '/saas/',
			'clusters' => array(
				'project-management' => array(
					'label' => 'Project Management',
					'url'   => '/saas/project-management/',
				),
				'email-marketing'    => array(
					'label' => 'Email Marketing & Funnels',
					'url'   => '/saas/email-marketing/',
				),
				'seo-marketing'      => array(
					'label' => 'SEO & Marketing',
					'url'   => '/saas/seo-marketing/',
				),
				'ai-writing'         => array(
					'label' => 'AI Writing Tools',
					'url'   => '/saas/ai-writing/',
				),
				'crm'                => array(
					'label' => 'CRM Software',
					'url'   => '/saas/crm/',
				),
			),
		),
		'hosting' => array(
			'label'    => 'Web Hosting',
			'url'      => '/hosting/',
			'clusters' => array(
				'managed-wordpress'        => array(
					'label' => 'Managed WordPress Hosting',
					'url'   => '/hosting/managed-wordpress/',
				),
				'wordpress/small-business' => array(
					'label'     => 'Small Business WordPress Hosting',
					'term_slug' => 'small-business-wordpress',
					'url'       => '/hosting/wordpress/small-business/',
				),
			),
		),
	);
}

/**
 * Register the rmh_tool internal registry CPT and rmh_silo / rmh_cluster taxonomies.
 */
function rmh_register_cpt_and_taxonomies(): void {
	register_post_type(
		'rmh_tool',
		array(
			'labels'              => array(
				'name'               => __( 'Tools & Affiliate Registry', 'readmehub-core' ),
				'singular_name'      => __( 'Tool Record', 'readmehub-core' ),
				'add_new_item'       => __( 'Add New Tool Record', 'readmehub-core' ),
				'edit_item'          => __( 'Edit Tool Record', 'readmehub-core' ),
				'new_item'           => __( 'New Tool Record', 'readmehub-core' ),
				'view_item'          => __( 'View Tool Record', 'readmehub-core' ),
				'search_items'       => __( 'Search Tool Registry', 'readmehub-core' ),
				'not_found'          => __( 'No tool records found.', 'readmehub-core' ),
				'not_found_in_trash' => __( 'No tool records found in Trash.', 'readmehub-core' ),
				'menu_name'          => __( 'RMH Tools', 'readmehub-core' ),
			),
			// Internal structured registry: never creates thin public single URLs; editorial reviews live at Silo URLs.
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'menu_position'       => 22,
			'menu_icon'           => 'dashicons-Clipboard',
			'supports'            => array( 'title', 'thumbnail', 'custom-fields' ),
		)
	);

	register_taxonomy(
		'rmh_silo',
		array( 'page', 'post', 'rmh_tool' ),
		array(
			'labels'            => array(
				'name'          => __( 'Silos', 'readmehub-core' ),
				'singular_name' => __( 'Silo', 'readmehub-core' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);

	register_taxonomy(
		'rmh_cluster',
		array( 'page', 'post', 'rmh_tool' ),
		array(
			'labels'            => array(
				'name'          => __( 'Topic Clusters', 'readmehub-core' ),
				'singular_name' => __( 'Topic Cluster', 'readmehub-core' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'rmh_register_cpt_and_taxonomies', 5 );

/**
 * Support the locked 3-segment Small Business WordPress Hosting path
 * (/hosting/wordpress/small-business/ and /hosting/wordpress/small-business/<child>/)
 * even when no standalone /hosting/wordpress/ hub page exists in the Site Registry,
 * and safely redirect bare /hosting/wordpress/ requests to /hosting/ (anti-thin-content).
 */
function rmh_register_small_business_hosting_rewrites(): void {
	add_rewrite_rule(
		'^hosting/wordpress/small-business/([^/]+)/?$',
		'index.php?pagename=hosting/wordpress/small-business/$matches[1]',
		'top'
	);
	add_rewrite_rule(
		'^hosting/wordpress/small-business/?$',
		'index.php?pagename=hosting/wordpress/small-business',
		'top'
	);
}
add_action( 'init', 'rmh_register_small_business_hosting_rewrites', 10 );

/**
 * Safely redirect bare /hosting/wordpress/ (an unmapped intermediate path segment)
 * to the canonical /hosting/ L1 hub with HTTP 301 so visitors and crawlers never hit a 404 or thin stub.
 */
function rmh_redirect_unmapped_intermediate_hosting_segment(): void {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );

	if ( 'hosting/wordpress' === $path ) {
		wp_safe_redirect( home_url( '/hosting/' ), 301 );
		exit;
	}
}
// Redirect intentionally not enabled: inspect existing site routes first.

/**
 * Seed the canonical 2 silos and 7 clusters into rmh_silo and rmh_cluster taxonomies.
 */
function rmh_seed_default_taxonomy_terms(): void {
	$map = rmh_get_locked_silo_map();
	foreach ( $map as $silo_slug => $silo_data ) {
		if ( ! term_exists( $silo_slug, 'rmh_silo' ) ) {
			wp_insert_term(
				(string) $silo_data['label'],
				'rmh_silo',
				array( 'slug' => $silo_slug )
			);
		}
		foreach ( $silo_data['clusters'] as $cluster_key => $cluster_data ) {
			$term_slug = (string) ( $cluster_data['term_slug'] ?? $cluster_key );
			if ( ! term_exists( $term_slug, 'rmh_cluster' ) ) {
				wp_insert_term(
					(string) $cluster_data['label'],
					'rmh_cluster',
					array( 'slug' => $term_slug )
				);
			}
		}
	}
}

/**
 * Count published editorial child pages/posts belonging to a hub or directory threshold.
 *
 * @param int $post_id Page ID being checked.
 * @return bool True when the page is forced noindex under Part 4 rules; false when indexable.
 */
function rmh_should_force_noindex( int $post_id = 0 ): bool {
	if ( ! $post_id && ( is_search() || is_404() || is_tag() || is_date() || is_author() ) ) {
		return true;
	}

	if ( ! $post_id && is_singular() ) {
		$post_id = (int) get_queried_object_id();
	}
	if ( ! $post_id ) {
		return false;
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return false;
	}

	// Drafts/non-published are never indexed.
	if ( 'publish' !== $post->post_status ) {
		return true;
	}

	// Block indexing if unresolved {{VERIFY-...}} placeholder exists anywhere in post_content!
	if ( false !== strpos( (string) $post->post_content, '{{VERIFY-' ) ) {
		return true;
	}

	$slug          = (string) $post->post_name;
	$template_type = (string) rmh_get_field( 'rmh_page_template_type', $post_id, '' );

	// T13 Search page & T20 404 page are permanently noindex (Part 4 Rows #12, #13).
	if ( in_array( $slug, array( 'search', '404' ), true ) || in_array( $template_type, array( 'T13', 'T20' ), true ) ) {
		return true;
	}

	// T11 Deals archive (/deals/): noindex until >= 1 verified active deal exists (Part 4 Row #11).
	if ( 'deals' === $slug || 'T11' === $template_type ) {
		return rmh_count_verified_active_deals() < 1;
	}

	// T2 Tools Directory: index only after at least three tool entries are published.
	if ( 'tools' === $slug || 'T2' === $template_type ) {
		return count( get_posts( array( 'post_type' => 'rmh_tool', 'post_status' => 'publish', 'posts_per_page' => 3, 'fields' => 'ids', 'no_found_rows' => true ) ) ) < 3;
	}

	// T4 Blog Archive (/blog/): index only once >= 1 informational article is live (Part 4 Row #9).
	if ( 'blog' === $slug || 'T4' === $template_type ) {
		return rmh_count_published_by_template( array( 'T5' ) ) < 1;
	}

	// T7 Comparisons Archive (/comparisons/): index only once >= 1 comparison is live (Part 4 Row #10).
	if ( 'comparisons' === $slug || 'T7' === $template_type ) {
		return rmh_count_published_by_template( array( 'T8' ) ) < 1;
	}

	// T6 Silo / Cluster Hubs: stay noindex if they have zero published child pages (Part 4 anti-thin-content rule).
	if ( 'T6' === $template_type ) {
		$children = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_parent'    => $post_id,
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		if ( empty( $children ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Helper: count published pages/posts matching one or more rmh_page_template_type values.
 *
 * @param array<int, string> $templates Template codes such as array( 'T3' ).
 * @return int
 */
function rmh_count_published_by_template( array $templates ): int {
	$ids = get_posts(
		array(
			'post_type'      => array( 'page', 'post' ),
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'     => 'rmh_page_template_type',
					'value'   => $templates,
					'compare' => 'IN',
				),
			),
		)
	);
	return is_array( $ids ) ? count( $ids ) : 0;
}

/**
 * Helper: count published T12 deal pages with rmh_deal_status = 'active' and non-empty verified date.
 *
 * @return int
 */
function rmh_count_verified_active_deals(): int {
	return count( array_filter( rmh_get_verified_deals(), function ( $deal ) { return 'active' === $deal['status']; } ) );
}

/**
 * Enforce Part 4 index/noindex rules in Rank Math robots meta.
 *
 * @param array<string, string> $robots Rank Math robots array.
 * @return array<string, string>
 */
function rmh_filter_rank_math_robots( array $robots ): array {
	if ( rmh_should_force_noindex() ) {
		$robots['index']  = 'noindex';
		$robots['follow'] = 'follow';
	}
	return $robots;
}
add_filter( 'rank_math/frontend/robots', 'rmh_filter_rank_math_robots', 20 );

/**
 * Fallback WordPress core wp_robots filter when Rank Math is inactive.
 *
 * @param array<string, bool|string> $robots Core robots directives.
 * @return array<string, bool|string>
 */
function rmh_filter_core_wp_robots( array $robots ): array {
	if ( ! defined( 'RANK_MATH_VERSION' ) && rmh_should_force_noindex() ) {
		unset( $robots['index'] );
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'rmh_filter_core_wp_robots', 20 );

/**
 * Exclude forced-noindex pages from Rank Math XML sitemaps automatically.
 *
 * @param array<string, mixed>|false $url    Sitemap entry array.
 * @param string                     $type   Object type ('post', 'term', etc.).
 * @param object                     $object Post/Term object.
 * @return array<string, mixed>|false
 */
function rmh_filter_rank_math_sitemap_entry( $url, string $type, $object ) {
	if ( 'post' === $type && is_object( $object ) && isset( $object->ID ) ) {
		if ( rmh_should_force_noindex( (int) $object->ID ) ) {
			return false;
		}
	}
	return $url;
}
add_filter( 'rank_math/sitemap/entry', 'rmh_filter_rank_math_sitemap_entry', 20, 3 );
