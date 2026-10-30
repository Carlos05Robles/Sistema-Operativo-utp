<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$page_title = 'Archivos';
$active     = 'files';
$q          = trim((string) ($_GET['q'] ?? ''));

$files = $recent_files;
if ($q !== '') {
    $files = array_values(array_filter($files, static function (array $file) use ($q): bool {
        return stripos($file['name'], $q) !== false
            || stripos($file['owner'], $q) !== false
            || stripos($file['type'], $q) !== false;
    }));
}

require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
  <div>
    <span class="eyebrow"><?= icon('folder') ?> Biblioteca compartida</span>
    <h1>Archivos</h1>
    <p><?= count($files) ?> elementos<?= $q !== '' ? ' para “' . e($q) . '”' : ' en este workspace' ?>.</p>
  </div>
  <div class="page-actions">
    <button class="button button-ghost" type="button" data-toast="La creación de carpetas es una demostración">
      <?= icon('plus') ?> Nueva carpeta
    </button>
    <button class="button button-primary" type="button" data-toast="Selector de archivos de demostración">
      <?= icon('upload') ?> Subir archivo
    </button>
  </div>
</div>

<section class="card">
  <div class="toolbar">
    <div class="toolbar-search">
      <?= icon('search') ?>
      <input type="search" placeholder="Filtrar por nombre, tipo o propietario…"
             data-filter-table="files-table" aria-label="Filtrar archivos">
    </div>
    <div class="toolbar-actions">
      <select class="select" aria-label="Ordenar">
        <option>Más recientes</option>
        <option>Nombre (A–Z)</option>
        <option>Tamaño</option>
      </select>
      <button class="button button-ghost button-small" type="button" data-toast="Vista de cuadrícula próximamente">
        <?= icon('grid') ?> Cuadrícula
      </button>
    </div>
  </div>

  <?php if ($files === []): ?>
    <div class="empty">
      <?= icon('search') ?>
      <strong>Sin resultados</strong>
      <p>No hay archivos que coincidan con “<?= e($q) ?>”.</p>
      <a class="button button-ghost" href="files.php">Limpiar filtro</a>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table" id="files-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Propietario</th>
            <th>Tamaño</th>
            <th>Última edición</th>
            <th>Estado</th>
            <th class="align-right">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($files as $file): ?>
            <tr data-search="<?= e(strtolower($file['name'] . ' ' . $file['owner'] . ' ' . $file['type'])) ?>">
              <td>
                <span class="file-cell">
                  <span class="file-type"><?= e($file['type']) ?></span>
                  <span>
                    <strong class="file-name"><?= e($file['name']) ?></strong>
                    <small class="muted">Documento compartido</small>
                  </span>
                </span>
              </td>
              <td><?= e($file['owner']) ?></td>
              <td class="muted"><?= e($file['size']) ?></td>
              <td class="muted"><?= e($file['updated']) ?></td>
              <td><span class="badge <?= e($file['tone']) ?>"><?= e($file['status']) ?></span></td>
              <td class="align-right">
                <button class="icon-button" type="button" data-toast="Enlace copiado (demostración)" aria-label="Compartir">
                  <?= icon('share') ?>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<section class="dropzone" data-toast="Zona de subida de demostración">
  <?= icon('upload') ?>
  <div>
    <strong>Arrastra archivos aquí</strong>
    <p>o selecciona desde tu equipo. Máximo 100 MB por archivo.</p>
  </div>
  <button class="button button-ghost" type="button" data-toast="Selector de archivos de demostración">Seleccionar</button>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
