# REST Users Service

This repository contains a demo implementation of a simple `Users` microservice. It is based on
Symfony 5 PHP framework. It uses MySQL for persistence and RabbitMQ as a message bus.

## Development environment

The development environment is based on Docker Compose with the following containers:
- php (running PHP 7.4 FPM)
- www (running httpd 2.4)
- rabbit (running RabbitMQ 3.7)
- mysql (running MySQL 5.7)

### Run the application

First and foremost you need to spin up the Docker Compose cluster:

```
docker-compose up -d
```

After this you will need to install the Composer dependencies and create the database schemas for both
dev and test environments by running the following commands:

```
docker-compose exec -u www-data php composer install
docker-compose exec -u www-data php bin/console doctrine:database:create -e dev
docker-compose exec -u www-data php bin/console doctrine:database:create -e test
docker-compose exec -u www-data php bin/console doctrine:schema:create -e dev
docker-compose exec -u www-data php bin/console doctrine:schema:create -e test
```

At this point you should be able to reach the application through your browser. You can try
to open up the browser at the following URL: [http://localhost:8000](http://localhost:8000).

Note: The project directory is mounted inside the `php` and `www` containers at the `/var/www/html` location.

### Run the tests

To run the tests simply execute:

```
docker-compose exec -u www-data php vendor/bin/phpunit
```

### Automatically fix your code

To automatically fix your code using PHP CS Fixer run this command:

```
docker-compose exec -u www-data php vendor/bin/php-cs-fixer fix --allow-risky=yes
```

### Perform static code analysis

You can use PHPStan to scan your code for issues:

```
docker-compose exec -u www-data php vendor/bin/phpstan analyse
```