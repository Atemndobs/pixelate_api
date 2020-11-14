#!/bin/bash
cp .env.prod .env.test
php composer.phar install --ignore-platform-reqs
