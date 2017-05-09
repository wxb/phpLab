# FE前端
* view视图层模板采用模板继承，默认模板布局文件 `application/view/_layouts/default_layout.php`;     
    用法：
    ```php
    <!-- 继承父视图 开始 -->
    <?php  $this->_extends('_layouts/default_layout'); ?>
    <!-- 继承父视图 结束 -->

    <!-- 页面头部私有js和css 开始 -->
    <?php  $this->_block('head'); ?>
    <?php  $this->_endblock(); ?>
    <!-- 页面头部私有js和css 结束 -->

    <!-- 左侧菜单栏 开始 -->
    <?php  $this->_block('sidebar'); ?>
    <?php  $this->_endblock(); ?>
    <!-- 左侧菜单栏 结束 -->

    <!-- 页面内容 开始 -->
    <?php  $this->_block('page-content'); ?>
    <!-- PAGE CONTENT BEGINS -->

    <!-- PAGE CONTENT ENDS -->
    <?php  $this->_endblock(); ?>
    <!-- 页面内容 结束 -->

    <!-- 底部私有js 开始 -->
    <?php  $this->_block('footer-js'); ?>
    <?php  $this->_endblock(); ?>
    <!-- 底部私有js 结束 -->
    
    ```
    要继承使用默认模板布局，必须调用继承 `$this->_extends('_layouts/default_layout');`。  对于其他部分有需要就调用，没有可以不写
