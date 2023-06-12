<div class="modal fade" id="editConsultationModal{{$consultation->id}}" tabindex="-1" aria-labelledby="editConsultationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editConsultationModalLabel">Modifier les informations de la consultation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body card-custom pt-1">
                <form action="{{ route('CMT.consultations.update', $consultation) }}" method="POST"  class="needs-validation border border-secondary rounded p-4 pt-0 mt-2 mb-2 pb-3" novalidate>
                    @csrf
                    @method('PATCH')
                    <!-- Progress bar -->
                    <div class="progressbar mt-4">
                        <div class="progress" id="progress"></div>
                        <div class="progress-step progress-step-active text-center" data-title="Employé"></div>
                        <div class="progress-step" data-title="Consultation"></div>
                        <div class="progress-step" data-title="Diagnostic"></div>
                    </div>

                    <!-- Steps -->
                    {{-- Employé --}}
                    <div class="form-step form-step-active">
                    <div class="custom-input-group mb-5  position-relative">
                            <label for="matricule" class="form-label fw-bold">Matricule* :</label>
                            @if (!empty($consultation->organique_id))
                                <input type="text" class="form-control form-control-custom" id="matricule" value="{{$consultation->employeOrganique->matricule}}" readonly>
                            @endif
                            @if (!empty($consultation->sousTraitant_id))
                                <input type="text" class="form-control form-control-custom" id="matricule" value="{{$consultation->sousTraitant->matricule}}" readonly>
                            @endif
                        </div>
                        <div class="custom-input-group mb-5  position-relative">
                            <label for="nom" class="form-label fw-bold">Nom* :</label>
                            @if (!empty($consultation->organique_id))
                                <input type="text" class="form-control form-control-custom" id="nom" value="{{$consultation->employeOrganique->nom}}" readonly>
                            @endif
                            @if (!empty($consultation->sousTraitant_id))
                                <input type="text" class="form-control form-control-custom" id="nom" value="{{$consultation->sousTraitant->nom}}" readonly>
                            @endif
                        </div>
                        <div class="custom-input-group mb-4  position-relative">
                            <label for="prenom" class="form-label fw-bold">Prénom* :</label>
                            @if (!empty($consultation->organique_id))
                                <input type="text" class="form-control form-control-custom" id="prenom" value="{{$consultation->employeOrganique->prenom}}" readonly>
                            @endif
                            @if (!empty($consultation->sousTraitant_id))
                                <input type="text" class="form-control form-control-custom" id="prenom" value="{{$consultation->sousTraitant->prenom}}" readonly>
                            @endif
                        </div>
                        <div style="margin-top: 4.6%">
                            <a href="#" class="btn btn-dark-no-radius btn-next width-50 ml-auto">Suivant</a>
                        </div>
                    </div>

                    {{-- Consultation --}}
                    <div class="form-step">
                    <div class="custom-input-group position-relative">
                            <div class="row">
                                <div class="col">
                                    <label for="date_consultation" class="form-label fw-bold">Date* :</label>
                                    <input type="date" max="{{$today}}" class="form-control form-control-custom" id="date_consultation" name="date_consultation" value="{{$consultation->date_consultation}}" required>
                                    <div class="invalid-tooltip">
                                        Veuillez remplir ce champ.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="region" class="form-label fw-bold">Région :</label>
                                    <input type="text" readonly class="form-control form-control-custom" id="region" name="region" value="{{$region->id}}">
                                </div>
                                <div class="col">
                                    <label for="cmt" class="form-label fw-bold">CMT :</label>
                                    <input type="text" readonly class="form-control form-control-custom" id="cmt" name="cmt" value="{{$cmt->id}}">
                                </div>
                            </div>
                        </div>
                        <div class="custom-input-group mt-4 position-relative">
                            <label for="maladies_chroniques" class="form-label fw-bold">Maladies chroniques* :</label>
                            <div class="form-check form-check-inline ms-4">
                                <input class="form-check-input" type="radio" name="maladies_chroniques" id="radio1" value="O" @if($consultation->maladies_chroniques == 'O') checked @endif required>
                                <label class="form-check-label" for="radio1">Oui</label>
                                <div class="custom-invalid-tooltip">
                                        Veuillez choisir une option.
                                </div>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="maladies_chroniques" id="radio2" value="N" @if($consultation->maladies_chroniques == 'N') checked @endif required>
                                <label class="form-check-label" for="radio2">Non</label>
                            </div>
                        </div>
                        <div class="custom-input-group  position-relative">
                            <label for="symptomes" class="form-label fw-bold">Symptomes* :</label>
                            <div class="form-check form-check-inline ms-4">
                                <input class="form-check-input" type="radio" name="symptomes" id="radio3" value="O" @if($consultation->symptomes == 'O') checked @endif required>
                                <label class="form-check-label" for="radio3">Oui</label>
                                <div class="custom-invalid-tooltip">
                                        Veuillez choisir une option.
                                </div>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="symptomes" id="radio4" value="N" @if($consultation->symptomes == 'N') checked @endif required>
                                <label class="form-check-label" for="radio4">Non</label>
                            </div>
                        </div>
                        <div class="custom-input-group  position-relative">
                            <div class="row">
                                <div class="col">
                                    <label for="type_test" class="form-label fw-bold">Type test de dépistage* :</label>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="1" name="type_test" value="Sérologique" @if($consultation->depistage->type_test == 'Sérologique') checked @endif>
                                        <label class="form-check-label" for="1">Test Sérologique</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="2" name="type_test" value="Antigénique" @if($consultation->depistage->type_test == 'Antigénique') checked @endif>
                                        <label class="form-check-label" for="2">Test Antigénique</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="3" name="type_test" value="PCR" @if($consultation->depistage->type_test == 'PCR') checked @endif>
                                        <label class="form-check-label" for="3">Test PCR</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-input-group mb-4 position-relative">
                            <label for="resultat_test" class="form-label fw-bold">Résultat test de dépistage* :</label>
                            <div class="form-check form-check-inline ms-4">
                                <input class="form-check-input" type="radio" name="resultat_test" id="inlineRadio1" value="Positif" @if($consultation->depistage->resultat_test == 'Positif') checked @endif required>
                                <label class="form-check-label" for="inlineRadio1">Positif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="resultat_test" id="inlineRadio2" value="Négatif" @if($consultation->depistage->resultat_test == 'Négatif') checked @endif required>
                                <label class="form-check-label" for="inlineRadio2">Négatif</label>
                                <div class="custom-invalid-tooltip">
                                        Veuillez choisir une option.
                                </div>
                            </div>
                        </div>
                        <div class="btns-group mt-0">
                            <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                            <a href="#" class="btn btn-dark-no-radius btn-next">Suivant</a>
                        </div>
                    </div>

                    {{-- Diagnostic --}}
                    <div class="form-step">
                        <div class="row mb-0">
                            <div class="col">
                                <div class="custom-input-group mb-3">
                                    <label for="modalite_priseEnCharge" class="form-label fw-bold">Modalité prise en charge : *</label>
                                    <select name="modalite_priseEnCharge" class="form-select form-control form-control-custom" aria-label="Default select example" required>
                                        <option hidden selected></option>
                                        <option value="D" @if($consultation->modalités_priseEnCharge == 'D') selected @endif>Confinement domicile</option>
                                        <option value="H" @if($consultation->modalités_priseEnCharge == 'H') selected @endif>Hospitalisation</option>
                                        <option value="RT" @if($consultation->modalités_priseEnCharge == 'RT') selected @endif>Reprise de travail</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-input-group mb-3">
                                    <label for="periode_confinement" class="form-label fw-bold">Période de confinement : *</label>
                                    <input type="number" class="form-control form-control-custom" id="periode_confinement" name="periode_confinement" value="{{$consultation->periode_confinement}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="custom-input-group">
                                    <label for="evolution_maladie" class="form-label fw-bold">Evolution de la maladie :</label>
                                    <div class="form-check ms-4 mt-2" style="margin-bottom: 0.9%">
                                        <input class="form-check-input" type="radio" name="evolution_maladie" id="flexRadioDefault1" value="G" @if($consultation->evolution_maladie == 'G') checked @endif>
                                        <label class="form-check-label" for="flexRadioDefault1">Guérison après traitement</label>
                                    </div>
                                    <div class="form-check ms-4" style="margin-bottom: 0.9%">
                                        <input class="form-check-input" type="radio" name="evolution_maladie" id="flexRadioDefault2" value="D" @if($consultation->evolution_maladie == 'D') checked @endif>
                                        <label class="form-check-label" for="flexRadioDefault2">Décès</label>
                                    </div>
                                    <div class="form-check ms-4">
                                        <input class="form-check-input" type="radio" name="evolution_maladie" id="flexRadioDefault3" value="P">
                                        <label class="form-check-label" for="flexRadioDefault3">Prolongation d'arrêt de travail</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-input-group">
                                    <label for="observation" class="form-label fw-bold">Oservations :</label>
                                    <textarea class="form-control form-control-custom" id="observation" name="observation" rows="5" placeholder="observations..." style="resize: none;">{{$consultation->observation}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="btns-group mb-2">
                            <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                            <input type="submit" value="Valider" class="btn btn-dark-no-radius" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
