<?php
// Minimal WordPress stubs to exercise the URL gate; not a WP integration test.
define('ABSPATH', __DIR__);
define('DAY_IN_SECONDS', 86400);
function add_shortcode(...$args) {}
function wp_parse_url($url, $component = -1) { return parse_url($url, $component); }
function esc_url_raw($url) { return $url; }
require __DIR__ . '/../readmehub-core-dynamic/inc/data.php';
require __DIR__ . '/../readmehub-core-dynamic/inc/shortcodes.php';
$result = rmh_run_affiliate_url_self_tests();
foreach ($result['assertions'] as $assertion) {
    echo ($assertion['passed'] ? 'PASS ' : 'FAIL ') . $assertion['label'] . "\n";
}
exit($result['all_passed'] ? 0 : 1);
