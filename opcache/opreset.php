<?php
	/*
	 * filename: opreset.php
	 * author  : wangxb
	 * create  : 2015_04_17 02:44:25
	 * update  : @update
	 */


// 当我们在调用这个文件中 opcache_reset() 方法之后，我们不用等待 60秒，此时 所有脚本会重新走一次php标准的流程，也就相当于我们修改后的脚本重新解释执行 然后将修改后的脚本的中间代码 字节码 opcode加载到内存中，我们就可以看到我们的修改效果了
	echo opcache_reset() ? '成功重置缓存内容' : '重置失败'; 
