<?php
/**
 * 美图库主题函数文件 - 单文章图集版
 *
 * @package 美图库
 * @version 1.0.0
 */

// 防止直接访问
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// 首页瀑布流布局(50图)
require get_template_directory() .'/inc/theme-core.php';


/** 主题后台选项配置  */ 
require get_template_directory() .'/inc/theme-options.php';


// 脚本 Javascript and CSS
require get_template_directory() .'/inc/theme-script.php';


/* ====== 基础配置 Basic ====== */
	
	require get_template_directory() .'/inc/basic/theme-basic-special.php'; // 特殊模式
	
	require get_template_directory() .'/inc/basic/theme-basic-block.php';   // 功能屏蔽
	
	require get_template_directory() .'/inc/basic/theme-basic-speed.php';   // 加速优化

	require get_template_directory() .'/inc/basic/theme-basic-extend.php';	// 扩展增强

/* ====== 页面布局 Page ====== */
	require get_template_directory() .'/inc/page/theme-page-seo.php'; // SEO

/* ====== 文章附件 Post ====== */
	require get_template_directory() .'/inc/post/theme-post-attachment.php';// 附件管理

/* ====== 广告中心 Ad ====== */
	require get_template_directory() .'/inc/ad/theme-ad-group.php';// 广告管理
	
/* ====== 移动端设置 App ====== */
	require get_template_directory() .'/inc/app/theme-app-set.php';   // 移动端设置
	
	

