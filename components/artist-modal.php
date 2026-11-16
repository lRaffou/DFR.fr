<?php
function renderArtistModal(array $artists): void
{
    $profiles = array_map(static function (array $artist): array {
        unset($artist['slug'], $artist['card_image'], $artist['press_bio']);
        return $artist;
    }, $artists);
    ?>
    <div id="artistModal" class="artistModal" role="dialog" aria-modal="true" aria-label="Fiche artiste">
        <div class="modalContent">
            <div class="artistModalBar">
                <button type="button" class="closeModal" aria-label="Fermer la fiche artiste">&times;</button>
            </div>
            <div id="artistDetailsContainer"></div>
        </div>
    </div>
    <script type="application/json" id="artistData"><?= json_encode($profiles, JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php
}
