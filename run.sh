#!/bin/sh

docker-compose rm -f

docker-compose up -d

docker-compose run --rm --no-deps php chmod -R 777 storage/

docker-compose run --rm --no-deps php chmod -R 777 bootstrap/cache/
