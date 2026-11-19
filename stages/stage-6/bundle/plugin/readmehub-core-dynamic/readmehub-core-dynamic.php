<?php
/**
 * Plugin Name:       ReadMeHub Core Dynamic
 * Plugin URI:        https://www.readmehub.com/
 * Description:       Zero-bloat dynamic data layer for ReadMeHub (Model D Silo'd Multi-Vertical Authority Hub): rmh_tool CPT, silo/cluster taxonomies, FTC-compliant [rmh_*] shortcodes, Systeme.io no-www enforcement, consolidated JSON-LD @graph, synced Gutenberg block patterns, and Audit Ledger design tokens.
 * Version:           1.0.0
 * Requires at least: 6.8
 * Requires PHP:      8.1
 * Author:            ReadMeHub
 * Author URI:        https://www.readmehub.com/about/
 * License:           GPL-2.0-or-later
 * Text Domain:       readmehub-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RMH_CORE_VERSION', '1.0.0' );
define( 'RMH_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'RMH_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'RMH_BASELINE_VERIFIED_DATE', '2026-09-03' );
define( 'RMH_BASELINE_EXPIRY_DATE', '2026-12-02' );
define( 'RMH_FRESHNESS_MAX_DAYS', 90 );

/**
 * Unified ACF Free + native post_meta helper.
 * Ensures zero fatal errors when ACF Free is not yet installed or active.
 *
 * @param string   $key     ACF field name / meta key.
 * @param int|null $post_id Target post ID (defaults to current post).
 * @param mixed    $default Default fallback value when empty.
 * @return mixed
 */
function rmh_get_field( string $key, ?int $post_id = null, $default = '' ) {
	$resolved_id = $post_id ? $post_id : get_the_ID();
	if ( ! $resolved_id ) {
		return $default;
	}

	if ( function_exists( 'get_field' ) ) {
		$val = get_field( $key, $resolved_id );
		if ( null !== $val && '' !== $val && false !== $val ) {
			return $val;
		}
	}

	$meta = get_post_meta( $resolved_id, $key, true );
	if ( '' !== $meta && null !== $meta ) {
		return $meta;
	}

	return $default;
}

/**
 * Parse ACF Free one-item-per-line textarea values into a clean string array.
 *
 * @param mixed $raw Raw textarea string or array.
 * @return array<int, string>
 */
function rmh_parse_lines( $raw ): array {
	if ( is_array( $raw ) ) {
		$lines = $raw;
	} elseif ( is_string( $raw ) ) {
		$lines = preg_split( '/\r\n|\r|\n/', $raw ) ?: array();
	} else {
		return array();
	}

	$clean = array();
	foreach ( $lines as $line ) {
		if ( is_string( $line ) ) {
			// Strip leading markdown bullets if an editor pasted "- item" or "* item".
			$trimmed = trim( preg_replace( '/^\s*([-*•✓⚠]+)\s*/u', '', trim( $line ) ) ?? '' );
			if ( '' !== $trimmed ) {
				$clean[] = $trimmed;
			}
		}
	}
	return $clean;
}

/**
 * Retrieve plugin-wide settings stored in wp_options (replaces ACF Pro Options Page).
 *
 * @param string $key     Setting key.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function rmh_get_setting( string $key, $default = '' ) {
	$defaults = array(
		'default_disclosure'    => 'Disclosure: ReadMeHub is reader-supported. When you buy through links marked as sponsored on this page, we may earn an affiliate commission at no extra cost to you. Commissions never influence our evaluations, rankings, or verdicts, and we review tools regardless of whether they offer an affiliate program.',
		'disclosure_page_url'   => '/affiliate-disclosure/',
		'methodology_page_url'  => '/editorial-methodology/',
		'default_author_name'   => '',
		'default_author_role'   => 'Software & Hosting Research Desk',
		'newsletter_action_url' => '',
		'newsletter_heading'    => 'Get Price-Change & Re-Verification Alerts',
		'newsletter_subcopy'    => 'Quarterly audit summaries when SaaS plans, renewal rates, or free-tier limits change. No daily hype, unsubscribe anytime.',
		'schema_ownership_mode' => 'extend_rank_math',
		'retain_faq_schema'     => '1',
	);

	$saved = get_option( 'rmh_core_settings', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	if ( array_key_exists( $key, $saved ) && '' !== $saved[ $key ] ) {
		return $saved[ $key ];
	}

	return $defaults[ $key ] ?? $default;
}

require_once RMH_CORE_PATH . 'inc/data.php';
require_once RMH_CORE_PATH . 'inc/settings.php';
require_once RMH_CORE_PATH . 'inc/cpt-tax.php';
require_once RMH_CORE_PATH . 'inc/seed-tools.php';
require_once RMH_CORE_PATH . 'inc/shortcodes.php';
require_once RMH_CORE_PATH . 'inc/shortcodes-extra.php';
require_once RMH_CORE_PATH . 'inc/schema.php';
require_once RMH_CORE_PATH . 'inc/block-patterns.php';

/**
 * Enqueue lightweight Audit Ledger CSS tokens & GA4 click-tracking hooks.
 */
