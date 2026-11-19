<?php
// Unit test of repeat-provision behavior with stubbed WP storage.
define( 'ABSPATH', __DIR__ );
define( 'RMH_CORE_PATH', __DIR__ . '/../readmehub-core-dynamic/' );
$posts = array();
function add_action(...$args) {}
function get_posts($args) { global $posts; foreach ($posts as $id => $p) { if ($p['meta_input']['_rmh_synced_template_key'] === $args['meta_value']) return array($id); } return array(); }
function wp_insert_post($post, $error = false) { global $posts; $id = count($posts) + 1; $posts[$id] = $post; return $id; }
function is_wp_error($x) { return false; }
require __DIR__ . '/../readmehub-core-dynamic/inc/block-patterns.php';
$first = rmh_provision_synced_templates();
$posts[1]['post_content'] = 'owner modification';
$second = rmh_provision_synced_templates();
$ok = $first === array('created'=>6,'existing'=>0,'failed'=>0) && $second === array('created'=>0,'existing'=>6,'failed'=>0) && $posts[1]['post_content'] === 'owner modification';
echo ($ok ? 'PASS' : 'FAIL') . " pattern provisioning preserves existing blocks on repeat\n";
exit($ok ? 0 : 1);
