#!/bin/sh

docker build -t crypto-tempus .
docker service rm crypto-tempus_tempus
docker stack deploy -c docker-compose.yml crypto-tempus