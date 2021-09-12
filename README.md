# 🕶 PVPGN PRO ➕ GHOST++ ➕ WEB STATS

![Status](https://img.shields.io/badge/status-active-success.svg)
[![GitHub Issues](https://img.shields.io/github/issues/acollazo25/pvpgn-ghost-docker.svg)](https://github.com/acollazo25/pvpgn-ghost-docker/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/wwmoraes/pvpgn-server-docker.svg)](https://github.com/acollazo25/pvpgn-ghost-docker/pulls)
---

## Deployment (WINDOWS / LINUX / MAC)

> **ℹ️ NOTE:** The Ghost configuration is designed to work with the ***Warcraft 1.26x*** client, but you can adjust it to work with ***1.28x*** or higher. The default map is ***dota-6.83d-en.w3x***, but any other is possible.

### 🛠 Requirements
1. [Docker](https://www.docker.com/products/docker-desktop)
2. [Docker Compose](https://docs.docker.com/compose/install/)

### ⬇️ Clone repo (*)

```shell
git clone https://github.com/acollazo25/pvpgn-ghost-docker.git
cd pvpgn-ghost-docker
```

### 📦 Export pvpgn data (LINUX / MAC)

```shell
mkdir "pvpgn"
docker run --rm -u root -v $PWD/pvpgn/var:/tmp/var wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/var/pvpgn /tmp/var
docker run --rm -u root -v $PWD/pvpgn/etc:/tmp/etc wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/etc/pvpgn /tmp/etc
```

### 📦 Export pvpgn data (WINDOWS)

```shell
mkdir "pvpgn"
docker run --rm -u root -v %CD%/pvpgn/var:/tmp/var wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/var/pvpgn /tmp/var
docker run --rm -u root -v %CD%/pvpgn/etc:/tmp/etc wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/etc/pvpgn /tmp/etc
```

### ⚙ Copy default config
1. Copy `.env.example` to `.env`.  Configure the `.env` for the [ssl termination](https://github.com/evertramos/nginx-proxy-automation) of the statistics website, otherwise you can ignore it and continue with the next step.
> Even if SSL termination is not configured the `.env` file **must exist** in the root of the directory.
```shell
cp .env.example .env
```
⚠ If SSL termination is not configured you must create a default proxy network.
```shell
docker network create proxy
```

### 🚚 Create Database Schemas and run seeders (*)
1. Seed database.
```shell
docker-compose up -d ghostpp_db
docker exec -i ghostpp_databse mysql -ughost -psecret ghost < ghostpp/db-schema.sql
docker exec -i ghostpp_databse mysql -ughost -psecret ghost < ghostpp/db-populate.sql
docker exec -i ghostpp_databse mysql -uroot ghost < ghostpp/mysql-settings.sql
```

### 📊 Setup Stats (*)
1. Install mysql required extensions.
```shell
docker-compose up -d stats
docker-compose exec stats docker-php-ext-install mysql
docker-compose restart stats
```
2. Set stats page. Edit the file `pvpgn/etc/pvpgn/anongame_infos.conf` and set the following settings.
```shell
...
server_URL = http://<your-public-ip>:8081/
...
or
...
# SSL Configured
server_URL = https://your-stats-domain.com
...
```

### 🚩 Start pvpgn and ghostpp services (*)

```shell
docker-compose up -d pvpgn ghostpp
```

### 🔀 Configure address translation for docker network (*)

1. Get the IP assigned to your `ghostpp` service.

```shell
docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' ghostpp_server
```
```shell
'192.168.224.3'
```
- `92.168.128.3`: **ghostpp-service-ip**

2. In the first line of the `pvpgn/etc/pvpgn/address_translation.conf` file add the following.

```shell
<ghostpp-service-ip>:6113 <your-public-ip>:6113 NONE ANY
########################################################################################################
#------------------------------------------------------------------------------------------------------#
# Address Translation table                                                                            #
#----------------------------------------------------------------------------
#
...
```

3. Restart pvpgn service `docker-compose restart pvpgn`

### 🤖 Bot Account creation (*)

1. Add the gateway to your battlenet servers `<your-public-ip>`.
2. Open your Warcraft client, go to battlenet and create a bot account, example user `bot` password `secret`.
3. Login and put any email.

### ⚙️ Ghost Configuration (*)

1. Copy the `ghostpp/config/default.cfg` to `ghostpp/config/ghost.cfg`.
```shell
cp ghostpp/config/default.cfg ghostpp/config/ghost.cfg
```
2. Edit the file `ghostpp/config/ghost.cfg` and set the following settings. This is enough to start.
```shell
...
bnet_username = bot
bnet_password = secret
...
```
3. Restart ghost service `docker-compose restart ghostpp`

### 🎮 Invite friends and play (*)

1. You and your friends can now add this battlenet server, create an account, and join the self-created game.

### 👮‍♂️ Adding root admins

1. Edit the file `ghostpp/config/ghost.cfg` and set the following settings.
```shell
...
bnet_rootadmin = yourAccount friendAccount otherFriend
...
```
2. Restart ghost service `docker-compose restart ghostpp`

### 🕹 Commands (*)

1. To see the list of available commands visit [Ghost++ Commands](https://wiki.eurobattle.net/index.php/Ghost++:Commands)

### 📄 View Logs
#### Pvpgn Logs
```shell
tail -f 50 pvpgn/var/pvpgn/bnetd.log
```
#### Ghost++ Logs
```shell
docker-compose logs -f --tail 50 ghostpp
```
#### Stats Logs
```shell
docker-compose logs -f --tail 50 stats
```
#### Database Logs
```shell
docker-compose logs -f --tail 50 ghostpp_db
```

### 🎉 Acknowledgements
-   [🙌 Pvpgn Official Page](https://pvpgn.pro/)
-   [🙌 Pvpgn Stable Repo](https://github.com/pvpgn/pvpgn-server)
-   [🙌 Pvpgn Docker Repo](https://github.com/wwmoraes/pvpgn-server-docker)
-   [🙌 Ghost++ Stable Repo](https://github.com/uakfdotb/ghostpp)
-   [🙌 Ghost++ Docker Repo](https://github.com/Fatorin/ghostpp_docker)
