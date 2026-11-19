<?php
/** Canonical editorial data validation. No network verification is implied. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Accept ACF's Ymd storage and its Y-m-d formatted return; reject invalid dates. */
function rmh_normalize_date( $raw ): string {
    $value = trim( (string) $raw );
    if ( preg_match( '/^\d{8}$/D', $value ) ) {
        $value = substr( $value, 0, 4 ) . '-' . substr( $value, 4, 2 ) . '-' . substr( $value, 6, 2 );
    }
    $date = DateTimeImmutable::createFromFormat( '!Y-m-d', $value, new DateTimeZone( 'UTC' ) );
    return $date && $date->format( 'Y-m-d' ) === $value ? $value : '';
}

/** Only a manually checked, non-tracking HTTPS source is accepted. */
function rmh_non_affiliate_source_url( $raw ): string {
    $url = trim( (string) $raw );
    $parts = wp_parse_url( $url );
    if ( ! is_array( $parts ) || strtolower( (string) ( $parts['scheme'] ?? '' ) ) !== 'https'
        || empty( $parts['host'] ) || isset( $parts['user'] ) || isset( $parts['pass'] )
        || isset( $parts['query'] ) || isset( $parts['fragment'] ) || isset( $parts['port'] ) ) {
        return '';
    }
    return esc_url_raw( $url, array( 'https' ) );
}

/** Price records only exist when an editor has entered all verification metadata. */
function rmh_get_verified_price( int $post_id ): ?array {
    $intro = trim( (string) rmh_get_field( 'rmh_intro_price', $post_id, '' ) );
    $renewal = trim( (string) rmh_get_field( 'rmh_renewal_price', $post_id, '' ) );
    $date = rmh_normalize_date( rmh_get_field( 'rmh_pricing_verified_date', $post_id, '' ) );
    $source = rmh_non_affiliate_source_url( rmh_get_field( 'rmh_pricing_source_url', $post_id, '' ) );
    $confirmed = (string) rmh_get_field( 'rmh_pricing_source_confirmed', $post_id, '' );
    if ( ( '' === $intro && '' === $renewal ) || '' === $date || $date > gmdate( 'Y-m-d' ) || '' === $source || '1' !== $confirmed ) {
        return null;
    }
    $days = (int) floor( ( time() - strtotime( $date . ' 00:00:00 UTC' ) ) / DAY_IN_SECONDS );
    return array( 'intro' => $intro, 'renewal' => $renewal, 'date' => $date, 'source' => $source, 'stale' => $days > 90 );
}

/** Render an attributed price cell, with separate intro and renewal amounts. */
function rmh_price_cell( int $post_id ): string {
    $price = rmh_get_verified_price( $post_id );
    if ( null === $price ) { return esc_html__( 'Price not verified', 'readmehub-core' ); }
    $html = '<span>' . esc_html__( 'Intro:', 'readmehub-core' ) . ' ' . esc_html( '' !== $price['intro'] ? $price['intro'] : __( 'Not verified', 'readmehub-core' ) ) . '</span><br>';
    $html .= '<span>' . esc_html__( 'Renewal:', 'readmehub-core' ) . ' ' . esc_html( '' !== $price['renewal'] ? $price['renewal'] : __( 'Not verified', 'readmehub-core' ) ) . '</span><br>';
    $html .= esc_html( sprintf( __( 'Pricing verified %s', 'readmehub-core' ), $price['date'] ) );
    if ( $price['stale'] ) { $html .= ' — ' . esc_html__( 're-verification due', 'readmehub-core' ); }
    return $html . ' <a href="' . esc_url( $price['source'] ) . '" rel="noopener">' . esc_html__( 'Vendor pricing source', 'readmehub-core' ) . '</a>';
}

