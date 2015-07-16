####关于PHPExcel导出的excel在打开时出现'包含不可读取内容'？

解决：   
  在 `$objWrite->save('php://output');` 输出excel到浏览器后，立即执行 `exit()`；
