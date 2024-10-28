#!/bin/sh

docker build -t crypto-aesaas .
docker service rm crypto-aesaas_aesaas
docker stack deploy -c docker-compose.yml crypto-aesaas