# Contributing


**Abstract**

The Devilbox is currently being developed in my spare time and mostly reflects the features that I
am using for all the web projects I have to handle. In order to better present it to the majority
of other software developers I do require support to cope with all the feature requests.


So first of all, If the Devilbox makes your life easier, **star it on GitHub**!

**Table of Contents**

1. [How to contribute](#how-to-contribute)
    1. [Documentation](#documentation)
    2. [Docker Container](#docker-container)
    3. [New Features](#new-features)
    4. [Intranet](#intranet)
    5. [Tests](#tests)
2. [Joining the Devilbox GitHub Organization](#joining-the-devilbox-github-organization)
3. [Important](#important)


## 1. How to contribute

There are various areas that need support. If you are willing to help, pick a topic below and start
contributing. If you are unclear about anything, let me know and I will clarify.

See the general [ROADMAP](https://github.com/cytopia/devilbox/issues/23) for what is planned.

### Documentation

**Required knowledge:** [Sphinx](http://www.sphinx-doc.org/en/stable/)

* General improvement of the documentation (typos, grammar, etc)
* Better documentation for setting up Xdebug
* More how to's on how to setup a specific framework or CMS
* General how to's and blog posts

### Docker Container

**Required knowledge:** Docker, [Ansible](https://www.ansible.com/), Apache, Nginx, MySQL, PHP-FPM

* Consolidate MySQL, PerconaDB and MariaDB into one repository for easier change management
* Consolidate Nginx and Apache into one repository for easier change management
* Performance improvements on Apache/Nginx and PHP-FPM
* Add new container to the stack

### New Features

**Required knowledge:** Various

Have a look at the GitHub issues and see if you can implement any features requested

### Intranet

**Required knowledge:** PHP, HTML, CSS and Javascript

* [ ] Fix email view: https://github.com/cytopia/devilbox/issues/337
* [ ] Better and more modern layout
* [ ] Try to remove as much vendor dependencies as possible

### Tests

**Required knowledge:** [Travis-CI](https://docs.travis-ci.com/)

* Shorten CI test time for faster releases
* Rewrite current tests, write new tests


## Joining the Devilbox GitHub Organization

If you want to contribute on a regular base and take care about major feature development you can
be invited to the GitHub organization.

This however requires some prerequisites:

1. Willing to dedicate a regular amount of time to invest in this project
2. Already spent a decent amount of time in improving the Devilbox
3. A good understanding about the Devilbox
4. A good understanding about the PHP-FPM container (and how it is built with Ansible)
