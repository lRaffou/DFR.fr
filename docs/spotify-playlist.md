# Playlist et catalogue DFR automatiques

La playlist existante `4z6XsMTJ8rsbSGyi20bz9T` reste intégrée au site. Le script CLI
ajoute les sorties des six profils confirmés dans `scripts/spotify/config.php`,
y compris les collaborations. Aucun ajout manuel au site n'est nécessaire.
Clarity sera intégrée quand un profil Spotify aura été confirmé.

Avec `--catalogue-file`, cette même collecte publie aussi les singles, EP et albums
sur le site. Une fiche est créée par identifiant d'album Spotify, collaborations
comprises. « À la une », sur l'accueil et la page Sorties, sélectionne dans cet
ordre la dernière sortie solo de Red Marshal, la dernière collaboration et la
dernière sortie d'un autre artiste. Chaque catégorie se renouvelle automatiquement.
Frenzysax, duo formé par Clarisax et Red Marshal selon le propriétaire, occupe la
catégorie collaboration même lorsque Spotify ne crédite que son profil. Cette règle est appliquée
à l’affichage et reste valable après chaque actualisation du catalogue.
Les autres sorties restent triées par date, par pages de 24. Les dates partielles fournies
par Spotify restent partielles, sans jour inventé.

Le fichier public `data/releases.json` ne contient que les titres, artistes,
dates, liens et URL des pochettes. Il est remplacé atomiquement après une collecte
réussie, indépendamment des ajouts à la playlist. Un échec de la collecte conserve la dernière version.
Si le fichier manque ou est corrompu, les fiches éditoriales de
`model/releases.seed.php` restent affichées. Les fiches historiques sont conservées
si Spotify cesse de les renvoyer, avec le lien « Voir sur Spotify ».
Les pochettes locales et les crédits éditoriaux (featurings confirmés) des fiches
initiales sont conservés lors de la fusion. Les nouvelles fiches incluent les
artistes crédités sur l'album et sur ses morceaux concernés.
Le JSON généré est ignoré par Git : il doit être conservé lors des déploiements.

Le script parcourt albums, singles, compilations et participations. Le catalogue
utilise aussi les sorties des morceaux présents dans la playlist DFR, même lorsque
Spotify les omet des discographies. Les deux sources fusionnent par identifiant
Spotify de la sortie. Les profils actifs et l’ancien profil confirmé de DJ Babe
sont reconnus par leur identifiant. Le script ne garde que les morceaux créditant
au moins un artiste configuré. Il exclut les morceaux
signalés indisponibles pour le marché demandé (le pays du compte Spotify prévaut),
les sorties futures et les exclusions explicites. Ce filtre porte sur les artistes,
pas sur le label de distribution : un titre publié ailleurs peut donc être inclus.

Les doublons sont repérés par URI et ISRC, lorsqu'il est fourni par Spotify.
Les nouveaux titres sont insérés en tête, par date de sortie décroissante. Les
titres existants ne sont ni supprimés ni réordonnés. Les métadonnées des albums
sont mises en cache sept jours ; un nouvel album est traité dès sa découverte.
Un titre redevenu disponible sur un album déjà connu peut attendre ce délai.

## Connexion initiale

