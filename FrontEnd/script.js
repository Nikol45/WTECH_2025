document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dropdown-toggle').forEach(btn => {
        btn.removeAttribute('data-bs-toggle');
    });

    // ----------- Navigácia -----------

    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    
    //otvorit dropdown menu profilu
    profileBtn.addEventListener('click', (e) => {
      e.stopPropagation(); // Prevent this click from closing it immediately
      profileMenu.classList.toggle('show');
    });
    
    //zatvorit dropdown menu profilu pri kliknuti mimo
    document.addEventListener('click', (e) => {
      if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
        profileMenu.classList.remove('show');
      }
    });

    // -------------- Kategórie ---------------
    const toggleBtn = document.getElementById('toggleCategoriesBtn');
    const catBar = document.getElementById('categoriesBar');
  
    toggleBtn.addEventListener('click', () => {
      catBar.classList.toggle('collapsed');
      toggleBtn.classList.toggle('rotated-arrow');
    });
});
