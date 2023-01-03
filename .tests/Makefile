ifneq (,)
.error This Makefile requires GNU Make.
endif


# -------------------------------------------------------------------------------------------------
# Misc Targets
# -------------------------------------------------------------------------------------------------
.PHONY: update-readme
update-readme: _update-readme-modules
update-readme: _update-readme-tools

.PHONY: _update-readme-modules
_update-readme-modules:
	cat "../README.md" \
		| perl -0 -pe "s#<!-- modules -->.*<!-- /modules -->#<!-- modules -->\n$$(./scripts/get-modules.sh)\n<!-- /modules -->#s" \
		> "../README.md.tmp"
	yes | mv -f "../README.md.tmp" "../README.md"

.PHONY: _update-readme-tools
_update-readme-tools:
	cat "../README.md" \
		| perl -0 -pe "s#<!-- tools -->.*<!-- /tools -->#<!-- tools -->\n$$(./scripts/get-tools.sh)\n<!-- /tools -->#s" \
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
		-c "find . -name '*.sh' -print0 | xargs -0 -n1 shellcheck --check-sourced --color=auto --exclude=SC1091 --shell=bash"


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
	@$(MAKE) --no-print-directory configure KEY=DEBUG_ENTRYPOINT VAL=3
	@$(MAKE) --no-print-directory configure KEY=NEW_UID VAL=$$(id -u)
	@$(MAKE) --no-print-directory configure KEY=NEW_GID VAL=$$(id -g)
	@$(PWD)/scripts/compose-start.sh


###
### Stop Devilbox
###
stop:
	@$(PWD)/scripts/compose-stop.sh


###
### Show Docker logs
###
logs:
	@$(PWD)/scripts/compose-logs.sh


# -------------------------------------------------------------------------------------------------
# Test Targets
# -------------------------------------------------------------------------------------------------

###
### PHP Modules
###
test-smoke-modules:
	$(PWD)/tests/modules-curl-vhosts.sh "modules"
	$(PWD)/tests/modules-php-vhosts.sh "modules"
	$(PWD)/tests/modules-fetch-tests.sh "modules-external"
	$(PWD)/tests/modules-curl-vhosts.sh "modules-external"
	$(PWD)/tests/modules-php-vhosts.sh "modules-external"


###
### Configuration
###
test-smoke-config:
	$(PWD)/tests/config-xdebug.sh


###
### Intranet
###
test-smoke-intranet:
	$(PWD)/tests/intranet-homepage.sh
	$(PWD)/tests/intranet-vhost.sh
	$(PWD)/tests/intranet-email.sh
	$(PWD)/tests/intranet-redis.sh
	$(PWD)/tests/intranet-memcached.sh


###
### Intranet Vendors
###
test-smoke-vendors:
	$(PWD)/tests/vendor-adminer-mysql.sh
	$(PWD)/tests/vendor-adminer-pgsql.sh
	$(PWD)/tests/vendor-adminer-mongo.sh
	$(PWD)/tests/vendor-phpmyadmin.sh
	$(PWD)/tests/vendor-phppgadmin.sh
	$(PWD)/tests/vendor-phpredmin.sh
	$(PWD)/tests/vendor-ocp.sh


###
### Virtual Host
###
test-smoke-vhosts:
	$(PWD)/tests/vhost-directory_index.sh
	$(PWD)/tests/vhost-vhostgen_default_template.sh
	$(PWD)/tests/vhost-vhostgen_customize.sh


###
### Reverse Proxy
###
test-smoke-rproxies:
	$(PWD)/tests/rproxy-javascript.sh


###
### SSL
###
test-smoke-ssl:
	$(PWD)/tests/ssl-intranet.sh
	$(PWD)/tests/ssl-vhost.sh


###
### Bind
###
test-smoke-bind:
	@echo noop


###
### Autostart
###
test-smoke-autostart:
	$(PWD)/tests/autostart-examples.sh


###
### Frameworks
###
test-smoke-framework-cakephp:
	$(PWD)/tests/framework-cakephp.sh
	$(PWD)/tests/framework-cakephp-php8.sh

test-smoke-framework-drupal:
	$(PWD)/tests/framework-drupal.sh

test-smoke-framework-wordpress:
	$(PWD)/tests/framework-wordpress.sh


###
### Container
###
test-smoke-container:
	$(PWD)/tests/container-mysql.sh


# -------------------------------------------------------------------------------------------------
# Helper Targets
# -------------------------------------------------------------------------------------------------

###
### Ensure .env file exists
###
../.env:
	install -m 0644 ../env-example ../.env
