<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Install
`make install`



## About Laravel

Pixelate (imaginary application name) is a real time app consisting if is a mix of an instagram clone with a commenting section.
Pixelate is the back-end build on Laravel
Deja-Vu is the frontend build in vue.js

This is my attempt to build a microservice  application based on laravel and vue js.
To try out this application, check the link below.
- [Pixelate front end app: Deja-Vu](https://dejavu.atmkng.de/#/).
- [Deja-Vu github repo](https://github.com/Atemndobs/deja-vue).

## Installation :
    - `make install`


The installation is scripted in the make file and covers following steps:
## Detailed Installation
    - Install app
- `composer install`
    - run Migrations and seed fake data
- `php artisan migrate:fresh --seed`
    - Setup Love Reacters and Reacterable (For Likes and Reactions)
- `make reacter && make reactant`
    - run Migrations
- `composer install`
    - Lunch api (application) by default on port 8090
- `php artisan serve`
    - Lunch api (application) by default on port 8090
- `php artisan serve`
    - Test Api endpoints using open api doc
- [api docs](http://localhost:8090/api/docs).
    - Connect and Test websocket 
- [web sockets](http://localhost:8090/laravel-websockets).

## Installation using Docker


## Features : 
    - Api for Posts (picture upload and manipuilation)
    - User managements
    - comments
    - Likes and reactions
    - Followers
    - Wather and Forecast
    - Tagging
    - Api documentation (swagger)
    - Music Analysing (TBD)
    - Video call and Streaming (TBD)

## Architecture : 
    - Microservices
    - Containerization : Docker using laravel sail
    - Websockets using laravel websockets


## Dependencies / Packages :

[laravel websockets](https://github.com/beyondcode/laravel-websockets).
[laravel sail](https://laravel.com/docs/8.x/sail).
[Eloquent-Taggable](https://github.com/cviebrock/eloquent-taggable).
[Laravel Love](https://github.com/cybercog/laravel-love).
[L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger).
[Laravel Responder](https://github.com/flugg/laravel-responder).
[Laravel MySQL Spatial extension](https://github.com/grimzy/laravel-mysql-spatial).
[Laravelista Comments](https://github.com/laravelista/comments).
[Laravel Excel](https://laravel-excel.com/).
[Laravel 5 Repositories](https://github.com/andersao/l5-repository).
[Spatie Image](https://spatie.be/docs/image/v1/introduction).
[Laravel Befriended](https://github.com/renoki-co/befriended).
[jwt-auth](https://github.com/tymondesigns/jwt-auth).
# dev - tools
[Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar).
[Laravel IDE Helper Generator](https://github.com/barryvdh/laravel-ide-helper).
[Laravel Telescope](https://laravel.com/docs/8.x/telescope).


## Todo :
    - Create Design Diagram
    - Break down features into apis:
        user api
        social api (comments, like and following),
        weather forecast api ,
        video call and streaming api
    - Intergrate the Music Api (for analysing music and returning track info like BPM, mood, energy ...)
    - implements OAuth2 and SSO inthe User api
