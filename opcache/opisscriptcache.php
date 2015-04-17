<?php
	/*
	 * filename: opisscriptcache.php
	 * author  : wangxb
	 * create  : 2015_04_17 13:14:16
	 * update  : @update
	 */

    //  我们就以前面已经在加载到内存中的opecho.php文件作为测试对象
    echo opcache_is_script_cached('./opecho.php') ? 'opecho.php脚本中间代码已经缓存' : 'opecho.php没有缓存中间代码opcode在内存中';


