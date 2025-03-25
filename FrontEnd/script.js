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

    const differentAddress = document.getElementById('differentAddress');
    const deliveryAddressSection = document.getElementById('deliveryAddressSection');

    // Skryť / zobraziť dodaciu adresu
    differentAddress.addEventListener('change', () => {
        if (differentAddress.checked) {
            deliveryAddressSection.classList.remove('d-none');
        } else {
            deliveryAddressSection.classList.add('d-none');
        }
    });

    const companyCheck = document.getElementById('companyCheck');
    const companySection = document.getElementById('companySection');

    // Skryť / zobraziť firmu
    companyCheck.addEventListener('change', () => {
        if (companyCheck.checked) {
            companySection.classList.remove('d-none');
        } else {
            companySection.classList.add('d-none');
        }
    });

    const summaryRow = document.getElementById('summaryRow');
    const deliveryGls = document.getElementById('deliveryGls');
    const deliveryBox = document.getElementById('deliveryBox');


    // Ukážkové zobrazenie sumy, keď sa zaškrtne jedna z možností dopravy
    [deliveryGls, deliveryBox].forEach(input => {
        input.addEventListener('change', () => {
            // Ak aspoň jeden je zaškrtnutý, zobrazíme riadok
            if (deliveryGls.checked || deliveryBox.checked) {
                summaryRow.classList.remove('d-none');
            } else {
                summaryRow.classList.add('d-none');
            }
        });
    });
});
