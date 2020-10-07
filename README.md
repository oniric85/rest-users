# REST Users Service

This repository contains a demo implementation of a simple `Users` microservice. It is based on
Symfony 5 PHP framework. It uses MySQL for persistence and RabbitMQ as a message bus.

## External services

This microservice communicates over HTTPS with (ipapi.co)[https://www.ipapi.co]. This service is used to geolocate the
calling IP on user creation. This service has rate limitation.

## Features

The microservice supports just 3 simple endpoints.

- User creation
- User update
- Users search

### User creation

Used to create a new `User`.

**URL** : `/users`

**Method** : `POST`

**Data constraints**

```json
{
    "email": "[valid email address]",
    "password": "[password in plain text, 8 characters minimum length]",
    "first_name": "[not empty first name]"
}
```

**Data example**

```json
{
    "email": "mario@example.com",
    "password": "password",
    "first_name": "Rossi"
}
```

#### Success Response

**Code** : `201 Created`

**Content example**

```json
{
    "id": "01c95056-5adb-4c35-a098-b78e8e95fbdf",
    "email": "mario@example.com",
    "first_name": "Rossi"
}
```

#### Error Response

**Condition** : If `password` is shorter than minimum length.

**Code** : `400 Bad Request`

**Content** :

```json
{
    "error": {
        "password": [
            "The password minimum length should be 8."
        ]
    }
}
```

### User update

Used to update an existing `User`.

**URL** : `/users/:id`

**URL Parameters** : `id=[uuid]` where `id` is the 36 characters UUID of the `User`.

**Method** : `PUT`

**Data constraints**

```json
{
    "email": "[valid email address]",
    "password": "[password in plain text, 8 characters minimum length]",
    "first_name": "[not empty first name]"
}
```

**Data example**

```json
{
    "email": "mario@example.com",
    "password": "password",
    "first_name": "Rossi"
}
```

**Data example** Partial data is allowed.

```json
{
    "email": "[valid email address]"
}
```

#### Success Response

**Code** : `200 OK`

**Content example**

```json
{
    "id": "01c95056-5adb-4c35-a098-b78e8e95fbdf",
    "email": "mario@example.com",
    "first_name": "Bianchi"
}
```

#### Error Response

**Condition** : `User` with the given id does not exist.

**Code** : `404 Not Found`

**Content** :

```json
{
    "error": "User not found."
}
```

#### Data ignored

Endpoint will ignore irrelevant and read-only data such as parameters that don't exist or `id`.

## Users search

Used to search `Users` respecting certain criteria. Currently, search by `email` and `first_name` is supported. If no
search filter is specified all users are returned. The endpoint only returns users that exactly match.

**URL** : `/users?first_name=:first_name&email=:email`

**URL Parameters** : `first_name=[string]` where `string` is any valid string (optional), `email=[string]` where `string`
is any valid string (optional). 

**Method** : `GET`

**Data constraints**

This endpoint does not any data payload.

#### Success Response

**Code** : `200 OK`

**Content example**

```json
[
    {
        "id": "174fd18e-781f-43c2-b074-a60384c3cab0",
        "email": "test1@example.com",
        "first_name": "Bianchi"
    },
    {
        "id": "1b2ac7aa-ec86-430e-95b1-2f2cbcec12a1",
        "email": "test2@example.com",
        "first_name": "Rossi"
    }
]
```

#### Error Response

There aren't error cases for this endpoint.

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

### Configuration parameters

You can customize the configuration parameters of the application by creating a `.env.local` file
in the project root directory. In this file you can override any environment variable used by the
application.