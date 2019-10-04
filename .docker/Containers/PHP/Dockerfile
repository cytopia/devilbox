ARG VERSION
FROM devilbox/php-fpm:${VERSION}-work-0.89

RUN apt-get update && apt-get install -y \
    libvips \
    libvips-dev \
    libvips-tools \
    aptitude

RUN aptitude update && \
    aptitude install -y imagemagick && \
    sudo apt-get update && \
    sudo apt-get install -y mupdf mupdf-tools  && \
    sudo apt-get install -y zbar-tools libzbar-dev && \
    sudo apt install -y ffmpeg && sudo apt-get install -y beanstalkd