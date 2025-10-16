FROM dunglas/frankenphp:latest

# 切换国内镜像
RUN if [ -f /etc/apt/sources.list.d/debian.sources ]; then \
    sed -i "s|URIs: http://deb.debian.org/debian|URIs: https://mirrors.aliyun.com/debian|g" /etc/apt/sources.list.d/debian.sources;  \
    elif [ -f /etc/apt/sources.list ]; then \
    sed -i "s|http://deb.debian.org/debian|https://mirrors.aliyun.com/debian|g" /etc/apt/sources.list; \
    fi

# 在此处添加其他扩展：
RUN install-php-extensions \
    pdo_mysql  \
    gd  \
    intl  \
    zip  \
    opcache  \
    bcmath  \
    pcntl  \
    redis

CMD ["frankenphp", "run", "--watch", "--config", "/etc/caddy/Caddyfile", "--adapter","caddyfile"]