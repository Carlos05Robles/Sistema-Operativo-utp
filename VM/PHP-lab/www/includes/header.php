<?php
require_once __DIR__ . '/config.php';

$page_title = $page_title ?? 'Panel';
$active     = $active ?? 'dashboard';
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($page_title) ?> · <?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <span class="brand-mark" aria-hidden="true">N</span>
      <span class="brand-copy">
        <strong><?= e(APP_NAME) ?></strong>
        <small>Workspace</small>
      </span>
      <button class="icon-button sidebar-close" type="button" data-sidebar-close aria-label="Cerrar menú">×</button>
    </div>

    <nav class="nav" aria-label="Principal">
      <span class="nav-label">Espacio de trabajo</span>
      <?php foreach ($nav_items as $item): ?>
        <a class="nav-item<?= nav_active($item['id'], $active) ?>" href="<?= e($item['href']) ?>">
          <?= icon($item['icon']) ?>
          <span><?= e($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="storage-card">
      <div class="storage-head">
        <span><?= icon('database') ?> Almacenamiento</span>
        <strong>38,2 GB</strong>
      </div>
      <div class="progress" role="progressbar" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100">
        <span style="width:38%"></span>
      </div>
      <small>38,2 GB de 100 GB utilizados</small>
      <a class="text-link" href="settings.php">Gestionar plan <?= icon('chevron') ?></a>
    </div>

    <div class="sidebar-user">
      <span class="avatar"><?= e($demo_user['initials']) ?></span>
      <span class="user-copy">
        <strong><?= e($demo_user['name']) ?></strong>
        <small><?= e($demo_user['role']) ?></small>
      </span>
      <a class="icon-button" href="index.php" title="Salir" aria-label="Salir"><?= icon('logout') ?></a>
    </div>
  </aside>

  <div class="sidebar-backdrop" data-sidebar-close aria-hidden="true"></div>

  <div class="app-main">
    <header class="topbar">
      <button class="icon-button menu-button" type="button" data-sidebar-open aria-label="Abrir menú">☰</button>

      <form class="search" action="files.php" method="get" role="search">
        <?= icon('search') ?>
        <input type="search" name="q" placeholder="Buscar archivos, personas o actividad…"
               value="<?= e($_GET['q'] ?? '') ?>" aria-label="Buscar">
        <kbd>⌘ K</kbd>
      </form>

      <div class="topbar-actions">
        <span class="env-badge" title="<?= e(APP_ENV) ?>"><?= e(APP_ENV) ?></span>
        <button class="icon-button" type="button" data-toast="No hay notificaciones nuevas" aria-label="Notificaciones">
          <?= icon('bell') ?><span class="dot"></span>
        </button>
        <button class="button button-primary" type="button" data-toast="Selector de archivos de demostración">
          <?= icon('upload') ?> Subir
        </button>
      </div>
    </header>

    <main class="content">
