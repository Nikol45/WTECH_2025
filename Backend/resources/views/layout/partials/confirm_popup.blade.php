<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalTitle">Potvrdenie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="confirmModalForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p id="confirmModalText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Zrušiť</button>
                    <button type="submit" class="btn btn-danger">Vymazať</button>
                </div>
            </form>
        </div>
    </div>
</div>
