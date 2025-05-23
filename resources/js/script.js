// Initialization for ES Users
import { Input, initMDB } from "mdb-ui-kit";
initMDB({ Input });

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
    // === ANIMAZIONE NAVBAR COLLAPSE ===
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

    // === NUMERI HOMEPAGE ===
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

    // === FILTRI DINAMICI SENZA BOTTONE ===
    let categorySelect = document.querySelector('#categorySelect');
    let sortSelect = document.querySelector('#sortSelect');
    let priceInput = document.querySelector('#priceInput');
    let priceValue = document.querySelector('#priceValue');
    let wordInput = document.querySelector('#wordInput');

    // Mostra il valore del prezzo slider al caricamento
    priceValue.textContent = priceInput.value;

    function updatePage() {
        let params = new URLSearchParams(window.location.search);

        params.set('category', categorySelect.value);
        params.set('sort', sortSelect.value);
        params.set('price', priceInput.value);
        params.set('word', wordInput.value);

        let baseUrl = window.location.origin + window.location.pathname;
        window.location.href = `${baseUrl}?${params.toString()}`;
    }

    categorySelect.addEventListener('change', updatePage);
    sortSelect.addEventListener('change', updatePage);
    priceInput.addEventListener('input', () => {
        priceValue.textContent = priceInput.value;
        updatePage();
    });
    wordInput.addEventListener('input', updatePage);
});
