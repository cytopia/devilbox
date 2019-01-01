# -------------------------------------------------------------------------------------------------
# Settings
# -------------------------------------------------------------------------------------------------

SHELL := /bin/bash

.PHONY: update-readme

# -------------------------------------------------------------------------------------------------
# Variables
# -------------------------------------------------------------------------------------------------

# Paths and directory names
CURRENT_PATH   = $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
DEVILBOX_PATH  = $(CURRENT_PATH)..
DEVILBOX_NAME  = $(shell cd $(DEVILBOX_PATH); basename $$(pwd))

# Docker
DOCKER_NETWORK = $(shell docker network ls --filter name='$(DEVILBOX_NAME)' --quiet)

# Devilbox test settings
PROJECTV = my-vhost
PROJECTR = my-rhost
VHOST    = $(PROJECTV).loc
RHOST    = $(PROJECTR).loc


# -------------------------------------------------------------------------------------------------
# Misc Targets
# -------------------------------------------------------------------------------------------------
update-readme:
	cat "../README.md" \
		| perl -00 -pe "s/<!-- modules -->.*<!-- \/modules -->/<!-- modules -->\n$$(./get-modules.sh)\n<!-- \/modules -->/s" \
		> "../README.md.tmp"
	yes | mv -f "../README.md.tmp" "../README.md"

# -------------------------------------------------------------------------------------------------
# Testing Targets
# -------------------------------------------------------------------------------------------------

###
### Initialize .env file and data dir
###
init:
	@echo "####################################################################################################"
	@echo "# INITIALIZING .env FILE"
	@echo "####################################################################################################"
	$(eval MY_UID := $(shell id -u))
	$(eval MY_GID := $(shell id -g))

	@# Ensure .env file exists
	install -m 0644 $(DEVILBOX_PATH)/env-example $(DEVILBOX_PATH)/.env
	@# Ensure project vhost-gen files exist (vhost)
	install -m 0644 $(DEVILBOX_PATH)/cfg/vhost-gen/apache22.yml-example-vhost $(CURRENT_PATH)/vhost-tests/$(PROJECTV)/.devilbox/apache22.yml
	install -m 0644 $(DEVILBOX_PATH)/cfg/vhost-gen/apache24.yml-example-vhost $(CURRENT_PATH)/vhost-tests/$(PROJECTV)/.devilbox/apache24.yml
	install -m 0644 $(DEVILBOX_PATH)/cfg/vhost-gen/nginx.yml-example-vhost $(CURRENT_PATH)/vhost-tests/$(PROJECTV)/.devilbox/nginx.yml
	@# Ensure project vhost-gen files exist (rhost)
	install -m 0644 $(DEVILBOX_PATH)/cfg/vhost-gen/apache22.yml-example-rproxy $(CURRENT_PATH)/vhost-tests/$(PROJECTR)/.devilbox/apache22.yml
	install -m 0644 $(DEVILBOX_PATH)/cfg/vhost-gen/apache24.yml-example-rproxy $(CURRENT_PATH)/vhost-tests/$(PROJECTR)/.devilbox/apache24.yml
	install -m 0644 $(DEVILBOX_PATH)/cfg/vhost-gen/nginx.yml-example-rproxy $(CURRENT_PATH)/vhost-tests/$(PROJECTR)/.devilbox/nginx.yml
	@# Set bind port
	echo "HOST_PORT_BIND=53" >> $(DEVILBOX_PATH)/.env
	@# Set data dir
	sed -i'' 's/^HOST_PATH_HTTPD_DATADIR=/#HOST_PATH_HTTPD_DATADIR=/g' $(DEVILBOX_PATH)/.env
	echo "HOST_PATH_HTTPD_DATADIR=.tests/vhost-tests" >> $(DEVILBOX_PATH)/.env
	@# Set uid/gid
	sed -i'' 's/^NEW_UID=.*/NEW_UID=$(MY_UID)/g' $(DEVILBOX_PATH)/.env
	sed -i'' 's/^NEW_GID=.*/NEW_GID=$(MY_GID)/g' $(DEVILBOX_PATH)/.env
	@echo


###
### Configure custom versions
###
configure:
ifdef SRV
ifdef VER
	sed -i'' 's/^$(SRV)/#$(SRV)/g' $(DEVILBOX_PATH)/.env
	echo "$(SRV)=$(VER)" >> $(DEVILBOX_PATH)/.env
