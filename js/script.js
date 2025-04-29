// Fonction pour envoyer un email
function send() {
    window.open("mailto:r.lourenco@outlook.fr");
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

// Modale pour vidéos
const videoCards = document.querySelectorAll(".videoCard");
const modal = document.getElementById("videoModal");
const modalVideo = document.getElementById("modalVideo");
const closeModal = document.querySelector(".closeModal");

if (videoCards.length > 0 && modal && modalVideo && closeModal) {
    videoCards.forEach(card => {
        card.addEventListener("click", function () {
            const videoId = this.getAttribute("data-video");
            modalVideo.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
            modal.style.display = "block";
        });
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
        modalVideo.src = "";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
            modalVideo.src = "";
        }
    });
}
