If you encounter a bug and something does not work, make sure you have done the following and check those boxes before submitting an issue - thank you!

- [ ] Pull latest dockers (e.g.: `docker pull cytopia/<used_docker>`) before running `docker-compose up`
- [ ] Specify used docker versions (php, web and database)
- [ ] Attach logs for php, mysql and webserver (found in `log/` directory)
- [ ] Start with debug mode and attach docker-compose output (`.env` setting `DEBUG_COMPOSE_ENTRYPOINT=1`)
- [ ] Never use different mysql|mariadb versions on the same `HOST_PATH_MYSQL_DATADIR` on existing database files. Different mysql|mariadb versions might upgrade/corrupt existing database files. If you have done that already, start with a different path of `HOST_PATH_MYSQL_DATADIR` (to an empty directory) and try again.

Please also specify the following info:

- [ ] Which operating system are you at (Linux, OSX or Windows)
- [ ] `docker version`
- [ ] `docker-compose version`
