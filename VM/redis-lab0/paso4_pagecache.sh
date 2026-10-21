#!/bin/bash
F=/tmp/redis-lab0/rdb.sim

echo "### 1) Creo un archivo de 300 MB que hace de dataset (rdb.sim)"
dd if=/dev/urandom of=$F bs=1M count=300 status=none

echo; echo "### 2) Memoria justo despues de crearlo (mira Cached, Dirty y Writeback)"
grep -E '^(Cached|Dirty|Writeback):' /proc/meminfo

echo; echo "### 3) Fuerzo la escritura a disco (sync) y saco SOLO este archivo del page cache"
sync
python3 -c 'import os; f=os.open("/tmp/redis-lab0/rdb.sim",0); os.posix_fadvise(f,0,0,os.POSIX_FADV_DONTNEED); os.close(f)'
grep -E '^(Cached|Dirty|Writeback):' /proc/meminfo

echo; echo "### 4) PRIMERA lectura: el archivo viene del disco (lenta)"
time dd if=$F of=/dev/null bs=1M status=none
grep -E '^(Cached|Dirty|Writeback):' /proc/meminfo

echo; echo "### 5) SEGUNDA lectura: el archivo viene de la RAM (rapida)"
time dd if=$F of=/dev/null bs=1M status=none
grep -E '^(Cached|Dirty|Writeback):' /proc/meminfo

rm -f $F
echo; echo "### Listo: rdb.sim eliminado"
