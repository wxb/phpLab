PHP编码规范 - PSR

本文档是PHP互操作性框架制定小组（[PHP-FIG][] :PHP Framework Interoperability Group）制定的PHP编码规范（[PSR][]:Proposing a Standards Recommendation）


* [PHP-FIG官方](http://www.php-fig.org/psr/) | [GitHub](https://github.com/php-fig)

* 本文档摘自-[PHP-FIG PSR中文版](https://github.com/PizzaLiu/PHP-FIG)
* 更加详细资料请点这里-[PHP 标准规范](https://psr.phphub.org/)



----- 

# PSR-0:自动加载规范

====================



> **此规范已被弃用** - 本规范已于2014年10月21日被标记为弃用，目前最新的替代规范为 [PSR-4] 。



本文是为`自动加载器（autoloader）`实现通用自动加载，所需要遵循的编码规范。



规范说明

---------



* 一个标准的 命名空间(namespace) 与 类(class) 名称的定义必须符合以下结构：

`\<Vendor Name>\(<Namespace>\)*<Class Name>`；

* 其中`Vendor Name`为每个命名空间都必须要有的一个顶级命名空间名；

* 需要的话，每个命名空间下可以拥有多个子命名空间；

* 当根据完整的命名空间名从文件系统中载入类文件时，每个命名空间之间的分隔符都会被转换成文件夹路径分隔符；

* 类名称中的每个 `_` 字符也会被转换成文件夹路径分隔符，而命名空间中的 `_` 字符则是无特殊含义的。

* 当从文件系统中载入标准的命名空间或类时，都将添加 `.php` 为目标文件后缀；

* `组织名称(Vendor Name)`、`命名空间(Namespace)` 以及 `类的名称(Class Name)` 可由任意大小写字母组成。



范例

--------



* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`

* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`

* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`

* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`



命名空间以及类名称中的下划线

-----------------------------------------



* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`

* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`





以上是使用通用自动加载必须遵循的最低规范标准， 可通过以下的示例函数 SplClassLoader 载入 PHP 5.3 的类文件，来验证你所写的命名空间以及类是否符合以上规范。



实例

----------------------



以下示例函数为本规范的一个简单实现。



```php

<?php



function autoload($className)

{

    $className = ltrim($className, '\\');

    $fileName  = '';

    $namespace = '';

    if ($lastNsPos = strrpos($className, '\\')) {

        $namespace = substr($className, 0, $lastNsPos);

        $className = substr($className, $lastNsPos + 1);

        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;

    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';



    require $fileName;

}

```



SplClassLoader 实例

-----------------------------



以下的 gist 是 一个 SplClassLoader 类文件的实例，如果你遵循了以上规范，可以把它用来载入你的类文件。 这也是目前 PHP 5.3 建议的类文件载入方式。



* [http://gist.github.com/221634](http://gist.github.com/221634)



 ---- 

# PSR-1:基本代码规范
=====================

本篇规范制定了代码基本元素的相关标准，
以确保共享的PHP代码间具有较高程度的技术互通性。

关键词 “必须”("MUST")、“一定不可/一定不能”("MUST NOT")、“需要”("REQUIRED")、
“将会”("SHALL")、“不会”("SHALL NOT")、“应该”("SHOULD")、“不该”("SHOULD NOT")、
“推荐”("RECOMMENDED")、“可以”("MAY")和”可选“("OPTIONAL")的详细描述可参见 [RFC 2119][] 。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/PizzaLiu/PHP-FIG/blob/master/PSR-0-cn.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


1. 概览
-----------

- PHP代码文件**必须**以 `<?php` 或 `<?=` 标签开始；

- PHP代码文件**必须**以 `不带BOM的 UTF-8` 编码；

- PHP代码中**应该**只定义类、函数、常量等声明，或其他会产生 `从属效应` 的操作（如：生成文件输出以及修改.ini配置文件等），二者只能选其一；

- 命名空间以及类**必须**符合 PSR 的自动加载规范：[PSR-4][]；

- 类的命名**必须**遵循 `StudlyCaps` 大写开头的驼峰命名规范；

- 类中的常量所有字母都**必须**大写，单词间用下划线分隔；

- 方法名称**必须**符合 `camelCase` 式的小写开头驼峰命名规范。


2. 文件
--------

### 2.1. PHP标签

PHP代码**必须**使用 `<?php ?>` 长标签 或 `<?= ?>` 短输出标签；
**一定不可**使用其它自定义标签。

### 2.2. 字符编码

PHP代码**必须**且只可使用`不带BOM的UTF-8`编码。

### 2.3. 从属效应（副作用）

一份PHP文件中**应该**要不就只定义新的声明，如类、函数或常量等不产生从属效应的操作，要不就只有会产生从属效应的逻辑操作，但**不该**同时具有两者。

“从属效应”(side effects)一词的意思是，仅仅通过包含文件，不直接声明类、
函数和常量等，而执行的逻辑操作。

“从属效应”包含却不仅限于：生成输出、直接的 `require` 或 `include`、连接外部服务、修改 ini 配置、抛出错误或异常、修改全局或静态变量、读或写文件等。

以下是一个错误的例子，一份包含声明以及产生从属效应的代码：

```php
<?php
// 从属效应：修改 ini 配置
ini_set('error_reporting', E_ALL);

// 从属效应：引入文件
include "file.php";

// 从属效应：生成输出
echo "<html>\n";

// 声明函数
function foo()
{
    // 函数主体部分
}
```

下面是一个范例，一份只包含声明不产生从属效应的代码：

```php
<?php
// 声明函数
function foo()
{
    // 函数主体部分
}

// 条件声明**不**属于从属效应
if (! function_exists('bar')) {
    function bar()
    {
        // 函数主体部分
    }
}
```


3. 命名空间和类
----------------------------

命名空间以及类的命名必须遵循 [PSR-4][]。

根据规范，每个类都独立为一个文件，且命名空间至少有一个层次：顶级的组织名称（vendor name）。

类的命名必须 遵循 `StudlyCaps` 大写开头的驼峰命名规范。

PHP 5.3及以后版本的代码**必须**使用正式的命名空间。

例如：

```php
<?php
// PHP 5.3及以后版本的写法
namespace Vendor\Model;

class Foo
{
}
```

5.2.x及之前的版本**应该**使用伪命名空间的写法，约定俗成使用顶级的组织名称（vendor name）如 `Vendor_` 为类前缀。

```php
<?php
// 5.2.x及之前版本的写法
class Vendor_Model_Foo
{
}
```

4. 类的常量、属性和方法
-------------------------------------------

此处的“类”指代所有的类、接口以及可复用代码块（traits）

### 4.1. 常量

类的常量中所有字母都**必须**大写，词间以下划线分隔。
参照以下代码：

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. 属性

类的属性命名可以遵循 大写开头的驼峰式 (`$StudlyCaps`)、小写开头的驼峰式 (`$camelCase`) 又或者是 下划线分隔式 (`$under_score`)，本规范不做强制要求，但无论遵循哪种命名方式，都**应该**在一定的范围内保持一致。这个范围可以是整个团队、整个包、整个类或整个方法。

### 4.3. 方法

方法名称**必须**符合 `camelCase()` 式的小写开头驼峰命名规范。


---- 

# PSR-2:代码风格规范
==================

本篇规范是 [PSR-1][] 基本代码规范的继承与扩展。

本规范希望通过制定一系列规范化PHP代码的规则，以减少在浏览不同作者的代码时，因代码风格的不同而造成不便。

当多名程序员在多个项目中合作时，就需要一个共同的编码规范，
而本文中的风格规范源自于多个不同项目代码风格的共同特性，
因此，本规范的价值在于我们都遵循这个编码风格，而不是在于它本身。

关键词 “必须”("MUST")、“一定不可/一定不能”("MUST NOT")、“需要”("REQUIRED")、
“将会”("SHALL")、“不会”("SHALL NOT")、“应该”("SHOULD")、“不该”("SHOULD NOT")、
“推荐”("RECOMMENDED")、“可以”("MAY")和”可选“("OPTIONAL")的详细描述可参见 [RFC 2119][] 。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/PizzaLiu/PHP-FIG/blob/master/PSR-0-cn.md
[PSR-1]: https://github.com/PizzaLiu/PHP-FIG/blob/master/PSR-1-basic-coding-standard-cn.md


1. 概览
-----------

- 代码**必须**遵循 [PSR-1][] 中的编码规范 。

- 代码**必须**使用4个空格符而不是 tab键 进行缩进。

- 每行的字符数**应该**软性保持在80个之内， 理论上**一定不可**多于120个， 但**一定不能**有硬性限制。

- 每个 `namespace` 命名空间声明语句和 `use` 声明语句块后面，**必须**插入一个空白行。

- 类的开始花括号(`{`)**必须**写在函数声明后自成一行，结束花括号(`}`)也**必须**写在函数主体后自成一行。

- 方法的开始花括号(`{`)**必须**写在函数声明后自成一行，结束花括号(`}`)也**必须**写在函数主体后自成一行。

- 类的属性和方法**必须**添加访问修饰符（`private`、`protected` 以及 `public`）， `abstract` 以及 `final` **必须**声明在访问修饰符之前，而 `static` **必须**声明在访问修饰符之后。
  
- 控制结构的关键字后**必须**要有一个空格符，而调用方法或函数时则**一定不能**有。

- 控制结构的开始花括号(`{`)**必须**写在声明的同一行，而结束花括号(`}`)**必须**写在主体后自成一行。

- 控制结构的开始左括号后和结束右括号前，都**一定不能**有空格符。

### 1.1. 例子

以下例子程序简单地展示了以上大部分规范：

```php
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function sampleFunction($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // method body
    }
}
```

2. 通则
----------

### 2.1 基本编码准则

代码**必须**符合 [PSR-1][] 中的所有规范。

### 2.2 文件

所有PHP文件**必须**使用`Unix LF (linefeed)`作为行的结束符。

所有PHP文件**必须**以一个空白行作为结束。

纯PHP代码文件**必须**省略最后的 `?>` 结束标签。

### 2.3. 行

行的长度**一定不能**有硬性的约束。

软性的长度约束**一定**要限制在120个字符以内，若超过此长度，带代码规范检查的编辑器**一定**要发出警告，不过**一定不可**发出错误提示。

每行**不应该**多于80个字符，大于80字符的行**应该**折成多行。

非空行后**一定不能**有多余的空格符。

空行**可以**使得阅读代码更加方便以及有助于代码的分块。

每行**一定不能**存在多于一条语句。

### 2.4. 缩进

代码**必须**使用4个空格符的缩进，**一定不能**用 tab键 。

> 备注: 使用空格而不是tab键缩进的好处在于，
> 避免在比较代码差异、打补丁、重阅代码以及注释时产生混淆。
> 并且，使用空格缩进，让对齐变得更方便。

### 2.5. 关键字 以及 True/False/Null

PHP所有 [关键字][]**必须**全部小写。

常量 `true` 、`false` 和 `null` 也**必须**全部小写。

[关键字]: http://php.net/manual/en/reserved.keywords.php



3. namespace 以及 use 声明
---------------------------------

`namespace` 声明后 必须 插入一个空白行。

所有 `use` 必须 在 `namespace` 后声明。

每条 `use` 声明语句 必须 只有一个 `use` 关键词。

`use` 声明语句块后 必须 要有一个空白行。

例如：

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... additional PHP code ...

```


4. 类、属性和方法
-----------------------------------

此处的“类”泛指所有的class类、接口以及traits可复用代码块。

### 4.1. 扩展与继承

关键词 `extends` 和 `implements`**必须**写在类名称的同一行。

类的开始花括号**必须**独占一行，结束花括号也**必须**在类主体后独占一行。

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constants, properties, methods
}
```

`implements` 的继承列表也**可以**分成多行，这样的话，每个继承接口名称都**必须**分开独立成行，包括第一个。

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constants, properties, methods
}
```

### 4.2. 属性

每个属性都**必须**添加访问修饰符。

**一定不可**使用关键字 `var` 声明一个属性。

每条语句**一定不可**定义超过一个属性。

**不要**使用下划线作为前缀，来区分属性是 protected 或 private。

以下是属性声明的一个范例：

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. 方法

所有方法都**必须**添加访问修饰符。

**不要**使用下划线作为前缀，来区分方法是 protected 或 private。

方法名称后**一定不能**有空格符，其开始花括号**必须**独占一行，结束花括号也**必须**在方法主体后单独成一行。参数左括号后和右括号前**一定不能**有空格。

一个标准的方法声明可参照以下范例，留意其括号、逗号、空格以及花括号的位置。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```    

### 4.4. 方法的参数

参数列表中，每个逗号后面**必须**要有一个空格，而逗号前面**一定不能**有空格。

有默认值的参数，**必须**放到参数列表的末尾。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```

参数列表**可以**分列成多行，这样，包括第一个参数在内的每个参数都**必须**单独成行。

拆分成多行的参数列表后，结束括号以及方法开始花括号 必须 写在同一行，中间用一个空格分隔。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // method body
    }
}
```

### 4.5. `abstract` 、 `final` 、 以及 `static`

需要添加 `abstract` 或 `final` 声明时， **必须**写在访问修饰符前，而 `static` 则**必须**写在其后。

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // method body
    }
}
```

### 4.6. 方法及函数调用

方法及函数调用时，方法名或函数名与参数左括号之间**一定不能**有空格，参数右括号前也 **一定不能**有空格。每个逗号前**一定不能**有空格，但其后**必须**有一个空格。

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

参数**可以**分列成多行，此时包括第一个参数在内的每个参数都**必须**单独成行。

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. 控制结构
---------------------

控制结构的基本规范如下：

- 控制结构关键词后**必须**有一个空格。
- 左括号 `(` 后**一定不能**有空格。
- 右括号 `)` 前也**一定不**能有空格。
- 右括号 `)` 与开始花括号 `{` 间**一定**有一个空格。
- 结构体主体**一定**要有一次缩进。
- 结束花括号 `}` **一定**在结构体主体后单独成行。

每个结构体的主体都**必须**被包含在成对的花括号之中，
这能让结构体更加结构话，以及减少加入新行时，出错的可能性。


### 5.1. `if` 、 `elseif` 和 `else`

标准的 `if` 结构如下代码所示，留意 括号、空格以及花括号的位置，
注意 `else` 和 `elseif` 都与前面的结束花括号在同一行。

```php
<?php
if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
```

**应该**使用关键词 `elseif` 代替所有 `else if` ，以使得所有的控制关键字都像是单独的一个词。 


### 5.2. `switch` 和 `case`

标准的 `switch` 结构如下代码所示，留意括号、空格以及花括号的位置。
`case` 语句**必须**相对 `switch` 进行一次缩进，而 `break` 语句以及 `case` 内的其它语句都 必须 相对 `case` 进行一次缩进。
如果存在非空的 `case` 直穿语句，主体里必须有类似 `// no break` 的注释。

