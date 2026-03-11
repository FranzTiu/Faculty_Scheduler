setup:
	@make build
	@makeup
	@make composer-update
build:
	docker-compose build --no cache --force-rm 
stop:
	docker-compose stop
up:
	docker-compose up -d
composer-update:
	docker exec it-faculty-comlab-scheduler bash -c "composer update"
data: 
	docker exec it-faculty-comlab-scheduler bash -c "php artisan migrate"
	docker exec it-faculty-comlab-scheduler bash -c "php artisan db:seed"

