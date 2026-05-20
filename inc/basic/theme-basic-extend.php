<?php

/* ========== 基础设置 basic ========== */

/*
 * 扩展增强 theme-extend
 */



// ========================================
// . 统计编辑器文本和图片 .
// . 内含条件判断(移植需注意) .
// ========================================
require_once get_stylesheet_directory() . '/inc/editor-stat/editor-stats.php';



// ========================================
// . 重置数据库id .
// ========================================

// 由于「修订」「草稿」「删除文章」,导致数据库id变得断续,该函数实现,对所有id重新从1开始排序.
add_action('admin_init', 'reset_posts_id_sequence_handler');

function reset_posts_id_sequence_handler() {
    // 权限检查（必须放在最前面）
    if (!current_user_can('manage_options')) {
        wp_die(
            '无权执行此操作',
            '权限错误',
            [
                'response' => 403,
                'back_link' => true
            ]
        );
    }
    
    // 选项检查
    if ( empty( my_option('opt_reset_id' ) ) ) {return;}
    
    // 执行核心逻辑
    reset_id_sequence();
}
// 核心重置函数
function reset_id_sequence() {
    global $wpdb;
    
    // 获取当前所有文章ID（按ID排序）
    $post_ids = $wpdb->get_col(
        "SELECT ID FROM $wpdb->posts ORDER BY ID"
    );
    
    if (empty($post_ids)) {
        error_log('没有找到需要处理的文章');
        return;
    }
    
    // 创建新ID映射（从1开始连续）
    $new_ids = range(1, count($post_ids));
    
    // 临时禁用外键检查
    $wpdb->query('SET FOREIGN_KEY_CHECKS = 0');
    
    // 启动事务
    $wpdb->query('START TRANSACTION');
    
    try {
        // 更新文章ID
        for ($i = 0; $i < count($post_ids); $i++) {
            $wpdb->update(
                $wpdb->posts,
                ['ID' => $new_ids[$i]],
                ['ID' => $post_ids[$i]],
                ['%d'],
                ['%d']
            );
            
            if ($wpdb->last_error) {
                throw new Exception('更新文章ID失败: ' . $wpdb->last_error);
            }
            
            // 更新相关元数据
            $wpdb->update(
                $wpdb->postmeta,
                ['post_id' => $new_ids[$i]],
                ['post_id' => $post_ids[$i]],
                ['%d'],
                ['%d']
            );
            
            if ($wpdb->last_error) {
                throw new Exception('更新元数据失败: ' . $wpdb->last_error);
            }
        }
        
        // 重置自增序列
        $next_id = max($new_ids) + 1;
        $wpdb->query(
            $wpdb->prepare(
                "ALTER TABLE $wpdb->posts AUTO_INCREMENT = %d",
                $next_id
            )
        );
        
        if ($wpdb->last_error) {
            throw new Exception('重置自增序列失败: ' . $wpdb->last_error);
        }
        
        // 提交事务
        $wpdb->query('COMMIT');
        error_log('ID序列重置成功，下一个ID为: ' . $next_id);
        
    } catch (Exception $e) {
        // 回滚事务
        $wpdb->query('ROLLBACK');
        error_log('操作失败: ' . $e->getMessage());
    }
    
    // 恢复外键检查
    $wpdb->query('SET FOREIGN_KEY_CHECKS = 1');
}



// ========================================
// . 网页复制 .
// ========================================
add_action('wp_enqueue_scripts', 'optimized_copy_protection');
// 复制控制处理器
function optimized_copy_protection() {
    static $copy_cache = null;
    
    // 内存优化：静态变量缓存配置（单次请求生命周期内仅获取1次）
    if ($copy_cache === null) {
        $copy = my_option('opt_copy_fieldset');
        $copy_cache = is_array($copy) ? $copy : [];
    }
    
    // 严格模式判断：仅当值为'1'时启用,避免多次函数调用
    if ($copy_cache['opt_copy_switch'] === '1') {
        // 钩子智能添加：避免重复注册
        if (!has_action('wp_footer', 'render_copy_protection_script')) {
            add_action('wp_footer', 'render_copy_protection_script');
        }
    } elseif ($copy_cache['opt_copyright_switch'] === '1') {
        // 参数封装：通过闭包传递配置值
        add_action('wp_footer', function() use ($copy_cache) {
            render_copyright_protection_script(
                $copy_cache['opt_copy_site'] ?? get_bloginfo('name'),
                $copy_cache['opt_copy_url'] ?? get_bloginfo('url')
            );
        });
    }
}

// 禁止复制模式脚本 * 核心功能：阻止默认复制行为、右键菜单、文本选择
function render_copy_protection_script() {
    static $script_rendered = false;
    if ($script_rendered) return; // 防止重复渲染
    
    $script = <<<JS
		<script type="text/javascript">
			// 禁止复制核心逻辑
			document.addEventListener('copy', e => e.preventDefault());
			// 禁用右键菜单（上下文菜单）
			document.addEventListener('contextmenu', e => e.preventDefault());
			// 禁用文本选择（防止拖动选择）
			document.addEventListener('selectstart', e => e.preventDefault());
		</script>
		JS;
    echo $script;
    $script_rendered = true;
}

// 版权保护模式脚本 * 核心功能：复制时自动追加版权声明
function render_copyright_protection_script($site_name, $site_url) {
    static $script_rendered = false;
    if ($script_rendered) return; // 防止重复渲染
    
    // 配置获取优化：直接使用上级传递的配置
    $site_name = wp_strip_all_tags($site_name);
    $site_url  = esc_url($site_url);
// 原文链接：{$site_url}
	$script =  <<<JS
		<script type="text/javascript">
		document.addEventListener('copy', event => {
			const userText = window.getSelection().toString();
			const copyrightNotice = `
			
			版权声明：本文来自 {$site_name}
			原文链接：\${document.location.href}
			转载请注明出处并保留原文链接`;
			
			event.clipboardData.setData('text/plain', userText + copyrightNotice);
			event.preventDefault();
		});
		</script>
		JS;
    echo $script;
    $script_rendered = true;
}
