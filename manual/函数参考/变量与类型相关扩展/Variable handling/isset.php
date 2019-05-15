<?php

/**
 * isset ( mixed $var [, mixed $... ] ) : bool
 * 
 * 1. 只能用于变量
 * 2. 是语言构造器，非函数不能可变函数调用
 * 3. 变量不存在 or =NULL 时 false
 *                  4. 入参全部被设置时返回true
 */