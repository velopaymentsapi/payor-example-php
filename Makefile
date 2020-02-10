db:
	docker-compose up -d db

env:
ifdef clone
	# make clone=1 key=123 secret=shhh payor=testers env
	cp .env.example .env
	sed -i.bak 's/VELO_API_APIKEY=contact_velo_for_info/VELO_API_APIKEY=$(key)/' .env && rm .env.bak
	sed -i.bak 's/VELO_API_APISECRET=contact_velo_for_info/VELO_API_APISECRET=$(secret)/' .env && rm .env.bak
	sed -i.bak 's/VELO_API_PAYORID=contact_velo_for_info/VELO_API_PAYORID=$(payor)/' .env && rm .env.bak
endif
	- mv .env lumen/.env

network:
	- docker network create payorexample

build:
	docker-compose build --no-cache api

up: clean network
	docker-compose run -d --service-ports api

sh: clean network
	docker-compose run --service-ports api sh

down:
	docker-compose down

clean:
	- docker rm /payor-example-php
	- docker rm /db

destroy:
	- docker rmi -f payor-example-php_api

setdep:
	# make version=2.16.18 setdep
	sed -i.bak 's/"velopaymentsapi\/velo-php": ".*"/"velopaymentsapi\/velo-php": "${version}"/g' lumen/composer.json && rm lumen/composer.json.bak