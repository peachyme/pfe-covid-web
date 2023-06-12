<div class="modal fade" id="addCMTModel" tabindex="-1" aria-labelledby="addCMTModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="addCMTModelLabel">Ajouter un nouveau CMT</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{ route('CMT.cmts.store') }}" method="POST"  class="needs-validation" novalidate>
                    @csrf
                    @method('POST')
                    <div class="mb-2">
                        <label for="code_cmt" class="form-label fw-bold">Code :</label>
                        <input type="text" class="form-control form-control-custom" id="code_cmt" name="code_cmt" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="libelle_cmt" class="form-label fw-bold">Libellé :</label>
                        <input type="text" class="form-control form-control-custom" id="libelle_cmt" name="libelle_cmt" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
