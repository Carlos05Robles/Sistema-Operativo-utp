
#!/bin/bash

echo
echo "### 1) Limites actuales"
docker inspect reto2_app --format \
'Memory={{.HostConfig.Memory}} MemorySwap={{.HostConfig.MemorySwap}} NanoCpus={{.HostConfig.NanoCpus}}'

echo
echo "### 2) Docker Stats"
docker stats --no-stream --filter name=reto2_app \
  --format 'table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}'

echo
echo "### 3) Memoria del proceso"
docker exec reto2_app sh -c \
'grep -E "VmRSS|VmSwap" /proc/1/status'

echo
echo "### 4) Estado"
docker ps --filter name=reto2_app

echo
echo "Para la evidencia visual, abre htop en otra terminal."