```php
<?php
switch ($expr) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Third case, return instead of break';
        return;
    default:
        echo 'Default case';
        break;
}
```


### 5.3. `while` 和 `do while`

一个规范的 `while` 语句应该如下所示，注意其 括号、空格以及花括号的位置。

```php
<?php
while ($expr) {
    // structure body
}
```

标准的 `do while` 语句如下所示，同样的，注意其 括号、空格以及花括号的位置。

```php
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for`

标准的 `for` 语句如下所示，注意其 括号、空格以及花括号的位置。

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach`
    
标准的 `foreach` 语句如下所示，注意其 括号、空格以及花括号的位置。

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch`

标准的 `try catch` 语句如下所示，注意其 括号、空格以及花括号的位置。

```php
<?php
try {
    // try body
} catch (FirstExceptionType $e) {
    // catch body
} catch (OtherExceptionType $e) {
    // catch body
}
```

6. 闭包
-----------

闭包声明时，关键词 `function` 后以及关键词 `use` 的前后都**必须**要有一个空格。

开始花括号**必须**写在声明的同一行，结束花括号**必须**紧跟主体结束的下一行。


参数列表和变量列表的左括号后以及右括号前，**必须不能**有空格。

参数和变量列表中，逗号前**必须不能**有空格，而逗号后**必须**要有空格。

闭包中有默认值的参数**必须**放到列表的后面。


标准的闭包声明语句如下所示，注意其 括号、逗号、空格以及花括号的位置。

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

参数列表以及变量列表**可以**分成多行，这样，包括第一个在内的每个参数或变量都**必须**单独成行，而列表的右括号与闭包的开始花括号**必须**放在同一行。

以下几个例子，包含了参数和变量列表被分成多行的多情况。

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // body
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // body
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};
```

注意，闭包被直接用作函数或方法调用的参数时，以上规则仍然适用。

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // body
    },
    $arg3
);
```


7. 总结
--------------
以上规范难免有疏忽，其中包括但不仅限于：

- 全局变量和常量的定义

- 函数的定义

- 操作符和赋值

- 行内对齐

- 注释和文档描述块

- 类名的前缀及后缀

- 最佳实践

本规范之后的修订与扩展将弥补以上不足。


#PSR-3: 日志接口规范
================

本文制定了日志类库的通用接口规范。

本规范的主要目的，是为了让日志类库以简单通用的方式，通过接收一个 `Psr\Log\LoggerInterface` 对象，来记录日志信息。
框架以及CMS内容管理系统如有需要，**可以**对此接口进行扩展，但需遵循本规范，
这才能保证在使用第三方的类库文件时，日志接口仍能正常对接。

关键词 “必须”("MUST")、“一定不可/一定不能”("MUST NOT")、“需要”("REQUIRED")、
“将会”("SHALL")、“不会”("SHALL NOT")、“应该”("SHOULD")、“不该”("SHOULD NOT")、
“推荐”("RECOMMENDED")、“可以”("MAY")和”可选“("OPTIONAL")的详细描述可参见 [RFC 2119][] 。

本文中的 `实现者` 指的是实现了 `LoggerInterface` 接口的类库或者框架，反过来讲，他们就是 `LoggerInterface` 的 `使用者`。

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. 规范说明
-----------------

### 1.1 基本规范

- `LoggerInterface` 接口对外定义了八个方法，分别用来记录 [RFC 5424][] 中定义的八个等级的日志：debug、 info、 notice、 warning、 error、 critical、 alert 以及 emergency 。

- 第九个方法 —— `log`，其第一个参数为记录的等级。可使用一个预先定义的等级常量作为参数来调用此方法，**必须**与直接调用以上八个方法具有相同的效果。如果传入的等级常量参数没有预先定义，则**必须**抛出 `Psr\Log\InvalidArgumentException` 类型的异常。在不确定的情况下，使用者**不该**使用未支持的等级常量来调用此方法。

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 记录信息

- 以上每个方法都接受一个字符串类型或者是有 `__toString()` 方法的对象作为记录信息参数，这样，实现者就能把它当成字符串来处理，否则实现者**必须**自己把它转换成字符串。

