FROM alpine
RUN apk update
RUN apk add nginx php84-fpm php84-fileinfo php84-intl php84-mbstring php84-pdo_pgsql php84-pgsql php84-opcache php84-pdo php84-pdo_mysql php84-phar php84-xml php84-zip php84-curl php84-json php84-gd php84-dom php84-iconv php84-session php84-tokenizer php84-simplexml php84-xmlreader php84-xmlwriter php84-sockets php84-bcmath php84-ctype php84-common
#redirect logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log
COPY container/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
RUN chmod 777 /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
