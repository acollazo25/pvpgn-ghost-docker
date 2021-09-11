# 🕶 PVPGN PRO ➕ GHOST++ ➕ WEB STATS

## Deployment (WINDOWS / LINUX / MAC)

> **ℹ️ NOTE:** The Ghost configuration is designed to work with the ***Warcraft 1.26x*** client, but you can adjust it to work with ***1.28x*** or higher. The default map is ***dota-6.83d-en.w3x***, but any other is possible.

### 📦 Export Pvpgn Data (LINUX / MAC)

```shell
mkdir "pvpgn"
docker run --rm -u root -v $PWD/pvpgn/var:/tmp/var wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/var/pvpgn /tmp/var
docker run --rm -u root -v $PWD/pvpgn/etc:/tmp/etc wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/etc/pvpgn /tmp/etc
```

### 📦 Export Pvpgn Data (WINDOWS)

```shell
mkdir "pvpgn"
docker run --rm -u root -v %CD%/pvpgn/var:/tmp/var wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/var/pvpgn /tmp/var
docker run --rm -u root -v %CD%/pvpgn/etc:/tmp/etc wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/etc/pvpgn /tmp/etc
```

### 🚩 Start services (*)

```shell
docker-compose up -d
```

### 🔀 Configure address translation for docker network (*)

1. List the IP assigned to your services.

```shell
docker network inspect pgpgn-server_default
```

```shell
[
...
    "Containers": {
        "247f8eda218b132456c66f87d593814a03050395dc5b918484d107815d9291ed": {
            "Name": "ghostpp_server",
            "IPv4Address": "192.168.128.3/20",
            ...
        },
        "9ea1220da14616d4d73c41217d20b6a8906af7b562c51b3e54272745158d530f": {
            "Name": "pvpgn_server",
            "IPv4Address": "192.168.128.2/20",
            ...
        }
    },
...
]
```

- `92.168.128.2`: **pvpgn-service-ip**
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
3. Restart ghost service `docker restart ghostpp`

### 🎮 Invite friends and play (*)

1. AYou and your friends can now add this battlenet server, create an account, and join the self-created game.

### 🕹 Commands (*)

1. To see the list of available commands visit [Ghost++ Commands](https://wiki.eurobattle.net/index.php/Ghost++:Commands)

### 🎉 Acknowledgements
-   [🙌 Pvpgn Docker Repo](https://github.com/wwmoraes/pvpgn-server-docker)
-   [🙌 Ghost++ Docker Repo](https://github.com/Fatorin/ghostpp_docker)
-   [🙌 Pvpgn Official Page](https://pvpgn.pro/)
-   [🙌 Ghost++ Stable Repo](https://github.com/uakfdotb/ghostpp)

### 📊 Stats
In process...