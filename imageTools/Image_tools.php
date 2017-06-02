<?php
/**
 * Created by PhpStorm.
 * Project    : msgServer
 * File       : Image_tools.php
 * Author     : wangxb
 * Email      : wangxiaobo@parkingwang.com
 * Date       : 2017-06-01 13:22
 * Description:
 */

namespace Libs;


class Image_tools
{
    public function __construct()
    {
    }

    /**
     * @Function    save_base64_to_img
     * @Author      wangxb
     * @Email       wangxiaobo@parkingwang.com
     * @Description 保存一个base64编码成一个图片文件
     * @param $img  base64 编码的变量
     * @param $save_name 保存文件名，不包含文件名后缀
     * @param $save_dir  保存文件目录
     * @param bool $replace 存在同名文件是否替换，默认：否
     * @return bool true-保存成功；false-保存失败
     * @throws \Exception
     */
    public function save_base64_to_img($img, $save_name, $save_dir, $replace=false)
    {
        // 文件存储目录不存在，且创建文件存储目录失败时，报异常
        if(!is_dir($save_dir) && !mkdir($save_dir,0777,true)){
            throw new \Exception('保存文件目录创建失败！');
        }

        // 图片描述头信息
        $b64img = substr($img, 0,100);
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $b64img, $matches)){
            // 文件后缀
            $suffix = $matches[2];
            $image_type = array('jpg', 'gif', 'png', 'jpeg');
            if(!in_array($suffix, $image_type)){
                throw new \Exception('图片格式不正确，只支持 jpg、gif、png、jpeg！');
            }

            // 包含文件名的保存路径
            $file_path = rtrim($save_dir, '/') . '/' . trim($save_name, '/'). '.' . $suffix;
            if((false == $replace) && file_exists($file_path)){
                throw new \Exception('存在同名文件，保存失败！');
            }

            // 写入到指定文件中保存
            $img = str_replace($matches[1], '', $img);
            $save = file_put_contents($file_path, base64_decode($img));

            if(false === $save){
                throw new \Exception('图片保存失败！');
            }
        }else{
            throw new \Exception('无法解析该图片的编码！');
        }
    }

    /**
     * @Function    encode_img_to_base64
     * @Author      wangxb
     * @Email       wangxiaobo@parkingwang.com
     * @Description 编码图片为base64字符串
     * @param $file_path 图片路径
     * @return string
     * @throws \Exception
     */
    public function encode_img_to_base64($file_path)
    {
        if(!file_exists($file_path)){
            throw new \Exception('文件不存在！');
        }
        // 获取图片文件信息
        $image_info = getimagesize($file_path);
        $image_data = file_get_contents($file_path);

        // 成功打开文件
        if(false !== $image_data){
            $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
            return $base64_image;
        }else{
            throw new \Exception('无法打开指定文件！');
        }

    }

}