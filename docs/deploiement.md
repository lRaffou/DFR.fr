# Publication du 15 novembre 2026

Site : https://daofamilyrecords.fr/ — hébergement Hostinger, compte `u580307023`.

Les 21 fichiers publics modifiés ont été publiés : accueil, événements, artistes,
catalogue, vidéos, navigation, CSS, JavaScript, modèles et cinq nouvelles pochettes.
Les ressources CSS/JavaScript portent une version issue de leur date de modification
pour renouveler le cache des visiteurs.

L'API `hosting_deploy` a échoué deux fois avec une erreur 500. Publication effectuée
par SSH/SFTP après accord, avec vérification de l'empreinte du serveur, transfert
contrôlé par SHA-256 et remplacement atomique de chaque fichier. Le paquet publié
est référencé dans `.deploy/release/manifest.txt` (dossier local ignoré par Git).

Sauvegarde complète avant publication, hors de la racine publique :
`/home/u580307023/domains/daofamilyrecords.fr/.dfr-deployments/20261115-publish-01/backup-before.tar.gz`.
Le même dossier contient le paquet publié et la date de publication.

Vérifications : syntaxe PHP/JavaScript, cinq pages en HTTP 200 sans erreur PHP,
contenu attendu (dont Red Rave et Red Night archivée), 25 ressources publiques
accessibles, empreintes des trois fichiers CSS/JavaScript identiques aux fichiers
locaux, contrôle visuel du catalogue en production.

La tâche Spotify `19qnu47Khs` reste configurée à `0 5 * * *`, avec programme et
jetons hors de `public_html`. La clé SSH temporaire est retirée après publication
et SSH est désactivé. Le dépôt Git contient le code publié, le programme de
synchronisation Spotify et sa documentation ; les jetons et sauvegardes restent
hors du dépôt.
