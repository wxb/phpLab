本函数一个有趣的用法是构造一个数组的数组，这可以很容易的通过用 NULL 作为回调函数名来实现。

Example #4 建立一个数组的数组

```php
<?php
$a = array(1, 2, 3, 4, 5);
$b = array("one", "two", "three", "four", "five");
$c = array("uno", "dos", "tres", "cuatro", "cinco");

$d = array_map(null, $a, $b, $c);
print_r($d);
?>
以上例程会输出：

Array
(
    [0] => Array
        (
            [0] => 1
            [1] => one
            [2] => uno
        )

    [1] => Array
        (
            [0] => 2
            [1] => two
            [2] => dos
        )

    [2] => Array
        (
            [0] => 3
            [1] => three
            [2] => tres
        )

    [3] => Array
        (
            [0] => 4
            [1] => four
            [2] => cuatro
        )

    [4] => Array
        (
            [0] => 5
            [1] => five
            [2] => cinco
        )

)
```
如果数组参数里面有字符串的键，那么返回的数组就会包含字符串的键，仅且仅当只传入一个数组的时候（试了下，就是原数组返回，没变化啊，这不是蛋疼么？）。 如果不止一个数组被传入，那么返回的数组的的键都是整型。
