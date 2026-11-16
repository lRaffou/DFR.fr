document.querySelectorAll("source[data-album-thumbnail] + img").forEach(image => {
    const source = image.previousElementSibling;
    const restoreCover = () => {
        if (image.currentSrc === source.srcset) source.remove();
    };

    image.addEventListener("error", restoreCover);
    if (image.complete && image.naturalWidth === 0) restoreCover();
});

// Le lecteur intégré est chargé à l’activation. Le lien reste utilisable sans JavaScript.
document.querySelectorAll("a[data-player-src]").forEach(preview => {
    preview.addEventListener("click", event => {
        if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        event.preventDefault();

        const iframe = document.createElement("iframe");
        iframe.src = preview.dataset.playerSrc;
        iframe.title = preview.dataset.playerTitle;
        iframe.allow = preview.dataset.playerAllow;
        iframe.allowFullscreen = true;
        iframe.referrerPolicy = "strict-origin-when-cross-origin";
        preview.replaceWith(iframe);
        iframe.focus({ preventScroll: true });
    });
});

// Modale pour vidéos
const videoCards = document.querySelectorAll(".videoCard");
const modal = document.getElementById("videoModal");
const modalVideo = document.getElementById("modalVideo");
const modalYoutubeLink = document.getElementById("modalYoutubeLink");
const closeModal = modal?.querySelector(".closeModal");

if (videoCards.length > 0 && modal && modalVideo && closeModal) {
    let activePlayButton = null;

    function closeVideoModal() {
        modal.style.display = "none";
        document.body.classList.remove("modalOpen");
        modalVideo.removeAttribute("src");
        modalYoutubeLink?.removeAttribute("href");
        activePlayButton?.focus();
    }

    videoCards.forEach(card => {
        card.addEventListener("click", function (event) {
            if (event.target.closest("a")) return;
            activePlayButton = card.querySelector(".playButton");
            const videoId = card.getAttribute("data-video");
            modalVideo.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&playsinline=1`;
            modalVideo.title = card.dataset.title;
            if (modalYoutubeLink) modalYoutubeLink.href = `https://www.youtube.com/watch?v=${videoId}`;
            modal.style.display = "block";
            document.body.classList.add("modalOpen");
            modal.querySelector(".modalContent").scrollTop = 0;
            closeModal.focus({ preventScroll: true });
        });
    });

    closeModal.addEventListener("click", closeVideoModal);
    modal.addEventListener("click", function (event) {
        if (event.target === modal) closeVideoModal();
    });
    modal.addEventListener("keydown", function (event) {
        if (event.key === "Escape") closeVideoModal();
        if (event.key !== "Tab") return;
        const firstControl = modalYoutubeLink || closeModal;
        if (event.shiftKey && document.activeElement === firstControl) {
            event.preventDefault();
            modalVideo.focus();
        } else if (!event.shiftKey && document.activeElement === modalVideo) {
            event.preventDefault();
            firstControl.focus();
        }
    });
}
