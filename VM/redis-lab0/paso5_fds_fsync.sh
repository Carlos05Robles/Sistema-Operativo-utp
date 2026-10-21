#!/bin/bash
SVC=$(pgrep -of '[r]edis-server.*7777' 2>/dev/null || echo $$)

echo "### 1) Descriptores de archivo (FDs) del servicio, PID $SVC"
ls -l /proc/$SVC/fd/ 2>/dev/null | head -15

echo; echo "### 2) Limite de archivos abiertos del servicio y de mi shell"
grep "open files" /proc/$SVC/limits 2>/dev/null
echo -n "ulimit -n = "; ulimit -n

echo; echo "### 3) Clientes y maxclients de Redis"
redis-cli -p 7777 info clients 2>/dev/null | grep -E "connected_clients|maxclients" || echo "(sin redis-cli o sin instancia en 7777: maxclients por defecto 10000, limitado por ulimit)"

echo; echo "### 4) El offset vive en la open file description"
echo dato > /tmp/redis-lab0/demo.txt
exec 3<>/tmp/redis-lab0/demo.txt
echo -n "al abrir:          "; grep pos /proc/$$/fdinfo/3
dd bs=1 count=2 <&3 of=/dev/null status=none
echo -n "tras leer 2 bytes: "; grep pos /proc/$$/fdinfo/3
exec 3>&-

echo; echo "### 5) fsync: buffered vs durable"
python3 - <<'PY'
import os
p = '/tmp/redis-lab0/aof.sim'
f = open(p, 'a', buffering=1)
f.write('*2\r\n$3\r\nSET\r\n$1\r\nx\r\n')
print('escrito sin fsync: visible para lectores, NO durable ante caida')
f.flush(); os.fsync(f.fileno())
print('tras fsync: durable (equivale a appendfsync always en ese comando)')
PY
