# Updating

This document will hold all information on how to update between major versions.


## Update from `v2.4.0` to `v3.0.0` (`v3.0.0-beta-1`)

**PR:** https://github.com/cytopia/devilbox/pull/942

This PR introduces new `.env` variables, so you are required to copy `env-example` over to `.env`.
Also ensure to stop and remove containers.
```bash
docker-compose stop
docker-compose rm -f
```

## Update from `v1.8.1` to `v1.8.2`

**PR:** https://github.com/cytopia/devilbox/pull/750

This PR adds many new images, ensure to remove your current old state before running them:
```bash
docker-compose stop
docker-compose rm -f
```


## Update from `v1.8.0` to `v1.8.1`

**PR:** https://github.com/cytopia/devilbox/pull/747

This PR ensures to have all directories in log/ mod/ cfg/ added by default. Prior this, some
of those directories might have been created by Docker (running as root) and they have the wrong
permissions (root instead of local user).
To mitigate this, you need to adjust directory permissions prior pulling this tag.

```bash
sudo chown -R <user>:<group> .
```


## Update from `v1.6.2` to `v1.6.3`

**PR:** https://github.com/cytopia/devilbox/pull/689

The following affects you if you have a PostgreSQL root password set:

Ensure to diff `env-example` against `.env` as a new environment variable (`PGSQL_HOST_AUTH_METHOD`)
has been introduced. A default value has been set in `docker-compose.yml` to make migration seamless.


## Update from `v1.1.0` to `v1.2.0`

**PR:** https://github.com/cytopia/devilbox/pull/647

This release changes the way the SSL CA and certificates are generated.
Background here: https://support.apple.com/en-us/HT210176

In order to use the new CA, you will need to delete your current CA in `ca/devilbox-ca.*`.
A new one will be automatically generated if none is present. Additionally you will have to
import the CA again in your browser(s).


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
