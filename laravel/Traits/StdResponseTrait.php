<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Http\Response;

/**
 * 格式化API响应
 * 1. 支持响应码和消息定制
 * 2. 支持日志记录定制
 */
trait StdResponseTrait {

    // 响应内容记录日志
    abstract public function Recorder(string $content);


    private $_map = [
        'FAIL' => '操作失败',
        'SUCC' => '操作成功',
    ];

    /**
     * 多维数组转换为一维数组,使用 . 号code形式获取信息
     *
     * @param [type] $index
     * @param [type] $map
     * @return void
     */
    private function _convert($index, $map){
        foreach($map as $k=>$v){
            if(is_array($v)){
                $this->_convert($index . '.' . $k, $v);
            }else{
                $this->_map[trim($index . '.' . $k, '.')] = $v;
            }
        }
    }

    /**
     * 设置接口响应code与message对应数组
     *
     * @param [type] $map
     * @return void
     */
    public function setCodeMsg($map)
    {
        if(!is_array($map)){
            throw new Exception('响应码和响应消息不许是健值对数组类型');
        }

        $this->_convert('', $map);
        return $this;
    }

    /**
     * 返回一个HTTP响应
     *
     * @param [type] $content
     * @param array $headers
     * @param [type] $status
     * @return void
     */
    public function retRsponse($content, $headers = [], $status = Response::HTTP_OK)
    {
        // 记录响应信息到日志
        $this->Recorder(json_encode([
            'status'  => $status,
            'header'  => $headers,
            'content' => $content,
        ]));

        $response = new Response($content, $status);
        foreach($headers as $k=>$v){
            $response->header($k, $v);
        }
        return $response;
    }

    /**
     * json响应
     *
     * @param array $data
     * @return void
     */
    public function retJSON($data = [])
    {
        return $this->retRsponse($data,['Content-Type' => 'application/json']);
    }

    /**
     * jsonP响应
     *
     * @param [type] $code
     * @param string $msg
     * @param array $data
     * @param string $callback
     * @return void
     */
    public function retJSONP($code, $msg = '', $data = [], $callback = '')
    {
        if(empty($msg) && isset($this->_map[$code])){
            $msg = $this->_map[$code];
        }

        $content = response()->json([
            'code'    => $code,
            'message' => $msg,
            'data'    => $data
        ])->setCallback($callback)->getContent();
        return $this->retRsponse($content, ['Content-Type' => 'text/javascript; charset=UTF-8']);
    }

    /**
     * 接口响应
     *
     * @param [type] $data
     * @param [type] $code
     * @param string $msg
     * @return void
     */
    public function retAPI($data, $code, $msg='')
    {
        if(empty($msg) && isset($this->_map[$code])){
            $msg = $this->_map[$code];
        }

        return $this->retJSON([
            'code'    => $code,
            'message' => $msg,
            'data'    => $data
        ]);
    }

    /**
     * 接口成功响应
     *
     * @param array $data
     * @param string $msg
     * @return void
     */
    public function retSuccAPI($data=[], $msg='')
    {
        return $this->retAPI($data, 'SUCC', $msg);
    }

    /**
     * 接口失败响应
     *
     * @param array $data
     * @param string $msg
     * @return void
     */
    public function retFailAPI($data=[], $msg='')
    {
        return $this->retAPI($data, 'FAIL', $msg);
    }

}