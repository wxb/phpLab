<?php
	
	// 假设这里有一个这样的url
	$url = "http://web.com?name=wangxb&mail=670@qq.com\中文名:王小波#男! %imooc + 慕课";

	// 使用urlencode编码
	echo urlencode($url);		
	// http%3A%2F%2Fweb.com%3Fname%3Dwangxb%26mail%3D670%40qq.com%5C%E4%B8%AD%E6%96%87%E5%90%8D%3A%E7%8E%8B%E5%B0%8F%E6%B3%A2%23%E7%94%B7%21+%25imooc+%2B+%E6%85%95%E8%AF%BE

	echo '<hr/>';

	// 使用urldecode解码
	echo urldecode('http%3A%2F%2Fweb.com%3Fname%3Dwangxb%26mail%3D670%40qq.com%5C%E4%B8%AD%E6%96%87%E5%90%8D%3A%E7%8E%8B%E5%B0%8F%E6%B3%A2%23%E7%94%B7%21+%25imooc+%2B+%E6%85%95%E8%AF%BE');
	// http://web.com?name=wangxb&mail=670@qq.com\中文名:王小波#男! %imooc + 慕课

	echo '<hr/>';
	// 我们来试试百度的url，当我们搜索“wang xiao bo”时，
	// url是这样：http://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&tn=93395212_hao_pg&wd=wang%20xiao%20bo&rsv_pq=b99bbe50000188e2&rsv_t=037a0w07Tfa%2BbGpK%2BhvIPK%2FMBS96E8gpUuSaZAbV6PnYkByYwH2DVIilm1o5%2FYgolw2sk07S&rsv_enter=0
	echo rawurldecode('http://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&tn=93395212_hao_pg&wd=wang%20xiao%20bo&rsv_pq=b99bbe50000188e2&rsv_t=037a0w07Tfa%2BbGpK%2BhvIPK%2FMBS96E8gpUuSaZAbV6PnYkByYwH2DVIilm1o5%2FYgolw2sk07S&rsv_enter=0');
	// 输出：http://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&tn=93395212_hao_pg&wd=wang xiao bo&rsv_pq=b99bbe50000188e2&rsv_t=037a0w07Tfa+bGpK+hvIPK/MBS96E8gpUuSaZAbV6PnYkByYwH2DVIilm1o5/Ygolw2sk07S&rsv_enter=0
	// 我们看到我们的空格被转化成了 %20 我们就知道百度这里是用的是rawurlencode函数编码
	
