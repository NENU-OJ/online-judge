FROM ubuntu

# edit sources.list according to https://www.alibabacloud.com/help/zh/doc-detail/120851.htm
RUN echo 'deb http://mirrors.aliyun.com/ubuntu/ focal main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ focal main restricted universe multiverse\n\
\n\
deb http://mirrors.aliyun.com/ubuntu/ focal-security main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ focal-security main restricted universe multiverse\n\
\n\
deb http://mirrors.aliyun.com/ubuntu/ focal-updates main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ focal-updates main restricted universe multiverse\n\
\n\
deb http://mirrors.aliyun.com/ubuntu/ focal-proposed main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ focal-proposed main restricted universe multiverse\n\
\n\
deb http://mirrors.aliyun.com/ubuntu/ focal-backports main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ focal-backports main restricted universe multiverse\n' > /etc/apt/sources.list

RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        vim \
        less \
        git \
        software-properties-common \
    && add-apt-repository -y ppa:ondrej/php && apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        php7.0 php7.0-gd php7.0-mysql php7.0-fpm php7.0-memcached \
        nginx

COPY web/start.sh /
COPY Front /web
RUN chown -R www-data:www-data /web/runtime /web/views/smarty/templates_c

CMD ["/start.sh"]