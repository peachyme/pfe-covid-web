@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
    <div class="justify-content-center p-3">
        <div class="card card-custom px-3 py-2">
            <form action="{{ route('CMT.vaccinations.store') }}" method="POST"  class="needs-validation border border-secondary rounded p-4 pt-0 mt-2 mb-2 pb-3" novalidate>
                @csrf
                @method('POST')
                <!-- Progress bar -->
                <div class="progressbar mt-4">
                    <div class="progress" id="progress"></div>
                    <div class="progress-step progress-step-active text-center" data-title="Employé"></div>
                    <div class="progress-step" data-title="Détails vaccination"></div>
                    <div class="progress-step" data-title="Observations"></div>
                </div>

                <!-- Steps -->
                {{-- Employé --}}
                <div class="form-step form-step-active">
                    <div class="custom-input-group mb-5  position-relative">
                        <label for="matricule" class="form-label fw-bold">Matricule : *</label>
                        <select name="matricule" id="matricule" class="form-select form-control form-control-custom" aria-label="Default select example" required>
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

                {{-- Détails vaccination --}}
                <div class="form-step">
                    <div class="custom-input-group mb-5 position-relative">
                        <label for="date_vaccination" class="form-label fw-bold">Date : *</label>
                        <input type="date" max="{{$today}}"  value="{{$today}}" class="form-control form-control-custom" id="date_vaccination" name="date_vaccination" required>
                        <div class="invalid-tooltip">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="custom-input-group mb-5 position-relative">
                        <label for="type_vaccin" class="form-label fw-bold">Type :</label>
                        <select name="type_vaccin" class="form-select form-control form-control-custom" aria-label="Default select example" required>
                            <option hidden selected></option>
                            <option value="SINOVAC">SINOVAC</option>
                            <option value="SPUTNIK">SPUTNIK</option>
                            <option value="AstraZeneca">AstraZeneca</option>
                        </select>
                    </div>
                    <div class="custom-input-group mb-5 position-relative">
                        <label for="dose_vaccination" class="form-label fw-bold">Dose :</label>
                        <input type="number" class="form-control form-control-custom" id="dose_vaccination" name="dose_vaccination" value="" required>
                    </div>
                    <div class="btns-group mb-3" style="margin-top: 3.5%;">
                        <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                        <a href="#" class="btn btn-dark-no-radius btn-next">Suivant</a>
                    </div>
                </div>

                {{-- Observations --}}
                <div class="form-step">
                    <div class="custom-input-group">
                        <label for="observation" class="form-label fw-bold">Oservations :</label>
                        <textarea class="form-control form-control-custom" id="observation" name="observation" rows="11" placeholder="observations..." style="resize: none;"></textarea>
                    </div>
                    <div class="btns-group mb-3" style="margin-top: 3.5%;">
                        <a href="#" class="btn btn-dark-no-radius btn-prev">Précédent</a>
                        <input type="submit" value="Valider" class="btn btn-dark-no-radius" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

