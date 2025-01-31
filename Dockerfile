ARG DOCKER_IMAGE
ARG TZ='Europe/Kiev'

FROM ${DOCKER_IMAGE} AS image
# Ubuntu
# Install base soft for allow apt-add-repository
RUN export DEBIAN_FRONTEND="noninteractive" \
    && apt-get update -qqy \
    && apt-get -qqy install software-properties-common apt-utils locales tzdata \
    && apt-get install -y --no-install-recommends libzip-dev unzip procps inotify-tools
#
# set UTC timezone
RUN echo ${TZ} > /etc/timezone \
    && rm -f /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && date
#

# set ENV lang
ENV LANG=ru_RU.UTF-8 \
	LC_ALL=ru_RU.UTF-8 \
	LANGUAGE=ru_RU.UTF-8 \
	COMPOSER_ALLOW_SUPERUSER=1 \
	COMPOSER_HOME=/tmp
#

# Install Curl
RUN apt-get update
#RUN export DEBIAN_FRONTEND="noninteractive" \
RUN apt-get install -qqy curl
