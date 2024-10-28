FROM ubuntu:24.04 AS builder
ARG DEBIAN_FRONTEND=noninteractive
RUN apt update && apt install -y make git python3 ninja-build gcc pkg-config libglib2.0-dev
RUN git clone https://github.com/qemu/qemu /qemu
WORKDIR /qemu
RUN ./configure --disable-system --enable-linux-user --disable-bpf --static
RUN make -j$((`nproc`-1)) qemu-mips

FROM ubuntu:24.04
RUN apt update && apt install -y libc6-arm64-cross socat qemu-user qemu-user-static
RUN useradd -m ctf
WORKDIR /challenge

COPY --from=builder /qemu/build/qemu-mips qemu-mips
COPY sssh sssh
COPY flag flag
RUN chown -R ctf:ctf /challenge && chmod -R 750 /challenge && chmod 440 /challenge/flag && chmod 550 /challenge

RUN mv ./flag ./flag-$(md5sum flag | awk '{print $1}')

# USER ctf
ENTRYPOINT ["socat", "tcp-l:1337,reuseaddr,fork", "EXEC:./qemu-mips ./sssh"]
EXPOSE 1337