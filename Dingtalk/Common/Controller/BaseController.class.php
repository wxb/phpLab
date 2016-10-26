<?php

/**
 * Created by PhpStorm.
 * User: wangxb
 * Date: 16-10-18
 * Time: 上午10:38
 */

namespace Dingtalk\Common\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        $user = session('user');
        if(empty($user)) {  // 用户session信息不存在，重新进行钉钉授权登陆
            $this->redirect('/dingtalk.php/Auth/index','', 0,'正在请求授权登陆，请稍后！');
            exit();
        }
    }

}