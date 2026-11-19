<?php
require '/wordpress/wp-load.php';
$res=array('elementor'=>defined('ELEMENTOR_VERSION')?ELEMENTOR_VERSION:'not loaded','results'=>array());
try {
 $res['results'][]=array('name'=>'Elementor class initialized','pass'=>class_exists('Elementor\\Plugin'));
 $element=\Elementor\Plugin::$instance->elements_manager->create_element_instance(array('id'=>'abc123','elType'=>'widget','widgetType'=>'shortcode','settings'=>array('shortcode'=>'[rmh_disclosure]')));
 $res['results'][]=array('name'=>'Shortcode widget instantiates','pass'=>is_object($element));
 add_filter('elementor/element/should_render_shortcode','__return_true');
 $ref=new ReflectionMethod($element,'should_render_shortcode'); $ref->setAccessible(true);
 $res['results'][]=array('name'=>'Default dynamic widget renders dynamically','pass'=>(bool)$ref->invoke($element));
 $active=\Elementor\Plugin::$instance->elements_manager->create_element_instance(array('id'=>'abc124','elType'=>'widget','widgetType'=>'shortcode','settings'=>array('shortcode'=>'[rmh_disclosure]','_element_cache'=>'no')));
 $res['results'][]=array('name'=>'Explicit Active cache overrides filter, requiring manual Inactive','pass'=>false===$ref->invoke($active));
} catch(Throwable $e) { $res['results'][]=array('name'=>'Error '.$e->getMessage(),'pass'=>false); }
file_put_contents('/stage/tests/wp-elementor-result.json',json_encode($res,JSON_PRETTY_PRINT));
