# Documentation

[Home](https://github.com/cytopia/devilbox) |
[Overview](README.md) |
[Configuration](Configuration.md) |
[Usage](Usage.md) |
Updating |
[Info](Info.md) |
[PHP Projects](PHP_Projects.md) |
[Emails](Emails.md) |
[Logs](Logs.md) |
[Intranet](Intranet.md) |
[FAQ](FAQ.md)

----

## Updating

### Tags vs Branches

The devilbox git repository offers two types of setups.

1. Git `tags` for stable release
2. The `master` branch with the latest features


### Git tags

Each new devilbox release is tagged in git and bound to tagged docker images.
Updating only involves to check out the latest tag and make sure to stop and remmove your containers prior starting them up again. Latest docker images will be pulled automatically as their docker tag number will also change between releases.

Additionally you will also have compare `env-example` against your current `.env` file and in case of differences apply them.

```shell
# Update source
$ git fetch --all
$ git checkout <tag>

# Stop and remove your container
$ docker-compose stop
$ docker-compose rm

# Check for config file changes
$ vimdiff .env env-example
```

Currently releases are not that frequent, so you might be better of with the `master` branch.

### Git master branch

The `master` branch does not use tagged docker images, but `latest`. So once you git pull you should also pull the latest docker images.


```shell
# Update source
$ git fetch --all
$ git pull origin master

# Stop and remove your container
$ docker-compose stop
$ docker-compose rm

# Pull latest docker images
$ docker-compose pull

# Check for config file changes
$ vimdiff .env env-example
```
