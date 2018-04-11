build:
	docker-compose build --pull
	docker-compose pull
	docker run --rm --volume $$PWD:/app composer install -n

start:
	docker-compose up -d --force-recreate

stop:
	docker-compose rm -f
	docker-compose stop

restart: stop start

respawn: kill start

kill:
	docker-compose kill

rm:
	docker-compose rm -f

ps:
	docker-compose ps