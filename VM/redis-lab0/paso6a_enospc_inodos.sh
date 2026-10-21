#!/bin/bash
echo "### 1) Creo un disco de prueba de 64 MB con solo 128 inodos"
truncate -s 64M /tmp/minifs.img
mkfs.ext4 -q -F -N 128 /tmp/minifs.img
sudo mkdir -p /mnt/mini
sudo mount -o loop /tmp/minifs.img /mnt/mini

echo; echo "### 2) Creo archivos vacios hasta que falle"
sudo bash -c 'n=0; for i in $(seq 1 1000); do touch /mnt/mini/f$i 2>/dev/null || { echo "ENOSPC (no hay inodos) tras crear $n archivos"; break; }; n=$i; done'

echo; echo "### 3) Inodos (df -i): IUse llega al 100 %"
df -i /mnt/mini

echo; echo "### 4) Espacio (df -h): casi todo sigue libre"
df -h /mnt/mini

echo; echo "### 5) Limpieza"
sudo umount /mnt/mini
rm -f /tmp/minifs.img
sudo rmdir /mnt/mini
echo "listo, disco de prueba eliminado"
