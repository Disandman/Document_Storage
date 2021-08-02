include .env

install: docker-down-clear pre-install docker-up
up: docker-up

docker-down-clear:
	@docker-compose down -v --remove-orphans
pre-install:
	@docker-compose run --rm php composer update --prefer-dist && docker-compose run --rm php composer install   
docker-up: 
	@docker-compose up -d
docker-down: 
	@docker-compose down --remove-orphans
php-shell:
	@docker-compose exec php /bin/bash
db-shell:
	@docker-compose exec db /bin/bash