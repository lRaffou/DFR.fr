<?php
require_once __DIR__ . '/release-catalogue.php';
$seed = require __DIR__ . '/releases.seed.php';
return dfrMergeReleases($seed, dfrReadCatalogue(__DIR__ . '/../data/releases.json'));
