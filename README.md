<div align="center">

## 美图库【Meitu-Gallery】 - 高清美图瀑布流展示

一个精美的响应式图片瀑布流展示网页，支持灯箱浏览、自动播放、键盘导航等功能。

</div>

## 📦 版本说明

- v3.0.0+ - 针对`codestar-framework`优化
- v2.0.0+ - 去插件`fancybox`，使用原生html,css布局
- v1.0.0+ - 完成瀑布流布局，实现基本功能

## ❤️ 主题预览

![预览图片](https://github.com/opai8/50tu/blob/main/screenshot/image1.png?raw=true)
![预览图片](https://github.com/opai8/50tu/blob/main/screenshot/image2.png?raw=true)

> 💡 移动端效果[单栏/双栏]

![预览图片](screenshot/iapp1.png?raw=true)
< OR >
![预览图片](screenshot/iapp2.png?raw=true)


## 📖 项目简介

**美图库**是一个现代化的图片展示网页，采用瀑布流布局呈现高清图片。项目具有以下特点：

- 🎨 **精美界面设计** - 紫色渐变主题，优雅的视觉效果
- 📱 **完全响应式** - 完美适配桌面端、平板和手机
- 🖼️ **瀑布流布局** - 自动适应不同尺寸图片，错落有致的排列
- 💡 **灯箱浏览** - 点击图片全屏查看，支持切换、自动播放
- ⌨️ **键盘导航** - 支持方向键、ESC、空格等快捷键操作
- 🚀 **流畅动画** - 精心设计的过渡效果，提升用户体验

### 交互优化

- **悬浮箭头**：灯箱内左右两侧悬浮导航箭头
- **键盘快捷键**：
  - `←` / `→`：上一张/下一张
  - `ESC`：关闭灯箱
  - `空格`：播放/暂停
  - `F`：全屏切换
- **返回顶部**：滚动后显示返回顶部按钮
- **布局切换**：移动端支持单栏/双栏切换

### 响应式设计

- **桌面端（>1200px）**：4列瀑布流布局
- **平板端（768px-1200px）**：3列瀑布流布局
- **移动端（<768px）**：2列瀑布流，可切换单栏
- **小屏幕（<480px）**：优化 Header 布局，隐藏社交链接文字

## ✨ 项目结构

```text
50tu/

├── header.php               # 头部模板
├── index.php                # 首页模板
├── footer.php               # 底部模板

├── style.css                # 主题头信息与全局样式
├── functions.php            # 主题入口与资源注册
├── screenshot.png           # 后台主题截图
├── README.md                # 项目说明

├── asset/                  # font-awesome / jquery / script
├── fancybox/                # 首页瀑布流布局与预览
├── image/	                 # 图片资源
├── 
├── inc/
| |-- codestar-framework/ 		# 后台主题设置框架
| |-- docx/ 					# 文档上传功能
| |-- watercolor/				# 水印功能
└── 
```

## 环境要求

- WordPress `5.6+`
- PHP `7.2+`
- MySQL `5.6+`
- 支持现代浏览器（Chrome、Firefox、Safari、Edge）

## 安装方式

### 方式一：从 Release 安装
1. 在 [Releases](https://github.com/opai8/50tu/releases) 下载 `50tu.zip`
2. 进入 WordPress 后台 `外观 -> 主题 -> 添加主题 -> 上传主题`
3. 上传并启用 `50tu`
4. 进入 `设置 -> 固定链接`，直接点击一次“保存更改”刷新重写规则

### 方式二：手动安装
1. 将主题目录放入 `wp-content/themes/`
2. 进入后台启用 `50tu`
3. 同样执行一次 `设置 -> 固定链接 -> 保存更改`

## 使用手册

### 发布图集

将图片批量插入`文档`, 「写文章」页面`选择文件`,上传文档

## 开发信息

#### 🛠️ 技术栈

- 前端：HTML5, CSS3 (CSS 变量), JavaScript (ES6+)
- JS库：jQuery 3.x
- 后端：PHP 7.4+, WordPress 5.8+
- 框架：codestar-framework v2.3.1 Free Version

## 📧 联系方式

如有问题或建议，欢迎通过以下方式联系：

- 提交 [Issue](https://github.com/opai8/Meitu-Gallery/issues)
- 发送邮件至：hongyexs@gmail.com

## ⭐ 支持项目

如果这个项目对你有帮助，请给它一个 ⭐ Star！

---

<div align="center">

**Made with ❤️ by 美图库团队**

Copyright &copy; 2026 美图库 All Rights Reserved.

</div>
