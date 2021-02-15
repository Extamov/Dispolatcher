# Dispolatcher

## Description

A system for police dispatchers based on WEB and backended by PHP on Apache web server.

The system contains 2 parts, 1 for a caller to call and one for the dispatcher to answer.

## Requirements

#### For the web server

- PHP 7+
- Apache 2+
- MySQL 5.4+
- Composer

## How to install and use

#### Running the server

1. Edit the file ``server-libs/db.php`` and set the credentials of the database.

2. Edit the file ``server-libs/mail.php`` and set the credentials for the mail system.

3. Go to the root folder of the project and install the Composer dependencies with the command ``composer install`` or ``php composer.phar install``.

4. Import the SQL file ``database.sql`` in ``server-libs`` into the database.

5. Run the Apache server.

6. Go to ``/register`` page to register the owner account(Can only be done once).

## Credits

This project was made by Extamov (Osher H).
