<?php
declare(strict_types=1);

/**
 * Configuración y datos de demostración del portal.
 *
 * LAB ONLY: esta aplicación es la fachada del stack vulnerable. El fallo de
 * session.upload_progress ocurre en el request startup, antes de que este
 * archivo se incluya. La app no llama a session_start() a propósito.
 *
 * Todos los datos son ficticios. No hay base de datos ni autenticación real.
 */

const APP_NAME        = 'Nimbus';
const APP_DESCRIPTION = 'Workspace para equipos que mueven archivos';
const APP_ENV         = 'Entorno de demostración';
const APP_VERSION     = '2.8.0';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function nav_active(string $item, string $current): string
{
    return $item === $current ? ' is-active' : '';
}

function icon(string $name, string $class = 'icon'): string
{
    $paths = [
        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'folder'   => '<path d="M3 7.5A2.5 2.5 0 0 1 5.5 5H9l2 2h7.5A2.5 2.5 0 0 1 21 9.5v7A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5z"/>',
        'activity' => '<path d="M3 12h4l2.5-6 4 12 2.5-6H21"/>',
        'users'    => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><path d="M16 8.5a2.8 2.8 0 0 1 0 5.6"/><path d="M17.5 14.5A4.5 4.5 0 0 1 21 19"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21h-4v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3v-4h.1A1.7 1.7 0 0 0 4.6 8.5a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3h4v.1A1.7 1.7 0 0 0 15.5 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 9c.1.4.3.75.6 1 .3.25.7.4 1.1.4h.1v4h-.1a1.7 1.7 0 0 0-1.7.6z"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'bell'     => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/>',
        'upload'   => '<path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M5 20h14"/>',
        'plus'     => '<path d="M12 5v14M5 12h14"/>',
        'chevron'  => '<path d="m9 18 6-6-6-6"/>',
        'share'    => '<circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="18" cy="18" r="2.5"/><path d="m8.2 10.8 7.6-3.6M8.2 13.2l7.6 3.6"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'database' => '<ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6c0 1.66 3.58 3 8 3s8-1.34 8-3V6"/><path d="M4 12v6c0 1.66 3.58 3 8 3s8-1.34 8-3v-6"/>',
        'logout'   => '<path d="M10 5H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4"/><path d="M14 8l4 4-4 4"/><path d="M9 12h9"/>',
        'check'    => '<path d="m5 12 4.5 4.5L19 7"/>',
        'file'     => '<path d="M7 3h7l4 4v14H7z"/><path d="M14 3v5h5"/>',
        'image'    => '<rect x="4" y="4" width="16" height="16" rx="2"/><circle cx="9" cy="9" r="1.5"/><path d="m4 17 4.5-4.5L14 18l2.5-2.5L20 19"/>',
        'shield'   => '<path d="M12 3 5 6v5c0 4.5 3 8.5 7 10 4-1.5 7-5.5 7-10V6z"/><path d="m9 12 2 2 4-4"/>',
        'spark'    => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M18.4 5.6l-2.8 2.8M8.4 15.6l-2.8 2.8"/>',
    ];
    $path = $paths[$name] ?? $paths['grid'];

    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
        . 'stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . $path . '</svg>';
}

$demo_user = [
    'name'     => 'Marta Ruiz',
    'role'     => 'Administradora',
    'email'    => 'marta@nimbus.test',
    'initials' => 'MR',
];

$nav_items = [
    ['id' => 'dashboard', 'label' => 'Panel',      'href' => 'dashboard.php', 'icon' => 'grid'],
    ['id' => 'files',     'label' => 'Archivos',   'href' => 'files.php',     'icon' => 'folder'],
    ['id' => 'activity',  'label' => 'Actividad',  'href' => 'activity.php',  'icon' => 'activity'],
    ['id' => 'team',      'label' => 'Equipo',     'href' => 'team.php',      'icon' => 'users'],
    ['id' => 'settings',  'label' => 'Ajustes',    'href' => 'settings.php',  'icon' => 'settings'],
];

$metrics = [
    ['label' => 'Archivos',        'value' => '1.248',  'change' => '+12,4 %', 'tone' => 'up',   'icon' => 'folder'],
    ['label' => 'Almacenamiento',  'value' => '38,2 GB', 'change' => 'de 100 GB', 'tone' => 'flat', 'icon' => 'database'],
    ['label' => 'Enlaces activos', 'value' => '86',     'change' => '+7 esta semana', 'tone' => 'up', 'icon' => 'share'],
    ['label' => 'Miembros',        'value' => '14',     'change' => '2 invitados', 'tone' => 'flat', 'icon' => 'users'],
];

