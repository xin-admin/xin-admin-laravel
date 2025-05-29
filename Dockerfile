FROM dunglas/frankenphp

# 设置工作目录
WORKDIR /app

RUN install-php-extensions \
    bcmath \
    pdo_mysql \
    pcntl \
    intl \
    opcache\
    redis
    # Add other PHP extensions here...

COPY . /app

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# 关闭 HTTPS（FrankenPHP 默认启用）
ENV FRANKENPHP_HTTPS_OFF=1

#Usage:
#   caddy php-server [--domain <example.com>] [--root <path>] [--listen <addr>] [--worker /path/to/worker.php<,nb-workers>] [--watch <paths...>] [--access-log] [--debug] [--no-compress] [--mercure] [flags]
#
#Flags:
#   -a, --access-log           Enable the access log
#   -v, --debug                Enable verbose debug logs
#   -d, --domain string        Domain name at which to serve the files
#   -h, --help                 help for php-server
#   -l, --listen string        The address to which to bind the listener
#   -m, --mercure              Enable the built-in Mercure.rocks hub
#       --no-compress          Disable Zstandard, Brotli and Gzip compression
#   -r, --root string          The path to the root of the site
#       --watch stringArray    Directory to watch for file changes
#   -w, --worker stringArray   Worker script

# 使用 FrankenPHP 启动 Laravel 的 index.php 入口文件
CMD ["frankenphp", "run", "--watch", "--config", "/app/Caddyfile"]
