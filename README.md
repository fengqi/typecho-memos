# typecho-memos
在 Typecho 中读取 Memos 列表

# 如何使用
下面是目录结构

```
.
├── LICENSE
├── README.md
├── plugins
│   └── Memos
│       └── Plugin.php
└── theme
    └── default
        └── memos.php

```

# 安装 

其中 plugins/Memos 放到 usr/plugins 下面，theme/default/pocket.php 放到主题目录下，上面的例子是默认主题，你可以根据你的主题进行调整。

# 配置

1. 打开 `Memos >> 设置 >> 我的设置` 复制 `OpenAPI` 下方输入框内的完整地址。
2. 进入`Typecho`后台，在 `控制台 >> 插件` 找到 `Memos` 点击 启用。
3. 在 `启用的插件` 里找到 `Memos` 点击后面的设置。
4. 在 `OpenAPI` 粘贴刚刚复制的 `OpenAPI` 地址，根据需要设置 `可见性` 和 `每页条数`，点击保存。

# 使用

进入后台，点击 "管理 >> 独立页面 >> 新增"，输入标题、自定义链接、描述、顺序等，选择模板为 `memos`，点击发布页面。
