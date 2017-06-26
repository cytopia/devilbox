# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
[Update](Update.md) |
[Configure](Configure.md) |
[Run](Run.md) |
[Usage](Usage.md) |
OS |
[Backups](Backups.md) |
[Examples](Examples.md) |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Operating System differences

1. [Linux](#1-linux)
2. [Windows](#2-windows)
    1. [/etc/hosts](#21-etchosts)
    1. [/etc/resolv.conf](#22-etcresolvconf)
3. [OSX](#3-osx)

---

## 1. Linux

Documentation is written from a Linux point of view, so there will be no differences.


## 2. Windows

On Windows you will have to edit different files on the Docker host than on Linux or OSX.

#### 2.1 `/etc/hosts`

To set custom DNS entries on windows you will have to edit `C:\WINDOWS\system32\drivers\etc` instead.

#### 2.2 `/etc/resolv.conf`

In order to add a custom DNS resolver in Windows, you will have to use the GUI tools of your network interfaces.

Check out this blog entry for how to adjust it:

http://www.pc-freak.net/blog/configure-equivalent-linux-etcresolvconf-search-domaincom-ms-windows-dns-suffixes/


## 3. OSX

OSX behaves the same as Linux and will not have any differences from the documentation.
