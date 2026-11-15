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
La sélection de l'accueil a ensuite été ajustée : une sortie de Red Marshal,
une collaboration et un autre artiste, avec la plus récente de chaque catégorie.
Les autres sorties sont paginées par 24, toujours par date décroissante.

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

## Sélection équilibrée à la une

La sélection réserve trois places : dernière sortie solo de Red Marshal, dernière
collaboration, dernière sortie d'un autre artiste. Elle s'applique à l'accueil
et à la page Sorties. Les autres fiches restent présentes, sans doublon.

Le journal de déploiement Git Hostinger a révélé un échec de `pull` sur les
fichiers ajoutés manuellement (modèles, pochettes et `data/.htaccess`). Ce processus
remettait les fichiers suivis dans leur ancienne version avant d'échouer.
La remise en cohérence utilise une sauvegarde privée du répertoire public,
puis aligne sa copie Git sur le commit publié. Le JSON généré reste conservé.

Réparation effectuée sur le commit `a89a2b1`, avec sauvegarde privée :
`/home/u580307023/domains/daofamilyrecords.fr/.dfr-deployments/20261115-featured-git-fix/backup-before.tar.gz`.
Les deux anciens ZIP de publication ont été déplacés dans ce même dossier.
La copie serveur n'a plus de modification locale ni de fichier non suivi bloquant.
Les 42 contrôles PHP passent sur le serveur ; les 68 fiches et le JSON généré
sont conservés. La copie privée du modèle et ses tests ont aussi été actualisés.
Un nouvel envoi GitHub (`8aa89f2`) est ensuite arrivé automatiquement sur Hostinger,
confirmant la réparation. Les outils CLI et les documents d'exploitation sont
bloqués par la règle du `.htaccess` racine lors des déploiements depuis le dépôt.

## Univers DFR, archives et Frenzysax

Ajout d’une présentation commune du label, de The Red Night et de Cœur Meurtri,
avec leurs liens Instagram, Shotgun et TikTok. Six éditions Shotgun complètent
l’archive Red Night Off #5 : sept fiches avec affiches officielles, dates, lieux
et programmations. Leur classement passé/à venir dépend de la date de fin.
La fiche Frenzysax utilise le portrait Spotify et les profils fournis.

Contrôles locaux : 25 vérifications du calendrier et des archives, 42 contrôles
Spotify, syntaxe PHP/JS, cinq pages HTTP 200 et 23 ressources accessibles.
Le navigateur connecté est indisponible au moment de cette vérification ; la
vérification visuelle et les publications Instagram restent à reprendre.

Frenzysax est ajouté à `scripts/spotify/config.php` dans le dépôt et dans la copie
privée Hostinger. Le déploiement Git ne met pas à jour cette copie privée.
Voir [les sources et limites éditoriales](sources-projets.md).

## Import Frenzysax terminé

Simulation réussie sur Hostinger : 81 titres présents, un ajout prévu (`Live The
Moment`, 6 novembre 2026) et 61 sorties automatiques. L’application a ensuite
reçu un HTTP 429, avec 77502 secondes d’attente. Aucun ajout via cette application
n’a été tenté après ce refus.

Le titre a été ajouté par le connecteur Spotify. Relecture complète de la playlist
en deux pages : 82 titres, avec une seule occurrence de Frenzysax. Sa fiche a été
publiée depuis le cache frais de la simulation, sans nouvel appel réseau, en
conservant les 60 autres sorties : 61 automatiques, 69 avec les archives manuelles.
La sélection à la une reste équilibrée : Red Marshal / collaboration / Frenzysax.

Le programme mémorise désormais les délais `Retry-After` dans le dossier privé
et suspend ses appels, y compris le renouvellement du jeton, pendant la pause.
47 contrôles hors réseau réussissent en local et sur le serveur. Pour ce quota,
la reprise est autorisée à partir du 16 novembre 2026 à 17h UTC, avec une marge.

Le lanceur privé `scripts/spotify/hostinger.php` remplace les opérateurs shell
dans la commande Cron. Son déclenchement par Hostinger a été réellement observé
le 15 novembre à 19:19 UTC : il a signalé la pause sans requête API. Le test
temporaire a été supprimé. La seule tâche DFR active est `qlZINi8abO`, à `0 18 * * *`
(19h à Paris en hiver, 20h en été). Premier passage après le quota : 16 novembre
à 19h Paris. Elle remplace `qptdz2aJH9`.

Sauvegarde du catalogue précédent et du programme modifié dans
`.dfr-deployments/20261115-frenzysax-sync`, hors du site public. Les jetons restent
privés ; seuls des identifiants publics et métadonnées musicales sont publiés.

La clé `dfr-frenzysax-sync-2026-11-15` a été retirée, le statut SSH `INACTIVE`
a été vérifié dans Hostinger et les trois fichiers de la clé temporaire locale
ont été supprimés. La synchronisation quotidienne fonctionne sans accès SSH.
