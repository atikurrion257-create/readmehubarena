<?php
/**
 * Extended [rmh_*] shortcodes (Part 7): evidence stamps, verdict boxes,
 * pricing stamps/tables, comparison matrix, cluster grids, directory,
 * deals list, and author strip. All output is escaped and mobile-first.
 *
 * @package ReadMeHubCoreDynamic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [rmh_evidence_stamp] — Mode A / Mode B proof card from page ACF.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_evidence_stamp( $atts ): string {
	$atts = shortcode_atts(
		array(
			'post_id' => '',
		),
		(array) $atts,
		'rmh_evidence_stamp'
	);

	$post_id = '' !== trim( (string) $atts['post_id'] ) ? (int) $atts['post_id'] : (int) get_the_ID();
	$evidence = rmh_verified_test_evidence( $post_id );
	$mode = null !== $evidence ? 'mode_b_tested' : 'mode_a_research';

	$mode_labels = array(
		'mode_a_research' => __( 'Research-Based Evaluation (Hands-On Testing Scheduled)', 'readmehub-core' ),
		'mode_b_tested'   => __( 'Hands-On Tested (Mode B)', 'readmehub-core' ),
	);
	$mode_label = $mode_labels[ $mode ] ?? $mode_labels['mode_a_research'];

	$plan_tested = $evidence['plan'] ?? '';
	$test_date = $evidence['date'] ?? '';
	$workflow = (string) rmh_get_field( 'rmh_workflow_evaluated', $post_id, '' );
	$limits = (string) rmh_get_field( 'rmh_observed_limits', $post_id, '' );

	$html = '<aside class="rmh-evidence-stamp" aria-label="' . esc_attr__( 'Evaluation evidence', 'readmehub-core' ) . '">';
	$html .= '<p class="rmh-evidence-stamp__mode">' . esc_html( $mode_label ) . '</p>';
	$item_name = trim( (string) rmh_get_field( 'rmh_review_item_name', $post_id, '' ) );
	if ( null !== $evidence && '' !== $item_name ) { $html .= '<p>' . esc_html__( 'Product evaluated:', 'readmehub-core' ) . ' ' . esc_html( $item_name ) . '</p>'; }
	$html .= '<dl class="rmh-evidence-stamp__grid">';
	if ( '' !== $plan_tested ) {
		$html .= '<div><dt>' . esc_html__( 'Plan Tested', 'readmehub-core' ) . '</dt><dd>' . esc_html( $plan_tested ) . '</dd></div>';
	}
	if ( '' !== $test_date ) {
		$html .= '<div><dt>' . esc_html__( 'Test Date', 'readmehub-core' ) . '</dt><dd>' . esc_html( $test_date ) . '</dd></div>';
	}
	if ( '' !== $workflow ) {
		$html .= '<div><dt>' . esc_html__( 'Workflow Evaluated', 'readmehub-core' ) . '</dt><dd>' . esc_html( $workflow ) . '</dd></div>';
	}
	if ( '' !== $limits ) {
		$html .= '<div><dt>' . esc_html__( 'Observed / Documented Limits', 'readmehub-core' ) . '</dt><dd>' . esc_html( $limits ) . '</dd></div>';
	}
	$html .= '</dl>';
	if ( 'mode_a_research' === $mode ) {
		$html .= '<p class="rmh-evidence-stamp__note">' . esc_html__( 'Research-based evaluation. No hands-on test results are claimed.', 'readmehub-core' ) . '</p>';
	}
	$html .= '</aside>';
	return $html;
}
add_shortcode( 'rmh_evidence_stamp', 'rmh_shortcode_evidence_stamp' );

/**
 * [rmh_verdict_box] — Quick/Final verdict + persona fit + qualified CTA.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_verdict_box( $atts ): string {
	$atts = shortcode_atts(
		array(
			'tool'     => '',
			'post_id'  => '',
			'placement' => 'top',
		),
		(array) $atts,
		'rmh_verdict_box'
	);

	$post_id = '' !== trim( (string) $atts['post_id'] ) ? (int) $atts['post_id'] : (int) get_the_ID();
	$verdict = (string) rmh_get_field( 'rmh_verdict_summary', $post_id, '' );
	$persona = (string) rmh_get_field( 'rmh_primary_persona', $post_id, '' );
	$skip_if = (string) rmh_get_field( 'rmh_skip_if', $post_id, '' );

	$record = null;
	if ( '' !== trim( (string) $atts['tool'] ) ) {
		$record = rmh_resolve_tool_record( (string) $atts['tool'] );
	} elseif ( '' !== $verdict ) {
		// Fall back to the page's own tool slug meta when present.
		$slug = (string) rmh_get_field( 'rmh_tool_slug', $post_id, '' );
		if ( '' !== $slug ) {
			$record = rmh_resolve_tool_record( $slug );
		}
	}

	if ( '' === $verdict && is_array( $record ) ) {
		$verdict = (string) $record['verdict'];
	}
	if ( '' === $persona && is_array( $record ) ) {
		$persona = (string) $record['best_for'];
	}
	if ( '' === $skip_if && is_array( $record ) ) {
		$skip_if = (string) $record['not_best_for'];
	}

	$html = '<aside class="rmh-verdict-box" aria-label="' . esc_attr__( 'Quick verdict', 'readmehub-core' ) . '">';
	if ( '' !== $verdict ) {
		$html .= '<p class="rmh-verdict-box__verdict">' . esc_html( $verdict ) . '</p>';
	}
	if ( '' !== $persona ) {
		$html .= '<p><strong>' . esc_html__( 'Best for:', 'readmehub-core' ) . '</strong> ' . esc_html( $persona ) . '</p>';
	}
	if ( '' !== $skip_if ) {
		$html .= '<p><strong>' . esc_html__( 'Skip if:', 'readmehub-core' ) . '</strong> ' . esc_html( $skip_if ) . '</p>';
	}
	if ( is_array( $record ) ) {
		$html .= do_shortcode(
			sprintf(
				'[rmh_affiliate_cta tool="%s" placement="%s"]',
				esc_attr( $record['slug'] ),
				esc_attr( (string) $atts['placement'] )
			)
		);
	}
	$html .= '</aside>';
	return $html;
}
add_shortcode( 'rmh_verdict_box', 'rmh_shortcode_verdict_box' );

/**
 * [rmh_pricing_stamp] — Inline "Pricing verified YYYY-MM-DD" with stale warning.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_pricing_stamp( $atts ): string {
	$atts = shortcode_atts(
		array(
			'post_id' => '',
			'date'    => '',
		),
		(array) $atts,
		'rmh_pricing_stamp'
	);

	$post_id = '' !== trim( (string) $atts['post_id'] ) ? (int) $atts['post_id'] : (int) get_the_ID();
	$date = trim( (string) $atts['date'] );
	if ( '' === $date ) {
		$date = (string) rmh_get_field( 'rmh_last_pricing_check', $post_id, '' );
	}
	$date = rmh_normalize_date( $date );
	if ( '' === $date || $date > gmdate( 'Y-m-d' ) ) {
		return '';
	}

	$badge = rmh_freshness_badge( $date );
	$label = sprintf( __( 'Pricing verified %s', 'readmehub-core' ), $date );
	if ( is_array( $badge ) && 'rmh-freshness--stale' === $badge['class'] ) {
		$label .= ' — ' . __( 're-verification due', 'readmehub-core' );
	}

	return sprintf(
		'<span class="rmh-pricing-stamp %s">%s</span>',
		esc_attr( is_array( $badge ) ? $badge['class'] : '' ),
		esc_html( $label )
	);
}
add_shortcode( 'rmh_pricing_stamp', 'rmh_shortcode_pricing_stamp' );

/**
 * [rmh_pricing_table] — Intro vs renewal rows with source URL and verified date.
 * Usage: [rmh_pricing_table tools="systeme-io,clickup"]
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_pricing_table( $atts ): string {
	$atts = shortcode_atts(
		array(
			'tools' => '',
		),
		(array) $atts,
		'rmh_pricing_table'
	);

	$slugs = array_filter( array_map( 'trim', explode( ',', (string) $atts['tools'] ) ) );
	if ( empty( $slugs ) ) {
		return '';
	}

	$html = '<div class="rmh-pricing-table-wrap" tabindex="0" role="region" aria-label="' . esc_attr__( 'Pricing comparison', 'readmehub-core' ) . '"><table class="rmh-pricing-table"><thead><tr><th scope="col">' . esc_html__( 'Tool', 'readmehub-core' ) . '</th><th scope="col">' . esc_html__( 'Intro and renewal prices', 'readmehub-core' ) . '</th></tr></thead><tbody>';
	foreach ( $slugs as $slug ) {
		$record = rmh_resolve_tool_record( $slug );
		if ( null === $record ) { continue; }
		$html .= '<tr><th scope="row">' . esc_html( $record['title'] ) . '</th><td>' . rmh_price_cell( (int) $record['id'] ) . '</td></tr>';
	}
	$html .= '</tbody></table></div>';
	return $html;
}
add_shortcode( 'rmh_pricing_table', 'rmh_shortcode_pricing_table' );

/**
 * [rmh_comparison_matrix] — Side-by-side matrix from rmh_tool records.
 * Mobile: horizontal scroll with sticky first column (CSS handles stacking).
 * Usage: [rmh_comparison_matrix tools="clickup,asana" columns="price,pros,cons,cta"]
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_comparison_matrix( $atts ): string {
	$atts = shortcode_atts(
		array(
			'tools'   => '',
			'columns' => 'price,pros,cons,cta',
		),
		(array) $atts,
		'rmh_comparison_matrix'
	);

	$slugs = array_filter( array_map( 'trim', explode( ',', (string) $atts['tools'] ) ) );
	if ( count( $slugs ) < 2 ) {
		return '';
	}
	$columns = array_map( 'trim', explode( ',', strtolower( (string) $atts['columns'] ) ) );

	$records = array();
	foreach ( $slugs as $slug ) {
		$record = rmh_resolve_tool_record( $slug );
		if ( null !== $record ) {
			$records[] = $record;
		}
	}
	if ( count( $records ) < 2 ) {
		return '';
	}

	$html = '<div class="rmh-comparison-wrap" tabindex="0" role="region" aria-label="' . esc_attr__( 'Comparison table', 'readmehub-core' ) . '"><table class="rmh-comparison-matrix"><thead><tr><th>' . esc_html__( 'Criterion', 'readmehub-core' ) . '</th>';
	foreach ( $records as $record ) {
		$html .= '<th>' . esc_html( $record['title'] ) . '</th>';
	}
	$html .= '</tr></thead><tbody>';

	$rows = array();
	if ( in_array( 'price', $columns, true ) ) {
		$rows[] = array(
			'label' => __( 'Pricing', 'readmehub-core' ),
			'get'   => function ( $r ) { return rmh_price_cell( (int) $r['id'] ); },
			'html'  => true,
		);
	}
	if ( in_array( 'free', $columns, true ) ) {
		$rows[] = array(
			'label' => __( 'Free Tier', 'readmehub-core' ),
			'get'   => function ( $r ) { return rmh_free_plan_cell( (int) $r['id'] ); },
			'html'  => true,
		);
	}
	if ( in_array( 'pros', $columns, true ) ) {
		$rows[] = array(
			'label' => __( 'Key Strengths', 'readmehub-core' ),
			'get'   => function ( $r ) { return implode( '; ', $r['pros_list'] ); },
		);
	}
	if ( in_array( 'cons', $columns, true ) ) {
		$rows[] = array(
			'label' => __( 'Key Limitations', 'readmehub-core' ),
			'get'   => function ( $r ) { return implode( '; ', $r['cons_list'] ); },
		);
	}
	if ( in_array( 'bestfor', $columns, true ) ) {
		$rows[] = array(
			'label' => __( 'Best For', 'readmehub-core' ),
			'get'   => function ( $r ) { return $r['best_for']; },
		);
	}
	if ( in_array( 'verdict', $columns, true ) ) {
		$rows[] = array(
			'label' => __( 'Verdict', 'readmehub-core' ),
			'get'   => function ( $r ) { return $r['verdict']; },
		);
	}

	foreach ( $rows as $row ) {
		$html .= '<tr><th scope="row">' . esc_html( $row['label'] ) . '</th>';
		foreach ( $records as $record ) {
			$value = (string) call_user_func( $row['get'], $record );
			$html .= '<td>' . ( ! empty( $row['html'] ) ? $value : esc_html( $value ) ) . '</td>';
		}
		$html .= '</tr>';
	}

	if ( in_array( 'cta', $columns, true ) ) {
		$html .= '<tr><th scope="row">' . esc_html__( 'Official Site', 'readmehub-core' ) . '</th>';
		foreach ( $records as $record ) {
			$html .= '<td>' . do_shortcode( sprintf( '[rmh_affiliate_cta tool="%s"]', esc_attr( $record['slug'] ) ) ) . '</td>';
		}
		$html .= '</tr>';
	}

	$html .= '</tbody></table></div>';
	return $html;
}
add_shortcode( 'rmh_comparison_matrix', 'rmh_shortcode_comparison_matrix' );

/**
 * [rmh_dynamic_cluster_grid] — Card grid of published pages in a silo/cluster.
 * Usage: [rmh_dynamic_cluster_grid silo="saas" cluster="project-management"]
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_dynamic_cluster_grid( $atts ): string {
	$atts = shortcode_atts(
		array(
			'silo'    => '',
			'cluster' => '',
			'limit'   => '12',
		),
		(array) $atts,
		'rmh_dynamic_cluster_grid'
	);

	$meta_query = array( 'relation' => 'AND' );
	if ( '' !== trim( (string) $atts['silo'] ) ) {
		$meta_query[] = array(
			'key'     => 'rmh_silo_slug',
			'value'   => sanitize_key( (string) $atts['silo'] ),
			'compare' => '=',
		);
	}
	if ( '' !== trim( (string) $atts['cluster'] ) ) {
		$meta_query[] = array(
			'key'     => 'rmh_cluster_slug',
			'value'   => sanitize_key( (string) $atts['cluster'] ),
			'compare' => '=',
		);
	}

	$query = new WP_Query(
		array(
			'post_type'      => array( 'page', 'post' ),
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, (int) $atts['limit'] ),
			'meta_query'     => count( $meta_query ) > 1 ? $meta_query : array(),
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return '<p class="rmh-empty-state">' . esc_html__( 'No published pages in this cluster yet. Check back soon — new reviews are added after verification.', 'readmehub-core' ) . '</p>';
	}

	$html = '<div class="rmh-cluster-grid">';
	while ( $query->have_posts() ) {
		$query->the_post();
		$html .= '<article class="rmh-cluster-card">';
		$html .= '<h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
		$html .= '<p>' . esc_html( wp_trim_words( (string) get_the_excerpt(), 24 ) ) . '</p>';
		$html .= '<p><a href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read more', 'readmehub-core' ) . '</a></p>';
		$html .= '</article>';
	}
	$html .= '</div>';
	wp_reset_postdata();
	return $html;
}
add_shortcode( 'rmh_dynamic_cluster_grid', 'rmh_shortcode_dynamic_cluster_grid' );

/**
 * [rmh_tool_directory] — Filterable directory of rmh_tool records (no affiliate CTA on cards).
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_tool_directory( $atts ): string {
    $atts = shortcode_atts( array( 'silo' => '', 'cluster' => '', 'per_page' => '12' ), (array) $atts, 'rmh_tool_directory' );
    $map = rmh_get_locked_silo_map();
    $silo = isset( $_GET['rmh_silo'] ) && is_string( $_GET['rmh_silo'] ) ? sanitize_key( wp_unslash( $_GET['rmh_silo'] ) ) : sanitize_key( (string) $atts['silo'] );
    $cluster = isset( $_GET['rmh_cluster'] ) && is_string( $_GET['rmh_cluster'] ) ? sanitize_key( wp_unslash( $_GET['rmh_cluster'] ) ) : sanitize_key( (string) $atts['cluster'] );
    $silo = isset( $map[$silo] ) ? $silo : '';
    $clusters = array();
    foreach ( $map as $silo_key => $data ) {
        if ( '' !== $silo && $silo !== $silo_key ) { continue; }
        foreach ( $data['clusters'] as $key => $entry ) { $clusters[ $entry['term_slug'] ?? $key ] = $entry['label']; }
    }
    $cluster = isset( $clusters[$cluster] ) ? $cluster : '';
    $page = isset( $_GET['rmh_page'] ) && is_scalar( $_GET['rmh_page'] ) ? max( 1, min( 10000, absint( $_GET['rmh_page'] ) ) ) : 1;
    $per_page = max( 1, min( 50, absint( $atts['per_page'] ) ) );
    $tax = array( 'relation' => 'AND' );
    if ( '' !== $silo ) { $tax[] = array( 'taxonomy' => 'rmh_silo', 'field' => 'slug', 'terms' => array( $silo ) ); }
    if ( '' !== $cluster ) { $tax[] = array( 'taxonomy' => 'rmh_cluster', 'field' => 'slug', 'terms' => array( $cluster ) ); }
    $args = array( 'post_type' => 'rmh_tool', 'post_status' => 'publish', 'posts_per_page' => $per_page, 'paged' => $page, 'orderby' => 'title', 'order' => 'ASC' );
    if ( count( $tax ) > 1 ) { $args['tax_query'] = $tax; }
    $query = new WP_Query( $args );
    $base = get_permalink( get_queried_object_id() );
    $html = '<form method="get" class="rmh-directory-filters" action="' . esc_url( $base ) . '">';
    $html .= '<label>' . esc_html__( 'Silo', 'readmehub-core' ) . ' <select name="rmh_silo"><option value="">' . esc_html__( 'All silos', 'readmehub-core' ) . '</option>';
    foreach ( $map as $key => $data ) { $html .= '<option value="' . esc_attr( $key ) . '"' . selected( $silo, $key, false ) . '>' . esc_html( $data['label'] ) . '</option>'; }
    $html .= '</select></label> <label>' . esc_html__( 'Cluster', 'readmehub-core' ) . ' <select name="rmh_cluster"><option value="">' . esc_html__( 'All clusters', 'readmehub-core' ) . '</option>';
    foreach ( $clusters as $key => $label ) { $html .= '<option value="' . esc_attr( $key ) . '"' . selected( $cluster, $key, false ) . '>' . esc_html( $label ) . '</option>'; }
    $html .= '</select></label> <button type="submit">' . esc_html__( 'Filter', 'readmehub-core' ) . '</button> <a href="' . esc_url( $base ) . '">' . esc_html__( 'Reset filters', 'readmehub-core' ) . '</a></form>';
    if ( ! $query->have_posts() ) {
        wp_reset_postdata();
        return $html . '<p class="rmh-empty-state">' . esc_html__( 'No tools match these filters. Reset filters or browse by category.', 'readmehub-core' ) . '</p>';
    }
    $html .= '<div class="rmh-tool-directory">';
    while ( $query->have_posts() ) {
        $query->the_post();
        $record = rmh_resolve_tool_record( (string) get_post()->post_name );
        if ( null === $record ) { continue; }
        $html .= '<article class="rmh-tool-directory__card">';
        if ( '' !== $record['thumbnail_url'] ) { $html .= '<img src="' . esc_url( $record['thumbnail_url'] ) . '" alt="" loading="lazy" width="64" height="64">'; }
        $html .= '<h3>' . esc_html( $record['title'] ) . '</h3><p>' . esc_html( $record['category'] ) . '</p>';
        $review_url = (string) rmh_get_field( 'rmh_review_url', (int) get_the_ID(), '' );
        $review_id = '' !== $review_url ? url_to_postid( $review_url ) : 0;
        if ( $review_id && 'publish' === get_post_status( $review_id ) ) { $html .= '<p><a href="' . esc_url( get_permalink( $review_id ) ) . '">' . esc_html__( 'Read review', 'readmehub-core' ) . '</a></p>'; }
        $html .= '</article>';
    }
    $html .= '</div>';
    if ( $query->max_num_pages > 1 ) {
        $html .= '<nav aria-label="' . esc_attr__( 'Tool directory pages', 'readmehub-core' ) . '">';
        if ( $page > 1 ) { $html .= '<a href="' . esc_url( add_query_arg( array( 'rmh_silo' => $silo, 'rmh_cluster' => $cluster, 'rmh_page' => $page - 1 ), $base ) ) . '">' . esc_html__( 'Previous', 'readmehub-core' ) . '</a> '; }
        $html .= esc_html( sprintf( __( 'Page %1$d of %2$d', 'readmehub-core' ), $page, $query->max_num_pages ) );
        if ( $page < $query->max_num_pages ) { $html .= ' <a href="' . esc_url( add_query_arg( array( 'rmh_silo' => $silo, 'rmh_cluster' => $cluster, 'rmh_page' => $page + 1 ), $base ) ) . '">' . esc_html__( 'Next', 'readmehub-core' ) . '</a>'; }
        $html .= '</nav>';
    }
    wp_reset_postdata();
    return $html;
}
add_shortcode( 'rmh_tool_directory', 'rmh_shortcode_tool_directory' );

/**
 * [rmh_deals_list] — Verified active deals only (expired never shown as active).
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_deals_list( $atts ): string {
	$atts = shortcode_atts(
		array(
			'limit' => '12',
		),
		(array) $atts,
		'rmh_deals_list'
	);

	$filter = isset( $_GET['rmh_deal'] ) && is_string( $_GET['rmh_deal'] ) ? sanitize_key( wp_unslash( $_GET['rmh_deal'] ) ) : 'all';
	if ( ! in_array( $filter, array( 'all', 'active', 'upcoming', 'expired' ), true ) ) { $filter = 'all'; }
	$limit = max( 1, min( 100, absint( $atts['limit'] ) ) );
	$deals = array_values( array_filter( rmh_get_verified_deals(), function ( $deal ) use ( $filter ) { return 'all' === $filter || $filter === $deal['status']; } ) );
	$base = get_permalink( get_queried_object_id() );
	$page = isset( $_GET['rmh_deals_page'] ) && is_scalar( $_GET['rmh_deals_page'] ) ? max( 1, absint( $_GET['rmh_deals_page'] ) ) : 1;
	$total_pages = max( 1, (int) ceil( count( $deals ) / $limit ) );
	$page = min( $page, $total_pages );
	$html = '<form method="get" action="' . esc_url( $base ) . '"><label>' . esc_html__( 'Offer status', 'readmehub-core' ) . ' <select name="rmh_deal">';
	foreach ( array( 'all' => __( 'All', 'readmehub-core' ), 'active' => __( 'Active', 'readmehub-core' ), 'upcoming' => __( 'Upcoming', 'readmehub-core' ), 'expired' => __( 'Expired', 'readmehub-core' ) ) as $key => $label ) {
		$html .= '<option value="' . esc_attr( $key ) . '"' . selected( $filter, $key, false ) . '>' . esc_html( $label ) . '</option>';
	}
	$html .= '</select></label> <button type="submit">' . esc_html__( 'Filter', 'readmehub-core' ) . '</button></form>';
	if ( empty( $deals ) ) { return $html . '<p class="rmh-empty-state">' . esc_html__( "No verified deals right now. We only list offers we have checked on the vendor's own site.", 'readmehub-core' ) . '</p>'; }
	$html .= '<div class="rmh-deals-list">';
	foreach ( array_slice( $deals, ( $page - 1 ) * $limit, $limit ) as $deal ) {
		$id = $deal['id'];
		$html .= '<article class="rmh-deal-card"><h3><a href="' . esc_url( get_permalink( $id ) ) . '">' . esc_html( get_the_title( $id ) ) . '</a></h3>';
		$html .= '<p>' . esc_html( ucfirst( $deal['status'] ) ) . ' · ' . esc_html( sprintf( __( 'Last verified %s', 'readmehub-core' ), $deal['verified'] ) );
		if ( $deal['stale'] ) { $html .= ' · ' . esc_html__( 'Re-verification due', 'readmehub-core' ); }
		$html .= '</p>';
		$terms = (string) rmh_get_field( 'rmh_deal_terms_preview', $id, '' );
		if ( '' !== $terms ) { $html .= '<p>' . esc_html( $terms ) . '</p>'; }
		$html .= '</article>';
	}
	$html .= '</div>';
	if ( $total_pages > 1 ) {
		$html .= '<nav aria-label="' . esc_attr__( 'Deal pages', 'readmehub-core' ) . '">';
		if ( $page > 1 ) { $html .= '<a href="' . esc_url( add_query_arg( array( 'rmh_deal' => $filter, 'rmh_deals_page' => $page - 1 ), $base ) ) . '">' . esc_html__( 'Previous', 'readmehub-core' ) . '</a> '; }
		$html .= esc_html( sprintf( __( 'Page %1$d of %2$d', 'readmehub-core' ), $page, $total_pages ) );
		if ( $page < $total_pages ) { $html .= ' <a href="' . esc_url( add_query_arg( array( 'rmh_deal' => $filter, 'rmh_deals_page' => $page + 1 ), $base ) ) . '">' . esc_html__( 'Next', 'readmehub-core' ) . '</a>'; }
		$html .= '</nav>';
	}
	return $html;
}
add_shortcode( 'rmh_deals_list', 'rmh_shortcode_deals_list' );

/**
 * [rmh_author_strip] — Author, reviewed-by, and dates strip.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_author_strip( $atts ): string {
	$atts = shortcode_atts(
		array(
			'post_id' => '',
		),
		(array) $atts,
		'rmh_author_strip'
	);

	$post_id = '' !== trim( (string) $atts['post_id'] ) ? (int) $atts['post_id'] : (int) get_the_ID();
	$author = (string) rmh_get_field( 'rmh_author_name', $post_id, '' );
	if ( '' === $author ) {
		$author = (string) rmh_get_setting( 'default_author_name' );
	}
	$reviewer = (string) rmh_get_field( 'rmh_reviewer_name', $post_id, '' );
	$updated = get_the_modified_date( 'F j, Y', $post_id );

	$parts = array();
	if ( '' !== trim( $author ) ) { $parts[] = sprintf( __( 'Written by %s', 'readmehub-core' ), esc_html( $author ) ); }
	if ( '' !== trim( $reviewer ) ) { $parts[] = sprintf( __( 'Reviewed by %s', 'readmehub-core' ), esc_html( $reviewer ) ); }
	if ( $updated ) { $parts[] = sprintf( __( 'Last updated %s', 'readmehub-core' ), esc_html( $updated ) ); }
	$html = '<div class="rmh-author-strip"><p>' . implode( ' · ', $parts ) . '</p></div>';
	return $html;
}
add_shortcode( 'rmh_author_strip', 'rmh_shortcode_author_strip' );

/** Prominent T12 verification state; no affiliate link or unverified offer. */
function rmh_shortcode_deal_stamp(): string {
    $deal = rmh_get_deal_state( (int) get_the_ID() );
    if ( ! $deal ) { return '<p class="rmh-empty-state">' . esc_html__( 'Offer not verified.', 'readmehub-core' ) . '</p>'; }
    $label = sprintf( __( '%1$s · Last verified %2$s', 'readmehub-core' ), ucfirst( $deal['status'] ), $deal['verified'] );
    if ( $deal['stale'] ) { $label .= ' · ' . __( 'Re-verification due', 'readmehub-core' ); }
    return '<p class="rmh-deal-stamp">' . esc_html( $label ) . ' <a href="' . esc_url( $deal['source'] ) . '" rel="noopener">' . esc_html__( 'Vendor offer source', 'readmehub-core' ) . '</a></p>';
}
add_shortcode( 'rmh_deal_stamp', 'rmh_shortcode_deal_stamp' );
