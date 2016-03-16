# PHP开发实践小技巧积累    

## 优化冗长的：`if-elseif-else`     
在实际开发中经常会碰到需要对多种情况进行分别处理，这时候就会碰到类似的代码：    
```php
    if(){

    }elseif(){

    }elseif(){

    }elseif(){

    }elseif(){

    }elseif(){

    }elseif(){

    }elseif(){

    }elseif(){

    }elseif(){

    }
    ....
```

在碰到这种代码时，让人总是觉得很不专业，总是感觉不是辣么舒服（虽然没什么错）。对于这样的代码我们完全可以用`switch`来让一切变得更好看，更专业：    
```php
    $value = -1;
    switch (true) {
        case $value<0:
            // code...
            echo "$value < 0";
            break;
        case $value>0:
            echo "$value > 0";
            break;
        case $value=0:
            echo "$value = 0";
            break;
        default:
            // code...
            echo "I don't know";
            break;
    }
```
