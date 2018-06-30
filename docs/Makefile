# Minimal makefile for Sphinx documentation
#

# You can set these variables from the command line.
SPHINXOPTS    =
SPHINXBUILD   = sphinx-build
SPHINXPROJ    = devilbox
SOURCEDIR     = .
BUILDDIR      = _build

# Put it first so that "make" without argument is like "make help".
help:
	@$(SPHINXBUILD) -M help "$(SOURCEDIR)" "$(BUILDDIR)" $(SPHINXOPTS) $(O)
	@echo
	@echo "Devilbox additional commands:"
	@echo "  build       Build and test documentation"
	@echo "  autobuild   Continuously run and build (http://127.0.0.1:8000)"

.PHONY: help Makefile test build autobuild

# Catch-all target: route all unknown targets to Sphinx using the new
# "make mode" option.  $(O) is meant as a shortcut for $(SPHINXOPTS).
%: Makefile
	@$(SPHINXBUILD) -M $@ "$(SOURCEDIR)" "$(BUILDDIR)" $(SPHINXOPTS) $(O)


#
# Devilbox additions
#
#
# Used Sphinx options:
# --------------------
#
# -a: Always write all output files. The default is to only write output files
#     for new and changed source files.
#
# -E: Don’t use a saved environment (the structure caching all cross-references),
#     but rebuild it completely.
#
# -n: Run in nit-picky mode. Currently, this generates warnings for all
#     missing references. See the config value nitpick_ignore for a way to
#     exclude some references as “known missing”.
#
# -q: Do not output anything on standard output, only write warnings and errors
#     to standard error.
#
# -W: Turn warnings into errors. This means that the build stops at the first
#     warning and sphinx-build exits with exit status 1.



build:
	sphinx-build -a -E -n -j auto -q -W . _build/html

autobuild:
	sphinx-autobuild -E -n -j auto . _build/html
