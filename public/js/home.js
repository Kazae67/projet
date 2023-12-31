document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour changer l'image dans un carrousel
    function changeCarouselImage(carouselImages, thumbnails, currentIndex, newIndex) {
        carouselImages[currentIndex].style.opacity = '0';
        thumbnails[currentIndex].classList.remove('active');

        setTimeout(() => {
            carouselImages[currentIndex].classList.remove('active');
            carouselImages[newIndex].classList.add('active');
            thumbnails[newIndex].classList.add('active');
            setTimeout(() => {
                carouselImages[newIndex].style.opacity = '1';
            }, 10);
        }, 500);

        return newIndex;
    }

    // Carrousel pour les produits les mieux notés
    const ratedImages = document.querySelectorAll('.carousel-rated .carousel-images img');
    const ratedThumbnails = document.querySelectorAll('.top-rated-products-container .product-thumbnail');
    let ratedIndex = 0;

    // Carrousel pour les produits les plus vendus
    const sellingImages = document.querySelectorAll('.carousel-selling .carousel-images img');
    const sellingThumbnails = document.querySelectorAll('.top-selling-products-container .product-thumbnail');
    let sellingIndex = 0;

    // Carrousel pour les produits les plus récents
    const latestImages = document.querySelectorAll('.carousel-latest .carousel-images img');
    const latestThumbnails = document.querySelectorAll('.latest-products-container .product-thumbnail');
    let latestIndex = 0;

    // Fonction pour ajouter des écouteurs d'événements et la logique de changement d'image à un carrousel
    function setupCarousel(carouselImages, carouselThumbnails, carouselButtons, currentIndex) {
        carouselThumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', (event) => {
                event.preventDefault();
                currentIndex = changeCarouselImage(carouselImages, carouselThumbnails, currentIndex, index);
            });
        });

        carouselButtons.next.addEventListener('click', () => {
            const nextIndex = (currentIndex + 1) % carouselImages.length;
            currentIndex = changeCarouselImage(carouselImages, carouselThumbnails, currentIndex, nextIndex);
        });

        carouselButtons.prev.addEventListener('click', () => {
            const prevIndex = (currentIndex - 1 + carouselImages.length) % carouselImages.length;
            currentIndex = changeCarouselImage(carouselImages, carouselThumbnails, currentIndex, prevIndex);
        });
    }

    // Configuration des carrousels
    setupCarousel(ratedImages, ratedThumbnails, { next: document.querySelector('.carousel-rated .next'), prev: document.querySelector('.carousel-rated .prev') }, ratedIndex);
    setupCarousel(sellingImages, sellingThumbnails, { next: document.querySelector('.carousel-selling .next'), prev: document.querySelector('.carousel-selling .prev') }, sellingIndex);
    setupCarousel(latestImages, latestThumbnails, { next: document.querySelector('.carousel-latest .next'), prev: document.querySelector('.carousel-latest .prev') }, latestIndex);
});
