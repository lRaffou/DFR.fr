<?php
require_once __DIR__ . '/../model/site.php';

function renderFooter(): string
{
    $site = dfrSite();
    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    ob_start();
    ?>
    <footer class="mainFooter">
        <div class="footerInner">
            <div class="footerGrid">
                <div class="footerIdentity">
                    <p class="footerWordmark">
                        <img src="/img/1logo_simple_dfr_blanc.png" alt="" width="193" height="327" loading="lazy" decoding="async">
                        <span>Dao Family Records</span>
                    </p>
                    <p class="footerDescription">Label de musique électronique à Toulouse, spécialisé dans la techno et la house.</p>
                    <a class="footerContact" href="<?= $escape($site['contact']) ?>">
                        <i class="fas fa-envelope" aria-hidden="true"></i> Contacter le label
                    </a>
                </div>

                <nav class="footerGroup" aria-label="Navigation du pied de page">
                    <h2>Navigation</h2>
                    <ul class="footerNavLinks">
                        <?php foreach ([...$site['navigation'], $site['booking']] as [$path, $label]): ?>
                            <li><a href="<?= $escape($path) ?>"><?= $escape($label) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <section class="footerGroup" aria-labelledby="footer-social-title">
                    <h2 id="footer-social-title">Suivez-nous</h2>
                    <ul class="footerSocialLinks">
                        <?php foreach ($site['social'] as $platform => $social): ?>
                            <li><a href="<?= $escape($social['url']) ?>" target="_blank" rel="noopener noreferrer">
                                <i class="fab <?= $escape($social['icon']) ?>" aria-hidden="true"></i>
                                <?= $escape($platform) ?>
                            </a></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            </div>

            <p class="footerCopyright">
                &copy; <?= date('Y') ?> Dao Family Records. Tous droits réservés.
            </p>
        </div>
    </footer>

    </body>
    </html>
    <?php
    return ob_get_clean();
}
?>
