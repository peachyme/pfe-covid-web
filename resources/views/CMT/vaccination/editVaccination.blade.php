<div class="modal fade" id="editVaccinationModal{{$vaccination->id}}" tabindex="-1" aria-labelledby="editVaccinationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content modal-custom-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editVaccinationModalLabel">Modifier les informations de la vaccination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3  border border-secondary rounded">
                <form action="{{ route('CMT.vaccinations.update', $vaccination) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    <!-- Progress bar -->
                    <div class="progressbar mt-2 mx-2">
                        <div class="progress" id="progress"></div>
                        <div class="progress-step progress-step-active text-center" data-title="Employé"></div>
                        <div class="progress-step" data-title="Détails vaccination"></div>
                        <div class="progress-step" data-title="Observations"></div>
                    </div>

                    <!-- Steps -->
                    {{-- Employé --}}
                    <div class="form-step form-step-active mx-2">
                        <div class="custom-input-group mb-5  position-relative">
                            <label for="matricule" class="form-label fw-bold">Matricule* :</label>
                            @if (!empty($vaccination->organique_id))
                                <input type="text" class="form-control form-control-custom" id="matricule" value="{{$vaccination->employeOrganique->matricule}}" readonly>
                            @endif
                            @if (!empty($vaccination->sousTraitant_id))
                                <input type="text" class="form-control form-control-custom" id="matricule" value="{{$vaccination->sousTraitant->matricule}}" readonly>
                            @endif
                        </div>
                        <div class="custom-input-group mb-5  position-relative">
                            <label for="nom" class="form-label fw-bold">Nom* :</label>
                            @if (!empty($vaccination->organique_id))
                                <input type="text" class="form-control form-control-custom" id="nom" value="{{$vaccination->employeOrganique->nom}}" readonly>
                            @endif
                            @if (!empty($vaccination->sousTraitant_id))
                                <input type="text" class="form-control form-control-custom" id="nom" value="{{$vaccination->sousTraitant->nom}}" readonly>
                            @endif
                        </div>
                        <div class="custom-input-group mb-4  position-relative">
                            <label for="prenom" class="form-label fw-bold">Prénom* :</label>
                            @if (!empty($vaccination->organique_id))
                                <input type="text" class="form-control form-control-custom" id="prenom" value="{{$vaccination->employeOrganique->prenom}}" readonly>
                            @endif
                            @if (!empty($vaccination->sousTraitant_id))
                                <input type="text" class="form-control form-control-custom" id="prenom" value="{{$vaccination->sousTraitant->prenom}}" readonly>
                            @endif
                        </div>
                        <div style="margin-top: 4.6%">
                            <a href="#" class="btn btn-dark-no-radius btn-next width-50 ml-auto">Suivant</a>
                        </div>
                    </div>

                    {{-- Détails vaccination --}}
                    <div class="form-step mx-2">
                        <div class="custom-input-group mb-5 position-relative">
                            <label for="date_vaccination" class="form-label fw-bold">Date : *</label>
                            <input type="date" max="{{$date}}" class="form-control form-control-custom" id="date_vaccination" name="date_vaccination" value="{{$vaccination->date_vaccination}}" required>
                            <div class="invalid-tooltip">
                                Veuillez remplir ce champ.
                            </div>
                        </div>
                        <div class="custom-input-group mb-5 position-relative">
                            <div class="row">
                                <div class="col">
                                    <label for="region" class="form-label fw-bold">Région :</label>
                                    <input type="text" readonly class="form-control form-control-custom" id="region" name="region" value="{{$vaccination->region->id}}">
                                </div>
                                <div class="col">
                                    <label for="cmt" class="form-label fw-bold">CMT :</label>
                                    <input type="text" readonly class="form-control form-control-custom" id="cmt" name="cmt" value="{{$vaccination->cmt->id}}">
                                </div>
                            </div>
                        </div>
                        <div class="custom-input-group mb-4 position-relative">
                            <div class="row">
                                <div class="col">
                                    <label for="type_vaccin" class="form-label fw-bold">Type :</label>
                                    <select name="type_vaccin" class="form-select form-control form-control-custom" aria-label="Default select example" required>
                                        <option hidden selected></option>
                                        <option value="SINOVAC" @if($vaccination->type_vaccin == 'SINOVAC') selected @endif>SINOVAC</option>
                                        <option value="SPUTNIK" @if($vaccination->type_vaccin == 'SPUTNIK') selected @endif>SPUTNIK</option>
                                        <option value="AstraZeneca" @if($vaccination->type_vaccin == 'AstraZeneca') selected @endif>AstraZeneca</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="dose_vaccination" class="form-label fw-bold">Dose :</label>
                                    <input type="number" class="form-control form-control-custom" id="dose_vaccination" name="dose_vaccination" value="{{$vaccination->dose_vaccination}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="btns-group mb-3" style="margin-top: 3.5%;">
                            <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                            <a href="#" class="btn btn-dark-no-radius btn-next">Suivant</a>
                        </div>
                    </div>

                    {{-- Observations --}}
                    <div class="form-step mx-2">
                        <div class="custom-input-group">
                            <label for="observation" class="form-label fw-bold">Oservations :</label>
                            <textarea class="form-control form-control-custom" id="observation" name="observation" rows="11" placeholder="observations..." style="resize: none;">{{$vaccination->observation}}</textarea>
                        </div>
                        <div class="btns-group">
                            <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                            <input type="submit" value="Valider" class="btn btn-dark-no-radius" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

