FROM php:8.2-cli

WORKDIR /app

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

CMD [ "php", "./public/main.php" ]