- 记录信息参数**可以**携带占位符，实现者**可以**根据上下文将其它替换成相应的值。

  其中占位符**必须**与上下文数组中的键名保持一致。

  占位符的名称**必须**由一个左花括号 `{` 以及一个右括号 `}` 包含。但花括号与名称之间**一定不能**有空格符。

  占位符的名称**应该**只由 `A-Z`、 `a-z`,`0-9`、下划线 `_`、以及英文的句号 `.`组成，其它字符作为将来占位符规范的保留。

  实现者**可以**通过对占位符采用不同的转义和转换策略，来生成最终的日志。
  而使用者在不知道上下文的前提下，**不该**提前转义占位符。

  以下是一个占位符使用的例子：

  ```php
  /**
   * 用上下文信息替换记录信息中的占位符
   */
  function interpolate($message, array $context = array())
  {
      // 构建一个花括号包含的键名的替换数组
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // 替换记录信息中的占位符，最后返回修改后的记录信息。
      return strtr($message, $replace);
  }

  // 含有带花括号占位符的记录信息。
  $message = "User {username} created";

  // 带有替换信息的上下文数组，键名为占位符名称，键值为替换值。
  $context = array('username' => 'bolivar');

  // 输出 "Username bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 上下文

- 每个记录函数都接受一个上下文数组参数，用来装载字符串类型无法表示的信息。它**可以**装载任何信息，所以实现者**必须**确保能正确处理其装载的信息，对于其装载的数据，**一定不能** 抛出异常，或产生PHP出错、警告或提醒信息（error、warning、notice）。

- 如需通过上下文参数传入了一个 `Exception` 对象， **必须**以 `'exception'` 作为键名。
记录异常信息是很普遍的，所以如果它能够在记录类库的底层实现，就能够让实现者从异常信息中抽丝剥茧。
当然，实现者在使用它时，**必须**确保键名为 `'exception'` 的键值是否真的是一个 `Exception`，毕竟它**可以**装载任何信息。

### 1.4 助手类和接口

- `Psr\Log\AbstractLogger` 类使得只需继承它和实现其中的 `log` 方法，就能够很轻易地实现 `LoggerInterface` 接口，而另外八个方法就能够把记录信息和上下文信息传给它。

- 同样地，使用  `Psr\Log\LoggerTrait`  也只需实现其中的 `log` 方法。不过，需要特别注意的是，在traits可复用代码块还不能实现接口前，还需要  `implement LoggerInterface`。

- 在没有可用的日志记录器时， `Psr\Log\NullLogger` 接口**可以**为使用者提供一个备用的日志“黑洞”。不过，当上下文的构建非常消耗资源时，带条件检查的日志记录或许是更好的办法。

- `Psr\Log\LoggerAwareInterface` 接口仅包括一个
  `setLogger(LoggerInterface $logger)` 方法，框架可以使用它实现自动连接任意的日志记录实例。

- `Psr\Log\LoggerAwareTrait` trait可复用代码块可以在任何的类里面使用，只需通过它提供的 `$this->logger`，就可以轻松地实现等同的接口。

- `Psr\Log\LogLevel` 类装载了八个记录等级常量。

2. 包
----------

上述的接口、类和相关的异常类，以及一系列的实现检测文件，都包含在 [psr/log](https://packagist.org/packages/psr/log) 文件包中。

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * 日志记录实例
 *
 * 日志信息变量 —— message， **必须**是一个字符串或是实现了  __toString() 方法的对象。
 *
 * 日志信息变量中**可以**包含格式如 “{foo}” (代表foo) 的占位符，
 * 它将会由上下文数组中键名为 "foo" 的键值替代。
 *
 * 上下文数组可以携带任意的数据，唯一的限制是，当它携带的是一个 exception 对象时，它的键名 必须 是 "exception"。
 *
 * 详情可参阅： https://github.com/PizzaLiu/PHP-FIG/blob/master/PSR-3-logger-interface-cn.md
 */
interface LoggerInterface
{
    /**
     * 系统不可用
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * **必须**立刻采取行动
     *
     * 例如：在整个网站都垮掉了、数据库不可用了或者其他的情况下，**应该**发送一条警报短信把你叫醒。
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * 紧急情况
     *
     * 例如：程序组件不可用或者出现非预期的异常。
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * 运行时出现的错误，不需要立刻采取行动，但必须记录下来以备检测。
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * 出现非错误性的异常。
     *
     * 例如：使用了被弃用的API、错误地使用了API或者非预想的不必要错误。
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * 一般性重要的事件。
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * 重要事件
     *
     * 例如：用户登录和SQL记录。
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * debug 详情
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * 任意等级的日志记录
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array());
}
```

4. `Psr\Log\LoggerAwareInterface`
---------------------------------

```php
<?php

namespace Psr\Log;

/**
 * logger-aware 定义实例
 */
interface LoggerAwareInterface
{
    /**
     * 设置一个日志记录实例
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger);
}
```

5. `Psr\Log\LogLevel`
---------------------

```php
<?php

namespace Psr\Log;

/**
 * 日志等级常量定义
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
}
```


----



# PSR-4:Autoloader

