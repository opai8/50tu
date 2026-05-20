<?php

/* ========== 移动端设置 app ========== */


// ========================================
// 完全禁用 REST API 
// ========================================
function ultimate_rest_api_block() {
    // 🔹 新增条件判断：检查是否启用防护
    if (empty(my_option('opt_app_restapi'))) {
        return; // 选项为空时直接退出，节省资源
    }

    // 第1层：路由过滤（核心防护）
    // 白名单模式：保留后台编辑器（Gutenberg）必需的 REST 端点，移除其余
    add_filter('rest_endpoints', function($routes) {
        // 后台编辑器必需的端点（Gutenberg / 经典编辑器依赖）
        $allow_routes = [
            '/wp/v2/posts',
            '/wp/v2/posts/(?P<id>[\d]+)',
            '/wp/v2/posts/(?P<id>[\d]+)/revisions',
            '/wp/v2/posts/(?P<id>[\d]+)/revisions/(?P<revision>[\d]+)',
            '/wp/v2/pages',
            '/wp/v2/pages/(?P<id>[\d]+)',
            '/wp/v2/pages/(?P<id>[\d]+)/revisions',
            '/wp/v2/pages/(?P<id>[\d]+)/revisions/(?P<revision>[\d]+)',
            '/wp/v2/media',
            '/wp/v2/media/(?P<id>[\d]+)',
            '/wp/v2/types',
            '/wp/v2/types/(?P<type>[\w-]+)',
            '/wp/v2/statuses',
            '/wp/v2/statuses/(?P<status>[\w-]+)',
            '/wp/v2/taxonomies',
            '/wp/v2/taxonomies/(?P<taxonomy>[\w-]+)',
            '/wp/v2/categories',
            '/wp/v2/categories/(?P<id>[\d]+)',
            '/wp/v2/tags',
            '/wp/v2/tags/(?P<id>[\d]+)',
            '/wp/v2/users',
            '/wp/v2/users/(?P<id>[\d]+)',
            '/wp/v2/users/me',
            '/wp/v2/settings',
            '/wp/v2/themes',
            '/wp/v2/themes/(?P<stylesheet>[\w-]+)',
            '/wp/v2/blocks',
            '/wp/v2/blocks/(?P<id>[\d]+)',
            '/wp/v2/block-types',
            '/wp/v2/block-types/(?P<namespace>[\w-]+)',
            '/wp/v2/block-types/(?P<namespace>[\w-]+)/(?P<name>[\w-]+)',
            '/wp/v2/block-renderer',
            '/wp/v2/block-renderer/(?P<name>[\w-]+)',
            '/wp/v2/search',
            '/wp/v2/sidebars',
            '/wp/v2/widget-types',
            '/wp/v2/widget-types/(?P<id>[\w-]+)',
            '/wp/v2/widgets',
            '/wp/v2/widgets/(?P<id>[\d]+)',
            '/wp/v2/menus',
            '/wp/v2/menus/(?P<id>[\d]+)',
            '/wp/v2/menu-items',
            '/wp/v2/menu-items/(?P<id>[\d]+)',
            '/wp/v2/menu-locations',
            '/wp/v2/global-styles/(?P<id>[\d]+)',
            '/wp/v2/global-styles/themes/(?P<stylesheet>[\w-]+)',
            '/wp/v2/editor',
            '/wp/v2/pattern-directory/patterns',
        ];

        foreach ($routes as $route => $handlers) {
            if (!in_array($route, $allow_routes, true)) {
                unset($routes[$route]);
            }
        }

        return $routes;
    }, 1000);

    // 第2层：请求路径拦截（仅拦截非后台的 REST 请求）
    if (isset($_SERVER['REQUEST_URI']) &&
        (strpos($_SERVER['REQUEST_URI'], '/wp-json') !== false ||
         strpos($_SERVER['REQUEST_URI'], '/index.php?rest_route=') !== false)) {

        // 后台编辑器请求放行（Gutenberg 通过 /wp-json 加载数据）
        if (is_admin() || is_user_logged_in()) {
            return;
        }

        // 第3层：协议禁用
        add_filter('json_enabled', '__return_false');
        add_filter('jsonp_enabled', '__return_false');

        // 第4层：强制返回验证信息
        status_header(403);
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<!-- REST API Disabled - Intelligent Guard v3.0 -->';
        echo '<div style="text-align:center;padding:3rem;max-width:600px;margin:0 auto;background:#fff8f8;border:1px solid #ffc0c0;border-radius:8px;font-family:system-ui,-apple-system,sans-serif">';
        echo '<h2 style="color:#c82333;margin-top:0">⚠️ REST API访问被拒绝</h2>';
        echo '<p style="font-size:1.1rem;line-height:1.6">系统已启用智能API防护，所有REST接口均被禁用</p>';
        echo '<p><strong>安全说明：</strong></p>';
        echo '<ul style="text-align:left;margin:1rem auto;max-width:80%">';
        echo '<li>当前防护状态：<b>已启用</b></li>';
        echo '<li>资源节省模式：<b>智能开启</b></li>';
        echo '<li>如需关闭防护，请通过选项设置</li>';
        echo '</ul>';
        echo '<p style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid #eee">';
        echo '<small>验证时间: ' . current_time('Y-m-d H:i:s') . ' | 状态码: 403</small>';
        echo '</p>';
        echo '</div>';
        exit;
    }
}
add_action('init', 'ultimate_rest_api_block', 1);

// 🔹 新增：钩子清除条件判断
function remove_rest_api_hooks() {
    if (empty(my_option('opt_app_restapi'))) {
        return;
    }
    
    remove_action('init', 'rest_api_init');
    remove_action('rest_api_init', 'rest_api_default_filters', 10);
    remove_action('parse_request', 'rest_api_loaded');
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);
}
add_action('plugins_loaded', 'remove_rest_api_hooks', 0);

// 🔹 新增：认证拦截条件判断
add_filter('rest_authentication_errors', function($result) {
    if (defined('REST_API_REQUEST') && REST_API_REQUEST) {
        if (!empty(my_option('opt_app_restapi'))) {
            return new WP_Error('rest_disabled', '智能防护系统已启用', ['status' => 403]);
        }
    }
    return $result;
});

// 健康检查保留（不添加条件判断，确保监控有效性）
function rest_api_health_check() {
    $test_url = home_url('/wp-json');
    $response = wp_remote_get($test_url, [
        'timeout' => 3,
        'headers' => ['Cache-Control' => 'no-cache']
    ]);

    if (wp_remote_retrieve_response_code($response) !== 403) {
        $admin_email = my_option('admin_email');
        $error_msg = sprintf(
            "严重安全告警：REST API验证失败！\n\n访问 %s 返回状态码 %d\n\n请立即检查防护配置",
            $test_url,
            wp_remote_retrieve_response_code($response)
        );
        wp_mail($admin_email, 'REST API防护告警', $error_msg);
    }
}
add_action('admin_init', 'rest_api_health_check');


