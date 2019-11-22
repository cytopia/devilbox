ifneq (,)
.error This Makefile requires GNU Make.
endif

SHELL = /bin/sh


# -------------------------------------------------------------------------------------------------
# Misc Targets
# -------------------------------------------------------------------------------------------------
update-readme:
	cat "../README.md" \
		| perl -00 -pe "s/<!-- modules -->.*<!-- \/modules -->/<!-- modules -->\n$$(./scripts/get-modules.sh)\n<!-- \/modules -->/s" \
		> "../README.md.tmp"
	yes | mv -f "../README.md.tmp" "../README.md"


# -------------------------------------------------------------------------------------------------
# Linting Targets
# -------------------------------------------------------------------------------------------------

lint-tests:
	docker run --rm $(tty -s && echo "-it" || echo) \
		-v "$(PWD):/mnt" \
		--entrypoint=sh \
		koalaman/shellcheck-alpine:stable \
		-c "find . -name '*.sh' -print0 | xargs -0 -n1 shellcheck --check-sourced --color=auto --shell=bash"


# -------------------------------------------------------------------------------------------------
# Startup Targets
# -------------------------------------------------------------------------------------------------

###
### Configure .env file
###
configure: ../.env
ifeq ($(KEY),)
	$(error Target requires KEY to be set.)
endif
	@$(PWD)/scripts/env-setvar.sh "$(KEY)" "$(VAL)"


###
### Pull currently setup images
###
pull: ../.env
	@$(PWD)/scripts/compose-pull.sh


###
### Startup Devilbox
###
start: ../.env
	@$(MAKE) --no-print-directory configure KEY=HOST_PATH_HTTPD_DATADIR VAL=.tests/www
	@$(MAKE) --no-print-directory configure KEY=DEBUG_COMPOSE_ENTRYPOINT VAL=2
	@$(MAKE) --no-print-directory configure KEY=NEW_UID VAL=$$(id -u)
	@$(MAKE) --no-print-directory configure KEY=NEW_GID VAL=$$(id -g)
	@$(PWD)/scripts/compose-start.sh


###
### Show Docker logs
###
logs:
	@$(PWD)/scripts/compose-logs.sh


# -------------------------------------------------------------------------------------------------
# Test Targets
# -------------------------------------------------------------------------------------------------

###
###
###
test-smoke-modules:
	$(PWD)/tests/modules-curl-vhosts.sh "modules"
	$(PWD)/tests/modules-php-vhosts.sh "modules"
	$(PWD)/tests/modules-fetch-tests.sh "modules-external"
	$(PWD)/tests/modules-curl-vhosts.sh "modules-external"
	$(PWD)/tests/modules-php-vhosts.sh "modules-external"


###
###
###
test-smoke-config:
	$(PWD)/tests/config-xdebug.sh


###
###
###
test-smoke-intranet:
	$(PWD)/tests/intranet-homepage.sh
	$(PWD)/tests/intranet-vhost.sh
	$(PWD)/tests/intranet-email.sh
	$(PWD)/tests/intranet-redis.sh
	$(PWD)/tests/intranet-memcached.sh


###
###
###
test-smoke-vendors:
	$(PWD)/tests/vendor-adminer-mysql.sh
	$(PWD)/tests/vendor-adminer-pgsql.sh
	$(PWD)/tests/vendor-adminer-mongo.sh
	$(PWD)/tests/vendor-phpmyadmin.sh
	$(PWD)/tests/vendor-phppgadmin.sh
	$(PWD)/tests/vendor-phpredmin.sh


###
###
###
test-smoke-vhosts:
	$(PWD)/tests/vhost-directory_index.sh
	$(PWD)/tests/vhost-vhostgen_default_template.sh
	$(PWD)/tests/vhost-vhostgen_customize.sh


###
###
###
test-smoke-rproxies:
	$(PWD)/tests/rproxy-javascript.sh


###
###
###
test-smoke-ssl:
	$(PWD)/tests/ssl-intranet.sh
	$(PWD)/tests/ssl-vhost.sh


###
###
###
test-smoke-bind:
	@echo noop


###
###
###
test-smoke-autostart:
	$(PWD)/tests/autostart-examples.sh


###
###
###
test-smoke-framework-cakephp:
	$(PWD)/tests/framework-cakephp.sh

test-smoke-framework-drupal:
	$(PWD)/tests/framework-drupal.sh


# -------------------------------------------------------------------------------------------------
# Helper Targets
# -------------------------------------------------------------------------------------------------

###
### Ensure .env file exists
###
../.env:
	install -m 0644 ../env-example ../.env
