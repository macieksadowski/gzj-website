<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Potwierdź usunięcie</h5>
            </div>
                @csrf
                <input type="hidden" id="entityId" name="id" value="{{ $entityId }}">
                <div class="modal-body ">
                    Czy na pewno chcesz usunąć {{ $entityName }} ?
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="delete" class="btn btn-danger btn-primary">Usuń</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="cancelButton">Anuluj</button>
                </div>
            </form>
        </div>
    </div>
</div>
