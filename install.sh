#!/bin/bash
cp .env.prod .env
php composer.phar install --ignore-platform-reqs
