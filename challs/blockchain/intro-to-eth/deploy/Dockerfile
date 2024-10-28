FROM gcr.io/paradigmxyz/ctf/eth-base:latest

COPY chal.py /home/ctf/chal.py

COPY contracts /tmp/contracts

RUN true \
    && cd /tmp \
    && forge build --out /home/ctf/compiled \
    && rm -rf /tmp/contracts \
    && true