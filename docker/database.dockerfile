FROM mariadb:latest

LABEL vendor="sharedBookshelf"

ENV MYSQL_RANDOM_ROOT_PASSWORD=true

RUN apt-get update && apt-get install -y openssh-server openssh-client

RUN sed -i 's|^#PermitRootLogin.*|PermitRootLogin yes|g' /etc/ssh/sshd_config

RUN echo "root:admin" | chpasswd
