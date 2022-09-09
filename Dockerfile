ARG PHP_VERSION

FROM php:$PHP_VERSION

MAINTAINER bxo@trf4.jus.br

ARG COMPOSER_VERSION
ARG PYTHON_VERSION
ARG XDEBUG_VERSION
ARG INSTALL_XDEBUG

ENV HOME_DIR /home/developer
ENV NVM_DIR ${HOME_DIR}/.nvm
ENV TZ=GMT-3


RUN export LC_ALL=C.UTF-8 && \
	DEBIAN_FRONTEND=noninteractive && \
	rm /bin/sh && ln -s /bin/bash /bin/sh && \
	ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone




RUN apt-get update && \
	apt-get install -y \
    sudo \
    wget \
    git \
    zip  \
    unzip \
    gnupg


RUN apt-get install -y gnupg

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    pecl install xdebug-${XDEBUG_VERSION} && \
    docker-php-ext-enable xdebug \
;fi





### Composer

RUN curl --silent --fail --location --retry 3 --output /tmp/installer.php --url https://raw.githubusercontent.com/composer/getcomposer.org/cb19f2aa3aeaa2006c0cd69a7ef011eb31463067/web/installer \
 && php -r " \
    \$signature = '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5'; \
    \$hash = hash('sha384', file_get_contents('/tmp/installer.php')); \
    if (!hash_equals(\$signature, \$hash)) { \
      unlink('/tmp/installer.php'); \
      echo 'Integrity check failed, installer is either corrupt or worse.' . PHP_EOL; \
      exit(1); \
    }" \
 && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
 && composer --ansi --version --no-interaction \
 && rm -f /tmp/installer.php \
 && find /tmp -type d -exec chmod -v 1777 {} +



### Phive

RUN wget -O phive.phar https://phar.io/releases/phive.phar \
	&& wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc \
	&& gpg --keyserver keyserver.ubuntu.com --recv-keys 0x9D8A98B29B2D5D79 \
	&& gpg --verify phive.phar.asc phive.phar \
	&& chmod +x phive.phar \
	&& sudo mv phive.phar /usr/local/bin/phive







 ################################# NODE & NPM & NVM
# Add a non-root user to prevent files being created with root permissions on host machine.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

# always run apt update when start and after add new source list, then clean up at end.
RUN apt-get update --allow-releaseinfo-change -yqq && \
    groupadd -g ${PGID} developer && \
    groupadd docker_env && \
    useradd -u ${PUID} -g developer -m developer -G docker_env && \
    usermod -p "*" developer -s /bin/bash


# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

USER developer
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /usr/src/myapp
