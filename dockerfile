FROM php:8.1-cli

WORKDIR /var/www/html

COPY . .

CMD php -S 0.0.0.0:$PORT -t /var/www/html