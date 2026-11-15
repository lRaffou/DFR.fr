<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

function dfrInstallRead(string $path): string
{
    if (realpath($path) !== $path || !is_file($path)) {
        throw new RuntimeException('Fichier absent ou redirigé : ' . basename($path));
    }
    $contents = file_get_contents($path);
    if ($contents === false || $contents === '') {
        throw new RuntimeException('Lecture impossible : ' . basename($path));
    }
    return $contents;
}

function dfrInstallWrite(string $path, string $contents): void
{
    $directory = dirname($path);
    $staged = tempnam($directory, '.mobile-covers-');
    try {
        if ($staged === false || dirname($staged) !== $directory
            || file_put_contents($staged, $contents) !== strlen($contents)
            || !chmod($staged, 0600) || !rename($staged, $path)) {
            throw new RuntimeException('Écriture atomique impossible : ' . basename($path));
        }
    } finally {
        if ($staged !== false && is_file($staged)) { unlink($staged); }
    }
}

function dfrInstallCheck(string $worker): void
{
    $lib = $worker . '/scripts/spotify/lib.php';
    if (realpath($lib) !== $lib) { throw new RuntimeException('Bibliothèque privée absente ou redirigée.'); }
    require_once $lib;
    require_once $worker . '/scripts/spotify/catalogue.php';

    $artist = ['id' => str_repeat('a', 22), 'name' => 'Artiste de test'];
    $albumId = str_repeat('b', 22);
    $large = 'https://i.scdn.co/image/ab67616d0000b2730123456789abcdef01234567';
    $mobile = 'https://i.scdn.co/image/ab67616d00001e020123456789abcdef01234567';
    $small = 'https://i.scdn.co/image/ab67616d000048850123456789abcdef01234567';
    $album = [
        'id' => $albumId, 'name' => 'Nouvelle sortie de test', 'release_date' => '2025-06', 'artists' => [$artist],
        'images' => [
            ['url' => $large, 'width' => 640, 'height' => 640],
            ['url' => $mobile, 'width' => 300, 'height' => 300],
            ['url' => $small, 'width' => 64, 'height' => 64],
        ],
    ];
    $track = [
        'id' => str_repeat('c', 22), 'uri' => 'spotify:track:' . str_repeat('c', 22), 'album_id' => $albumId,
        'release_date' => '2025-06', 'artists' => [$artist],
    ];
    $config = ['artists' => [$artist['id'] => $artist['name']], 'excluded_album_ids' => [],
        'excluded_track_ids' => [], 'excluded_isrcs' => []];
    $result = buildReleaseCatalogue([$album], [$track], $config, null);
    $release = $result['releases'][0] ?? [];
    if (count($result['releases']) !== 1 || ($release['image'] ?? '') !== $large
        || ($release['mobile_image'] ?? '') !== $mobile) {
        throw new RuntimeException('Le programme privé ne produit pas la miniature 300 px attendue.');
    }
}

umask(0077);
$replaced = [];
$lock = null;
$status = 0;
try {
    $domain = '/home/u580307023/domains/daofamilyrecords.fr';
    $public = $domain . '/public_html';
    $worker = $domain . '/dfr-spotify-worker';
    $backups = $domain . '/.dfr-deployments';
    $backup = $backups . '/spotify-mobile-covers-20261115';
    $lockPath = $domain . '/.dfr-spotify/sync.lock';
    if (realpath(__DIR__ . '/../..') !== $public || realpath($worker) !== $worker
        || realpath($backups) !== $backups || realpath($lockPath) !== $lockPath || !is_file($lockPath)) {
        throw new RuntimeException('Chemins DFR attendus introuvables ou redirigés.');
    }
    $lock = fopen($lockPath, 'r+');
    if ($lock === false || !flock($lock, LOCK_EX | LOCK_NB)) {
        throw new RuntimeException('Verrou de synchronisation occupé ou indisponible.');
    }

    $relativePaths = ['model/release-catalogue.php', 'scripts/spotify/catalogue.php'];
    $changes = [];
    $hashes = [];
    foreach ($relativePaths as $relative) {
        $path = $worker . '/' . $relative;
        $before = dfrInstallRead($path);
        $after = dfrInstallRead($public . '/' . $relative);
        $hashes[$relative] = hash('sha256', $after);
        if ($before !== $after) { $changes[$path] = [$before, $after]; }
    }
    if ($changes) {
        if ((!is_dir($backup) && !mkdir($backup, 0700)) || realpath($backup) !== $backup || !chmod($backup, 0700)) {
            throw new RuntimeException('Dossier privé de sauvegarde indisponible.');
        }
        foreach ($changes as $path => [$before, $after]) {
            $saved = $backup . '/' . basename($path);
            if (is_link($saved)) { throw new RuntimeException('Sauvegarde redirigée.'); }
            if (!file_exists($saved)) {
                dfrInstallWrite($saved, $before);
            } else {
                dfrInstallRead($saved);
            }
        }
        foreach ($changes as $path => [$before, $after]) {
            dfrInstallWrite($path, $after);
            $replaced[$path] = $before;
        }
    }
    foreach ($hashes as $relative => $expected) {
        if (hash('sha256', dfrInstallRead($worker . '/' . $relative)) !== $expected) {
            throw new RuntimeException('Empreinte du fichier installé incorrecte : ' . $relative);
        }
    }
    dfrInstallCheck($worker);
    echo $changes ? "Programme privé installé.\n" : "Programme privé déjà à jour.\n";
    foreach ($hashes as $relative => $expected) { echo $relative . ' SHA-256 ' . $expected . "\n"; }
    echo "Test hors réseau réussi : image 640 px et miniature 300 px générées.\n";
} catch (Throwable $failure) {
    foreach (array_reverse($replaced, true) as $path => $before) {
        try {
            dfrInstallWrite($path, $before);
        } catch (Throwable $rollback) {
            fwrite(STDERR, "Restauration impossible pour " . basename($path) . ".\n");
        }
    }
    fwrite(STDERR, 'Installation arrêtée : ' . $failure->getMessage() . "\n");
    $status = 1;
} finally {
    if (is_resource($lock)) { fclose($lock); }
}
exit($status);
