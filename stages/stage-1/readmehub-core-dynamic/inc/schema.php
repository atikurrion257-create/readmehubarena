<?php
/**
 * Consolidated JSON-LD @graph builder (Part 9).
 * Mode A (research draft) emits only Organization, WebSite, BreadcrumbList,
 * WebPage, Article, and FAQPage (when visible). Zero Review, reviewRating,
 * AggregateRating, or Offer.price nodes until dated Stage-1 test logs exist.
 *
 * @package ReadMeHubCoreDynamic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the ReadMeHub @graph array for the current request.
 *
 * @return array<string, mixed>
 */
function rmh_build_jsonld_graph(): array {
	$home_url = home_url( '/' );
	$site_name = (string) get_option( 'blogname', 'ReadMeHub' );
	if ( '' === $site_name ) {
		$site_name = 'ReadMeHub';
	}

	$graph = array();

	$graph[] = array(
		'@type' => 'Organization',
		'@id'   => $home_url . '#organization',
		'name'  => $site_name,
		'url'   => $home_url,
	);

	$graph[] = array(
		'@type' => 'WebSite',
		'@id'   => $home_url . '#website',
		'url'   => $home_url,
		'name'  => $site_name,
		'publisher' => array(
			'@id' => $home_url . '#organization',
		),
		'inLanguage' => get_bloginfo( 'language' ),
	);

	// FAQ schema is intentionally omitted; visible FAQ remains available to readers.

	// WebPage node for singular pages/posts.
	if ( is_singular() ) {
		$post_id = (int) get_queried_object_id();
		$post = get_post( $post_id );
		if ( $post ) {
			// Only emit breadcrumbs when the page explicitly renders this shortcode.
			if ( has_shortcode( $post->post_content, 'rmh_breadcrumbs' ) ) {
				$crumbs = rmh_get_breadcrumb_trail(); $items = array();
				foreach ( $crumbs as $index => $crumb ) {
					$item = array( '@type' => 'ListItem', 'position' => $index + 1, 'name' => $crumb['label'] );
					if ( ! empty( $crumb['url'] ) ) { $item['item'] = $crumb['url']; }
					$items[] = $item;
				}
				if ( count( $items ) > 1 ) { $graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $items ); }
			}
			$webpage = array(
				'@type'       => 'WebPage',
				'@id'         => get_permalink( $post_id ) . '#webpage',
				'url'         => get_permalink( $post_id ),
				'name'        => (string) $post->post_title,
				'isPartOf'    => array( '@id' => $home_url . '#website' ),
				'about'       => array( '@id' => $home_url . '#organization' ),
				'inLanguage'  => get_bloginfo( 'language' ),
			);
			$graph[] = $webpage;

			// Article node for posts and informational pages.
			$template = (string) rmh_get_field( 'rmh_page_template_type', $post_id, '' );
			if ( 'post' === $post->post_type || in_array( $template, array( 'T3', 'T5', 'T8', 'T9', 'T10', 'T12' ), true ) ) {
				$author_name = (string) rmh_get_field( 'rmh_author_name', $post_id, '' );
				if ( '' === $author_name ) {
					$author_name = (string) rmh_get_setting( 'default_author_name' );
				}
				$article = array(
					'@type'         => 'Article',
					'headline'      => (string) $post->post_title,
					'url'           => get_permalink( $post_id ),
					'publisher'     => array( '@id' => $home_url . '#organization' ),
						);
				if ( '' !== trim( $author_name ) && ( has_shortcode( $post->post_content, 'rmh_author_strip' ) || has_shortcode( $post->post_content, 'rmh_author_box' ) ) ) { $article['author'] = array( '@type' => 'Person', 'name' => $author_name ); }
				$graph[] = $article;
			}
			// A Review is allowed only if the evidence stamp and reviewed product name
			// are visible, and the explicit attached-evidence gate passes.
			$item_name = trim( (string) rmh_get_field( 'rmh_review_item_name', $post_id, '' ) );
			if ( 'T3' === $template && '' !== $item_name && has_shortcode( $post->post_content, 'rmh_evidence_stamp' ) && rmh_verified_test_evidence( $post_id ) ) {
				$review = array( '@type' => 'Review', 'itemReviewed' => array( '@type' => 'SoftwareApplication', 'name' => $item_name ) );
				if ( '' !== trim( $author_name ?? '' ) && has_shortcode( $post->post_content, 'rmh_author_strip' ) ) { $review['author'] = array( '@type' => 'Person', 'name' => $author_name ); }
				// Exact bare shortcode guarantees the page-level lists are what render.
				if ( false !== strpos( $post->post_content, '[rmh_pros_cons]' ) ) {
					foreach ( array( 'positiveNotes' => 'rmh_pros_list', 'negativeNotes' => 'rmh_cons_list' ) as $key => $field ) {
						$lines = rmh_parse_lines( rmh_get_field( $field, $post_id, '' ) );
						if ( $lines ) { $review[$key] = array( '@type' => 'ItemList', 'itemListElement' => array_map( function ( $text, $index ) { return array( '@type' => 'ListItem', 'position' => $index + 1, 'name' => $text ); }, $lines, array_keys( $lines ) ) ); }
					}
				}
				$graph[] = $review;
			}
		}
	}


	return array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);
}

/**
 * Preferred ownership: extend Rank Math's JSON-LD output into the single @graph.
 *
 * @param array<string, mixed> $data Rank Math JSON-LD data.
 * @return array<string, mixed>
 */
function rmh_extend_rank_math_jsonld( array $data ): array {
    if ( 'standalone_fallback' === (string) rmh_get_setting( 'schema_ownership_mode' ) ) { return array(); }
    // Rank Math wraps these nodes in its own @graph. Replace its nodes to prevent
    // duplicate entities and unsupported review/rating schema in research mode.
    $graph = rmh_build_jsonld_graph();
    return $graph['@graph'];
}
add_filter( 'rank_math/json_ld', 'rmh_extend_rank_math_jsonld', 999 );

/**
 * Print the standalone @graph on wp_head only in fallback mode (never both).
 */
function rmh_print_standalone_jsonld(): void {
	$mode = (string) rmh_get_setting( 'schema_ownership_mode' );
	if ( 'standalone_fallback' === $mode || ! defined( 'RANK_MATH_VERSION' ) ) {
		$graph = rmh_build_jsonld_graph();
		echo '<script type="application/ld+json">' . wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'rmh_print_standalone_jsonld', 30 );
