# LAMP (Linux/Apache/MySQL/PHP)
This LAMP docker image is based on `mattrayner/lamp:0.8.0-2004-php8` image.
You have more detailed information at https://hub.docker.com/r/mattrayner/lamp/

This image has the following versions of the LAMP stack:
- Linux: Ubuntu 20.04
- Apache: 2.4.41
- MySQL: 8.0.26
- PHP: 8.0.10


# Build the image
First, you have to build the docker image. This process has to be done only once.
```
git clone https://www.sing-group.org/dt/gitlab/dgpena/lampserver
cd lampserver
docker build . -t lampserver
```

# Starting your web project
You need to create a folder where your web files and the database will reside.
```
# create a project directory
mkdir my-web-project
cd my-web-project
# create a subdirectory for web files
mkdir www
```
Inside `my-web-project/www` you place your web files.

# Running the server
Go to your web project directory (e.g., `my-web-project`) and run:

### On Linux (bash)
```
docker run -it -e APACHE_ROOT=www -e PHP_DISPLAY_ERRORS=On \
 -e DOCKER_USER_ID=`id -u \`whoami\`` -p "80:80" -v ${PWD}:/app \
 -v ${PWD}/mysql:/var/lib/mysql --name lampserver-1 --rm lampserver
```
### On Windows (powershell)
```
docker run -it -e APACHE_ROOT=www -e PHP_DISPLAY_ERRORS=On -p "80:80" -v ${PWD}:/app -v ${PWD}/mysql:/var/lib/mysql --name lampserver-1 --rm lampserver

```

`lampserver-1` is the name of the docker *container*, i.e., a running instance
of the `lampserver` image.

The first time you run the server in your directory, a MySQL data folder will
be created inside your project directory `./mysql`

A password for the `admin` user of MySQL is shown the first time.

Your server is available at: http://localhost
(if you want to use another port of your host machine, change `-p "80:80"`
by `-p "<another_port>:80"`)

# Using MySQL
1. You can use PHPMyAdmin by going to http://localhost/phpmyadmin. You have
to login with the `admin` user.
2. Alternatively, you can use the MySQL client for a running instance by issuing:
```
docker exec -it lampserver-1 mysql -uroot
```

# Backup a database
You can get an SQL dump of a given database in your running instance by issuing:
```
docker exec -it lampserver-1 mysqldump -uroot [yourdatabasename] > db.sql
```
