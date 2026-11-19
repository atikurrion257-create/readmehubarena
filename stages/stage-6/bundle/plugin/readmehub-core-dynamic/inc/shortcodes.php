<?php
/**
 * FTC-compliant [rmh_*] shortcode suite.
 * All affiliate links route through [rmh_affiliate_cta] with
 * rel="sponsored nofollow noopener" and Systeme.io www-stripping.
 *
 * @package ReadMeHubCoreDynamic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize and sanitize an affiliate URL.
 * - Strips www. from systeme.io hosts (hard rule).
 * - Rejects javascript:, data:, and vbscript: schemes.
 * - Rejects affiliate markup on non-active / unapproved / validation-pending records.
 *
 * @param string $raw_url Raw URL from shortcode attribute or meta.
 * @param array<string, mixed>|null $record Optional rmh_tool record for gate checks.
 * @return string Sanitized URL or empty string when blocked.
 */
function rmh_sanitize_affiliate_url( string $raw_url, ?array $record = null ): string {
	$url = trim( $raw_url );
	if ( '' === $url ) {
		return '';
	}

	// Block dangerous schemes outright.
	if ( preg_match( '/^\s*(javascript|data|vbscript)\s*:/i', $url ) ) {
		return '';
	}

	// Only an explicit HTTPS URL can be approved.
	if ( ! preg_match( '#^https://#i', $url ) ) {
		return '';
	}

	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) || empty( $parts['host'] ) || isset( $parts['user'] ) || isset( $parts['pass'] ) ) {
		return '';
	}

	$host = strtolower( (string) $parts['host'] );

	// Hard rule: Systeme.io affiliate links must never contain www.
	if ( 'systeme.io' === $host || 'www.systeme.io' === $host ) {
		$host = 'systeme.io';
	}

	$scheme = strtolower( (string) ( $parts['scheme'] ?? 'https' ) );
	if ( ! in_array( $scheme, array( 'http', 'https' ), true ) ) {
		$scheme = 'https';
	}

	$port = isset( $parts['port'] ) ? ':' . (int) $parts['port'] : '';
	$path = isset( $parts['path'] ) ? (string) $parts['path'] : '/';
	$query = isset( $parts['query'] ) ? '?' . (string) $parts['query'] : '';
	$fragment = isset( $parts['fragment'] ) ? '#' . (string) $parts['fragment'] : '';

	$clean = $scheme . '://' . $host . $port . $path . $query . $fragment;

	// Gate: never output affiliate markup for closed, discontinued, deferred,
	// validation-pending, or unapproved records.
	if ( is_array( $record ) ) {
		$program_status = (string) ( $record['program_status'] ?? '' );
		$approval_status = (string) ( $record['approval_status'] ?? '' );
		$verification_status = (string) ( $record['verification_status'] ?? '' );
		if ( false !== stripos( (string) ( $record['wave'] ?? '' ), 'HOLD' ) ) { return ''; }

		$blocked_programs = array( 'discontinued', 'closed_to_new', 'deferred_until_traffic', 'requires_validation' );
		if ( in_array( $program_status, $blocked_programs, true ) || ! in_array( $program_status, array( 'active_open', 'active_network', 'active_approval_required' ), true ) ) {
			return '';
		}
		$expires = rmh_normalize_date( $record['expires_date'] ?? '' );
		$verified = rmh_normalize_date( $record['verified_date'] ?? '' );
		$today = gmdate( 'Y-m-d' );
		if ( '' === $expires || '' === $verified || $verified > $today || $expires < $today
			|| strtotime( $verified . ' +90 days UTC' ) < strtotime( $today . ' UTC' ) ) {
			return '';
		}
		if ( 'verified' !== $verification_status || 'approved' !== $approval_status || preg_match( '/YOURID|\{\{|\}\}/i', $clean ) ) {
			return '';
		}
	}

	return esc_url_raw( $clean );
}

/**
 * Resolve a tool reference (slug, ID, or title) to a normalized record array.
 *
 * @param string $tool_ref Slug, numeric ID, or exact title.
 * @return array<string, mixed>|null
 */
