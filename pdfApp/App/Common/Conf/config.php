<?php
return array(
	//'配置项'=>'配置值'
	// 显示页面Trace信息
	'SHOW_PAGE_TRACE' =>true,
	'TMPL_PARSE_STRING'=>array( //添加自己的模板变量规则
	  '__CSS__'=>__ROOT__.'/Public/Css',
	  '__JS__'=>__ROOT__.'/Public/Js',
	  '__IMG__'=>__ROOT__.'/Public/images',
	 )
);
