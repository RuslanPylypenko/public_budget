init: docker-down-clear docker-pull docker-build docker-up api-init
up: docker-up
down: docker-down
restart: down up
lint: api-lint

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

api-init: api-composer-install

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-lint:
	docker-compose run --rm api-php-cli composer lint


install-data:
	docker-compose exec api-php-fpm php bin/console doctrine:fixtures:load --no-interaction

bash:
	docker-compose exec api-php-cli bash -it

test:
	docker-compose exec api-php-cli ./vendor/bin/phpunit

migrate:
	docker-compose exec -u www-data api-php-fpm bin/console doctrine:migrations:migrate --no-interaction
diff:
	docker-compose exec -u www-data api-php-fpm bin/console doctrine:migrations:diff --no-interaction
drop:
	docker-compose exec -u www-data api-php-fpm bin/console doctrine:schema:drop --force