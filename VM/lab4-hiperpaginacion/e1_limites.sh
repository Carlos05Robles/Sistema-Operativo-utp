#!/bin/bash
echo "### Contexto: estoy DENTRO del contenedor"
docker exec reto2_app sh -c 'hostname'
docker exec reto2_app sh -c 'cat /proc/1/comm'
echo
echo "### Limites del cgroup (v1)"
docker exec reto2_app sh -c 'echo "  memory.limit_in_bytes       = $(( $(cat /sys/fs/cgroup/memory/memory.limit_in_bytes) / 1048576 )) MB"'
docker exec reto2_app sh -c 'echo "  memory.memsw.limit_in_bytes = $(( $(cat /sys/fs/cgroup/memory/memory.memsw.limit_in_bytes) / 1048576 )) MB (RAM + swap)"'
docker exec reto2_app sh -c 'echo "  swap real                   = $(( ($(cat /sys/fs/cgroup/memory/memory.memsw.limit_in_bytes) - $(cat /sys/fs/cgroup/memory/memory.limit_in_bytes)) / 1048576 )) MB"'
docker exec reto2_app sh -c 'echo "  cpu quota/period            = $(cat /sys/fs/cgroup/cpu/cpu.cfs_quota_us) / $(cat /sys/fs/cgroup/cpu/cpu.cfs_period_us)   <- 1 core"'
echo
echo "### Los mismos limites, segunda fuente (docker inspect)"
docker inspect reto2_app --format '  Memory={{.HostConfig.Memory}}  MemorySwap={{.HostConfig.MemorySwap}}  NanoCpus={{.HostConfig.NanoCpus}}'
echo
docker stats --no-stream --format 'table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}'
