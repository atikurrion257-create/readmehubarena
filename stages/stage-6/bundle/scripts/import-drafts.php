<?php
/**
 * Offline Stage 6 importer: WP-CLI eval-file wrapper and testable implementation.
 * Safe only for isolated, owner-approved staging with a backup. No publish or update path.
 */
function rmh6_error($code, $message) { return new WP_Error($code, $message); }
function rmh6_find_child($parent, $slug) {
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare("SELECT ID, post_status FROM {$wpdb->posts} WHERE post_type = %s AND post_parent = %d AND post_name = %s ORDER BY ID", 'page', $parent, $slug));
}
function rmh6_import($manifest_path, $mode, $expected_home) {
    if (!in_array($mode, array('plan','apply'), true)) return rmh6_error('mode', 'Mode must be plan or apply.');
    if (is_multisite()) return rmh6_error('multisite', 'Multisite is not supported; do not run this importer.');
    $home = untrailingslashit(home_url('/')); $site = untrailingslashit(site_url('/'));
    if (untrailingslashit($expected_home) !== $home) return rmh6_error('home_mismatch', 'Expected staging home does not exactly match WordPress home URL.');
    $home_host = strtolower((string) wp_parse_url($home, PHP_URL_HOST)); $site_host = strtolower((string) wp_parse_url($site, PHP_URL_HOST));
    if (!$home_host || !$site_host || $home_host !== $site_host) return rmh6_error('host_mismatch', 'Home and site host must match on isolated staging.');
    if (in_array($home_host, array('readmehub.com','www.readmehub.com'), true) || in_array($site_host, array('readmehub.com','www.readmehub.com'), true)) return rmh6_error('production', 'Production host is prohibited.');
    if ((string) get_option('blog_public') !== '0') return rmh6_error('indexing', 'Staging must have blog_public=0 before import.');
    if (!function_exists('acf_get_field') || !post_type_exists('rmh_tool')) return rmh6_error('dependencies', 'Activate ACF Free and the bundled ReadMeHub plugin on staging first.');
    $raw = file_get_contents($manifest_path); $manifest = json_decode((string)$raw, true);
    if (!is_array($manifest) || ($manifest['version'] ?? null) !== 1 || !is_array($manifest['pages'] ?? null)) return rmh6_error('manifest', 'Manifest invalid.');
    $pages = $manifest['pages']; $seen = array(); $existing = array('/'=>0); $plans = array();
    foreach ($pages as $item) {
        $path = $item['draft_path'] ?? '';
        if (!is_string($path) || !preg_match('~^/(?:[a-z0-9-]+/)+$~', $path) || isset($seen[$path])) return rmh6_error('manifest_path', 'Duplicate or invalid draft path.');
        if (($item['publish_blocked'] ?? false) !== true || !is_string($item['content'] ?? null) || !is_array($item['acf'] ?? null) || !is_array($item['acf_field_keys'] ?? null)) return rmh6_error('manifest_item', 'Invalid draft item: '.$path);
        $source = $item['source'] ?? '';
        if ($source !== 'assembly/intermediate-parent') {
            if (!preg_match('~^content/stage-[2-5]/pages/[a-z0-9-]+\.md$~', $source)) return rmh6_error('source_path', 'Invalid source path: '.$path);
            $source_file = dirname(__DIR__).'/'.$source;
            if (!is_file($source_file) || !hash_equals((string)($item['source_sha256'] ?? ''), hash_file('sha256', $source_file))) return rmh6_error('source_hash', 'Source file mismatch for '.$path.'; rebuild/review the manifest.');
        }
        foreach ($item['acf'] as $field => $value) {
            $key = $item['acf_field_keys'][$field] ?? '';
            $definition = $key ? acf_get_field($key) : false;
            if (!$definition || ($definition['name'] ?? '') !== $field) return rmh6_error('acf_fields', 'ACF field missing or mismatched: '.$field.'; import bundled ACF groups first.');
        }
        $seen[$path] = true;
        $parts = explode('/', trim($path, '/')); $slug = array_pop($parts); $parent_path = $parts ? '/'.implode('/',$parts).'/' : '/';
        if (!array_key_exists($parent_path, $existing)) return rmh6_error('missing_parent', 'Manifest parent missing or not ordered: '.$parent_path);
        $parent_id = $existing[$parent_path];
        if ($parent_id === null) { $existing[$path] = null; $plans[] = array('path'=>$path,'action'=>'create'); continue; }
        $found = rmh6_find_child($parent_id, $slug);
        if (count($found) > 1) return rmh6_error('collision', 'Multiple existing pages at '.$path.'; no changes made.');
        if (count($found) === 1) {
            $id = (int)$found[0]->ID;
            if ($found[0]->post_status !== 'draft' || get_post_meta($id, '_rmh6_draft_path', true) !== $path) return rmh6_error('collision', 'Existing non-owned or non-draft page at '.$path.'; no changes made.');
            $existing[$path] = $id; $plans[] = array('path'=>$path,'action'=>'skip-owned-draft','id'=>$id);
        } else { $existing[$path] = null; $plans[] = array('path'=>$path,'action'=>'create'); }
    }
    if ($mode === 'plan') return array('mode'=>'plan','home'=>$home,'actions'=>$plans,'created'=>array());
    $ids = array('/'=>0); $created = array();
    foreach ($pages as $i=>$item) {
        $path = $item['draft_path']; $parts = explode('/', trim($path, '/')); $slug = array_pop($parts); $parent_path = $parts ? '/'.implode('/',$parts).'/' : '/'; $parent = $ids[$parent_path];
        if ($plans[$i]['action'] === 'skip-owned-draft') { $ids[$path] = $plans[$i]['id']; continue; }
        // Recheck immediately before each insertion to reduce races; concurrent editors remain a reason for isolated staging.
        if (count(rmh6_find_child($parent, $slug))) return rmh6_error('race', 'A page appeared at '.$path.' during import. Earlier new drafts remain; inspect the import report and backup.');
        $id = wp_insert_post(array('post_type'=>'page','post_status'=>'draft','post_name'=>$slug,'post_title'=>$item['post_title'],'post_parent'=>$parent,'post_content'=>$item['content']), true);
        if (is_wp_error($id)) return rmh6_error('insert', 'Insert failed at '.$path.': '.$id->get_error_message().'. Earlier new drafts remain; inspect backup.');
        $id = (int)$id; $created[] = array('id'=>$id,'path'=>$path); $post = get_post($id);
        if (!$post || $post->post_status !== 'draft' || $post->post_name !== $slug || (int)$post->post_parent !== $parent) return rmh6_error('postcondition', 'Post insert modified by another plugin at ID '.$id.'; stop and inspect immediately.');
        update_post_meta($id, '_rmh6_draft_path', $path);
        update_post_meta($id, '_rmh6_source', $item['source']);
        update_post_meta($id, '_rmh6_source_sha256', $item['source_sha256']);
        update_post_meta($id, '_rmh6_registry_url', $item['registry_url'] ?? '');
        update_post_meta($id, '_rmh6_publish_blocked', '1');
        if ($item['stage'] === 3) update_post_meta($id, '_rmh6_elementor_scaffold', '1');
        foreach ($item['acf'] as $field=>$value) { update_post_meta($id, $field, $value); update_post_meta($id, '_'.$field, $item['acf_field_keys'][$field]); }
        if ($item['seo_title']) update_post_meta($id, 'rank_math_title', $item['seo_title']);
        if ($item['seo_description']) update_post_meta($id, 'rank_math_description', $item['seo_description']);
        update_post_meta($id, 'rank_math_robots', array('noindex','nofollow'));
        $ids[$path] = $id;
    }
    return array('mode'=>'apply','home'=>$home,'actions'=>$plans,'created'=>$created);
}
// WP-CLI eval-file provides positional arguments in $args. Include from a test without invoking this wrapper.
if (defined('WP_CLI') && WP_CLI && !defined('RMH6_TEST_MODE')) {
    $mode = $args[0] ?? ''; $target = $args[1] ?? '';
    if (!$target) WP_CLI::error('Usage: wp eval-file scripts/import-drafts.php <plan|apply> <exact-staging-home-url>');
    $result = rmh6_import(dirname(__DIR__).'/content/draft-manifest.json', $mode, $target);
    if (is_wp_error($result)) WP_CLI::error($result->get_error_message());
    WP_CLI::log(wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
