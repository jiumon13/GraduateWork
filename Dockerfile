FROM ubuntu:14.04

RUN apt-get dist-upgrade
RUN apt-get -qq update && apt-get -y install apache2 libapache2-mod-php5 curl php5 php5-cli php5-dev php5-curl php5-gd php5-mysql git \
    php5-xdebug

# Enable apache mod
RUN a2enmod php5

#Enable rewrite mod
RUN a2enmod rewrite

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

EXPOSE 80

# Add application directory
RUN mkdir -p /var/www
WORKDIR /var/www

COPY configuration/apache.conf /etc/apache2/sites-enabled/000-default.conf

# Start apache.
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]