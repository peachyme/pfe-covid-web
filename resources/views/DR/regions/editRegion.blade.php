<div class="modal fade" id="editRegionModal{{$region->id}}" tabindex="-1" aria-labelledby="editRegionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editRegionModalLabel">Editer la direction régionale <strong>{{$region->code_region}}</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{ route('DR.regions.update', $region) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-2">
                        <label for="code_region" class="form-label fw-bold">Code :</label>
                        <input type="text" class="form-control form-control-custom" id="code_region" name="code_region" value="{{$region->code_region}}" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="libelle_region" class="form-label fw-bold">Libellé :</label>
                        <input type="text" class="form-control form-control-custom" id="libelle_region" name="libelle_region" value="{{ $region->libellé_region }}" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
