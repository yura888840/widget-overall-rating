widget-overall-rating
=====================

Fork repository

After this, you should do first of all
```
    composer install
```

You will be asked for database connection settings.

By next step you should create database. Run from project folder
```
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
    php bin/console doctrine:database:import data/hotel_db.sql
```

To test the project simplest way is to run build-in web server. You
can run it by

```
    php bin/console server:start
```

So, you can test it in http://127.0.0.1:8000

Sample requests:

http://127.0.0.1:8000/widget/HOTEL_HASH_TEST.js

On web page just use :

<script src="http://127.0.0.1:8000/widget/HOTEL_HASH_TEST.js"></script>

To run unit tests
```
    ./vendor/phpunit/phpunit/phpunit
```

