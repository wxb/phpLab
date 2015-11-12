<?php

class HttpRequest{
    private $httpClient;
    private $response;

    public function __construct(){
        //Create an instance
        $this->httpClient = new \Leaps\HttpClient\Adapter\Curl();
    }

    public function getResponseInfo($url, $id=''){
        if(empty($url)) throw new Exception('请输入请求URL地址');
        !empty($id) && ($id = '?id='.$id);
        $this->response = $this->httpClient->get($url.$id);
        if(!$this->response->isOk()) throw new Exception('请求失败');
        if('text/json' == $this->response->getContentType()){
            return json_decode($this->response->getContent(), true);
        }else{
            $result = $this->response->getContent();
        }
        return $result;
    }

    public function postResponseInfo($url, $data){
        if(empty($url)) throw new Exception('请输入请求URL地址');
        if(!is_array($data) || empty($data)) throw new Exception('请求数据必须为一维非空数组');
        $this->response = $this->httpClient->post($url, $data);
        if(!$this->response->isOk()) throw new Exception('请求失败');
        if('text/json' == $this->response->getContentType()){
            return json_decode($this->response->getContent(), true);
        }else{
            $result = $this->response->getContent();
        }
        return $result;
    }
}
