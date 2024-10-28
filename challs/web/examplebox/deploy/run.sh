#!/bin/sh

docker build -t web-examplebox ../src/
docker service rm web-examplebox_examplebox
docker stack deploy -c docker-compose.yml web-examplebox