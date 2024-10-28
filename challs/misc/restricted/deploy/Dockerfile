FROM python:3.10-alpine

WORKDIR /app

RUN apk add socat --update --no-cache

COPY jail.py /app/run
RUN chmod +x /app/run
COPY RestrictedPython /app/RestrictedPython
COPY flag.txt /flag.txt

ENV PYTHONUNBUFFERED=0

RUN chmod 444 /flag.txt && mv /flag.txt /flag-$(head -c8 /dev/urandom | md5sum | tr -cd '[:alnum:]')
ENTRYPOINT socat -dd TCP4-LISTEN:10002,fork,reuseaddr EXEC:"./run",su=nobody
