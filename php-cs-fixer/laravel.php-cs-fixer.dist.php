<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    'array_syntax' => ['syntax' => 'short'], // php 数组定义语法：long / short
    'list_syntax' => ['syntax' => 'short'], // List(数组解构)赋值应该使用配置的语法声明。需要PHP >= 7.1。

    'blank_line_after_opening_tag' => true,
    'braces' => [
        'allow_single_line_closure' => true, // 运行单行闭包
    ],
    'cast_spaces' => ['space' => 'single'], // 在强制转换和变量之间应用的间距
    'concat_space' => [
        'spacing' => 'one', // 根据配置拼接
    ],
    'no_alternative_syntax' => true, // 将控制结构替代语法替换为使用大括号。
    'declare_equal_normalize' => ['space' => 'single'],
    'elseif' => true,
    'encoding' => true,
    'full_opening_tag' => true,
    'fully_qualified_strict_types' => true, // added by Shift
    'function_declaration' => true, // 在函数声明中应该正确地放置空格。
    'function_typehint_space' => true, // 确保函数的参数和它的typehint之间只有一个空格。
    'heredoc_to_nowdoc' => true,
    'include' => true, // Include/Require和文件路径应该用一个空格分隔。文件路径不应放在括号内。
    'increment_style' => ['style' => 'post'],
    'indentation_type' => true,
    'linebreak_after_opening_tag' => true,
    'line_ending' => true,
    'lowercase_cast' => true, // 强制类型转换应该使用小写字母
    'lowercase_keywords' => true, // PHP关键字必须小写。
    'lowercase_static_reference' => true, // 类静态引用self, static和parent必须是小写的。 added from Symfony
    'magic_method_casing' => true, // Magic方法应该使用正确的大小写来引用。 added from Symfony
    'magic_constant_casing' => true, // Magic常量应该使用正确的大小写来引用
    'method_argument_space' => [ // 方法参数
        'keep_multiple_spaces_after_comma' => false,
        'on_multiline' => 'ensure_fully_multiline',
        'after_heredoc' => true,
    ],
    'native_function_casing' => true, // PHP定义的函数应该使用正确的大小写来调用。
    'native_function_type_declaration_casing' => true, // 函数方法的原生类型提示应该使用正确的大小写
    'no_alias_functions' => true,
    // 删除配置后的额外空行和/或空行。
    'no_extra_blank_lines' => [
        'tokens' => [
            'extra',
            'throw',
            'use',
            'use_trait',
        ],
    ],
    'no_blank_lines_after_class_opening' => true, // 类左大括号后面不应该有空行。
    'no_blank_lines_after_phpdoc' => true, // 文档块和文档元素之间不应该有空行。
    'no_closing_tag' => true,
    'no_empty_phpdoc' => true, // 不应该有空的PHPDoc块。
    'no_empty_statement' => true, // 删除无用的(分号)语句。
    'no_leading_import_slash' => true, // 删除use子句中的前导斜杠。
    'no_mixed_echo_print' => [
        'use' => 'echo',
    ],
    'no_multiline_whitespace_around_double_arrow' => true, // 操作符=> 不应该被多行空格包围。
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],
    'no_short_bool_cast' => true, // 不应使用使用双感叹号的短强制转换bool。
    'no_singleline_whitespace_before_semicolons' => true, // 禁止在结束分号前使用单行空格。
    'no_spaces_after_function_name' => true, // 当进行方法或函数调用时，方法或函数名和开括号之间不能有空格。
    'no_spaces_inside_parenthesis' => true, // 开括号后面不能有空格。右括号前不能有空格。
    'no_trailing_comma_in_list_call' => true, // 删除列表函数调用中的末尾逗号。
    'no_trailing_comma_in_singleline_array' => true, // PHP单行数组的末尾不应该有逗号。
    'no_trailing_whitespace' => true, // 删除非空行末尾的尾随空格。
    'no_trailing_whitespace_in_comment' => true, // 在注释或PHPDoc中不能有尾随空格。
    'no_unreachable_default_argument_value' => true,
    'no_useless_return' => true, // 在函数的末尾不应该有一个空的return语句。
    'no_whitespace_before_comma_in_array' => true, // 数组定义中，逗号前禁止有空格
    'no_whitespace_in_blank_line' => true, // 删除空行末尾的空白。
    'normalize_index_brace' => true, // 数组下标应该始终使用方括号来编写。
    'not_operator_with_successor_space' => true, // 逻辑NOT操作符(!)末尾应该有一个空格。
    'object_operator_without_whitespace' => true, // 在对象操作符>和?->之前和之后不应该有空格。
    'phpdoc_indent' => true, // 文档块应该具有与文档主题相同的缩进。
    'phpdoc_no_access' => true,
    'phpdoc_no_package' => true,
    'phpdoc_no_useless_inheritdoc' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true, // PHPDoc的摘要应该以句号、感叹号或问号结束。
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_var_without_name' => true,
    'self_accessor' => true,
    'short_scalar_cast' => true, // 强制类型转换 (boolean)和(integer)应该写成(bool)和(int)， (double)和(real)写成(float)， (binary)写成(string)。
    'simplified_null_return' => false, // 希望返回void的return语句不应该返回null。
    'single_blank_line_at_eof' => true, // 没有结束标记的PHP文件必须始终以一个空换行符结束。
    'single_line_comment_style' => [
        'comment_types' => ['asterisk', 'hash'], // 单行注释和只有一行实际内容的多行注释应该使用//语法。
    ],
    'single_quote' => true, // 将简单字符串的双引号转换为单引号。
    'space_after_semicolon' => true, // 修正分号后的空白。
    'standardize_not_equals' => true, // Replace all <> with !=.
    'switch_case_semicolon_to_colon' => true, // case后面应该跟着冒号而不是分号。
    'switch_case_space' => true, // 删除冒号和大小写值之间的额外空格。
    'ternary_operator_spaces' => true, // 标准化三元运算符周围的空间。
    'trim_array_spaces' => true, // 数组应该像函数/方法参数一样格式化，没有前导或末尾的单行空格。
    'unary_operator_spaces' => true, // 一元运算符应该放在与其操作数相邻的位置。
    'whitespace_after_comma_in_array' => true, // 在数组声明中，每个逗号后必须有一个空格。

    // php-cs-fixer 3: Renamed rules
    'constant_case' => ['case' => 'lower'], // PHP常量true、false和null必须使用正确的大小写。
    'general_phpdoc_tag_rename' => true,
    'phpdoc_inline_tag_normalizer' => true, // 处理phpdoc行内标签
    'phpdoc_tag_type' => true,
    'psr_autoloading' => true,
    'trailing_comma_in_multiline' => ['elements' => ['arrays']], // 多行数组，参数列表和形参列表必须有一个逗号。

    'array_indentation' => true, // 数组中的每个元素必须精确缩进一次。
    'compact_nullable_typehint' => true, // 删除可空类型提示中的额外空格。
    // 二进制运算符应该像配置的那样被空格包围。
    'binary_operator_spaces' => [
        'default' => 'single_space',
        'operators' => [
            '=>' => 'align_single_space_minimal',
            '=' => 'align_single_space_minimal',
        ],
    ],
    // 空换行符必须在任何已配置语句之前。
    'blank_line_before_statement' => [
        'statements' => ['yield'],
    ],
    // 类元素、特征元素和接口元素必须用一行或一行都不空白分隔。
    'class_attributes_separation' => [
        'elements' => [
            'trait_import' => 'only_if_meta',
            'const' => 'only_if_meta',
            'method' => 'one',
            'property' => 'only_if_meta',
        ],
    ],
    // 类、特征或接口定义的关键字周围的空格应该是一个空格。
    'class_definition' => [
        'multi_line_extends_each_single_line' => false,
        'single_item_single_line' => true,
        'single_line' => true,
        'space_before_parenthesis' => false,
    ],
    // use 引入组织排序
    'ordered_imports' => [
        'sort_algorithm' => 'none',
        'imports_order' => ['const', 'class', 'function'],
    ],
    'single_import_per_statement' => true, // 每个声明必须有一个use关键字。
    'single_line_after_imports' => true, // 每个名称空间use MUST都在它自己的行上，并且在use statements块之后必须有一个空行。

    // 删除控制语句周围不需要的括号。
    'no_unneeded_control_parentheses' => [
        'statements' => ['break', 'clone', 'continue', 'echo_print', 'return', 'switch_case', 'yield'],
    ],
    // 在偏移大括号周围不能有空格。
    'no_spaces_around_offset' => [
        'positions' => ['inside', 'outside'],
    ],
    'visibility_required' => [
        'elements' => ['property', 'method', 'const'], // 必须在所有属性和方法上声明可见性
    ],

    'no_unset_cast' => true, // 变量必须设置为空而不是使用(unset)强制转换。
    'no_null_property_initialization' => true, // 除非属性有类型声明，否则不能显式地用null初始化属性(PHP 7.4)。
    'ordered_class_elements' => [
        'order' => ['use_trait', 'constant', 'property', 'construct', 'destruct', 'magic', 'method'], // 排序类/接口/特征的元素。
    ],
    'no_unneeded_curly_braces' => true, // 删除不必要的花括号，它们是多余的，并且不是控制结构主体的一部分。
    'self_static_accessor' => true,
    'single_class_element_per_statement' => true, // 每个语句中不能声明多个属性或常量。
    'no_break_comment' => true, // switch 添加 no break
    'no_superfluous_elseif' => true, // 用if替换多余的elseif。
    'no_useless_else' => true, // There should not be useless else cases.
    'yoda_style' => true, // 以Yoda风格(true)、非Yoda风格(['equal' => false， 'identical' => false， 'less_and_greater' => false])写入条件，或者根据配置忽略这些条件(null)。
    'lambda_not_used_import' => true, // Lambda不能导入它不使用的变量。
    'return_type_declaration' => true,
    'single_line_throw' => true, // 单行抛异常
    'no_unused_imports' => true, // 必须删除未使用的使用语句。
    'combine_consecutive_issets' => true, // 多次使用isset($var) &&应该在一次调用中完成。
    'combine_consecutive_unsets' => true, // 在多个项目上调用unset应该在一次调用中完成。
    'single_space_after_construct' => true, // 确保语言结构后面只有一个空格。
    'blank_line_after_namespace' => true, // 名称空间声明后必须有一个空行。
    'clean_namespace' => true, // 命名空间不能包含空格、注释或PHPDoc。
    'no_leading_namespace_whitespace' => true, // 命名空间声明行不应该包含前导空格。
    'single_blank_line_before_namespace' => true, // 在命名空间声明之前应该正好有一个空行。
    'not_operator_with_space' => true, // 逻辑NOT操作符(!)应该有前导和末尾的空格。
    'ternary_to_null_coalescing' => true, // 使用空合并操作符??在可能的情况下。需要PHP >= 7.0。
    'return_assignment' => true, // 局部、动态和直接引用的变量不应该被函数或方法赋值或直接返回。
    'explicit_string_variable' => true, // 将隐式变量转换为双引号字符串或heredoc语法中的显式变量。
    'types_spaces' => [
        'space' => 'single', // 联合类型操作符周围应该有一个空格或none。
    ],
    //
    'align_multiline_comment' => true,
    'phpdoc_add_missing_param_annotation' => true, // PHPDoc should contain @param for all params.
    'phpdoc_align' => [
        'align' => 'vertical', // 给定的phpdoc标记的所有项必须左对齐或(默认情况下)垂直对齐。
    ],
    'phpdoc_annotation_without_dot' => true, // PHPDoc注释描述不应该是一个句子。
    // 将文档块从单行更改为多行或反转。仅适用于类常量、属性和方法。
    'phpdoc_line_span' => [
        'const' => 'multi',
        'property' => 'multi',
        'method' => 'multi',
    ],
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);