FROM php:7.1-fpm
MAINTAINER Vlad Mitchenko <vlad.mitchenko@yahoo.com>

RUN apt-get update

# Install extra libs here
RUN apt-get -y install libmcrypt-dev \
        libpng12-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev

# Install extra modules here
RUN docker-php-ext-install mcrypt \
        pdo \
        pdo_mysql \
        gd \
        zip

# Permissions
ENV LOCAL_USER user
ENV LOCAL_PASS password

RUN useradd -m -s /bin/bash ${LOCAL_USER} && \
    echo "${LOCAL_USER}:${LOCAL_PASS}"|chpasswd && \
    adduser ${LOCAL_USER} sudo && \
    echo ${LOCAL_USER}' ALL=(ALL) NOPASSWD: ALL' >> /etc/sudoers

USER user