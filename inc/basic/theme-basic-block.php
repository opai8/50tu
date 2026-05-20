<?php

/* ========== 基础设置 basic ========== */

/*
 * 功能屏蔽 theme-basic-block
 *
 * 改进说明：
 * 1. 统一入口点：theme_basic_block_init() 集中管理所有钩子注册
 * 2. 选项缓存 + array_flip：isset O(1) 替代 in_array O(n)，避免 filter 重复执行
 * 3. 修复时序 BUG：嵌套 init 钩子改为直接执行
 * 4. 修复逻辑：前台工具栏钩子错位、管理员重定向错误
 */

// 防止直接访问
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// ========================================
// 选项缓存系统
// ========================================

/**
 * 获取选项并缓存，使用 array_flip 转换为键值对
 * 调用方通过 isset() O(1) 检查，替代 in_array() O(n)
 *
 * @param string $option_key CSF 选项 ID
 * @return array 翻转后的数组（空数组表示未勾选或无效）
 */
function theme_basic_get_blocks( $option_key ) {
    static $cache = [];

    if ( ! isset( $cache[ $option_key ] ) ) {
        $val = my_option( $option_key );
        $cache[ $option_key ] = is_array( $val ) ? array_flip( $val ) : [];
    }

    return $cache[ $option_key ];
}


// ========================================
// 统一入口点
// ========================================

/**
 * 集中注册所有钩子，统一管理生命周期
 */
function theme_basic_block_init() {

    // 后台初始化（admin_init）
    add_action( 'admin_init', 'theme_basic_admin_init' );

    // 前台初始化（init 优先级 9，确保在大多数 WP 默认钩子之前）
    add_action( 'init', 'theme_basic_frontend_init', 9 );

    // 后台菜单构建
    add_action( 'admin_menu', 'theme_basic_admin_menu' );

    // 仪表盘小工具
    add_action( 'wp_dashboard_setup', 'theme_basic_dashboard_setup' );

    // 自定义工具栏
    add_action( 'admin_bar_menu', 'custom_toolbar_link', 100 );

    // 页脚信息
    add_action( 'admin_init', 'change_admin_footer' );
}
theme_basic_block_init();


// ========================================
// 后台 init 回调
// ========================================

function theme_basic_admin_init() {
	
	// 功能屏蔽
    $blocks = theme_basic_get_blocks( 'opt_block_common' );

    if ( isset( $blocks['opt_revise'] ) ) {
        disable_post_revise();
    }

    if ( isset( $blocks['opt_revision'] ) ) {
        disable_post_revision();
    }

    if ( isset( $blocks['opt_draft'] ) ) {
        disable_draft_data();
    }

    if ( isset( $blocks['opt_xmlrpc'] ) ) {
        disable_xmlrpc();
    }

    if ( isset( $blocks['opt_auto_update'] ) ) {
        disable_auto_update();
    }

    if ( isset( $blocks['opt_email_check'] ) ) {
        disable_email_check();
    }

    if ( isset( $blocks['opt_screen_option'] ) ) {
        remove_screen_option();
    }

    // 函数禁用
    $func = theme_basic_get_blocks( 'opt_block_func' );

    if ( isset( $func['opt_translations_api'] ) ) {
        remove_translations_api();
    }

    if ( isset( $func['opt_wp_phpv'] ) ) {
        remove_wp_phpv();
    }

    if ( isset( $func['opt_check_browserv'] ) ) {
        remove_check_browserv();
    }

    // 转换功能
    $transform = theme_basic_get_blocks( 'opt_block_transform' );

    if ( isset( $transform['opt_emoji'] ) ) {
        disable_emoji();
    }

    if ( isset( $transform['opt_wptexturize'] ) ) {
        disable_wptexturize();
    }

    if ( isset( $transform['opt_capitalization'] ) ) {
        disable_capitalization();
    }

    // 嵌入功能
    $embed = theme_basic_get_blocks( 'opt_block_embed' );

    if ( isset( $embed['opt_auto_embed'] ) ) {
        disable_auto_embed();
    }

    if ( isset( $embed['opt_wp_embed'] ) ) {
        disable_wp_embed();
    }

    // 古腾堡编辑器
    $editor = theme_basic_get_blocks( 'opt_block_editor' );

    if ( isset( $editor['opt_gutenberg_editor'] ) ) {
        disable_gutenberg_editor();
    }

    if ( isset( $editor['opt_widget_editor'] ) ) {
        disable_widget_editor();
    }
}


// ========================================
// 前台 init 回调
// ========================================

