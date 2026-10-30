<?php
// LAB ONLY - aplicacion de entrenamiento deliberadamente vulnerable.
// No usar este codigo fuera de un entorno aislado.
$msg = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $f = $_FILES['avatar'];
    $name = basename((string) $f['name']);

    // VULN INTENCIONADA: blacklist incompleta. Solo bloquea ".php" exacto;
    // deja pasar .phtml, .php5, .phar, etc.
    if (preg_match('/\.php$/i', $name)) {
        $msg = 'Extension .php bloqueada.';
    } elseif ($f['error'] !== UPLOAD_ERR_OK) {
        $msg = 'Error de subida: ' . (int) $f['error'];
    } else {
        $dir = __DIR__ . '/uploads';
        @mkdir($dir, 0777, true);
        $dest = $dir . '/' . $name;
        if (move_uploaded_file($f['tmp_name'], $dest)) {
            $ok = true;
            $msg = 'Avatar subido: uploads/' . htmlspecialchars($name, ENT_QUOTES);
        } else {
            $msg = 'No se pudo guardar el archivo.';
        }
    }
}

$files = array_values(array_diff(scandir(__DIR__ . '/uploads') ?: [], ['.', '..']));
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Subir archivo · PHP2Shell Training Portal</title>
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
        <a href="index.php">Inicio</a>
        <a class="active" href="upload.php">Subir archivo</a>
        <a href="uploads/">Listado de uploads</a>
      </nav>
    </div>
  </header>

  <main class="main">
    <div class="container hero">
      <div class="hero-content">
        <span class="pill pill-warn"><span>LAB ONLY</span> · Lista negra incompleta</span>
        <h1>Subida de archivos</h1>
        <p>Sube un archivo al directorio <code>uploads/</code>. La validación solo bloquea extensiones que terminen exactamente en <code>.php</code>, por lo que extensiones como <code>.phtml</code> siguen siendo aceptadas.</p>
      </div>
    </div>

    <div class="container grid">
      <section class="card card-wide">
        <div class="card-header">
          <div class="card-header-text">
            <h2>Formulario de subida</h2>
            <p class="muted">Usa este formulario para subir archivos al contenedor de entrenamiento.</p>
          </div>
        </div>

        <?php if ($msg): ?>
          <div class="alert <?= $ok ? 'ok' : 'warn' ?>">
            <span class="alert-title"><?= $ok ? 'Subida correcta' : 'Atención' ?></span>
            <span><?= $msg ?></span>
          </div>
        <?php endif; ?>

        <form class="form" method="post" enctype="multipart/form-data">
          <div class="form-row">
            <label class="muted" for="avatar">Selecciona un archivo</label>
            <div class="file-drop">
              <input id="avatar" type="file" name="avatar" required>
              <span class="muted">Formatos aceptados según la regla de validación del laboratorio</span>
            </div>
          </div>
          <div class="file-actions">
            <button class="btn btn-primary" type="submit">Subir archivo</button>
            <a class="btn btn-ghost" href="uploads/">Ver directorio uploads</a>
          </div>
        </form>
      </section>

      <aside class="card card-side">
        <div class="card-header-text">
          <h3>Archivos subidos</h3>
          <p class="muted">Listado actual del directorio <code>uploads/</code></p>
        </div>
        <div class="list">
          <?php if (empty($files)): ?>
            <div class="empty">Aún no hay archivos subidos.</div>
          <?php else: ?>
            <?php foreach ($files as $f): ?>
              <div class="list-item">
                <a class="list-link" href="uploads/<?= htmlspecialchars($f, ENT_QUOTES) ?>">
                  <span class="list-dot"></span>
                  <span><?= htmlspecialchars($f, ENT_QUOTES) ?></span>
                </a>
                <span class="muted">Ver</span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </aside>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <span>PHP2Shell Lab · Uso exclusivo en entorno aislado</span>
      <span class="muted">Vulnerabilidad intencional · No exponer a redes públicas</span>
    </div>
  </footer>
</body>

</html>
