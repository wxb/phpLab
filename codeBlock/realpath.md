## realpath()

### 定义
string realpath ( string $path )  
返回规范化的绝对路径名

realpath() 扩展所有的符号连接并且处理输入的 path 中的 '/./', '/../' 以及多余的 '/' 并返回规范化后的绝对路径名。
返回的路径中没有符号连接，'/./' 或 '/../' 成分

### 注意
**realpath() 失败时返回 FALSE，比如说文件不存在的话。也就是说：realpath只能对存在的路径进行规范化**   

### 规范任何路径   
对于某些不存在的路径，使用下面函数可以规范化--[摘自php手册](http://php.net/manual/zh/function.realpath.php#112367)
```php
Needed a method to normalize a virtual path that could handle .. references that go beyond the initial folder reference. So I created the following.
<?php

function normalizePath($path)
{
    $parts = array();// Array to build a new path from the good parts
    $path = str_replace('\\', '/', $path);// Replace backslashes with forwardslashes
    $path = preg_replace('/\/+/', '/', $path);// Combine multiple slashes into a single slash
    $segments = explode('/', $path);// Collect path segments
    $test = '';// Initialize testing variable
    foreach($segments as $segment)
    {
        if($segment != '.')
        {
            $test = array_pop($parts);
            if(is_null($test))
                $parts[] = $segment;
            else if($segment == '..')
            {
                if($test == '..')
                    $parts[] = $test;

                if($test == '..' || $test == '')
                    $parts[] = $segment;
            }
            else
            {
                $parts[] = $test;
                $parts[] = $segment;
            }
        }
    }
    return implode('/', $parts);
}
?>

Will convert /path/to/test/.././..//..///..///../one/two/../three/filename
to ../../one/three/filename

```

### 实例

`var_dump(normalizePath('home/work/../ubuntu/1234/1234//22345/../../abc'));`

输出

`string(20) "home/ubuntu/1234/abc"`
