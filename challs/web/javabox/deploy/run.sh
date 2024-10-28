#!/bin/sh

docker build -t web-javabox ../src/
docker service rm web-javabox_javabox
docker stack deploy -c docker-compose.yml web-javabox