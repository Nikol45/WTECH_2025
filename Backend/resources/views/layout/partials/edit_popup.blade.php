<!-- ========== EDIT MODAL ========== -->
<div id="editModal" class="custom-modal d-none">
    <div class="modal-content p-4 border-0 shadow position-relative">

        <!-- Zavrieť -->
        <button type="button" class="btn material-icons position-absolute end-0 top-0 m-2"
                aria-label="Zavrieť" onclick="closeModal('editModal')">
            close
        </button>

        <!-- Nadpis -->
        <h2 id="editModalTitle" class="mb-4 text-center text-uppercase modal-title">Upraviť údaje</h2>

        <form id="editModalForm" method="POST" enctype="multipart/form-data">
            <!-- CSRF -->
            <input type="hidden" name="_token" value="">

            <!-- Dynamicky vkladané polia -->
            <div id="dynamicFields"></div>

            <!-- ===== Hviezdičky ===== -->
            <div id="ratingField" class="mb-3 d-none">
                <label id="ratingLabel" class="form-label d-block mb-1">
                    Počet hviezdičiek
                </label>

                <!-- skutočné číslo, ktoré pôjde do POST -->
                <input type="hidden"
                       id="ratingInput"
                       name="rating"
                       min="1"
                       max="5"
                       step="0.5"
                       value="0">

                <div id="starPicker" class="d-flex gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="material-icons star empty selectable"
                              data-index="{{ $i }}">
                            star_outline
                        </span>
                    @endfor
                </div>
            </div>

            <!-- Chyby + tlačidlá -->
            <div id="editModalErrors" class="text-danger my-2"></div>

            <div class="d-flex justify-content-between gap-2 mt-4">
                <button type="button" class="btn btn-secondary w-50" onclick="closeModal('editModal')">
                    Zrušiť
                </button>

                <button type="submit" class="btn btn-confirm w-50">
                    Uložiť
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        /* ========== Hviezdičky ========== */
        const ratingField = document.getElementById('ratingField');
        const ratingInput = document.getElementById('ratingInput');
        const ratingLabel = document.getElementById('ratingLabel');
        const stars       = [...document.querySelectorAll('#starPicker .star')];

        const refreshStars = val => {
            stars.forEach(star => {
                const idx = +star.dataset.index;          // 1‒5
                star.classList.remove('filled','half-filled','empty');

                if (val >= idx) {
                    star.textContent = 'star';
                    star.classList.add('filled');
                } else if (val >= idx - 0.5) {
                    star.textContent = 'star_half';
                    star.classList.add('half-filled');
                } else {
                    star.textContent = 'star_outline';
                    star.classList.add('empty');
                }
            });
        };

        /* klik – rozozná ľavú / pravú polovicu ikony */
        stars.forEach(star =>
            star.addEventListener('click', e => {
                const rect = star.getBoundingClientRect();
                const idx  = +star.dataset.index;
                const isLeftHalf = (e.clientX - rect.left) < rect.width / 2;
                const newVal = isLeftHalf ? idx - 0.5 : idx;

                ratingInput.value = newVal;
                refreshStars(newVal);
            })
        );

        /* ========== Otváranie modalu ========== */
        window.openEditModal = function (config = {}) {
            const modal  = document.getElementById('editModal');
            const title  = document.getElementById('editModalTitle');
            const form   = document.getElementById('editModalForm');
            const fields = document.getElementById('dynamicFields');

            /* Header + form atribúty */
            title.textContent = config.title || 'Upraviť údaje';
            form.action  = config.submitUrl || '#';
            form.method  = config.method   || 'POST';
            form.enctype = config.enctype  || 'multipart/form-data';

            // method spoofing pre PUT, PATCH, DELETE
            const methodInput = form.querySelector('input[name="_method"]');
            if (config.method && config.method.toUpperCase() !== 'POST') {
                if (!methodInput) {
                    const spoof = document.createElement('input');
                    spoof.type  = 'hidden';
                    spoof.name  = '_method';
                    spoof.value = config.method;
                    form.appendChild(spoof);
                } else {
                    methodInput.value = config.method;
                }
            } else if (methodInput) {
                methodInput.remove();
            }

            /* CSRF */
            form.querySelector('input[name="_token"]').value =
                document.querySelector('meta[name="csrf-token"]')?.content || '';

            /* Reset */
            fields.innerHTML = '';
            ratingField.classList.add('d-none');

            /* Generovanie polí */
            (config.fields || []).forEach(field => {
                /* ----- rating ----- */
                if (field.name === 'rating') {
                    ratingLabel.textContent = field.label || 'Počet hviezdičiek';
                    ratingInput.min   = field.min  ?? 1;
                    ratingInput.max   = field.max  ?? 5;
                    ratingInput.step  = field.step ?? 0.5;
                    ratingInput.value = field.value ?? 0;
                    refreshStars(+ratingInput.value);
                    ratingField.classList.remove('d-none');
                    return;
                }

                /* ----- ostatné vstupy ----- */
                const wrap  = document.createElement('div');
                wrap.className = 'mb-3';

                /* label */
                const label = document.createElement('label');
                label.className = 'form-label';
                label.setAttribute('for', field.name);
                label.textContent = field.label || field.name;
                wrap.appendChild(label);

                /* input / textarea */
                let input;
                if (field.type === 'textarea') {
                    input = document.createElement('textarea');
                    input.className = 'form-control input-custom';
                    input.id   = field.name;
                    input.name = field.name;
                    input.required = !!field.required;
                    input.textContent = field.value ?? '';
                } else {
                    input = document.createElement('input');
                    input.className = 'form-control input-custom';
                    input.type  = field.type || 'text';
                    input.id    = field.name;
                    input.name  = field.name;
                    input.required = !!field.required;
                    if (field.type !== 'file') input.value = field.value ?? '';
                }
                wrap.appendChild(input);
                fields.appendChild(wrap);
            });

            /* Zobraz modal */
            modal.classList.remove('d-none');
            document.getElementById('overlayBackground')?.classList.remove('d-none');
        };
    })();
</script>
