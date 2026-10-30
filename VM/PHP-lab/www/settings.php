<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$page_title = 'Ajustes';
$active     = 'settings';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
  <div>
    <span class="eyebrow"><?= icon('settings') ?> Configuración</span>
    <h1>Ajustes</h1>
    <p>Perfil, preferencias y seguridad del workspace.</p>
  </div>
  <div class="page-actions">
    <button class="button button-primary" type="button" data-toast="Cambios guardados (demostración)">
      <?= icon('check') ?> Guardar cambios
    </button>
  </div>
</div>

<div class="grid-2 grid-2-wide">
  <div class="stack-lg">
    <section class="card">
      <div class="card-head">
        <div>
          <h2>Perfil</h2>
          <p>Cómo te ve el resto del equipo</p>
        </div>
      </div>

      <form class="stack" data-demo-form="Perfil actualizado (demostración)">
        <div class="profile-row">
          <span class="avatar avatar-xl"><?= e($demo_user['initials']) ?></span>
          <div>
            <button class="button button-ghost button-small" type="button" data-toast="Selector de imagen de demostración">
              <?= icon('image') ?> Cambiar foto
            </button>
            <p class="muted small">JPG o PNG. Máximo 2 MB.</p>
          </div>
        </div>

        <div class="form-grid">
          <label class="field">
            <span>Nombre</span>
            <input type="text" name="name" value="<?= e($demo_user['name']) ?>">
          </label>
          <label class="field">
            <span>Email</span>
            <input type="email" name="email" value="<?= e($demo_user['email']) ?>">
          </label>
          <label class="field">
            <span>Rol</span>
            <input type="text" name="role" value="<?= e($demo_user['role']) ?>" readonly>
          </label>
          <label class="field">
            <span>Zona horaria</span>
            <select name="timezone">
              <option>Europe/Madrid (UTC+2)</option>
              <option>Europe/London (UTC+1)</option>
              <option>America/Bogota (UTC−5)</option>
            </select>
          </label>
        </div>

        <label class="field">
          <span>Bio</span>
          <textarea name="bio" rows="3">Responsable de operaciones y del espacio de trabajo de Nimbus.</textarea>
        </label>

        <div class="row-end">
          <button class="button button-primary" type="submit">Guardar perfil</button>
        </div>
      </form>
    </section>

    <section class="card">
      <div class="card-head">
        <div>
          <h2>Preferencias</h2>
          <p>Notificaciones y comportamiento de la interfaz</p>
        </div>
      </div>

      <ul class="setting-list">
        <li>
          <div>
            <strong>Notificaciones por email</strong>
            <p class="muted small">Resumen diario de actividad y enlaces compartidos.</p>
          </div>
          <label class="switch">
            <input type="checkbox" checked>
            <span></span>
          </label>
        </li>
        <li>
          <div>
            <strong>Alertas de seguridad</strong>
            <p class="muted small">Avisos de accesos nuevos y cambios de permisos.</p>
          </div>
          <label class="switch">
            <input type="checkbox" checked>
            <span></span>
          </label>
        </li>
        <li>
          <div>
            <strong>Vista compacta</strong>
            <p class="muted small">Más filas visibles en las tablas de archivos.</p>
          </div>
          <label class="switch">
            <input type="checkbox">
            <span></span>
          </label>
        </li>
        <li>
          <div>
            <strong>Enlaces con caducidad por defecto</strong>
            <p class="muted small">Los enlaces nuevos caducan a los 30 días.</p>
          </div>
          <label class="switch">
            <input type="checkbox" checked>
            <span></span>
          </label>
        </li>
      </ul>
    </section>

    <section class="card">
      <div class="card-head">
        <div>
          <h2>Seguridad</h2>
          <p>Contraseña y sesiones</p>
        </div>
      </div>

      <form class="stack" data-demo-form="Contraseña actualizada (demostración)">
        <div class="form-grid">
          <label class="field">
            <span>Contraseña actual</span>
            <input type="password" name="current" placeholder="••••••••">
          </label>
          <label class="field">
            <span>Nueva contraseña</span>
            <input type="password" name="new" placeholder="Mínimo 12 caracteres">
          </label>
        </div>
        <div class="row-between">
          <span class="muted small">Último cambio: hace 94 días</span>
          <button class="button button-ghost" type="submit">Actualizar contraseña</button>
        </div>
      </form>

      <div class="divider"></div>

      <div class="row-between">
        <div>
          <strong>Verificación en dos pasos</strong>
          <p class="muted small">Añade una capa extra al iniciar sesión.</p>
        </div>
        <button class="button button-ghost button-small" type="button" data-toast="Configuración de 2FA de demostración">Configurar</button>
      </div>
    </section>
  </div>

  <aside class="stack-lg">
    <section class="card card-tint">
      <span class="card-icon"><?= icon('spark') ?></span>
      <h2>Plan Business</h2>
      <p>100 GB, historial de 90 días y enlaces ilimitados.</p>
      <div class="progress"><span style="width:38%"></span></div>
      <p class="muted small">38,2 GB de 100 GB utilizados</p>
      <button class="button button-primary button-block" type="button" data-toast="Facturación de demostración">
        Mejorar plan
      </button>
    </section>

    <section class="card">
      <div class="card-head">
        <div>
          <h2>API</h2>
          <p>Integraciones y automatizaciones</p>
        </div>
      </div>
      <label class="field">
        <span>Clave de API</span>
        <div class="input-row">
          <input type="text" value="nmb_live_••••••••••••7f3a" readonly>
          <button class="button button-ghost button-small" type="button" data-toast="Clave copiada (demostración)">Copiar</button>
        </div>
      </label>
      <p class="muted small">No compartas esta clave. Puedes rotarla cuando quieras.</p>
    </section>

    <section class="card danger-card">
      <h2>Zona de riesgo</h2>
      <p>Eliminar el workspace borra todos los archivos y enlaces. Esta acción no se puede deshacer.</p>
      <button class="button button-danger button-block" type="button" data-toast="Acción deshabilitada en la demostración">
        Eliminar workspace
      </button>
    </section>
  </aside>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
