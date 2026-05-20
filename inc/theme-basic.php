<?php

/* ========== 基础设置 basic ========== */
/*
 * 主题基础设置模块
 * 
 * 模块说明：
 * - 特殊模式 (theme-basic-special)：哀悼模式、维护模式等
 * - 功能屏蔽 (theme-basic-block)：禁用不需要的 WordPress 功能
 * - 加速优化 (theme-basic-speed)：性能优化相关设置
 * - 扩展增强 (theme-basic-extend)：复制保护等扩展功能
 * - 伪静态：URL 重写规则配置
 */

CSF::createSection( $prefix, array(
    'id'        => 'basic_fields',
    'title'     => '基础设置',
    'icon'      => 'fas fa-globe',
) );

// 特殊模式
CSF::createSection( $prefix, array(
    'parent'        => 'basic_fields',
    'title'         => '特殊模式',
	'icon'			=> 'fas fa-arrow-right',
    'fields'        => array(
        array(
            'id'        => 'opt_rip',
            'type'      => 'switcher',
            'title'     => '哀悼模式',
            'subtitle'  => '首页黑白模式',
            'desc'		=> '支持自定义日期',
            'help'      => '重大悲痛日期开启以作纪念',
            'default'   => false
        ),
		array(
			'id'        => 'opt_rip_date',
			'type'      => 'datetime',
			'title'     => '设定日期',
			'subtitle'  => '可选设置',
			'desc' 		=> '设置开启和关闭时间',
			'from_to'   => true,
			'text_from' => 'From',
			'text_to'   => 'To',
			'settings' 	 => array(
				'enableTime' => true,
				'dateFormat' => 'Y-m-d H:i',
			),
			'dependency'  => array( 'opt_rip', '==', 'true', '', 'visible')
		),
        array(
            'id'        => 'opt_maintenance',
            'type'      => 'switcher',
            'title'     => '维护模式',
            'subtitle'  => '程序更新开启',
            'desc'      => '用户访问时展示自定义页面(管理员除外)',
            'default'   => false
        )
    )
) );


