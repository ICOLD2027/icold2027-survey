FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_sqlite \
    && a2enmod rewrite

COPY . /var/www/html/

# Apache must be allowed to write the SQLite data file.
RUN chown -R www-data:www-data /var/www/html/data \
    && chmod -R 775 /var/www/html/data

# AllowOverride All so the bundled .htaccess (rewrite rules, access rules) applies.
RUN sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Render (and most PaaS hosts) tell the container which port to listen on via
# $PORT at *runtime* -- rewrite Apache's port config on container start, then
# launch normally.
RUN printf '#!/bin/sh\nset -e\nPORT="${PORT:-10000}"\nsed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf\nsed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf\nexec apache2-foreground\n' > /usr/local/bin/start-apache.sh \
    && chmod +x /usr/local/bin/start-apache.sh

EXPOSE 10000
CMD ["/usr/local/bin/start-apache.sh"]
