###PHP判断数组相等

**一般情况下PHP中的 `==` 和 `===` 判断   
```php
$a + $b 联合 $a 和 $b 的联合。
$a == $b 相等 如果 $a 和 $b 具有相同的键／值对则为 TRUE。
$a === $b 全等 如果 $a 和 $b 具有相同的键／值对并且顺序和类型都相同则为 TRUE。
$a != $b 不等 如果 $a 不等于 $b 则为 TRUE。
$a <> $b 不等 如果 $a 不等于 $b 则为 TRUE。
$a !== $b 不全等 如果 $a 不全等于 $b 则为 TRUE。
```

**但是如果我们在判断一些索引数组时，数组是相等的，但是对应的键key不一样，也就是说顺序不一样，此时我们可以使用下面的方法判断**
```php
function judgeEqual($key1,$key2){

        $a = array_diff($key1,$key2);
        $b = array_diff($key2,$key1);
        if(empty($a) && empty($b)){
            return true;
        }else{
            return false;
        }
    }

```
