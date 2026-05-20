<?php
/**
 * Word 文档转换器
 * 支持在编辑器中直接上传 DOCX/DOC 文件并转换为 HTML
 *
 * @author Jim King <hongyexs@gmail.com>
 * @license MIT License
 */

// ============================================================================
// 注册元框：在文章编辑器底部显示上传区域
// ============================================================================
function mammoth_add_post_meta_box() {
    $post_types = get_post_types();

    foreach ($post_types as $post_type) {
        // 仅在支持编辑器的文章类型显示
        if (post_type_supports($post_type, 'editor')) {
            add_meta_box(
                'mammoth_add_post',
                'DOCX文档转换器',
                'mammoth_render_editor_box',
                $post_type
            );
        }
    }
}

// ============================================================================
// 加载样式文件
// ============================================================================
function mammoth_admin_style($hook) {
    // 仅在文章编辑页面加载
    if (in_array($hook, ['post.php', 'post-new.php'])) {
        wp_enqueue_style(
            'mammoth-style',
            get_template_directory_uri() . '/inc/docx/mammoth.css',
            [],
            '1.3.0'
        );
    }
}

// ============================================================================
// 加载 JavaScript 文件
// ============================================================================
function mammoth_load_javascript() {
    $js_dir = get_template_directory_uri() . '/inc/docx/';

    // 主脚本：处理文档转换
    wp_enqueue_script(
        'mammoth-editor',
        $js_dir . 'mammoth-editor.js?v=1.21.0',
        ['jquery', 'wp-util'],
        '1.21.0',
        true
    );

    // 标签页切换脚本
    wp_enqueue_script(
        'mammoth-tabs',
        $js_dir . 'tabs.js?v=1.21.0',
        ['jquery'],
        '1.21.0',
        true
    );
}

// ============================================================================
// 渲染上传界面 HTML
// ============================================================================
function mammoth_render_editor_box($post) {
    ?>
    <div id="mammoth-docx-uploader" class="status-empty">

        <!-- 文件选择区域 -->
        <div>
            <label>
                Select docx file:
                <input type="file" id="mammoth-docx-upload" />
            </label>
        </div>

        <!-- 状态提示 -->
        <div id="mammoth-docx-loading">Loading...</div>
        <div id="mammoth-docx-inserting">Inserting...</div>

        <!-- 错误提示 -->
        <p class="mammoth-docx-error">
            Error while attempting to convert file:
            <span id="mammoth-docx-error-message"></span>
        </p>

        <!-- 预览和插入区域 -->
        <div class="mammoth-docx-preview">

            <!-- WordPress 必需的隐藏字段 -->
            <input type="hidden"
                id="mammoth-docx-upload-image-nonce"
                value="<?php echo wp_create_nonce('media-form'); ?>" />
            <input type="hidden"
                id="mammoth-docx-upload-image-href"
                value="<?php echo get_site_url(null, 'wp-admin/async-upload.php', 'admin'); ?>" />
            <input type="hidden"
                id="mammoth-docx-admin-ajax-href"
                value="<?php echo get_site_url(null, 'wp-admin/admin-ajax.php', 'admin'); ?>" />

            <!-- 插入按钮 -->
            <p>
                <input type="button" id="mammoth-docx-insert" class="mammoth-insert-btn" value="Insert into editor" />
            </p>

            <!-- 预览标签页 -->
            <div class="mammoth-tabs">
                <div class="tab">
                    <h4>Visual</h4>
                    <iframe
                        id="mammoth-docx-visual-preview"
                        src="about:blank"
                        data-stylesheets="<?php echo mammoth_editor_stylesheets_list(); ?>">
                    </iframe>
                </div>
                <div class="tab">
                    <h4>Raw HTML</h4>
                    <pre id="mammoth-docx-raw-preview"></pre>
                </div>
                <div class="tab">
                    <h4>Messages</h4>
                    <div id="mammoth-docx-messages"></div>
                </div>
            </div>

        </div>

    </div>
    <?php
}

// ============================================================================
// 获取编辑器样式表列表
// ============================================================================
function mammoth_editor_stylesheets_list() {
    $styles = get_editor_stylesheets();
    return !empty($styles) ? implode(',', $styles) : '';
}

// ============================================================================
// AJAX 处理函数（预留接口）
// ============================================================================
add_action('wp_ajax_mammoth_docx_convert', 'handle_docx_conversion');

function handle_docx_conversion() {
    if (!wp_verify_nonce($_REQUEST['_nonce'], 'media-form')) {
        wp_send_json_error('无效请求', 403);
    }

    try {
        wp_send_json_success([
            'html' => '<p>模拟的DOCX转换内容</p>',
            'messages' => ['转换成功']
        ]);
    } catch (Exception $e) {
        wp_send_json_error($e->getMessage(), 500);
    }
}

// ============================================================================
// 注册 WordPress 钩子
// ============================================================================
add_action('add_meta_boxes', 'mammoth_add_post_meta_box');
add_action('admin_head', 'mammoth_load_javascript');
add_action('admin_enqueue_scripts', 'mammoth_admin_style', 10, 1);
