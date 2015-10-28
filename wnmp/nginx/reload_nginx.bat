@echo off
REM Windows 下无效
REM set PHP_FCGI_CHILDREN=5

tasklist /nh|find /i "php-cgi.exe" > nul
echo Reloading PHP FastCGI...
if ERRORLEVEL 1 (
RunHiddenConsole D:/wnmp/php5.6/php-cgi.exe -b 127.0.0.1:9000 -c D:/wnmp/php5.6/php.ini
) else (
taskkill /F /IM php-cgi.exe > nul
RunHiddenConsole D:/wnmp/php5.6/php-cgi.exe -b 127.0.0.1:9000 -c D:/wnmp/php5.6/php.ini
)

tasklist /nh|find /i "nginx.exe" > nul
echo Reloading nginx...
if ERRORLEVEL 1 (
RunHiddenConsole D:/wnmp/nginx/nginx.exe -p D:/wnmp/nginx
) else (
RunHiddenConsole D:/wnmp/nginx/nginx.exe -s reload -p D:/wnmp/nginx
)


