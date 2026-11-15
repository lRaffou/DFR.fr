// Fonction pour envoyer un email
function send() {
    window.open("mailto:Daofamilyrecords@gmail.com");
}

// Mise à jour automatique de l'année
const yearElement = document.getElementById("year");
if (yearElement) {
    yearElement.innerText = new Date().getFullYear();
}

// Carousel Accueil
const carousel = document.querySelector(".carousel");
if (carousel) {
    const firstImg = carousel.firstElementChild;
    const arrowIcons = document.querySelectorAll(".wrapper i");

    let isDragStart = false,
        isDragging = false,
        prevPageX,
        prevScrollLeft,
        positionDiff;

    const showHideIcons = () => {
        const scrollWidth = carousel.scrollWidth - carousel.clientWidth;
        arrowIcons[0].style.display = carousel.scrollLeft === 0 ? "none" : "block";
        arrowIcons[1].style.display = carousel.scrollLeft === scrollWidth ? "none" : "block";
    };

    arrowIcons.forEach(icon => {
        icon.addEventListener("click", () => {
            const firstImgWidth = firstImg.clientWidth + 14;
            carousel.scrollLeft += icon.id === "left" ? -firstImgWidth : firstImgWidth;
            setTimeout(showHideIcons, 60);
        });
    });

    const autoSlide = () => {
        if (carousel.scrollLeft === carousel.scrollWidth - carousel.clientWidth) return;
        const firstImgWidth = firstImg.clientWidth + 14;
        const scrollAmount = positionDiff > firstImgWidth / 3 ? firstImgWidth : positionDiff;
        carousel.scrollTo({
            left: carousel.scrollLeft + (carousel.scrollLeft > prevScrollLeft ? scrollAmount : -scrollAmount),
            behavior: "smooth"
        });
    };

    const dragStart = (e) => {
        isDragStart = true;
        prevPageX = e.pageX || e.touches[0].pageX;
        prevScrollLeft = carousel.scrollLeft;
    };

    const dragging = (e) => {
        if (!isDragStart) return;
        e.preventDefault();
        isDragging = true;
        carousel.classList.add("dragging");
        positionDiff = (e.pageX || e.touches[0].pageX) - prevPageX;
        carousel.scrollLeft = prevScrollLeft - positionDiff;
        showHideIcons();
    };

    const dragStop = () => {
        isDragStart = false;
        carousel.classList.remove("dragging");
        if (!isDragging) return;
        isDragging = false;
        autoSlide();
    };

    carousel.addEventListener("mousedown", dragStart);
    carousel.addEventListener("mousemove", dragging);
    carousel.addEventListener("mouseup", dragStop);
    carousel.addEventListener("mouseleave", dragStop);
    carousel.addEventListener("touchstart", dragStart);
    carousel.addEventListener("touchmove", dragging);
    carousel.addEventListener("touchend", dragStop);
}

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