function theme_basic_frontend_init() {
    $frontend = theme_basic_get_blocks( 'opt_block_frontend' );

    if ( isset( $frontend['opt_trackback'] ) ) {
        disable_trackback();
    }

    if ( isset( $frontend['opt_feed'] ) ) {
        disable_feed();
    }

    if ( isset( $frontend['opt_unuse_style'] ) ) {
        disable_unuse_style();
    }

    // 后台优化 - 前台工具栏（需在 init 中注册，wp_dashboard_setup 仅后台触发）
    $backend = theme_basic_get_blocks( 'opt_block_backend' );

    if ( isset( $backend['opt_home_bar'] ) ) {
        remove_home_bar();
    }
	
	if ( isset( $backend['opt_admin_logo'] ) ) {
        remove_admin_logo();
    }

    if ( isset( $backend['opt_admin_bar'] ) ) {
        remove_admin_bar();
    }
}


// ========================================
// 后台菜单回调
// ========================================

function theme_basic_admin_menu() {
    $page = theme_basic_get_blocks( 'opt_block_page' );

    if ( isset( $page['opt_theme_menu'] ) ) {
        remove_theme_menu();
    }

    if ( isset( $page['opt_site_editor'] ) ) {
        remove_site_editor();
    }

    if ( isset( $page['opt_customize_manage'] ) ) {
        remove_customize_manage();
    }

    if ( isset( $page['opt_theme_editor'] ) ) {
        remove_theme_editor();
    }

    if ( isset( $page['opt_site_health'] ) ) {
        remove_site_health();
    }

    if ( isset( $page['opt_gdpr_privacy'] ) ) {
        remove_gdpr_privacy();
    }

    if ( isset( $page['opt_editor_metabox'] ) ) {
        remove_editor_metabox();
    }
}


// ========================================
// 仪表盘回调
// ========================================

function theme_basic_dashboard_setup() {
    $backend = theme_basic_get_blocks( 'opt_block_backend' );

    if ( isset( $backend['opt_dashboard_tool'] ) ) {
        remove_dashboard_tool();
    }
}


// ========================================
// . 屏蔽文章修订功能 .
// ========================================
function disable_post_revise() {

    // 1. 定义常量彻底禁用（如果 wp-config.php 未定义）
    if ( ! defined( 'WP_POST_REVISIONS' ) ) {
        define( 'WP_POST_REVISIONS', false );
    }

    // 2. 禁用自动保存
    add_filter( 'autosave_interval', '__return_false' );

    // 3. 移除自动保存 JS 脚本
    add_action( 'admin_print_scripts', function() {
        wp_deregister_script( 'autosave' );
    });

    // 4. 移除编辑器中的修订版本元框
    add_action( 'admin_menu', function() {
        remove_meta_box( 'revisionsdiv', 'post', 'normal' );
        remove_meta_box( 'revisionsdiv', 'page', 'normal' );

        // 支持自定义文章类型
        $post_types = get_post_types( array( 'public' => true ), 'names' );
        foreach ( $post_types as $post_type ) {
            remove_meta_box( 'revisionsdiv', $post_type, 'normal' );
        }
    });

    // 5. 保存文章时移除修订版本动作
    add_action( 'save_post', function( $post_id, $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        remove_action( 'pre_post_update', 'wp_save_post_revision' );
    }, 10, 2 );

    // 6. 禁用 REST API 修订版本端点
    add_filter( 'rest_prepare_revision', '__return_null' );

    // 7. 设置保留修订版本数量为 0
    add_filter( 'wp_revisions_to_keep', '__return_zero', 10, 2 );
}

// ========================================
// . 清理所有修订版本 .
// ========================================
function disable_post_revision() {
    // 仅在管理员访问后台时执行一次清理
    if ( is_admin() && current_user_can( 'manage_options' ) ) {
        global $wpdb;

        // 先删除 postmeta 中的孤立数据
        $wpdb->query( "DELETE pm FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.ID IS NULL" );

        // 删除所有修订版本
        $deleted = $wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'" );

        if ( $deleted > 0 ) {
            $wpdb->query( "OPTIMIZE TABLE {$wpdb->posts}" );
            $wpdb->query( "OPTIMIZE TABLE {$wpdb->postmeta}" );
        }
    }
}

// ========================================
// . 删除所有自动保存草稿 .
// ========================================
function disable_draft_data() {

    // 仅在后台环境执行
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;

    global $wpdb;

    // 启动事务确保原子性
    $wpdb->query( 'START TRANSACTION' );

    // 1. 删除所有自动保存草稿
    $wpdb->delete(
        $wpdb->posts,
        [ 'post_status' => 'auto-draft' ],
        [ '%s' ]
    );
    if ( $wpdb->last_error ) {
        $wpdb->query( 'ROLLBACK' );
        error_log( '删除自动草稿时出错: ' . $wpdb->last_error );
        return;
    }

    // 2. 删除所有自动保存修订版本
    $wpdb->delete(
        $wpdb->posts,
        [
            'post_type'   => 'revision',
            'post_status' => 'auto-draft'
        ],
        [ '%s', '%s' ]
    );

    // 3. 删除所有inherit类型数据
    $wpdb->delete(
        $wpdb->posts,
        [ 'post_status' => 'inherit' ],
        [ '%s' ]
    );

    // 清理相关元数据
    $wpdb->query(
        "DELETE meta FROM $wpdb->postmeta meta
         LEFT JOIN $wpdb->posts posts ON meta.post_id = posts.ID
         WHERE posts.ID IS NULL"
    );

    // 提交事务
    $wpdb->query( 'COMMIT' );

    // 清除缓存保证数据一致性
    wp_cache_flush();
}

