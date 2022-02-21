ifneq (,)
.error This Makefile requires GNU Make.
endif

# -------------------------------------------------------------------------------------------------
# Default configuration
# -------------------------------------------------------------------------------------------------
.PHONY: help build autobuild linkcheck linkcheck2


# -------------------------------------------------------------------------------------------------
# Default Target
# -------------------------------------------------------------------------------------------------

help:
	@echo "build            Build and test documentation"
	@echo "autobuild        Continuously run and build (http://0.0.0.0:8000)"
	@echo "linkcheck        Sphinx linkcheck"
	@echo "linkcheck2       Custom linkcheck"


# -------------------------------------------------------------------------------------------------
#  Target
# -------------------------------------------------------------------------------------------------

linkcheck2:
	docker run \
		--rm \
		$$(tty -s && echo "-it" || echo) \
		-v $(PWD):/data \
		cytopia/linkcheck -l -k -r 60 -t 30 -e rst -c '200,204,403' _includes/

linkcheck:
	docker run \
		--rm \
		$$(tty -s && echo "-it" || echo) \
		-e SPHINX_PROJECT="docs" \
		-e SPHINX_PORT=8000 \
		-e NEW_UID="$$(id -u)" \
		-e NEW_GID="$$(id -g)" \
		-v $(PWD)/..:/shared/httpd \
		devilbox/python-sphinx:3.8-dev \
		sphinx-build -M linkcheck . _build

build:
	docker run \
		--rm \
		$$(tty -s && echo "-it" || echo) \
		-e SPHINX_PROJECT="docs" \
		-e SPHINX_PORT=8000 \
		-e NEW_UID="$$(id -u)" \
		-e NEW_GID="$$(id -g)" \
		-v $(PWD)/..:/shared/httpd \
		devilbox/python-sphinx:3.8-dev \
		sphinx-build -a -E -n -j auto -W . _build/html

autobuild:
	docker run \
		--rm \
		$$(tty -s && echo "-it" || echo) \
		-e SPHINX_PROJECT="docs" \
		-e SPHINX_PORT=8000 \
		-e NEW_UID="$$(id -u)" \
		-e NEW_GID="$$(id -g)" \
		-p "8000:8000" \
		-v $(PWD)/..:/shared/httpd \
		devilbox/python-sphinx:3.8-dev \
		sphinx-autobuild -a -E -n -j auto -W --host 0.0.0.0 --port 8000 . _build/html
