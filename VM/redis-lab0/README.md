# LAB 0 — Calentamiento al Módulo III con Redis (60 min, sin sudo)

> Hilo conductor: **Redis** (caché + persistencia RDB/AOF). En 60 min tocas los 3 pilares del módulo — **FDs/VFS (4.1), page cache + fsync (4.2/5.x), errores reales (ENOSPC/EMFILE)** — usando el mismo servicio que luego aparece en el semestral y en el LAB 2. Sin niveles, sin riesgo, sin `sudo` (salvo paso 6a opcional).

**Duración:** 1 sesión (1 h a 1 h 15 min)
**Tema de Syllabus:** 4.1 (VFS, FD) y 4.2 (page cache + persistencia) — versión blanda, anticipo del LAB 1 y LAB 2
**Servicio ejemplo:** Redis (`redis-server`: sockets de clientes + `dump.rdb` + `appendonly.aof` + `appendfsync everysec`)
**Herramientas:** bash, `cat`, `free`, `stat`, `ls`, `df`, `time`, `dd`, `python3`, `/proc` (+ `redis-server`/`redis-cli` **opcionales**: si no están, se simula con archivos)
**Privilegios:** ninguno — salvo el **paso 6a** (marcado `(root, opcional)`)

### Objetivos de aprendizaje

1. Localizar el PID de **Redis** (o simularlo) y leer `/proc/<PID>/status`, `/proc/<PID>/fd`, `/proc/<PID>/limits` → entender qué es un FD (socket, fichero RDB/AOF).
2. Usar `stat`, `ls -i` y `df` sobre los ficheros de persistencia (`dump.rdb`, `appendonly.aof`) → "ver" inodos y los dos límites distintos (espacio vs inodos).
3. Medir con `time` que la 2ª lectura de un fichero tipo-RDB es casi instantánea → **page cache**: por qué Redis carga el dataset más rápido cuando está en RAM.
4. Entender en 5 minutos los 3 modos de `appendfsync` (`no` / `everysec` / `always`) con una demo Python sin riesgo → anticipo del LAB 1-N3 (durabilidad) y LAB 2 (torneo fio).
5. Respetar dos errores reales de Redis: `ENOSPC` (el AOF crece y llena disco/inodos) y `EMFILE` (`maxclients` > `LimitNOFILE`/límite del proceso).

### Procedimiento (todo guiado, paso a paso)

**Paso 1 — El kernel detrás de Redis (5 min)**

```bash
# Si hay redis-server, lanza el Redis del lab en el puerto 7777; si NO, crea una
# simulación rootless en /tmp/redis-lab0 (mismo laboratorio, sin instalar nada).
./scripts/redis-lab0.sh setup
# Servicio = Redis del lab (7777) o, sin Redis, TU propio shell ($$).
# (No usar sshd/nginx: son de otro usuario o "no-dumpables" y /proc/<pid>/fd falla.)
SVC=$(pgrep -of '[r]edis-server.*7777' 2>/dev/null || echo $$)
echo "PID_SERVICIO=$SVC"
cat /proc/$SVC/cmdline 2>/dev/null | tr "\0" " "; echo
cat /proc/$SVC/status 2>/dev/null | grep -E "Name|PPid|Threads|VmRSS|State"
uname -r; cat /proc/version; uptime -p
```

> **Si NO tienes Redis (modo estudiante):** el `setup` deja `dump.rdb` y `appendonly.aof` simulados en `/tmp/redis-lab0`, y `$SVC` pasa a ser tu propio shell. Los pasos 2, 3, 4 y 6 se hacen igual; en el paso 5 verás los FDs de tu shell (tty, pipes) en vez de sockets + AOF de Redis. Es el mismo mecanismo del kernel.

> **Pista para el informe:** `/proc` **no es un archivo**: es una *ventana en vivo* a las estructuras del kernel (task_struct, fdtable). `cat /proc/version` dos veces seguidas lo demuestra. En Redis, cada cliente conectado = 1 socket = 1 FD en `/proc/<redis>/fd`.

**Paso 2 — La memoria que el SO presta a los datos (10 min)**

```bash
free -h                      # columnas: total used free shared buff/cache available
grep -E '^(MemTotal|MemFree|MemAvailable|Cached):' /proc/meminfo
```

Llénalo en tu informe:

