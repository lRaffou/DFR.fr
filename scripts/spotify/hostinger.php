<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

$domainRoot = dirname(__DIR__, 3);
$installRoot = basename(dirname(__DIR__, 2));
if (!in_array($installRoot, ['public_html', 'dfr-spotify-worker'], true) || !is_dir($domainRoot . '/public_html/data')) {
    fwrite(STDERR, "Ce lanceur doit être installé dans le site DFR sur Hostinger.\n");
    exit(1);
}
$arguments = array_slice($argv, 1);
if ($arguments !== [] && $arguments !== ['--dry-run']) {
    fwrite(STDERR, "Option inconnue. Utiliser --dry-run pour une simulation.\n");
    exit(1);
}
echo gmdate(DATE_ATOM) . " DFR — synchronisation quotidienne\n";
$argv = [__DIR__ . '/console.php', 'sync',
    '--state-dir=' . $domainRoot . '/.dfr-spotify',
    '--catalogue-file=' . $domainRoot . '/public_html/data/releases.json'];
if ($arguments === []) { $argv[] = '--apply'; }
require __DIR__ . '/console.php';
