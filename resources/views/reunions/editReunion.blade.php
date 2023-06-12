<div class="modal fade" id="editReunionModel{{$reunion->id}}" tabindex="-1" aria-labelledby="addReunionModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="addReunionModelLabel">Planifier une réunion</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{ route('reunions.update', $reunion->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="mb-2">
                        <label for="title" class="form-label fw-bold">Objet Réunion :</label>
                        <input type="text" class="form-control form-control-custom" id="title" name="title" value="{{$reunion->title}}"
                            required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="start_time" class="form-label fw-bold">Heure début réunion :</label>
                            <input type="time" class="form-control form-control-custom" id="start_time"
                                name="start_time" value="{{\Carbon\Carbon::parse($reunion->debut_reunion)->format('H:i')}}">
                            <div class="invalid-feedback">
                                Veuillez remplir ce champ.
                            </div>
                        </div>
                        <div class="col">
                            <label for="end_time" class="form-label fw-bold">Heure fin réunion :</label>
                            <input type="time" class="form-control form-control-custom" id="end_time"
                                name="end_time" value="{{\Carbon\Carbon::parse($reunion->fin_reunion)->format('H:i')}}">
                            <div class="invalid-feedback">
                                Veuillez remplir ce champ.
                            </div>
                        </div>
                    </div>
                    <label for="nom" class="form-label fw-bold">Nom : *</label>
                        <select name="nom[]" multiple class="form-select form-control form-control-custom" aria-label="Default select example" required>
                            <option hidden selected></option>
                            @foreach ($organiques as $organique)
                                <option value="{{ $organique->id }}" @foreach ($reunion->employes as $membre) @if ($membre->id === $organique->id) selected @endif @endforeach>
                                    {{ $organique->nom }} {{ $organique->prenom }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-tooltip">
                            Veuillez remplir ce champ.
                        </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" id="saveBtn" class="btn btn-dark-no-radius mt-3">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
