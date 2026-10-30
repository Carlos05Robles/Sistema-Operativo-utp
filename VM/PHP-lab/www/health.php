<?php
declare(strict_types=1);

/**
 * Endpoint de salud para el laboratorio.
 *
 * Mantiene la salida que esperan los scripts (`ok pid=... peak=... limit=...`)
 * sin mezclarla con la interfaz del portal.
 */

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');
header('X-Powered-By: PHP/' . PHP_VERSION);

echo 'ok pid=' . getmypid()
   . ' peak=' . memory_get_peak_usage(true)
   . ' limit=' . ini_get('memory_limit')
   . "\n";
