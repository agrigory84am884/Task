#!/bin/sh
docker-compose -f docker-compose.yml up --build -d
docker exec -it messaging-app composer dump-autoload
docker exec -it messaging-app composer install --ignore-platform-reqs
sleep 10
docker exec -i messaging-mariadb mysql -u root -p123456 messaging_app < migration.sql