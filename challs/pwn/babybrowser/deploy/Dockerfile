FROM ubuntu:24.04

RUN apt update
RUN apt-get install -y socat unzip python-is-python3 adduser

RUN adduser pwn
RUN adduser pwned

WORKDIR /home/pwn
USER pwn

COPY ./flag ./flag
COPY ./flag_reader ./flag_reader

COPY ./run.sh ./run.sh
COPY ./runner.py ./runner.py
COPY ./d8 ./d8
COPY ./snapshot_blob.bin ./snapshot_blob.bin

USER root
RUN chown pwned:pwned flag
RUN chown pwned:pwn flag_reader

RUN chmod 400 ./flag 
RUN chmod 4550 ./flag_reader
RUN chmod 755 ./run.sh
RUN chmod 644 ./runner.py

RUN mv ./flag ./flag-$(md5sum flag | awk '{print $1}')
RUN mv ./flag_reader ./flag_reader-$(md5sum flag_reader | awk '{print $1}')

USER pwn

EXPOSE 8080
CMD socat TCP-LISTEN:8080,reuseaddr,fork EXEC:./run.sh,stderr