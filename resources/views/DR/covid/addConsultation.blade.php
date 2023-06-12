@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
    <div class="justify-content-center p-3">
        <div class="card card-custom px-3 py-2">
            <form action="{{ route('DR.consultations.store') }}" method="POST"  class="needs-validation border border-secondary rounded p-4 pt-0 mt-2 mb-2 pb-3" novalidate>
                @csrf
                @method('POST')
                <!-- Progress bar -->
                <div class="progressbar mt-4">
                    <div class="progress" id="progress"></div>
                    <div class="progress-step progress-step-active text-center" data-title="Employé"></div>
                    <div class="progress-step" data-title="Consultation"></div>
                    <div class="progress-step" data-title="Diagnostic"></div>
                    <div class="progress-step" data-title="Evolution"></div>
                </div>

                <!-- Steps -->
                {{-- Employé --}}
                <div class="form-step form-step-active">
                    <div class="custom-input-group mb-5  position-relative">
                        <label for="matricule" class="form-label fw-bold">Matricule : *</label>
                        <select name="matricule" id="matricule" class="form-select form-control form-control-custom matriculee" aria-label="Default select example" required>
                            <option hidden selected></option>
                            @foreach ($organiques as $organique)
                                <option value="{{ $organique->matricule }}">{{ $organique->matricule }}</option>
                            @endforeach
                            @foreach ($sousTraitants as $sousTraitant)
                                <option value="{{ $sousTraitant->matricule }}">{{ $sousTraitant->matricule }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-tooltip">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="custom-input-group mb-5  position-relative">
                        <label for="nom" class="form-label fw-bold">Nom :</label>
                        <input type="text" readonly class="form-control form-control-custom" id="nom" name="nom">
                    </div>

                    <div class="custom-input-group mb-5  position-relative">
                        <label for="prenom" class="form-label fw-bold">Prénom :</label>
                        <input type="text" readonly class="form-control form-control-custom" id="prenom" name="prenom">
                    </div>
                    <div class="mb-3" style="margin-top: 3.5%;">
                        <a href="#" class="btn btn-dark-no-radius btn-next width-50 ml-auto">Suivant</a>
                    </div>
                </div>

                {{-- Consultation --}}
                <div class="form-step">
                    <div class="custom-input-group mt-4 position-relative">
                        <label for="date_consultation" class="form-label fw-bold">Date : *</label>
                        <input type="date"  max="{{$today}}" value="{{$today}}" class="form-control form-control-custom" id="date_consultation" name="date_consultation" required>
                        <div class="invalid-tooltip">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="custom-input-group mt-4 position-relative">
                        <label for="maladies_chroniques" class="form-label fw-bold">Maladies chroniques : *</label>
                        <div class="form-check form-check-inline ms-4">
                            <input class="form-check-input" type="radio" name="maladies_chroniques" id="radio1" value="O" required>
                            <label class="form-check-label" for="radio1">Oui</label>
                            <div class="custom-invalid-tooltip">
                                    Veuillez choisir une option.
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="maladies_chroniques" id="radio2" value="N" required>
                            <label class="form-check-label" for="radio2">Non</label>
                        </div>
                    </div>
                    <div class="custom-input-group position-relative">
                        <label for="symptomes" class="form-label fw-bold">Symptomes : *</label>
                        <div class="form-check form-check-inline ms-4">
                            <input class="form-check-input" type="radio" name="symptomes" id="radio3" value="O">
                            <label class="form-check-label" for="radio3">Oui</label>
                            <div class="custom-invalid-tooltip">
                                    Veuillez choisir une option.
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="symptomes" id="radio4" value="N">
                            <label class="form-check-label" for="radio4">Non</label>
                        </div>
                    </div>
                    <div class="custom-input-group position-relative">
                        <div class="row">
                            <div class="col">
                                <label for="type_test" class="form-label fw-bold">Type test de dépistage : *</label>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="1" name="type_test" value="Sérologique">
                                    <label class="form-check-label" for="1">Test Sérologique</label>
                                </div>
                            </div>
                            <div class="col">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="2" name="type_test" value="Antigénique">
                                    <label class="form-check-label" for="2">Test Antigénique</label>
                                  </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="3" name="type_test" value="PCR">
                                    <label class="form-check-label" for="3">Test PCR</label>
                                </div>
                            </div>
                            <div class="col">
                                  <div class="form-check">
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="custom-input-group mb-3 position-relative">
                        <label for="resultat_test" class="form-label fw-bold">Résultat test de dépistage : *</label>
                        <div class="form-check form-check-inline ms-4">
                            <input class="form-check-input" type="radio" name="resultat_test" id="inlineRadio1" value="Positif">
                            <label class="form-check-label" for="inlineRadio1">Positif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="resultat_test" id="inlineRadio2" value="Négatif">
                            <label class="form-check-label" for="inlineRadio2">Négatif</label>
                            <div class="custom-invalid-tooltip">
                                    Veuillez choisir une option.
                            </div>
                        </div>
                    </div>
                    <div class="btns-group mb-2" style="margin-top: 2.2%;">
                        <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                        <a href="#" class="btn btn-dark-no-radius btn-next">Suivant</a>
                    </div>
                </div>

                {{-- Diagnostic --}}
                <div class="form-step">
                    <div class="custom-input-group mb-5">
                        <label for="modalite_priseEnCharge" class="form-label fw-bold">Modalité prise en charge : *</label>
                        <select name="modalite_priseEnCharge" class="form-select form-control form-control-custom" aria-label="Default select example" required>
                            <option hidden selected></option>
                            <option value="D">Confinement domicile</option>
                            <option value="BDV">Confinement Base de vie</option>
                            <option value="H">Hospitalisation</option>
                            <option value="RT">Reprise de travail</option>
                        </select>
                    </div>
                    <div class="custom-input-group mb-5">
                        <label for="periode_confinement" class="form-label fw-bold">Période de confinement : *</label>
                        <input type="number" class="form-control form-control-custom" id="periode_confinement" name="periode_confinement" required>
                    </div>
                    <div class="custom-input-group mb-4">
                        <label for="zone" class="form-label fw-bold">Zone de confinement : *</label>
                        <select name="zone" class="form-select form-control form-control-custom" aria-label="Default select example" required>
                            <option hidden selected disabled>Non confinée dans une zone</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->code_zone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="btns-group" style="margin-top: 4.6%">
                        <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                        <a href="#" class="btn btn-dark-no-radius btn-next">Suivant</a>
                    </div>
                </div>

                {{-- Evolution --}}
                <div class="form-step">
                    <div class="custom-input-group mb-4 mt-4">
                        <label for="evolution_maladie" class="form-label fw-bold">Evolution de la maladie :</label>
                        <div class="form-check ms-4 mt-2" style="margin-bottom: 0.9%">
                            <input class="form-check-input" type="radio" name="evolution_maladie" id="flexRadioDefault1" value="G">
                            <label class="form-check-label" for="flexRadioDefault1">Guérison après traitement</label>
                        </div>
                        <div class="form-check ms-4" style="margin-bottom: 0.9%">
                            <input class="form-check-input" type="radio" name="evolution_maladie" id="flexRadioDefault2" value="D">
                            <label class="form-check-label" for="flexRadioDefault2">Décès</label>
                        </div>
                        <div class="form-check ms-4">
                            <input class="form-check-input" type="radio" name="evolution_maladie" id="flexRadioDefault3" value="P">
                            <label class="form-check-label" for="flexRadioDefault3">Prolongation d'arrêt de travail</label>
                        </div>
                    </div>
                    <div class="custom-input-group">
                        <label for="observation" class="form-label fw-bold">Oservations :</label>
                        <textarea class="form-control form-control-custom" id="observation" name="observation" rows="5" placeholder="observations..." style="resize: none;"></textarea>
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
