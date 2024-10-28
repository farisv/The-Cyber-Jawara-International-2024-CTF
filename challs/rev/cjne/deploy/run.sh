#!/bin/sh

docker build -t rev-cjne ../src/
docker service rm rev-cjne_cjne
docker stack deploy -c docker-compose.yml rev-cjne