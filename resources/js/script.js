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

    let categorySelect = document.querySelector('#categorySelect');
    let sortSelect = document.querySelector('#sortSelect');
    let priceInput = document.querySelector('#priceInput');
    let priceValue = document.querySelector('#priceValue');
    let wordInput = document.querySelector('#wordInput');

    if (priceInput && priceValue) {
        priceValue.textContent = priceInput.value;
    }

    function updatePage() {
        let params = new URLSearchParams(window.location.search);

        if (categorySelect) params.set('category', categorySelect.value);
        if (sortSelect) params.set('sort', sortSelect.value);
        if (priceInput) params.set('price', priceInput.value);
        if (wordInput) params.set('word', wordInput.value);

        let baseUrl = window.location.origin + window.location.pathname;
        window.location.href = `${baseUrl}?${params.toString()}`;
    }

    if (categorySelect) categorySelect.addEventListener('change', updatePage);
    if (sortSelect) sortSelect.addEventListener('change', updatePage);
    if (priceInput) priceInput.addEventListener('input', () => {
        priceValue.textContent = priceInput.value;
        updatePage();
    });
    if (wordInput) wordInput.addEventListener('input', updatePage);

    let swiper = new Swiper('.mySwiper', {
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
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
});