/** A verified offer state derived from explicit editorial data and UTC dates. */
function rmh_get_deal_state( int $id ): ?array {
    $status = (string) rmh_get_field( 'rmh_deal_status', $id, '' );
    $verified = rmh_normalize_date( rmh_get_field( 'rmh_deal_last_verified', $id, '' ) );
    $source = rmh_non_affiliate_source_url( rmh_get_field( 'rmh_deal_source_url', $id, '' ) );
    $confirmed = (string) rmh_get_field( 'rmh_deal_source_confirmed', $id, '' );
    $start_raw = (string) rmh_get_field( 'rmh_deal_starts', $id, '' );
    $end_raw = (string) rmh_get_field( 'rmh_deal_expires', $id, '' );
    $start = rmh_normalize_date( $start_raw );
    $end = rmh_normalize_date( $end_raw );
    $today = gmdate( 'Y-m-d' );
    if ( ! in_array( $status, array( 'active', 'upcoming', 'expired' ), true ) || '' === $source || '1' !== $confirmed
        || '' === $verified || $verified > $today || ( '' !== $start_raw && '' === $start ) || ( '' !== $end_raw && '' === $end )
        || ( '' !== $start && '' !== $end && $start > $end ) ) { return null; }
    if ( '' !== $end && $end < $today ) { $status = 'expired'; }
    elseif ( '' !== $start && $start > $today ) { $status = 'upcoming'; }
    elseif ( 'upcoming' === $status ) { return null; } // Editor must confirm activation.
    if ( 'expired' !== $status && strtotime( $verified . ' +90 days UTC' ) < strtotime( $today . ' UTC' ) ) { return null; }
    return array( 'status' => $status, 'verified' => $verified, 'source' => $source, 'expires' => $end,
        'stale' => strtotime( $verified . ' +90 days UTC' ) < strtotime( $today . ' UTC' ) );
}

/** All published deal candidates; actual eligibility is determined in PHP. */
function rmh_get_verified_deals(): array {
    $ids = get_posts( array( 'post_type' => array( 'post', 'page' ), 'post_status' => 'publish',
        'posts_per_page' => -1, 'fields' => 'ids', 'no_found_rows' => true,
        'meta_key' => 'rmh_page_template_type', 'meta_value' => 'T12' ) );
    $deals = array();
    foreach ( $ids as $id ) {
        $deal = rmh_get_deal_state( (int) $id );
        if ( null !== $deal ) { $deal['id'] = (int) $id; $deals[] = $deal; }
    }
    $order = array( 'active' => 0, 'upcoming' => 1, 'expired' => 2 );
    usort( $deals, function ( $a, $b ) use ( $order ) {
        return $order[$a['status']] <=> $order[$b['status']]
            ?: strcmp( $a['expires'] ?: '9999-12-31', $b['expires'] ?: '9999-12-31' )
            ?: $a['id'] <=> $b['id'];
    } );
    return $deals;
}

/** Hands-on claim gate: attached dated log, screenshot and explicit editor review. */
function rmh_verified_test_evidence( int $id ): ?array {
    if ( 'mode_b_tested' !== (string) rmh_get_field( 'rmh_evidence_mode', $id, '' ) || '1' !== (string) rmh_get_field( 'rmh_evidence_verified', $id, '' ) ) { return null; }
    $plan = trim( (string) rmh_get_field( 'rmh_plan_tested', $id, '' ) );
    $date = rmh_normalize_date( rmh_get_field( 'rmh_test_date', $id, '' ) );
    $workflow = trim( (string) rmh_get_field( 'rmh_workflow_evaluated', $id, '' ) );
    $limits = trim( (string) rmh_get_field( 'rmh_observed_limits', $id, '' ) );
    $log_id = absint( rmh_get_field( 'rmh_test_log_attachment', $id, 0 ) );
    $image_id = absint( rmh_get_field( 'rmh_test_screenshot_attachment', $id, 0 ) );
    if ( '' === $plan || '' === $date || $date > gmdate( 'Y-m-d' ) || '' === $workflow || '' === $limits
        || 'attachment' !== get_post_type( $log_id ) || 'attachment' !== get_post_type( $image_id )
        || ! wp_attachment_is_image( $image_id ) || ! get_attached_file( $log_id ) || ! get_attached_file( $image_id )
        || ! is_file( get_attached_file( $log_id ) ) || ! is_file( get_attached_file( $image_id ) ) ) { return null; }
    return array( 'plan' => $plan, 'date' => $date, 'workflow' => $workflow, 'limits' => $limits );
}

/** Free-plan claim uses the same verified source and date as pricing. */
function rmh_free_plan_cell( int $post_id ): string {
    $price = rmh_get_verified_price( $post_id );
    $value = (string) rmh_get_field( 'rmh_free_plan_available', $post_id, '' );
    if ( ! $price || ! in_array( $value, array( 'yes', 'no' ), true ) ) { return esc_html__( 'Not verified', 'readmehub-core' ); }
    $label = 'yes' === $value ? __( 'Yes', 'readmehub-core' ) : __( 'No', 'readmehub-core' );
    $label .= ' · ' . sprintf( __( 'Verified %s', 'readmehub-core' ), $price['date'] );
    if ( $price['stale'] ) { $label .= ' · ' . __( 'Re-verification due', 'readmehub-core' ); }
    return esc_html( $label ) . ' <a href="' . esc_url( $price['source'] ) . '" rel="noopener">' . esc_html__( 'Source', 'readmehub-core' ) . '</a>';
}
