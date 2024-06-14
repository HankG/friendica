# Using the Friendica tests

## Install Tools

You need to install the following software:

* PHP
* MySQL or Mariadb (the latter is preferred)

For example in Ubuntu you can run:

```
sudo apt install mariadb-server php
```

## Install PHP extensions

The following extensions must be installed:

* MySQL
* Curl
* GD
* XML
* DOM
* SimpleXML
* Intl
* Multi-precision
* Multi-byte string

For example in Ubuntu:

```
sudo apt install php-mysql php-curl php-gd php-xml php-intl php-gmp php-mbstring
```

## Create Local Database

The default database name is `test`, username `friendica`, password
`friendica`.  These can be overridden using environment variables
`DATABASE_NAME`, `DATABASE_USER`, `DATABASE_HOST`, and
`DATABASE_PASSWORD`.  Whatever settings you choose, you must give the
corresponding user the necessary privileges to create and destroy the
chosen database.

```
GRANT ALL PRIVILEGES ON test.* TO 'friendica'@'localhost' IDENTIFIED BY 'friendica' WITH GRANT OPTION;
GRANT CREATE, DROP ON test.* TO 'friendica'@'localhost';
```

## Use Docker Database

Instead of using a local database, you can also use a database running in a docker container.

TODO this section needs to be filled in with working examples.

## Running Tests

You can then run the tests using the `autotest.sh` script.  You should
specify the type of database as an argument, either `mysql` or
`mariadb`:

```
bin/dev/autotest.sh mariadb
```

You can also run just one particular file of tests:

```
bin/dev/autotest.sh mariadb src/Util/ImagesTest.php
```

Example output of tests passing:

```
OK (2 tests, 2 assertions)
```

Failed tests look like this.  Examine the output before this to see which tests failed.

```
FAILURES!
Tests: 2, Assertions: 2, Failures: 1.
```
