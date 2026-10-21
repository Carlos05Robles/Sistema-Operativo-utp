#!/bin/bash
echo "### 1) Latencia de 3 llamadas a /api/v1/analytics"
for n in 1 2 3; do
  curl -s -o /dev/null -w "  llamada $n -> HTTP %{http_code} en %{time_total}s\n" \
    "http://localhost:5002/api/v1/analytics"
done

echo; echo "### 2) vmstat durante una llamada (si=swap in, so=swap out, wa=esperando I/O)"
vmstat 1 > /tmp/v.txt 2>&1 &
VMPID=$!
sleep 1
curl -s -o /dev/null "http://localhost:5002/api/v1/analytics"
sleep 1
kill $VMPID 2>/dev/null
tail -n +3 /tmp/v.txt

echo; echo "### 3) El proceso se reparte entre RAM y swap"
docker exec reto2_app sh -c 'grep -E "VmRSS|VmSwap" /proc/1/status'
docker exec reto2_app sh -c 'echo "  memory.usage_in_bytes = $(( $(cat /sys/fs/cgroup/memory/memory.usage_in_bytes) / 1048576 )) MB de $(( $(cat /sys/fs/cgroup/memory/memory.limit_in_bytes) / 1048576 )) MB"'
docker exec reto2_app sh -c 'echo "  swap usado por el cgroup = $(( ($(cat /sys/fs/cgroup/memory/memory.memsw.usage_in_bytes) - $(cat /sys/fs/cgroup/memory/memory.usage_in_bytes)) / 1048576 )) MB"'
echo "  Swap del host:"
free -m | grep -E "total|Swap"

echo; echo "### 4) El cgroup toco su tope, pero NADIE fue matado"
docker exec reto2_app sh -c 'echo "  memory.failcnt = $(cat /sys/fs/cgroup/memory/memory.failcnt)   <- veces que se topo el limite de RAM"'
docker exec reto2_app sh -c 'cat /sys/fs/cgroup/memory/memory.oom_control'
docker inspect reto2_app --format '  OOMKilled={{.State.OOMKilled}}  ExitCode={{.State.ExitCode}}'

echo; echo "### 5) El contenedor NO muere"
docker ps --filter name=reto2_app --format '  {{.Names}} -> {{.Status}}'
echo -n "  /health -> " && curl -s http://localhost:5002/health && echo