关键词 “必须”("MUST")、“一定不可/一定不能”("MUST NOT")、“需要”("REQUIRED")、
“将会”("SHALL")、“不会”("SHALL NOT")、“应该”("SHOULD")、“不该”("SHOULD NOT")、
“推荐”("RECOMMENDED")、“可以”("MAY")和”可选“("OPTIONAL")的详细描述可参见 [RFC 2119][http://tools.ietf.org/html/rfc2119] 。


## 1. 概述

本 PSR 是关于由文件路径 [自动载入][自动载入] 对应类的相关规范，
本规范是可互操作的，可以作为任一自动载入规范的补充，其中包括 [PSR-0][PSR-0]，此外，
本 PSR 还包括自动载入的类对应的文件存放路径规范。


## 2. 详细说明

1. 此处的“类”泛指所有的class类、接口、traits可复用代码块以及其它类似结构。

2. 一个完整的类名需具有以下结构:

        \<命名空间>(\<子命名空间>)*\<类名>

    1. 完整的类名**必须**要有一个顶级命名空间，被称为 "vendor namespace"；

    2. 完整的类名**可以**有一个或多个子命名空间；

    3. 完整的类名**必须**有一个最终的类名；

    4. 完整的类名中任意一部分中的下划线都是没有特殊含义的；

    5. 完整的类名**可以**由任意大小写字母组成；

    6. 所有类名都**必须**是大小写敏感的。

3. 当根据完整的类名载入相应的文件……

    1. 完整的类名中，去掉最前面的命名空间分隔符，前面连续的一个或多个命名空间和子命名空间，作为“命名空间前缀”，其必须与至少一个“文件基目录”相对应；

    2. 紧接命名空间前缀后的子命名空间**必须**与相应的”文件基目录“相匹配，其中的命名空间分隔符将作为目录分隔符。

    3. 末尾的类名**必须**与对应的以 `.php` 为后缀的文件同名。

    4. 自动加载器（autoloader）的实现**一定不能**抛出异常、**一定不能**触发任一级别的错误信息以及**不应该**有返回值。


## 3. 例子

下表展示了符合规范完整类名、命名空间前缀和文件基目录所对应的文件路径。

| 完整类名    | 命名空间前缀   | 文件基目录           | 文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

关于本规范的实现，可参阅 [相关实例][]   
注意：实例并**不**属于规范的一部分，且随时**会**有所变动。

[自动载入]: http://php.net/autoload
[PSR-0]: https://github.com/PizzaLiu/PHP-FIG/blob/master/PSR-0-cn.md
[相关实例]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md


----

# PSR-6: Caching Interface(缓存接口规范)

[PSR-6-cache](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-6-cache.md)

## 介绍

缓存是提升应用性能的常用手段，为框架中最通用的功能，每个框架也都推出专属的、功能多
样的缓存库。这些差别使得开发人员不得不学习多种系统，而很多可能是他们并不需要的功能。
此外，缓存库的开发者同样面临着一个窘境，是只支持有限数量的几个框架还是创建一堆庞
大的适配器类。

一个通用的缓存系统接口可以解决掉这些问题。库和框架的开发人员能够知道缓存系统会按照他们所
预期的方式工作，缓存系统的开发人员只需要实现单一的接口，而不用去开发各种各样的适配器。

## 目标

本 PSR 的目标是：创建一套通用的接口规范，能够让开发人员整合到现有框架和系统，而不需要去
开发框架专属的适配器类。

## 关于「能愿动词」的使用

为了避免歧义，文档大量使用了「能愿动词」，对应的解释如下：

* `必须 (MUST)`：绝对，严格遵循，请照做，无条件遵守；
* `一定不可 (MUST NOT)`：禁令，严令禁止；
* `应该 (SHOULD)` ：强烈建议这样做，但是不强求；
* `不该 (SHOULD NOT)`：强烈不建议这样做，但是不强求；
* `可以 (MAY)` 和 `可选 (OPTIONAL)` ：选择性高一点，在这个文档内，此词语使用较少；

> 参见：[RFC 2119](http://www.ietf.org/rfc/rfc2119.txt)

## 定义

* **调用类库 (Calling Library)** - 调用者，使用缓存服务的类库，这个类库调用缓存服务，调用的
是此缓存接口规范的具体「实现类库」，调用者不需要知道任何「缓存服务」的具体实现。

* **实现类库 (Implementing Library)** - 此类库是对「缓存接口规范」的具体实现，封装起来的缓存服务，供「调用类库」使用。实现类库 **必须** 提供 PHP 类来实现
 `Cache\CacheItemPoolInterface` 和 `Cache\CacheItemInterface` 接口。
实现类库 **必须** 支持最小的如下描述的 TTL 功能，秒级别的精准度。

* **生存时间值 (TTL - Time To Live)** - 定义了缓存可以存活的时间，以秒为单位的整数值。

* **过期时间 (Expiration)** - 定义准确的过期时间点，一般为缓存存储发生的时间点加上 TTL 时
间值，也可以指定一个 DateTime 对象。

    假如一个缓存项的 TTL 设置为 300 秒，保存于 1:30:00 ，那么缓存项的过期时间为 1:35:00。

    实现类库 **可以**	让缓存项提前过期，但是 **必须** 在到达过期时间时立即把缓存项标示为
    过期。如果调用类库在保存一个缓存项的时候未设置「过期时间」、或者设置了 `null` 作为过期
    时间（或者 TTL 设置为 `null`），实现类库 **可以** 使用默认自行配置的一个时间。如果没
    有默认时间，实现类库 **必须**把存储时间当做 `永久性` 存储，或者按照底层驱动能支持的
    最长时间作为保持时间。

* **键 (KEY)** - 长度大于 1 的字串，用作缓存项在缓存系统里的唯一标识符。实现类库 
**必须** 支持「键」规则 `A-Z`, `a-z`, `0-9`, `_`, 和 `.` 任何顺序的 UTF-8 编码，长度
小于 64 位。实现类库 **可以** 支持更多的编码或者更长的长度，不过 **必须** 支持至少以上指定
的编码和长度。实现类库可自行实现对「键」的转义，但是 **必须** 保证能够无损的返回「键」字串。以下
的字串作为系统保留: `{}()/\@:`，**一定不可** 作为「键」的命名支持。

* **命中 (Hit)** - 一个缓存的命中，指的是当调用类库使用「键」在请求一个缓存项的时候，在缓存
池里能找到对应的缓存项，并且此缓存项还未过期，并且此数据不会因为任何原因出现错误。调用类
库 **应该** 确保先验证下 `isHit()` 有命中后才调用 `get()` 获取数据。

* **未命中 (Miss)** - 一个缓存未命中，是完全的上面描述的「命中」的相反。指的是当调用类库使用「键」在请求一个缓存项的时候，在缓存池里未能找到对应的缓存项，或者此缓存项已经过期，或者此数据因为任何原因出现错误。一个过期的缓存项，**必须** 被当做 `未命中` 来对待。

* **延迟 (Deferred)** - 一个延迟的缓存，指的是这个缓存项可能不会立刻被存储到物理缓存池里。一个
缓存池对象 **可以** 对一个指定延迟的缓存项进行延迟存储，这样做的好处是可以利用一些缓存服务器提供
的批量插入功能。缓存池 **必须** 能对所有延迟缓存最终能持久化，并且不会丢失。**可以** 在调用类库还未发起保存请求之前就做持久化。当调用类库调用 `commit()` 方法时，所有的延迟缓存都 **必须** 
做持久化。实现类库 **可以** 自行决定使用什么逻辑来触发数据持久化，如对象的 `析构方法 (destructor)` 内、调用 `save()` 时持久化、倒计时保存或者触及最大数量时保存等。当请求一个延迟
缓存项时，**必须** 返回一个延迟，未持久化的缓存项对象。

## 数据

实现类库 **必须** 支持所有的可序列化的 PHP 数据类型，包含：

* **字符串** - 任何大小的 PHP 兼容字符串
* **整数** - PHP 支持的低于 64 位的有符号整数值
* **浮点数** - 所有的有符号浮点数
* **布尔** - true 和 false.
* **Null** - `null` 值
* **数组** - 各种形式的 PHP 数组
* **对象（Object）** - 所有的支持无损序列化和反序列化的对象，如：` $o == unserialize(serialize($o))` 。对象 **可以** 
使用 PHP 的 `Serializable` 接口，`__sleep()` 或者 `__wakeup()` 魔术方法，或者在合适的情况下，使用其他类似的语言特性。

所有存进实现类库的数据，都 `必须` 能做到原封不动的取出。连类型也 `必须` 是完全一致，如果
存进缓存的是字符串 5，取出来的却是整数值 5 的话，可以算作严重的错误。实现类库 **可以** 使用 PHP 的「serialize()/unserialize() 方法」作为底层实现，不过不强迫这样做。对于他们的兼容性，以能支持所有数据类型作为基准线。

实在无法「完整取出」存入的数据的话，实现类库 **必须** 把「缓存丢失」标示作为返回，而不是损坏了的数据。

## 主要概念

### 缓存池 Pool

缓存池包含缓存系统里所有缓存数据的集合。缓存池逻辑上是所有缓存项存储的仓库，所有存储进去的数据，
都能从缓存池里取出来，所有的对缓存的操作，都发生在缓存池子里。

### 缓存项 Items

一条缓存项在缓存池里代表了一对「键/值」对应的数据，「键」被视为每一个缓存项主键，是缓存项的
唯一标识符，**必须** 是不可变更的，当然，「值」**可以** 任意变更。

## 错误处理

缓存对应用性能起着至关重要的作用，但是，无论在任何情况下，缓存 **一定不可** 作为应用程序不
可或缺的核心功能。

缓存系统里的错误 **一定不可** 导致应用程序故障，所以，实现类库 **一定不可** 抛出任何除了
此接口规范定义的以外的异常，并且 **必须** 捕捉包括底层存储驱动抛出的异常，不让其冒泡至超
出缓存系统内。

实现类库 **应该** 对此类错误进行记录，或者以任何形式通知管理员。

调用类库发起删除缓存项的请求，或者清空整个缓冲池子的请求，「键」不存在的话 **必须** 不能
当成是有错误发生。后置条件是一样的，如果取数据时，「键」不存在的话 **必须** 不能当成是有错误发生

## 接口

### CacheItemInterface

`CacheItemInterface` 定义了缓存系统里的一个缓存项。每一个缓存项 **必须** 有一个「键」与之相
关联，此「键」通常是通过 Cache\CacheItemPoolInterface 来设置。

Cache\CacheItemInterface 对象把缓存项的存储进行了封装，每一个 Cache\CacheItemInterface 由一个 Cache\CacheItemPoolInterface 对象生成，CacheItemPoolInterface 负责一些必须的设置，并且给对象设置具有 `唯一性` 的「键」。

Cache\CacheItemInterface 对象 **必须** 能够存储和取出任何类型的，在「数据」章节定义的 PHP 数值。

调用类库 **一定不可** 擅自初始化「CacheItemInterface」对象，「缓存项」只能使用「CacheItemPoolInterface」对象的 `getItem()` 方法来获取。调用类库 **一定不可** 假设
由一个实现类库创建的「缓存项」能被另一个实现类库完全兼容。

```php
namespace Psr\Cache;

/**
 * CacheItemInterface 定了缓存系统里对缓存项操作的接口
 */
interface CacheItemInterface
{
    /**
     * 返回当前缓存项的「键」
     * 
     * 「键」由实现类库来加载，并且高层的调用者（如：CacheItemPoolInterface）
     *  **应该** 能使用此方法来获取到「键」的信息。
     *
     * @return string
     *   当前缓存项的「键」
     */
    public function getKey();

    /**
     * 凭借此缓存项的「键」从缓存系统里面取出缓存项。
     *
     * 取出的数据 **必须** 跟使用 `set()` 存进去的数据是一模一样的。
     *
     * 如果 `isHit()` 返回 false 的话，此方法必须返回 `null`，需要注意的是 `null` 
     * 本来就是一个合法的缓存数据，所以你 **应该** 使用 `isHit()` 方法来辨别到底是
     * "返回 null 数据" 还是 "缓存里没有此数据"。
     *
     * @return mixed
     *   此缓存项的「键」对应的「值」，如果找不到的话，返回 `null`
     */
    public function get();

    /**
     * 确认缓存项的检查是否命中。
     * 
     * 注意: 调用此方法和调用 `get()` 时 **一定不可** 有先后顺序之分。
     *
     * @return bool
     *   如果缓冲池里有命中的话，返回 `true`，反之返回 `false`
     */
    public function isHit();

    /**
     * 为此缓存项设置「值」。
     *
     * 参数 $value 可以是所有能被 PHP 序列化的数据，序列化的逻辑
     * 需要在实现类库里书写。
     *
     * @param mixed $value
     *   将被存储的可序列化的数据。
     *
     * @return static
     *   返回当前对象。
     */
    public function set($value);

    /**
     * 设置缓存项的准确过期时间点。
     *
     * @param \DateTimeInterface $expiration
     * 
     *   过期的准确时间点，过了这个时间点后，缓存项就 **必须** 被认为是过期了的。
     *   如果明确的传参 `null` 的话，**可以** 使用一个默认的时间。
     *   如果没有设置的话，缓存 **应该** 存储到底层实现的最大允许时间。
     *
     * @return static
     *   返回当前对象。
     */
    public function expiresAt($expiration);

    /**
     * 设置缓存项的过期时间。
     *
     * @param int|\DateInterval $time
     *   以秒为单位的过期时长，过了这段时间后，缓存项就 **必须** 被认为是过期了的。
     *   如果明确的传参 `null` 的话，**可以** 使用一个默认的时间。
     *   如果没有设置的话，缓存 **应该** 存储到底层实现的最大允许时间。
     *
     * @return static
     *   返回当前对象
     */
    public function expiresAfter($time);

}
```

### CacheItemPoolInterface

Cache\CacheItemPoolInterface 的主要目的是从调用类库接收「键」，然后返回对应的 Cache\CacheItemInterface 对象。

此接口也是作为主要的，与整个缓存集合交互的方式。所有的配置和初始化由实现类库自行实现。

```php
namespace Psr\Cache;

/**
 * CacheItemPoolInterface 生成 CacheItemInterface 对象
 */
interface CacheItemPoolInterface
{
    /**
     * 返回「键」对应的一个缓存项。
     *
     * 此方法 **必须** 返回一个 CacheItemInterface 对象，即使是找不到对应的缓存项
     * 也 **一定不可** 返回 `null`。
     *
     * @param string $key
     *   用来搜索缓存项的「键」。
     *
     * @throws InvalidArgumentException
     *   如果 $key 不是合法的值，\Psr\Cache\InvalidArgumentException 异常会被抛出。
     *
     * @return CacheItemInterface
     *   对应的缓存项。
     */
    public function getItem($key);

    /**
     * 返回一个可供遍历的缓存项集合。
     *
     * @param array $keys
     *   由一个或者多个「键」组成的数组。
     *
     * @throws InvalidArgumentException
     *   如果 $keys 里面有哪个「键」不是合法，\Psr\Cache\InvalidArgumentException 异常
     *   会被抛出。
     *   
     * @return array|\Traversable
     *   返回一个可供遍历的缓存项集合，集合里每个元素的标识符由「键」组成，即使即使是找不到对
     *   的缓存项，也要返回一个「CacheItemInterface」对象到对应的「键」中。
     *   如果传参的数组为空，也需要返回一个空的可遍历的集合。
     */
    public function getItems(array $keys = array());

    /**
     * 检查缓存系统中是否有「键」对应的缓存项。
     *
     * 注意: 此方法应该调用 `CacheItemInterface::isHit()` 来做检查操作，而不是
     * `CacheItemInterface::get()`
     *
     * @param string $key
     *   用来搜索缓存项的「键」。
     *
     * @throws InvalidArgumentException
     *   如果 $key 不是合法的值，\Psr\Cache\InvalidArgumentException 异常会被抛出。
     *
     * @return bool
     *   如果存在「键」对应的缓存项即返回 true，否则 false
     */
    public function hasItem($key);

    /**
     * 清空缓冲池
     *
     * @return bool
     *   成功返回 true，有错误发生返回 false
     */
    public function clear();

    /**
     * 从缓冲池里移除某个缓存项
     *
     * @param string $key
     *   用来搜索缓存项的「键」。
     *
     * @throws InvalidArgumentException
     *   如果 $key 不是合法的值，\Psr\Cache\InvalidArgumentException 异常会被抛出。
     *
     * @return bool
     *   成功返回 true，有错误发生返回 false
     */
    public function deleteItem($key);

    /**
     * 从缓冲池里移除多个缓存项
     *
     * @param array $keys
     *   由一个或者多个「键」组成的数组。
     *   
     * @throws InvalidArgumentException
     *   如果 $keys 里面有哪个「键」不是合法，\Psr\Cache\InvalidArgumentException 异常
     *   会被抛出。
     *
     * @return bool
     *   成功返回 true，有错误发生返回 false
     */
    public function deleteItems(array $keys);

    /**
     * 立刻为「CacheItemInterface」对象做数据持久化。
     *
     * @param CacheItemInterface $item
     *   将要被存储的缓存项
     *
     * @return bool
     *   成功返回 true，有错误发生返回 false
     */
    public function save(CacheItemInterface $item);

    /**
     * 稍后为「CacheItemInterface」对象做数据持久化。
     *
     * @param CacheItemInterface $item
     *   将要被存储的缓存项
     *
     * @return bool
     *   成功返回 true，有错误发生返回 false
     */
    public function saveDeferred(CacheItemInterface $item);

    /**
     * 提交所有的正在队列里等待的请求到数据持久层，配合 `saveDeferred()` 使用
     *
     * @return bool
     *  成功返回 true，有错误发生返回 false
     */
    public function commit();
}
```

### CacheException

此异常用于缓存系统发生的所有严重错误，包括但不限制于 *缓存系统配置*，如连接到缓存服务器出错、错
误的用户身份认证等。

所有的实现类库抛出的异常都 **必须** 实现此接口。

```php
namespace Psr\Cache;

/**
 * 被所有的实现类库抛出的异常继承的「异常接口」
 */
interface CacheException
{
}
```

### InvalidArgumentException

```php
namespace Psr\Cache;

/**
 * 传参错误抛出的异常接口
 *
 * 当一个错误或者非法的传参发生时，**必须** 抛出一个继承了
 * Psr\Cache\InvalidArgumentException 的异常
 */
interface InvalidArgumentException extends CacheException
{
}
```



# PSR-7: HTTP message interfaces(HTTP 消息接口规范)

[PSR-7-http-message](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)

# HTTP消息接口

此文档描述了 [RFC 7230](http://tools.ietf.org/html/rfc7230) 和
[RFC 7231](http://tools.ietf.org/html/rfc7231) HTTP 消息传递的接口，还有 [RFC 3986](http://tools.ietf.org/html/rfc3986) 里对 HTTP 消息的 URIs 使用。

HTTP 消息是 Web 技术发展的基础。浏览器或 HTTP 客户端如 `curl` 生成发送 HTTP 请求消息到 Web 服务器，Web 服务器响应 HTTP 请求。服务端的代码接受 HTTP 请求消息后返回 HTTP 响应消息。

通常 HTTP 消息对于终端用户来说是不可见的，但是作为 Web 开发者，我们需要知道 HTTP 机制，如何发起、构建、取用还有操纵 HTTP 消息，知道这些原理，以助我们刚好的完成开发任务，无论这个任务是发起一个 HTTP 请求，或者处理传入的请求。 

每一个 HTTP 请求都有专属的格式：

```http
POST /path HTTP/1.1
Host: example.com

foo=bar&baz=bat
```

按照顺序，第一行的各个字段意义为： HTTP 请求方法、请求的目标地址（通常是一个绝对路径的 URI 或
者路径），HTTP 协议。

接下来是 HTTP 头信息，在这个例子中：目的主机。接下来是空行，然后是消息内容。

HTTP 返回消息有类似的结构：

```http
HTTP/1.1 200 OK
Content-Type: text/plain

这是返回的消息内容
```

按照顺序，第一行为状态行，包括 HTTP 协议版本，HTTP 状态码，描述文本。

和 HTTP 请求类似的，接下来是 HTTP 头信息，在这个例子中：内容类型。接下来是空行，然后是消息内容。

此文档探讨的是 HTTP 请求消息接口，和构建 HTTP 消息需要的元素数据定义。

### 参考资料

- [RFC 2119](http://tools.ietf.org/html/rfc2119)
- [RFC 3986](http://tools.ietf.org/html/rfc3986)
- [RFC 7230](http://tools.ietf.org/html/rfc7230)
- [RFC 7231](http://tools.ietf.org/html/rfc7231)

## 关于「能愿动词」的使用

为了避免歧义，文档大量使用了「能愿动词」，对应的解释如下：

* `必须 (MUST)`：绝对，严格遵循，请照做，无条件遵守；
* `一定不可 (MUST NOT)`：禁令，严令禁止；
* `应该 (SHOULD)` ：强烈建议这样做，但是不强求；
* `不该 (SHOULD NOT)`：强烈不建议这样做，但是不强求；
* `可以 (MAY)` 和 `可选 (OPTIONAL)` ：选择性高一点，在这个文档内，此词语使用较少；

> 参见：[RFC 2119](http://www.ietf.org/rfc/rfc2119.txt)

## 1. 规范详情

### 1.1 消息

一个 HTTP 消息，指定是一个从客户端发往服务器端的请求，或者是服务器端返回客户端的响应。对应的两
个消息接口：`Psr\Http\Message\RequestInterface` 和 `Psr\Http\Message\ResponseInterface`。

这个两个接口都继承于 `Psr\Http\Message\MessageInterface`。虽然你 **可以** 实现
 `Psr\Http\Message\MessageInterface` 接口，但是，最重要的，你 **必须** 实现
 `Psr\Http\Message\RequestInterface` 和 `Psr\Http\Message\ResponseInterface` 
 接口。

从现在开始，为了行文的方便，我们提到这些接口的时候，都会把命名空间 `Psr\Http\Message` 去除掉。

### 1.2 HTTP 头信息

#### 大小写不敏感的字段名字

HTTP 消息包含大小写不敏感头信息。使用 `MessageInterface` 接口来设置和获取头信息，大小写
不敏感的定义在于，如果你设置了一个 `Foo` 的头信息，`foo` 的值会被重写，你也可以通过 `foo` 来
拿到 `FoO` 头对应的值。

```php
$message = $message->withHeader('foo', 'bar');

echo $message->getHeaderLine('foo');
// 输出: bar

echo $message->getHeaderLine('FOO');
// 输出: bar

$message = $message->withHeader('fOO', 'baz');
echo $message->getHeaderLine('foo');
// 输出: baz
```

虽然头信息可以用大小写不敏感的方式取出，但是接口实现类 **必须** 保持自己的大小写规范，特别是用 `getHeaders()` 方法输出的内容。

因为一些非标准的 HTTP 应用程序，可能会依赖于大小写敏感的头信息，所有在此我们把主宰 HTTP 大小写的权利开放出来，以适用不同的场景。

#### 对应多条数组的头信息

为了适用一个 HTTP 「键」可以对应多条数据的情况，我们使用字符串配合数组来实现，你可以从一个 `MessageInterface` 取出数组或字符串，使用 `getHeaderLine($name)` 方法可以获取通过逗号分割的不区分大小写的字符串形式的所有值。也可以通过 `getHeader($name)` 获取数组形式头信息的所有值。


```php
$message = $message
    ->withHeader('foo', 'bar')
    ->withAddedHeader('foo', 'baz');

$header = $message->getHeaderLine('foo');
// $header 包含: 'bar, baz'

$header = $message->getHeader('foo');
// ['bar', 'baz']
```

注意：并不是所有的头信息都可以适用逗号分割（例如 `Set-Cookie`），当处理这种头信息时候， `MessageInterace` 的继承类 **应该** 使用 `getHeader($name)` 方法来获取这种多值的情况。

#### 主机信息

在请求中，`Host` 头信息通常和 URI 的 host 信息，还有建立起 TCP 连接使用的 Host 信息一致。
然而，HTTP 标准规范允许主机 `host` 信息与其他两个不一样。

在构建请求的时候，如果 `host` 头信息未提供的话，实现类库 **必须** 尝试着从 URI 中提取 `host` 
信息。

`RequestInterface::withUri()` 会默认的，从传参的 `UriInterface` 实例中提取 `host` ，
并替代请求中原有的 `host` 信息。

你可以提供传参第二个参数为 `true` 来保证返回的消息实例中，原有的 `host` 头信息不会被替代掉。

以下表格说明了当 `withUri()` 的第二个参数被设置为 `true` 的时，返回的消息实例中调用
 `getHeaderLine('Host')` 方法会返回的内容：

请求 Host 头信息<sup>[1](#rhh)</sup> | 请求 URI 中的 Host 信息<sup>[2](#rhc)</sup> | 传参进去 URI 的 Host  <sup>[3](#uhc)</sup> | 结果
----------------------------------------|--------------------------------------------|----------------------------------------|--------
''                                      | ''                                         | ''                                     | ''
''                                      | foo.com                                    | ''                                     | foo.com
''                                      | foo.com                                    | bar.com                                | foo.com
foo.com                                 | ''                                         | bar.com                                | foo.com
foo.com                                 | bar.com                                    | baz.com                                | foo.com

- <sup id="rhh">1</sup> 当前请求的 `Host` 头信息。
- <sup id="rhc">2</sup> 当前请求 `URI` 中的 `Host` 信息。
- <sup id="uhc">3</sup> 通过 `withUri()` 传参进入的 URI 中的 `host` 信息。


### 1.3 数据流

HTTP 消息包含开始的一行、头信息、还有消息的内容。HTTP 的消息内容有时候可以很小，有时候确是
非常巨大。尝试使用字符串的形式来展示消息内容，会消耗大量的内存，使用数据流的形式来读取消息
可以解决此问题。`StreamInterface` 接口用来隐藏具体的数据流读写实现。在一些情况下，消息
类型的读取方式为字符串是能容许的，可以使用 `php://memory` 或者 `php://temp`。

`StreamInterface` 暴露出来几个接口，这些接口允许你读取、写入，还有高效的遍历内容。

数据流使用这个三个接口来阐明对他们的操作能力：`isReadable()`、`isWritable()` 和
 `isSeekable()`。这些方法可以让数据流的操作者得知数据流能否能提供他们想要的功能。
 
每一个数据流的实例，都会有多种功能：可以只读、可以只写、可以读和写，可以随机读取，可以按顺序读取等。

最终，`StreamInterface` 定义了一个 `__toString()` 的方法，用来一次性以字符串的形式输出
所有消息内容。

与请求和响应的接口不同的是，`StreamInterface` 并不强调不可修改性。因为在 PHP 的实现内，基
本上没有办法保证不可修改性，因为指针的指向，内容的变更等状态，都是不可控的。作为读取者，可以
调用只读的方法来返回数据流，以最大程度上保证数据流的不可修改性。使用者要时刻明确的知道数据
流的可修改性，建议把数据流附加到消息实例中，来强迫不可修改的特性。

### 1.4 请求目标和 URI 

> 翻译到此先告一段落，此规范篇幅有点过长，需要消耗的时间挺长的，暂且翻译至此，他日再战。


Per RFC 7230, request messages contain a "request-target" as the second segment
of the request line. The request target can be one of the following forms:

- **origin-form**, which consists of the path, and, if present, the query
  string; this is often referred to as a relative URL. Messages as transmitted
  over TCP typically are of origin-form; scheme and authority data are usually
  only present via CGI variables.
- **absolute-form**, which consists of the scheme, authority
  ("[user-info@]host[:port]", where items in brackets are optional), path (if
  present), query string (if present), and fragment (if present). This is often
  referred to as an absolute URI, and is the only form to specify a URI as
  detailed in RFC 3986. This form is commonly used when making requests to
  HTTP proxies.
- **authority-form**, which consists of the authority only. This is typically
  used in CONNECT requests only, to establish a connection between an HTTP
  client and a proxy server.
- **asterisk-form**, which consists solely of the string `*`, and which is used
  with the OPTIONS method to determine the general capabilities of a web server.

Aside from these request-targets, there is often an 'effective URL' which is
separate from the request target. The effective URL is not transmitted within
an HTTP message, but it is used to determine the protocol (http/https), port
and hostname for making the request.

The effective URL is represented by `UriInterface`. `UriInterface` models HTTP
and HTTPS URIs as specified in RFC 3986 (the primary use case). The interface
provides methods for interacting with the various URI parts, which will obviate
the need for repeated parsing of the URI. It also specifies a `__toString()`
method for casting the modeled URI to its string representation.

When retrieving the request-target with `getRequestTarget()`, by default this
method will use the URI object and extract all the necessary components to
construct the _origin-form_. The _origin-form_ is by far the most common
request-target.

If it's desired by an end-user to use one of the other three forms, or if the
user wants to explicitly override the request-target, it is possible to do so
with `withRequestTarget()`.

Calling this method does not affect the URI, as it is returned from `getUri()`.

For example, a user may want to make an asterisk-form request to a server:

```php
$request = $request
    ->withMethod('OPTIONS')
    ->withRequestTarget('*')
    ->withUri(new Uri('https://example.org/'));
```

This example may ultimately result in an HTTP request that looks like this:

```http
OPTIONS * HTTP/1.1
```

But the HTTP client will be able to use the effective URL (from `getUri()`),
to determine the protocol, hostname and TCP port.

An HTTP client MUST ignore the values of `Uri::getPath()` and `Uri::getQuery()`,
and instead use the value returned by `getRequestTarget()`, which defaults
to concatenating these two values.

Clients that choose to not implement 1 or more of the 4 request-target forms,
MUST still use `getRequestTarget()`. These clients MUST reject request-targets
they do not support, and MUST NOT fall back on the values from `getUri()`.

`RequestInterface` provides methods for retrieving the request-target or
creating a new instance with the provided request-target. By default, if no
request-target is specifically composed in the instance, `getRequestTarget()`
will return the origin-form of the composed URI (or "/" if no URI is composed).
`withRequestTarget($requestTarget)` creates a new instance with the
specified request target, and thus allows developers to create request messages
that represent the other three request-target forms (absolute-form,
authority-form, and asterisk-form). When used, the composed URI instance can
still be of use, particularly in clients, where it may be used to create the
connection to the server.

### 1.5 Server-side Requests

`RequestInterface` provides the general representation of an HTTP request
message. However, server-side requests need additional treatment, due to the
nature of the server-side environment. Server-side processing needs to take into
account Common Gateway Interface (CGI), and, more specifically, PHP's
abstraction and extension of CGI via its Server APIs (SAPI). PHP has provided
simplification around input marshaling via superglobals such as:

- `$_COOKIE`, which deserializes and provides simplified access for HTTP
  cookies.
- `$_GET`, which deserializes and provides simplified access for query string
  arguments.
- `$_POST`, which deserializes and provides simplified access for urlencoded
  parameters submitted via HTTP POST; generically, it can be considered the
  results of parsing the message body.
- `$_FILES`, which provides serialized metadata around file uploads.
- `$_SERVER`, which provides access to CGI/SAPI environment variables, which
  commonly include the request method, the request scheme, the request URI, and
  headers.

`ServerRequestInterface` extends `RequestInterface` to provide an abstraction
around these various superglobals. This practice helps reduce coupling to the
superglobals by consumers, and encourages and promotes the ability to test
request consumers.

The server request provides one additional property, "attributes", to allow
consumers the ability to introspect, decompose, and match the request against
application-specific rules (such as path matching, scheme matching, host
matching, etc.). As such, the server request can also provide messaging between
multiple request consumers.

### 1.6 Uploaded files

`ServerRequestInterface` specifies a method for retrieving a tree of upload
files in a normalized structure, with each leaf an instance of
`UploadedFileInterface`.

The `$_FILES` superglobal has some well-known problems when dealing with arrays
of file inputs. As an example, if you have a form that submits an array of files
— e.g., the input name "files", submitting `files[0]` and `files[1]` — PHP will
represent this as:

```php
array(
    'files' => array(
        'name' => array(
            0 => 'file0.txt',
            1 => 'file1.html',
        ),
        'type' => array(
            0 => 'text/plain',
            1 => 'text/html',
        ),
        /* etc. */
    ),
)
```

instead of the expected:

```php
array(
    'files' => array(
        0 => array(
            'name' => 'file0.txt',
            'type' => 'text/plain',
            /* etc. */
        ),
        1 => array(
            'name' => 'file1.html',
            'type' => 'text/html',
            /* etc. */
        ),
    ),
)
```

The result is that consumers need to know this language implementation detail,
and write code for gathering the data for a given upload.

Additionally, scenarios exist where `$_FILES` is not populated when file uploads
occur:

- When the HTTP method is not `POST`.
- When unit testing.
- When operating under a non-SAPI environment, such as [ReactPHP](http://reactphp.org).

In such cases, the data will need to be seeded differently. As examples:

- A process might parse the message body to discover the file uploads. In such
  cases, the implementation may choose *not* to write the file uploads to the
  file system, but instead wrap them in a stream in order to reduce memory,
  I/O, and storage overhead.
- In unit testing scenarios, developers need to be able to stub and/or mock the
  file upload metadata in order to validate and verify different scenarios.

`getUploadedFiles()` provides the normalized structure for consumers.
Implementations are expected to:

- Aggregate all information for a given file upload, and use it to populate a
  `Psr\Http\Message\UploadedFileInterface` instance.
- Re-create the submitted tree structure, with each leaf being the appropriate
  `Psr\Http\Message\UploadedFileInterface` instance for the given location in
  the tree.

The tree structure referenced should mimic the naming structure in which files
were submitted.

In the simplest example, this might be a single named form element submitted as:

```html
<input type="file" name="avatar" />
```

In this case, the structure in `$_FILES` would look like:

```php
array(
    'avatar' => array(
        'tmp_name' => 'phpUxcOty',
        'name' => 'my-avatar.png',
        'size' => 90996,
        'type' => 'image/png',
        'error' => 0,
    ),
)
```

The normalized form returned by `getUploadedFiles()` would be:

```php
array(
    'avatar' => /* UploadedFileInterface instance */
)
```

In the case of an input using array notation for the name:

```html
<input type="file" name="my-form[details][avatar]" />
```

`$_FILES` ends up looking like this:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatar' => array(
                'tmp_name' => 'phpUxcOty',
                'name' => 'my-avatar.png',
                'size' => 90996,
                'type' => 'image/png',
                'error' => 0,
            ),
        ),
    ),
)
```

And the corresponding tree returned by `getUploadedFiles()` should be:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatar' => /* UploadedFileInterface instance */
        ),
    ),
)
```

In some cases, you may specify an array of files:

```html
Upload an avatar: <input type="file" name="my-form[details][avatars][]" />
Upload an avatar: <input type="file" name="my-form[details][avatars][]" />
```

(As an example, JavaScript controls might spawn additional file upload inputs to
allow uploading multiple files at once.)

In such a case, the specification implementation must aggregate all information
related to the file at the given index. The reason is because `$_FILES` deviates
from its normal structure in such cases:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatars' => array(
                'tmp_name' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'name' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'size' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'type' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'error' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
            ),
        ),
    ),
)
```

The above `$_FILES` array would correspond to the following structure as
returned by `getUploadedFiles()`:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatars' => array(
                0 => /* UploadedFileInterface instance */,
                1 => /* UploadedFileInterface instance */,
                2 => /* UploadedFileInterface instance */,
            ),
        ),
    ),
)
```

Consumers would access index `1` of the nested array using:

```php
$request->getUploadedFiles()['my-form']['details']['avatars'][1];
```

Because the uploaded files data is derivative (derived from `$_FILES` or the
request body), a mutator method, `withUploadedFiles()`, is also present in the
interface, allowing delegation of the normalization to another process.

In the case of the original examples, consumption resembles the following:

```php
$file0 = $request->getUploadedFiles()['files'][0];
$file1 = $request->getUploadedFiles()['files'][1];

printf(
    "Received the files %s and %s",
    $file0->getClientFilename(),
    $file1->getClientFilename()
);

// "Received the files file0.txt and file1.html"
```

This proposal also recognizes that implementations may operate in non-SAPI
environments. As such, `UploadedFileInterface` provides methods for ensuring
operations will work regardless of environment. In particular:

- `moveTo($targetPath)` is provided as a safe and recommended alternative to calling
  `move_uploaded_file()` directly on the temporary upload file. Implementations
  will detect the correct operation to use based on environment.
- `getStream()` will return a `StreamInterface` instance. In non-SAPI
  environments, one proposed possibility is to parse individual upload files
  into `php://temp` streams instead of directly to files; in such cases, no
  upload file is present. `getStream()` is therefore guaranteed to work
  regardless of environment.

As examples:

```
// Move a file to an upload directory
$filename = sprintf(
    '%s.%s',
    create_uuid(),
    pathinfo($file0->getClientFilename(), PATHINFO_EXTENSION)
);
$file0->moveTo(DATA_DIR . '/' . $filename);

// Stream a file to Amazon S3.
// Assume $s3wrapper is a PHP stream that will write to S3, and that
// Psr7StreamWrapper is a class that will decorate a StreamInterface as a PHP
// StreamWrapper.
$stream = new Psr7StreamWrapper($file1->getStream());
stream_copy_to_stream($stream, $s3wrapper);
```

## 2. Package

上面讨论的接口和类库已经整合成为扩展包：
[psr/http-message](https://packagist.org/packages/psr/http-message)。

## 3. Interfaces

### 3.1 `Psr\Http\Message\MessageInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * 
 * HTTP 消息值得是客户端发起的「请求」和服务器端返回的「响应」，此接口
 * 定义了他们通用的方法。
 * 
 * HTTP 消息是被视为无法修改的，所有能修改状态的方法，都 **必须** 有一套
 * 机制，在内部保持好原有的内容，然后把修改状态后的信息返回。
 *
 * @see http://www.ietf.org/rfc/rfc7230.txt
 * @see http://www.ietf.org/rfc/rfc7231.txt
 */
interface MessageInterface
{
    /**
     * 获取字符串形式的 HTTP 协议版本信息
     *
     * 字符串必须包含 HTTP 版本数字，如："1.1", "1.0"。
     *
     * @return string HTTP 协议版本
     */
    public function getProtocolVersion();

    /**
     * 返回指定 HTTP 版本号的消息实例。
     *
     * 传参的版本号必须 **只** 包含 HTTP 版本数字，如："1.1", "1.0"。
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息对象，然后返回
     * 一个新的带有传参进去的 HTTP 版本的实例
     *
     * @param string $version HTTP 版本信息
     * @return self
     */
    public function withProtocolVersion($version);

    /**
     * 获取所有的头信息
     *
     * 返回的二维数组中，第一维数组的「键」代表单条头信息的名字，「值」是
     * 以数据形式返回的，见以下实例：
     *
     *     // 把「值」的数据当成字串打印出来
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ': ' . implode(', ', $values);
     *     }
     *
     *     // 迭代的循环二维数组
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * 虽然头信息是没有大小写之分，但是使用 `getHeaders()` 会返回保留了原本
     * 大小写形式的内容。
     *
     * @return string[][] 返回一个两维数组，第一维数组的「键」 **必须** 为单条头信息的
     *     名称，对应的是由字串组成的数组，请注意，对应的「值」 **必须** 是数组形式的。
     */
    public function getHeaders();

    /**
     * 检查是否头信息中包含有此名称的值，不区分大小写
     *
     * @param string $name 不区分大小写的头信息名称
     * @return bool 找到返回 true，未找到返回 false
     */
    public function hasHeader($name);

    /**
     * 根据给定的名称，获取一条头信息，不区分大小写，以数组形式返回
     *
     * 此方法以数组形式返回对应名称的头信息。
     *
     * 如果没有对应的头信息，**必须** 返回一个空数组。
     *
     * @param string $name 不区分大小写的头部字段名称。
     * @return string[] 返回头信息中，对应名称的，由字符串组成的数组值，如果没有对应
     * 	的内容，**必须** 返回空数组。
     */
    public function getHeader($name);

    /**
     * 根据给定的名称，获取一条头信息，不区分大小写，以逗号分隔的形式返回
     * 
     * 此方法返回所有对应的头信息，并将其使用逗号分隔的方法拼接起来。
     *
     * 注意：不是所有的头信息都可使用逗号分隔的方法来拼接，对于那些头信息，请使用
     * `getHeader()` 方法来获取。
     * 
     * 如果没有对应的头信息，此方法 **必须** 返回一个空字符串。
     *
     * @param string $name 不区分大小写的头部字段名称。
     * @return string 返回头信息中，对应名称的，由逗号分隔组成的字串，如果没有对应
     * 	的内容，**必须** 返回空字符串。
     */
    public function getHeaderLine($name);

    /**
     * 返回指定头信息「键/值」对的消息实例。
     *
     * 虽然头信息是不区分大小写的，但是此方法必须保留其传参时的大小写状态，并能够在
     * 调用 `getHeaders()` 的时候被取出。
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息对象，然后返回
     * 一个新的带有传参进去头信息的实例
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value);

    /**
     * 返回一个头信息增量的 HTTP 消息实例。
     *
     * 原有的头信息会被保留，新的值会作为增量加上，如果头信息不存在的话，会被加上。
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息对象，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @param string $name 不区分大小写的头部字段名称。
     * @param string|string[] $value 头信息对应的值。
     * @return self
     * @throws \InvalidArgumentException 头信息字段名称非法时会被抛出。
     * @throws \InvalidArgumentException 头信息的值非法的时候，会被抛出。
     */
    public function withAddedHeader($name, $value);

    /**
     * 返回被移除掉指定头信息的 HTTP 消息实例。
     *
     * 头信息字段在解析的时候，**必须** 保证是不区分大小写的。
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息对象，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @param string $name 不区分大小写的头部字段名称。
     * @return self
     */
    public function withoutHeader($name);

    /**
     * 获取 HTTP 消息的内容。
     *
     * @return StreamInterface 以数据流的形式返回。
     */
    public function getBody();

    /**
     * 返回拼接了内容的 HTTP 消息实例。
     *
     * 内容 **必须** 是 StreamInterface 接口的实例。
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息对象，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @param StreamInterface $body 数据流形式的内容。
     * @return self
     * @throws \InvalidArgumentException 当消息内容不正确的时候。
     */
    public function withBody(StreamInterface $body);
}
```

### 3.2 `Psr\Http\Message\RequestInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * 代表客户端请求的 HTTP 消息对象。
 *
 * 根据规范，每一个 HTTP 请求都包含以下信息：
 *
 * - HTTP 协议版本号 (Protocol version)
 * - HTTP 请求方法 (HTTP method)
 * - URI
 * - 头信息 (Headers)
 * - 消息内容 (Message body)
 *
 * 在构造 HTTP 请求对象的时候，实现类库 **必须** 从给出的 URI 中去提取 HOST 信息。
 *
 * HTTP 请求是被视为无法修改的，所有能修改状态的方法，都 **必须** 有一套机制，在内部保
 * 持好原有的内容，然后把修改状态后的，新的 HTTP 请求实例返回。
 */
interface RequestInterface extends MessageInterface
{
    /**
     * 获取消息请求的目标。
     *
     * 在大部分情况下，此方法会返回完整的 URI，除非 `withRequestTarget()` 被设置过。
     *
     * 如果没有提供 URI，并且没有提供任何的请求目标，此方法 **必须** 返回 "/"。
     *
     * @return string
     */
    public function getRequestTarget();

    /**
     * 返回一个指定目标的请求实例。
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 请求实例，然后返回
     * 一个新的修改过的 HTTP 请求实例。
     *
     * @see 关于请求目标的各种允许的格式，请见 http://tools.ietf.org/html/rfc7230#section-2.7 
     * 
     * @param mixed $requestTarget
     * @return self
     */
    public function withRequestTarget($requestTarget);

    /**
     * 获取当前请求使用的 HTTP 方法
     *
     * @return string HTTP 方法字符串
     */
    public function getMethod();

    /**
     * 返回更改了请求方法的消息实例。
     *
     * 虽然，在大部分情况下，HTTP 请求方法都是使用大写字母来标示的，但是，实现类库 **一定不可**
     * 修改用户传参的大小格式。
     * 
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 请求实例，然后返回
     * 一个新的修改过的 HTTP 请求实例。
     *
     * @param string $method 大小写敏感的方法名
     * @return self
     * @throws \InvalidArgumentException 当非法的 HTTP 方法名传入时会抛出异常。
     */
    public function withMethod($method);

    /**
     * 获取 URI 实例。
     *
     * 此方法必须返回 `UriInterface` 的 URI 实例。
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface 返回与当前请求相关 `UriInterface` 类型的 URI 实例。
     */
    public function getUri();

    /**
     * 返回修改了 URI 的消息实例。
     *
     * 当传入的 `URI` 包含有 `HOST` 信息时，**必须** 更新 `HOST` 头信息，如果 `URI` 
     * 实例没有附带 `HOST` 信息，任何之前存在的 `HOST` 信息 **必须** 作为候补，应用
     * 更改到返回的消息实例里。
     * 
     * 你可以通过传入第二个参数来，来干预方法的处理，当 `$preserveHost` 设置为 `true` 
     * 的时候，会保留原来的 `HOST` 信息。
     * 
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 请求实例，然后返回
     * 一个新的修改过的 HTTP 请求实例。
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri `UriInterface` 类型的 URI 实例
     * @param bool $preserveHost 是否保留原有的 HOST 头信息
     * @return self
     */
    public function withUri(UriInterface $uri, $preserveHost = false);
}
```

#### 3.2.1 `Psr\Http\Message\ServerRequestInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Representation of an incoming, server-side HTTP request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * Additionally, it encapsulates all data as it has arrived to the
 * application from the CGI and/or PHP environment, including:
 *
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body parameters (generally from $_POST)
 *
 * $_SERVER values MUST be treated as immutable, as they represent application
 * state at the time of request; as such, no methods are provided to allow
 * modification of those values. The other values provide such methods, as they
 * can be restored from $_SERVER or the request body, and may need treatment
 * during the application (e.g., body parameters may be deserialized based on
 * content type).
 *
 * Additionally, this interface recognizes the utility of introspecting a
 * request to derive and match additional parameters (e.g., via URI path
 * matching, decrypting cookie values, deserializing non-form-encoded body
 * content, matching authorization headers to users, etc). These parameters
 * are stored in an "attributes" property.
 *
 * HTTP 请求是被视为无法修改的，所有能修改状态的方法，都 **必须** 有一套机制，在内部保
 * 持好原有的内容，然后把修改状态后的，新的 HTTP 请求实例返回。
 */
interface ServerRequestInterface extends RequestInterface
{
    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams();

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    public function getCookieParams();

    /**
     * Return an instance with the specified cookies.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息实例，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     * 
     * @param array $cookies Array of key/value pairs representing cookies.
     * @return self
     */
    public function withCookieParams(array $cookies);

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * Note: the query params might not be in sync with the URI or server
     * params. If you need to ensure you are only getting the original
     * values, you may need to parse the query string from `getUri()->getQuery()`
     * or from the `QUERY_STRING` server param.
     *
     * @return array
     */
    public function getQueryParams();

    /**
     * Return an instance with the specified query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息实例，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @param array $query Array of query string arguments, typically from
     *     $_GET.
     * @return self
     */
    public function withQueryParams(array $query);

    /**
     * Retrieve normalized file upload data.
     *
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Psr\Http\Message\UploadedFileInterface.
     *
     * These values MAY be prepared from $_FILES or the message body during
     * instantiation, or MAY be injected via withUploadedFiles().
     *
     * @return array An array tree of UploadedFileInterface instances; an empty
     *     array MUST be returned if no data is present.
     */
    public function getUploadedFiles();

    /**
     * Create a new instance with the specified uploaded files.
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息实例，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @param array An array tree of UploadedFileInterface instances.
     * @return self
     * @throws \InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles);

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only. A null value indicates
     * the absence of body content.
     *
     * @return null|array|object The deserialized body parameters, if any.
     *     These will typically be an array or object.
     */
    public function getParsedBody();

    /**
     * Return an instance with the specified body parameters.
     *
     * These MAY be injected during instantiation.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, use this method
     * ONLY to inject the contents of $_POST.
     *
     * The data IS NOT REQUIRED to come from $_POST, but MUST be the results of
     * deserializing the request body content. Deserialization/parsing returns
     * structured data, and, as such, this method ONLY accepts arrays or objects,
     * or a null value if nothing was available to parse.
     *
     * As an example, if content negotiation determines that the request data
     * is a JSON payload, this method could be used to create a request
     * instance with the deserialized parameters.
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息实例，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @param null|array|object $data The deserialized body data. This will
     *     typically be in an array or object.
     * @return self
     * @throws \InvalidArgumentException if an unsupported argument type is
     *     provided.
     */
    public function withParsedBody($data);

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return mixed[] Attributes derived from the request.
     */
    public function getAttributes();

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($name, $default = null);

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息实例，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     * @return self
     */
    public function withAttribute($name, $value);

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息实例，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @return self
     */
    public function withoutAttribute($name);
}
```

### 3.3 `Psr\Http\Message\ResponseInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Representation of an outgoing, server-side response.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 *
 * Responses are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 * 
 * HTTP 响应是被视为无法修改的，所有能修改状态的方法，都 **必须** 有一套机制，在内部保
 * 持好原有的内容，然后把修改状态后的，新的 HTTP 响应实例返回。
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode();

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * 此方法在实现的时候，**必须** 保留原有的不可修改的 HTTP 消息实例，然后返回
     * 一个新的修改过的 HTTP 消息实例。
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return self
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '');

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be empty. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase();
}
```

### 3.4 `Psr\Http\Message\StreamInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Describes a data stream.
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 */
interface StreamInterface
{
    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString();

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close();

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach();

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize();

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public function tell();

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof();

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Seek to a position in the stream.
     *
     * @see http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @see http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind();

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string);

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length);

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws \RuntimeException if unable to read.
     * @throws \RuntimeException if error occurs while reading.
     */
    public function getContents();

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @see http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null);
}
```

### 3.5 `Psr\Http\Message\UriInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * URI 数据对象。
 *
 * 此接口按照 RFC 3986 来构建 HTTP URI，提供了一些通用的操作，你可以自由的对此接口
 * 进行扩展。你可以使用此 URI 接口来做 HTTP 相关的操作，也可以使用此接口做任何 URI 
 * 相关的操作。
 *
 * 此接口的实例化对象被视为无法修改的，所有能修改状态的方法，都 **必须** 有一套机制，在内部保
 * 持好原有的内容，然后把修改状态后的，新的实例返回。
 *
 * @see http://tools.ietf.org/html/rfc3986 (URI 通用标准规范)
 */
