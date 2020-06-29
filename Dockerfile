FROM trafex/alpine-nginx-php7
COPY src /var/www/html
RUN /var/www/html/update_covid.sh