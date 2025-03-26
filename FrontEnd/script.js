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
  
    //zrolovat kategorie pri kliknuti, otocit sipku
    toggleBtn.addEventListener('click', () => {
      catBar.classList.toggle('collapsed');
      toggleBtn.classList.toggle('rotated-arrow');
    });

    // -------------- Sekcie produktov -----------

    const carouselRow = document.getElementById('carouselRow');
    const arrowRight = document.getElementById('arrowRight');
    const arrowLeft = document.getElementById('arrowLeft');

    const carouselRow1 = document.getElementById('carouselRow1');
    const arrowRight1 = document.getElementById('arrowRight1');
    const arrowLeft1 = document.getElementById('arrowLeft1');

    const carouselRow2 = document.getElementById('carouselRow2');
    const arrowRight2 = document.getElementById('arrowRight2');
    const arrowLeft2 = document.getElementById('arrowLeft2');

    const carouselRow3 = document.getElementById('carouselRow3');
    const arrowRight3 = document.getElementById('arrowRight3');
    const arrowLeft3 = document.getElementById('arrowLeft3');

    //pre sekciu clankov
    const carouselRow4 = document.getElementById('carouselRow4');
    const arrowRight4 = document.getElementById('arrowRight4');
    const arrowLeft4 = document.getElementById('arrowLeft4');

    let isAnimating = false;

    //animacie na scrollovanie sekciami produktov
    arrowRight.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;

        carouselRow.style.transform = 'translateX(-25%)';

        carouselRow.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
            const firstCol = carouselRow.querySelector('.col');
            carouselRow.appendChild(firstCol);

            carouselRow.style.transition = 'none';
            carouselRow.style.transform = 'none';

            carouselRow.offsetHeight;

            carouselRow.style.transition = 'transform 0.3s ease';

            carouselRow.removeEventListener('transitionend', handler);
            isAnimating = false;
            }
        });
    });

    arrowLeft.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;
      
        const allCols = carouselRow.querySelectorAll('.col');
        const lastCol = allCols[allCols.length - 1];
        carouselRow.insertBefore(lastCol, allCols[0]);

        carouselRow.style.transition = 'none';
        carouselRow.style.transform = 'translateX(-25%)';
      
        carouselRow.offsetHeight;
      
        carouselRow.style.transition = 'transform 0.3s ease';
        carouselRow.style.transform = 'none';
      
        carouselRow.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
                carouselRow.removeEventListener('transitionend', handler);
                isAnimating = false;
            }
        });
    });

    arrowRight1.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;

        carouselRow1.style.transform = 'translateX(-25%)';

        carouselRow1.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
            const firstCol = carouselRow1.querySelector('.col');
            carouselRow1.appendChild(firstCol);

            carouselRow1.style.transition = 'none';
            carouselRow1.style.transform = 'none';

            carouselRow1.offsetHeight;

            carouselRow1.style.transition = 'transform 0.3s ease';

            carouselRow1.removeEventListener('transitionend', handler);
            isAnimating = false;
            }
        });
    });

    arrowLeft1.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;
      
        const allCols = carouselRow1.querySelectorAll('.col');
        const lastCol = allCols[allCols.length - 1];
        carouselRow1.insertBefore(lastCol, allCols[0]);

        carouselRow1.style.transition = 'none';
        carouselRow1.style.transform = 'translateX(-25%)';
      
        carouselRow1.offsetHeight;
      
        carouselRow1.style.transition = 'transform 0.3s ease';
        carouselRow1.style.transform = 'none';
      
        carouselRow1.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
                carouselRow1.removeEventListener('transitionend', handler);
                isAnimating = false;
            }
        });
    });

    arrowRight2.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;

        carouselRow2.style.transform = 'translateX(-25%)';

        carouselRow2.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
            const firstCol = carouselRow2.querySelector('.col');
            carouselRow2.appendChild(firstCol);

            carouselRow2.style.transition = 'none';
            carouselRow2.style.transform = 'none';

            carouselRow2.offsetHeight;

            carouselRow2.style.transition = 'transform 0.3s ease';

            carouselRow2.removeEventListener('transitionend', handler);
            isAnimating = false;
            }
        });
    });

    arrowLeft2.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;
      
        const allCols = carouselRow2.querySelectorAll('.col');
        const lastCol = allCols[allCols.length - 1];
        carouselRow2.insertBefore(lastCol, allCols[0]);

        carouselRow2.style.transition = 'none';
        carouselRow2.style.transform = 'translateX(-25%)';
      
        carouselRow2.offsetHeight;
      
        carouselRow2.style.transition = 'transform 0.3s ease';
        carouselRow2.style.transform = 'none';
      
        carouselRow2.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
                carouselRow2.removeEventListener('transitionend', handler);
                isAnimating = false;
            }
        });
    });

    arrowRight3.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;

        carouselRow3.style.transform = 'translateX(-25%)';

        carouselRow3.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
            const firstCol = carouselRow3.querySelector('.col');
            carouselRow3.appendChild(firstCol);

            carouselRow3.style.transition = 'none';
            carouselRow3.style.transform = 'none';

            carouselRow3.offsetHeight;

            carouselRow3.style.transition = 'transform 0.3s ease';

            carouselRow3.removeEventListener('transitionend', handler);
            isAnimating = false;
            }
        });
    });

    arrowLeft3.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;
      
        const allCols = carouselRow3.querySelectorAll('.col');
        const lastCol = allCols[allCols.length - 1];
        carouselRow3.insertBefore(lastCol, allCols[0]);

        carouselRow3.style.transition = 'none';
        carouselRow3.style.transform = 'translateX(-25%)';
      
        carouselRow3.offsetHeight;
      
        carouselRow3.style.transition = 'transform 0.3s ease';
        carouselRow3.style.transform = 'none';
      
        carouselRow3.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
                carouselRow3.removeEventListener('transitionend', handler);
                isAnimating = false;
            }
        });
    });

    //animacie na scrollovanie sekciou clankov
    arrowRight4.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;

        carouselRow4.style.transform = 'translateX(-25%)';

        carouselRow4.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
            const firstCol = carouselRow4.querySelector('.col');
            carouselRow4.appendChild(firstCol);

            carouselRow4.style.transition = 'none';
            carouselRow4.style.transform = 'none';

            carouselRow4.offsetHeight;

            carouselRow4.style.transition = 'transform 0.3s ease';

            carouselRow4.removeEventListener('transitionend', handler);
            isAnimating = false;
            }
        });
    });

    arrowLeft4.addEventListener('click', () => {
        if (isAnimating) return;
        isAnimating = true;
      
        const allCols = carouselRow4.querySelectorAll('.col');
        const lastCol = allCols[allCols.length - 1];
        carouselRow4.insertBefore(lastCol, allCols[0]);

        carouselRow4.style.transition = 'none';
        carouselRow4.style.transform = 'translateX(-25%)';
      
        carouselRow4.offsetHeight;
      
        carouselRow4.style.transition = 'transform 0.3s ease';
        carouselRow4.style.transform = 'none';
      
        carouselRow4.addEventListener('transitionend', function handler(e) {
            if (e.propertyName === 'transform') {
                carouselRow4.removeEventListener('transitionend', handler);
                isAnimating = false;
            }
        });
    });

    // ------------------------------------------------

    const differentAddress = document.getElementById('differentAddress');
    const deliveryAddressSection = document.getElementById('deliveryAddressSection');

    // Skryť/zobraziť dodaciu adresu
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
