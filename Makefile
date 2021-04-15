###### . PROD install #########
install-prod:
	make env-prod
	make echo
	/bin/bash db_prod.sh
	php composer.phar install --ignore-platform-reqs
	make key
	php artisan migrate:fresh --seed
	make types-setup
	php artisan storage:link > /dev/null &
	php artisan serve --port=8090 > /dev/null &
	/Applications/Google\ Chrome.app/Contents/MacOS/Google\ chrome 'http://localhost:8090'  > /dev/null &
	x-www-browser http://localhost:8090  > /dev/null &
	php artisan websockets:serve


###### . local install #########
install:
	make env
	make echo
	/bin/bash db.sh
	composer install
	make key
	php artisan migrate:fresh --seed
	make types-setup
	php artisan storage:link > /dev/null &
	php artisan serve --port=8090 > /dev/null &
	/Applications/Google\ Chrome.app/Contents/MacOS/Google\ chrome 'http://localhost:8090'  > /dev/null &
	x-www-browser http://localhost:8090  > /dev/null &
	php artisan websockets:serve

###### . Docker  install #########
install-docker:
	make env-docker
	make echo
	./vendor/bin/sail build && ./vendor/bin/sail up -d
	./vendor/bin/sail artisan key:generate
	./vendor/bin/sail artisan migrate:fresh --seed
	make sail-types
	./vendor/bin/sail artisan storage:link
	/Applications/Google\ Chrome.app/Contents/MacOS/Google\ chrome 'http://localhost:8090'  > /dev/null &
	x-www-browser http://localhost:8090  > /dev/null &
	./vendor/bin/sail artisan websockets:serve

stop:
	./vendor/bin/sail down

####### local Commands ##########
key:
	php artisan key:generate
socket:
	php artisan websockets:serve
test:
	sail shell &&  vendor/bin/phpunit tests --exclude-group skip-test --testdox --colors=always
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

seed:
	make post && make user && make comment && make like
post:
	php artisan reset:table posts
	php artisan reset:table users
	php artisan reset:table comments
	php artisan clear:assets public
	php artisan db:seed --class=AtemTableSeeder
	php artisan db:seed --class=DefaultUserTableSeeder
	php artisan db:seed --class=PostTableSeeder
	php artisan db:seed --class=PostsTableSeeder
user:
	php artisan db:seed --class=UsersTableSeeder
comment-seed:
	php artisan db:seed --class=CommentssTableSeeder
migrate:
	php artisan migrate

types-setup:
	php artisan reset:table love_reaction_types
	php artisan reaction Like,Laugh,Happy,Surprise,Smile
	php artisan love:register-reactants --model="App\Models\Post"
	php artisan love:register-reactants --model="App\Models\Comment"
	php artisan love:register-reacters --model="App\Models\User"
#### or ####
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
types-migration:
	php artisan love:setup-reacterable --model="App\Models\User" --nullable
	php artisan love:setup-reactable --model="App\Models\Post" --nullable
	php artisan love:setup-reactable --model="App\Models\Comment" --nullable

### Docker Commands #######

sail-key:
	php artisan key:generate

build:
	./vendor/bin/sail build --no-cache && ./vendor/bin/sail up

sail-types:
	./vendor/bin/sail artisan reset:table love_reaction_types
	./vendor/bin/sail artisan reaction Like,Laugh,Happy,Surprise,Smile
	./vendor/bin/sail artisan love:register-reactants --model="App\Models\Post"
	./vendor/bin/sail artisan love:register-reactants --model="App\Models\Comment"
	./vendor/bin/sail artisan love:register-reacters --model="App\Models\User"

sail:
	./vendor/bin/sail up
sail-soc:
	./vendor/bin/sail artisan websockets:serve


########## Git ##############

push:
	git add .
	@read -p "Enter commit message:" MESSAGE; \
	git commit -m $$MESSAGE
	git push
	git status

########## Useful commands ##############
clc:
	php artisan config:clear && php artisan config:cache && php artisan cache:clear && php artisan optimize:clear && composer dump-autoload && php artisan view:clear
wk:
	 ./vendor/bin/sail artisan schedule:work

link:
	php artisan storage:link

pusher:
	cp config/pusher.php config/broadcasting.php
echo:
	cp config/echo.php config/broadcasting.php
env:
	cp .env.example .env
env-docker:
	cp .env.docker .env
env-prod:
	cp .env.prod .env

help:
	make help-doc && make help-model && make help-meta
help-doc:
	php artisan ide-helper:generate
help-model:
	php artisan ide-helper:models
help-meta:
	php artisan ide-helper:meta

sail-types-migrations:
	./vendor/bin/sail artisan love:setup-reacterable --model="App\Models\User" --nullable
	./vendor/bin/sail artisan love:setup-reactable --model="App\Models\Post" --nullable
	./vendor/bin/sail love:setup-reactable --model="App\Models\Comment" --nullable