interface UriInterface
{
    /**
     * 从 URI 中取出 scheme。
     *
     * 如果不存在 Scheme，此方法 **必须** 返回空字符串。
     *
     * 返回的数据 **必须** 是小写字母，遵照  RFC 3986 规范 3.1 章节。
     *
     * 最后部分的 ":" 字串不属于 Scheme，**一定不可** 作为返回数据的一部分。
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string URI scheme 的值
     */
    public function getScheme();

    /**
     * 返回 URI 授权信息。
     *
     * 如果没有 URI 信息的话，**必须** 返回一个空数组。
     *
     * URI 的授权信息语法是：
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * 如果端口部分没有设置，或者端口不是标准端口，**一定不可** 包含在返回值内。
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string URI 授权信息，格式为："[user-info@]host[:port]" 
     */
    public function getAuthority();

    /**
     * 从 URI 中获取用户信息。
     *
     * 如果不存在用户信息，此方法 **必须** 返回一个空字符串。
     *
     * 用户信息后面跟着的 "@" 字符，不是用户信息里面的一部分，**一定不可** 在返回值里
     * 出现。
     *
     * @return string URI 的用户信息，格式："username[:password]" 
     */
    public function getUserInfo();

    /**
     * 从 URI 信息中获取 HOST 值。
     *
     * 如果 URI 中没有此值，**必须** 返回空字符串。
     *
     * 返回的数据 **必须** 是小写字母，遵照  RFC 3986 规范 3.2.2 章节。
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string URI 信息中的 HOST 值。
     */
    public function getHost();

