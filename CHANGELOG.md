# Changelog

Make sure to have a look at [UPDATING](Updating.md) to see any required steps for updating
major versions.

## [Unreleasd]

### Added
- [CHANGELOG](CHANGELOG.md) by the standard of: https://keepachangelog.com
- [UPDATING](UPDATING.md) provides information how to update between major versions

### Changed
- Use Docker volumes instead of directory mounts for stateful data (MySQL, PgSQL and MongoDB)
    - This fixes various mount issues on Windows: #175 #382
    - This improves general performance
- Split Bind container into internal DNS and autoDNS: #248
    - This fixes various issues with Docker Toolbox and DNS resolution: #119
- Use semantic versioning
    - This allows for faster releases
    - This allows for better visibility of breaking changes
