# Devilbox Documentation

**[Overview](README.md)** |
**[Install](Install.md)** |
**[Update](Update.md)** |
**[Configure](Configure.md)** |
**[Run](Run.md)** |
**[Usage](Usage.md)** |
**[Backups](Backups.md)** |
**[Examples](Examples.md)** |
**[Technical](Technical.md)** |
**[Hacking](Hacking.md)** |
**FAQ**

---

## FAQ


**How do I backup my MySQL database?**

1. Use phpMyAdmin
2. Use Adminer
3. use `mysqldump` from your host computer
4. use `mysqldump` from within the php container

**Why are mounted MySQL data directories separated by version?**

This is just a pre-caution. Imagine they would link to the same datadir. You start the devilbox with mysql 5.5, create a database and add some data. Now you decide to switch to mysql 5.7 and restart the devilbox. The newer mysql version will probably upgrade the data leaving it unable to start with older mysql versions.

**Why are mounted PostgreSQL data directories separated by version?**

See: *Why are mounted MySQL data directories separated by version?*

**Why do the user/group permissions of log/ or cfg/ directories show 1000?**

Uid and Gid are set to 1000 by default. You can alter them to match the uid/gid of your current user. Open the `.env` file and change the sections `NEW_UID` and `NEW_GID`. When you start up the devilbox, the php-container will use these values for its user.

**Can I not just comment out the service in the `.env` file?**

No, don't do this. This will lead to unexpected behaviour (different versions will be loaded).
The `.env` file allows you to configure the devilbox, but not to start services selectively.

**Are there any required services that must/will always be started?**

Yes. `http` and `php` will automatically always be started (due to dependencies inside `docker-compose.yml`) if you specify them or not.

**What PHP Modules are available?**

**Can I add other PHP Modules?**

**Can I change php.ini?**

**Can I change my.cnf?**

**Can I switch HHVM between PHP 5.6 and PHP 7 mode?**
