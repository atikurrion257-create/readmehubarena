<?php
/** Run only on a disposable Playground database. */
require '/wordpress/wp-load.php';
require '/bundle/scripts/import-drafts.php';
$results=array(); function rmh6_test_prod_url($url){return 'https://www.readmehub.com/';} function rmh6_test($name,$ok){global $results; $results[]=array('name'=>$name,'pass'=>(bool)$ok);}
try {
 foreach(json_decode(file_get_contents('/bundle/acf/acf-export-readmehub.json'),true) as $group) acf_import_field_group($group);
 update_option('blog_public','0'); $home=untrailingslashit(home_url('/')); $orig_home=get_option('home'); $orig_site=get_option('siteurl');
 $manifest='/bundle/content/draft-manifest.json';
 $wrong=rmh6_import($manifest,'plan','https://incorrect.example'); rmh6_test('wrong expected host fails closed',is_wp_error($wrong));
 add_filter('home_url', 'rmh6_test_prod_url'); add_filter('site_url', 'rmh6_test_prod_url');
 $prod=rmh6_import($manifest,'apply','https://www.readmehub.com'); rmh6_test('production is rejected',is_wp_error($prod) && $prod->get_error_code()==='production');
 remove_filter('home_url', 'rmh6_test_prod_url'); remove_filter('site_url', 'rmh6_test_prod_url'); $home=untrailingslashit(home_url('/'));
 update_option('blog_public','1'); $index=rmh6_import($manifest,'apply',$home); rmh6_test('publicly indexable staging is rejected',is_wp_error($index) && $index->get_error_code()==='indexing'); update_option('blog_public','0');
 $collision=wp_insert_post(array('post_type'=>'page','post_name'=>'about','post_title'=>'Existing page','post_status'=>'publish'),true);
 $blocked=rmh6_import($manifest,'apply',$home); rmh6_test('collision blocks all writes and preserves existing page',is_wp_error($blocked) && $blocked->get_error_code()==='collision' && get_post_status($collision)==='publish' && !get_page_by_path('saas'));
 wp_delete_post($collision,true);
 $default_privacy=get_page_by_path('privacy-policy'); if ($default_privacy) wp_delete_post($default_privacy->ID,true);
 $bad_manifest=json_decode(file_get_contents($manifest),true); $bad_manifest['pages'][0]['source_sha256']=str_repeat('0',64); $bad_path='/tmp/rmh6-bad-manifest.json'; file_put_contents($bad_path,wp_json_encode($bad_manifest)); $bad=rmh6_import($bad_path,'apply',$home); rmh6_test('source hash mismatch blocks writes',is_wp_error($bad) && $bad->get_error_code()==='source_hash' && !get_page_by_path('saas'));
 $plan=rmh6_import($manifest,'plan',$home); rmh6_test('dry run plans 39 drafts with no creates',!is_wp_error($plan) && count($plan['actions'])===39 && !$plan['created'] && !get_page_by_path('saas'));
 $front=get_option('page_on_front'); $show=get_option('show_on_front'); $res=rmh6_import($manifest,'apply',$home); if (is_wp_error($res)) throw new Exception($res->get_error_code().': '.$res->get_error_message());
 rmh6_test('apply creates 39 drafts',!is_wp_error($res) && count($res['created'])===39);
 $all_ok=true; foreach($res['created'] as $row){$p=get_post($row['id']); if(!$p || $p->post_status!=='draft' || get_post_meta($p->ID,'rank_math_robots',true)!==array('noindex','nofollow') || get_post_meta($p->ID,'_rmh6_publish_blocked',true)!=='1') $all_ok=false;}
 rmh6_test('every generated record is draft, noindex and blocked', $all_ok);
 rmh6_test('front-page settings untouched',get_option('page_on_front')===$front && get_option('show_on_front')===$show);
 $hub=get_page_by_path('hosting/wordpress/small-business'); $leaf=get_page_by_path('hosting/wordpress/small-business/best-wordpress-hosting-for-small-business');
 rmh6_test('hierarchical small-business hub and leaf are drafts',$hub && $leaf && $hub->post_status==='draft' && (int)$leaf->post_parent===(int)$hub->ID);
 $about=get_page_by_path('about'); rmh6_test('ACF template stored with key',get_field('rmh_page_template_type',$about->ID)==='T14' && get_post_meta($about->ID,'_rmh_page_template_type',true)==='field_rmh_page_template_type');
 $review=get_page_by_path('saas/project-management/clickup-review'); $rendered=do_blocks($review->post_content);
 rmh6_test('commercial blocks render headings, links and price verification tokens',strpos($rendered,'<h1')!==false && strpos($rendered,'{{VERIFY-PRICE:')!==false && strpos($rendered,'https://clickup.com/pricing')!==false);
 rmh6_test('disclosure precedes first CTA shortcode',strpos($review->post_content,'[rmh_disclosure]') < strpos($review->post_content,'[rmh_affiliate_cta'));
 $homepost=get_page_by_path('readmehub-home-draft'); rmh6_test('homepage is separate scaffold and not front-page setting',$homepost && $homepost->post_status==='draft' && get_post_meta($homepost->ID,'_rmh6_elementor_scaffold',true)==='1');
 wp_update_post(array('ID'=>$about->ID,'post_content'=>'Editor retained draft'));
 $again=rmh6_import($manifest,'apply',$home); rmh6_test('second apply skips and preserves edits',!is_wp_error($again) && count($again['created'])===0 && get_post($about->ID)->post_content==='Editor retained draft');
 wp_update_post(array('ID'=>$about->ID,'post_status'=>'publish'));
 $blocked=rmh6_import($manifest,'apply',$home); rmh6_test('published owned page refuses repeat import',is_wp_error($blocked) && $blocked->get_error_code()==='collision');
} catch(Throwable $e){$results[]=array('name'=>'Exception: '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine(),'pass'=>false);}
file_put_contents('/bundle/tests/playground-results.json',wp_json_encode(array('scope'=>'Disposable WP/ACF PHP integration; not a WP-CLI, browser or staging test.','wp'=>get_bloginfo('version'),'php'=>PHP_VERSION,'results'=>$results),JSON_PRETTY_PRINT));
