# Changelog

Make sure to have a look at [UPDATING.md](Updating.md) to see any required steps for updating
major versions

## Devilbox v1.0

* Use Docker volumes for stateful data (MySQL, PgSQL, MongoDB)
    - This fixes various mount issues on Windows
    - This improves general performance
* Split Bind container into internal DNS and autoDNS
    - This fixes various issues with Docker Toolbox and DNS resolution
* Use semantic versioning
    - This allows for faster releases
    - This allows for better visibility of breaking changes
