/**
 * 编辑器统计 - 通用模块
 * 修复：古腾堡自动初始化
 */
var EditorStats = (function($) {
    'use strict';

    var CONFIG = {
        classic: {
            textarea: '#content',
            editor: 'content'
        },
        gutenberg: {
            subscribe: 'core/editor'
        }
    };

    // 📊 核心统计（纯函数，无副作用）
    function countStats(content) {
        var text = content.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
        return {
            words: text.length,
            images: (content.match(/<img[^>]*>/gi) || []).length
        };
    }

    // 🔄 更新显示（仅操作DOM）
    function updateDisplay(stats) {
        var $word = $('#word-count');
        var $image = $('#image-count');
        
        if ($word.length) $word.text(stats.words);
        if ($image.length) $image.text(stats.images);
    }

    // 🎮 经典编辑器初始化
    function initClassic() {
        var $textarea = $(CONFIG.classic.textarea);
        
        function getContent() {
            if (typeof tinyMCE !== 'undefined' && tinyMCE.get(CONFIG.classic.editor)) {
                return tinyMCE.get(CONFIG.classic.editor).getContent();
            }
            return $textarea.val();
        }

        function refresh() {
            updateDisplay(countStats(getContent()));
        }

        // 事件委托（减少绑定次数）
        $textarea.on('input keyup', refresh);
        
        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.get(CONFIG.classic.editor).on('change keyup nodechange', refresh);
        }

        // 模式切换时刷新
        $(document).on('click', '.switch-tmce, .switch-html', function() {
            setTimeout(refresh, 100);
        });

        refresh();
    }

    // 🎮 古腾堡编辑器初始化（修复版）
    function initGutenberg() {
        var select = wp.data.select;
        var subscribe = wp.data.subscribe;

        function refresh() {
            var content = select(CONFIG.gutenberg.subscribe).getEditedPostContent();
            if (content) {
                updateDisplay(countStats(content));
            }
        }

        // ✅ 修复：订阅内容变化（自动触发，无需wp.domReady）
        subscribe(refresh);
        
        // 初始化
        refresh();
    }

    // 🚀 公共接口
    return {
        init: function(type) {
            if (type === 'classic') initClassic();
            if (type === 'gutenberg') initGutenberg();
        }
    };

})(jQuery);