// ========================================
// . 彻底禁用 Trackback 功能 .
// ========================================
function disable_trackback() {
    // 层次 1: 核心功能禁用
    add_filter( 'pings_open', '__return_false', 100 );
    add_filter( 'trackback_enabled', '__return_false' );
    add_filter( 'xmlrpc_methods', function( $methods ) {
        unset( $methods['pingback.ping'] );
        unset( $methods['pingback.extensions.getPingbacks'] );
        return $methods;
    });

    // 层次 2: 请求拦截层
    add_action( 'template_redirect', function() {
        if ( isset( $_GET['tb_id'] ) || isset( $_POST['tb_id'] ) ||
             preg_match( '/trackback\?/i', $_SERVER['REQUEST_URI'] ) ||
             ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
            wp_die(
                '本站已彻底禁用 Trackback/Pingback 功能',
                '服务已禁用',
                [ 'response' => 403, 'back_link' => false ]
            );
        }
    });

    // 层次 3: REST API
    add_filter( 'rest_endpoints', function( $routes ) {
        foreach ( $routes as $route => $callback ) {
            if ( preg_match( '/(trackback|pingback)/i', $route ) ) {
                unset( $routes[ $route ] );
            }
        }
        return $routes;
    });

    // 层次 4: UI 层移除
    add_action( 'admin_init', function() {
        remove_meta_box( 'trackbacksdiv', [ 'post', 'page', 'link' ], 'normal' );

        add_filter( 'discussion_settings', function( $fields ) {
            unset( $fields['trackback_enabled'] );
            unset( $fields['pingback_enabled'] );
            return $fields;
        }, 10, 1 );
    });
}

// ========================================
// . 彻底禁用并隐藏XML-RPC功能 .
// ========================================
function disable_xmlrpc() {
    // ===== 层1：核心禁用 =====
    add_filter( 'xmlrpc_enabled', '__return_false', 1 );

    // ===== 层2：移除头部链接（无闪错） =====
    remove_action( 'wp_head', 'rsd_link' );
    add_filter( 'wp_headers', function( $headers ) {
        unset( $headers['X-Pingback'] );
        return $headers;
    });

    // ===== 层3：精准拦截（替换 parse_request） =====
    // ✅ 只在登录页面访问 xmlrpc.php 时拦截，不影响编辑器
    add_action( 'login_init', function() {
        if ( strpos( $_SERVER['REQUEST_URI'], 'xmlrpc.php' ) !== false ) {
            wp_die( 'XML-RPC已禁用', 403 );
        }
    });
}

// ========================================
// . 完全禁用WordPress的所有自动更新 .
// ========================================
function disable_auto_update() {

    // === 禁用所有自动更新功能 (过滤器) ===

    // 1. 禁用核心自动更新
    add_filter( 'auto_update_core', '__return_false' );
    add_filter( 'allow_dev_auto_core_updates', '__return_false' );
    add_filter( 'allow_minor_auto_core_updates', '__return_false' );
    add_filter( 'allow_major_auto_core_updates', '__return_false' );

    // 2. 禁用插件/主题/翻译自动更新
    add_filter( 'auto_update_plugin', '__return_false' );
    add_filter( 'auto_update_theme', '__return_false' );
    add_filter( 'auto_update_translation', '__return_false' );

    // 3. 阻止WordPress检查更新
    add_filter( 'pre_site_transient_update_core', '__return_null' );
    add_filter( 'pre_site_transient_update_plugins', '__return_null' );
    add_filter( 'pre_site_transient_update_themes', '__return_null' );

    // === 移除定时任务（直接执行，不再嵌套 init） ===
    wp_clear_scheduled_hook( 'wp_version_check' );
    wp_clear_scheduled_hook( 'wp_update_plugins' );
    wp_clear_scheduled_hook( 'wp_update_themes' );

    // === 移除后台更新通知 ===
    add_action( 'admin_notices', function() {
        remove_action( 'admin_notices', 'update_nag', 3 );
        remove_action( 'admin_notices', 'maintenance_nag', 10 );
        remove_action( 'network_admin_notices', 'update_nag', 3 );
        remove_action( 'network_admin_notices', 'maintenance_nag', 10 );
    });
}

