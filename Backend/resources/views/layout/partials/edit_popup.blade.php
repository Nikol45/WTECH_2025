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
    (() => {
        /* ========== Hviezdičky ========== */
        const ratingField = document.getElementById('ratingField');
        const ratingInput = document.getElementById('ratingInput');
        const ratingLabel = document.getElementById('ratingLabel');
        const stars       = [...document.querySelectorAll('#starPicker .star')];

        const refreshStars = val => {
            stars.forEach(star => {
                const i = +star.dataset.index;
                star.textContent = val >= i       ? 'star'
                    :  val >= i - 0.5 ? 'star_half'
                        : 'star_outline';
            });
        };
        stars.forEach(star =>
            star.addEventListener('click', e => {
                const {left, width} = star.getBoundingClientRect();
                const i  = +star.dataset.index;
                ratingInput.value = (e.clientX - left) < width / 2 ? i - .5 : i;
                refreshStars(+ratingInput.value);
            })
        );

        /* ========== Otváranie modálu ========== */
        window.openEditModal = cfg => {
            const modal  = document.getElementById('editModal');
            const title  = document.getElementById('editModalTitle');
            const form   = document.getElementById('editModalForm');
            const fields = document.getElementById('dynamicFields');

            /* hlavička + atribúty formulára */
            title.textContent = cfg.title   ?? 'Upraviť údaje';
            form.action       = cfg.submitUrl ?? '#';
            form.method       = cfg.method  ?? 'POST';
            form.enctype      = cfg.enctype ?? 'multipart/form-data';

            /* method spoofing */
            let spoof = form.querySelector('[name=_method]');
            if (cfg.method && cfg.method.toUpperCase() !== 'POST') {
                if (!spoof) {
                    spoof = document.createElement('input');
                    spoof.type = 'hidden'; spoof.name = '_method';
                    form.appendChild(spoof);
                }
                spoof.value = cfg.method;
            } else if (spoof) spoof.remove();

            /* CSRF */
            form.querySelector('[name=_token]').value =
                document.querySelector('meta[name=csrf-token]')?.content || '';

            /* reset */
            fields.innerHTML = ''; ratingField.classList.add('d-none');

            /* ========= dynamické polia ========= */
            (cfg.fields ?? []).forEach(f => {
                /* rating */
                if (f.name === 'rating') {
                    ratingLabel.textContent = f.label ?? 'Počet hviezdičiek';
                    Object.assign(ratingInput, {min: f.min ?? 1, max: f.max ?? 5,
                        step: f.step ?? .5,
                        value: f.value ?? 0});
                    refreshStars(+ratingInput.value);
                    return ratingField.classList.remove('d-none');
                }

                /* obal + label */
                const wrap  = Object.assign(document.createElement('div'),
                    {className: 'mb-3'});
                const label = Object.assign(document.createElement('label'),
                    {className: 'form-label', htmlFor: f.name,
                        textContent: f.label ?? f.name});
                wrap.appendChild(label);

                /* input / textarea */
                let inp;
                if (f.type === 'textarea') {
                    inp = Object.assign(document.createElement('textarea'),
                        {className: 'form-control', id: f.name, name: f.name,
                            rows: 4, required: !!f.required, textContent: f.value ?? ''});
                } else {
                    inp = document.createElement('input');
                    inp.className = 'form-control';
                    inp.id   = f.name;
                    inp.name = f.name;
                    inp.type = f.type || 'text';
                    if (f.required)   inp.required = true;
                    if (f.accept)     inp.accept   = f.accept;
                    if (f.multiple)   inp.multiple = true;
                    if (f.step)       inp.step     = f.step;
                    if (f.min !== undefined) inp.min = f.min;

                    if (f.type === 'checkbox') {
                        inp.classList.remove('form-control');
                        if (f.value) inp.checked = true;
                        // label  za checkboxom
                        wrap.classList.add('form-check');
                        label.classList.add('form-check-label');
                        inp.classList.add('form-check-input');
                        wrap.prepend(inp);
                        fields.appendChild(wrap);
                        return;
                    }

                    if (f.type !== 'file') inp.value = f.value ?? '';
                }
                wrap.appendChild(inp);

                /* náhľad pre file input */
                if (inp.type === 'file') {
                    const preview = Object.assign(document.createElement('div'),
                        {id: `${f.name}-preview`,
                            className: 'd-flex flex-wrap gap-2 mt-2'});
                    wrap.appendChild(preview);

                    inp.addEventListener('change', () => {
                        preview.innerHTML = '';
                        [...inp.files].forEach(file => {
                            const img = new Image();
                            img.src   = URL.createObjectURL(file);
                            img.style = 'width:120px;height:120px;object-fit:cover;' +
                                'border:1px solid #ddd;border-radius:.5rem';
                            preview.appendChild(img);
                        });
                    });
                }
                fields.appendChild(wrap);
            });

            /* zobraziť modál */
            modal.classList.remove('d-none');
            document.getElementById('overlayBackground')?.classList.remove('d-none');
        };
    })();
</script>
