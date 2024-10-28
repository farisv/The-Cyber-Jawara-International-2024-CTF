#!/bin/sh

docker build -t crypto-chemistry-lab ../src/
docker service rm crypto-chemistry-lab_chemistry-lab
docker stack deploy -c docker-compose.yml crypto-chemistry-lab