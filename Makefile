db:
	docker-compose up -d db

build:
	docker-compose build --no-cache api

up:
	docker-compose run --service-ports api

sh:
	docker-compose run --service-ports api sh

down:
	docker-compose down

clean:
	docker rm /payor-example-php

destroy:
	docker rmi -f payor-example-php_api
	docker rmi -f payor-example-php_db
	docker rmi -f payor-example-php_velo
