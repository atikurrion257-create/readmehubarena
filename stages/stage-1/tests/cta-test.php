<?php
// CTA output checks with stubs and synthetic records; not WordPress integration.
define('ABSPATH', __DIR__); define('DAY_IN_SECONDS', 86400);
$fields = array('rmh_program_status'=>'active_open', 'rmh_verification_status'=>'verified', 'rmh_approval_status'=>'approved', 'rmh_verified_date'=>gmdate('Y-m-d'), 'rmh_expires_date'=>gmdate('Y-m-d',time()+86400), 'rmh_affiliate_url_template'=>'https://www.systeme.io/?sa=test');
function add_shortcode(...$args) {}
function wp_parse_url($url,$component=-1) {return parse_url($url,$component);}
function esc_url_raw($url,$protocols=null) {return $url;}
function esc_url($s) {return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');}
function esc_attr($s) {return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');}
function esc_html($s) {return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');}
function __($s,$domain=null) {return $s;}
function esc_attr__($s,$domain=null) { return esc_attr($s); }
function esc_html__($s,$domain=null) { return esc_html($s); }
function rmh_get_setting($key) { return $key === 'default_disclosure' ? 'Disclosure: affiliate links may earn a commission.' : ''; }
function sanitize_key($s) {return preg_replace('/[^a-z0-9_-]/','',strtolower($s));}
function sanitize_html_class($s) {return sanitize_key($s);}
function sanitize_title($s) {return $s;}
function shortcode_atts($defaults,$atts,$name=null) {return array_merge($defaults,array_intersect_key($atts,$defaults));}
function get_posts($args) {return ($args['name'] ?? '') === 'systeme-io' ? array(1) : array();}
function get_post($id) {return $id===1 ? (object) array('post_type'=>'rmh_tool','post_title'=>'Systeme.io','post_name'=>'systeme-io') : null;}
function rmh_get_field($key,$id=null,$default='') {global $fields;return $fields[$key] ?? $default;}
function rmh_parse_lines($s) {return array();}
function get_the_post_thumbnail_url($id,$size) {return '';}
require __DIR__.'/../readmehub-core-dynamic/inc/data.php';
require __DIR__.'/../readmehub-core-dynamic/inc/shortcodes.php';
$tests=[];function cta_check($name,$ok){global $tests;$tests[]=$ok;echo ($ok?'PASS ':'FAIL ').$name."\n";}
$html=rmh_shortcode_affiliate_cta(array('tool'=>'systeme-io','url'=>'https://evil.example/?ref=abc','placement'=>'top'));
cta_check('Approved registry link with attributes, ignores URL override',strpos($html,'https://systeme.io/?sa=test')!==false && strpos($html,'evil.example')===false && strpos($html,'rel="sponsored nofollow noopener"')!==false && strpos($html,'data-rmh-cta="top"')!==false && strpos($html,'Disclosure:') < strpos($html,'<a class='));
$fields['rmh_approval_status']='not_approved';
cta_check('Unapproved has no fallback without separate confirmation',rmh_shortcode_affiliate_cta(array('tool'=>'systeme-io'))==='');
$fields['rmh_non_affiliate_url']='https://systeme.io/pricing';$fields['rmh_non_affiliate_url_confirmed']='1';$fields['rmh_non_affiliate_verified_date']=gmdate('Ymd');
$html=rmh_shortcode_affiliate_cta(array('tool'=>'systeme-io'));
cta_check('Separately verified plain fallback is not sponsored or tracked',strpos($html,'https://systeme.io/pricing')!==false && strpos($html,'sponsored')===false && strpos($html,'data-rmh-cta')===false && strpos($html,'sa=test')===false);
$fields['rmh_non_affiliate_url']='https://systeme.io/?ref=test';
cta_check('Fallback with query withheld',rmh_shortcode_affiliate_cta(array('tool'=>'systeme-io'))==='');
cta_check('Missing record withheld',rmh_shortcode_affiliate_cta(array('tool'=>'unknown','url'=>'https://evil.example/'))==='');
exit(in_array(false,$tests,true)?1:0);
