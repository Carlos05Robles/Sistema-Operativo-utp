#!/bin/bash
echo "### EMFILE: bajo el limite de FDs de un subshell a 128 y abro archivos hasta que falle"
echo -n "limite actual de mi shell: "; ulimit -n
echo
( ulimit -n 128; echo -n "limite dentro del subshell: "; ulimit -n; python3 -c 'import os
fds=[]
for i in range(9999):
    try: fds.append(os.open("/dev/null", os.O_RDONLY))
    except OSError as e: print(f"EMFILE tras abrir {i} fds: {e}"); break
print("abiertos:", len(fds))' )
echo
echo -n "mi shell sigue con limite: "; ulimit -n
