FROM ubuntu:latest

RUN apt-get update && \
    apt-get install -y socat coreutils && \
    rm -rf /var/lib/apt/lists/*

RUN useradd -m tempuser

COPY stone /usr/local/bin/stone

RUN chmod +x /usr/local/bin/stone && \
    chown tempuser:tempuser /usr/local/bin/stone

USER tempuser

EXPOSE 10001

CMD ["sh", "-c", "socat -d -d TCP4-LISTEN:10001,reuseaddr,fork EXEC:'/usr/local/bin/stone',pty,stderr,setsid,sigint,sane"]