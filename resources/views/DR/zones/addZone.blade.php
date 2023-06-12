<div class="modal fade" id="addZoneModel" tabindex="-1" aria-labelledby="addZoneModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="addZoneModelLabel">Ajouter une nouvelle zone de confinement</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{ route('DR.zones.store') }}" method="POST"  class="needs-validation" novalidate>
                    @csrf
                    @method('POST')
                    <div class="mb-2">
                        <label for="region_zone" class="form-label fw-bold">Direction régionale :</label>
                        <select name="region_zone" class="form-select form-control form-control-custom" aria-label="Default select example">
                            <option value="" selected hidden></option>
                            @foreach ($regions as $region)
                                @if ($region->id != 1)
                                <option value="{{ $region->id }}">{{ $region->code_region }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-2">
                                <label for="code_zone" class="form-label fw-bold">Code :</label>
                                <input type="text" class="form-control form-control-custom" id="code_zone" name="code_zone" required>
                                <div class="invalid-feedback">
                                    Veuillez remplir ce champ.
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <label for="libelle_zone" class="form-label fw-bold">Libellé :</label>
                                <input type="text" class="form-control form-control-custom" id="libelle_zone" name="libelle_zone" required>
                                <div class="invalid-feedback">
                                    Veuillez remplir ce champ.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="capacite_zone" class="form-label fw-bold">Capacité :</label>
                        <input type="text" class="form-control form-control-custom" id="capacite_zone" name="capacite_zone" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="medecins_zone" class="form-label fw-bold">Effectif médecins :</label>
                        <input type="text" class="form-control form-control-custom" id="medecins_zone" name="medecins_zone" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="infermiers_zone" class="form-label fw-bold">Effectif infermiers :</label>
                        <input type="text" class="form-control form-control-custom" id="infermiers_zone" name="infermiers_zone" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="responsable_zone" class="form-label fw-bold">Résponsable :</label>
                        <select name="responsable_zone" class="form-select form-control form-control-custom" aria-label="Default select example" required>
                            <option value="" selected hidden></option>
                            @foreach ($employes as $employe)
                                <option value="{{ $employe->id }}">{{ $employe->matricule }} {{ $employe->nom }} {{ $employe->prenom }}</option>
                            @endforeach
                        </select>
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
