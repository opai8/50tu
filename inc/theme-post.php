<?php

/* ========== 附件管理 ========== */

CSF::createSection( $prefix, array(
	'title'     => '附件管理',
	'icon'      => 'fas fa-cloud-upload-alt',
	'fields'        => array(
		array(
            'id'        => 'opt_schedule_post',
            'type'      => 'switcher',
            'title'     => '定时发布',
            'subtitle'  => '统一发布时间',
            'desc'  	=> '仅限首次发布,更新不算,时间固定为[tomorrow 00:01]',
            'default'   => true
        ),		
		array(
            'id'        => 'opt_docx',
            'type'      => 'switcher',
            'title'     => 'DOCX文档上传',
            'subtitle'  => '编辑器页面底部',
            'desc'      => '文档直接插入编辑器',
            'help'      => '确保其顶部「显示选项」是勾选状态',
            'default'   => true
        ),
		array(
            'id'        => 'opt_image_size',
            'type'      => 'switcher',
            'title'     => '禁止生成缩略图',
            'subtitle'  => '拒绝生成多种尺寸图片资源',
            'desc'      => '避免冗余系统资源',
            'help'      => '推荐打开项目',
            'default'   => true
        ),
		array(
            'id'        => 'opt_imagedelete',
            'type'      => 'switcher',
            'title'     => '删除文章图片',
            'subtitle'  => '启用/禁用',
            'desc'      => '删除文章时同步删除关联文件',
            'help'      => '文章移至回收站不会删除文件,回收站里永久删除才会删除文件',
            'default'   => true
        ),
		array(
            'id'        => 'opt_watercolor',
            'type'      => 'switcher',
            'title'     => '添加水印',
            'subtitle'  => '为上传图片添加水印',
            'desc'      => '添加专属标识,可以防盗图',
            'help'      => '启用后,保存配置,刷新页面,可看到水印设置功能',
            'default'   => false
        ),
		array(
            'id'        => 'opt_image_rename',
            'type'      => 'switcher',
            'title'     => '文件重命名',
            'subtitle'  => '防止大量的SQL查询',
            'desc'      => '格式:时间戳_随机字符串_索引.扩展名',
            'help'      => '示例：20260311120000_AbCdEfGh1234_1.jpg',
            'default'   => true
        ),
        array(
            'id'        => 'opt_image_url',
            'type'      => 'switcher',
            'title'     => '文件存储路径',
            'subtitle'  => '媒体文件存放在当日文件夹下',
            'desc'      => '文件默认存储在「/年/月/」文件夹下,现在「/年/月/日/」',
            'help'      => '每日多文件站点建议开启',
            'default'   => true
        ),
		array(
            'id'        => 'opt_post_keep',
            'type'      => 'switcher',
            'title'     => '清理旧文章保留最新',
            'subtitle'  => '定时任务,只保留最新N篇',
            'desc'      => '相关媒体文件也会被删除',
            'default'   => false
        ),
		array(
            'id'          => 'opt_post_keep_num',
            'type'        => 'number',
            'title'       => '日期关联',
            'subtitle'    => '范围为:1~100',
            'unit'        => 'width',
            'default'     => '50',
            'dependency'  => array( 'opt_post_keep', '==', 'true' )
        ),
		array(
            'id'        => 'opt_cleanup_unused',
            'type'      => 'switcher',
            'title'     => '清理未使用附件',
            'subtitle'  => '每日凌晨3点自动清理',
            'desc'      => '请谨慎选择,有可能误删重要文件',
            'help'      => '仅限上传至媒体库文件,通过其他手段(FTP)直接上传至uploads文件夹,无法在媒体库展览，亦无法通过该手法删除',
            'default'   => false
        ),
        array(
            'id'          => 'opt_day_threshold',
            'type'        => 'number',
            'title'       => '日期关联',
            'subtitle'    => '范围为:0~N',
            'desc'        => '保留最近N天的文件',
            'help'        => '',
            'unit'        => 'day',
            'default'     => '0',
            'dependency'  => array( 'opt_cleanup_unused', '==', 'true' )
        ),

    )
) );
