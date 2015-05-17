<?php

/**
* 
*/
class TestReflection
{
	public static $arg1 = null;
	protected $arg2 = 0;
	private $arg3 = '';
	function __construct($argument)
	{
		# code...

	}
}

/**
* 
*/
class TestExport extends TestReflection
{
	public static $arg4 = null;
	protected $arg5 = 0;
	private $arg6 = '';
	function __construct($argument)
	{
		# code...
		parent::__construct(1);
	}
}


$ref_class = new ReflectionClass('TestExport');
//Reflection::export($ref_class, false);
//Reflection::getModifierNames(1);
echo $ref_class->name;
echo ReflectionClass::IS_FINAL;