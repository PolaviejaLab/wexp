wexp
====

Web application for running simple experiments.

Installation
------------

Install PHP5 MySQL driver.

    apt-get install php5-mysql

Set the **wexp/web** as the document root of your site. Then use composer to update the project. In case you forget this, an error concerning **bootstrap.php.cache** will occur.

    composer update

During the update process it will ask for the database credentials, mailer and rabbit setup. While the former is mandatory, the latter can be skipped at the moment.

After updating composer, setup the database schema and load the fixtures.

    php app/console doctrine:schema:drop --force
    php app/console doctrine:schema:update --force
    php app/console doctrine:fixtures:load -n


Development
-----------

When the schema has been updated, the entities should be updated using.

    php app/console generate:doctrine:entities --no-backup AppBundle