    /**
     * 从 URI 信息中获取端口信息。
     *
     * 如果端口信息是与当前 Scheme 的标准端口不匹配的话，就使用整数值的格式返回，如果是一
     * 样的话，**必须** 返回 `null` 值。
     * 
     * 如果存在端口信息，都是不存在 scheme 信息的话，**必须** 返回 `null` 值。
     * 
     * 如果不存在端口数据，但是 scheme 数据存在的话，**可以** 返回 scheme 对应的
     * 标准端口，但是 **应该** 返回 `null`。
     * 
     * @return null|int 从 URI 信息中的端口信息。
     */
    public function getPort();

    /**
     * 从 URI 信息中获取路径。
     *
     *  The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     *
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URI path.
     */
    public function getPath();

    /**
     * Retrieve the query string of the URI.
     *
     * If no query string is present, this method MUST return an empty string.
     *
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     *
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string The URI query string.
     */
    public function getQuery();

    /**
     * Retrieve the fragment component of the URI.
     *
     * If no fragment is present, this method MUST return an empty string.
     *
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URI fragment.
     */
    public function getFragment();

    /**
     * Return an instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     *
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     * @return self A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid schemes.
     * @throws \InvalidArgumentException for unsupported schemes.
     */
    public function withScheme($scheme);

