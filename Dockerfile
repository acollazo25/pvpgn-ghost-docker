### Dockerfile
FROM ubuntu:16.04

# Add sources
ADD ghostpp /ghostpp

# Install Dep.
RUN apt-get update
RUN apt-get install -y git libboost-all-dev build-essential libgmp-dev zlib1g-dev libbz2-dev

# Build
WORKDIR /ghostpp/bncsutil/src/bncsutil/
RUN make && make install
WORKDIR /ghostpp/StormLib/stormlib/
RUN make && make install
WORKDIR /ghostpp/ghost
RUN make

# Clean
RUN rm -rf /var/lib/apt/lists/* && rm -rf /src/*.deb

# Start
CMD [ "./ghost++","./config/ghost.cfg" ]