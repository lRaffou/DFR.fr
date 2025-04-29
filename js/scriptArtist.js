const artistData = {
    "red-marshal": {
        name: "Red Marshal",
        role: "DJ - Producteur",
        genre: "Hard Techno",
        image: "/img/Red_Marshal_1.jpg",
        bio: [
            "Thao Dao est français d'origine vietnamienne. Ses parents sont arrivés en France avant sa naissance, quittant leur pays pour offrir à leurs enfants une vie meilleure. Pour honorer le choix de ses parents, Thao vit sa vie à 100 km/heure. Très vite, il décide de se consacrer à sa véritable passion, celle qui l'a toujours animé et rendu heureux : la musique.",
            "Comme beaucoup de gens de sa génération, Thao est tombé amoureux de la musique électronique avec Daft Punk. Dès lors, il n'a eu de cesse de parfaire sa culture musicale avec The Prodigy, Justice, GESAFFELSTEIN, Laurent Garnier, Jeff Mills, Paul Kalkbrenner, Vitalic Official, REZZ. Il a une véritable passion pour la musique. C'est donc tout naturellement qu'il a commencé à composer en 2010.",
            "Pour parfaire ses sets, il s'est formé pendant deux ans à la production musicale avec Hugo Lemercier du groupe Sociopark. Son style s'affirme, ses sets se précisent et le projet prend forme : Red Marshal est né. Red Marshal produit une techno sombre et puissante, rehaussée par l'esthétique de ses clips (réalisés par son frère Yo Production). La cohérence de son univers, ses productions hypnotiques et son aisance en live font de lui la nouvelle figure techno toulousaine.",
        ],
        spotify:
            "https://open.spotify.com/embed/artist/3zpewQ11GYG2ev9Ngdsvi9?utm_source=generator",
        gallery: [
            "/img/Red_Marshal_5.jpg",
            "/img/Red_Marshal_2.jpg",
            "/img/Red_Marshal_7.jpg",
            "/img/Red_Marshal_9.jpg",
        ],
    },
    "dj-babe": {
        name: "Dj Babe",
        role: "DJ - Producteur",
        genre: "Tech House",
        image: "/img/Sophie-détouré.png",
        bio: [
            "Sophie est une artiste française d'origine vietnamienne, dont le parcours musical a été initié par l'influence de ses frères, eux-mêmes actifs dans les scènes bass house (Dao Brothers) et hard techno (Red Marshal). C'est dans cet environnement familial et musical qu'elle a forgé ses premières inspirations et aspirations artistiques.",
            "Forte de ses connaissances et des multiples influences qui l'entourent, Sophie a développé au fil des années un univers musical qui lui est propre. Forte de son expérience et de sa créativité, elle s'est lancée dans la création d'un album ambitieux, dont la sortie est prévue en 2024.",
            "Cet album, composé de 10 titres soigneusement élaborés, reflète parfaitement son style unique et son évolution en tant qu'artiste. Puissance et groove sont au cœur de cet opus, où chaque titre offre une expérience sonore captivante et immersive. De la fusion de la bass house énergique et des sons hard techno à ses propres expérimentations musicales, Sophie propose un voyage auditif dynamique et enivrant dans son univers musical.",
        ],
        spotify:
            "https://open.spotify.com/embed/artist/7o0ucOb1npEkb3qhGFIY3U?utm_source=generator",
        gallery: [
            "/img/Sophie-détouré.png",
            "/img/Japanese_temple.png",
            "/img/DJ_BABE_Event_2.png",
            "/img/Sophie-détouré.png",
        ],
    },
    fernando: {
        name: "Fernando Gomez",
        role: "DJ - Producteur",
        genre: "Techno",
        image: "/img/Fernando-détouré.png",
        bio: [
            "Fernando d'origine espagnol est passionné de musique et de show lumière (Jean-Michel Jarre) depuis son plus jeune âge, il est un fervent amateur de piano et de guitare. À l'adolescence, il décide de se concentrer sur ses styles musicaux de prédilection : le métal et le hard métal.",
            "Durant ses études dans le Nord de la France, ses goûts musicaux évoluent petit à petit vers le hardcore, la hard techno et le hardstyle. À la fin de sa formation, il déménage à Toulouse pour travailler et découvrir le monde de la nuit. Il devient alors technicien son et lumière pour différentes boîtes de nuit de la ville rose.",
            "Lorsque le Covid arrive, il y voit une opportunité pour se mettre au mix. Pour mixer de la techno, il s'inspire de Space 92, Jeff Mills, Carl Cox, Charlotte Witte ...",
        ],
        spotify:
            "https://open.spotify.com/embed/artist/51UZk9XPK15uZgwQEWE3MK?utm_source=generator",
        gallery: [
            "/img/Fernando-détouré.png",
            "/img/Darkness_fernando.png",
            "/img/Imagination_Fernando.png",
            "/img/Fernando-détouré.png",
        ],
    },
    "dao-brothers": {
        name: "Dao Brothers",
        role: "DJ - Producteur",
        genre: "Bass House",
        image: "/img/Brothers-détouré.png",
        bio: [
            'Les frères Dao sont des artistes qui produisent de la Bass House, une musique émergente propagée par les artistes français « Malaa " et " DJ SNAKE ». Composé des frères Thao et Yo (nés en 1991 et 1990), le duo français d\'origine vietnamienne se produit à Toulouse.',
            "Dans leur enfance, ils se passionnent pour la musique et le cinéma. Ils se sont rapidement formés et spécialisés dans ces deux domaines. Le style musical de Thao est très sombre avec des basses puissantes, tandis que celui de Yo est groovy et rythmé. Ces différences forment un mélange de sons innovant.",
        ],
        spotify:
            "https://open.spotify.com/embed/artist/4FP9ILC4hO3YvOb2R0NIzj?utm_source=generator",
        gallery: [
            "/img/Photo_dao_yo.jpg",
            "/img/Photo_dao_ yo_thao.jpg",
            "/img/meilleur.jpg",
            "/img/Photo_dao_thao.jpg",
        ],
    },
};

