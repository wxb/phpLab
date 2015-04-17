<?php
	/*
	 * filename: opcomplie.php
	 * author  : wangxb
	 * create  : 2015_04_17 11:53:30
	 * update  : @update
	 */


    // opcache_compile_file() 方法要求传入待缓存文件的路径，此时不需要运行这个带缓存文件，只需要执行这个方法即可在内存中缓存
    echo opcache_compile_file('./compileScript.php') ? '脚本成功缓存' : '脚本缓存失败';
