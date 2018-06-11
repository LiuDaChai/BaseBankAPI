项目配置说明：

为了便捷开发, 使用了 ThinkPHP 框架快速搭建网站的基础。
网站默认访问的根目录为 /public
如，Apache下配置的虚拟站点为

<VirtualHost *.*.*.*:80>
    DocumentRoot "[路径]/public/"
    ServerName *.*.*.*
</VirtualHost>

====================================================

开发说明：

1、数据库设计文件 [地址]/BaseBank.sql
2、测试接口地址 [地址]/test.php
3、接口代码都放置在目录 /application/bankapi/ 中，controller 是逻辑过程