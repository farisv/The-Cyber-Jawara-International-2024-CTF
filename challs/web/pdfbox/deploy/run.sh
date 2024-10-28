#!/bin/sh

docker build -t web-pdfbox ../src/
docker service rm web-pdfbox_pdfbox
docker stack deploy -c docker-compose.yml web-pdfbox