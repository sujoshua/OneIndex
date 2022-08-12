FROM php:fpm-alpine


RUN apk add --no-cache \
        git \
        bash \
        nginx \
        tzdata \
        openssh

RUN  git clone https://github.com/sujoshua/OneIndex.git /var/www/html

RUN  mkdir -p /run/nginx && \
    mv /var/www/html/docker/nginx.conf /etc/nginx/http.d/default.conf && \
    mv /var/www/html/docker/php.ini /usr/local/etc/php && \
    mv /var/www/html/docker/docker-entrypoint.sh / &&\
    chmod +x /docker-entrypoint.sh && \
    rm -r /var/www/html/docker && \
    ssh-keygen -A

EXPOSE 80
# Persistent config file and cache
VOLUME [ "/var/www/html/config", "/var/www/html/cache" ]
ENTRYPOINT [ "/docker-entrypoint.sh" ]