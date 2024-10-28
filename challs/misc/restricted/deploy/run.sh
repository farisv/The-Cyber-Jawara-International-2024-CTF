#!/bin/sh

docker build -t misc-restricted .
docker service rm misc-restricted_jail
docker stack deploy -c docker-compose.yml misc-restricted