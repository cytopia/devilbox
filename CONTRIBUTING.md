# Contributing

There is quite a lot todo and planned. If you like to contribute, pick any of the below topics or contact me directly.

Contributors will be credited within the intranet and on the github page.


## General

* [X] Handle emails sent from PHP dockers [#5](https://github.com/cytopia/devilbox/issues/5)
  1. Add another container which is able to display mails: https://github.com/sj26/mailcatcher
  2. Proxy this webinterface into the Intranet

## Dockers

* [ ] Add more travis checks to ensure they are always working under any condition
* [ ] Add more base dockers
  - [X] MySQL 5.6
  - [X] MySQL 5.7 [#1](https://github.com/cytopia/devilbox/issues/1)
  - [X] MariaDB 5.5 [#2](https://github.com/cytopia/devilbox/issues/2)
  - [X] MariaDB 10.0 [#3](https://github.com/cytopia/devilbox/issues/3)
  - [X] MariaDB 10.2 [#6](https://github.com/cytopia/devilbox/issues/6)
  - [X] MariaDB 10.2 [#7](https://github.com/cytopia/devilbox/issues/7)
  - [ ] PostgreSQL
  - [ ] LightHTTPD
  - [ ] HHVM
* [ ] Add optional dockers
  - [ ] Memcached
  - [ ] Redis
  - [ ] MongoDB
  - [ ] CouchDB
  - [ ] Cassandra
  - [ ] Apache Solr
  - [ ] Elasticsearch
* [ ] Add other stack dockers (instead of PHP)
  - [ ] Go
  - [ ] Python
  - [ ] Ruby
  - [ ] Perl


## Docker-compose

* [ ] Allow for optional dockers (via `setup.sh` script or similar)
  - via compose entrypoint overwrite


## Documentation

* [ ] Improve documentation
* [ ] Remove all typos / wrong grammar

## Intranet

* [X] View emails sent/received within PHP dockers
* [ ] Better layout
* [ ] Better logos
* [ ] Try to remove as much vendor dependencies as possible
