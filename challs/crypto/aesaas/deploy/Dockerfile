FROM ubuntu:latest

RUN apt-get update && \
    apt-get install -y socat coreutils && \
    rm -rf /var/lib/apt/lists/*

RUN useradd -m tempuser

COPY chall /usr/local/bin/chall
COPY flag.txt .

RUN chmod +x /usr/local/bin/chall && \
    chown tempuser:tempuser /usr/local/bin/chall

USER tempuser

EXPOSE 18271

CMD ["sh", "-c", "socat -d -d TCP4-LISTEN:18271,reuseaddr,fork EXEC:'/usr/local/bin/chall',stderr,setsid,sigint"]