<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$page_title = 'Equipo';
$active     = 'team';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
  <div>
    <span class="eyebrow"><?= icon('users') ?> Personas con acceso</span>
    <h1>Equipo</h1>
    <p>Gestiona quién puede ver, editar y compartir archivos.</p>
  </div>
  <div class="page-actions">
    <button class="button button-primary" type="button" data-toast="Invitación de demostración">
      <?= icon('plus') ?> Invitar miembro
    </button>
  </div>
</div>

<section class="team-grid">
  <?php foreach ($team as $member): ?>
    <article class="card member-card">
      <div class="member-head">
        <span class="avatar avatar-lg"><?= e($member['initials']) ?></span>
        <span class="badge <?= e($member['tone']) ?>"><?= e($member['status']) ?></span>
      </div>
      <h2><?= e($member['name']) ?></h2>
      <p class="muted"><?= e($member['role']) ?></p>
      <p class="member-email"><?= e($member['email']) ?></p>
      <div class="member-actions">
        <button class="button button-ghost button-small" type="button" data-toast="Gestión de permisos de demostración">
          <?= icon('settings') ?> Permisos
        </button>
        <button class="icon-button" type="button" data-toast="Miembro eliminado (demostración)" aria-label="Eliminar miembro">
          <?= icon('logout') ?>
        </button>
      </div>
    </article>
  <?php endforeach; ?>
</section>

<section class="card">
  <div class="card-head">
    <div>
      <h2>Permisos por rol</h2>
      <p>Referencia rápida del workspace</p>
    </div>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Rol</th>
          <th>Ver</th>
          <th>Subir</th>
          <th>Compartir</th>
          <th>Administrar</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Administrador</strong></td>
          <td><span class="badge green">Sí</span></td>
          <td><span class="badge green">Sí</span></td>
          <td><span class="badge green">Sí</span></td>
          <td><span class="badge green">Sí</span></td>
        </tr>
        <tr>
          <td><strong>Editor</strong></td>
          <td><span class="badge green">Sí</span></td>
          <td><span class="badge green">Sí</span></td>
          <td><span class="badge green">Sí</span></td>
          <td><span class="badge gray">No</span></td>
        </tr>
        <tr>
          <td><strong>Lector</strong></td>
          <td><span class="badge green">Sí</span></td>
          <td><span class="badge gray">No</span></td>
          <td><span class="badge gray">No</span></td>
          <td><span class="badge gray">No</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
