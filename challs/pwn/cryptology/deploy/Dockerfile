FROM ubuntu@sha256:2e863c44b718727c860746568e1d54afd13b2fa71b160f5cd9058fc436217b30

RUN apt-get update -y && \
    apt-get install -y xinetd && \
    apt-get install libcrypto++-dev libcrypto++-doc libcrypto++-utils -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN useradd -m user

COPY cryptology /home/user/
COPY flag.txt /flag.txt
COPY run.sh /
COPY xinetd /etc/xinetd.d/user

WORKDIR /home/user

RUN chown -R root:root . && \
    chmod o+rwx . && \
    chmod 444 /flag.txt && \
    chmod a+x cryptology && \
    chmod +x /run.sh

RUN mv /flag.txt /flag-$(md5sum /flag.txt | awk '{print $1}')

EXPOSE 1337

CMD ["/usr/sbin/xinetd", "-dontfork"]

