<?php
/**
 * 编辑器字数图片统计 - 主题式插件
 * 特点：自带开关判断，外部无需关心
 */

if (!defined('ABSPATH')) exit;

// ========================================
// 📏 定义路径常量
// ========================================
define('EDITOR_STATS_DIR', get_stylesheet_directory() . '/inc/editor-stat');
define('EDITOR_STATS_URL', get_stylesheet_directory_uri() . '/inc/editor-stat');

// ========================================
// 🚪 总开关
// ========================================
if ( empty( my_option('opt_editor_stat' ) ) ) {
    return;
}

// ========================================
// ✅ 以下代码只在选项开启时执行
// ========================================

// 📦 经典编辑器统计
add_action('add_meta_boxes', function() {
    add_meta_box(
        'editor_stats',
        '📊 内容统计',
        'render_classic_editor_stats',
        'post',
        'side',
        'high'
    );
});

function render_classic_editor_stats() {
    ?>
    <div id="editor-stats" class="editor-stats-box">
        <div class="stat-row">
            <span class="stat-label">📝 字数统计：</span>
            <span id="word-count" class="stat-value word">0</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">🖼️ Image:</span>
            <span id="image-count" class="stat-value image">0</span>
        </div>
        <div class="stat-hint">⚡ 实时统计中...</div>
    </div>

    <script src="<?php echo EDITOR_STATS_URL; ?>/editor-stats.js"></script>
    <script>
    jQuery(document).ready(function($) {
        EditorStats.init('classic');
    });
    </script>
    <?php
}

// 🟦 古腾堡编辑器统计（修复版）
add_action('enqueue_block_editor_assets', function() {
    // 加载JS
    wp_enqueue_script(
        'editor-stats',
        EDITOR_STATS_URL . '/editor-stats.js',
        ['wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-data'],
        '1.0.0',
        true
    );
    
    // 加载CSS
    wp_enqueue_style(
        'editor-stats',
        EDITOR_STATS_URL . '/editor-stats.css',
        array(),
        '1.0.0'
    );
});

// 🎨 加载CSS（经典编辑器也需要）
add_action('admin_head', function() {
    ?>
    <link rel="stylesheet" href="<?php echo EDITOR_STATS_URL; ?>/editor-stats.css">
    <?php
});
