# Use a imagem oficial do PHP 8.1 com Apache como base
FROM php:8.1-cli

RUN apt-get update && \
    apt-get install -y libpq-dev libzip-dev zip && \
    docker-php-ext-install pdo pdo_mysql zip

WORKDIR /src

COPY . .

CMD ["php", "-S", "0.0.0.0:8080", "-t", "./public"]
