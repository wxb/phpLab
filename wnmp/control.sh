#!/bin/sh

if [ $# -lt 1 ];then
	echo "usage : bash $0 [start|stop|restart]"
	exit
fi

function start()
{
	/usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf
	/usr/local/nginx/sbin/nginx
	/usr/local/php/sbin/php-fpm -c /usr/local/php/etc/php.ini -y /usr/local/php/etc/php-fpm.conf
}

function stop()
{
	killall /usr/local/php/sbin/php-fpm
	/usr/local/nginx/sbin/nginx -s stop
}

function restart()
{
	killall /usr/local/php/sbin/php-fpm
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

