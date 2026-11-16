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
    <section class="dfrProjects" aria-labelledby="projects-title">
        <div class="dfrSectionHeading">
            <p class="dfrEyebrow">Une famille, plusieurs rendez-vous</p>
            <h2 id="projects-title">L’univers DFR</h2>
        </div>
        <div class="dfrProjectGrid">
            <?php foreach ($projects as $id => $project): ?>
                <?php $links = isset($project['summary'])
                    ? ['Découvrir le concept' => '/controller/evenement.php#' . rawurlencode($id)]
                    : $project['links']; ?>
                <article class="dfrProject dfrProject--<?= htmlspecialchars($id) ?>" id="<?= htmlspecialchars($id) ?>">
                    <p class="dfrEyebrow"><?= htmlspecialchars($project['eyebrow']) ?></p>
                    <h3><?= htmlspecialchars($project['name']) ?></h3>
                    <p class="dfrProjectDescription"><?= htmlspecialchars($project['summary'] ?? $project['description']) ?></p>
                    <?php renderProjectLinks($links, $project['name']); ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php return ob_get_clean();
}
