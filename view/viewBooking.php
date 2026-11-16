<?php
require_once __DIR__ . '/../model/site.php';
require_once __DIR__ . '/../model/seo.php';

function renderBooking(): string
{
    $artists = require __DIR__ . '/../model/artists.php';
    $site = dfrSite();
    $escape = static fn(string $text): string => htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $contact = $site['contact'];
    $labelMail = $contact . '?subject=' . rawurlencode('Booking ou presse — Dao Family Records');
    ob_start();
    ?>
    <main class="mainBooking">
        <section class="bookingHero" aria-labelledby="booking-title">
            <h1 id="booking-title">Booking &amp; Presse</h1>
            <p>Programmez un artiste DFR ou retrouvez les ressources pour préparer votre annonce.</p>
            <a class="bookingAction" href="<?= $escape($labelMail) ?>">
                <i class="fas fa-envelope" aria-hidden="true"></i> Contacter le label
            </a>
        </section>

        <section aria-labelledby="booking-artists">
            <h2 id="booking-artists">Contacts artistes</h2>
            <div class="bookingGrid">
                <?php foreach ($artists as $id => $artist): ?>
                    <?php
                    $name = $artist['name'];
                    $artistContact = isset($artist['booking']) ? 'mailto:' . $artist['booking'] : $contact;
                    $bookingMail = $artistContact . '?subject=' . rawurlencode('Booking — ' . $name);
                    $profile = dfrArtistPath($id, $artist);
                    $pressImage = $artist['card_image']['desktop']['src'] ?? null;
                    if ($pressImage === null) {
                        foreach ($artist['gallery'] as $photo) {
                            if (str_starts_with($photo['src'], '/img/')) {
                                $pressImage = $photo['src'];
                                break;
                            }
                        }
                    }
                    ?>
                    <article class="bookingCard" data-artist="<?= $escape($id) ?>" aria-labelledby="booking-<?= $escape($id) ?>">
                        <div class="bookingCardTop">
                            <div class="bookingPortrait">
                                <img src="<?= $escape($artist['image']) ?>" alt="" width="100" height="100" loading="lazy" decoding="async">
                            </div>
                            <div>
                                <h3 id="booking-<?= $escape($id) ?>"><?= $escape($name) ?></h3>
                                <p class="bookingBio"><?= $escape($artist['press_bio']) ?></p>
                            </div>
                        </div>
                        <div class="bookingLinks">
                            <a class="bookingAction" href="<?= $escape($bookingMail) ?>" aria-label="<?= $escape('Booking — ' . $name) ?>">Booking</a>
                            <?php if ($pressImage !== null): ?>
                                <a class="bookingTextLink" href="<?= $escape($pressImage) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= $escape('Voir le visuel — ' . $name) ?>">Voir le visuel</a>
                            <?php endif; ?>
                            <a class="bookingTextLink" href="<?= $escape($profile) ?>" aria-label="<?= $escape('Fiche complète — ' . $name) ?>">Fiche complète</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="bookingNote" aria-labelledby="booking-request">
            <h2 id="booking-request">Préparer votre demande</h2>
            <p>Pour une programmation, indiquez le nom de l’artiste, la date, la ville et le lieu. Pour une annonce, contactez le label pour les visuels en haute définition et les crédits.</p>
        </section>
    </main>
    <?php
    return ob_get_clean();
}