| Qué muestra | Valor en tu máquina | Pregunta a responder (piensa en Redis) |
|---|---|---|
| `buff/cache` | ____ | ¿Es memoria "usada" o memoria `prestada` al dataset/RDB? |
| `MemAvailable` | ____ | ¿Por qué `available` suele ser > `free`? (pista: page cache reclamable) |

**Paso 3 — Inodos: dónde Redis guarda lo persistente (10 min)**

```bash
ls -l /tmp/redis-lab0/          # dump.rdb + appendonly.aof (reales o simulados)
stat /tmp/redis-lab0/dump.rdb
stat /tmp/redis-lab0/appendonly.aof
ls -i /tmp/redis-lab0/dump.rdb /tmp/redis-lab0/appendonly.aof
cp /tmp/redis-lab0/dump.rdb /tmp/redis-lab0/dump-copia.rdb
ls -i /tmp/redis-lab0/dump.rdb /tmp/redis-lab0/dump-copia.rdb   # ¿mismo inodo? ¿por qué?
df -h /tmp ; df -i /tmp      # espacio vs inodos: dos agotamientos distintos
```

> **Conexión semestral:** `dump.rdb` (snapshot) y `appendonly.aof` (log) **deben vivir en un volumen**, nunca en el `upperdir` efímero del contenedor (LAB 3). Si el disco se llena, Redis falla con `ENOSPC` aunque haya RAM libre.

**Paso 4 — Page cache con un fichero tipo-RDB (10 min)**

```bash
dd if=/dev/urandom of=/tmp/redis-lab0/rdb.sim bs=1M count=300 status=none  # "dataset" de 300 MB
grep -E '^(Cached|Dirty|Writeback):' /proc/meminfo
# "Expulsar" del cache SOLO este archivo (sin sudo, sin tocar nada más):
python3 -c 'import os; f=os.open("/tmp/redis-lab0/rdb.sim",0); os.posix_fadvise(f,0,0,os.POSIX_FADV_DONTNEED); os.close(f)'
time dd if=/tmp/redis-lab0/rdb.sim of=/dev/null bs=1M status=none   # 1ª lectura: desde disco (lenta)
grep -E '^(Cached|Dirty|Writeback):' /proc/meminfo     # Cached subió
time dd if=/tmp/redis-lab0/rdb.sim of=/dev/null bs=1M status=none   # 2ª lectura: desde RAM (rápida)
grep -E '^(Cached|Dirty|Writeback):' /proc/meminfo
rm -f /tmp/redis-lab0/rdb.sim
```

> **Pista (Redis):** al reiniciar, Redis lee `dump.rdb`/`appendonly.aof` de disco a RAM. La 1ª carga es lenta; mientras el fichero siga en **page cache**, recargas y `BGREWRITEAOF` van mucho más rápido. Ese "segundo intento rápido" es el page cache (sección 4.2). Anota ambos tiempos: es tu primera evidencia para el LAB 2.

**Paso 5 — FDs de Redis + `appendfsync` en 5 minutos (10 min)**

```bash
SVC=$(pgrep -of '[r]edis-server.*7777' 2>/dev/null || echo $$)   # Redis del lab o tu shell
ls -l /proc/$SVC/fd/ 2>/dev/null | head -15   # Redis real: sockets TCP + aof/rdb + log; sin Redis: FDs de tu shell
cat /proc/$SVC/limits 2>/dev/null | grep "open files"   # RLIMIT_NOFILE del servicio
ulimit -n
redis-cli -p 7777 info clients 2>/dev/null | grep -E "connected_clients|maxclients" || echo "(sin redis-cli o sin instancia en 7777: maxclients por defecto 10000, limitado por ulimit)"
# Demostración POSIX: el offset vive en la open file description
echo dato > /tmp/redis-lab0/demo.txt
exec 3<>/tmp/redis-lab0/demo.txt; cat /proc/$$/fdinfo/3 | grep pos; exec 3>&-
```

```bash
# Demo suave de fsync (sin matar nada): buffered vs durable
python3 - <<'PY'
import os
p = '/tmp/redis-lab0/aof.sim'
f = open(p, 'a', buffering=1)
f.write('*2\r\n$3\r\nSET\r\n$1\r\nx\r\n')  # como un comando del AOF, aún en buffer de usuario/página
print('escrito sin fsync: visible para lectores, NO durable ante caída')
f.flush(); os.fsync(f.fileno())              # ahora sí: writeback -> disco
print('tras fsync: durable (equivale a appendfsync always en ese comando)')
PY
```

