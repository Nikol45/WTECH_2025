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
                <li><a class="dropdown-item" href="${routes.profile}">Môj profil</a></li>
                <li><a class="dropdown-item" href="${routes.profileHistory}">Objednávky</a></li>
                <li><a class="dropdown-item" href="${routes.profileReviews}">Recenzie</a></li>
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

    //na otvorenie login pop up z hamburger menu
    const mobileProfileLink = document.getElementById('mobileProfileLink');
    if (mobileProfileLink) {
        mobileProfileLink.addEventListener('click', (e) => {
            e.preventDefault();
            const offcanvas = document.getElementById('mobileMenu');
            if (offcanvas) {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
                if (bsOffcanvas) {
                    bsOffcanvas.hide();
                }
            }
            openModal('loginModal');
        });
    }

    const mobileProfileMenu = document.getElementById('mobileProfileMenu');

    //na zmenenie moznosti hamburger menu podla prihlasenia
    function updateMobileProfileMenu() {
        if (mobileProfileMenu) {
            mobileProfileMenu.innerHTML = '';
            if (isLoggedIn) {
                mobileProfileMenu.innerHTML = `
                <li class="mb-3 fw-bold fs-5" ><a href="${routes.profile}">Môj profil</a></li>
                <li class="mb-3 fw-bold fs-5" ><a href="${routes.profileHistory}">Objednávky</a></li>
                <li class="mb-3 fw-bold fs-5" ><a href="${routes.profileReviews}">Recenzie</a></li>
                <li class="mb-3 fw-bold fs-5" ><a href="#" id="logoutLink">Odhlásiť</a></li>
            `;
            }
            else {
                mobileProfileMenu.innerHTML = `
                <li class="mb-3 fw-bold fs-5"><a href="#" id="mobileLoginLink">Prihlásiť sa</a></li>
                `;
            }
        }
    }

    updateMobileProfileMenu();

    //aby po zatvoreni sidebaru sa resetol padding a overflow
    const offcanvasEl = document.getElementById('mobileMenu');
    offcanvasEl.addEventListener('hidden.bs.offcanvas', () => {
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
    });

    document.addEventListener('click', (e) => {
        if (e.target && e.target.id === 'mobileLoginLink') {
            e.preventDefault();
            isLoggedIn = true;
            updateMobileProfileMenu();
            const offcanvas = document.getElementById('mobileMenu');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
            if (bsOffcanvas) bsOffcanvas.hide();
            openModal('loginModal');
        }

        if (e.target && e.target.id === 'mobileLogoutLink') {
            e.preventDefault();
            isLoggedIn = false;
            updateMobileProfileMenu();
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
    const deliveryRow = document.getElementById('deliveryRow');
    const priceRow = document.getElementById('priceRow');

    const deliveryGlsSt = document.querySelector('input[id="delivery_GLS_standard"]');
    const deliveryGlsEx = document.querySelector('input[id="delivery_GLS_express"]');
    const deliveryPersonal = document.querySelector('input[id="delivery_personal"]');

    const payDobierkaWrapper = document.getElementById('payDobierkaWrapper');
    const priceDeliveryRow = document.getElementById('price_deliveryRow');
    const priceDobierkaRow = document.getElementById('price_dobierkaRow');
    const payDobierka = document.querySelector('input[name="paymentMethod"][value="cash_on_delivery"]');
    const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');

    function updateDeliveryDisplay() {
        const isGLS = deliveryGlsSt?.checked || deliveryGlsEx?.checked;
        const isPersonal = deliveryPersonal?.checked;

        if (isGLS) {
            deliveryRow.classList.remove('d-none');
            priceRow.classList.remove('d-none');
            priceDeliveryRow.classList.remove('d-none');
            payDobierkaWrapper.classList.remove('d-none');
        } else if (isPersonal) {
            deliveryRow.classList.add('d-none');
            priceRow.classList.remove('d-none');
            priceDeliveryRow.classList.add('d-none');
            payDobierkaWrapper.classList.add('d-none');
        } else {
            deliveryRow.classList.add('d-none');
            priceRow.classList.add('d-none');
            priceDeliveryRow.classList.add('d-none');
            payDobierkaWrapper.classList.add('d-none');
        }

        // Obnov zobrazenie dobierky
        updateDobierkaDisplay();
    }

    function updateDobierkaDisplay() {
        if (payDobierka?.checked) {
            priceDobierkaRow.classList.remove('d-none');
        } else {
            priceDobierkaRow.classList.add('d-none');
        }
    }

    [deliveryGlsSt, deliveryGlsEx, deliveryPersonal].forEach(input => {
        input?.addEventListener('change', updateDeliveryDisplay);
    });

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', updateDobierkaDisplay);
    });


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

    const carouselRow7 = document.getElementById('carouselRow7');
    const arrowRight7 = document.getElementById('arrowRight7');
    const arrowLeft7 = document.getElementById('arrowLeft7');

    const carouselRow8 = document.getElementById('carouselRow8');
    const arrowRight8 = document.getElementById('arrowRight8');
    const arrowLeft8 = document.getElementById('arrowLeft8');

    //pre sekciu clankov
    const carouselRow4 = document.getElementById('carouselRow4');
    const arrowRight4 = document.getElementById('arrowRight4');
    const arrowLeft4 = document.getElementById('arrowLeft4');

    //pre sekciu mini obrazkov v galerii
    const arrowRight5 = document.getElementById('arrowRight5');
    const arrowLeft5 = document.getElementById('arrowLeft5');
    const thumbnailList = document.querySelector('.thumbnail-list');

    //pre sekciu fariem
    const carouselRow6 = document.getElementById('carouselRow6');
    const arrowRight6 = document.getElementById('arrowRight6');
    const arrowLeft6 = document.getElementById('arrowLeft6');

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

    if (arrowRight7 && carouselRow7) {
        arrowRight7.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;

            carouselRow7.style.transform = 'translateX(-25%)';

            carouselRow7.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    const firstCol = carouselRow7.querySelector('.col');
                    carouselRow7.appendChild(firstCol);

                    carouselRow7.style.transition = 'none';
                    carouselRow7.style.transform = 'none';

                    carouselRow7.offsetHeight;

                    carouselRow7.style.transition = 'transform 0.3s ease';

                    carouselRow7.removeEventListener('transitionend', handler);
                    isAnimating = false;
                }
            });
        });
    }

    if (arrowLeft7 && carouselRow7) {
        arrowLeft7.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;

            const allCols = carouselRow7.querySelectorAll('.col');
            const lastCol = allCols[allCols.length - 1];
            carouselRow7.insertBefore(lastCol, allCols[0]);

            carouselRow7.style.transition = 'none';
            carouselRow7.style.transform = 'translateX(-25%)';

            carouselRow7.offsetHeight;

            carouselRow7.style.transition = 'transform 0.3s ease';
            carouselRow7.style.transform = 'none';

            carouselRow7.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    carouselRow7.removeEventListener('transitionend', handler);
                    isAnimating = false;
                }
            });
        });
    }

    if (arrowRight8 && carouselRow8) {
        arrowRight8.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;

            carouselRow8.style.transform = 'translateX(-25%)';

            carouselRow8.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    const firstCol = carouselRow8.querySelector('.col');
                    carouselRow8.appendChild(firstCol);

                    carouselRow8.style.transition = 'none';
                    carouselRow8.style.transform = 'none';

                    carouselRow8.offsetHeight;

                    carouselRow8.style.transition = 'transform 0.3s ease';

                    carouselRow8.removeEventListener('transitionend', handler);
                    isAnimating = false;
                }
            });
        });
    }

    if (arrowLeft8 && carouselRow8) {
        arrowLeft8.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;

            const allCols = carouselRow8.querySelectorAll('.col');
            const lastCol = allCols[allCols.length - 1];
            carouselRow8.insertBefore(lastCol, allCols[0]);

            carouselRow8.style.transition = 'none';
            carouselRow8.style.transform = 'translateX(-25%)';

            carouselRow8.offsetHeight;

            carouselRow8.style.transition = 'transform 0.3s ease';
            carouselRow8.style.transform = 'none';

            carouselRow8.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    carouselRow8.removeEventListener('transitionend', handler);
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

    //animacie a scrollovanie sekcia mini obrazkov
    if (arrowRight5 && thumbnailList) {
        arrowRight5.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;
            const firstItem = thumbnailList.children[0];
            const itemWidth = firstItem.offsetWidth;
            thumbnailList.style.transition = 'transform 0.3s ease';
            thumbnailList.style.transform = `translateX(-${itemWidth}px)`;

            thumbnailList.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    thumbnailList.appendChild(firstItem);
                    thumbnailList.style.transition = 'none';
                    thumbnailList.style.transform = 'none';
                    void thumbnailList.offsetWidth;
                    thumbnailList.style.transition = 'transform 0.3s ease';
                    thumbnailList.removeEventListener('transitionend', handler);
                    isAnimating = false;
                }
            });
        });
    }

    if (arrowLeft5 && thumbnailList) {
        arrowLeft5.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;
            const items = thumbnailList.children;
            const lastItem = items[items.length - 1];
            thumbnailList.insertBefore(lastItem, items[0]);
            thumbnailList.style.transition = 'none';
            const itemWidth = lastItem.offsetWidth;
            thumbnailList.style.transform = `translateX(-${itemWidth}px)`;
            void thumbnailList.offsetWidth;
            thumbnailList.style.transition = 'transform 0.3s ease';
            thumbnailList.style.transform = 'none';

            thumbnailList.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    thumbnailList.removeEventListener('transitionend', handler);
                    isAnimating = false;
                }
            });
        });
    }

    //animacie na scrollovanie sekciou fariem
    if (arrowRight6 && carouselRow6) {
        arrowRight6.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;

            carouselRow6.style.transform = 'translateX(-25%)';

            carouselRow6.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    const firstCol = carouselRow6.querySelector('.col');
                    carouselRow6.appendChild(firstCol);

                    carouselRow6.style.transition = 'none';
                    carouselRow6.style.transform = 'none';

                    carouselRow6.offsetHeight;

                    carouselRow6.style.transition = 'transform 0.3s ease';

                    carouselRow6.removeEventListener('transitionend', handler);
                    isAnimating = false;
                }
            });
        });
    }

    if (arrowLeft6 && carouselRow6) {
        arrowLeft6.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;

            const allCols = carouselRow6.querySelectorAll('.col');
            const lastCol = allCols[allCols.length - 1];
            carouselRow6.insertBefore(lastCol, allCols[0]);

            carouselRow6.style.transition = 'none';
            carouselRow6.style.transform = 'translateX(-25%)';

            carouselRow6.offsetHeight;

            carouselRow6.style.transition = 'transform 0.3s ease';
            carouselRow6.style.transform = 'none';

            carouselRow6.addEventListener('transitionend', function handler(e) {
                if (e.propertyName === 'transform') {
                    carouselRow6.removeEventListener('transitionend', handler);
                    isAnimating = false;
                }
            });
        });
    }


    //animacia na fillnutie srdiecka pri kliknuti
    document.querySelectorAll('.favorite-btn').forEach(favBtn => {
        favBtn.addEventListener('click', () => {
            const icon = favBtn.querySelector('.material-icons');
            if (icon && icon.textContent.trim() === 'favorite_border') {
                icon.textContent = 'favorite';
                icon.classList.add('filled');
            } else if (icon) {
                icon.textContent = 'favorite_border';
                icon.classList.remove('filled');
            }
        });
    });

