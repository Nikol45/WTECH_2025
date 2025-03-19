document.addEventListener('DOMContentLoaded', () => {

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


    // -------- Placeholder akcie pre šípky (carousel) --------

    const arrowLeftButtons = document.querySelectorAll('.arrow-left');
    const arrowRightButtons = document.querySelectorAll('.arrow-right');

    arrowLeftButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            // Tu implementujte logiku posunu doľava v karuseli
            alert('Posun doľava');
        });
    });

    arrowRightButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            // Tu implementujte logiku posunu doprava v karuseli
            alert('Posun doprava');
        });
    });


    // -------- Toggle lišty kategórií (dropdown pre kategórie)

    const categoriesBar = document.getElementById('categoriesBar');
    const toggleCategoriesBtn = document.getElementById('toggleCategoriesBtn');
    if (toggleCategoriesBtn && categoriesBar) {
        toggleCategoriesBtn.addEventListener('click', () => {
            categoriesBar.classList.toggle('collapsed');
        });
    }

    // -------- Spracovanie kliknutia na kategóriu --------
    const subcatButtons = document.querySelectorAll('.subcat-btn');
    subcatButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            alert(`Klikol si na podkategóriu: ${btn.textContent}`);
            // Alebo iná logika, napr. presmerovanie na podstránku
            // window.location.href = '/podkategoria-1';
        });
    });

    // -------- Spracovanie kliknutia na šípku späť --------
    const backLink = document.querySelector('.back-link');
    if (backLink) {
        backLink.addEventListener('click', (e) => {
            e.preventDefault();
            // Vlastná logika, napr. vrátiť sa na predchádzajúcu stránku
            window.history.back();
            // alebo window.location.href = '/hlavna-stranka';
        });
    }

    // -------- Prepínanie tabov (napr. v detailoch produktu)
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            // Odstrániť triedu 'active' zo všetkých tab tlačidiel
            tabButtons.forEach((b) => b.classList.remove('active'));
            // Skryť všetky panelové elementy
            tabPanels.forEach((panel) => panel.style.display = 'none');

            // Nastaviť active na kliknuté tlačidlo a zobraziť príslušný panel
            btn.classList.add('active');
            const target = btn.getAttribute('data-tab');
            const targetPanel = document.getElementById(target);
            if (targetPanel) {
                targetPanel.style.display = 'block';
            }
        });
    });

    // -------- Prepínanie krokov objednávky pomocou triedy .step-btn
    const stepButtons = document.querySelectorAll('.step-btn');
    stepButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            // Odstrániť triedu active zo všetkých krokov
            stepButtons.forEach((b) => b.classList.remove('active'));
            // Nastaviť active len na kliknuté tlačidlo
            btn.classList.add('active');
            // Možno pridať logiku presmerovania, ak je potrebné
        });
    });

    // -------- Checkbox "celý košík" → Označí/odznačí všetky item-check
    const combineShops = document.getElementById('combineShops');
    const itemChecks = document.querySelectorAll('.item-check');

    if (combineShops) {
        combineShops.addEventListener('change', () => {
            itemChecks.forEach((check) => {
                check.checked = combineShops.checked;
            });
        });
    }


    // ====== DETAILNÉ INFORMÁCIE (klik -> prepnutie napr. na "Popis" tab) ======
    const detailInfoLink = document.getElementById('detailInfoLink');
    if (detailInfoLink) {
        detailInfoLink.addEventListener('click', (e) => {
            e.preventDefault();
            // Napr. automatické prepnutie na tab s ID="popis"
            const popisTabBtn = document.querySelector('.tab-btn[data-tab="popis"]');
            if (popisTabBtn) {
                popisTabBtn.click(); // klikneme programovo
            }
        });
    }

    // ====== ZOOM BUTTON (napr. lightbox) ======
    const zoomBtn = document.getElementById('zoomBtn');
    if (zoomBtn) {
        zoomBtn.addEventListener('click', () => {
            alert('Tu by sa mohol otvoriť lightbox s väčším obrázkom.');
        });
    }

    // ====== TLAČIDLO "OBĽÚBENÉ" ======
    const btnFavorite = document.getElementById('btnFavorite');
    if (btnFavorite) {
        btnFavorite.addEventListener('click', () => {
            alert('Produkt bol pridaný do obľúbených!');
            // Alebo iná logika, napr. zmena ikony, zápis do localStorage atď.
        });
    }

    // ====== ROZBALENIE ĎALŠÍCH FARMIEN ======
    const expandFarmsBtn = document.getElementById('expandFarmsBtn');
    if (expandFarmsBtn) {
        expandFarmsBtn.addEventListener('click', () => {
            alert('Tu môžeš zobraziť ďalšie farmy, prípadne zrolovať sekciu.');
        });
    }

});