| `appendfsync` | Qué hace el kernel | Riesgo / costo |
|---|---|---|
| `no` | deja todo en page cache, el SO decide cuándo escribir | rapidísimo, se pierde hasta ~30 s ante caída |
| `everysec` (defecto) | 1 `fsync` por segundo del AOF | equilibrio: pierdes ≤1 s, costo moderado (lo medirás en LAB 2) |
| `always` | `fsync` por cada escritura | máxima durabilidad, mínimo rendimiento |

**Paso 6 — Dos errores que respetarás: aplicados a Redis (10 min)**

```bash
# b) EMFILE (sin root): maxclients choca con el límite de FDs
( ulimit -n 128; python3 -c 'import os
fds=[]
for i in range(9999):
    try: fds.append(os.open("/dev/null", os.O_RDONLY))
    except OSError as e: print(f"EMFILE tras abrir {i} fds: {e}"); break
print("abiertos:", len(fds))' )
# -> "Too many open files": en Redis real verías 'maxclients exceeded' y rechazos aunque haya CPU libre (LAB 1)
```

```bash
# a) ENOSPC de INODOS (no de espacio): (root, opcional) FS diminuto en loop con pocos inodos
truncate -s 64M /tmp/minifs.img                     # archivo que será el "disco"
mkfs.ext4 -q -F -N 128 /tmp/minifs.img              # solo 128 inodos
sudo mkdir -p /mnt/mini && sudo mount -o loop /tmp/minifs.img /mnt/mini
sudo bash -c 'for i in $(seq 1 1000); do : > /mnt/mini/f$i 2>/dev/null || { echo "ENOSPC (no hay inodos)"; break; }; done'
df -i /mnt/mini                                     # IUse 100 %: ¡se agotaron los inodos, no el espacio!
sudo umount /mnt/mini; rm -f /tmp/minifs.img; sudo rmdir /mnt/mini  # limpieza
```

> **Para lectores sin `sudo`:** recuerda *conceptualmente* que "inodos" y "espacio" son dos agotamientos distintos (`df -i` vs `df -h`). En Redis el AOF puede llenar el **espacio** (crece sin `rewrite`) o un volumen con millones de ficheros pequeños puede agotar **inodos**. El profesor puede proyectar el demo 6a si tu VM no permite root.

**Cierre / "pase de lista" (escribirlo antes de salir):**

| Lo que vi | Comando que lo demostró | En una frase (piensa en Redis) |
|---|---|---|
| Memoria prestada al dataset | ____ | ____ |
| Inodos de `dump.rdb` / `appendonly.aof` | ____ | ____ |
| Page cache sobre fichero tipo-RDB | ____ | ____ |
| FDs del servicio vs `maxclients`/`LimitNOFILE` | ____ | ____ |
| `appendfsync` no/everysec/always | ____ | ____ |
| ENOSPC vs EMFILE (aplicados a Redis) | ____ | ____ |

### Entrega y evaluación (suave)

**Entregable:** la tabla del cierre completada + las capturas de los pasos 1, 4 y 5 (solo *copiar-pegar*, no se pide análisis profundo aún).

**Puntuación:** 2 puntos de **participación** (no entra en la rúbrica de los Labs 1-3); se aprueba simplemente "echándoles un vistazo" a las capturas. Es la *zona segura* para confirmar VM, consola y paciencia antes de las misiones.

### A dónde va cada cosa (mapa al módulo)

| Lo visto aquí | Dónde se profundiza |
|---|---|
| `/proc/<redis>/fd`, `maxclients`, `LimitNOFILE` | LAB 1 (fuga de FDs, EMFILE, `file-nr`) |
| page cache, `Cached`/`Dirty`, `posix_fadvise` | LAB 1 + LAB 2 (por qué la 2ª lectura vuela) |
| `appendfsync`, `fsync` | LAB 1-N3 (durabilidad tras caída) + LAB 2 (torneo `fsync=1` vs batch) |
| `dump.rdb` / `appendonly.aof` en disco | LAB 3 (volumen vs `upperdir`: lo persistente NUNCA en capas) |
| `ENOSPC` / `EMFILE` | Misión Final (post-mortem: disco fantasma + 502 por FDs) |
