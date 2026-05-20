<?php

/* ========== 主题简介 intro ========== */

CSF::createSection( $prefix, array(
    'title'      => '主题简介',
    'icon'       => 'fas fa-home',
    'fields'     => array(
        array(
            'type'   => 'subheading',
            'title'  => '主题介绍',
        ),
        array(
            'type'    => 'submessage',
            'style'   => 'success',
            'content' => '个人开发的第一个 WordPress 主题',
        ),
        array(
            'type'    => 'subheading',
            'content' => '系统环境'
        ),
        array(
            'type'    => 'content',
            'content' => '<div style="margin-left:20px;">
                    <li><strong>操作系统</strong>： ' . PHP_OS . ' </li>
                    <li><strong>运行环境</strong>： ' . $_SERVER["SERVER_SOFTWARE"] . ' </li>
                    <li><strong>PHP版本</strong>： ' . PHP_VERSION . ' </li>
                    <li><strong>PHP上传限制</strong>： ' . ini_get('upload_max_filesize') . ' </li>
                    <li><strong>WordPress版本</strong>： ' . $wp_version . '</li>
                    <li><strong>Codestar Framework</strong>： v2.3.1 free </li>
                    <li><strong>服务器时间</strong>： ' . current_time('mysql') . '</li>
                    </div>'
        ),
        array(
            'type'    => 'subheading',
            'content' => '版权声明',
        ),
        array(
            'type'    => 'content',
            'content' => '主题源码地址 <a href="https://github.com/opai8/Meitu-Gallery" target="_blank">GitHub项目</a>, 使用 MIT License 开源协议进行许可',
        ),
        array(
            'type'    => 'subheading',
            'content' => '讨论交流',
        ),
        array(
            'type' => 'content',
            // 'content' => '<div style="max-width:800px;"><img style="width: 100%;height: auto;" src="' . get_template_directory_uri() . '/assets/img/options/discuss.png"></div>',
        ),
        array(
            'type'    => 'subheading',
            'content' => '打赏支持',
        ),
        array(
            'type' => 'content',
            // 'content' => '<div style="max-width:800px;"><img style="width: 100%;height: auto;" src="' . get_template_directory_uri() . '/assets/img/options/donate.png"></div>',
        )
    )
) );
