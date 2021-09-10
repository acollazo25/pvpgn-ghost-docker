# 🕶 PVPGN PRO ➕ GHOST++ ➕ WEB STATS

## Export Pvpgn Data (LINUX / MAC)
```bash
mkdir "pvpgn"
docker run --rm -u root -v $PWD/pvpgn/var:/tmp/var wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/var/pvpgn /tmp/var
docker run --rm -u root -v $PWD/pvpgn/etc:/tmp/etc wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/etc/pvpgn /tmp/etc
```

## Export Pvpgn Data (WINDOWS)
```bash
mkdir "pvpgn"
docker run --rm -u root -v %CD%/pvpgn/var:/tmp/var wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/var/pvpgn /tmp/var
docker run --rm -u root -v %CD%/pvpgn/etc:/tmp/etc wwmoraes/pvpgn-server cp -r /usr/local/pvpgn/etc/pvpgn /tmp/etc
```

## Start Server (Linux / MAC / WINDOWS)
```bash
docker-compose up -d
```

## Start Server (Linux / MAC / WINDOWS)
```bash
docker run --rm -u root -v $PWD/ghostpp:/tmp/ghostpp ender25/ghostpp cp -r /ghostpp  /tmp/ghostpp
```