    /**
     * Return an instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return self A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null);

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return self A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host);

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     * @return self A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port);

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If an HTTP path is intended to be host-relative rather than path-relative
     * then it must begin with a slash ("/"). HTTP paths not starting with a slash
     * are assumed to be relative to some base path known to the application or
     * consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     * @return self A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path);

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return self A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query);

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return self A new instance with the specified fragment.
     */
    public function withFragment($fragment);

    /**
     * Return the string representation as a URI reference.
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string
     */
    public function __toString();
}
```

### 3.6 `Psr\Http\Message\UploadedFileInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * 通过 HTTP 请求上传的一个文件内容。
 *
 * 此接口的实例是被视为无法修改的，所有能修改状态的方法，都 **必须** 有一套机制，在内部保
 * 持好原有的内容，然后把修改状态后的，新的实例返回。
 */
interface UploadedFileInterface
{
    /**
     * 获取上传文件的数据流。
     *
     * 此方法必须返回一个 `StreamInterface` 实例，此方法的目的在于允许 PHP 对获取到的数
     * 据流直接操作，如 stream_copy_to_stream() 。
     *
     * 如果在调用此方法之前调用了 `moveTo()` 方法，此方法 **必须** 抛出异常。
     *
     * @return StreamInterface 上传文件的数据流
     * @throws \RuntimeException 没有数据流的情形下。
     * @throws \RuntimeException 无法创建数据流。
     */
    public function getStream();

