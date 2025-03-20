document.addEventListener('DOMContentLoaded', () => {
    // Odstráňte prípadné Bootstrap atribúty, ak používate vlastné JS
    document.querySelectorAll('.dropdown-toggle').forEach(btn => {
        btn.removeAttribute('data-bs-toggle');
    });

    // -------- Dropdown toggles pre ikonky --------
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    const heartBtn = document.getElementById('heartBtn');
    const heartDropdown = document.getElementById('heartDropdown');
    const cartBtn = document.getElementById('cartBtn');
    const cartDropdown = document.getElementById('cartDropdown');

    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
            // Zatvorenie ostatných
            heartDropdown && heartDropdown.classList.remove('active');
            cartDropdown && cartDropdown.classList.remove('active');
        });
    }

    if (heartBtn && heartDropdown) {
        heartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            heartDropdown.classList.toggle('active');
            profileDropdown && profileDropdown.classList.remove('active');
            cartDropdown && cartDropdown.classList.remove('active');
        });
    }

    if (cartBtn && cartDropdown) {
        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            cartDropdown.classList.toggle('active');
            profileDropdown && profileDropdown.classList.remove('active');
            heartDropdown && heartDropdown.classList.remove('active');
        });
    }

    // Zatvorenie dropdownov, keď kliknete mimo
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.icon-wrapper')) {
            profileDropdown && profileDropdown.classList.remove('active');
            heartDropdown && heartDropdown.classList.remove('active');
            cartDropdown && cartDropdown.classList.remove('active');
        }
    });

    // -------- Toggle kategórií --------
    const categoriesBar = document.getElementById('categoriesBar');
    const toggleCategoriesBtn = document.getElementById('toggleCategoriesBtn');
    if (toggleCategoriesBtn && categoriesBar) {
        toggleCategoriesBtn.addEventListener('click', () => {
            categoriesBar.classList.toggle('collapsed');
        });
    }
});