function rmh_enqueue_frontend_assets(): void {
	wp_enqueue_style(
		'rmh-components',
		RMH_CORE_URL . 'assets/rmh-components.css',
		array(),
		RMH_CORE_VERSION
	);

	wp_enqueue_script(
		'rmh-ga4',
		RMH_CORE_URL . 'assets/rmh-ga4.js',
		array(),
		RMH_CORE_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'rmh_enqueue_frontend_assets', 20 );

/**
 * Also load component styles in the Block Editor so synced patterns preview accurately.
 */
function rmh_enqueue_block_editor_assets(): void {
	wp_enqueue_style(
		'rmh-components-editor',
		RMH_CORE_URL . 'assets/rmh-components.css',
		array(),
		RMH_CORE_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'rmh_enqueue_block_editor_assets' );

/** Elementor 4.3.x dynamic-content filter for default-cache widgets.
 * An explicit Elementor "Active" widget override precedes this filter, so editors
 * MUST still set all dynamic widgets to Inactive. Do not use this as a substitute.
 */
function rmh_mark_elementor_dynamic( $dynamic, $raw_data, $element ) {
    if ( ! is_array( $raw_data ) ) { return $dynamic; }
    $settings = $raw_data['settings'] ?? array();
    $encoded = is_array( $settings ) ? wp_json_encode( $settings ) : '';
    return $dynamic || ( is_string( $encoded ) && false !== strpos( $encoded, '[rmh_' ) );
}
add_filter( 'elementor/element/is_dynamic_content', 'rmh_mark_elementor_dynamic', 10, 3 );

/**
 * Plugin activation hook: register CPT/taxonomies and flush rewrite rules.
 */
function rmh_core_activate(): void {
	rmh_register_cpt_and_taxonomies();
	rmh_register_small_business_hosting_rewrites();

	// Site identity changes require a separate, explicitly approved operation.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'rmh_core_activate' );

/**
 * Flush rewrite rules on deactivation.
 */
function rmh_core_deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'rmh_core_deactivate' );

/**
 * Register WP-CLI utility commands when running under WP-CLI.
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'rmh seed-tools',
		function () {
			$summary = rmh_seed_all_tool_records( false );
			WP_CLI::success(
				sprintf(
					'Seeded rmh_tool registry: %d created, %d updated, %d skipped.',
					$summary['created'],
					$summary['updated'],
					$summary['skipped']
				)
			);
		}
	);

	WP_CLI::add_command(
		'rmh fix-site-title',
		function () {
			update_option( 'blogname', 'ReadMeHub' );
			WP_CLI::success( 'Updated blogname to "ReadMeHub"; tagline unchanged.' );
		}
	);

	WP_CLI::add_command(
		'rmh test-url-sanitizer',
		function () {
			$results = rmh_run_affiliate_url_self_tests();
			foreach ( $results['assertions'] as $assertion ) {
				WP_CLI::log( ( $assertion['passed'] ? '[PASS] ' : '[FAIL] ' ) . $assertion['label'] );
			}
			if ( $results['all_passed'] ) {
				WP_CLI::success( 'All Systeme.io www-stripping and affiliate gate tests passed.' );
			} else {
				WP_CLI::error( 'One or more affiliate URL sanitizer assertions failed.' );
			}
		}
	);
}
