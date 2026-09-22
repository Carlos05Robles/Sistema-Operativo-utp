FROM python:3.11-slim

# Herramientas utiles dentro del contenedor:
# procps (ps/kill), util-linux (taskset), htop, curl
RUN apt-get update && apt-get install -y \
    procps \
    util-linux \
    htop \
    curl \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
RUN pip install flask

# Copia los archivos del laboratorio
COPY app.py stress.py ./

EXPOSE 5000
CMD ["python", "app.py"]
