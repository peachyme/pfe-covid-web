<div class="modal fade" id="editZoneModal{{$zone->id}}" tabindex="-1" aria-labelledby="editZoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editZoneModalLabel">Editer la zone <strong>{{$zone->code_zone}}</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{ route('DR.zones.update', $zone) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-2">
                        <label for="code_zone" class="form-label fw-bold">Code :</label>
                        <input type="text" class="form-control form-control-custom" id="code_zone" name="code_zone" value="{{$zone->code_zone}}" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="libelle_zone" class="form-label fw-bold">Libellé :</label>
                        <input type="text" class="form-control form-control-custom" id="libelle_zone" name="libelle_zone" value="{{ $zone->libelle_zone }}" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="capacite_zone" class="form-label fw-bold">Capacité :</label>
                        <input type="text" class="form-control form-control-custom" id="capacite_zone" name="capacite_zone" value="{{ $zone->capacité_zone }}" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="medecins_zone" class="form-label fw-bold">Effectif médecins :</label>
                        <input type="text" class="form-control form-control-custom" id="medecins_zone" name="medecins_zone" value="{{ $zone->effectif_medecins }}" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="infermiers_zone" class="form-label fw-bold">Effectif infermiers :</label>
                        <input type="text" class="form-control form-control-custom" id="infermiers_zone" name="infermiers_zone" value="{{ $zone->effectif_infermiers }}" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="responsable_zone" class="form-label fw-bold">Résponsable :</label>
                        <select name="responsable_zone" class="form-select form-control form-control-custom" aria-label="Default select example" required>
                            @foreach ($employes as $employe)
                                <option value="{{ $employe->id }}" @if ($zone->responsable_zone == $employe->id) selected @endif>{{ $employe->matricule }} {{ $employe->nom }} {{ $employe->prenom }}</option>
                            @endforeach
                        </select>
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
