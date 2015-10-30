#!/bin/sh

if [ $# -lt 1 ];then
	echo "usage : bash $0 [start|stop|restart]"
	exit
fi

function start()
{
	/usr/local/nginx/sbin/nginx 
	/usr/local/php/sbin/php-fpm -c /usr/local/php/etc/php.ini -y /usr/local/php/etc/php-fpm.conf 
}

function stop()
{
	killall php-fpm 
	/usr/local/nginx/sbin/nginx -s stop 
}

function restart()
{
	killall php-fpm 
	/usr/local/php/sbin/php-fpm -c /usr/local/php/etc/php.ini -y /usr/local/php/etc/php-fpm.conf
	/usr/local/nginx/sbin/nginx -s reload
}

case $1 in
	start)
		start
		;;
	stop)
		stop
		;;
	restart)
		restart
		;;
	*)
		echo "usage : bash $0 [start|stop|restart]"
		;;
esac