// ========================================
// . 屏蔽站点 Feed 功能 .
// ========================================
function disable_feed() {
    // 1. 禁用所有 Feed 类型并拦截请求
    add_action( 'do_feed',      'disable_feed_handler', 1 );
    add_action( 'do_feed_rdf',  'disable_feed_handler', 1 );
    add_action( 'do_feed_rss',  'disable_feed_handler', 1 );
    add_action( 'do_feed_rss2', 'disable_feed_handler', 1 );
    add_action( 'do_feed_atom', 'disable_feed_handler', 1 );

    // 2. 移除 Feed 链接（防止被扫描发现）
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'template_redirect', 'do_feed_redirect', 10 );

    // 3. 移除 REST API 中真正的 Feed 端点（仅移除 /wp/v2/posts 下的 embed/context 子端点）
    add_filter( 'rest_endpoints', function( $endpoints ) {
        // 只移除 posts 的 embed 和 render 子路由（Feed 相关），保留核心 CRUD 端点
        $feed_only_routes = [
            '/wp/v2/posts/(?P<id>[\d]+)/revisions',
            '/wp/v2/search',
        ];
        foreach ( $feed_only_routes as $route ) {
            if ( isset( $endpoints[ $route ] ) ) {
                unset( $endpoints[ $route ] );
            }
        }
        return $endpoints;
    }, 9999 );
}

// 禁用 Feed 请求处理
function disable_feed_handler() {
    wp_redirect( home_url( '/404' ), 404 );
    exit();
}

// ========================================
// . 禁用站点管理员邮箱定期验证 .
// ========================================
function disable_email_check() {

    // 核心禁用
    add_filter( 'admin_email_verification_required', '__return_false' );
    add_filter( 'admin_email_check_interval', '__return_false' );

    // 清理数据
    $options = array(
        'admin_email_verification_pending',
        'admin_email_verification_expiration',
        'admin_email_verification_token',
        'admin_email_verification_key',
        'admin_email_last_checked',
        '_admin_email_verification_pending'
    );

    foreach ( $options as $option ) {
        delete_option( $option );
    }

    // 清理用户元数据（直接执行，不再嵌套 init）
    static $email_check_done = false;
    if ( ! $email_check_done ) {
        global $wpdb;
        $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE '%admin_email_verification%'" );
        $email_check_done = true;
    }

    // 移除提示
    remove_action( 'admin_notices', 'admin_email_verify_notice' );
    remove_action( 'admin_init', 'admin_email_verify_init' );
}

// ========================================
// . 优化wp_head,移除未使用样式 .
// ========================================
function disable_unuse_style() {

    // 安全移除：版本号暴露
    if ( function_exists( 'wp_generator' ) ) {
        remove_action( 'wp_head', 'wp_generator' );
    }
    // 移除Windows Live Writer支持
    remove_action( 'wp_head', 'wlwmanifest_link' );

    // 移除短链接
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );

    // SEO优化：相邻文章链接
    if ( has_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' ) ) {
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
    }

    // 移除 oEmbed 链接（保留 JS/CSS，避免编辑器错乱）
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'rest_output_link_wp_head' );

	    // ===== ❌ 禁止移除（古腾堡核心样式） =====
		// 样式优化：移除块样式
    // ❌ 不要移除 wp-block-library（古腾堡核心样式）
    // if ( wp_style_is( 'wp-block-library', 'registered' ) ) {
    //     wp_dequeue_style( 'wp-block-library' );
    //     wp_deregister_style( 'wp-block-library' );
    // }
		// 移除内联样式缓存
    // ❌ 不要移除 global-styles（古腾堡全局样式）
    // if ( wp_style_is( 'global-styles', 'registered' ) ) {
    //     wp_dequeue_style( 'global-styles' );
    //     remove_action( 'wp_head', 'wp_global_styles_render_svg_filters' );
    //     remove_action( 'wp_head', 'wp_global_styles_render_keyframes' );
    // }
}

// ========================================
// . 禁止 translations_api .
// ========================================
function remove_translations_api() {
	add_filter('translations_api', '__return_false', 999, 2);

}

// ========================================
// . 禁止 wp_check_php_version .
// ========================================
function remove_wp_phpv() {
    add_filter( 'wp_check_php_version', '__return_empty_array' );
}

// ========================================
// . 禁止 wp_check_browser_version .
// ========================================
function remove_check_browserv() {
    add_filter( 'wp_check_browser_version', '__return_empty_array' );
}

// ========================================
// . 屏蔽emoji .
// ========================================
function disable_emoji() {
    // 1. 移除所有Emoji相关动作和过滤器
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_head', 'print_emoji_styles', 99 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_styles', 'print_emoji_styles', 99 );
    remove_action( 'embed_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );

    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'oembed_response_data', 'wp_filter_oembed_result' );

    // 2. 禁用DNS预取中的Emoji
    remove_action( 'wp_head', 'wp_resource_hints', 2 );

    // 3. 禁用SVG Emoji
    add_filter( 'emoji_svg_url', '__return_false' );

    // 4. 移除编辑器Emoji
    add_filter( 'tiny_mce_plugins', function( $plugins ) {
        if ( is_array( $plugins ) && in_array( 'wpemoji', $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        }
        return $plugins;
    });

    // 5. 移除REST API中的Emoji
    remove_filter( 'rest_pre_echo_response', 'wp_oembed_add_discovery_links' );

    // 6. 禁用自动转换
    add_filter( 'wp_staticize_emoji', '__return_false' );

    // 7. 移除Emoji相关的CSS类
    add_action( 'wp_head', function() {
        echo '<style>img.emoji { display: none !important; }</style>';
    }, 999 );
}

