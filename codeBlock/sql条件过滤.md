**经常在我们写模型方法时，对于需要拼接的where条件，我们需要做判断过滤**   

	
	public function getRegisterInfo($where_arr){
		$fileds = array('user_id', 'user_name', 'mobile', 'register_time', 'register_country', 'register_province', 'register_city');
		$fileds_flip = array_flip($fileds);
		//array_intersect_key — 使用键名比较计算数组的交集
		$where_arr = array_intersect_key($where_arr, $fileds_flip);
		$where_str = ' WHERE a.is_deleted=0';
		
		foreach ($where_arr as $k=>$v){
			if('register_time' == $k){ 
				$where_str .= " AND FROM_UNIXTIME(a.register_time,'%Y-%m-%d')='".$v."'";
			}else{
				$where_str .= " AND a.{$k}={$v}";
			}
		}
		
		$sql = 'SELECT a.user_id, b.user_name, a.mobile, a.mail, FROM_UNIXTIME(a.register_time,"%Y-%m-%d %H:%i:%s") AS register_time, a.register_ip, a.register_country, a.register_province, a.register_city  
				FROM user_account_info AS a LEFT JOIN user_basic_info AS b ON a.user_id=b.user_id
				'.$where_str;
		$result = $this->query($sql);
		echo $this->getLastSql(); exit();
		return $result;
	}


