
#!/bin/bash

echo
echo "### 1) Cambio aplicado en caliente"
echo '$ docker update --memory 512m --memory-swap 512m reto2_app'
docker update --memory 512m --memory-swap 512m reto2_app

echo
echo "### 2) Limites nuevos verificados en cgroups v1"

docker exec reto2_app sh -c '
echo "  memory.limit_in_bytes = $(( $(cat /sys/fs/cgroup/memory/memory.limit_in_bytes) / 1048576 )) MiB"
echo "  memory.memsw.limit_in_bytes = $(( $(cat /sys/fs/cgroup/memory/memory.memsw.limit_in_bytes) / 1048576 )) MiB (RAM + swap)"
echo "  swap permitido = $(( ($(cat /sys/fs/cgroup/memory/memory.memsw.limit_in_bytes) - $(cat /sys/fs/cgroup/memory/memory.limit_in_bytes)) / 1048576 )) MiB"
echo "  CPU quota = $(cat /sys/fs/cgroup/cpu/cpu.cfs_quota_us)"
echo "  CPU period = $(cat /sys/fs/cgroup/cpu/cpu.cfs_period_us)"
'

echo
echo "### 3) Limites confirmados por Docker"
docker inspect reto2_app --format \
'  Memory={{.HostConfig.Memory}} MemorySwap={{.HostConfig.MemorySwap}} NanoCpus={{.HostConfig.NanoCpus}}'

echo
echo "### 4) Latencia recuperada: 5 llamadas"
for i in $(seq 1 5); do
  curl -s -o /dev/null \
    -w "  intento $i -> HTTP %{http_code} en %{time_total}s\n" \
    http://localhost:5002/api/v1/analytics
done

echo
echo "### 5) Memoria del proceso Python"
docker exec reto2_app sh -c \
'grep -E "VmRSS|VmSwap" /proc/1/status'

echo
echo "### 6) Consumo y limites actuales"
docker stats --no-stream --filter name=reto2_app

echo
echo "### 7) Contadores del cgroup"
docker exec reto2_app sh -c '
echo "memory.failcnt:"
cat /sys/fs/cgroup/memory/memory.failcnt
echo "memory.oom_control:"
cat /sys/fs/cgroup/memory/memory.oom_control
'

echo
echo "### 8) Estado del contenedor"
docker ps --filter name=reto2_app