// ========================================
// . 屏蔽字符转换(禁用wptexturize) .
// ========================================
function disable_wptexturize() {
    remove_filter( 'the_content', 'wptexturize' );
    remove_filter( 'the_excerpt', 'wptexturize' );
    remove_filter( 'comment_text', 'wptexturize' );
    remove_filter( 'the_content_rss', 'wptexturize' );
    remove_filter( 'the_title_rss', 'wptexturize' );
    remove_filter( 'widget_text', 'wptexturize' );
    remove_filter( 'widget_title', 'wptexturize' );
}

// ========================================
// . 屏蔽 WordPress 大小写 .
// ========================================
function disable_capitalization() {
    remove_filter( 'the_title', 'capital_P_dangit', 11 );
    remove_filter( 'the_content', 'capital_P_dangit', 11 );
    remove_filter( 'comment_text', 'capital_P_dangit', 31 );
    remove_filter( 'title_save_pre', 'capital_P_dangit' );
    remove_filter( 'the_title_rss', 'capital_P_dangit' );
    remove_filter( 'wp_title', 'capital_P_dangit' );
}

// ========================================
// . 移除前台顶部工具栏（管理员保留） .
// . 去掉 if 判断,全部隐藏
// ========================================
function remove_home_bar() {
    // 仅对非管理员隐藏前台工具栏
    if ( ! current_user_can( 'manage_options' ) ) {
        add_filter( 'show_admin_bar', '__return_false' );
        remove_action( 'wp_head', '_admin_bar_bump_cb' );
        remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
    }
}

// ========================================
// . 移除 Admin Bar 中的 W 标志（保留工具栏）.
// ========================================
function remove_admin_logo() {
    add_action('admin_bar_menu', function($admin_bar) {
        $admin_bar->remove_node('wp-logo');
    }, 999);
}

// ========================================
// . 移除后台顶部工具栏（包括管理员） .
// ========================================
function remove_admin_bar() {
    // 仅在后台执行
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        add_filter( 'show_admin_bar', '__return_false', 100 );
        remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
        remove_action( 'admin_print_scripts', 'wp_admin_bar_enqueue_scripts' );
        remove_action( 'admin_print_styles', 'wp_admin_bar_enqueue_styles' );

        add_action( 'admin_head', function() {
            echo '<style>
                #wpadminbar,
                #wp-toolbar {
                    display: none !important;
                    visibility: hidden !important;
                }
                html {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                }
            </style>';
        });
    }
}

// ========================================
// . 移除仪表盘小工具 .
// ========================================
function remove_dashboard_tool() {

    $widgets = array(
        'dashboard_right_now',
        'dashboard_activity',
        'dashboard_primary',
        'dashboard_secondary',
        'dashboard_quick_press',
        'dashboard_recent_drafts',
        'dashboard_recent_comments',
        'dashboard_incoming_links',
        'dashboard_plugins'
    );

    foreach ( $widgets as $widget ) {
        remove_meta_box( $widget, 'dashboard', 'normal' );
        remove_meta_box( $widget, 'dashboard', 'side' );
    }

    // 清理缓存
    delete_transient( 'dashboard_primary' );
    delete_transient( 'dashboard_secondary' );
    delete_transient( 'dashboard_activity' );
}

// ========================================
// . 移除后台右上角帮助和选项 .
// ========================================
function remove_screen_option() {
    add_filter( 'screen_options_show_screen', '__return_false' );
    add_filter( 'hidden_columns', '__return_empty_array' );
    add_action( 'in_admin_header', function() {
        global $current_screen;
        $current_screen->remove_help_tabs();
    });
}

// ========================================
// . 移除「外观」菜单项 .
// ========================================
function remove_theme_menu() {
    remove_menu_page( 'themes.php' );
    remove_submenu_page( 'options-general.php', 'options-reading.php' );
}

// ========================================
// . 移除「外观-样板」菜单项 .
// ========================================
function remove_site_editor() {

    // 移除后台菜单项
    global $submenu;
    if ( isset( $submenu['themes.php'] ) ) {
        foreach ( $submenu['themes.php'] as $key => $menu_item ) {
            if (
                is_array( $menu_item ) &&
                isset( $menu_item[0] ) &&
                strpos( $menu_item[0], '样板' ) !== false
            ) {
                remove_submenu_page( 'themes.php', $menu_item[2] );
                break;
            }
        }
    }

    // 防止通过URL直接访问
    if ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/site-editor.php' ) !== false ) {
        wp_redirect( admin_url(), 301 );
        exit;
    }
}

