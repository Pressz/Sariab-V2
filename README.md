Project Sariab
===
Based on **Pressz** guideline

A hub which bloggers can share their content with target audience.

# Installation notes

## Install Apache-MySQL-PHP

```
apt install tasksel
tasksel install lamp-server
```

## Configure MySQL

```
mysql_secure_installation
```

## Check apache installation

```
cd /var/www/html
vi index.php
```
> `<?php phpinfo(); ?>`
```
wget http://localhost/index.php
```

## Check the php version

`php --version`

## Install php-gd for *image generator*

> If you get errors like: [php7:error] [client ::1:34758] PHP Fatal error:  Uncaught Error: Call to undefined function imagecreatetruecolor() in /var/www/html/Sariab-V2/image-generator.php:XX Stack trace: #0 {main}  thrown in /var/www/html/Sariab-V2/image-generator.php on line XX,

>> Note: you have to check `cat /var/log/apache2/error.log`

- php 7.2 `sudo apt-get install php7.2-gd`
- php 5 `apt-get update && apt-get -y install php5-gd`

## Create database

```
mysql> CREATE DATABASE `sariab_data`;
```

## Execute database query

From file `db.sql`

## Change config file and set yout cridentials

Change the lines:

file: `config.pass.php`

```
$_SERVER['PHP_AUTH_USER'] = "DATABASE_USER";
$_SERVER['PHP_AUTH_PW'] = "DATABASE_PASSWORD";
```

file: `config.php`

```
$servername = "SERVER_NAME_HERE"; // Server name her
$dbname = "DATABASE_NAME_HERE";
```

# **How to contribute on project :**

0- Install git from git-scm.com

1- Make a **Fork** of the repository

2- **Clone** the project on your machine

3- Change whatever you want

4- Commit your changes

5- **Pull** before push

6- Push

7- Make a **pull request**

8- Tell us your notes

9- Your commits will be merged after review by developers of master branch