// 功能屏蔽
CSF::createSection( $prefix, array(
    'parent'        => 'basic_fields',
    'title'         => '功能屏蔽',
    'icon'			=> 'fas fa-arrow-right',
    'fields'        => array(
		array(
            'id'        => 'opt_block_common',
            'type'      => 'checkbox',
            'title'     => '常规功能',
            'subtitle'  => '屏蔽 WordPress 中一些用不上的功能',
            'options'   => array(
                'opt_revise' => '屏蔽文章修订功能',
                'opt_revision' => '清理所有文章修订版本',
                'opt_draft' => '删除已保存草稿以及inherit数据',
                'opt_xmlrpc' => '关闭XML-RPC功能,只在后台发布文章',
                'opt_auto_update' => '关闭自动更新功能,通过「仪表盘」手动「更新」',
                'opt_email_check' => '屏蔽站点管理员邮箱定期验证功能',
				'opt_screen_option'  => '移除「帮助」和「显示选项」按钮'
            ),
			'default'   => array('opt_revise', 'opt_xmlrpc', 'opt_auto_update', 'opt_email_check', 'opt_screen_option')
        ),
		array(
			'id'        => 'opt_block_func',
			'type'      => 'checkbox',
			'title'     => '函数禁用',
			'subtitle'  => '禁止访问 WordPress.org 获取版本信息',
			'options'   => array(
				'opt_translations_api' => '禁止查询翻译文件更新',
				'opt_wp_phpv' => '禁止检查 PHP 版本兼容性',
				'opt_check_browserv' => '禁止提交浏览器版本信息'
			),
			'default'   => array('opt_translations_api', 'opt_wp_phpv', 'opt_check_browserv')
		),
		array(
            'id'        => 'opt_block_transform',
            'type'      => 'checkbox',
            'title'     => '转换功能',
            'options'   => array(
                'opt_emoji' => '屏蔽Emoji转换成图片功能，直接使用Emoji',
                'opt_wptexturize' => '屏蔽字符转换成格式化的HTML实体功能',
                'opt_capitalization' => '屏蔽WordPress大小写修正，自行决定如何书写'
            ),
			'default'   => array('opt_emoji', 'opt_wptexturize', 'opt_capitalization')
        ),
		array(
			'id'        => 'opt_block_frontend',
			'type'      => 'checkbox',
			'title'     => '前台优化',
			'subtitle'  => '优化前端页面性能和安全性',
			'options'   => array(
				'opt_trackback' => '禁用 Trackback/Pingback，防止垃圾评论',
				'opt_feed' => '禁用 RSS Feed，防止内容被采集',		
				'opt_unuse_style' => '移除头部冗余 CSS/JS，提升加载速度'		
			),
			'default'   => array('opt_trackback', 'opt_feed', 'opt_unuse_style')
		),
		array(
			'id'        => 'opt_block_backend',
			'type'      => 'checkbox',
			'title'     => '后台优化',
			'subtitle'  => '简化后台界面，提升管理效率',
			'options'   => array(
				'opt_admin_logo'     => '移除 Admin Bar 中的 W 标志',
				'opt_home_bar'       => '隐藏前台顶部工具栏（管理员保留）',
				'opt_admin_bar' 	 => '隐藏后台顶部工具栏（包括管理员）',
				'opt_dashboard_tool' => '清理仪表盘小工具（保留欢迎面板）'			
			),
			'default'   => array('opt_admin_logo', 'opt_home_bar', 'opt_dashboard_tool')
		),
		array(
            'id'        => 'opt_block_page',
            'type'      => 'checkbox',
            'title'     => '页面功能',
            'subtitle'  => '删除不必要的后台菜单选项',
            'options'   => array(
                'opt_theme_menu'     => '移除「外观」菜单项(其它页面参考)',
                'opt_site_editor'    => '移除「外观-样板」菜单项页面及其相关资源加载',
                'opt_customize_manage'   => '移除「外观-自定义」菜单项页面及其相关资源加载',
                'opt_theme_editor'   => '移除「外观-主题文件编辑器」菜单项页面及其相关资源加载',
				'opt_site_health'    => '移除[工具-站点健康页面]「工具-个人数据」菜单项及仪表盘健康状态面板,',
				'opt_gdpr_privacy'   => '移除[设置-隐私]菜单项页面,由欧洲通用数据保护条例生成',
				'opt_editor_metabox'    => '移除[显示帮助]映射在经典编辑器下方的字段'
				
            ),
			'default'   => array('opt_site_editor', 'opt_customize_manage', 'opt_theme_editor', 'opt_site_health', 'opt_gdpr_privacy', 'opt_editor_metabox')
        ),
		array(
            'id'        => 'opt_block_embed',
            'type'      => 'checkbox',
            'title'     => '嵌入功能',
            'options'   => array(
                'opt_auto_embed' => '嵌入功能禁用Auto Embeds功能，加快页面解析速度',
                'opt_wp_embed' => '屏蔽嵌入其他WordPress文章的Embed功能'
            ),
			'default'   => array('opt_auto_embed', 'opt_wp_embed')
        ),
		array(
            'id'        => 'opt_block_editor',
            'type'      => 'checkbox',
            'title'     => '古腾堡编辑器',
            'subtitle'  => '根据个人习惯开启',
            'options'   => array(
                'opt_gutenberg_editor' => '屏蔽Gutenberg编辑器，换回经典编辑器',
                'opt_widget_editor' => '屏蔽小工具区块编辑器模式，切换回经典模式'
            ),
			'default'   => array('opt_gutenberg_editor', 'opt_widget_editor')
        ),
		array(
			'id'        => 'opt_custom_toolbar',
			'type'      => 'switcher',
			'title'     => '自定义后台工具栏',
			'subtitle'  => '自定义工具栏菜单项',
			'desc'		=> '启用前请确保「后台优化-隐藏后台工具栏」未勾选',
			'default'   => false
		),
		array(
            'id'        => 'opt_footerinfo_fieldset',
            'type'      => 'fieldset',
            'fields'    => array(
                array(
                    'type'      => 'subheading',
                    'content'   => '后台页脚信息定制'
                ),
                array(
					'id'        => 'opt_footer_info',
					'type'      => 'switcher',
					'title'     => '移除版本信息和标识',
					'subtitle'  => '位于WordPress后台底部左右',
					'desc'      => '支持自定义文字信息',
					'help'      => '「主题设置」页面限制不显示',
					'default'   => false
				),
                array(
					'id'        => 'opt_footer_info_left',
					'type'      => 'text',
					'title'     => '网站标识',
					'desc'      => '允许为空',
					'placeholder' => '感谢使用 WordPress 进行创作',
					'dependency'  => array( 'opt_footer_info', '==', 'true', '', 'visible' )
				),
				array(
					'id'        => 'opt_footer_info_right',
					'type'      => 'text',
					'title'     => '版本信息',
					'desc'      => '允许为空',
					'placeholder' => '6.8.5 版本',
					'dependency'  => array( 'opt_footer_info', '==', 'true', '', 'visible' )
				)
            )
        )

    )
) );

// 加速优化
CSF::createSection( $prefix, array(
    'parent'        => 'basic_fields',
    'title'         => '加速优化',
    'icon'			=> 'fas fa-arrow-right',
    'fields'        => array(
        array(
            'id'        => 'opt_execution_time',
            'type'      => 'switcher',
            'title'     => '页面执行时间',
            'subtitle'  => '显示在右下角',
            'desc'		=> '显示精确到0.0001秒的执行时间',
            'default'   => false
        ),
		array(
            'id'        => 'opt_jquery_migrate',
            'type'      => 'switcher',
            'title'     => '前台移除jQuery Migrate',
            'subtitle'  => '仅影响前台,后台功能正常',
            'desc'		=> '减少HTTP请求,节省带宽,加快页面加载速度',
            'default'   => true
        ),
        array(
            'id'        => 'opt_google_font',
            'type'      => 'switcher',
            'title'     => 'Google字体加速',
            'subtitle'  => '全面禁用WordPress中Google字体',
            'desc'		=> '回退到使用系统自带sans-serif字体',
            'default'   => true
        ),
        array(
            'id'        => 'opt_gravatar',
            'type'      => 'switcher',
            'title'     => 'Gravatar加速',
            'subtitle'  => '替换国内 Gravatar 头像加速',
            'default'   => true
        )
		
		
    )
) );



