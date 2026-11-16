<?php
function renderProjects(array $projects, bool $eventsOnly = false): string
{
    if ($eventsOnly) { unset($projects['dfr']); }
    ob_start(); ?>
    <section class="dfrProjects" aria-labelledby="projects-title">
        <div class="dfrSectionHeading">
            <p class="dfrEyebrow">Une famille, plusieurs rendez-vous</p>
            <h2 id="projects-title"><?= $eventsOnly ? 'Nos concepts' : 'L’univers DFR' ?></h2>
        </div>
        <div class="dfrProjectGrid">
            <?php foreach ($projects as $id => $project): ?>
                <article class="dfrProject dfrProject--<?= htmlspecialchars($id) ?>" id="<?= htmlspecialchars($id) ?>">
                    <p class="dfrEyebrow"><?= htmlspecialchars($project['eyebrow']) ?></p>
                    <h3><?= htmlspecialchars($project['name']) ?></h3>
                    <p class="dfrProjectDescription"><?= htmlspecialchars($project['description']) ?></p>
                    <div class="dfrProjectLinks">
                        <?php foreach ($project['links'] as $label => $url): ?>
                            <a href="<?= htmlspecialchars($url) ?>" <?= str_starts_with($url, 'https://') ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
                                aria-label="<?= htmlspecialchars($project['name'] . ' — ' . $label) ?>"><?= htmlspecialchars($label) ?><span aria-hidden="true"> ↗</span></a>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php return ob_get_clean();
}
