FROM nginx:1.25

ADD ./nginx/default.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/api