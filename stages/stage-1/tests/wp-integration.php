<?php
/** Disposable WordPress integration checks. Run only on isolated test sites. */
require_once '/wordpress/wp-load.php';
$results = array();
function rmh_check_it($name, $ok) { global $results; $results[] = array('name'=>$name, 'pass'=>(bool)$ok); }
try {
 rmh_check_it('WordPress and PHP versions', get_bloginfo('version') === '7.1.3' && version_compare(PHP_VERSION, '8.2', '>=') && version_compare(PHP_VERSION, '8.3', '<'));
 rmh_check_it('Plugin and ACF loaded', function_exists('rmh_seed_all_tool_records') && function_exists('acf_import_field_group'));
 $json = json_decode(file_get_contents('/stage/acf-export-readmehub.json'), true);
 foreach ($json as $group) { acf_import_field_group($group); }
 rmh_check_it('ACF groups imported', count(acf_get_field_groups()) >= 2);
 $seed = rmh_seed_all_tool_records();
 rmh_check_it('Seed creates 17 drafts', $seed['created']===17 && count(get_posts(array('post_type'=>'rmh_tool','post_status'=>'draft','posts_per_page'=>-1)))===17);
 $tool = rmh_find_tool_post_by_slug('systeme-io');
 update_post_meta($tool, 'rmh_approval_status', 'approved');
 update_post_meta($tool, 'rmh_affiliate_url_template', 'https://www.systeme.io/?sa=synthetic');
 update_post_meta($tool, 'rmh_verified_date', gmdate('Y-m-d'));
 update_post_meta($tool, 'rmh_expires_date', gmdate('Y-m-d',time()+86400));
 $seed2=rmh_seed_all_tool_records();
 rmh_check_it('Repeat seeding preserves approval and URL', $seed2['created']===0 && get_post_meta($tool,'rmh_approval_status',true)==='approved' && get_post_meta($tool,'rmh_affiliate_url_template',true)==='https://www.systeme.io/?sa=synthetic');
 $cta=do_shortcode('[rmh_affiliate_cta tool="systeme-io" placement="top"]');
 rmh_check_it('Affiliate shortcode resolves draft tool, strips www and marks link', strpos($cta,'https://systeme.io/?sa=synthetic')!==false && strpos($cta,'sponsored nofollow noopener')!==false && strpos($cta,'data-rmh-cta="top"')!==false);
 update_field('field_rmh_intro_price','$10/month',$tool);
 update_field('field_rmh_pricing_source_url','https://example.com/pricing',$tool);
 update_field('field_rmh_pricing_source_confirmed',1,$tool);
 update_field('field_rmh_pricing_verified_date',gmdate('Ymd'),$tool);
 $price=rmh_get_verified_price($tool);
 rmh_check_it('ACF price fields and date read correctly', is_array($price) && $price['intro']==='$10/month' && $price['date']===gmdate('Y-m-d'));
 $table=do_shortcode('[rmh_pricing_table tools="systeme-io"]');
 rmh_check_it('Pricing table includes sourced price', strpos($table,'$10/month')!==false && strpos($table,'example.com/pricing')!==false);
 $patterns1=rmh_provision_synced_templates(); $patterns2=rmh_provision_synced_templates();
 rmh_check_it('Six synced block records provision idempotently', $patterns1['created']===6 && $patterns2['existing']===6 && count(get_posts(array('post_type'=>'wp_block','post_status'=>'publish','posts_per_page'=>-1)))===6);
 $blocks=get_posts(array('post_type'=>'wp_block','post_status'=>'publish','posts_per_page'=>1)); $ref=$blocks[0]->ID;
 wp_update_post(array('ID'=>$ref,'post_content'=>'<!-- wp:paragraph --><p>Synthetic shared block</p><!-- /wp:paragraph -->'));
 $rendered=do_blocks('<!-- wp:block {"ref":'.$ref.'} /-->');
 rmh_check_it('WordPress renders synced block reference',strpos($rendered,'Synthetic shared block')!==false);
 $deal=wp_insert_post(array('post_title'=>'Synthetic deal','post_status'=>'publish','post_type'=>'page'));
 foreach(array('rmh_page_template_type'=>'T12','rmh_deal_status'=>'active','rmh_deal_last_verified'=>gmdate('Y-m-d'),'rmh_deal_source_url'=>'https://example.com/offer','rmh_deal_source_confirmed'=>'1','rmh_deal_expires'=>gmdate('Y-m-d',time()-86400)) as $k=>$v) update_post_meta($deal,$k,$v);
 $state=rmh_get_deal_state($deal);
 rmh_check_it('Expired date overrides active status', $state && $state['status']==='expired' && rmh_count_verified_active_deals()===0);
 update_field('field_rmh_deal_expires',gmdate('Ymd',time()+86400),$deal);
 rmh_check_it('Current verified deal counts as active', rmh_count_verified_active_deals()===1);
 $deal_state=rmh_get_deal_state($deal); rmh_check_it('Current deal is active and source attributed', $deal_state && $deal_state['status']==='active' && $deal_state['source']==='https://example.com/offer');
 $expired=wp_insert_post(array('post_title'=>'Synthetic expired deal','post_status'=>'publish','post_type'=>'page'));
 foreach(array('rmh_page_template_type'=>'T12','rmh_deal_status'=>'active','rmh_deal_last_verified'=>gmdate('Y-m-d'),'rmh_deal_source_url'=>'https://example.com/offer','rmh_deal_source_confirmed'=>'1','rmh_deal_expires'=>gmdate('Y-m-d',time()-86400)) as $k=>$v) update_post_meta($expired,$k,$v);
 $archive=wp_insert_post(array('post_title'=>'Deals archive','post_status'=>'publish','post_type'=>'page'));
 $GLOBALS['wp_query']=new WP_Query(array('page_id'=>$archive)); $GLOBALS['wp_query']->the_post(); $_GET['rmh_deal']='active';
 $list=do_shortcode('[rmh_deals_list limit="1"]');
 rmh_check_it('Active deal filter excludes expired offers',strpos($list,'Synthetic deal')!==false && strpos($list,'Synthetic expired deal')===false);
 $_GET['rmh_deal']='expired'; $list=do_shortcode('[rmh_deals_list]');
 rmh_check_it('Expired deal filter labels expired offers',strpos($list,'Synthetic expired deal')!==false && strpos($list,'Expired')!==false);
 unset($_GET['rmh_deal']);

 rmh_check_it('Taxonomies contain two silos and seven clusters', count(get_terms(array('taxonomy'=>'rmh_silo','hide_empty'=>false)))===2 && count(get_terms(array('taxonomy'=>'rmh_cluster','hide_empty'=>false)))===7);
 rmh_check_it('Affiliate disclosure precedes CTA', strpos($cta,'rmh-disclosure')!==false && strpos($cta,'rmh-disclosure') < strpos($cta,'data-rmh-cta'));
 $post=wp_insert_post(array('post_title'=>'Synthetic test','post_type'=>'page','post_status'=>'publish','post_content'=>'[rmh_evidence_stamp] [rmh_pros_cons]'));
 update_post_meta($post,'rmh_page_template_type','T3'); update_post_meta($post,'rmh_review_item_name','Synthetic product'); update_post_meta($post,'rmh_pros_list','Synthetic strength'); update_post_meta($post,'rmh_cons_list','Synthetic limitation');
 foreach (array('rmh_evidence_mode'=>'mode_b_tested','rmh_plan_tested'=>'Synthetic plan','rmh_test_date'=>gmdate('Y-m-d'),'rmh_workflow_evaluated'=>'Synthetic workflow','rmh_observed_limits'=>'Synthetic limitation','rmh_evidence_verified'=>'1') as $k=>$v) update_post_meta($post,$k,$v);
 rmh_check_it('Mode B without attachments fails closed', null === rmh_verified_test_evidence($post));
 $upload=wp_upload_dir(); wp_mkdir_p($upload['path']);
 $log=$upload['path'].'/synthetic-test-log.txt'; $image=$upload['path'].'/synthetic-test-image.png';
 file_put_contents($log,'Synthetic test only.'); file_put_contents($image,base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='));
 $log_id=wp_insert_attachment(array('post_title'=>'Synthetic log','post_mime_type'=>'text/plain'),$log);
 $image_id=wp_insert_attachment(array('post_title'=>'Synthetic image','post_mime_type'=>'image/png'),$image);
 update_field('field_rmh_test_log_attachment',$log_id,$post); update_field('field_rmh_test_screenshot_attachment',$image_id,$post);
 rmh_check_it('Mode B needs reviewed attached evidence', is_array(rmh_verified_test_evidence($post)));
 $GLOBALS['wp_query']=new WP_Query(array('page_id'=>$post)); $GLOBALS['wp_query']->the_post();
 $graph=json_encode(rmh_build_jsonld_graph());
 rmh_check_it('Mode B graph contains visible reviewed item and lists, no rating', strpos($graph,'"Review"')!==false && strpos($graph,'Synthetic product')!==false && strpos($graph,'Synthetic strength')!==false && strpos($graph,'reviewRating')===false);
 $base=wp_insert_post(array('post_title'=>'Hosting','post_name'=>'hosting','post_type'=>'page','post_status'=>'publish'));
 $intermediate=wp_insert_post(array('post_title'=>'Intermediate','post_name'=>'wordpress','post_type'=>'page','post_status'=>'draft','post_parent'=>$base));
 $hub=wp_insert_post(array('post_title'=>'Small business','post_name'=>'small-business','post_type'=>'page','post_status'=>'publish','post_parent'=>$intermediate));
 $leaf=wp_insert_post(array('post_title'=>'Best hosting','post_name'=>'best-hosting','post_type'=>'page','post_status'=>'publish','post_parent'=>$hub));
 rmh_check_it('Locked hierarchical paths resolve to expected pages', get_page_by_path('hosting/wordpress/small-business')->ID === $hub && get_page_by_path('hosting/wordpress/small-business/best-hosting')->ID === $leaf);
 $resolved = new WP_Query(array('pagename'=>'hosting/wordpress/small-business/best-hosting'));
 rmh_check_it('Locked path queries published leaf under draft intermediate', $resolved->post && $resolved->post->ID === $leaf);
 $dynamic=apply_filters('elementor/element/is_dynamic_content',false,array('settings'=>array('shortcode'=>'[rmh_disclosure]')),null);
 rmh_check_it('Elementor dynamic hook marks RMH shortcode', true === $dynamic);

} catch (Throwable $e) { $results[]=array('name'=>'Exception: '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine(),'pass'=>false); }
file_put_contents('/stage/tests/wp-integration-result.json', json_encode(array('wp'=>get_bloginfo('version'),'php'=>PHP_VERSION,'acf'=>defined('ACF_VERSION')?ACF_VERSION:'not loaded','results'=>$results), JSON_PRETTY_PRINT));
