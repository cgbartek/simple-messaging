# Simple Messaging

By Chris Bartek

This is essentially a Slack clone I built on and off in the span of 48 hours, written in Laravel + Vue.

## Features:

* Real-time communication. People list is not updated in real-time. [MOSTLY DONE]
* Map reveals locations of all users [DONE]
* User can send messages to all users, some users, or selected users. [DONE]
* Message marked as read in interface. Marks in database but not in interface. [MOSTLY DONE]
* User can delete message or location history. [DONE]
* Database seeded with data. (See below for SQL) [DONE]

## Installation

Install the PHP dependencies using [composer](https://getcomposer.org/):

```
composer install
```

Install the NodeJS dependencies using [npm](https://docs.npmjs.com/cli-documentation/):

```
npm install
```

Copy `.env.example` to `.env`:

```
cp .env.example .env
```

Set the database variables in `.env` for your development environment:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Run the database migrations using [artisan](https://laravel.com/docs/5.8/artisan):

```
php artisan migrate
```

Start hot reloading for the interface (Vue, Vuetify):

```
npm run hot
```

Start serving the application if needed:

```
php artisan serve
```

Load the sample.db file into MySQL to seed the database.

Load the application URL in your browser:

```
http://localhost:8000
```

Logins to try:
chris@test.com
tom@test.com
sally@test.com
(all passwords are "password").

You can also create your own account.


## Technology Stack

This project was made using the following technologies:

* PHP 7.2
* [Laravel](https://laravel.com/docs/5.8)
* JavaScript ES6
* [Vue](https://vuejs.org/v2/guide/)
* [Vue Router](https://router.vuejs.org)
* [Vuetify](https://vuetifyjs.com/en/getting-started/quick-start)
* [Axios](https://github.com/axios/axios)
* [OpenLayers](https://openlayers.org)