// ========================================
// . 移除「外观-主题文件编辑器」菜单项 .
// ========================================
function remove_theme_editor() {

    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;

    // 第一层：URL拦截
    if ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/theme-editor.php' ) !== false ) {
        wp_redirect( admin_url(), 301 );
        exit;
    }

    // 第二层：移除菜单项
    add_action( 'admin_menu', function() {
        remove_submenu_page( 'themes.php', 'theme-editor.php' );
    }, 999 );

    // 第三层：权限过滤
    add_filter( 'user_has_cap', function( $allcaps, $caps, $args ) {
        $page = isset( $_GET['page'] ) ? $_GET['page'] : '';

        if ( $page === 'theme-editor.php' ) {
            $allcaps['edit_themes'] = false;
            $allcaps['edit_files'] = false;
            $allcaps['edit_plugins'] = false;
            $allcaps['switch_themes'] = false;
            $allcaps['edit_theme_options'] = false;
            $allcaps['edit_theme_plugin_files'] = false;
        }

        if ( isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) {
            $allcaps['edit_files'] = false;
            $allcaps['edit_themes'] = false;
        }

        return $allcaps;
    }, 10, 3 );

    // 第四层：清理资源加载
    add_action( 'admin_enqueue_scripts', function( $hook_suffix ) {
        $page = isset( $_GET['page'] ) ? $_GET['page'] : '';

        if ( $page === 'theme-editor.php' ) {
            wp_dequeue_style( 'theme-editor' );
            wp_deregister_style( 'theme-editor' );
            wp_dequeue_script( 'theme-editor' );
            wp_deregister_script( 'theme-editor' );
            wp_dequeue_script( 'code-editor' );
            wp_deregister_script( 'code-editor' );
            wp_dequeue_style( 'code-editor' );
            wp_deregister_style( 'code-editor' );
            wp_dequeue_script( 'wp-theme-plugin-editor' );
            wp_deregister_script( 'wp-theme-plugin-editor' );
            wp_dequeue_style( 'wp-codemirror' );
            wp_deregister_style( 'wp-codemirror' );
        }
    }, 999 );

    // 第五层：禁用保存接口
    add_action( 'admin_post_edit-theme-plugin-file', function() {
        wp_redirect( admin_url(), 301 );
        exit;
    }, 0 );

    add_action( 'admin_post_edit_theme_file', function() {
        wp_redirect( admin_url(), 301 );
        exit;
    }, 0 );

    // 第六层：禁用AJAX接口
    add_action( 'wp_ajax_edit-theme-plugin-file', function() {
        wp_send_json_error( [
            'message' => '主题编辑器已被禁用',
            'code'    => 'editor_disabled',
            'data'    => '您没有权限编辑主题文件'
        ], 403 );
    }, 0 );

    add_action( 'wp_ajax_edit_theme_file', function() {
        wp_send_json_error( [
            'message' => '文件编辑功能已被禁用',
            'code'    => 'file_edit_disabled'
        ], 403 );
    }, 0 );

    // 第七层：页面内容锁定
    add_action( 'admin_head-theme-editor.php', function() {
        echo '<style>
            .theme-editor-php #wpbody-content,
            .theme-editor-php .wrap,
            .theme-editor-php #template,
            .theme-editor-php #templateside,
            .theme-editor-php #theme-editor-warning {
                display: none !important;
            }
            .theme-editor-php::before {
                content: "主题编辑器已被禁用";
                display: block;
                padding: 30px;
                background: #d32f2f;
                color: #fff;
                text-align: center;
                font-size: 24px;
                font-weight: bold;
                margin: 20px;
                border-radius: 5px;
            }
        </style>';

        echo '<script>
            jQuery(document).ready(function($) {
                $("button, input[type=submit]").prop("disabled", true).css("opacity", "0.5");
                $("textarea").prop("readonly", true).css("background", "#f5f5f5");
                $("*").off("click");
                alert("主题编辑器已被管理员禁用！\n\n您无法编辑或保存任何文件。");
                setTimeout(function() {
                    window.location.href = "' . admin_url() . '";
                }, 1000);
            });
        </script>';
    }, 999 );

    // 第八层：清理元数据
    add_action( 'admin_init', function() {
        $current_user = wp_get_current_user();
        if ( $current_user ) {
            delete_user_meta( $current_user->ID, 'theme_editor_warning_dismissed' );
            delete_user_meta( $current_user->ID, 'editable_extensions' );
        }

        delete_option( 'theme_editor_warning_dismissed' );
        delete_option( 'can_edit_themes' );
    });
}

