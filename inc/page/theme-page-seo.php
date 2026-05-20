<?php

/* ========== 页面布局 page ========== */
/*
 * SEO theme-page-seo
 */

// 防止直接访问
if ( ! defined( 'ABSPATH' ) ) {exit;}

// ========================================
// robots.txt 配置
// ========================================
// 挂载到WordPress的robots_txt过滤器
add_filter('robots_txt', function ($output, $public) {
    if ('0' == $public) {
        return "User-agent: *\nDisallow: /\n";
    } else {
        if (!empty(my_option('seo_robots', getrobots(), 'seo_robots_fieldset'))) {
            $output = esc_attr(strip_tags(my_option('seo_robots', getrobots(), 'seo_robots_fieldset')));
        }
        return $output;
    }
}, 10, 2);



// ========================================
// 修改 站点标题
// ========================================
function custom_site_title($title) {
    // 获取自定义值
    $custom_title = my_option('opt_bar_title');
    
    // 如果有自定义值，返回自定义值；否则返回默认标题
    if ($custom_title) {
        return $custom_title;
    }
    
    return $title;
}
add_filter('pre_option_blogname', 'custom_site_title');



/**
 * =============================================================
 * SEO 优化函数集
 * =============================================================
 * 
 */

/**
 * 获取SEO关键字
 * 
 */
function get_seo_keywords() {
    // 先尝试获取自定义关键字
    $custom_keywords = my_option('seo_keyword');
    
    // 检查是否设置了自定义关键字（包括空字符串）
    if (false !== $custom_keywords) {
        // 如果用户明确设置了空字符串，返回空字符串
        // 否则返回自定义关键字
        return $custom_keywords;
    }
    
    // 获取站点名称和描述
    $blog_name = get_bloginfo('name');
    $blog_description = get_bloginfo('description');
    
    // 组合默认关键字
    $default_keywords = $blog_name;
    if (!empty($blog_description)) {
        $default_keywords .= ', ' . $blog_description;
    }
    
    return $default_keywords;
}

/**
 * 获取SEO标题
 * 
 */
function get_seo_title() {

	// 如果是404页面，直接返回固定标题
    if (is_404()) {
        return '页面未找到 - 404';
    }
	
    // 先尝试获取自定义标题
    $custom_title = my_option('seo_title');
    
    // 检查是否设置了自定义标题（包括空字符串）
    if (false !== $custom_title) {
        // 如果用户明确设置了空字符串，返回空字符串
        // 否则返回自定义标题
        return $custom_title;
    }
    
    // 如果没有自定义标题，返回WordPress生成的标题
    return wp_get_document_title();
}

/**
 * 获取SEO描述
 * 
 */
function get_seo_description() {
    // 先尝试获取自定义描述
    $custom_description = my_option('seo_description');
    
    // 检查是否设置了自定义描述（包括空字符串）
    if (false !== $custom_description) {
        // 如果用户明确设置了空字符串，返回空字符串
        // 否则返回自定义描述
        return $custom_description;
    }
    
    // 如果没有自定义描述，返回站点描述
    return get_bloginfo('description');
}

/**
 * 输出SEO meta标签（关键字、描述和标题）到wp_head
 */
function output_seo_meta() {
    // 获取值
    $keywords = get_seo_keywords();
    $description = get_seo_description();
    $title = get_seo_title();

    // 标题标签（有描述则拼接，无描述则仅输出标题）
    if (!empty($title)) {
        echo '<title>' . esc_html($title);
        if (!empty($description)) {
            echo ' - ' . esc_attr($description);
        }
        echo '</title>' . "\n";
    }
    
    // 关键字 Meta (注意：Google等主要搜索引擎已不再将此作为排名因素，但保留无害)
    if (!empty($keywords)) {
        echo '<meta name="keywords" content="' . esc_attr($keywords) . '" />' . "\n";
    }
    
    // 描述 Meta (非常重要)
    if (!empty($description)) {
        echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    }
}
add_action('wp_head', 'output_seo_meta');



// ========================================
// 修改 favicon.ico
// ========================================
function custom_site_favicon() {
    $favicon = my_option('opt_favicon');
    
    if (empty($favicon)) {
        return;
    }
    
    $url = esc_url($favicon);
    
    // ✅ 直接输出，完全正确！
    echo '<link rel="icon" href="' . $url . '" type="image/x-icon">';
}
add_action('wp_head', 'custom_site_favicon');