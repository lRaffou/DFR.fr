<?php
function renderProjectLinks(array $links, string $name): void
{
    ?>
    <div class="dfrProjectLinks">
        <?php foreach ($links as $label => $url): ?>
            <?php $external = str_starts_with($url, 'https://'); ?>
            <a href="<?= htmlspecialchars($url) ?>"<?= $external ? ' target="_blank" rel="noopener noreferrer"' : '' ?>
                aria-label="<?= htmlspecialchars($name . ' — ' . $label) ?>"><?= htmlspecialchars($label) ?><span aria-hidden="true"><?= $external ? ' ↗' : ' →' ?></span></a>
        <?php endforeach; ?>
    </div>
    <?php
}

function renderHomeProjects(array $projects): string
{
    ob_start(); ?>
    <section class="homeSection" aria-labelledby="projects-title">
        <div class="homeSectionHeading">
            <h2 id="projects-title">Les soirées DFR</h2>
            <a href="/controller/evenement.php">Voir l’agenda <span aria-hidden="true">→</span></a>
        </div>
        <div class="dfrProjectGrid">
            <?php foreach ($projects as $id => $project): ?>
                <?php if (!isset($project['summary'])) { continue; } ?>
                <article class="dfrProject dfrProject--<?= htmlspecialchars($id) ?>" id="<?= htmlspecialchars($id) ?>">
                    <p class="dfrEyebrow"><?= htmlspecialchars($project['eyebrow']) ?></p>
                    <h3><?= htmlspecialchars($project['name']) ?></h3>
                    <p class="dfrProjectDescription"><?= htmlspecialchars($project['summary']) ?></p>
                    <?php renderProjectLinks(['Découvrir le concept' => '/controller/evenement.php#' . rawurlencode($id)], $project['name']); ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php return ob_get_clean();
}
