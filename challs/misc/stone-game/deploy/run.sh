#!/bin/sh

docker build -t misc-stone .
docker service rm misc-stone_stone
docker stack deploy -c docker-compose.yml misc-stone