<?php

/* ========== 页面布局 page ========== */
/*
 * 文章xx theme-page-xxxa
 * 文章xx theme-page-xxx
 * 文章xx theme-page-xxx
 * 文章xx theme-page-xxx
 */


CSF::createSection( $prefix, array(
	'id'        => 'page_fields',
	'title'     => '页面布局',
	'icon'      => 'fas fa-rocket',
) );


/* ===== SEO ===== */
CSF::createSection( $prefix, array(
    'parent'        => 'page_fields',
    'title'         => 'SEO',
    'icon'			=> 'fas fa-arrow-right',
    'fields'        => array(
        array(
            'id' => 'seo_link',
            'type' => 'select',
            'title' => 'SEO连接符',
			'subtitle' => '标题-描述',
            'desc' => 'SEO标题连接符（ 一般为 - 或 _ 或者 | ）',
			'placeholder' => '请选择',
            'options' => array(
				'option-1' => ' - ',
				'option-2' => ' _ ',
				'option-3' => ' | '
			),
			'default' => 'option-1'
        ),
		array(
            'id' => 'seo_title',
            'type' => 'text',
            'title' => 'SEO标题(title)',
			'subtitle' => '站点一句话描述的吸引力标题',
			'desc' => '自定义网站的SEO标题(title)',
			'help' => '建议25-35字,如果未设置，则采用「站点-副标题」',
            'default' => '美图库'
        ),
        array(
            'id' => 'seo_keyword',
            'type' => 'text',
            'title' => 'SEO关键字(keyword)',
			'subtitle' => '关键字有利于SEO优化',
			'desc' => '自定义网站的SEO关键字(keyword)',
			'help' => '建议个数在5-8之间，用英文逗号隔开',
            'default' => '妹子图,萌妹子图片,清纯美女,软妹子,妹纸图,可爱美女,美女图片,私房写真,福利美图,萌妹子图片免费下载,美之图,50美图库'
        ),
		array(
            'id' => 'seo_description',
            'type' => 'textarea',
            'title' => 'SEO描述(description)',
            'subtitle' => '自定义网站的SEO描述',
			'help' => '介绍,描述您的网站,建议字数在40-70之间',
			'default' => '每天分享50张精选美图！',
			'attributes'  => array(
                    'rows' => 3,
					'placeholder' => 'hello world'
                ),
            'sanitize'    => false
        ),
		array(
            'id' => 'seo_robots_fieldset',
            'type' => 'fieldset',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => 'robots.txt 配置',
                ),
                array(
                    'type' => 'content',
                    'content' => '
						<ul>
							<li>需开启<a href="' . admin_url('options-reading.php') . '" target="_blank">设置-阅读-对搜索引擎的可见性</a>，以下配置才会生效</li>
							<li>若网站根目录已存在robots.txt文件，手动复制以下配置粘贴</li>
							<li>访问<a href="' . home_url() . '/robots.txt" target="_blank">/robots.txt</a>验证配置是否生效（开启CDN时需刷新缓存）</li>
						</ul>
					',
                ),
				array(
					'type'    => 'notice',
					'style'   => 'success',
					'content' => '站点地图： /sitemap.xml ',
				),
                array(
                    'id' => 'seo_robots',
                    'type' => 'code_editor',
                ),
            ),
            'default' => array(
                'seo_robots' => getrobots(),
            )
			
        ),
		
    )
) );

// ========================================
// robots.txt 配置--通过函数直接获得
// ========================================
function getrobots() {
    $site_url = parse_url(site_url());
    $web_url = get_bloginfo('url');
    $path = trim($site_url['path'] ?? '', '/');
    $base_path = $path ? "/{$path}/" : '/';

    $robots = "User-agent: *\n";  // 适用于所有搜索引擎爬虫
    
    // ================= 允许规则 =================
    $robots .= "# 允许媒体文件访问\n";
    $robots .= "Allow: /wp-content/uploads/  # 允许媒体文件\n\n";
    
    // ================= 禁止规则 =================
    $robots .= "# 核心禁止规则\n";
    
    $disallow_rules = [
        ['path' => "{$base_path}wp-admin/",          'comment' => '禁止后台管理'],
        ['path' => "{$base_path}wp-includes/",       'comment' => '禁止核心系统文件'],
        ['path' => "{$base_path}wp-content/plugins/", 'comment' => '禁止插件目录'],
        ['path' => "{$base_path}wp-content/themes/", 'comment' => '禁止主题目录'],
        ['path' => "{$base_path}wp-login.php",       'comment' => '禁止登录页'],
        ['path' => "{$base_path}wp-register.php",    'comment' => '禁止注册页'],
        ['path' => "{$base_path}readme.html",        'comment' => '禁止查看WordPress版本信息'],
        ['path' => "{$base_path}license.txt",        'comment' => '禁止查看许可证'],
        ['path' => "{$base_path}wp-config-sample.php", 'comment' => '禁止配置示例'],
        ['path' => "{$base_path}?s=",               'comment' => '禁止站内搜索'],
        ['path' => "{$base_path}search/",           'comment' => '禁止伪静态搜索页面'],
        ['path' => "{$base_path}trackback/",        'comment' => '禁止trackback垃圾'],
        ['path' => "{$base_path}feed",              'comment' => '禁止主RSS feed'],
        ['path' => "{$base_path}*/feed",            'comment' => '禁止所有feed'],
        ['path' => "{$base_path}comments/feed",     'comment' => '禁止评论feed'],
        ['path' => "{$base_path}attachment/",       'comment' => '禁止图片附件页'],
        ['path' => "{$base_path}?replytocom=",      'comment' => '禁止评论回复链接'],
        ['path' => "{$base_path}category/*/page/",  'comment' => '禁止分类分页'],
        ['path' => "{$base_path}tag/*/page/",       'comment' => '禁止标签分页'],
        ['path' => "{$base_path}*/page/",           'comment' => '禁用通用分页']
    ];
    
    foreach ($disallow_rules as $rule) {
        $clean_path = str_replace('//', '/', $rule['path']);
        $robots .= "Disallow: " . $clean_path . "  #" . $rule['comment'] . "\n";
    }
    
    // ================= 允许资源文件 =================
    $robots .= "\n# 允许资源文件访问\n";
    $robots .= "Allow: *.js  # 允许JavaScript文件\n";
    $robots .= "Allow: *.css  # 允许CSS样式文件\n\n";
    
    // ================= 站点地图 =================
    $robots .= "# 站点地图声明\n";
    $sitemaps = [
        '/sitemap.xml',                  // 主站点地图
        '/post-sitemap.xml',             // 文章站点地图
        '/page-sitemap.xml',             // 页面站点地图
        '/category-sitemap.xml',         // 分类站点地图
        '/tag-sitemap.xml'               // 标签站点地图
    ];
    
    foreach ($sitemaps as $map) {
        $full_url = set_url_scheme($web_url . $map);
        $robots .= "Sitemap: " . $full_url . "  #" . basename($map) . "\n";
    }
    
    return $robots;
}