$chart = [
    ['label' => 'L', 'value' => 38],
    ['label' => 'M', 'value' => 54],
    ['label' => 'X', 'value' => 47],
    ['label' => 'J', 'value' => 68],
    ['label' => 'V', 'value' => 61],
    ['label' => 'S', 'value' => 29],
    ['label' => 'D', 'value' => 22],
];

$recent_files = [
    ['name' => 'informe-q4-2026.pdf',      'type' => 'PDF',  'owner' => 'Marta Ruiz',   'size' => '2,4 MB',  'updated' => 'hace 12 min', 'status' => 'Compartido', 'tone' => 'blue'],
    ['name' => 'presupuesto-marketing.xlsx','type' => 'XLSX', 'owner' => 'Diego Salas',  'size' => '840 KB',  'updated' => 'hace 38 min', 'status' => 'Privado',    'tone' => 'gray'],
    ['name' => 'branding-final.zip',        'type' => 'ZIP',  'owner' => 'Lucía Prado',  'size' => '18,7 MB', 'updated' => 'hace 2 h',    'status' => 'Compartido', 'tone' => 'blue'],
    ['name' => 'roadmap-producto.md',       'type' => 'MD',   'owner' => 'Andrés Lima',  'size' => '12 KB',   'updated' => 'hace 3 h',    'status' => 'Equipo',     'tone' => 'green'],
    ['name' => 'capturas-onboarding.png',   'type' => 'PNG',  'owner' => 'Marta Ruiz',   'size' => '4,1 MB',  'updated' => 'ayer',        'status' => 'Privado',    'tone' => 'gray'],
    ['name' => 'contrato-proveedor.docx',   'type' => 'DOCX', 'owner' => 'Diego Salas',  'size' => '320 KB',  'updated' => 'ayer',        'status' => 'Equipo',     'tone' => 'green'],
    ['name' => 'analitica-octubre.csv',     'type' => 'CSV',  'owner' => 'Lucía Prado',  'size' => '96 KB',   'updated' => 'hace 2 días', 'status' => 'Privado',    'tone' => 'gray'],
    ['name' => 'manual-de-marca.pdf',       'type' => 'PDF',  'owner' => 'Andrés Lima',  'size' => '5,6 MB',  'updated' => 'hace 2 días', 'status' => 'Compartido', 'tone' => 'blue'],
];

$activity = [
    ['who' => 'Lucía Prado',  'action' => 'compartió',   'target' => 'branding-final.zip',      'when' => 'hace 18 min', 'icon' => 'share'],
    ['who' => 'Diego Salas',  'action' => 'subió',       'target' => 'presupuesto-marketing.xlsx', 'when' => 'hace 41 min', 'icon' => 'upload'],
    ['who' => 'Marta Ruiz',   'action' => 'editó',       'target' => 'informe-q4-2026.pdf',     'when' => 'hace 1 h',    'icon' => 'file'],
    ['who' => 'Andrés Lima',  'action' => 'creó',        'target' => 'roadmap-producto.md',     'when' => 'hace 3 h',    'icon' => 'plus'],
    ['who' => 'Sistema',      'action' => 'archivó',     'target' => 'backup-septiembre.zip',   'when' => 'hace 5 h',    'icon' => 'clock'],
];

$team = [
    ['name' => 'Marta Ruiz',   'role' => 'Administradora', 'email' => 'marta@nimbus.test',  'initials' => 'MR', 'status' => 'Activa',   'tone' => 'green'],
    ['name' => 'Diego Salas',  'role' => 'Editor',         'email' => 'diego@nimbus.test',  'initials' => 'DS', 'status' => 'Activo',   'tone' => 'green'],
    ['name' => 'Lucía Prado',  'role' => 'Editora',        'email' => 'lucia@nimbus.test',  'initials' => 'LP', 'status' => 'Activa',   'tone' => 'green'],
    ['name' => 'Andrés Lima',  'role' => 'Lector',         'email' => 'andres@nimbus.test', 'initials' => 'AL', 'status' => 'Invitado', 'tone' => 'blue'],
];
