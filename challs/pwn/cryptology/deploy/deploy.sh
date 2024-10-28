#!/bin/sh

docker build -t pwn-cryptology .
docker service rm pwn-cryptology_cyberjawara
docker stack deploy -c docker-compose.yml pwn-cryptology