function rmh_resolve_tool_record( string $tool_ref ): ?array {
	$tool_ref = trim( $tool_ref );
	if ( '' === $tool_ref ) {
		return null;
	}

	$post_id = null;
	if ( is_numeric( $tool_ref ) ) {
		$candidate = get_post( (int) $tool_ref );
		if ( $candidate && 'rmh_tool' === $candidate->post_type ) {
			$post_id = (int) $tool_ref;
		}
	}
	if ( null === $post_id ) {
		$found = get_posts(
			array(
				'post_type'      => 'rmh_tool',
				'name'           => sanitize_title( $tool_ref ),
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		$post_id = ! empty( $found ) ? (int) $found[0] : null;
	}
	if ( null === $post_id ) {
		$found = get_posts(
			array(
				'post_type'      => 'rmh_tool',
				'title'          => $tool_ref,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		$post_id = ! empty( $found ) ? (int) $found[0] : null;
	}
	if ( null === $post_id ) {
		return null;
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return null;
	}

	$record = array(
		'id'                     => $post_id,
		'title'                  => (string) $post->post_title,
		'slug'                   => (string) $post->post_name,
		'category'               => (string) rmh_get_field( 'rmh_category', $post_id, '' ),
		'program_status'         => (string) rmh_get_field( 'rmh_program_status', $post_id, '' ),
		'verification_status'    => (string) rmh_get_field( 'rmh_verification_status', $post_id, '' ),
		'application_status'     => (string) rmh_get_field( 'rmh_application_status', $post_id, '' ),
		'approval_status'        => (string) rmh_get_field( 'rmh_approval_status', $post_id, '' ),
		'affiliate_url_template' => (string) rmh_get_field( 'rmh_affiliate_url_template', $post_id, '' ),
		'commission_summary'     => (string) rmh_get_field( 'rmh_commission_summary', $post_id, '' ),
		'cookie_days'            => (int) rmh_get_field( 'rmh_cookie_days', $post_id, 0 ),
		'cookie_note'            => (string) rmh_get_field( 'rmh_cookie_note', $post_id, '' ),
		'minimum_payout'         => (string) rmh_get_field( 'rmh_minimum_payout', $post_id, '' ),
		'priority'               => (string) rmh_get_field( 'rmh_priority', $post_id, '' ),
		'wave'                   => (string) rmh_get_field( 'rmh_wave', $post_id, '' ),
		'verified_date'          => (string) rmh_get_field( 'rmh_verified_date', $post_id, '' ),
		'expires_date'           => (string) rmh_get_field( 'rmh_expires_date', $post_id, '' ),
		'non_affiliate_url'      => (string) rmh_get_field( 'rmh_non_affiliate_url', $post_id, '' ),
		'non_affiliate_url_confirmed' => (string) rmh_get_field( 'rmh_non_affiliate_url_confirmed', $post_id, '' ),
		'non_affiliate_verified_date' => (string) rmh_get_field( 'rmh_non_affiliate_verified_date', $post_id, '' ),
		'price_note'             => (string) rmh_get_field( 'rmh_price_note', $post_id, '' ),
		'free_tier_note'         => (string) rmh_get_field( 'rmh_free_tier_note', $post_id, '' ),
		'pros_list'              => rmh_parse_lines( rmh_get_field( 'rmh_pros_list', $post_id, '' ) ),
		'cons_list'              => rmh_parse_lines( rmh_get_field( 'rmh_cons_list', $post_id, '' ) ),
		'best_for'               => (string) rmh_get_field( 'rmh_best_for', $post_id, '' ),
		'not_best_for'           => (string) rmh_get_field( 'rmh_not_best_for', $post_id, '' ),
		'verdict'                => (string) rmh_get_field( 'rmh_verdict', $post_id, '' ),
		'thumbnail_url'          => (string) get_the_post_thumbnail_url( $post_id, 'medium' ),
	);

	$affiliate_url = rmh_sanitize_affiliate_url( $record['affiliate_url_template'], $record );
	$record['affiliate_url'] = $affiliate_url;
	$record['is_affiliate'] = '' !== $affiliate_url;

	return $record;
}

/**
 * Compute a freshness badge from a verified date.
 *
 * @param string $verified_date Y-m-d date string.
 * @return array{label:string, class:string, days:int}|null
 */
function rmh_freshness_badge( string $verified_date ): ?array {
	$verified_date = trim( $verified_date );
	if ( '' === $verified_date ) {
		return null;
	}
	$timestamp = strtotime( $verified_date );
	if ( false === $timestamp ) {
		return null;
	}
	$days = (int) floor( ( time() - $timestamp ) / DAY_IN_SECONDS );
	if ( $days < 0 ) {
		$days = 0;
	}
	$formatted = date_i18n( 'M j, Y', $timestamp );

	if ( $days <= RMH_FRESHNESS_MAX_DAYS ) {
		return array(
			'label' => sprintf( __( 'Verified %s (%d days ago)', 'readmehub-core' ), $formatted, $days ),
			'class' => 'rmh-freshness--fresh',
			'days'  => $days,
		);
	}
	return array(
		'label' => sprintf( __( 'Last verified %s — re-verification due', 'readmehub-core' ), $formatted ),
		'class' => 'rmh-freshness--stale',
		'days'  => $days,
	);
}

/**
 * [rmh_disclosure] — FTC disclosure above the first affiliate CTA.
 * Supports per-page override via rmh_disclosure_override meta.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @param string|null $content Enclosed content.
 * @return string
 */
function rmh_shortcode_disclosure( $atts, ?string $content = null ): string {
	$atts = shortcode_atts(
		array(
			'text' => '',
		),
		(array) $atts,
		'rmh_disclosure'
	);

	$text = trim( (string) $atts['text'] );
	if ( '' === $text ) {
		$text = (string) rmh_get_field( 'rmh_disclosure_override', null, '' );
	}
	if ( '' === $text ) {
		$text = (string) rmh_get_setting( 'default_disclosure' );
	}

	$methodology_url = (string) rmh_get_setting( 'methodology_page_url' );
	$methodology_link = '';
	if ( '' !== $methodology_url ) {
		$methodology_link = sprintf(
			' <a href="%s">%s</a>.',
			esc_url( $methodology_url ),
			esc_html__( 'Read our Editorial Methodology', 'readmehub-core' )
		);
	}

	return sprintf(
		'<aside class="rmh-disclosure" role="note" aria-label="%s"><p>%s%s</p></aside>',
		esc_attr__( 'Affiliate disclosure', 'readmehub-core' ),
		esc_html( $text ),
		$methodology_link
	);
}
add_shortcode( 'rmh_disclosure', 'rmh_shortcode_disclosure' );

/**
 * [rmh_affiliate_cta] — the only approved affiliate link output.
 * Always renders rel="sponsored nofollow noopener" and target="_blank".
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @param string|null $content Enclosed link text.
 * @return string
 */
function rmh_shortcode_affiliate_cta( $atts, ?string $content = null ): string {
	$atts = shortcode_atts(
		array(
			'tool'   => '',
			'url'    => '',
			'label'  => '',
			'style'  => 'primary',
			'class'  => '',
			'placement' => '',
		),
		(array) $atts,
		'rmh_affiliate_cta'
	);

	$record = null;
	if ( '' !== trim( (string) $atts['tool'] ) ) {
		$record = rmh_resolve_tool_record( (string) $atts['tool'] );
	}

	// Only the reviewed registry URL is eligible; shortcode URL overrides are ignored.
	$raw_url = is_array( $record ) ? (string) $record['affiliate_url_template'] : '';

	// A missing registry record must never bypass affiliate status checks.
	$clean_url = is_array( $record ) ? rmh_sanitize_affiliate_url( $raw_url, $record ) : '';

	$label = trim( (string) $atts['label'] );
	if ( '' === $label && null !== $content && '' !== trim( $content ) ) {
		$label = trim( wp_strip_all_tags( $content ) );
	}
	if ( '' === $label && is_array( $record ) ) {
		$label = sprintf( __( 'Check %s Pricing', 'readmehub-core' ), $record['title'] );
	}
	if ( '' === $label ) {
		$label = __( 'Visit Official Site', 'readmehub-core' );
	}

	$style = in_array( (string) $atts['style'], array( 'primary', 'secondary', 'text' ), true ) ? (string) $atts['style'] : 'primary';
	$class = 'rmh-cta rmh-cta--' . $style . ( '' !== trim( (string) $atts['class'] ) ? ' ' . sanitize_html_class( (string) $atts['class'] ) : '' );

	// Fallback is separately verified; never reuse a rejected affiliate URL.
	if ( '' === $clean_url ) {
		if ( is_array( $record ) && false !== stripos( (string) $record['wave'], 'HOLD' ) ) { return ''; }
		if ( ! is_array( $record ) || '1' !== $record['non_affiliate_url_confirmed'] ) { return ''; }
		$fallback = rmh_non_affiliate_source_url( $record['non_affiliate_url'] );
		$checked = rmh_normalize_date( $record['non_affiliate_verified_date'] );
		$today = gmdate( 'Y-m-d' );
		if ( '' === $fallback || '' === $checked || $checked > $today || strtotime( $checked . ' +90 days UTC' ) < strtotime( $today . ' UTC' ) ) { return ''; }
		return '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $fallback ) . '" rel="noopener">' . esc_html( $label ) . '</a>';
	}

	// Inline disclosure guarantees visibility before every affiliate link, including
	// cards and matrix cells where page-level markup may have been omitted.
	return rmh_shortcode_disclosure( array() ) . sprintf(
		'<a class="%s" href="%s" rel="sponsored nofollow noopener" target="_blank" data-rmh-cta="%s" data-rmh-tool="%s">%s</a>',
		esc_attr( $class ),
		esc_url( $clean_url ),
		esc_attr( sanitize_key( (string) $atts['placement'] ) ),
		esc_attr( $record['slug'] ),
		esc_html( $label )
	);
}
add_shortcode( 'rmh_affiliate_cta', 'rmh_shortcode_affiliate_cta' );

/**
 * [rmh_tool_card] — single tool summary card (T3 review / T9 comparison / T10 listicle).
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_tool_card( $atts ): string {
	$atts = shortcode_atts(
		array(
			'tool'  => '',
			'show'  => 'pros,cons,price,cta',
			'class' => '',
		),
		(array) $atts,
		'rmh_tool_card'
	);

	$record = rmh_resolve_tool_record( (string) $atts['tool'] );
	if ( null === $record ) {
		return '';
	}

	$show = array_map( 'trim', explode( ',', strtolower( (string) $atts['show'] ) ) );
	$class = 'rmh-tool-card' . ( '' !== trim( (string) $atts['class'] ) ? ' ' . sanitize_html_class( (string) $atts['class'] ) : '' );

	$html = '<article class="' . esc_attr( $class ) . '">';
	$html .= '<header class="rmh-tool-card__head">';
	if ( '' !== $record['thumbnail_url'] ) {
		$html .= sprintf(
			'<img class="rmh-tool-card__logo" src="%s" alt="%s" loading="lazy" width="64" height="64" />',
			esc_url( $record['thumbnail_url'] ),
			esc_attr( $record['title'] )
		);
	}
	$html .= '<div class="rmh-tool-card__titles">';
	$html .= '<h3 class="rmh-tool-card__title">' . esc_html( $record['title'] ) . '</h3>';
	if ( '' !== $record['category'] ) {
		$html .= '<p class="rmh-tool-card__category">' . esc_html( $record['category'] ) . '</p>';
	}
	$html .= '</div>';
	$html .= '</header>';

	// Product prices require separate dated vendor-source verification.
	if ( in_array( 'price', $show, true ) ) { $html .= '<p class="rmh-tool-card__price">' . rmh_price_cell( (int) $record['id'] ) . '</p>'; }

	if ( in_array( 'pros', $show, true ) && ! empty( $record['pros_list'] ) ) {
		$html .= '<ul class="rmh-pros">';
		foreach ( $record['pros_list'] as $pro ) {
			$html .= '<li>' . esc_html( $pro ) . '</li>';
		}
		$html .= '</ul>';
	}
	if ( in_array( 'cons', $show, true ) && ! empty( $record['cons_list'] ) ) {
		$html .= '<ul class="rmh-cons">';
		foreach ( $record['cons_list'] as $con ) {
			$html .= '<li>' . esc_html( $con ) . '</li>';
		}
		$html .= '</ul>';
	}

	if ( in_array( 'cta', $show, true ) ) {
		$html .= do_shortcode(
			sprintf(
				'[rmh_affiliate_cta tool="%s" label="%s"]',
				esc_attr( $record['slug'] ),
				esc_attr( sprintf( __( 'Check %s Pricing', 'readmehub-core' ), $record['title'] ) )
			)
		);
	}

	$html .= '</article>';
	return $html;
}
add_shortcode( 'rmh_tool_card', 'rmh_shortcode_tool_card' );

/**
 * [rmh_pros_cons] — pros/cons two-column block.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_pros_cons( $atts ): string {
	$atts = shortcode_atts(
		array(
			'tool' => '',
		),
		(array) $atts,
		'rmh_pros_cons'
	);

	$record = rmh_resolve_tool_record( (string) $atts['tool'] );
	if ( null === $record && '' === trim( (string) $atts['tool'] ) ) {
		$record = array( 'pros_list' => rmh_parse_lines( rmh_get_field( 'rmh_pros_list', null, '' ) ), 'cons_list' => rmh_parse_lines( rmh_get_field( 'rmh_cons_list', null, '' ) ) );
	}
	if ( null === $record || ( empty( $record['pros_list'] ) && empty( $record['cons_list'] ) ) ) { return ''; }

	$html = '<div class="rmh-pros-cons">';
	if ( ! empty( $record['pros_list'] ) ) {
		$html .= '<div class="rmh-pros-cons__col"><h3>' . esc_html__( 'Pros', 'readmehub-core' ) . '</h3><ul class="rmh-pros">';
		foreach ( $record['pros_list'] as $pro ) {
			$html .= '<li>' . esc_html( $pro ) . '</li>';
		}
		$html .= '</ul></div>';
	}
	if ( ! empty( $record['cons_list'] ) ) {
		$html .= '<div class="rmh-pros-cons__col"><h3>' . esc_html__( 'Cons', 'readmehub-core' ) . '</h3><ul class="rmh-cons">';
		foreach ( $record['cons_list'] as $con ) {
			$html .= '<li>' . esc_html( $con ) . '</li>';
		}
		$html .= '</ul></div>';
	}
	$html .= '</div>';
	return $html;
}
add_shortcode( 'rmh_pros_cons', 'rmh_shortcode_pros_cons' );

/**
 * [rmh_faq] — visible FAQ accordion with optional FAQPage JSON-LD mirroring.
 * FAQ items are stored as Q: / A: line pairs in rmh_faq_items meta.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @param string|null $content Enclosed Q/A pairs (alternative to meta).
 * @return string
 */
function rmh_shortcode_faq( $atts, ?string $content = null ): string {
	$atts = shortcode_atts(
		array(
			'tool' => '',
		),
		(array) $atts,
		'rmh_faq'
	);

	$raw = (string) rmh_get_field( 'rmh_faq_items', null, '' );
	if ( '' === trim( $raw ) && null !== $content && '' !== trim( $content ) ) {
		$raw = trim( $content );
	}

	$items = rmh_parse_faq_pairs( $raw );
	if ( empty( $items ) ) {
		return '';
	}

	$html = '<div class="rmh-faq">';
	foreach ( $items as $item ) {
		$html .= '<details class="rmh-faq__item">';
		$html .= '<summary>' . esc_html( $item['q'] ) . '</summary>';
		$html .= '<div class="rmh-faq__answer">' . wp_kses_post( wpautop( $item['a'] ) ) . '</div>';
		$html .= '</details>';
	}
	$html .= '</div>';

	// FAQPage schema is intentionally omitted.

	return $html;
}
add_shortcode( 'rmh_faq', 'rmh_shortcode_faq' );

/**
 * Parse Q: / A: pairs from raw textarea content.
 *
 * @param string $raw Raw FAQ text.
 * @return array<int, array{q:string, a:string}>
 */
function rmh_parse_faq_pairs( string $raw ): array {
	$items = array();
	$current = null;
	$lines = preg_split( '/\r\n|\r|\n/', $raw ) ?: array();
	foreach ( $lines as $line ) {
		$trimmed = trim( $line );
		if ( '' === $trimmed ) {
			continue;
		}
		if ( preg_match( '/^Q\s*:\s*(.+)$/i', $trimmed, $m ) ) {
			if ( null !== $current && '' !== $current['q'] && '' !== $current['a'] ) {
				$items[] = $current;
			}
			$current = array(
				'q' => trim( $m[1] ),
				'a' => '',
			);
		} elseif ( preg_match( '/^A\s*:\s*(.+)$/i', $trimmed, $m ) ) {
			if ( null !== $current ) {
				$current['a'] = trim( $m[1] );
			}
		} elseif ( null !== $current && '' !== $current['a'] ) {
			$current['a'] .= ' ' . $trimmed;
		}
	}
	if ( null !== $current && '' !== $current['q'] && '' !== $current['a'] ) {
		$items[] = $current;
	}
	return $items;
}

/**
 * [rmh_newsletter] — quarterly audit alert signup (pending state until provider set).
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_newsletter( $atts ): string {
	$atts = shortcode_atts(
		array(
			'heading' => '',
			'subcopy' => '',
		),
		(array) $atts,
		'rmh_newsletter'
	);

	$heading = trim( (string) $atts['heading'] ) !== '' ? (string) $atts['heading'] : (string) rmh_get_setting( 'newsletter_heading' );
	$subcopy = trim( (string) $atts['subcopy'] ) !== '' ? (string) $atts['subcopy'] : (string) rmh_get_setting( 'newsletter_subcopy' );

	$heading_id = wp_unique_id( 'rmh-newsletter-heading-' );
	$html = '<section class="rmh-newsletter" aria-labelledby="' . esc_attr( $heading_id ) . '">';
	$html .= '<h2 id="' . esc_attr( $heading_id ) . '">' . esc_html( $heading ) . '</h2>'; 
	if ( '' !== $subcopy ) {
		$html .= '<p>' . esc_html( $subcopy ) . '</p>';
	}

	// No provider contract or consent flow has been supplied; do not collect data.
	$html .= '<p class="rmh-newsletter__pending">' . esc_html__( 'Newsletter signup is coming soon. We will open subscriptions once our email provider is confirmed.', 'readmehub-core' ) . '</p>';
	$html .= '</section>';
	return $html;
}
add_shortcode( 'rmh_newsletter', 'rmh_shortcode_newsletter' );

/**
 * [rmh_author_box] — alias of [rmh_author_strip] (Part 7 canonical name).
 * Kept for backward compatibility with block patterns and legacy content.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_author_box( $atts ): string {
	return rmh_shortcode_author_strip( $atts );
}
add_shortcode( 'rmh_author_box', 'rmh_shortcode_author_box' );

/**
 * [rmh_breadcrumbs] — accessible breadcrumb trail.
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string
 */
function rmh_shortcode_breadcrumbs( $atts ): string {
	$atts = shortcode_atts(
		array(
			'separator' => '›',
		),
		(array) $atts,
		'rmh_breadcrumbs'
	);

	$crumbs = rmh_get_breadcrumb_trail();
	if ( empty( $crumbs ) ) {
		return '';
	}

	$html = '<nav class="rmh-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'readmehub-core' ) . '"><ol>';
	foreach ( $crumbs as $crumb ) {
		if ( ! empty( $crumb['url'] ) ) {
			$html .= sprintf(
				'<li><a href="%s">%s</a></li>',
				esc_url( $crumb['url'] ),
				esc_html( $crumb['label'] )
			);
		} else {
			$html .= '<li aria-current="page">' . esc_html( $crumb['label'] ) . '</li>';
		}
	}
	$html .= '</ol></nav>';

	$GLOBALS['rmh_breadcrumb_schema_items'] = $crumbs;
	return $html;
}
add_shortcode( 'rmh_breadcrumbs', 'rmh_shortcode_breadcrumbs' );

/**
 * Build breadcrumb trail from the current page ancestry.
 *
 * @return array<int, array{label:string, url:string}>
 */
function rmh_get_breadcrumb_trail(): array {
	$trail = array(
		array(
			'label' => __( 'Home', 'readmehub-core' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_singular() ) {
		$post_id = (int) get_queried_object_id();
		$post = get_post( $post_id );
		if ( $post ) {
			$ancestors = array_reverse( get_post_ancestors( $post_id ) );
			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_post( $ancestor_id );
				if ( $ancestor ) {
					$trail[] = array(
						'label' => (string) $ancestor->post_title,
						'url'   => (string) get_permalink( $ancestor_id ),
					);
				}
			}
			$trail[] = array(
				'label' => (string) $post->post_title,
				'url'   => '',
			);
		}
	} elseif ( is_post_type_archive() || is_tax() || is_category() ) {
		$trail[] = array(
			'label' => (string) single_term_title( '', false ),
			'url'   => '',
		);
	}

	return $trail;
}

/**
 * Built-in unit assertions for the affiliate URL sanitizer.
 * Executed from Settings -> ReadMeHub Core and `wp rmh test-url-sanitizer`.
 *
 * @return array{assertions:array<int, array{label:string, passed:bool}>, all_passed:bool}
 */
function rmh_run_affiliate_url_self_tests(): array {
	$assertions = array();
	$all_passed = true;

	$check = function ( string $label, bool $passed ) use ( &$assertions, &$all_passed ): void {
		$assertions[] = array(
			'label'  => $label,
			'passed' => $passed,
		);
		if ( ! $passed ) {
			$all_passed = false;
		}
	};

	$good = array( 'program_status' => 'active_open', 'approval_status' => 'approved', 'verification_status' => 'verified', 'verified_date' => gmdate( 'Y-m-d' ), 'expires_date' => gmdate( 'Y-m-d', time() + 86400 ) );
	$check( 'Systeme.io www stripped', 'https://systeme.io/?sa=example' === rmh_sanitize_affiliate_url( 'https://www.systeme.io/?sa=example', $good ) );
	$check( 'Placeholder rejected', '' === rmh_sanitize_affiliate_url( 'https://systeme.io/?sa=YOURID', $good ) );
	$blocked = array_merge( $good, array( 'program_status' => 'discontinued' ) );
	$check( 'Discontinued rejected', '' === rmh_sanitize_affiliate_url( 'https://example.com/', $blocked ) );
	$blocked = array_merge( $good, array( 'verification_status' => 'REQUIRES VALIDATION' ) );
	$check( 'Unverified rejected', '' === rmh_sanitize_affiliate_url( 'https://example.com/', $blocked ) );
	$blocked = array_merge( $good, array( 'approval_status' => 'pending_owner_id' ) );
	$check( 'Pending approval rejected', '' === rmh_sanitize_affiliate_url( 'https://example.com/', $blocked ) );
	$blocked = array_merge( $good, array( 'expires_date' => '2000-01-01' ) );
	$check( 'Expired verification rejected', '' === rmh_sanitize_affiliate_url( 'https://example.com/', $blocked ) );
	$blocked = array_merge( $good, array( 'wave' => 'Wave-3 HOLD' ) );
	$check( 'Wave hold rejected', '' === rmh_sanitize_affiliate_url( 'https://example.com/', $blocked ) );
	$check( 'Unsafe scheme rejected', '' === rmh_sanitize_affiliate_url( 'javascript:alert(1)', $good ) );

	return array(
		'assertions' => $assertions,
		'all_passed' => $all_passed,
	);
}
