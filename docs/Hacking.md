# Devilbox Documentation

**[Overview](README.md)** |
**Installing** |
**Updating** |
**Configuration** |
**[Usage](Usage.md)** |
**[Examples](Examples.md)** |
**[Technical](Technical.md)** |
**Hacking** |
**[FAQ](FAQ.md)**

---

## Hacking

1. [Adding your own docker container](#1-adding-your-own-docker-container)

---

### 1. Adding your own docker container

You can add your custom docker container including its configuration to `docker-compose.yml`.


#### 1.1 What information will you need?

1. A name that you can use to refer to in the docker-compose command
2. The image name
3. The image version or `latest`
4. An unused IP address from the devilbox network

#### 1.2 How to add your service?

1. Open `docker-compose.yml`
2. Paste the following snippet with your replaced values just below the `services:` line (with one level of indentation)

```yml
  <name>:
    image: <image-name>:<image-version>
    networks:
      app_net:
        ipv4_address: <unused-ip-address>
    # For ease of use always automatically start these:
    depends_on:
      - bind
      - php
      - httpd
```

#### 1.3 How to start your service?

```shell
# The following will bring up your service including all the
# dependent services (bind, php and httpd)
$ docker-compose up <name>
```
