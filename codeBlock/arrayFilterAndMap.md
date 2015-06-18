**php函数array_filter()和array_map()**   

* 函数原型  
```php
array_filter() 
  array array_filter ( array $input [, callable $callback = "" ] )
  依次将 input 数组中的每个值传递到 callback 函数。如果 callback 函数返回 TRUE，
  则 input 数组的当前值会被包含在返回的结果数组中。数组的键名保留不变。

array_map()
  array array_map ( callable $callback , array $arr1 [, array $... ] )
  array_map() 返回一个数组，该数组包含了 arr1 中的所有单元经过 callback 作用过之后的单元。
  callback 接受的参数数目应该和传递给 array_map() 函数的数组数目一致。

```
* 测试用例
  ```php
  // 定义一个数组
  $arr = array('1', 1, '', 0, '0', null, array());
  //　使用array_filter函数过滤掉值为空的、false、空数组等
  $new_arr = array_filter($arr, function($val){
  	return empty($val) ? false : true;
  	});
  print_r($new_arr);
  echo '----';
  // 使用array_map函数，修改数组中的每个单元
  print_r(array_map(function($v){
  	return empty($v) ? 1 : 2;
  	
  }, $arr));

  ```
  
  * 输出   
  ```php
  Array
  (
      [0] => 1
      [1] => 1
  )
  ----
  Array
  (
      [0] => 2
      [1] => 2
      [2] => 1
      [3] => 1
      [4] => 1
      [5] => 1
      [6] => 1
  )

  ```
  
* 总结
  array_filter是对数组过滤，根据回调函数返回true时保存数组单元，false时丢弃数组单元。但是注意array_filter接收的参数并不是
  参数引用，所以函数不会改变原数组，array_filter函数会返回一个处理过的数组。  
  array_map是对数组中每个单元的处理，返回一个经过处理后的数组。同样这个函数不会改变原数组。
  
* 再来一个引用传值的方法array_walk
  1. 方法原型   
  ```php
  bool array_walk ( array &$array , callable $funcname [, mixed $userdata = NULL ] )
  ```
  请注意第一个参数```php array &$array ``` 这里的 **&**
  2. 测试
  ```php
  <?php

  // your code goes here
  
  $arr = array('1', 1, '', 0, '0', null, array());
  // test1函数没有定义接收引用值
  function test1($v, $k){
  	if(empty($v)){
  		return 'this is null';
  	}
  	return $v;
  }
  // test2函数，接收数组的引用值，这样函数中的修改就直接作用到了原数组，请看下面结果
  function test2(&$v, $k){
  	if(empty($v)){
  		$v = 'this is null';
  	}
  }
  // 执行test1，打印原数组，可以在下面看到原数组没有改变
  array_walk($arr, 'test1');
  print_r($arr);
  // 执行test2，打印原数组，可以在下面看到原数组改变了
  array_walk($arr, 'test2');
  print_r($arr);
  
  ```
  
  3. 结果
  ```php
  // 执行test1，打印原数组，可以在下面看到原数组没有改变
      Array
      (
          [0] => 1
          [1] => 1
          [2] => 
          [3] => 0
          [4] => 0
          [5] => 
          [6] => Array
              (
              )
      
      )
      // 执行test2，打印原数组，可以在下面看到原数组改变了
      Array
      (
          [0] => 1
          [1] => 1
          [2] => this is null
          [3] => this is null
          [4] => this is null
          [5] => this is null
          [6] => this is null
      )
  ```