// Gestion ouverture et remplissage modale artistes
document.addEventListener("DOMContentLoaded", function () {
    const artistCards = document.querySelectorAll(".artistCard");
    const modal = document.getElementById("artistModal");
    const closeModal = document.querySelector(".closeModal");
    const artistDetailsContainer = document.getElementById("artistDetailsContainer");

    function createArtistDetails(artistId) {
        const artist = artistData[artistId];
        return `
            <div class="artistDetails">
                <div class="artistHeader">
                    <div class="artistProfile">
                        <img src="${artist.image}" alt="${artist.name}">
                        <div class="artistTitle">
                            <h2>${artist.name}</h2>
                            <p>${artist.role}</p>
                            <span class="artistGenre">${artist.genre}</span>
                        </div>
                    </div>
                </div>

                <div class="artistBio">
                    <h3>Biographie</h3>
                    ${artist.bio.map(p => `<p>${p}</p>`).join('')}
                </div>

                <div class="artistMusic">
                    <h3>Écouter</h3>
                    <iframe style="border-radius:12px" src="${artist.spotify}" width="100%" height="152" frameborder="0" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                </div>

                <div class="artistGallery">
                    <h3>Galerie</h3>
                    <div class="galleryGrid">
                        ${artist.gallery.map(img => `<div class="galleryItem"><img src="${img}" alt="${artist.name}"></div>`).join('')}
                    </div>
                </div>
            </div>
        `;
    }

    artistCards.forEach(card => {
        card.addEventListener("click", function () {
            const artistId = this.getAttribute("data-artist");
            artistDetailsContainer.innerHTML = createArtistDetails(artistId);
            modal.style.display = "block";
        });
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});
