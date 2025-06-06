// Initialization for ES Users
import { Input, initMDB } from "mdb-ui-kit";
initMDB({ Input });

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

let confirm = true;

let createInterval = function (n, element, time) {
    let counter = 0;
    let interval = setInterval(() => {
        if (counter < n) {
            counter++;
            element.innerHTML = counter;
        } else {
            clearInterval(interval);
        }
    }, time);
    setTimeout(() => { confirm = true; }, 8000);
};

document.addEventListener('DOMContentLoaded', () => {
    // === Navbar collapse ===
    let navbarCollapse = document.querySelector('#navbarResponsive');
    let navbarToggler = document.querySelector('.navbar-toggler');

    if (navbarToggler && navbarCollapse) {
        navbarCollapse.addEventListener('show.bs.collapse', () => {
            navbarCollapse.classList.remove('collapsing-out');
            navbarCollapse.classList.add('collapsing-in');
        });

        navbarCollapse.addEventListener('hide.bs.collapse', () => {
            navbarCollapse.classList.remove('collapsing-in');
            navbarCollapse.classList.add('collapsing-out');
        });

        navbarCollapse.addEventListener('hidden.bs.collapse', () => {
            navbarCollapse.classList.remove('collapsing-out');
        });

        navbarCollapse.addEventListener('shown.bs.collapse', () => {
            navbarCollapse.classList.remove('collapsing-in');
        });
    }

    // === Numeri dinamici ===
    let firstNumber = document.querySelector('#firstNumber');
    let secondNumber = document.querySelector('#secondNumber');
    let thirdNumber = document.querySelector('#thirdNumber');

    if (firstNumber && secondNumber && thirdNumber) {
        let observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && confirm) {
                    createInterval(1887, firstNumber, 13);
                    createInterval(4043, secondNumber, 0);
                    createInterval(369, thirdNumber, 66);
                    confirm = false;
                }
            });
        });
        observer.observe(firstNumber);
    }

    // === Animazioni dinamiche ===
    let animatedBlocks = document.querySelectorAll(
        '.slide-from-left, .slide-from-right, .slide-from-bottom, .slide-from-bottom-slow, .review-fade-in, .article-fade-in'
    );

    let blockObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('slide-visible');
                blockObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    animatedBlocks.forEach(el => blockObserver.observe(el));

    // === Slideshow Swiper ===
    let swiper = new Swiper('.mySwiper', {
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '#nextButton',
            prevEl: '#prevButton',
        },

        breakpoints: {
            576: {
                direction: 'horizontal',
                slidesPerView: 3,
                coverflowEffect: {
                    rotate: 40,
                    stretch: 0,
                    depth: 150,
                    modifier: 1,
                    slideShadows: false,
                }
            },
            0: {
                direction: 'vertical',
                slidesPerView: 3,
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 150,
                    modifier: 1,
                    slideShadows: false,
                }
            }
        },
        effect: 'coverflow'
    });

    // === Comportamento responsive per sezioni dinamiche ===
    function updateResponsiveSections() {
        document.querySelectorAll('.responsive-section').forEach(section => {
            section.classList.remove('d-flex', 'align-items-center', 'min-vh-100', 'section-responsive');

            if (window.innerWidth < 992) {
                section.classList.add('section-responsive');
            } else {
                section.classList.add('section-responsive', 'd-flex', 'align-items-center', 'min-vh-100');
            }
        });
    }

    updateResponsiveSections();
    window.addEventListener('resize', updateResponsiveSections);

    // === Filtro articoli con bottone "Applica filtri" ===
    let applyFiltersBtn = document.querySelector('#applyFiltersBtn');
    let filterForm = document.querySelector('#filterForm');
    let priceInput = document.querySelector('#priceInput');
    let priceValue = document.querySelector('#priceValue');

    // ✅ Mostra dinamicamente il prezzo mentre muovi l’asticella
    if (priceInput && priceValue) {
        priceValue.textContent = priceInput.value;
        priceInput.addEventListener('input', () => {
            priceValue.textContent = priceInput.value;
        });
    }

    if (applyFiltersBtn && filterForm) {
        applyFiltersBtn.addEventListener('click', () => {
            let selectedCategory = document.querySelector('#categorySelect').value;
            let sort = document.querySelector('#sortSelect').value;
            let price = document.querySelector('#priceInput').value;
            let query = document.querySelector('#wordInput').value;

            let params = new URLSearchParams();
            if (sort) params.append('sort', sort);
            if (price) params.append('price', price);
            if (query) params.append('query', query); // 👈 modifica fatta qui

            if (selectedCategory && selectedCategory !== 'all') {
                window.location.href = `/category/${selectedCategory}?${params.toString()}`;
            } else {
                window.location.href = `/article/index?${params.toString()}`;
            }
        });
    }
});
