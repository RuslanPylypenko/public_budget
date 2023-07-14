.ONESHELL: # Applies to every targets in the file!

UNAME := $(shell uname)

up:
	docker-compose up --force-recreate --no-deps -d $(c)

down:
	docker-compose down

destroy:
	docker-compose down -v --remove-orphans

build:
	docker-compose build $(c)

install:
	docker-compose exec backend php bin/console doctrine:fixtures:load