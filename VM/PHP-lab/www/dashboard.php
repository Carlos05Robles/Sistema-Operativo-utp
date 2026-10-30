<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$page_title = 'Panel';
$active     = 'dashboard';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
  <div>
    <span class="eyebrow"><?= icon('spark') ?> Resumen del workspace</span>
    <h1>Buenos días, <?= e(explode(' ', $demo_user['name'])[0]) ?>.</h1>
    <p>Esto es lo que ha pasado en <?= e(APP_NAME) ?> durante los últimos siete días.</p>
  </div>
  <div class="page-actions">
    <button class="button button-ghost" type="button" data-toast="Informe exportado (demostración)">
      <?= icon('file') ?> Exportar
    </button>
    <button class="button button-primary" type="button" data-toast="Selector de archivos de demostración">
      <?= icon('plus') ?> Nuevo archivo
    </button>
  </div>
</div>

<section class="metric-grid" aria-label="Métricas">
  <?php foreach ($metrics as $metric): ?>
    <article class="metric-card">
      <div class="metric-head">
        <span class="metric-icon"><?= icon($metric['icon']) ?></span>
        <span class="metric-label"><?= e($metric['label']) ?></span>
      </div>
      <strong class="metric-value"><?= e($metric['value']) ?></strong>
      <span class="metric-change <?= e($metric['tone']) ?>"><?= e($metric['change']) ?></span>
    </article>
  <?php endforeach; ?>
</section>

<div class="grid-2">
  <section class="card chart-card">
    <div class="card-head">
      <div>
        <h2>Actividad de archivos</h2>
        <p>Subidas y ediciones por día</p>
      </div>
      <select class="select" aria-label="Periodo">
        <option>Últimos 7 días</option>
        <option>Últimos 30 días</option>
      </select>
    </div>

    <div class="chart" role="img" aria-label="Gráfico de actividad de los últimos siete días">
      <?php foreach ($chart as $bar): ?>
        <div class="chart-col">
          <div class="chart-bar" style="height:<?= (int) $bar['value'] ?>%">
            <span><?= (int) $bar['value'] ?>%</span>
          </div>
          <small><?= e($bar['label']) ?></small>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="chart-foot">
      <span><span class="legend-dot"></span> Este periodo</span>
      <strong>+18,6 %</strong>
      <span class="muted">vs. periodo anterior</span>
    </div>
  </section>

  <section class="card">
    <div class="card-head">
      <div>
        <h2>Actividad reciente</h2>
        <p>Lo último del equipo</p>
      </div>
      <a class="text-link" href="activity.php">Ver todo <?= icon('chevron') ?></a>
    </div>

    <ul class="activity-list">
      <?php foreach (array_slice($activity, 0, 4) as $event): ?>
        <li class="activity-item">
          <span class="activity-icon"><?= icon($event['icon']) ?></span>
          <span class="activity-copy">
            <strong><?= e($event['who']) ?></strong> <?= e($event['action']) ?>
            <em><?= e($event['target']) ?></em>
            <small><?= e($event['when']) ?></small>
          </span>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
</div>

<section class="card">
  <div class="card-head">
    <div>
      <h2>Archivos recientes</h2>
      <p>Los últimos movimientos del workspace</p>
    </div>
    <a class="text-link" href="files.php">Abrir archivos <?= icon('chevron') ?></a>
  </div>

  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Propietario</th>
          <th>Tamaño</th>
          <th>Última edición</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_slice($recent_files, 0, 4) as $file): ?>
          <tr>
            <td>
              <span class="file-cell">
                <span class="file-type"><?= e($file['type']) ?></span>
                <span><?= e($file['name']) ?></span>
              </span>
            </td>
            <td><?= e($file['owner']) ?></td>
            <td class="muted"><?= e($file['size']) ?></td>
            <td class="muted"><?= e($file['updated']) ?></td>
            <td><span class="badge <?= e($file['tone']) ?>"><?= e($file['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
