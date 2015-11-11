// 处理上传文件和图片信息数组，只保存文件名和文件路径
        $upData['supplyImg'] = array_map(function($v){
            $key = array('name'=>0, 'path'=>0);
            return array_intersect_key($v, $key);
        }, $upData['supplyImg']);