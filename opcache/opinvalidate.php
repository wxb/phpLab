<?php
    // opcache_invalidate() 方法中有两个参数：
    // args: 1、 缓存需要被作废对应的脚本路径
    //       2、 布尔型，true表示 强制作废， false或者缺省值，表示文件更新后才失效
    echo opcache_invalidate('./opecho1.php', TRUE) ? '操作成功' : '操作失败';




