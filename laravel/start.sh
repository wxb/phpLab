#!/bin/bash
docker run --name laravel -v /Users/wangxb/github/phpLab/laravel/app/:/var/www/laravel/app/ -v /Users/wangxb/github/phpLab/laravel/public/:/var/www/laravel/public/ -v /Users/wangxb/github/phpLab/laravel/vendor/:/var/www/laravel/vendor/ -p 80:80 -p 443:443 -d eboraas/laravel
