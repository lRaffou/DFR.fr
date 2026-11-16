<?php
require_once __DIR__ . '/../model/site.php';

function renderFooter(): string
{
    $site = dfrSite();
    ob_start();
    ?>
    <footer class="mainFooter">
        <div class="footerContent">
            <div class="footerSection">
                <img class="footerLogo" src="/img/1logo_dfr_blanc_complet.png" alt="Dao Family Records" />
                <p class="footerDescription">Dao Family Records est un label de musique électronique basé à Toulouse,
                    spécialisé dans la techno et la house.</p>
            </div>

            <div class="footerSection">
                <h3>Navigation</h3>
                <ul class="footerLinks">
                    <?php foreach ([...$site['navigation'], $site['booking']] as [$path, $label]): ?>
                        <li><a href="<?= htmlspecialchars($path) ?>"><?= htmlspecialchars($label) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= htmlspecialchars($site['contact']) ?>">Contactez-nous</a></li>
                </ul>
            </div>

            <div class="footerSection">
                <h3>Suivez-nous</h3>
                <div class="socialLinks">
                    <?php foreach ($site['social'] as $platform => $social): ?>
                        <a href="<?= htmlspecialchars($social['url']) ?>" target="_blank" rel="noopener noreferrer" class="socialLink">
                            <i class="fab <?= htmlspecialchars($social['icon']) ?>" aria-hidden="true"></i>
                            <span><?= htmlspecialchars($platform) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="footerBottom">
            <p class="copyright">
                Copyright &copy; <?= date('Y') ?> Dao Family Records. Tous droits réservés.
            </p>
        </div>
    </footer>

    </body>
    </html>
    <?php
    return ob_get_clean();
}
?>
