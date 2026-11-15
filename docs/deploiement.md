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

## Catalogue automatique, le même jour

Le catalogue utilise maintenant la collecte Spotify quotidienne : 60 sorties
importées, fusionnées avec les anciennes fiches pour afficher 68 sorties uniques.
Les trois plus récentes alimentent l'accueil. Les autres sont paginées par 24.

Le programme privé, ses modèles partagés et les fichiers publics ont été mis à
jour via l'accès SSH temporaire autorisé. Sauvegarde avant installation :
`/home/u580307023/domains/daofamilyrecords.fr/.dfr-deployments/20261115-catalogue-01/backup-before.tar.gz`.
Cette sauvegarde contient `public_html` et le programme privé, sans les jetons.
Les paquets `catalogue-update.tar.gz` et `corrections.tar.gz` sont conservés dans
le même dossier privé et ont été contrôlés par SHA-256 avant installation.

Le contrôle final a relevé 11 fichiers de navigation, contrôleurs, vues et JS
encore différents de la version du dépôt ; ils ont été remis en cohérence avec
les modifications déjà validées. Les crédits éditoriaux des collaborations sont
préservés lors de la fusion avec Spotify.

Vérifications : 39 contrôles PHP réussis en local et sur Hostinger, syntaxe PHP/JS,
simulation sans publication, synchronisation réelle et deuxième passage sans
doublon. Cinq pages publiques en HTTP 200, 68 fiches uniques sur trois pages,
68 pochettes accessibles, accueil actualisé et affichage mobile vérifiés.

Le cron actif `qptdz2aJH9` remplace l'ancien : une seule commande quotidienne
actualise la playlist et `public_html/data/releases.json`. Voir
[la configuration Spotify](spotify-playlist.md). Conserver ce JSON lors des
futures publications : il est généré sur le serveur et ignoré par Git.
La clé temporaire `dfr-catalogue-install-2026-11-15` a été retirée et l'état SSH
`INACTIVE` a été vérifié après installation.
