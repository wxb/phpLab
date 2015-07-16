###PHPExcel生成excel列名 

* 将列的数字序号转成字母使用： `PHPExcel_Cell::stringFromColumnIndex($i); // 从o开始`  
  ```php
    /**
  	 *	String from columnindex
  	 *
  	 *	@param	int $pColumnIndex Column index (base 0 !!!)
  	 *	@return	string
  	 */
  	public static function stringFromColumnIndex($pColumnIndex = 0)
  	{
  		//	Using a lookup cache adds a slight memory overhead, but boosts speed
  		//	caching using a static within the method is faster than a class static,
  		//		though it's additional memory overhead
  		static $_indexCache = array();
  
  		if (!isset($_indexCache[$pColumnIndex])) {
  			// Determine column string
  			if ($pColumnIndex < 26) {
  				$_indexCache[$pColumnIndex] = chr(65 + $pColumnIndex);
  			} elseif ($pColumnIndex < 702) {
  				$_indexCache[$pColumnIndex] = chr(64 + ($pColumnIndex / 26)) .
  											  chr(65 + $pColumnIndex % 26);
  			} else {
  				$_indexCache[$pColumnIndex] = chr(64 + (($pColumnIndex - 26) / 676)) .
  											  chr(65 + ((($pColumnIndex - 26) % 676) / 26)) .
  											  chr(65 + $pColumnIndex % 26);
  			}
  		}
  		return $_indexCache[$pColumnIndex];
  	}
  	```
  	
* 将列的字母转成数字序号使用： `PHPExcel_Cell::columnIndexFromString(‘AA’);`  
  ```php
  	/**
  	 *	Column index from string
  	 *
  	 *	@param	string $pString
  	 *	@return	int Column index (base 1 !!!)
  	 */
  	public static function columnIndexFromString($pString = 'A')
  	{
  		//	Using a lookup cache adds a slight memory overhead, but boosts speed
  		//	caching using a static within the method is faster than a class static,
  		//		though it's additional memory overhead
  		static $_indexCache = array();
  
  		if (isset($_indexCache[$pString]))
  			return $_indexCache[$pString];
  
  		//	It's surprising how costly the strtoupper() and ord() calls actually are, so we use a lookup array rather than use ord()
  		//		and make it case insensitive to get rid of the strtoupper() as well. Because it's a static, there's no significant
  		//		memory overhead either
  		static $_columnLookup = array(
  			'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13,
  			'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26,
  			'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, 'g' => 7, 'h' => 8, 'i' => 9, 'j' => 10, 'k' => 11, 'l' => 12, 'm' => 13,
  			'n' => 14, 'o' => 15, 'p' => 16, 'q' => 17, 'r' => 18, 's' => 19, 't' => 20, 'u' => 21, 'v' => 22, 'w' => 23, 'x' => 24, 'y' => 25, 'z' => 26
  		);
  
  		//	We also use the language construct isset() rather than the more costly strlen() function to match the length of $pString
  		//		for improved performance
  		if (isset($pString{0})) {
  			if (!isset($pString{1})) {
  				$_indexCache[$pString] = $_columnLookup[$pString];
  				return $_indexCache[$pString];
  			} elseif(!isset($pString{2})) {
  				$_indexCache[$pString] = $_columnLookup[$pString{0}] * 26 + $_columnLookup[$pString{1}];
  				return $_indexCache[$pString];
  			} elseif(!isset($pString{3})) {
  				$_indexCache[$pString] = $_columnLookup[$pString{0}] * 676 + $_columnLookup[$pString{1}] * 26 + $_columnLookup[$pString{2}];
  				return $_indexCache[$pString];
  			}
  		}
  		throw new PHPExcel_Exception("Column string index can not be " . ((isset($pString{0})) ? "longer than 3 characters" : "empty"));
  	}
    
  ```
  