endif
endif


###
### Start Devilbox
###
start: stop pull
	@echo "####################################################################################################"
	@echo "# STARTING CONTAINER"
	@echo "####################################################################################################"
	cd $(DEVILBOX_PATH) && docker-compose up -d
	@$(MAKE) _wait
	@echo


###
### Pulling latest container
###
pull:
	@echo "####################################################################################################"
	@echo "# PULLING LATEST CONTAINER"
	@echo "####################################################################################################"
	cd $(DEVILBOX_PATH) && until docker-compose pull; do sleep 1; done
	@echo
	@echo


###
### Stop Devilbox
###
stop:
	@echo "####################################################################################################"
	@echo "# STOPPING CONTAINER"
	@echo "####################################################################################################"
	cd $(DEVILBOX_PATH) && docker-compose stop  || true
	cd $(DEVILBOX_PATH) && docker-compose kill  || true
	cd $(DEVILBOX_PATH) && docker-compose rm -f || true
	@echo


###
### Get Info
###
info:
	@echo "####################################################################################################"
	@echo "# CONTAINER INFO"
	@echo "####################################################################################################"

	$(eval DOCKER  := $(shell docker --version))
	$(eval COMPOSE := $(shell docker-compose --version))

	$(eval BIND    := $(shell curl -sS localhost/index.php | grep -Ei 'bg-info.*Bind' | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))
	$(eval PHP     := $(shell curl -sS localhost/index.php | grep -Ei 'bg-info.*PHP'  | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))
	$(eval HTTPN   := $(shell curl -sS localhost/index.php | grep -Ei 'bg-info.*(nginx|apache)' | grep -Eio 'nginx|apache'))
	$(eval HTTPV   := $(shell curl -sS localhost/index.php | grep -Ei 'bg-info.*(nginx|apache)' | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))
	$(eval MYSQLN  := $(shell curl -sS localhost/index.php | grep -Ei 'bg-warn.*(mysql|maria|percona)' | grep -Eio 'mysql|maria|percona'))
	$(eval MYSQLV  := $(shell curl -sS localhost/index.php | grep -Ei 'bg-warn.*(mysql|maria|percona)' | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))
	$(eval PGSQL   := $(shell curl -sS localhost/index.php | grep -Ei 'bg-warn.*Postgre'   | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))
	$(eval REDIS   := $(shell curl -sS localhost/index.php | grep -Ei 'bg-dang.*Redis'     | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))
	$(eval MEMCD   := $(shell curl -sS localhost/index.php | grep -Ei 'bg-dang.*Memcached' | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))
	$(eval MONGO   := $(shell curl -sS localhost/index.php | grep -Ei 'bg-dang.*Mongo'     | grep -Eo '\(.+?\)' | sed -e 's/(//g' -e 's/)//g'))

	@echo "Docker:  $(DOCKER)"
	@echo "Compose: $(COMPOSE)"
	@echo "Bind:    $(BIND)"
	@echo "PHP:     $(PHP)"
	@echo "HTTPD:   $(HTTPN) $(HTTPV)"
	@echo "MYSQL:   $(MYSQLN) $(MYSQLV)"
	@echo "PGSQL:   $(PGSQL)"
	@echo "Redis:   $(REDIS)"
	@echo "Memcd:   $(MEMCD)"
	@echo "Mongo:   $(MONGO)"
	@echo


###
### Run tests
###
test: test-vhost test-rhost test-intra test-startup


