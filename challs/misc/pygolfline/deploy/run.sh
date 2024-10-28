#!/bin/sh

docker build -t misc-pygolfline .
docker service rm misc-pygolfline_jail
docker stack deploy -c docker-compose.yml misc-pygolfline