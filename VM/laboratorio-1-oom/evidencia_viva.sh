#!/bin/bash
set -x

echo "=== 0. Reiniciar contenedor limpio ==="
docker rm -f reto1_app 2>/dev/null
docker run -d --name reto1_app --restart no -p 5001:5000 \
  --memory="256m" --memory-swap="256m" --cpus="1" app-reto1-oom:v1
sleep 2
curl -s http://localhost:5001/health
echo ""

echo "=== 1. docker inspect (limites configurados) ==="
docker inspect reto1_app --format '{{.HostConfig.Memory}} bytes memoria | {{.HostConfig.MemorySwap}} bytes memoria+swap | {{.HostConfig.NanoCpus}} nanocpus'

echo "=== 2. Estado ANTES de cargar memoria ==="
docker exec reto1_app sh -c 'cat /proc/1/status | grep -E "VmRSS|VmSize|VmSwap"'
docker exec reto1_app sh -c 'cat /proc/1/oom_score'
docker exec reto1_app sh -c 'cat /sys/fs/cgroup/memory.max'
docker exec reto1_app sh -c 'cat /sys/fs/cgroup/memory.current'
docker exec reto1_app sh -c 'cat /sys/fs/cgroup/memory.events'
docker stats --no-stream reto1_app

for i in 1 2 3; do
  echo "=== LLAMADA $i: enviando 64MB ==="
  curl -s -X POST "http://localhost:5001/api/v1/process-data?mb=64"
  echo ""
  echo "--- Estado tras llamada $i ---"
  docker exec reto1_app sh -c 'cat /proc/1/status | grep -E "VmRSS|VmSize|VmSwap"'
  docker exec reto1_app sh -c 'cat /proc/1/oom_score'
  docker exec reto1_app sh -c 'cat /sys/fs/cgroup/memory.current'
  docker exec reto1_app sh -c 'cat /sys/fs/cgroup/memory.events'
  docker stats --no-stream reto1_app
  docker ps -a | grep reto1_app
done

echo "=== 3. MITIGACION: /admin/clear ==="
curl -s -X POST http://localhost:5001/admin/clear
echo ""
echo "--- Estado tras la mitigacion ---"
docker exec reto1_app sh -c 'cat /proc/1/status | grep -E "VmRSS|VmSize|VmSwap"'
docker exec reto1_app sh -c 'cat /sys/fs/cgroup/memory.current'
docker exec reto1_app sh -c 'cat /sys/fs/cgroup/memory.events'

set +x
echo "=== FIN DEL SCRIPT ==="
