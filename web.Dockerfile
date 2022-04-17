FROM ubuntu:latest

RUN apt-get update && apt-get install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php && apt-get update && apt-get install php8.1 php-mysql -y
RUN groupadd -g 5000 -r upb-rental
RUN useradd -g 5000 -u 5000 -M -d /opt/upb-rental -s /sbin/nologin -r upb-rental
WORKDIR /opt/upb-rental
CMD php -S 0.0.0.0:8080