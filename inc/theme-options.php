<?php
/**
 * Codestar Framework
 * 主题选项
 */


// 禁止直接访问.
if ( ! defined( 'ABSPATH' )  ) {die;}

// 引入 codestar-framework 框架
require_once get_theme_file_path() .'/inc/codestar-framework/codestar-framework.php';


// 获取输出选项的自定义函数 my_option('id')
if ( ! function_exists( 'my_option' ) ) {
	function my_option( $option = '', $default = null, $fieldset = '' ) {
		$options = get_option( 'my_framework' );
		
		if (!empty($fieldset)) {
            $results = $options[$fieldset][$option];
        } else {
            $results = $options[$option];
        }

		return (isset($results)) ? $results : $default;
	}
}


// 判断核心类是否有加载，以免出错
if( class_exists( 'CSF' ) ) {
	// 设置一个独立的选项 ID
	$prefix = 'my_framework';
	
	// 创建选项
	CSF::createOptions( $prefix, array(
		'menu_title' => '主题设置',
		'menu_slug' => 'theme-options',
		'theme' => 'light',
		'show_search' => false,
		'show_all_options' => false,
		'sticky_header' => false,
		'show_sub_menu' => false,
		'show_bar_menu' => false,
		'admin_bar_menu_icon' => 'dashicons-admin-generic',
		'framework_title' => '主题设置',
		'footer_credit' => '感谢使用 <a target="_blank" href="https://github.com/opai8/Meitu-Gallery">美图库主题</a> 进行创作，欢迎加入主题交流QQ群： 151649165</a>',
		'footer_text' => '更优雅的 WordPress 主题 -- 美图库[meitu-gallery] V 3.0.4'
	) );



/* ======= 主题简介 Intro ======= */
require get_template_directory() .'/inc/theme-intro.php';

/* ======= 基础配置 Basic ======= */
require get_template_directory() .'/inc/theme-basic.php';

/* ======= 页面布局 Page ======= */
require get_template_directory() .'/inc/theme-page.php';

/* ======= 文章附件 Post ======= */
require get_template_directory() .'/inc/theme-post.php';

/* ======= 广告管理 Ad ======= */
require get_template_directory() .'/inc/theme-ad.php';

/* ======= 移动端设置 App ======= */
require get_template_directory() .'/inc/theme-app.php';


}


?>