Prérequis : PHP 8.1+ avec cURL, compte Spotify propriétaire Premium, application
Web API dans [Spotify Developer Dashboard](https://developer.spotify.com/dashboard).
Application prévue : **DFR Playlist Sync** ; URI de retour exacte :
`http://127.0.0.1:8765/callback`.

L'authentification PKCE n'utilise pas de client secret. Elle demande seulement
`playlist-modify-public`. Spotify ne propose pas de permission limitée à une
seule playlist ; le code vérifie le compte et cible uniquement l'identifiant DFR.
Ne jamais copier un jeton dans Git, dans une page publique ou dans une conversation.

Depuis le PC, en remplaçant les valeurs et en gardant le dossier privé hors du site :

```powershell
& 'C:\Program Files\PHP\php.exe' -d 'extension_dir=C:\Program Files\PHP\ext' -d extension=curl scripts/spotify/console.php authorize --client-id=CLIENT_ID --state-dir=C:\CHEMIN_PRIVE\dfr-spotify
```

Ouvrir le lien affiché et autoriser le compte propriétaire. Le serveur de retour
écoute uniquement `127.0.0.1`, expire après dix minutes et vérifie l'état OAuth.
Le fichier privé `credentials.json` contient les jetons renouvelables. Le script
ne lit aucun mot de passe et n'affiche jamais les jetons.

## Installation Hostinger

Hostinger déploie le programme `scripts/spotify/` avec le site depuis GitHub.
Le serveur bloque ce dossier par HTTP dans `.htaccess` et les scripts exigent
PHP en ligne de commande. Les données privées restent dans
`/home/u580307023/domains/daofamilyrecords.fr/.dfr-spotify`, hors de `public_html`
(droits 0700).
Transférer `credentials.json` par SFTP ou le gestionnaire de fichiers sécurisé de
Hostinger dans ce dossier (droits 0600). Ne pas utiliser une URL publique de transfert.

Créer `public_html/data/` en 0755 et y installer `data/.htaccess`. Installer les
modèles, vues et CSS du site avant la première publication du catalogue.
Pour une simulation, lancer la commande suivante et vérifier la liste proposée.

```sh
/usr/bin/php /home/u580307023/domains/daofamilyrecords.fr/public_html/scripts/spotify/hostinger.php --dry-run
```

La tâche quotidienne Hostinger à **18:00 UTC** (19 h à Paris en hiver, 20 h en
été) utilise l’expression `0 18 * * *` et la commande suivante.

```sh
/usr/bin/php /home/u580307023/domains/daofamilyrecords.fr/public_html/scripts/spotify/hostinger.php
```

Cette commande reste sous la limite de 255 caractères de Hostinger, sans opérateur
shell ni variable à développer. Le lanceur retrouve le dossier privé à partir de
son emplacement. Le fichier privé
`last-success.json` donne le dernier succès. La sortie du dernier passage est
consultable dans hPanel, Tâches Cron, ou via l'API Hostinger `hosting_cron-jobs_output`.
En cas de révocation ou d'expiration de l'abonnement Premium, reconnecter le compte.
Spotify impose également une nouvelle autorisation tous les six mois ; le
renouvellement automatique des jetons d'accès ne prolonge pas cette durée.
À réception de `invalid_grant`, le script efface les jetons expirés et s'arrête
avec un message invitant à reconnecter le compte, sans répétition de la demande.
En cas de quota Spotify, le délai est conservé dans `rate-limit.json` hors du site.
Aucune requête n’est envoyée pendant la pause ; la tâche suivante recalculera
les ajouts. Une écriture dont le résultat est incertain n'est jamais répétée dans
la même exécution. Un verrou empêche deux lancements locaux simultanés ; une
modification manuelle pendant le travail provoque un arrêt si le snapshot change.

## Exclure un morceau et tester

Ajouter les IDs Spotify dans `excluded_track_ids`/`excluded_album_ids`, ou les
ISRC dans `excluded_isrcs`. Ces listes bloquent les ajouts futurs ; elles ne retirent
pas les titres déjà présents. Sans exclusion, un titre supprimé manuellement de
la playlist peut être ajouté à nouveau lors du passage suivant.
Pour retirer une fiche du site, utiliser `excluded_album_ids` : cette exclusion
s'applique également aux fiches historiques et manuelles lors du prochain succès.
Une exclusion de morceau ou d'ISRC seule ne supprime pas une fiche historique.

```sh
php scripts/spotify/tests.php
```

Tests hors réseau avec fausses réponses Spotify, publication atomique, fusion,
exclusions, dates, pagination et conservation de la dernière version. La simulation réelle nécessite
la connexion initiale. Toutes les entrées PHP de ce dossier refusent les requêtes
HTTP, y compris avec le serveur PHP local qui ne lit pas `.htaccess`.

Références : [OAuth PKCE](https://developer.spotify.com/documentation/web-api/tutorials/code-pkce-flow),
[API des playlists](https://developer.spotify.com/documentation/web-api/reference/add-items-to-playlist),
[expiration après six mois](https://developer.spotify.com/blog/2026-06-18-refresh-token-expiration),
[tâches Hostinger](https://www.hostinger.com/support/1583465-how-to-set-up-a-cron-job-at-hostinger/).

## Mise en service du 15 novembre 2026

- Application Spotify : `DFR Playlist Sync`, Client ID public `8fc7dc15dc6c415697840e525e2bb072`.
- Import initial : 57 titres ajoutés aux 24 existants, soit 81 titres.
- Sur Hostinger : tests PHP, simulation et synchronisation réelle réussis ; le
  second passage ne propose aucun ajout. Renouvellement du jeton testé sur le serveur.
- Programme et jetons hors de `public_html`, dossiers 0700, fichiers PHP et jetons 0600.
- Cron quotidien `19qnu47Khs` : `0 5 * * *` (05:00 UTC, 06:00 Paris en hiver,
  07:00 en été). La première exécution quotidienne est prévue le 16 novembre.
- Les modifications visuelles ont été publiées séparément le même jour ; voir
  [le compte rendu de publication](deploiement.md). La playlist publique intégrée
  bénéficie directement des ajouts.
- Accès SSH temporaire refermé après vérification, clé d'installation retirée et
  copie locale des jetons supprimée. La tâche ne dépend pas du PC ni de SSH.
- Une nouvelle autorisation Spotify sera nécessaire vers mai 2027. Refaire
  `authorize` et transférer le nouveau fichier privé, sans le publier dans Git.

## Extension au catalogue le 15 novembre 2026

- Ancien cron : `qptdz2aJH9`, même horaire `0 5 * * *`.
  Il remplace `19qnu47Khs` ; une seule tâche met à jour playlist et catalogue.
- Premier import : 60 sorties Spotify et 8 anciennes fiches conservées, soit
  68 fiches uniques réparties sur 3 pages. La playlist reste à 81 titres.
- Simulation sans publication, synchronisation réelle et répétition sans doublon
  vérifiées sur Hostinger ; 39 contrôles hors réseau réussis.
- Accueil, pagination, crédits des collaborations et 68 pochettes vérifiés en ligne.
- Les fichiers privés et les jetons restent hors de la racine publique ; le JSON
  généré (0644) ne contient que les métadonnées publiques.

## Frenzysax et tâche quotidienne actuelle

- Frenzysax (`4NqtEeAINDNe0G3FiHbqbs`) figure dans le dépôt et la configuration
  privée. `Live The Moment` est présent une seule fois dans la playlist : 82 titres.
- Le catalogue contient 61 sorties automatiques et 8 fiches historiques, soit 69.
- Seul cron actif : `qlZINi8abO`, `0 18 * * *`, avec le lanceur privé
  `hostinger.php` ci-dessus. Il remplace `qptdz2aJH9`.
- Déclenchement réel du lanceur observé le 15 novembre 2026 à 19:19 UTC ; la pause
  Spotify est respectée sans appel réseau. La tâche de test a ensuite été retirée.
- Après le quota reçu pendant l’installation, le programme attend le 16 novembre
  à 17h UTC au minimum. Le prochain passage est prévu le 16 novembre à 18h UTC
  (19h à Paris). Les 47 tests hors réseau passent en local et sur Hostinger.
- Voir [le compte rendu](deploiement.md) pour la sauvegarde et l’import initial.
