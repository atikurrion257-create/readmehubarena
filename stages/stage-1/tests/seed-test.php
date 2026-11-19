<?php
// In-memory seed test, not a WordPress integration test.
define('ABSPATH', __DIR__);
$posts = array(); $meta = array();
function rmh_seed_default_taxonomy_terms() {}
function get_posts($args) { global $posts; foreach ($posts as $id => $post) if ($post['post_name'] === $args['name']) return array($id); return array(); }
function wp_insert_post($post, $error = false) { global $posts; $id = count($posts) + 1; $posts[$id] = $post; return $id; }
function is_wp_error($x) { return false; }
function metadata_exists($type, $id, $key) { global $meta; return array_key_exists($key, $meta[$id] ?? array()); }
function get_post_meta($id, $key, $single = false) { global $meta; return $meta[$id][$key] ?? ''; }
function update_post_meta($id, $key, $value) { global $meta; $meta[$id][$key] = $value; }
function term_exists($term, $taxonomy) { return true; }
function has_term($term, $taxonomy, $id) { return false; }
function wp_set_object_terms(...$args) { return array(); }
require __DIR__ . '/../readmehub-core-dynamic/inc/seed-tools.php';
$first = rmh_seed_all_tool_records();
$meta[1]['rmh_approval_status'] = 'approved';
$meta[1]['rmh_affiliate_url_template'] = 'https://systeme.io/?sa=editor';
$second = rmh_seed_all_tool_records();
$ok = $first['created'] === 17 && $second['created'] === 0 && count($posts) === 17 && $meta[1]['rmh_approval_status'] === 'approved' && $meta[1]['rmh_affiliate_url_template'] === 'https://systeme.io/?sa=editor' && !isset($meta[1]['rmh_price_note']);
foreach ($posts as $post) { if ($post['post_status'] !== 'draft') $ok = false; }
echo ($ok ? 'PASS' : 'FAIL') . " 17 draft records and owner meta preserved on repeat\n";
exit($ok ? 0 : 1);
