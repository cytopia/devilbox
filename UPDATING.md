# Updating

This document will hold all information on how to update between major versions.


## Update from `v0.x.y` to `v1.x.y`

#### Docker Volumes

**PR:** https://github.com/cytopia/devilbox/pull/383

This feature will move all data directories (MySQL, PostgreSQL, MongoDB and others) to Docker
volumes for best-practice and compatibility reasons on different operating systems.

Before updating to this release, you will need to manually trigger a backup of MySQL,
PostgreSQL and MongoDB to ensure that you have a copy of your data. By switching to this release
you will be unable to access your current data:

**Steps to update:**

1. Backup your data
2. Switch to the new release
3. Import your data

**Documentation:**

* [Backup Mysql](https://devilbox.readthedocs.io/en/latest/maintenance/backup-and-restore-mysql.html)
* [Backup PostgreSQL](https://devilbox.readthedocs.io/en/latest/maintenance/backup-and-restore-pgsql.html)
* [Backup MongoDB](https://devilbox.readthedocs.io/en/latest/maintenance/backup-and-restore-mongo.html)
