<?php
require '/wordpress/wp-load.php';
$checks = array();
function schema_check($name,$value) { global $checks; $checks[]=array('name'=>$name,'pass'=>(bool)$value); }
try {
 $id=wp_insert_post(array('post_title'=>'Synthetic guide','post_name'=>'synthetic-guide','post_type'=>'page','post_status'=>'publish','post_content'=>'Visible text.'));
 update_post_meta($id,'rmh_page_template_type','T5');
 $GLOBALS['wp_query'] = new WP_Query(array('page_id'=>$id));
 $GLOBALS['wp_query']->the_post();
 if ( ! has_action('rank_math/head') && class_exists('RankMath\\Schema\\JsonLD') ) { (new \RankMath\Schema\JsonLD())->setup(); }
 ob_start(); do_action('rank_math/head'); rmh_print_standalone_jsonld(); $html=ob_get_clean();
 preg_match_all('~<script[^>]*type="application/ld\\+json"[^>]*>(.*?)</script>~s',$html,$matches);
 $graph = count($matches[1])===1 ? json_decode($matches[1][0],true) : null;
 schema_check('Rank Math active',defined('RANK_MATH_VERSION'));
 schema_check('Exactly one JSON-LD script',count($matches[1])===1);
 schema_check('Valid @graph',is_array($graph) && isset($graph['@graph']) && is_array($graph['@graph']));
 schema_check('Research graph contains no rating, review or offer',is_array($graph) && !preg_match('/reviewRating|aggregateRating|"Review"|"Offer"/',json_encode($graph)));
 schema_check('Graph includes page title',is_array($graph) && strpos(json_encode($graph),'Synthetic guide')!==false);
 $tools=wp_insert_post(array('post_title'=>'Tools directory','post_type'=>'page','post_name'=>'tools','post_status'=>'publish'));
 update_post_meta($tools,'rmh_page_template_type','T2');
 $GLOBALS['wp_query']=new WP_Query(array('page_id'=>$tools)); $GLOBALS['wp_query']->the_post();
 $robots=apply_filters('rank_math/frontend/robots',array('index'=>'index','follow'=>'follow'));
 schema_check('Thin tool directory gets Rank Math noindex',isset($robots['index']) && $robots['index']==='noindex');
 $entry=apply_filters('rank_math/sitemap/entry',array('loc'=>get_permalink($tools)),'post',get_post($tools));
 schema_check('Thin tool directory excluded from Rank Math sitemap',false === $entry);
 $plain=wp_insert_post(array('post_title'=>'Clean guide','post_type'=>'page','post_status'=>'publish','post_content'=>'Clean text.'));
 schema_check('Clean page remains in sitemap',false !== apply_filters('rank_math/sitemap/entry',array('loc'=>get_permalink($plain)),'post',get_post($plain)));

} catch(Throwable $e) {$checks[]=array('name'=>'Exception: '.$e->getMessage().' '.$e->getFile().':'.$e->getLine(),'pass'=>false);}
file_put_contents('/stage/tests/wp-schema-result.json',json_encode(array('wp'=>get_bloginfo('version'),'php'=>PHP_VERSION,'rank_math'=>defined('RANK_MATH_VERSION')?RANK_MATH_VERSION:'missing','results'=>$checks),JSON_PRETTY_PRINT));
