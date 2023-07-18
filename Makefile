.ONESHELL: # Applies to every targets in the file!

UNAME := $(shell uname)

# Запустити проект
# тут також встановлення composer i нових міграцій бази даних автоматично
up:
	docker-compose up --force-recreate --no-deps -d

#Зупинити проект
down:
	docker-compose down

#Зупинити і видалити базу даних
destroy:
	docker-compose down -v --remove-orphans

#Білд, потрібно запускати, якщо треба перезібрати image
build:
	docker-compose build

#Наповнення бази тестовими даними
install:
	docker-compose exec api-php-cli php bin/console doctrine:fixtures:load