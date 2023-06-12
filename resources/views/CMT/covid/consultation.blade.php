@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Détails Consultation</h4>
        </div>
        <div class="justify-content-center mx-4">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card card-custom">
                <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Matricule</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">Age</th>
                            @if (!empty($consultation->organique_id))
                                <th scope="col">Structure</th>
                                <th scope="col">Poste de travail</th>
                            @endif
                            @if (!empty($consultation->sousTraitant_id))
                                <th colspan="2">Fonction</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($consultation->organique_id))
                            <tr>
                                <td>{{ $consultation->employeOrganique->matricule }}</td>
                                <td>{{ $consultation->employeOrganique->nom }}</td>
                                <td>{{ $consultation->employeOrganique->prenom }}</td>
                                <td>{{ $age_organique }}</td>
                                <td>{{ $consultation->employeOrganique->structure }}</td>
                                <td>{{ $consultation->employeOrganique->fonction }}</td>
                            </tr>
                        @endif
                        @if (!empty($consultation->sousTraitant_id))
                            <tr>
                                <td>{{ $consultation->sousTraitant->matricule }}</td>
                                <td>{{ $consultation->sousTraitant->nom }}</td>
                                <td>{{ $consultation->sousTraitant->prenom }}</td>
                                <td>{{ $age_sousTraitant }}</td>
                                <td colspan="2">{{ $consultation->sousTraitant->type }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="6">
                                <table class="table table-bordered mb-0">
                                    <thead >
                                        <tr class="text-center text-orange">
                                          <th colspan="2">Diagnostic et modalités de prise en charge</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <tr>
                                            <td>
                                                @if ($consultation->symptomes == 'O')Sujet symptomatique @endif
                                                @if ($consultation->symptomes == 'N')Sujet asymptomatique @endif
                                            </td>
                                            <td>
                                                @if ($consultation->maladies_chroniques == 'O')A des maladies chroniques @endif
                                                @if ($consultation->maladies_chroniques == 'N')Pas de maladies chroniques @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Test {{$consultation->depistage->type_test}} {{$consultation->depistage->resultat_test}}</td>
                                            <td>
                                                @if ($consultation->modalités_priseEnCharge == 'D')Confinement Domicile @endif
                                                @if ($consultation->modalités_priseEnCharge == 'H')Hospitalisation @endif
                                                @if ($consultation->modalités_priseEnCharge == 'RT')Reprise de travail @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Période de confinement : {{ $consultation->periode_confinement }} jours</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <strong>Evolution de la maladie : &nbsp;&nbsp;&nbsp;</strong>
                                @if ($consultation->evolution_maladie == 'G')Guérison apràs traitement @endif
                                @if ($consultation->evolution_maladie == 'D')Décès @endif
                                @if ($consultation->evolution_maladie == '')_____ @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <strong>Ovservations :</strong>
                                @if ($consultation->observation == '')_____
                                @else
                                {{ $consultation->observation }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#editConsultationModal{{$consultation->id}}" class="btn btn-dark-no-radius"><i class="fa-solid fa-pen-to-square me-2"></i>Editer</a>
                    @include('CMT.covid.editConsultation')
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
