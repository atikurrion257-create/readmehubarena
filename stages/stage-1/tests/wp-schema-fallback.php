<?php
require '/wordpress/wp-load.php';
$results=array();
try {
 update_option('rmh_core_settings',array('schema_ownership_mode'=>'standalone_fallback'));
 $id=wp_insert_post(array('post_title'=>'Fallback synthetic','post_type'=>'page','post_status'=>'publish'));
 $GLOBALS['wp_query']=new WP_Query(array('page_id'=>$id)); $GLOBALS['wp_query']->the_post();
 if (!has_action('rank_math/head') && class_exists('RankMath\\Schema\\JsonLD')) { (new \RankMath\Schema\JsonLD())->setup(); }
 ob_start(); do_action('rank_math/head'); rmh_print_standalone_jsonld(); $out=ob_get_clean();
 preg_match_all('~<script[^>]*type="application/ld\\+json"[^>]*>(.*?)</script>~s',$out,$matches);
 $data = count($matches[1]) === 1 ? json_decode($matches[1][0],true) : null;
 $results[]=array('name'=>'Fallback suppresses Rank Math and emits exactly one custom graph','pass'=>is_array($data) && isset($data['@graph']));
} catch(Throwable $e) { $results[]=array('name'=>'Exception: '.$e->getMessage(),'pass'=>false); }
file_put_contents('/stage/tests/wp-schema-fallback-result.json',json_encode(array('wp'=>get_bloginfo('version'),'php'=>PHP_VERSION,'rank_math'=>defined('RANK_MATH_VERSION')?RANK_MATH_VERSION:'missing','results'=>$results),JSON_PRETTY_PRINT));
