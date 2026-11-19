<?php
// Unit tests with isolated WP stubs; run in PHP CLI, not a WP integration test.
define( 'ABSPATH', __DIR__ );
define( 'DAY_IN_SECONDS', 86400 );
$values = array();
function wp_parse_url( $url, $part = -1 ) { return parse_url( $url, $part ); }
function esc_url_raw( $url, $protocols = null ) { return $url; }
function rmh_get_field( $key, $id, $fallback = '' ) { global $values; return $values[$key] ?? $fallback; }
require __DIR__ . '/../readmehub-core-dynamic/inc/data.php';
$tests = array();
function check_case( $label, $ok ) { global $tests; $tests[] = $ok; echo ($ok ? 'PASS ' : 'FAIL ') . $label . "\n"; }
check_case( 'ACF stored date normalized', rmh_normalize_date('20260131') === '2026-01-31' );
check_case( 'Invalid date rejected', rmh_normalize_date('2026-02-31') === '' );
check_case( 'Tracking query rejected', rmh_non_affiliate_source_url('https://example.com/plans?ref=123') === '' );
check_case( 'Credentials rejected', rmh_non_affiliate_source_url('https://user:pass@example.com/') === '' );
check_case( 'HTTP rejected', rmh_non_affiliate_source_url('http://example.com/') === '' );
$values = array( 'rmh_intro_price' => '$10/month', 'rmh_pricing_verified_date' => gmdate('Ymd'), 'rmh_pricing_source_url' => 'https://example.com/pricing/', 'rmh_pricing_source_confirmed' => '1' );
$price = rmh_get_verified_price( 1 );
check_case( 'Complete source and date accepted', is_array($price) && $price['intro'] === '$10/month' && !$price['stale'] );
$values['rmh_pricing_source_confirmed'] = '0';
check_case( 'Unconfirmed source withheld', rmh_get_verified_price(1) === null );
$values['rmh_pricing_source_confirmed'] = '1';
$values['rmh_pricing_verified_date'] = gmdate('Ymd', time() - 100 * DAY_IN_SECONDS);
check_case( 'Old price marked stale', rmh_get_verified_price(1)['stale'] === true );
$values['rmh_pricing_verified_date'] = gmdate('Ymd', time() + 2 * DAY_IN_SECONDS);
check_case( 'Future verification rejected', rmh_get_verified_price(1) === null );
exit( in_array( false, $tests, true ) ? 1 : 0 );