/* ===== 图标LOGO ===== */
CSF::createSection( $prefix, array(
    'parent'        => 'page_fields',
    'title'         => '图标LOGO',
    'icon'			=> 'fas fa-arrow-right',
    'fields'        => array(
		array(
            'id' => 'opt_bar_title',
            'type' => 'text',
            'title' => '站点标题',
			'subtitle' => '后台顶部标题文字',
			'desc' => '覆盖「设置-常规-站点标题」修改',
			'help' => '仅后台可见',
            'default' => 'Meitu-Gallery'
        ),
        array(
			'id' => 'opt_favicon',
			'type' => 'upload',
			'title'  => '网站ico',
			'subtitle' => '状态栏favicon',
			'desc' => '自定义网站图标，也就是favicon.ico(建议48x48)',
            'default' => get_template_directory_uri().'/image/favicon.png',
			'preview' => true,
            'library' => 'image'			
		),
		array(
			'id' => 'opt_logo_day',
			'type' => 'upload',
			'title'  => '网站LOGO',
			'subtitle' => '日间主题',
			'desc' => '建议高度60px，请使用png格式的透明图片',
            'default' => get_template_directory_uri().'/image/logo.png',
			'preview' => true,
            'library' => 'image'
		),
		array(
			'id' => 'opt_content_fzf',
			'type' => 'upload',
			'title'  => '404 页面图片',
			'subtitle' => '图片显示出来是 404 的形状',
			'desc' => '建议高度px，请使用png格式的透明图片',
            'default' => get_template_directory_uri().'/image/404_1.png',
			'preview' => true,
            'library' => 'image'
		)
		
    )
) );


/* ===== 首页设置 ===== */
CSF::createSection( $prefix, array(
    'parent'        => 'page_fields',
    'title'         => '首页设置',
    'icon'			=> 'fas fa-arrow-right',
    'fields'        => array(
		array(
            'id'        => 'opt_declare',
            'type'      => 'fieldset',
			'fields'	=> array(
				array(
                    'type' => 'subheading',
                    'content' => '顶部声明',
                ),
                array(
                    'id' => 'opt_telegram',
                    'type' => 'text',
                    'title' => 'Telegram群组',
					'default' => 'https://t.me/yoyonv'
                ),
				array(
                    'id' => 'opt_github',
                    'type' => 'text',
                    'title' => 'Github项目',
                    'default' => 'https://github.com/opai8/Meitu-Gallery'
                ),
				array(
                    'id' => 'opt_email',
                    'type' => 'text',
                    'title' => 'Email邮箱',
                    'default' => 'hongyexs@gmail.com'
                ),
			)
        ),
        array(
            'id' => 'footer_ending',
            'type' => 'text',
            'title' => '说明',
			'subtitle' => '底部结束语',
            'default' => '明天，我们不见不散！'
        ),
		array(
            'id' => 'footer_copyright',
            'type' => 'textarea',
            'title' => '版权信息',
            'default' => 'COPYRIGHT &copy; 2018 - 2026 . All Rights Reserved.'
        ),
		array(
            'id' => 'footer_note',
            'type' => 'textarea',
            'title' => '版权声明',
            'default' => '本站所有图片均来源于网络，仅供欣赏，如有侵权请联系删除。'
        ),
		array(
			'id'       => 'site_statistical',
			'type'     => 'code_editor',
			'title'    => '统计代码',
			'subtitle' => '<span style="color:red">输入代码时请注意辨别代码安全性</span>',
			'settings' => array(
				'theme'  => 'default',
				'mode'   => 'javascript',
			),
			'sanitize' => false,
            'default' => '<script></script>'
		)
		
    )
) );

