FROM mysql:latest

COPY init.sql /tmp/init.sql
COPY init.sh /docker-entrypoint-initdb.d/02-init.sh
RUN chmod +x /docker-entrypoint-initdb.d/02-init.sh