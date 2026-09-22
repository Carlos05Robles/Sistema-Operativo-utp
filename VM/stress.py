import os
import time
import multiprocessing

WORKERS = int(os.environ.get("STRESS_WORKERS", "20"))
NICE_LEVEL = int(os.environ.get("STRESS_NICE", "-5"))


def burn():
    # Los workers de estres arrancan con MAYOR prioridad que app.py (nice mas bajo)
    try:
        os.nice(NICE_LEVEL)
    except PermissionError:
        pass
    while True:
        x = 0
        for i in range(1_000_000):
            x += i * i


if __name__ == "__main__":
    procesos = []
    for _ in range(WORKERS):
        p = multiprocessing.Process(target=burn)
        p.start()
        procesos.append(p)
    for p in procesos:
        p.join()
