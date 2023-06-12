<div class="modal fade" id="addPVModal{{$reunion->id}}" tabindex="-1" aria-labelledby="addPVModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="addPVModalLabel">Ajouter le PV de la réunion</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4">
                <form action="{{ route('reunions.update', $reunion->id) }}" method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="pv" class="form-label fw-bold">PV :</label>
                        <input type="file" class="form-control form-control-custom" id="pv" name="pv" placeholder="wtv" required>
                        <div class="invalid-feedback">
                            Veuillez choisir un fichier.
                        </div>
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
