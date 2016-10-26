<?php
/**
 * Created by PhpStorm.
 * User: wangxb
 * Date: 16-10-22
 * Time: 下午4:00
 */

namespace Dingtalk\Controller;

use \Think\Controller;
use Dingtalk\Common\Api\Auth as Auth;
use Dingtalk\Common\Api\User as User;

/**
 * 登陆信息校验 控制器
 * Class AuthController
 * @package Dingtalk\Controller
 */
class AuthController extends Controller
{
    /**
     * js校验 获取CODE
     * @throws \Exception
     */
    public function index()
    {
        $dingConfig = Auth::getConfig();
        $this->assign('dingtalk_config', json_encode($dingConfig, JSON_UNESCAPED_SLASHES));
        $this->display();
    }

    /**
     * 获取用户信息，注册用户信息到session
     */
    public function login()
    {
        $code = I('code', '');
        $accessToken = Auth::getAccessToken();
        $userInfo = User::getUserInfo($accessToken, $code);
        (empty($userInfo['userid'])) && $this->error('用户信息验证不正确，登陆失败！','/dingtalk.php/Auth/logout');
        $userDtail = User::getUserDetail($accessToken, $userInfo['userid']);
        $user = array('email'=>$userDtail['email']);
        session('user', $user);
        $this->success('用户信息验证完成，登陆成功！', '/dingtalk.php/Index/index');
    }

    /**
     * 退出系统
     */
    public function logout()
    {
        session('[destroy]'); // 销毁session
        $this->display();
    }

}