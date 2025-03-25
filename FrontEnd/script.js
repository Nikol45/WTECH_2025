document.addEventListener('DOMContentLoaded', () => {
    // Odstráňte prípadné Bootstrap atribúty, ak používate vlastné JS
    document.querySelectorAll('.dropdown-toggle').forEach(btn => {
        btn.removeAttribute('data-bs-toggle');
    });

    // -------- Dropdown toggles pre ikonky --------

    const profileDropdown = document.getElementById('profileDropdown');
    const heartBtn = document.getElementById('heartBtn');
    const heartDropdown = document.getElementById('heartDropdown');
    const cartBtn = document.getElementById('cartBtn');
    const cartDropdown = document.getElementById('cartDropdown');


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

    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    profileBtn.addEventListener('click', () => {
        profileMenu.classList.toggle('show');
    });


    // -------- Toggle kategórií --------
    const toggleBtn = document.getElementById('toggleCategoriesBtn');
    const catBar = document.getElementById('categoriesBar');
  
    toggleBtn.addEventListener('click', () => {
      // Toggle the bar's collapsed state
      catBar.classList.toggle('collapsed');
      // Toggle a class on the button to flip the arrow
      toggleBtn.classList.toggle('rotated-arrow');
    });
});