// 扩展增强
CSF::createSection( $prefix, array(
    'parent'        => 'basic_fields',
    'title'         => '扩展增强',
    'icon'			=> 'fas fa-arrow-right',
    'fields'        => array(
		array(
            'id'        => 'opt_editor_stat',
            'type'      => 'switcher',
            'title'     => '编辑器内容统计',
            'subtitle'  => '仅适用经典编辑器',
            'desc'      => '分别统计你即将发布的文字和图片',
			'help'		=> '编辑器页面,位置可调整,移动或代码修改',
            'default'   => true
        ),
		array(
            'id'        => 'opt_reset_id',
            'type'      => 'switcher',
            'title'     => '重置数据库ID',
            'subtitle'  => '为强迫症患者准备',
            'desc'      => '调整表[wp_posts]所有ID重新从「1」顺序排列',
			'help'		=> '风险巨大,请提前备份数据库',
            'default'   => false
        ),		      
        array(
            'id'        => 'opt_copy_fieldset',
            'type'      => 'fieldset',
            'fields'    => array(
                array(
                    'type'      => 'subheading',
                    'content'   => '网页复制'
                ),
                array(
                    'id'        => 'opt_copy_switch',
                    'type'      => 'switcher',
                    'title'     => '禁止复制',
                    'subtitle'  => '默认允许',
                    'desc'      => '开启后禁止[右键/文本选择]复制',
                    'help'      => '通过禁用JS可破解',
                    'text_on'   => '开启',
                    'text_off'  => '关闭',
                    'default'   => false
                ),
                array(
                    'id'        => 'opt_copyright_switch',
                    'type'      => 'switcher',
                    'title'     => '版权保护',
                    'subtitle'  => '复制时自动追加版权声明',
                    'desc'      => '开启后,可在下方设置版权内容',
                    'help'      => '这个开关和上面的,不能同时开启',
                    'text_on'   => '开启',
                    'text_off'  => '关闭',
                    'default'   => false
                ),
                array(
                    'id'        => 'opt_copy_site',
                    'type'      => 'text',
                    'title'     => '网站名称',
                    'subtitle'  => '允许为空',
                    'placeholder' => '美图库',
                    'dependency'  => array( 'opt_copyright_switch', '==', 'true', '', 'visible' )
                ),
                array(
                    'id'        => 'opt_copy_url',
                    'type'      => 'text',
                    'title'     => '网址信息',
                    'subtitle'  => '允许为空',
                    'desc'  	=> '已固定为主页网址,源码修改自定义',
                    'placeholder' => 'https://xxxxx.com/',
                    'dependency'  => array( 'opt_copyright_switch', '==', 'true', '', 'visible' )
                ),
                array(
                    'type'      => 'content',
                    'content'   => '
                        复制内容<br><br>
                        版权声明：本文来自 [网站名称]<br>
                        原文链接：[网址信息][http://50tu.cc/]<br>
                        转载请注明出处并保留原文链接
                    '
                )
            )
        )
		
    )
) );


/* ===== 伪静态 ===== */
CSF::createSection( $prefix, array(
	'parent'      => 'basic_fields',
	'title'       => '伪静态',
	'icon'			=> 'fas fa-arrow-right',
	'description' => '重要提示:必须先配置伪静态,再设置固定链接! 如果顺序颠倒,会导致页面404错误',
	'fields'      => array(
		array(
			'type'    => 'notice',
			'style'   => 'success',
			'content' => '在WordPress根目录创建或编辑.htaccess文件，添加以下规则:',
		),
		array(
			'id' => 'opt_rewrite_nginx',
			'type' => 'code_editor',
			'title' => 'Nginx',
			'sanitize' => false,
			'default' => '
				location / {
					try_files $uri $uri/ /index.php?$args;
				}
				rewrite /wp-admin$ $scheme://$host$uri/ permanent;
			'
		),
		array(
			'id' => 'opt_rewrite_apache',
			'type' => 'code_editor',
			'title' => 'Apache',
			'sanitize' => false,
			'default' => '
				# BEGIN WordPress
				<IfModule mod_rewrite.c>
				RewriteEngine On
				RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
				RewriteBase /
				RewriteRule ^index\.php$ - [L]
				RewriteCond %{REQUEST_FILENAME} !-f
				RewriteCond %{REQUEST_FILENAME} !-d
				RewriteRule . /index.php [L]
				</IfModule>
				# END WordPress
			'
		)		
	
	)
) );