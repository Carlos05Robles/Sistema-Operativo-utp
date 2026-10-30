<?php
declare(strict_types=1);

/**
 * Portada + acceso del portal.
 *
 * LAB ONLY: el fallo de session.upload_progress se produce en el request
 * startup, antes de que este archivo se compile o ejecute. Por eso este
 * script no llama a session_start() y no necesita ninguna lógica especial.
 */

require_once __DIR__ . '/includes/config.php';

// Mantén la huella del runtime para las comprobaciones del laboratorio.
header('X-Powered-By: PHP/' . PHP_VERSION);
header('X-Nimbus-App: demo');

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    // Sin credenciales (por ejemplo, el probe del laboratorio) se responde 200
    // con la portada. Con credenciales se simula un acceso correcto.
    if ($email !== '' && $password !== '') {
        header('Location: dashboard.php', true, 302);
        exit;
    }

    if ($email !== '' || $password !== '') {
        $error = 'Introduce tu email y tu contraseña para continuar.';
    }
}
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NAME) ?> · <?= e(APP_DESCRIPTION) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-body">
  <main class="auth-shell">
    <section class="auth-brand">
      <a class="brand brand-large" href="index.php">
        <span class="brand-mark" aria-hidden="true">N</span>
        <span class="brand-copy">
          <strong><?= e(APP_NAME) ?></strong>
          <small>Workspace</small>
        </span>
      </a>

      <div class="auth-pitch">
        <span class="eyebrow"><?= icon('spark') ?> Colaboración sin fricción</span>
        <h1>Todo el trabajo de tu equipo, en un solo lugar.</h1>
        <p>
          Archivos, enlaces y actividad compartidos con permisos claros.
          <?= e(APP_NAME) ?> mantiene a tu equipo sincronizado sin cambiar de herramienta.
        </p>
      </div>

      <ul class="auth-points">
        <li><?= icon('check') ?> Espacios privados por equipo y proyecto</li>
        <li><?= icon('check') ?> Enlaces con caducidad y contraseña</li>
        <li><?= icon('check') ?> Historial de actividad y versiones</li>
      </ul>

      <div class="auth-proof">
        <div class="avatar-stack">
          <span class="avatar">MR</span>
          <span class="avatar avatar-2">DS</span>
          <span class="avatar avatar-3">LP</span>
        </div>
        <span>Más de 2.400 equipos organizan su trabajo con <?= e(APP_NAME) ?>.</span>
      </div>
    </section>

    <section class="auth-panel">
      <div class="auth-card">
        <div class="auth-card-head">
          <h2>Entra en tu workspace</h2>
          <p>Usa la cuenta de demostración o cualquier email para ver el panel.</p>
        </div>

        <?php if ($error !== ''): ?>
          <p class="alert alert-error"><?= e($error) ?></p>
        <?php endif; ?>

        <form class="stack" method="post" action="index.php">
          <label class="field">
            <span>Email</span>
            <input type="email" name="email" autocomplete="username"
                   placeholder="tu@empresa.com" value="<?= e($email) ?>">
          </label>
          <label class="field">
            <span>Contraseña</span>
            <input type="password" name="password" autocomplete="current-password"
                   placeholder="••••••••">
          </label>

          <div class="row-between">
            <label class="checkbox">
              <input type="checkbox" name="remember" value="1">
              <span>Recordarme</span>
            </label>
            <a class="text-link" href="#" data-toast="El restablecimiento no está disponible en la demo">¿Olvidaste la contraseña?</a>
          </div>

          <button class="button button-primary button-block" type="submit">
            Entrar <?= icon('chevron') ?>
          </button>
        </form>

        <div class="divider"><span>o</span></div>

        <a class="button button-ghost button-block" href="dashboard.php">
          <?= icon('grid') ?> Ver el panel de demostración
        </a>

        <p class="demo-hint">
          Demo: <code>marta@nimbus.test</code> · cualquier contraseña.
        </p>
      </div>

      <p class="auth-foot">
        <?= e(APP_ENV) ?> · datos ficticios ·
        <a class="text-link" href="health.php">estado del servicio</a>
      </p>
    </section>
  </main>
</body>
</html>