//=========================== Product Listing ===============================

    //otvorenie filtrov
    const filterToggle = document.querySelector('.filter-toggle');
    const filterSection = document.querySelector('.filter-section');
    if (filterToggle && filterSection) {
        filterToggle.addEventListener('click', () => {
            filterSection.classList.toggle('open');
        });
    }

    //zatvorenie filtrov pri kliknuti mimo nich
    document.addEventListener('click', (e) => {
        if (filterSection && filterSection.classList.contains('open')) {
            if (!filterSection.contains(e.target) && !filterToggle.contains(e.target)) {
                filterSection.classList.remove('open');
            }
        }
    });

    //zatvorenie filtrov pri kliknuti na talcidla na spodku
    const filterButtons = filterSection ? filterSection.querySelectorAll('.filter-buttons button') : null;
    if (filterButtons) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterSection.classList.remove('open');
            });
        });
    }

    //funkcia na zmiznutie placeholder textov min a max pri nizkej sirke ich rodica
    function updatePlaceholders() {
        const priceRanges = document.querySelectorAll('.price-range');
        if (priceRanges) {
            priceRanges.forEach(priceRange => {
                const rect = priceRange.getBoundingClientRect();
                const minInputs = priceRange.querySelectorAll('.min-price');
                const maxInputs = priceRange.querySelectorAll('.max-price');

                if (rect.width < 173) {
                    minInputs.forEach(input => input.placeholder = '');
                    maxInputs.forEach(input => input.placeholder = '');
                } else {
                    minInputs.forEach(input => input.placeholder = 'Min');
                    maxInputs.forEach(input => input.placeholder = 'Max');
                }
            });
        }

        const distRanges = document.querySelectorAll('.distance-range');
        if (distRanges) {
            distRanges.forEach(distRange => {
                const rect = distRange.getBoundingClientRect();
                const minInputs = distRange.querySelectorAll('.min-distance');
                const maxInputs = distRange.querySelectorAll('.max-distance');

                if (rect.width < 194) {
                    minInputs.forEach(input => input.placeholder = '');
                    maxInputs.forEach(input => input.placeholder = '');
                } else {
                    minInputs.forEach(input => input.placeholder = 'Min');
                    maxInputs.forEach(input => input.placeholder = 'Max');
                }
            });
        }
    }

    updatePlaceholders();
    window.addEventListener('resize', updatePlaceholders);

    //nastavenie posledne kliknutej stranky na aktivnu
    document.querySelectorAll('.pagination .page-item').forEach(pageItem => {
        pageItem.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.pagination .page-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        });
    });

    //===================== Product Detail ===========================//

    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail-list .col img');

    //zamena hlavneho obrazka za kliknuty z mini galerie
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', () => {
            if (mainImage) {
                mainImage.src = thumb.src;
                mainImage.alt = thumb.alt;
            }
        });
    });

    //funkcia tlacidiel na zmenu mnozstva pre kg aj pre ks
    document.querySelectorAll('.quantity').forEach(container => {
        const qtyInput = container.querySelector('.quantity-input-kg');
        const btnPlus = container.querySelector('.plus');
        const btnMinus = container.querySelector('.minus');

        if (btnPlus && qtyInput) {
            btnPlus.addEventListener('click', () => {
                let currentValue = parseFloat(qtyInput.value) || 1;
                qtyInput.value = (currentValue + 1).toFixed(2);
            });
        }

        if (btnMinus && qtyInput) {
            btnMinus.addEventListener('click', () => {
                let currentValue = parseFloat(qtyInput.value) || 1;
                if (currentValue > 1) {
                    qtyInput.value = (currentValue - 1).toFixed(2);
                }
            });
        }
    });

    document.querySelectorAll('.quantity').forEach(container => {
        const qtyInput = container.querySelector('.quantity-input-ks');
        const btnPlus = container.querySelector('.plus');
        const btnMinus = container.querySelector('.minus');

        if (btnPlus && qtyInput) {
            btnPlus.addEventListener('click', () => {
                let currentValue = parseInt(qtyInput.value) || 1;
                qtyInput.value = (currentValue + 1);
            });
        }

        if (btnMinus && qtyInput) {
            btnMinus.addEventListener('click', () => {
                let currentValue = parseInt(qtyInput.value) || 1;
                if (currentValue > 1) {
                    qtyInput.value = (currentValue - 1);
                }
            });
        }
    });

    //zaokruhlenie na 2 desatiny pre kg a na 0 desatin pre ks
    document.querySelectorAll('.quantity-input-kg').forEach(input => {
        input.addEventListener('change', () => {
            let value = parseFloat(input.value);
            if (!isNaN(value)) {
                input.value = value.toFixed(2);
            }
        });
    });

    document.querySelectorAll('.quantity-input-ks').forEach(input => {
        input.addEventListener('change', () => {
            let value = parseFloat(input.value);
            if (!isNaN(value)) {
                input.value = value.toFixed(0);
            }
        });
    });

    //zmena textu v tlacidle pri vybrati ks alebo kg
    document.querySelectorAll('.product-detail-dropdown .dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            const selectedText = e.target.textContent.trim();
            const dropdownToggle = e.target.closest('.dropdown').querySelector('.dropdown-toggle');
            if (dropdownToggle) {
                dropdownToggle.textContent = selectedText;
            }
        });
    });

    const farms = document.querySelectorAll('.farm-col.card');

    //nastavenie ramceka na signalizaciu vyberu pri kliknuti na jednu z fariem
    farms.forEach(farm => {
        farm.addEventListener('click', () => {
            farms.forEach(f => f.classList.remove('selected'));
            farm.classList.add('selected');
        });
    });

    //================ Cart - Items ===================//

    //oznacenie vsetkych produktov naraz
    const checkAll = document.getElementById('checkAllCart');
    if (checkAll) {
        checkAll.addEventListener('change', () => {
            const allCheckboxes = document.querySelectorAll('input.form-check-input[type="checkbox"]:not(#checkAllCart)');
            allCheckboxes.forEach(chk => {
                chk.checked = checkAll.checked;
            });
        });
    }

});
