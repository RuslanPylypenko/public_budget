##################
# Variables
##################

DOCKER_COMPOSE = docker-compose --env-file ./api/.env
DOCKER_COMPOSE_PHP_FPM_EXEC = ${DOCKER_COMPOSE} exec -u www-data api-php-fpm

##################
# Docker compose
##################

up:
	docker-compose up --force-recreate --no-deps -d

down:
	docker-compose down

destroy:
	docker-compose down -v --remove-orphans

build:
	docker-compose build

init: destroy build up

##################
# App
##################


install:
	docker-compose exec api-php-fpm php bin/console doctrine:fixtures:load

bash:
	${DOCKER_COMPOSE} exec api-php-fpm bash


##################
# Database
##################

migrate:
	${DOCKER_COMPOSE} exec -u www-data api-php-fpm bin/console doctrine:migrations:migrate --no-interaction
diff:
	${DOCKER_COMPOSE} exec -u www-data api-php-fpm bin/console doctrine:migrations:diff --no-interaction
drop:
	${DOCKER_COMPOSE} exec -u www-data api-php-fpm bin/console doctrine:schema:drop --force