// ========================================
// . 移除「外观-自定义」菜单项 .
// ========================================
function remove_customize_manage() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // 1. 移除自定义器前端资源加载
    remove_action( 'admin_enqueue_scripts', 'wp_customize_controls_enqueue_scripts' );
    remove_action( 'customize_controls_enqueue_scripts', 'wp_customize_theme_control_enqueue_scripts' );
    remove_action( 'customize_controls_init', 'wp_customize_theme_control_init' );
    remove_action( 'customize_register', 'wp_customize_theme_control_register' );

    // 2. 禁用自定义器API接口
    remove_action( 'wp_ajax_customize_save', 'wp_customize_save' );
    remove_action( 'wp_ajax_nopriv_customize_save', 'wp_customize_save' );
    remove_action( 'wp_ajax_customize_load', 'wp_customize_load' );
    remove_action( 'wp_ajax_nopriv_customize_load', 'wp_customize_load' );
    remove_action( 'wp_ajax_customize_preview', 'wp_customize_preview' );
    remove_filter( 'customize_save_response', 'wp_customize_save_response' );

    // 3. 移除后台菜单项
    global $submenu;
    if ( isset( $submenu['themes.php'] ) ) {
        foreach ( $submenu['themes.php'] as $key => $menu_item ) {
            if (
                is_array( $menu_item ) &&
                isset( $menu_item[2] ) &&
                (
                    $menu_item[2] === 'customize.php' ||
                    strpos( $menu_item[2], 'customize' ) !== false ||
                    strpos( $menu_item[0], '自定义' ) !== false
                )
            ) {
                unset( $submenu['themes.php'][ $key ] );
                break;
            }
        }
    }

    // 4. 防止通过URL直接访问
    if ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/customize.php' ) !== false ) {
        wp_redirect( admin_url(), 301 );
        exit;
    }
}

// ========================================
// . 移除[工具-站点健康]「工具-个人数据」菜单项 .
// ========================================
function remove_site_health() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;

    // === 菜单项移除 ===
    add_action( 'admin_menu', function() {
        remove_menu_page( 'site-health.php' );
        remove_submenu_page( 'tools.php', 'site-health.php' );
        remove_submenu_page( 'site-health.php', 'site-health.php' );
    }, 11 );

    // === 仪表盘面板移除 ===
    add_action( 'wp_dashboard_setup', function() {
        $meta_boxes = [
            'dashboard_site_health',
            'dashboard_php_nag',
            'dashboard_health_status',
            'dashboard_health_tests'
        ];

        foreach ( $meta_boxes as $box ) {
            remove_meta_box( $box, 'dashboard', 'normal' );
            remove_meta_box( $box, 'dashboard', 'side' );
        }
    });

    // === 访问拦截 ===
    add_action( 'admin_init', function() {
        if ( isset( $_GET['page'] ) && in_array( $_GET['page'], [
            'site-health',
            'site-health.php',
            'health-check',
            'health-check.php'
        ], true ) ) {
            wp_safe_redirect( admin_url(), 301 );
            exit;
        }
    });

    // === 功能禁用 ===
    add_filter( 'site_health_send_email_notification', '__return_false' );
    add_filter( 'site_health_run_tests', '__return_false' );
    add_filter( 'site_health_collect_data', '__return_false' );
    add_filter( 'site_health_enable_api', '__return_false' );
    add_filter( 'site_health_navigation_tabs', '__return_empty_array' );
    add_filter( 'site_health_error_log', '__return_false' );

    // === 定时任务清理（直接执行） ===
    wp_clear_scheduled_hook( 'site_health_cron_hook' );
    wp_clear_scheduled_hook( 'health-check-site-status' );

    // === 资源移除 ===
    add_action( 'admin_enqueue_scripts', function( $hook ) {
        if ( strpos( $hook, 'site-health' ) !== false ||
             strpos( $hook, 'health-check' ) !== false ) {
            wp_dequeue_script( 'site-health' );
            wp_dequeue_style( 'site-health' );
        }
    });

    // === 屏幕访问拦截 ===
    add_action( 'current_screen', function() {
        $screen = get_current_screen();
        if ( $screen && in_array( $screen->id, [
            'site-health',
            'dashboard_page_site-health',
            'health-check'
        ] ) ) {
            wp_safe_redirect( admin_url(), 301 );
            exit;
        }
    });
}

// ========================================
// . 移除[设置-隐私]菜单项 .
// ========================================
function remove_gdpr_privacy() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // 1. 移除隐私政策页面
    $privacy_page = get_posts( [
        'post_type'   => 'page',
        'meta_key'    => '_wp_page_template',
        'meta_value'  => 'privacy-policy',
        'fields'      => 'ids',
        'numberposts' => 1
    ] );

    if ( ! empty( $privacy_page ) ) {
        wp_delete_post( $privacy_page[0], true );

        global $wpdb;
        $wpdb->delete(
            $wpdb->postmeta,
            [
                'post_id'  => $privacy_page[0],
                'meta_key' => '_wp_page_template'
            ],
            [ '%d', '%s' ]
        );
    }

    // 2. 移除后台菜单项
    remove_submenu_page( 'options-general.php', 'options-privacy.php' );
    remove_submenu_page( 'tools.php', 'export-personal-data.php' );
    remove_submenu_page( 'tools.php', 'erase-personal-data.php' );

    // 3. 清理全局残留数据
    delete_option( 'wp_page_for_privacy_policy' );
    delete_option( '_wp_page_for_privacy_policy' );

    // 4. 禁用隐私相关功能钩子
    if ( class_exists( 'WP_Privacy_Policy_Content' ) ) {
        $policy_class = 'WP_Privacy_Policy_Content';
        remove_action( 'admin_init', [ $policy_class, 'text_change_check' ], 100 );
        remove_action( 'edit_form_after_title', [ $policy_class, 'notice' ] );
        remove_action( 'admin_init', [ $policy_class, 'add_suggested_content' ], 1 );
    }
}

