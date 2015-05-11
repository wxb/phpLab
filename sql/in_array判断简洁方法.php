    <?php
    public function update_row($dataArr, $whereArr)
    {
        if(empty($dataArr) || empty($whereArr)) return false;
        $user_fields = array(
                                't_user_name',
                                't_user_email',
                                't_user_phone',
                                't_user_password',
                                't_user_status',
                                't_user_address1',
                                't_user_address2',
                                't_user_address3',
                                'm_port_id',
                                'm_country_id',
                                't_user_admin',
                                't_user_delete',
                                'created',
                                'modified'
                        );
        // array_intersect()  返回一个数组，该数组包含了所有在 array1 中也同时出现在所有其它参数数组中的值。注意键名保留不变
        // 更加详细的说明学习php手册
        if(count(array_intersect($user_fields, array_keys($dataArr))) != count($dataArr) || 
            count(array_intersect($user_fields, array_keys($whereArr))) != count($whereArr))
        {
                return false;
        }
        $this->load->model('user/Db_user');
        $this->Db_user->updateRow($dataArr, $whereArr);
    }



    // 任务： 测试上面的方法很普通方法的执行效率