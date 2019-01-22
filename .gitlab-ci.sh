#!/bin/bash

# Install dependencies only for Docker.
[[ ! -e /.dockerinit ]] && exit 0
set -xe

# Update packages and install composer and PHP dependencies.
apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E1DD270288B4E6030699E45FA1715D88E1DF1F24 -yqq
su -c "echo 'deb http://ppa.launchpad.net/git-core/ppa/ubuntu trusty main' > /etc/apt/sources.list.d/git.list" -yqq


apt-get update -yqq
apt-get install git libcurl4-gnutls-dev libicu-dev libmcrypt-dev libvpx-dev libjpeg-dev libpng-dev libxpm-dev zlib1g-dev libfreetype6-dev libxml2-dev libexpat1-dev libbz2-dev libgmp3-dev libldap2-dev unixodbc-dev libpq-dev libsqlite3-dev libaspell-dev libsnmp-dev libpcre3-dev libtidy-dev phpunit -yqq

# Compile PHP, include these extensions.
docker-php-ext-install mbstring mcrypt pdo pdo_mysql curl json intl gd xml zip bz2 opcache