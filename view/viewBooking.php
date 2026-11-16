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
    <main class="mainBooking editorialPage">
        <header class="editorialHeader" aria-labelledby="booking-title">
            <h1 id="booking-title">Booking &amp; Presse</h1>
            <p>Contacts, biographies et visuels des artistes de Dao Family Records.</p>
        </header>

        <div class="bookingOverview">
            <section class="bookingContact" aria-labelledby="booking-contact">
                <h2 id="booking-contact">Contact du label</h2>
                <a class="bookingAction" href="<?= $escape($labelMail) ?>">
                    <i class="fas fa-envelope" aria-hidden="true"></i> Contacter le label
                </a>
            </section>
            <section class="bookingNote" aria-labelledby="booking-request">
                <h2 id="booking-request">Préparer votre demande</h2>
                <p><strong>Programmation</strong> · Indiquez l’artiste, la date, la ville et le lieu.</p>
                <p><strong>Presse</strong> · Demandez au label les visuels en haute définition et leurs crédits.</p>
            </section>
        </div>

        <section aria-labelledby="booking-artists">
            <div class="editorialSectionHeading">
                <h2 id="booking-artists">Contacts artistes</h2>
            </div>
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
                            <a class="bookingAction" href="<?= $escape($bookingMail) ?>" aria-label="<?= $escape('Booking — ' . $name) ?>"><i class="fas fa-envelope" aria-hidden="true"></i> Booking</a>
                            <?php if ($pressImage !== null): ?>
                                <a class="bookingTextLink" href="<?= $escape($pressImage) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= $escape('Voir le visuel — ' . $name) ?>">Voir le visuel <span aria-hidden="true">↗</span></a>
                            <?php endif; ?>
                            <a class="bookingTextLink" href="<?= $escape($profile) ?>" aria-label="<?= $escape('Fiche complète — ' . $name) ?>">Fiche complète <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php
    return ob_get_clean();
}
