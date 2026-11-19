<?php
/**
 * Synced Gutenberg block patterns for ReadMeHub reusable sections.
 * Patterns are shared across Elementor and Gutenberg pages so disclosure,
 * pros/cons, FAQ, newsletter, and author strips stay identical everywhere.
 *
 * @package ReadMeHubCoreDynamic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the ReadMeHub synced block pattern category.
 */
function rmh_register_block_pattern_category(): void {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}
	register_block_pattern_category(
		'readmehub',
		array(
			'label'       => __( 'ReadMeHub', 'readmehub-core' ),
			'description' => __( 'Reusable ReadMeHub sections (disclosure, pros/cons, FAQ, newsletter, author).', 'readmehub-core' ),
		)
	);
}
add_action( 'init', 'rmh_register_block_pattern_category' );

/**
 * Register synced block patterns.
 */
function rmh_register_block_patterns(): void {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern(
		'readmehub/disclosure',
		array(
			'title'       => __( 'RMH Disclosure', 'readmehub-core' ),
			'description' => __( 'FTC affiliate disclosure strip (above first affiliate CTA).', 'readmehub-core' ),
			'categories'  => array( 'readmehub' ),
			'keywords'    => array( 'disclosure', 'ftc', 'affiliate' ),
			'content'     => '<!-- wp:shortcode -->[rmh_disclosure]<!-- /wp:shortcode -->',
		)
	);

	register_block_pattern(
		'readmehub/pros-cons',
		array(
			'title'       => __( 'RMH Pros & Cons', 'readmehub-core' ),
			'description' => __( 'Pros/cons two-column block for a tool record.', 'readmehub-core' ),
			'categories'  => array( 'readmehub' ),
			'keywords'    => array( 'pros', 'cons', 'verdict' ),
			'content'     => '<!-- wp:shortcode -->[rmh_pros_cons]<!-- /wp:shortcode -->',
		)
	);

	register_block_pattern(
		'readmehub/faq',
		array(
			'title'       => __( 'RMH FAQ', 'readmehub-core' ),
			'description' => __( 'Visible buyer FAQ accordion with FAQPage JSON-LD mirroring.', 'readmehub-core' ),
			'categories'  => array( 'readmehub' ),
			'keywords'    => array( 'faq', 'questions' ),
			'content'     => '<!-- wp:shortcode -->[rmh_faq]<!-- /wp:shortcode -->',
		)
	);

	register_block_pattern(
		'readmehub/newsletter',
		array(
			'title'       => __( 'RMH Newsletter', 'readmehub-core' ),
			'description' => __( 'Quarterly audit alert signup section.', 'readmehub-core' ),
			'categories'  => array( 'readmehub' ),
			'keywords'    => array( 'newsletter', 'email', 'subscribe' ),
			'content'     => '<!-- wp:shortcode -->[rmh_newsletter]<!-- /wp:shortcode -->',
		)
	);

	register_block_pattern(
		'readmehub/author-box',
		array(
			'title'       => __( 'RMH Author Box', 'readmehub-core' ),
			'description' => __( 'E-E-A-T author strip (real names only).', 'readmehub-core' ),
			'categories'  => array( 'readmehub' ),
			'keywords'    => array( 'author', 'e-e-a-t', 'byline' ),
			'content'     => '<!-- wp:shortcode -->[rmh_author_box]<!-- /wp:shortcode -->',
		)
	);
}
add_action( 'init', 'rmh_register_block_patterns' );

/**
 * Explicitly provision synced patterns as wp_block posts. Never runs on activation.
 * Existing posts with our unique marker are left untouched, including owner edits.
 * A wp_block must be published to be available as a synced pattern; this is a
 * persistent site write and requires staging / change approval.
 *
 * @return array{created:int,existing:int,failed:int}
 */
function rmh_provision_synced_templates(): array {
    $files = array(
        't3-review' => 'T3-review.html',
        't5-informational' => 'T5-informational.html',
        't8-comparison' => 'T8-comparison.html',
        't9-roundup' => 'T9-roundup.html',
        't10-alternatives' => 'T10-alternatives.html',
        't12-deal' => 'T12-deal.html',
    );
    $result = array( 'created' => 0, 'existing' => 0, 'failed' => 0 );
    foreach ( $files as $key => $filename ) {
        $existing = get_posts( array(
            'post_type' => 'wp_block', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
            'posts_per_page' => 1, 'fields' => 'ids', 'no_found_rows' => true,
            'meta_key' => '_rmh_synced_template_key', 'meta_value' => $key,
        ) );
        if ( ! empty( $existing ) ) { ++$result['existing']; continue; }
        $path = RMH_CORE_PATH . 'patterns/' . $filename;
        $content = is_readable( $path ) ? file_get_contents( $path ) : false;
        if ( false === $content || '' === trim( $content ) ) { ++$result['failed']; continue; }
        $id = wp_insert_post( array(
            'post_type' => 'wp_block', 'post_status' => 'publish',
            'post_title' => 'ReadMeHub ' . strtoupper( $key ) . ' skeleton',
            'post_content' => $content,
            'meta_input' => array( '_rmh_synced_template_key' => $key ),
        ), true );
        if ( is_wp_error( $id ) || ! $id ) { ++$result['failed']; } else { ++$result['created']; }
    }
    return $result;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command( 'rmh provision-synced-patterns', function () {
        $result = rmh_provision_synced_templates();
        WP_CLI::log( sprintf( 'Created %d; preserved %d; failed %d.', $result['created'], $result['existing'], $result['failed'] ) );
        if ( $result['failed'] ) { WP_CLI::error( 'Pattern provisioning incomplete.' ); }
        WP_CLI::success( 'Synced skeletons provisioned; verify in the block editor before use.' );
    } );
}
