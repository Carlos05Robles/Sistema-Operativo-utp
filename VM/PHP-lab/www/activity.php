<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$page_title = 'Actividad';
$active     = 'activity';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
  <div>
    <span class="eyebrow"><?= icon('activity') ?> Registro del workspace</span>
    <h1>Actividad</h1>
    <p>Todo lo que ha ocurrido en <?= e(APP_NAME) ?>, en orden cronológico.</p>
  </div>
  <div class="page-actions">
    <button class="button button-ghost" type="button" data-toast="Filtros avanzados de demostración">
      <?= icon('search') ?> Filtrar
    </button>
    <button class="button button-primary" type="button" data-toast="Informe exportado (demostración)">
      <?= icon('file') ?> Exportar CSV
    </button>
  </div>
</div>

<div class="grid-2 grid-2-wide">
  <section class="card">
    <div class="card-head">
      <div>
        <h2>Hoy</h2>
        <p>5 eventos registrados</p>
      </div>
      <span class="badge green">En directo</span>
    </div>

    <ul class="timeline">
      <?php foreach ($activity as $event): ?>
        <li class="timeline-item">
          <span class="timeline-icon"><?= icon($event['icon']) ?></span>
          <div class="timeline-body">
            <p>
              <strong><?= e($event['who']) ?></strong> <?= e($event['action']) ?>
              <em><?= e($event['target']) ?></em>
            </p>
            <small><?= e($event['when']) ?> · desde 192.0.2.10</small>
          </div>
        </li>
      <?php endforeach; ?>
      <li class="timeline-item timeline-muted">
        <span class="timeline-icon"><?= icon('clock') ?></span>
        <div class="timeline-body">
          <p>Sin más actividad reciente</p>
          <small>Los eventos anteriores se archivan a los 90 días.</small>
        </div>
      </li>
    </ul>
  </section>

  <aside class="stack-lg">
    <section class="card">
      <div class="card-head">
        <div>
          <h2>Resumen</h2>
          <p>Últimas 24 horas</p>
        </div>
      </div>
      <ul class="stat-list">
        <li><span>Subidas</span><strong>18</strong></li>
        <li><span>Descargas</span><strong>64</strong></li>
        <li><span>Enlaces creados</span><strong>9</strong></li>
        <li><span>Miembros activos</span><strong>6</strong></li>
      </ul>
    </section>

    <section class="card card-tint">
      <span class="card-icon"><?= icon('shield') ?></span>
      <h2>Todo en orden</h2>
      <p>No se han detectado accesos anómalos en los últimos siete días.</p>
      <a class="text-link" href="settings.php">Revisar seguridad <?= icon('chevron') ?></a>
    </section>
  </aside>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
