<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

// Install alongside console.php in the private dfr-spotify-worker directory.
// A plain PHP cron command avoids shell operators and environment expansion.
$domainRoot = dirname(__DIR__, 3);
if (basename(dirname(__DIR__, 2)) !== 'dfr-spotify-worker' || !is_dir($domainRoot . '/public_html/data')) {
    fwrite(STDERR, "Ce lanceur doit être installé dans le programme privé Hostinger.\n");
    exit(1);
}
echo gmdate(DATE_ATOM) . " DFR — synchronisation quotidienne\n";
$argv = [__DIR__ . '/console.php', 'sync',
    '--state-dir=' . $domainRoot . '/.dfr-spotify',
    '--catalogue-file=' . $domainRoot . '/public_html/data/releases.json', '--apply'];
require __DIR__ . '/console.php';
