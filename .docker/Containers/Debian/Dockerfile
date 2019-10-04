ARG DEBIAN_VERSION
FROM debian:${DEBIAN_VERSION}

ARG NODE_VERSION

RUN apt-get update && apt-get install -y \
    openssh-server \
            sudo   \
            curl   \
            git

RUN curl -sL https://deb.nodesource.com/setup_${NODE_VERSION}.x | sudo bash - && \
    apt-get update && apt-get install -y nodejs && \
    curl -o- -L https://yarnpkg.com/install.sh | bash