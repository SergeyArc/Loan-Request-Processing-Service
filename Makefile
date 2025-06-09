include .env
export

up: docker-up
down: docker-down
restart: down up
init: docker-down docker-pull docker-build docker-up composer-install db-refresh
db-refresh: wait-db db-purge db-init
db-init: wait-db migrate

docker-up:
	docker compose up -d
docker-down:
	docker compose down --remove-orphans
docker-clear-volumes:
	docker compose down --remove-orphans -v
docker-pull:
	docker compose pull
docker-build:
	docker compose build


composer-install:
	docker compose run --rm php-fpm composer install
wait-db:
	until docker compose exec -T db pg_isready --timeout=0 --dbname=$(POSTGRES_DB) ; do sleep 1; done
migrate:
	docker compose run --rm php-fpm php yii migrate --interactive=0

db-purge:
	docker compose exec -T db psql -U $(POSTGRES_USER) -d postgres -c "DROP DATABASE IF EXISTS $(POSTGRES_DB);"
	docker compose exec -T db psql -U $(POSTGRES_USER) -d postgres -c "CREATE DATABASE $(POSTGRES_DB) OWNER $(POSTGRES_USER);"

run-tests:
	docker-compose run --rm php-fpm ./vendor/bin/phpunit tests/unit