    /**
     * 把上传的文件移动到新目录。
     *
     * 此方法保证能同时在 `SAPI` 和 `non-SAPI` 环境下使用。实现类库 **必须** 判断
     * 当前处在什么环境下，并且使用合适的方法来处理，如 move_uploaded_file(), rename()
     * 或者数据流操作。
     *
     * $targetPath 可以是相对路径，也可以是绝对路径，使用 rename() 解析起来应该是一样的。
     *
     * 当这一次完成后，原来的文件 **必须** 会被移除。
     * 
     * 如果此方法被调用多次，一次以后的其他调用，都要抛出异常。
     *
     * 如果在 SAPI 环境下的话，$_FILES 内有值，当使用  moveTo(), is_uploaded_file()
     * 和 move_uploaded_file() 方法来移动文件时 **应该** 确保权限和上传状态的准确性。
     * 
     * 如果你希望操作数据流的话，请使用 `getStream()` 方法，因为在 SAPI 场景下，无法
     * 保证书写入数据流目标。
     * 
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     * @param string $targetPath 目标文件路径。
     * @throws \InvalidArgumentException 参数有问题时抛出异常。
     * @throws \RuntimeException 发生任何错误，都抛出此异常。
     * @throws \RuntimeException 多次运行，也抛出此异常。
     */
    public function moveTo($targetPath);

    /**
     * 获取文件大小。
     *
     * 实现类库 **应该** 优先使用 $_FILES 里的 `size` 数值。
     * 
     * @return int|null 以 bytes 为单位，或者 null 未知的情况下。
     */
    public function getSize();

    /**
     * 获取上传文件时出现的错误。
     *
     * 返回值 **必须** 是 PHP 的 UPLOAD_ERR_XXX 常量。
     *
     * 如果文件上传成功，此方法 **必须** 返回 UPLOAD_ERR_OK。
     *
     * 实现类库 **必须** 返回 $_FILES 数组中的 `error` 值。
     * 
     * @see http://php.net/manual/en/features.file-upload.errors.php
     * @return int PHP 的 UPLOAD_ERR_XXX 常量。
     */
    public function getError();

    /**
     * 获取客户端上传的文件的名称。
     * 
     * 永远不要信任此方法返回的数据，客户端有可能发送了一个恶意的文件名来攻击你的程序。
     * 
     * 实现类库 **应该** 返回存储在 $_FILES 数组中 `name` 的值。
     *
     * @return string|null 用户上传的名字，或者 null 如果没有此值。
     */
    public function getClientFilename();

    /**
     * 客户端提交的文件类型。
     * 
     * 永远不要信任此方法返回的数据，客户端有可能发送了一个恶意的文件类型名称来攻击你的程序。
     *
     * 实现类库 **应该** 返回存储在 $_FILES 数组中 `type` 的值。
     *
     * @return string|null 用户上传的类型，或者 null 如果没有此值。
     */
    public function getClientMediaType();
}
```



