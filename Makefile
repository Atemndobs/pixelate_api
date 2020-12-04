os_type := $(shell uname -s)

export USER_ID=$(shell id -u)
export GROUP_ID=$(shell id -g)

ifeq ($(os_type),Linux)
	export CURRENT_HOST_ADDRESS=172.17.0.1
endif
ifeq ($(os_type),Darwin)
	export CURRENT_HOST_ADDRESS=docker.for.mac.localhost
    zcat_expansion = .Z
endif


start:
	gnome-terminal -- sh -c "make serve"
	make send

all:
	make ticket
	make dev
	make mantis
	make lunch
	gnome-terminal -- sh -c "make serve"
	make send

serve:
	php artisan serve

update:
	php vendor/bin/composer update

push:
	git add .
	@read -p "Enter commit message:" MESSAGE; \
	git commit -m $$MESSAGE
	git push
	git status

ticket:
	python -m webbrowser "http://localhost:8000/tickets"

dev:
	python -m webbrowser "http://localhost:8000/dev"

mantis:
	python -m webbrowser "http://localhost:8000/mantis"

lunch:
	xdg-open "https://127.0.0.1:8000/"

test:
	php vendor/bin/phpunit --exclude-group skip-test -v --testdox
cron:
	crontab * * * * * /bin/zsh /home/ba/workdir/data/ticket-assistant/run.sh

analyse:
	php vendor/bin/phpstan.phar analyse --level=6 src

qu:
	php artisan queue:work &
horizon:
	php artisan horizon
restart-php-pfm:
	sudo service phpX.Y-fpm-sp restart
install:
	 /bin/bash /var/www/pixelate/install.sh
composer:
	 /bin/bash /var/www/pixelate/composer.sh
sql:
	mysql -uatemkeng_compixelate -ppixelate
env:
	cp .env.prod .env
pull:
	git pull && make env
comp:
	php vendor/bin/composer install
up:
	make pull && make install && make qu
