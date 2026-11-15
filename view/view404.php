<?php
function render404()
{
    ob_start();
    ?>

    <main class="mainNotFound">
        <h1>Page introuvable</h1>
        <p>Tu t’es perdu dans l’espace et le temps.</p>
        <a class="btnPrimary" href="/index.php">Retour à l’accueil</a>
    </main>

    <?php
    return ob_get_clean();
}
?>
