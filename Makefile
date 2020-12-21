socket:
	php artisan websockets:serve

update:
	php vendor/bin/composer update

push:
	git add .
	@read -p "Enter commit message:" MESSAGE; \
	git commit -m $$MESSAGE
	git push
	git status

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
	git reset --hard && git pull && make env
comp:
	php vendor/bin/composer install
up:
	make pull && make cl && make key && make qu
help:
	make help-doc && make help-model && make help-meta
help-doc:
	php artisan ide-helper:generate
help-model:
	php artisan ide-helper:models
help-meta:
	php artisan ide-helper:meta
key:
	php artisan key:generate
cl:
	php artisan config:clear && php artisan config:cache && php artisan cache:clear && php artisan optimize:clear && php artisan view:clear
type:
	php artisan love:reaction-type-add --default
reacter:
	php artisan love:setup-reacterable --model="App\Models\User" --nullable
reactant:
	php artisan love:setup-reactable --model="App\Models\Comment" --nullable

register-reacter:
	php artisan love:register-reacters --model="App\Models\User"
register-reactant:
	php artisan love:register-reactants --model="App\Models\Comment"

echo-socket:
	php -d memory_limit=-1 composer.phar require beyondcode/laravel-websockets
echo-pub:
	php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
echo-config:
	php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
ss:
	php artisan websocket:serve

compose:
	@read -p "Enter package name:  " MESSAGE; \
	php -d memory_limit=-1 composer.phar require $$MESSAGE

seed:
	make post && make user && make comment && make like && make design
post:
	php artisan db:seed --class=PostsTableSeeder
user:
	php artisan db:seed --class=UsersTableSeeder
comment:
	php artisan db:seed --class=UsersTableSeeder
like:
	php artisan db:seed --class=UsersTableSeeder
design:
	php artisan db:seed --class=DesignsTableSeeder
migrate:
	php srtisan migrate
comment:
	make reactant && make migrate && make register-reactant
vu:
	cd ../deja-vue && quasar dev
client:
	cd ../deja-vue && pstorm .


