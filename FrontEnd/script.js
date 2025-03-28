document.addEventListener('DOMContentLoaded', () => {
    // =================== MODAL FUNKCIE ===================
    // Funkcie na ovládanie zobrazenia / skrytia modálov

    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        const overlay = document.getElementById("overlayBackground");
        if (modal && overlay) {
            modal.classList.remove("d-none");
            overlay.classList.remove("d-none");
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        const overlay = document.getElementById("overlayBackground");
        if (modal && overlay) {
            modal.classList.add("d-none");
            overlay.classList.add("d-none");
        }
    };

    window.switchModal = function(currentModalId, nextModalId) {
        window.closeModal(currentModalId);
        window.openModal(nextModalId);
    };

    // Zatvorenie modálu, ak klikneme mimo (priamo na jeho wrapper)
    // Týka sa .custom-modal, nie overlay-u.
    document.querySelectorAll(".custom-modal").forEach(modal => {
        modal.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.classList.add("d-none");
                const overlay = document.getElementById("overlayBackground");
                if (overlay) overlay.classList.add("d-none");
            }
        });
    });


    // =================== PROFILOVÉ DROPDOWN MENU ===================
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    // Simulácia stavu prihlásenia používateľa
    let isLoggedIn = false;

    // Funkcia na aktualizáciu obsahu menu podľa stavu prihlásenia
    function updateProfileMenu() {
        profileMenu.classList.remove('show');

        profileMenu.innerHTML = '';
        if (isLoggedIn) {
            profileMenu.innerHTML = `
                <li><a class="dropdown-item" href="Profil-udaje.html">Môj profil</a></li>
                <li><a class="dropdown-item" href="Profil-historia.html">Objednávky</a></li>
                <li><a class="dropdown-item" href="Profil-recenzie.html">Recenzie</a></li>
                <li><a class="dropdown-item" href="#" id="logoutLink">Odhlásiť</a></li>
            `;
        } else {
            profileMenu.innerHTML = `
                <li><a class="dropdown-item" href="#" id="loginLink">Prihlásiť sa</a></li>
            `;
        }
    }
    // Inicializácia (len ak existuje profileMenu)
    if (profileMenu) {
        updateProfileMenu();
    }

    // Kliknutie na tlačidlo profilu (ak existuje)
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });
    }

    // Zatvorenie dropdown menu, ak klikneme mimo
    document.addEventListener('click', (e) => {
        // zatvoriť menu, ak klik nie je na (profileMenu) ani (profileBtn)
        if (profileMenu && profileBtn) {
            if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                profileMenu.classList.remove('show');
            }
        }
    });

    // Delegovanie kliknutí v dropdown menu
    document.addEventListener('click', (e) => {
        // Prihlásiť sa
        if (e.target && e.target.id === 'loginLink') {
            e.preventDefault();
            isLoggedIn = true;
            updateProfileMenu();
            // Otvoríme popup pre prihlasenie
            openModal('loginModal');
        }
        // Odhlásiť
        if (e.target && e.target.id === 'logoutLink') {
            e.preventDefault();
            isLoggedIn = false;
            updateProfileMenu();
        }
    });


    // =================== TOGGLE KATEGÓRIÍ ===================
    const toggleBtn = document.getElementById('toggleCategoriesBtn');
    const catBar = document.getElementById('categoriesBar');
    if (toggleBtn && catBar) {
        toggleBtn.addEventListener('click', () => {
            catBar.classList.toggle('collapsed');
            toggleBtn.classList.toggle('rotated-arrow');
        });
    }


    // =================== TOGGLE DODACEJ ADRESY ===================
    const differentAddress = document.getElementById('differentAddress');
    const deliveryAddressSection = document.getElementById('deliveryAddressSection');
    if (differentAddress && deliveryAddressSection) {
        differentAddress.addEventListener('change', () => {
            if (differentAddress.checked) {
                deliveryAddressSection.classList.remove('d-none');
            } else {
                deliveryAddressSection.classList.add('d-none');
            }
        });
    }


    // =================== TOGGLE FIRMY ===================
    const companyCheck = document.getElementById('companyCheck');
    const companySection = document.getElementById('companySection');
    if (companyCheck && companySection) {
        companyCheck.addEventListener('change', () => {
            if (companyCheck.checked) {
                companySection.classList.remove('d-none');
            } else {
                companySection.classList.add('d-none');
            }
        });
    }


    // =================== ZOBRAZENIE SUMÁRIÁLNEHO RIADKU DOPRAVY ===================
    const summaryRow = document.getElementById('summaryRow');
    const deliveryGls = document.getElementById('deliveryGls');
    const deliveryBox = document.getElementById('deliveryBox');

    if (summaryRow && deliveryGls && deliveryBox) {
        [deliveryGls, deliveryBox].forEach(input => {
            input.addEventListener('change', () => {
                if (deliveryGls.checked || deliveryBox.checked) {
                    summaryRow.classList.remove('d-none');
                } else {
                    summaryRow.classList.add('d-none');
                }
            });
        });
    }


    // ============= SCROLLOVANIE SEKCII PRODUKTOV ==============

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
    if (arrowRight && carouselRow) {
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
    }

    if (arrowLeft && carouselRow) {
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
    }

    if (arrowRight1 && carouselRow1) {
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
    }

    if (arrowLeft1 && carouselRow1) {
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
    }
    
    if (arrowRight2 && carouselRow2) {
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
    }

    if (arrowLeft2 && carouselRow2) {
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
    }

    if (arrowRight3 && carouselRow3) {
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
    }

    if (arrowLeft3 && carouselRow3) {
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
    }

    //animacie na scrollovanie sekciou clankov
    if (arrowRight4 && carouselRow4) {
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
    }

    if (arrowLeft4 && carouselRow4) {
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
    }

    //animacia na fillnutie srdiecka pri kliknuti
    document.querySelectorAll('.favorite-btn').forEach(favBtn => {
        favBtn.addEventListener('click', () => {
          const icon = favBtn.querySelector('.material-icons');
          if (icon.textContent.trim() === 'favorite_border') {
            icon.textContent = 'favorite';
            icon.classList.add('filled');
          } else {
            icon.textContent = 'favorite_border';
            icon.classList.remove('filled');
          }
        });
    });

});
