// Gestion ouverture et remplissage modale artistes
document.addEventListener("DOMContentLoaded", function () {
    const artistCards = document.querySelectorAll(".artistCard");
    const modal = document.getElementById("artistModal");
    const closeModal = modal?.querySelector(".closeModal");
    const artistDetailsContainer = document.getElementById(
        "artistDetailsContainer"
    );
    const artistDataElement = document.getElementById("artistData");

    if (!modal || !closeModal || !artistDetailsContainer || !artistDataElement) return;

    const artistData = JSON.parse(artistDataElement.textContent);
    const htmlEntities = {"&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"};
    const escapeHtml = (value) => value.replace(/[&<>"']/g, (character) => htmlEntities[character]);
    const sharePage = "https://daofamilyrecords.fr/controller/artiste.php";
    const slugForCard = (card) => new URL(card.href).searchParams.get("artiste");
    const cardsBySlug = new Map(Array.from(artistCards, (card) => [slugForCard(card), card]));
    let activeCard = null;

    function hideArtistModal() {
        if (!activeCard) return;
        modal.style.display = "none";
        document.body.classList.remove("modalOpen");
        artistDetailsContainer.innerHTML = "";
        const previousCard = activeCard;
        activeCard = null;
        previousCard.focus();
    }

    function closeArtistModal() {
        if (!activeCard) return;
        if (history.state?.dfrArtistModal === true) {
            hideArtistModal();
            history.back();
        } else {
            const url = new URL(window.location.href);
            url.searchParams.delete("artiste");
            history.replaceState(history.state, "", url);
            hideArtistModal();
        }
    }

    function showArtistModal(card) {
        const artistId = card.dataset.artist;
        activeCard = card;
        artistDetailsContainer.innerHTML = createArtistDetails(artistId);
        modal.style.display = "block";
        modal.scrollTop = 0;
        document.body.classList.add("modalOpen");
        closeModal.focus({preventScroll: true});

        const shareUrl = new URL(card.getAttribute("href"), sharePage).href;
        const copyButton = modal.querySelector(".artistShareButton");
        const shareInput = modal.querySelector(".artistShareInput");
        const shareStatus = modal.querySelector(".artistShareStatus");
        copyButton.addEventListener("click", async () => {
            try {
                await navigator.clipboard.writeText(shareUrl);
                shareStatus.textContent = "Lien copié.";
            } catch {
                shareInput.value = shareUrl;
                shareInput.hidden = false;
                shareInput.focus();
                shareInput.select();
                shareStatus.textContent = "Sélectionne et copie le lien.";
            }
        });
    }

    function syncArtistModalFromUrl() {
        const slug = new URL(window.location.href).searchParams.get("artiste");
        const card = cardsBySlug.get(slug);
        if (card) {
            showArtistModal(card);
        } else {
            hideArtistModal();
        }
    }

    function createArtistDetails(artistId) {
        const artist = artistData[artistId];
        const externalLinks = (links) => links.map(({label, href}) =>
            `<a href="${escapeHtml(href)}" target="_blank" rel="noopener noreferrer">${escapeHtml(label)}</a>`
        ).join("");
        const name = escapeHtml(artist.name);
        return `
            <div class="artistDetails" data-artist="${escapeHtml(artistId)}">
                <div class="artistHeader">
                    <div class="artistProfile">
                        <div class="artistPortrait">
                            <img src="${escapeHtml(artist.image)}" alt="${name}">
                        </div>
                        <div class="artistTitle">
                            <h2>${name}</h2>
                            <p>${escapeHtml(artist.role)}</p>
                            <span class="artistGenre">${escapeHtml(artist.genres.join(" / "))}</span>
                            <div class="social_artist">
                                ${artist.social_youtube ? `<a href="${escapeHtml(artist.social_youtube)}" class="socialLink" target="_blank" rel="noopener noreferrer" aria-label="${name} sur YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>` : ""}
                                ${
                                    artist.social_spotify
                                        ? `
                                    <a href="${escapeHtml(artist.social_spotify)}"
                                    class="socialLink" target="_blank" rel="noopener noreferrer" aria-label="${name} sur Spotify"><i class="fab fa-spotify"></i></a>
                                `
                                        : ""
                                }
                                <a href="${escapeHtml(artist.social_instagram)}"
                                class="socialLink" target="_blank" rel="noopener noreferrer" aria-label="${name} sur Instagram"><i class="fab fa-instagram"></i></a>
                            </div>
                            <div class="artistShare">
                                <button type="button" class="artistShareButton">Copier le lien</button>
                                <span class="artistShareStatus" role="status"></span>
                                <input class="artistShareInput" type="text" inputmode="url" readonly hidden aria-label="Lien direct de la fiche">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="artistBio">
                    <h3>Biographie</h3>
                    ${artist.bio.map((p) => `<p>${escapeHtml(p)}</p>`).join("")}
                </div>

                ${artist.spotify || artist.release ? `
                    <div class="artistMusic">
                        <h3>Écouter</h3>
                        ${artist.release ? `
                        <div class="artistRelease">
                            <img src="${escapeHtml(artist.release.cover)}" alt="Pochette de ${escapeHtml(artist.release.title)}" width="320" height="320" loading="lazy">
                            <div>
                                <h4>${escapeHtml(artist.release.title)}</h4>
                                <div class="artistTextLinks">${externalLinks(artist.release.links)}</div>
                            </div>
                        </div>` : ""}
                        ${artist.spotify ? `
                            <iframe style="border-radius:12px" src="${escapeHtml(artist.spotify)}" title="Écouter ${name} sur Spotify" width="100%" height="152" frameborder="0" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        ` : ""}
                    </div>` : ""}

                ${artist.moreLinks ? `
                    <div class="artistBio">
                        <h3>À découvrir</h3>
                        <div class="artistTextLinks">${externalLinks(artist.moreLinks)}</div>
                    </div>` : ""}

                ${artist.booking ? `
                    <div class="artistBio">
                        <h3>Contact programmation</h3>
                        <div class="artistTextLinks"><a href="mailto:${escapeHtml(artist.booking)}">${escapeHtml(artist.booking)}</a></div>
                    </div>` : ""}

                ${artist.gallery.length ? `<div class="artistGallery">
                    <h3>Galerie</h3>
                    <div class="galleryGrid">
                        ${artist.gallery
                            .map(
                                ({src, position = "center", ratio = "1"}) => `
                                    <div class="galleryItem" style="--gallery-photo-position: ${escapeHtml(position)}; --gallery-photo-ratio: ${escapeHtml(ratio)}">
                                        <img src="${escapeHtml(src)}" alt="${name}" loading="lazy" decoding="async">
                                    </div>`
                            )
                            .join("")}
                    </div>
                </div>` : ""}
            </div>
        `;
    }

    artistCards.forEach((card) => {
        card.addEventListener("click", (event) => {
            if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
            event.preventDefault();
            const url = new URL(window.location.href);
            url.searchParams.set("artiste", slugForCard(card));
            history.pushState({dfrArtistModal: true}, "", url);
            syncArtistModalFromUrl();
        });
        card.addEventListener("keydown", (event) => {
            if (event.key === " ") {
                event.preventDefault();
                card.click();
            }
        });
    });

    closeModal.addEventListener("click", closeArtistModal);
    modal.addEventListener("click", function (event) {
        if (event.target === modal) closeArtistModal();
    });
    modal.addEventListener("keydown", function (event) {
        if (event.key === "Escape") closeArtistModal();
        if (event.key !== "Tab") return;
        const controls = modal.querySelectorAll("button, a[href], input:not([hidden]), iframe");
        const first = controls[0];
        const last = controls[controls.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });
    window.addEventListener("popstate", syncArtistModalFromUrl);
    syncArtistModalFromUrl();
});
