#!/bin/bash

# 设置时区
ln -sf /usr/share/zoneinfo/${TZ:-"Asia/Shanghai"} /etc/localtime
echo ${TZ:-"Asia/Shanghai"} > /etc/timezone

# sshd
if [ -n "${SSH_PASSWORD}" ];then
    mkdir -p /var/run/sshd
    echo root:${SSH_PASSWORD} | chpasswd
    sed -ri 's/^#?PermitRootLogin\s+.*/PermitRootLogin yes/' /etc/ssh/sshd_config
    /usr/sbin/sshd
fi

# 计划任务设置crontab
if [ -z $DISABLE_CRON ];then
    REFRESH_TOKEN=${REFRESH_TOKEN:-"0 * * * *"}
    REFRESH_CACHE=${REFRESH_CACHE:-"*/10 * * * *"}
    rm -rf /tmp/cron.`whoami`
    echo "${REFRESH_TOKEN} php /var/www/html/one.php token:refresh" >> /tmp/cron.`whoami`
    echo "${REFRESH_CACHE} php /var/www/html/one.php cache:refresh" >> /tmp/cron.`whoami`
    crontab -u `whoami` /tmp/cron.`whoami`
    crond
fi

# 设置nginx监听端口
sed -i "s|listen 80|listen ${PORT:-80}|" /etc/nginx/conf.d/nginx.conf
# 设置默认模式为去掉？
sed -i "'root_path' => '?'|'root_path' => ''|" /var/www/html/controller/AdminController.php

chown -R www-data:www-data /var/www/html/cache
chown -R www-data:www-data /var/www/html/config
php-fpm & nginx '-g daemon off;'