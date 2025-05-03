document.addEventListener('DOMContentLoaded', () => {
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

    // ======== STAV PRIHLÁSENIA & ELEMENTY ========
    let isLoggedIn = window.isLoggedIn || false;
    const overlay            = document.getElementById('overlayBackground');
    const profileBtn         = document.getElementById('profileBtn');
    const profileMenu        = document.getElementById('profileMenu');
    const mobileMenuEl       = document.getElementById('mobileMenu');
    const mobileProfileMenu  = document.getElementById('mobileProfileMenu');

    // ======== OTVORENIE / ZATVORENIE MODÁLOV ========
    function openModal(id)  { document.getElementById(id).classList.remove('d-none'); overlay.classList.remove('d-none'); }
    function closeModal(id) { document.getElementById(id).classList.add('d-none');    overlay.classList.add('d-none');    }

    // ======== AKTUALIZÁCIA MENU (desktop + mobil) ========
    function updateMenus() {
        // desktop
        profileMenu.innerHTML = isLoggedIn
            ? `
            <li><a class="dropdown-item" href="${routes.profile}">Môj profil</a></li>
            <li><a class="dropdown-item" href="${routes.profileHistory}">Objednávky</a></li>
            <li><a class="dropdown-item" href="${routes.profileReviews}">Recenzie</a></li>
            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Odhlásiť</a>
</li>`
            : `<li><a class="dropdown-item" href="#" id="loginLink">Prihlásiť sa</a></li>`;
        // mobil
        mobileProfileMenu.innerHTML = isLoggedIn
            ? `
            <li class="mb-3 fw-bold fs-5"><a href="${routes.profile}">Môj profil</a></li>
            <li class="mb-3 fw-bold fs-5"><a href="${routes.profileHistory}">Objednávky</a></li>
            <li class="mb-3 fw-bold fs-5"><a href="${routes.profileReviews}">Recenzie</a></li>
            <li class="mb-3 fw-bold fs-5"><a href="#" id="logoutLink">Odhlásiť</a></li>`
            : `<li class="mb-3 fw-bold fs-5"><a href="#" id="loginLink">Prihlásiť sa</a></li>`;
    }
    updateMenus();

    // ======== AJAX FORMY (login + registration) ========
    function handleAjaxForm(formId, modalId, errorsId) {
        const form = document.getElementById(formId);
        const errs = document.getElementById(errorsId);
        if (!form || !errs) return;

        form.addEventListener('submit', async e => {
            e.preventDefault();
            errs.textContent = '';
            const data = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': data.get('_token'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: data
                });

                if (res.ok) {
                    isLoggedIn = true;
                    updateMenus();
                    closeModal(modalId);
                    location.reload();

                } else if (res.status === 422) {
                    const json = await res.json();
                    errs.textContent = Object.values(json.errors || {}).flat().join(' ');
                } else {
                    const json = await res.json().catch(() => ({}));
                    errs.textContent = json.error || json.message || 'Neočakávaná chyba.';
                }
            } catch {
                errs.textContent = 'Chyba pripojenia na server.';
            }
        });
    }
    handleAjaxForm('loginForm',        'loginModal',        'loginErrors');
    handleAjaxForm('registrationForm', 'registrationModal', 'registrationErrors');

    // ======== AJAX LOGOUT ========

    profileBtn?.addEventListener('click', e => {
        e.stopPropagation();
        profileMenu.classList.toggle('show');
    });

    // ======== EVENT DELEGATION ========
    document.addEventListener('click', e => {
        const id = e.target.id;
        // desktop: toggle dropdown
        // login
        if (id === 'loginLink') {
            e.preventDefault();
            openModal('loginModal');
        }

        // hamburger – otvorenie login modal
        if (id === 'mobileProfileLink') {
            e.preventDefault();
            bootstrap.Offcanvas.getInstance(mobileMenuEl)?.hide();
            openModal('loginModal');
        }
    });

    // zatváranie dropdownu klikom mimo
    document.addEventListener('click', e => {
        if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
            profileMenu.classList.remove('show');
        }
    });

    // zatvorenie modálu klikom mimo wrapper
    document.querySelectorAll('.custom-modal').forEach(modal =>
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal(modal.id);
        })
    );

    // reset štýlov po zatvorení offcanvas (hamburger)
    mobileMenuEl?.addEventListener('hidden.bs.offcanvas', () => {
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
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
    const deliveryRow = document.getElementById('deliveryRow');
    const priceRow = document.getElementById('priceRow');

    const deliveryGlsSt = document.querySelector('input[id="delivery_GLS_standard"]');
    const deliveryGlsEx = document.querySelector('input[id="delivery_GLS_express"]');
    const deliveryPersonal = document.querySelector('input[id="delivery_personal"]');

    const payDobierkaWrapper = document.getElementById('wrapper_payment_cash_on_delivery');
    const payPickupWrapper = document.getElementById('wrapper_payment_cash_on_pickup');
    const priceDeliveryRow = document.getElementById('price_deliveryRow');
    const priceDobierkaRow = document.getElementById('price_dobierkaRow');
    const payPickup = document.querySelector('input[name="paymentMethod"][value="cash_on_pickup"]');
    const payDobierka = document.querySelector('input[name="paymentMethod"][value="cash_on_delivery"]');

    const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');

    function updateDeliveryDisplay() {
        const isGLS = deliveryGlsSt?.checked || deliveryGlsEx?.checked;
        const isPersonal = deliveryPersonal?.checked;

        if (deliveryRow && priceRow && priceDeliveryRow && payDobierkaWrapper && payPickupWrapper && payDobierka && payPickup) {
            if (isGLS) {
                deliveryRow.classList.remove('d-none');
                priceRow.classList.remove('d-none');
                priceDeliveryRow.classList.remove('d-none');
                payDobierkaWrapper.classList.remove('d-none');
                payPickupWrapper.classList.add('d-none');
            } else if (isPersonal) {
                deliveryRow.classList.add('d-none');
                priceRow.classList.remove('d-none');
                priceDeliveryRow.classList.add('d-none');
                payDobierkaWrapper.classList.add('d-none');
                payPickupWrapper.classList.remove('d-none');
                payDobierka.checked = false;
            } else {
                deliveryRow.classList.add('d-none');
                priceRow.classList.add('d-none');
                priceDeliveryRow.classList.add('d-none');
                payDobierkaWrapper.classList.add('d-none');
                payPickupWrapper.classList.add('d-none');
                payPickup.checked = false;
            }
        }
        const pricesEl = document.getElementById('delivery-prices');
        const selected = document.querySelector('input[name="deliveryMethod"]:checked')?.value;

        if (selected && pricesEl) {
            const price = pricesEl.getAttribute(`data-price-${selected}`);
            const eta = pricesEl.getAttribute(`data-eta-${selected}`);

            const priceElem = document.querySelector('#deliveryRow .delivery-price');
            const etaElem = document.querySelector('#deliveryRow .delivery-eta');

            const summaryPriceElem = document.querySelector('#price_deliveryRow .delivery-price');

            if (priceElem) priceElem.textContent = price + ' €';
            if (etaElem) etaElem.textContent = eta;
            if (summaryPriceElem) summaryPriceElem.textContent = price + ' €';
        }

        // Obnov zobrazenie dobierky
        updateDobierkaDisplay();
    }

    function updateDobierkaDisplay() {
        if (payDobierka && priceDobierkaRow) {
            if (payDobierka?.checked) {
                priceDobierkaRow.classList.remove('d-none');
            } else {
                priceDobierkaRow.classList.add('d-none');
            }
        }

        // ===== Prepočet Celkom =====
        const grandTotalElem = document.getElementById('grand-total');
        if (grandTotalElem) {
            const base = parseFloat(grandTotalElem.dataset.base) || 0;

            const deliveryText = document.querySelector('#price_deliveryRow .delivery-price')?.textContent?.trim();
            const delivery = deliveryText ? parseFloat(deliveryText.replace('€', '').replace(',', '.')) : 0;

            const codText = document.querySelector('#price_dobierkaRow .dobierka-price')?.textContent?.trim();
            const cod = (payDobierka.checked && codText) ? parseFloat(codText.replace('€', '').replace(',', '.')) : 0;

            const sum = base + delivery + cod;

            grandTotalElem.textContent = sum.toFixed(2).replace('.', ',');
        }
    }


    [deliveryGlsSt, deliveryGlsEx, deliveryPersonal].forEach(input => {
        input?.addEventListener('change', updateDeliveryDisplay);
    });

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', updateDobierkaDisplay);
    });

    updateDeliveryDisplay()

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

    //funkcia tlacidiel na zmenu mnozstva
    document.querySelectorAll('.quantity').forEach(container => {
        const qtyInput = container.querySelector('input[name="quantity"]');
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

    document.querySelectorAll('.integer-only').forEach(input => {
        input.addEventListener('change', () => {
            let value = parseFloat(input.value);
            if (!isNaN(value)) {
                input.value = value.toFixed(0);
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
        checkAll.addEventListener('change', function(){
            document
                .querySelectorAll('input[name="items[]"]')
                .forEach(cb => cb.checked = this.checked);
        });

    }

    //pridanie a odstranenie classy submenu-open pre dropdowns kategorii
    document.querySelectorAll('#categoriesBar .dropdown-toggle')
        .forEach(btn=>{
            btn.addEventListener('shown.bs.dropdown', () => {
                document.getElementById('categoriesBar')
                    .classList.add('submenu-open');
            });
            btn.addEventListener('hidden.bs.dropdown', () => {
                document.getElementById('categoriesBar')
                    .classList.remove('submenu-open');
            });
        });

    //funkcie na dynamicky update total ceny a ceny bez dph
    const token = document.querySelector('meta[name="csrf-token"]').content;

    function formatMoney(n) {
        return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcGrandTotal() {
        let sum = 0;
        document.querySelectorAll('.quantity-input').forEach(input => {
            const unitPrice = parseFloat(input.dataset.unitPrice);
            const qty = parseInt(input.value) || 0;
            sum += unitPrice * qty;
        });
        document.getElementById('cart-grand-total').textContent = formatMoney(sum);

        const noDph = sum / 1.2;
        document.getElementById('cart-no-dph').textContent = formatMoney(noDph);
    }

    function updateLineTotal(fpid, qty) {
        const unitPrice = parseFloat(
            document.querySelector(`.quantity-input[data-fpid="${fpid}"]`).dataset.unitPrice
        );
        const total = unitPrice * qty;
        const span  = document.querySelector(`.line-total[data-fpid="${fpid}"]`);
        span.textContent = formatMoney(total) + ' €';
    }

    function updateCartQty(fpid, qty) {
        fetch(`/cart-items/${fpid}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ quantity: qty })
        })
            .then(res => res.json())
            .then(json => {
                if (json.success) {
                    updateLineTotal(fpid, qty);
                    recalcGrandTotal();
                } else {
                    console.error('Cart update failed');
                }
            });
    }

    //tlacidla plus minus v kosiku
    document.querySelectorAll('.quantity-button').forEach(btn => {
        btn.addEventListener('click', () => {
            const fpid  = btn.dataset.fpid;
            const input = document.querySelector(`.quantity-input[data-fpid="${fpid}"]`);
            let qty = parseInt(input.value) || 1;
            qty += btn.classList.contains('plus') ? 1 : -1;
            if (qty < 1) qty = 1;
            input.value = qty;
            updateCartQty(fpid, qty);
        });
    });

    //ked pouzivatel manualne zada mnozstvo
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', () => {
            let qty = parseInt(input.value) || 1;
            if (qty < 1) qty = 1;
            input.value = qty;
            updateCartQty(input.dataset.fpid, qty);
        });
    });


    window.openConfirmModal = function ({title, text, submitUrl}) {
        const modalEl   = document.getElementById('confirmModal');
        const bsModal   = bootstrap.Modal.getOrCreateInstance(modalEl);

        // vyplň texty
        document.getElementById('confirmModalTitle').innerText = title || 'Potvrdenie';
        document.getElementById('confirmModalText').innerText  = text  || 'Naozaj chcete pokračovať?';

        // nastav akčnú url
        const form = document.getElementById('confirmModalForm');
        form.action = submitUrl;

        bsModal.show();
    };

});
