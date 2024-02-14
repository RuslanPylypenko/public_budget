up: docker-up
init: docker-down-clear api-clear docker-pull docker-build docker-up api-init api-fixtures
build: docker-build
down: docker-down

docker-up:
	docker-compose up -d

docker-build:
	docker-compose build

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/*'
	docker run --rm -v ${PWD}/storage:/storage -w /storage alpine sh -c 'rm -rf public/*'

api-init: api-permissions api-composer-install api-wait-db api-migrations

api-fixtures:
	docker-compose run --rm api-php-cli php bin/console doctrine:fixtures:load --no-interaction

api-test:
	docker-compose run --rm api-php-cli ./vendor/bin/phpunit

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var

api-migrations:
	docker-compose run --rm api-php-cli composer app do:mi:mi --no-interaction

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-mysql:3306 -t 30

bash:
	docker-compose exec api-php-cli bash -it