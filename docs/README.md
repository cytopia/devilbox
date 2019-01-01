# Devilbox Documentation

The Devilbox documentation is build via [sphinx](http://www.sphinx-doc.org/en/master) and
automatically updated on [readthedocs](https://devilbox.readthedocs.io) by every git push.


## Documentation

The documentation is available online: https://devilbox.readthedocs.io

<a href="https://devilbox.readthedocs.io" title="Devilbox Documentation">
  <img style="width:200px;height:200px;" widh="200" height="200" title="Devilbox Documentation" name="Devilbox Documentation" src="https://raw.githubusercontent.com/cytopia/icons/master/400x400/readthedocs.png" />
</a>


## Local setup

You can also build the documentation locally before pushing to ensure everything looks fine:

#### Requirements

```
sudo pip install sphinx sphinx-autobuild recommonmark
sudo pip install sphinx_rtd_theme
```
#### How to build and error-check
```
cd docs/
make build
```

#### How to build continuously
```
cd docs/
make autobuild
```

#### How to view

When using `make autobuild` your documentation is served at: http://127.0.0.1:8000
