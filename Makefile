

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
	php vendor/bin/phpunit tests --exclude-group skip-test --testdox --colors=always

all-tests:
	./vendor/phpunit/phpunit/phpunit tests --debug  --colors=always -v --testdox


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

up:
	make pull && make cl && make key && make pusher && make qu
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
#like:
#php artisan love:reaction-type-add --default
reacter:
	php artisan love:setup-reacterable --model="App\Models\User" --nullable
reactant:
	php artisan love:setup-reactable --model="App\Models\Post" --nullable

register-reacter:
	php artisan love:register-reacters --model="App\Models\User"
register-reactant-comment:
	php artisan love:register-reactants --model="App\Models\Comment"
register-reactant-post:
	php artisan love:register-reactants --model="App\Models\Post"

echo-socket:
	php -d memory_limit=-1 composer.phar require beyondcode/laravel-websockets
echo-pub:
	php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
echo-config:
	php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"

compose:
	@read -p "Enter package name:  " MESSAGE; \
	php -d memory_limit=-1 composer.phar require $$MESSAGE

seed:
	make post && make user && make comment && make like && make design
post:
	php artisan reset:table posts
	php artisan reset:table users
	php artisan reset:table comments
	php artisan clear:assets public
	php artisan db:seed --class=AtemTableSeeder
	php artisan db:seed --class=DefaultUserTableSeeder
	php artisan db:seed --class=PostsTableSeeder
user:
	php artisan db:seed --class=UsersTableSeeder
comment-seed:
	php artisan db:seed --class=CommentssTableSeeder
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


types:
	php artisan love:reaction-type-add --default
	php artisan love:reaction-type-add  --mass=1 --name=Laugh
	php artisan love:reaction-type-add  --mass=-1 --name=DisLaugh
	php artisan love:reaction-type-add  --mass=1 --name=Happy
	php artisan love:reaction-type-add  --mass=-1 --name=DisHappy
	php artisan love:reaction-type-add  --mass=1 --name=Surprise
	php artisan love:reaction-type-add  --mass=-1 --name=DisSurprise
	php artisan love:reaction-type-add  --mass=1 --name=Smile
	php artisan love:reaction-type-add  --mass=-1 --name=DisSmile

res:
	@read -p "Enter database table:  " TABLE; \
	php artisan reset:table $$TABLE

start:
	php artisan serve &
	make socket &
	make vu &
	make client
link:
	php artisan storage:link

pusher:
	cp config/pusher.php config/broadcasting.php
echo:
	cp config/echo.php config/broadcasting.php
sail:
	./vendor/bin/sail up
sail-soc:
	./vendor/bin/sail artisan websockets:serve


types-sail:
	sail artisan love:reaction-type-add --default
	sail artisan love:reaction-type-add  --mass=1 --name=Laugh && sail artisan love:reaction-type-add  --mass=-1 --name=DisLaugh
	sail artisan love:reaction-type-add  --mass=1 --name=Happy && sail artisan love:reaction-type-add  --mass=-1 --name=DisHappy
	sail artisan love:reaction-type-add  --mass=1 --name=Surprise && sail artisan love:reaction-type-add  --mass=-1 --name=DisSurprise
	sail artisan love:reaction-type-add  --mass=1 --name=Smile && sail artisan love:reaction-type-add  --mass=-1 --name=DisSmile

sail-types:
	./vendor/bin/sail artisan reset:table love_reaction_types
	./vendor/bin/sail artisan reaction Like,Laugh,Happy,Surprise,Smile
	./vendor/bin/sail artisan love:register-reactants --model="App\Models\Post"
	./vendor/bin/sail artisan love:register-reactants --model="App\Models\Comment"
	./vendor/bin/sail artisan love:register-reacters --model="App\Models\User"



types-all:
	php artisan reset:table love_reaction_types
	php artisan reaction Like,Laugh,Happy,Surprise,Smile
	php artisan love:register-reactants --model="App\Models\Post"
	php artisan love:register-reactants --model="App\Models\Comment"
	php artisan love:register-reacters --model="App\Models\User"
#	php artisan love:setup-reacterable --model="App\Models\User" --nullable
#	php artisan love:setup-reactable --model="App\Models\Post" --nullable
#	php artisan love:setup-reactable --model="App\Models\Comment" --nullable





build:
	./vendor/bin/sail build --no-cache && ./vendor/bin/sail up
share:
	./vendor/bin/sail share --subdomain=pixelate

size:
	export COMPOSER_MEMORY_LIMIT=-1
com-up:
	php -d memory_limit=-1 /usr/local/bin/composer update

com-t:
	composer info laravel/passport -t
# soundstretch my_original_file.wav output_file.wav -tempo=+15 -pitch=-3
comp:
	php -d memory_limit=-1 /usr/local/bin/composer require


clc:
	php artisan config:clear && php artisan config:cache && php artisan cache:clear && php artisan optimize:clear && composer dump-autoload && php artisan view:clear

repo-install:
	composer require prettus/l5-repository && php artisan vendor:publish --provider "Prettus\Repository\Providers\RepositoryServiceProvider"
	#     \App\Providers\RepositoryServiceProvider::class
repo:
	@read -p "Enter Model name e.g User:  " MODEL; \
	php artisan make:repository $$MODEL

# php artisan make:entity Post
# php artisan make:repository "Blog\Post"
# php artisan make:repository "Blog\Post" --fillable="title,content"
# php artisan make:entity Cat --fillable="title:string,content:text" --rules="title=>required|min:2, content=>sometimes|min:10"
# Route::resource('cats', CatsController::class);
# php artisan make:criteria MyCriteria

responder:
	sail composer require flugger/laravel-responder && sail artisan vendor:publish --provider="Flugg\Responder\ResponderServiceProvider"
# config/app.php => Flugg\Responder\ResponderServiceProvider::class,
# aliases 'Responder' => Flugg\Responder\Facades\Responder::class,
#        'Transformation' => Flugg\Responder\Facades\Transformation::class,