// ========================================
// . 移除[显示帮助]映射在经典编辑器下方的字段 .
// ========================================
function remove_editor_metabox() {
    remove_meta_box( 'postexcerpt', 'post', 'normal' );
    remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
    remove_meta_box( 'commentsdiv', 'post', 'normal' );
    remove_meta_box( 'authordiv', 'post', 'normal' );
    remove_meta_box( 'postcustom', 'post', 'normal' );
    remove_meta_box( 'slugdiv', 'post', 'normal' );
}

// ========================================
// . 禁用Auto Embeds功能 .
// ========================================
function disable_auto_embed() {
    remove_filter( 'the_content', 'wp_auto_embed', 8 );
    add_filter( 'autoembed_enabled', '__return_false' );
}

// ========================================
// . 屏蔽嵌入其他WordPress文章功能 .
// ========================================
function disable_wp_embed() {
    remove_action( 'wp_enqueue_scripts', 'wp_maybe_enqueue_embeds' );
    add_filter( 'embed_oembed_html', '__return_false' );
}

// ========================================
// . 屏蔽Gutenberg编辑器 .
// ========================================
function disable_gutenberg_editor() {
    add_filter( 'use_block_editor_for_post', '__return_false', 10, 2 );
    add_filter( 'classic_editor_enabled', '__return_true' );
}

// ========================================
// . 屏蔽小工具区块编辑器模式 .
// ========================================
function disable_widget_editor() {
    add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
}

// ========================================
// . 自定义后台工具栏 .
// ========================================
function custom_toolbar_link( $wp_admin_bar ) {

    if ( empty( my_option( 'opt_custom_toolbar' ) ) ) {
        return;
    }

    // 定义需要移除的节点
    $target_node = [
        'wp-logo',
        'view-site',
        'updates',
        'comments',
        'new-content',
        'top-secondary',
    ];

    foreach ( $target_node as $args ) {
        $wp_admin_bar->remove_node( $args );
    }

    // 主菜单项
    $main_menu_item = [
        [
            'id'    => 'custom_home_link',
            'title' => '前台首页',
            'group' => null,
            'href'  => home_url(),
            'meta'  => [
                'class'  => 'custom_home_link',
                'title'  => '回到首页',
                'target' => '_blank'
            ]
        ],
        [
            'id'    => 'custom_new_title',
            'title' => '新建文章',
            'group' => null,
            'href'  => admin_url( 'edit.php' ),
            'meta'  => [
                'class'  => 'custom_new_title',
                'target' => '_self'
            ]
        ]
    ];

    // 子菜单项
    $submenu_item = [
        [
            'id'     => 'custom_new_submenu',
            'parent' => 'custom_home_link',
            'title'  => '子菜单项1',
            'href'   => admin_url()
        ],
        [
            'id'     => 'custom_new_submenu2',
            'parent' => 'custom_home_link',
            'title'  => '子菜单项2',
            'href'   => admin_url()
        ]
    ];

    // 用户账户右侧节点
    $node_item = [
        [
            'id'     => 'custom-home-link',
            'parent' => 'user-actions',
            'title'  => '前台首页1',
            'href'   => home_url(),
            'meta'   => [
                'target' => '_blank',
                'class'  => 'custom-home-link'
            ]
        ],
        [
            'id'     => 'custom-home-link2',
            'parent' => 'user-actions',
            'title'  => '前台首页2',
            'href'   => home_url(),
            'meta'   => [
                'target' => '_blank',
                'class'  => 'custom-home-link'
            ]
        ],
    ];

    foreach ( $main_menu_item as $args ) {
        $wp_admin_bar->add_menu( $args );
    }

    foreach ( $submenu_item as $args ) {
        $wp_admin_bar->add_menu( $args );
    }

    foreach ( $node_item as $args ) {
        $wp_admin_bar->add_node( $args );
    }
}

// ========================================
// . 后台页脚信息定制 .
// ========================================
function change_admin_footer() {

    // 排除特定页面
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'theme-options' ) {
        return;
    }

    $footer_info = my_option( 'opt_footerinfo_fieldset' );

    if ( empty( $footer_info ) ) {
        return;
    }

    add_filter( 'admin_footer_text', function() use ( $footer_info ) {
        return $footer_info['opt_footer_info_left'] ?? '';
    }, 99 );

    add_filter( 'update_footer', function() use ( $footer_info ) {
        return $footer_info['opt_footer_info_right'] ?? '';
    }, 99 );
}
