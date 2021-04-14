## About Pixelate

Pixelate (imaginary application name) is a real time app consisting if is a mix of an instagram clone with a commenting section.
Pixelate is the back-end build on Laravel
Deja-Vu is the frontend build in vue.js

This is my attempt to build a microservice  application based on laravel and vue js.
To try out this application, check the link below.
- [Pixelate backend api](https://atemkeng.com/).
- [Pixelate frontend app: Deja-Vu](https://dejavu.atmkng.de/#/).
- [Deja-Vu github repo](https://github.com/Atemndobs/deja-vue).

To install, clone this repository, cd into it and from root folder, run the following commands

## Quick Installation Local:
    - `make install`

## Quick Installation Docker:
    - `make install-docker`


The installation is scripted in the make file and covers following steps:
## Detailed local Installation

   -  copy .env file
- `make env`
   -  edit .env file as follows
    
    - Database (change database settings if you wish, default settings below)
      DB_PORT=3306
      DB_DATABASE=pixelate
      DB_USERNAME=root
      DB_PASSWORD=root
    - Websocket (By default Laravel echo is already set up. If you want to use pusher please change the following)
        Pusher :
            PUSHER_APP_ID=YOUR_PUSHER_ID
      
            PUSHER_APP_KEY=YOUR_PUSHER_KEY
      
            PUSHER_APP_SECRET=YOUR_PUSHER_SECRET
      
            PUSHER_APP_CLUSTER=YOUR_PUSHER_CLUSTER  e.g (eu)
      
        Laravel Echo (default):
            PUSHER_APP_ID=local
      
            PUSHER_APP_KEY=local
      
            PUSHER_APP_SECRET=local
      
            PUSHER_APP_CLUSTER=local
      
    - copy broadcasting config for pusher (** Please do this ONLY if you use pusher)
- `cp config/pusher.php config/broadcasting.php`
   -  run composer install
- `composer install`
    - run Migrations and seed fake data
- `php artisan migrate:fresh --seed`
    - Setup Love Reacters and Reacterable (For Likes and Reactions)
- `make types-setup`
    - Lunch api (application) by default on port 8090
- `php artisan serve` 
    - Lunch web sockets (default on port 6001)
- `php artisan websockets:serve`
    - Link storage
- `php artisan storage:link`
  
    - Test Api endpoints using open api doc
- [api docs](http://localhost:8090/api/docs).
    - Connect and Test websocket 
- [web sockets](http://localhost:8090/laravel-websockets).


## Installation using Docker
Alternatively you can run the api as docker containers. This spins up the laravel sail docker containers and a cron container for the cronjobs
    - copy .env file and edit if needed
- `make env`
    - copy broadcasting config for pusher (** Please do this ONLY if you use pusher)
- `cp config/pusher.php config/broadcasting.php`
    -  run composer install
- `sail composer install`
    - run Migrations and seed fake data
- `sail artisan migrate:fresh --seed`
    - Setup Love Reacters and Reacterable (For Likes and Reactions)
- `make types-setup`
    - Lunch api (application) by default on port 8090
- `sail artisan serve`
    - Lunch web sockets (default on port 6001)
- `sail artisan websockets:serve`
    - Link storage
- `sail artisan storage:link`
    
- [api docs](http://localhost:8090/api/docs).
    - Connect and Test websocket
- [web sockets](http://localhost:8090/laravel-websockets).

## Features : 
    - Api for Posts (picture upload and manipuilation)
    - User managements
    - comments
    - Likes and reactions
    - Followers
    - Tagging
    - Wather and Forecast
    - Api documentation (swagger)
    - Music Analysing (TBD)
    - Video call and Streaming (TBD)

## Architecture : 
    - Microservices
    - Containerization : Docker using laravel sail
    - Websockets using laravel websockets


## Dependencies / Packages :

- [laravel websockets](https://github.com/beyondcode/laravel-websockets).
- [laravel sail](https://laravel.com/docs/8.x/sail).
- [Eloquent-Taggable](https://github.com/cviebrock/eloquent-taggable).
- [Laravel Love](https://github.com/cybercog/laravel-love).
- [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger).
- [Laravel Responder](https://github.com/flugg/laravel-responder).
- [Laravel MySQL Spatial extension](https://github.com/grimzy/laravel-mysql-spatial).
- [Laravelista Comments](https://github.com/laravelista/comments).
- [Laravel Excel](https://laravel-excel.com/).
- [Laravel 5 Repositories](https://github.com/andersao/l5-repository).
- [Spatie Image](https://spatie.be/docs/image/v1/introduction).
- [Laravel Befriended](https://github.com/renoki-co/befriended).
- [jwt-auth](https://github.com/tymondesigns/jwt-auth).

# dev - tools
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar).
- [Laravel IDE Helper Generator](https://github.com/barryvdh/laravel-ide-helper).
- [Laravel Telescope](https://laravel.com/docs/8.x/telescope).


## Todo :
    - Create Design Diagram
    - Break down features into apis:
        user api
        social api (comments, like and following),
        weather forecast api ,
        video call and streaming api
    - Intergrate the Music Api (for analysing music and returning track info like BPM, mood, energy ...)
    - implements OAuth2 and SSO in the User api
    - Host on cloud server (This will give me root access and this way I can run websockets and replace pusher)
