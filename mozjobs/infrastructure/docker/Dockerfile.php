FROM php:8.2-cli
WORKDIR /var/www
COPY ../../backend /var/www
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