test-vhost:
	@echo "####################################################################################################"
	@echo "# RUNNING TESTS: VHOST"
	@echo "####################################################################################################"
	@echo

	@# Required to test against apache-2.(2|4)
	$(eval HTTPD_SERVER := $(shell grep -E '^HTTPD_SERVER' $(DEVILBOX_PATH)/.env | sed 's/.*=//g'))

	@# Gather files to test
	$(eval PHP_FILES := $(notdir $(wildcard $(CURRENT_PATH)/vhost-tests/$(PROJECTV)/htdocs/*.php)))

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [vhost] Test curl HTTP from Docker host"
	@echo "# ----------------------------------------------------------------------------------------"
	@$(foreach file,\
		$(PHP_FILES), \
		printf "[TEST] curl -sS --header 'Host: $(VHOST)' http://localhost/$(file)"; \
		cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
		if ! curl -sS --header 'Host: $(VHOST)' http://localhost/$(file) | grep -qE '^OK$$'; then \
			printf "\r[FAIL] curl -sS --header 'Host: $(VHOST)' http://localhost/$(file)\n"; \
			curl -sS --header 'Host: $(VHOST)' http://localhost/$(file); \
			curl -sS -I --header 'Host: $(VHOST)' http://localhost/$(file); \
			cd $(CURRENT_PATH) && $(MAKE) _logs; \
			exit 1; \
		else \
			printf "\r[OK]   curl -sS --header 'Host: $(VHOST)' http://localhost/$(file)\n"; \
		fi; \
	)
	@echo

	@# Error: Hostname localhost provided via SNI and hostname vhost-tests.loc provided via HTTP are different
	@# @echo "# ----------------------------------------------------------------------------------------"
	@# @echo "# [vhost] Test curl HTTPS from Docker host"
	@# @echo "# ----------------------------------------------------------------------------------------"
	@# @$(foreach file,\
	@# 	$(PHP_FILES), \
	@# 	printf "[TEST] curl -k -sS --header 'Host: $(VHOST)' https://localhost/$(file)"; \
	@# 	cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
	@# 	if ! curl -k -sS --header 'Host: $(VHOST)' https://localhost/$(file) | grep -qE '^OK$$'; then \
	@# 		printf "\r[FAIL] curl -k -sS --header 'Host: $(VHOST)' https://localhost/$(file)\n"; \
	@# 		curl -k -sS --header 'Host: $(VHOST)' https://localhost/$(file); \
	@# 		curl -k -sS -I --header 'Host: $(VHOST)' https://localhost/$(file); \
	@# 		cd $(CURRENT_PATH) && $(MAKE) _logs; \
	@# 		exit 1; \
	@# 	else \
	@# 		printf "\r[OK]   curl -k -sS --header 'Host: $(VHOST)' https://localhost/$(file)\n"; \
	@# 	fi; \
	@# )
	@# @echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [vhost] Test curl HTTP from inside Docker container"
	@echo "# ----------------------------------------------------------------------------------------"
	@$(foreach file,\
		$(PHP_FILES), \
		printf "[TEST] docker-compose exec php curl -sS http://$(VHOST)/$(file)"; \
		cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
		cd $(DEVILBOX_PATH); \
		if ! docker-compose exec php curl -sS http://$(VHOST)/$(file) | grep -qE '^OK$$'; then \
			printf "\r[FAIL] docker-compose exec php curl -sS http://$(VHOST)/$(file)\n"; \
			cd $(DEVILBOX_PATH) && docker-compose exec php curl -sS http://$(VHOST)/$(file); \
			cd $(DEVILBOX_PATH) && docker-compose exec php curl -sS -I http://$(VHOST)/$(file); \
			cd $(CURRENT_PATH) && $(MAKE) _logs; \
			exit 1; \
		else \
			printf "\r[OK]   docker-compose exec php curl -sS http://$(VHOST)/$(file)\n"; \
		fi; \
	)
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [vhost] Test curl HTTPS from inside Docker container"
	@echo "# ----------------------------------------------------------------------------------------"
	@$(foreach file,\
		$(PHP_FILES), \
		printf "[TEST] docker-compose exec php curl -sS https://$(VHOST)/$(file)"; \
		cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
		cd $(DEVILBOX_PATH); \
		if ! docker-compose exec php curl -sS https://$(VHOST)/$(file) | grep -qE '^OK$$'; then \
			printf "\r[FAIL] docker-compose exec php curl -sS https://$(VHOST)/$(file)\n"; \
			cd $(DEVILBOX_PATH) && docker-compose exec php curl -sS https://$(VHOST)/$(file); \
			cd $(DEVILBOX_PATH) && docker-compose exec php curl -sS -I https://$(VHOST)/$(file); \
			cd $(CURRENT_PATH) && $(MAKE) _logs; \
			exit 1; \
		else \
			printf "\r[OK]   docker-compose exec php curl -sS https://$(VHOST)/$(file)\n"; \
		fi; \
	)
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [vhost] Test php-cli from inside Docker container"
	@echo "# ----------------------------------------------------------------------------------------"
	@$(foreach file,\
		$(PHP_FILES), \
		printf "[TEST] docker-compose exec php php /shared/httpd/$(PROJECTV)/htdocs/$(file)"; \
		cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
		cd $(DEVILBOX_PATH); \
		if ! docker-compose exec php php /shared/httpd/$(PROJECTV)/htdocs/$(file) | grep -q '^OK$$'; then \
			printf "\r[TEST] docker-compose exec php php /shared/httpd/$(PROJECTV)/htdocs/$(file)\n"; \
			cd $(DEVILBOX_PATH) && docker-composeexec php php /shared/httpd/$(PROJECTV)/htdocs/$(file); \
			cd $(CURRENT_PATH) && $(MAKE) _logs; \
			exit 1; \
		else \
			printf "\r[OK]   docker-compose exec php php /shared/httpd/$(PROJECTV)/htdocs/$(file)\n"; \
		fi; \
	)
	@echo


test-rhost:
	@echo "####################################################################################################"
	@echo "# RUNNING TESTS: RHOST"
	@echo "####################################################################################################"
	@echo

	@# Gather files to test
	$(eval SH_FILES := $(notdir $(wildcard $(CURRENT_PATH)/vhost-tests/$(PROJECTR)/tests/*.sh)))

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [rhost] Startup"
	@echo "# ----------------------------------------------------------------------------------------"
	@$(CURRENT_PATH)/vhost-tests/$(PROJECTR)/start-pm.sh

	@$(foreach file,\
		$(SH_FILES), \
		echo; \
		echo "# ----------------------------------------------------------------------------------------"; \
		echo "# [rhost] $(file)"; \
		echo "# ----------------------------------------------------------------------------------------"; \
		cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
		if ! $(CURRENT_PATH)/vhost-tests/$(PROJECTR)/tests/$(file); then \
			cd $(CURRENT_PATH) && $(MAKE) _logs; \
			exit 1; \
		fi \
	)
	@echo


test-intra:
	@echo "####################################################################################################"
	@echo "# RUNNING TESTS: INTRANET"
	@echo "####################################################################################################"
	@echo

	@# Gather files to test
	$(eval SH_FILES := $(notdir $(wildcard $(CURRENT_PATH)/intra-tests/*.sh)))

	@$(foreach file,\
		$(SH_FILES), \
		echo "# ----------------------------------------------------------------------------------------"; \
		echo "# [intra] $(file)"; \
		echo "# ----------------------------------------------------------------------------------------"; \
		cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
		if ! $(CURRENT_PATH)/intra-tests/$(file); then \
			cd $(CURRENT_PATH) && $(MAKE) _logs; \
			exit 1; \
		fi; \
		echo; \
	)
	@echo


test-startup:
	@echo "####################################################################################################"
	@echo "# RUNNING TESTS: STARTUP"
	@echo "####################################################################################################"
	@echo

	@# Gather files to test
	$(eval SH_FILES := $(notdir $(wildcard $(CURRENT_PATH)/startup-tests/*.sh)))

	@$(foreach file,\
		$(SH_FILES), \
		cd $(CURRENT_PATH) && $(MAKE) _clean-round >/dev/null; \
		if ! $(CURRENT_PATH)/startup-tests/$(file); then \
			cd $(CURRENT_PATH) && $(MAKE) _logs; \
			exit 1; \
		fi; \
		echo; \
	)
	@echo


# -------------------------------------------------------------------------------------------------
# Helper Targets
# -------------------------------------------------------------------------------------------------

# TODO: this is super slow and should better be replaced by using local files instead
_clean-round:
	$(eval HTTPD_SERVER := $(shell grep -E '^HTTPD_SERVER' $(DEVILBOX_PATH)/.env | sed 's/.*=//g'))
	$(eval PHP_SERVER   := $(shell grep -E '^PHP_SERVER' $(DEVILBOX_PATH)/.env | sed 's/.*=//g'))

	@> $(DEVILBOX_PATH)/mail/devilbox

	@> $(DEVILBOX_PATH)/log/php-fpm-$(PHP_SERVER)/php-fpm.access
	@> $(DEVILBOX_PATH)/log/php-fpm-$(PHP_SERVER)/php-fpm.error

	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/defaultlocalhost-access.log >/dev/null 2>&1 || true
	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/defaultlocalhost-error.log  >/dev/null 2>&1 || true
	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/defaultlocalhost_ssl-access.log >/dev/null 2>&1 || true
	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/defaultlocalhost_ssl-error.log  >/dev/null 2>&1 || true

	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/$(PROJECTV)-access.log >/dev/null 2>&1 || true
	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/$(PROJECTV)-error.log  >/dev/null 2>&1 || true
	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/$(PROJECTV)_ssl-access.log >/dev/null 2>&1 || true
	@#cd $(DEVILBOX_PATH) && docker-compose exec httpd dd if=/dev/null of=/var/log/$(HTTPD_SERVER)/$(PROJECTV)_ssl-error.log  >/dev/null 2>&1 || true


_logs:
	@echo "####################################################################################################"
	@echo "# SHOWING LOGS"
	@echo "####################################################################################################"
	@echo

	$(eval HTTPD_SERVER := $(shell grep -E '^HTTPD_SERVER' $(DEVILBOX_PATH)/.env | sed 's/.*=//g'))

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] docker-compose ps"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose ps;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [HTTPD] docker-compose logs httpd"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs httpd;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [PHP] docker-compose logs php"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs php;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [BIND] docker-compose logs bind"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs bind;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [MYSQL] docker-compose logs mysql"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs mysql;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [PGSQL] docker-compose logs pgsql"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs pgsql;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [REDIS] docker-compose logs redis"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs redis;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [MEMCD] docker-compose logs memcd"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs memcd;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [DOCKER] [MONGO] docker-compose logs mongo"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose logs mongo;
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [PHP] php-fpm.access"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec php cat /var/log/php/php-fpm.access || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [PHP] php-fpm.error"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec php cat /var/log/php/php-fpm.error || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] defaultlocalhost-access.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/defaultlocalhost-access.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] defaultlocalhost-error.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/defaultlocalhost-error.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] defaultlocalhost_ssl-access.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/defaultlocalhost_ssl-access.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] defaultlocalhost_ssl-error.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/defaultlocalhost_ssl-error.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTV)-access.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTV)-access.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTV)-error.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTV)-error.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTV)_ssl-access.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTV)_ssl-access.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTV)_ssl-error.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTV)_ssl-error.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTR)-access.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTR)-access.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTR)-error.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTR)-error.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTR)_ssl-access.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTR)_ssl-access.log || true
	@echo

	@echo "# ----------------------------------------------------------------------------------------"
	@echo "# [HTTPD] $(PROJECTR)_ssl-error.log"
	@echo "# ----------------------------------------------------------------------------------------"
	cd $(DEVILBOX_PATH) && docker-compose exec httpd cat /var/log/$(HTTPD_SERVER)/$(PROJECTR)_ssl-error.log || true
	@echo


_wait:
	@printf "Waiting for container to be ready ";
	@# [HTTPD] Test for HTTP Status 200
	@until curl -sS -o /dev/null -I -w "%{http_code}" localhost 2>/dev/null | grep -q '200'; do \
		printf "."; \
		sleep 1; \
	done;

	@# [PHP] Test for HTTP content
	@until curl -sS localhost 2>/dev/null | grep -q 'dvlbox-ok'; do \
		printf "."; \
		sleep 1; \
	done;

	@# Add 20 more seconds for other services (e.g.: db) to initialize properly
	@for i in $$(seq 1 20); do \
		printf "."; \
		sleep 1; \
	done;

	@# [MongoDB] Test for MongoDB connection
	@until cd $(DEVILBOX_PATH) && docker-compose exec php mongofiles --host=mongo list >/dev/null 2>&2; do \
		printf "."; \
		sleep 1; \
	done;

	@# [MYSQL] Test for MySQL connection
	@until cd $(DEVILBOX_PATH) && docker-compose exec php mysql --user=root --password='' --host=mysql -e 'show databases;' 2>&1 | grep -q mysql; do \
		printf "."; \
		sleep 1; \
	done;

	@# [PGSQL] Test for PGSQL connection
	@until cd $(DEVILBOX_PATH) && docker-compose exec php pg_isready --host=pgsql >/dev/null 2>&1; do \
		printf "."; \
		sleep 1; \
	done;

	@# Add 40 more seconds just to be sure
	@for i in $$(seq 1 40); do \
		printf "."; \
		sleep 1; \
	done;
	@printf "\n";
