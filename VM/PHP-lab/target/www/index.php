<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP2Shell Training Portal · LAB ONLY</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>

<body>
  <header class="site-header">
    <div class="container header-inner">
      <div class="brand">
        <span class="brand-dot"></span>
        <span class="brand-name">PHP2Shell Training Portal</span>
      </div>
      <nav class="nav">
        <a class="active" href="index.php">Inicio</a>
        <a href="upload.php">Subir archivo</a>
        <a href="uploads/">Listado de uploads</a>
      </nav>
    </div>
  </header>

  <main class="main">
    <div class="container hero">
      <div class="hero-content">
        <span class="pill pill-warn"><span>LAB ONLY</span> · Aplicación deliberadamente vulnerable</span>
        <h1>Entrenamiento controlado para la ruta PHP2Shell</h1>
        <p>Este entorno reproduce, de forma aislada a <code>127.0.0.1</code>, un escenario de subida de archivos con lista negra incompleta para practicar la detección y explotación en laboratorio.</p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="upload.php">Empezar reto</a>
          <a class="btn btn-ghost" href="uploads/">Ver archivos subidos</a>
        </div>
      </div>
    </div>

    <div class="container grid">
      <article class="card">
        <h3>Objetivo</h3>
        <p>Obtener ejecución remota de comandos en este contenedor y leer <code>/flag.txt</code>.</p>
      </article>
      <article class="card">
        <h3>Superficie vulnerable</h3>
        <p>Formulario de subida con filtro basado únicamente en la extensión <code>.php</code>. Otras extensiones interpretables por PHP, como <code>.phtml</code>, quedan permitidas.</p>
      </article>
      <article class="card">
        <h3>Entorno aislado</h3>
        <p>Solo accesible en localhost. Usa este portal exclusivamente para fines de formación y validación de reglas.</p>
      </article>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <span>PHP2Shell Lab · Uso exclusivo en entorno aislado</span>
      <span class="muted">No exponer a redes públicas</span>
    </div>
  </footer>
</body>

</html>
