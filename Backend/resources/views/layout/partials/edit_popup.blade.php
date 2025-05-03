<div id="editModal" class="custom-modal d-none">
    <div class="modal-content p-4 border-0 shadow position-relative">
        <button type="button" class="btn material-icons position-absolute end-0 top-0 m-2"
                aria-label="Zavrieť" onclick="closeModal('editModal')">
            close
        </button>

        <h2 id="editModalTitle" class="mb-4 text-center text-uppercase modal-title">Upraviť údaje</h2>

        <form id="editModalForm" method="POST" enctype="multipart/form-data"></form>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function openEditModal(config) {
        const modal = document.getElementById('editModal');
        const title = document.getElementById('editModalTitle');
        const form  = document.getElementById('editModalForm');

        title.textContent = config.title || 'Upraviť údaje';

        // čistennie starého obsahu
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        let html = `<input type="hidden" name="_token" value="${token}">`;


        (config.fields || []).forEach(field => {
            // ak je priamo HTML, tak ho vložíme
            if (field.html) {
                html += field.html;
                return;
            }

            // inak klasický input
            html += `
        <div class="mb-3">
            <label class="form-label" for="${field.name}">${field.label}</label>
            <input class="form-control input-custom"
                   type="${field.type || 'text'}"
                   id="${field.name}" name="${field.name}"
                   ${field.required ? 'required' : ''}
                   ${field.type === 'file' ? '' : `value="${field.value ?? ''}"`}>
        </div>`;
        });


        html += `
            <div id="editModalErrors" class="text-danger my-2"></div>
            <div class="d-flex justify-content-between gap-2 mt-4">
                <button type="button" class="btn btn-secondary w-50" onclick="closeModal('editModal')">Zrušiť</button>
                <button type="submit" class="btn btn-confirm w-50">Uložiť</button>
            </div>`;

        form.action  = config.submitUrl;
        form.method  = 'POST';
        form.enctype = config.enctype || 'multipart/form-data';
        form.innerHTML = html;

        modal.classList.remove('d-none');
        document.getElementById('overlayBackground').classList.remove('d-none');

        const iconGridTemplate = document.getElementById('avatarIconGrid');
        const iconGridHTML = iconGridTemplate.innerHTML;

        html += iconGridHTML;
    }
</script>
