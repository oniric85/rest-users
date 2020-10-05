FROM httpd:2.4

ARG APP_USER
ARG APP_GROUP

COPY httpd-vhosts.conf /usr/local/apache2/conf/extra/httpd-vhosts.conf

RUN sed -i '/^#Include conf\/extra\/httpd-vhosts.conf/s/^#//g' /usr/local/apache2/conf/httpd.conf && \
    sed -i '/^#LoadModule rewrite_module modules\/mod_rewrite.so/s/^#//g' /usr/local/apache2/conf/httpd.conf && \
    sed -i '/^#LoadModule proxy_module modules\/mod_proxy.so/s/^#//g' /usr/local/apache2/conf/httpd.conf && \
    sed -i '/^#LoadModule proxy_fcgi_module modules\/mod_proxy_fcgi.so/s/^#//g' /usr/local/apache2/conf/httpd.conf

RUN usermod -u 1000 ${APP_USER} && \
    groupmod -g 1000 ${APP_GROUP}