@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Etat nominatif des cas COVID-19 @if($today != 0) du {{$today}} @endif de la région {{ auth()->user()->region_cmt }}</h4>
        </div>
        <div class="justify-content-center mx-4">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card card-custom">
                <div class="card-body pb-1">
                <table class="table table-hover table-bordered">
                        <thead>
                           <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Matricule</th>
                                <th scope="col">Nom et Prénom</th>
                                <th scope="col">Structure <br> /Type</th>
                                <th scope="col">Symptomes</th>
                                <th scope="col">Test de dépistage</th>
                                <th scope="col">Modalité prise en charge</th>
                                <th class="text-center" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="{{ route('consultations.search') }}" method="GET">
                                    <td class="col-1 p-1">
                                        <input type="date" name="date_consultation" id="date_consultation" class="form-control form-control-custom" placeholder="date...">
                                    </td>
                                    <td class="col-15 p-1">
                                        <input type="text" name="matricule" id="matricule" class="form-control form-control-custom" placeholder="mat...">
                                    </td>
                                    <td class="col p-1">
                                        <input type="text" name="nom" id="nom" class="form-control form-control-custom" placeholder="nom...">
                                    </td>
                                    <td class="col-1 p-1">
                                        <input type="text" name="structure" id="structure" class="form-control form-control-custom" placeholder="str...">
                                    </td>
                                    <td class="col p-1">
                                        <select name="symptomes" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected hidden>symptomes...</option>
                                            <option value="O">Symptomatique</option>
                                            <option value="N">Asymptomatique</option>
                                        </select>
                                    </td>
                                    <td class="col p-1">
                                        <select name="depistage" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected hidden>test...</option>
                                            <option value="Positif">Positif</option>
                                            <option value="Négatif">Négatif</option>
                                        </select>
                                    </td>
                                    <td class="col p-1">
                                        <select name="modalite_priseEnCharge" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected hidden>modalités...</option>
                                            <option value="BDV">Confinement base de vie</option>
                                            <option value="D">Confinement domicile</option>
                                            <option value="H">Hospitalisation</option>
                                            <option value="RT">Reprise de travail</option>
                                        </select>
                                    </td>
                                    <td class="col-1 text-center">
                                        <button type="submit" class="btn-no-btn text-orange"><i class="fa-solid fa-magnifying-glass mt-2"></i></button>
                                    </td>
                                </form>
                            </tr>
                            @if (count($consultations) > 0)
                                @foreach ($consultations as $consultation)
                                    <tr>
                                        <td>{{date('d-m-Y', strtotime($consultation->date_consultation));}}</td>
                                        @if (!empty($consultation->sousTraitant_id))
                                            <td>{{$consultation->sousTraitant->matricule}}</td>
                                            <td>{{$consultation->sousTraitant->nom}} {{$consultation->sousTraitant->prenom}}</td>
                                            <td>{{ $consultation->sousTraitant->type}}</td>
                                        @endif
                                        @if (!empty($consultation->organique_id))
                                            <td>{{$consultation->employeOrganique->matricule}}</td>
                                            <td>{{$consultation->employeOrganique->nom}} {{$consultation->employeOrganique->prenom}}</td>
                                            <td>{{ $consultation->employeOrganique->structure}}</td>
                                        @endif
                                        <td>
                                            @if ($consultation->symptomes == 'O')symptomatique @endif
                                            @if ($consultation->symptomes == 'N')asymptomatique @endif
                                        </td>
                                        <td>{{$consultation->depistage->type_test}} {{$consultation->depistage->resultat_test}}</td>
                                        <td>
                                            @if ($consultation->modalités_priseEnCharge == 'BDV')Confinement Base de vie @endif
                                            @if ($consultation->modalités_priseEnCharge == 'D')Confinement Domicile @endif
                                            @if ($consultation->modalités_priseEnCharge == 'H')Hospitalisation @endif
                                            @if ($consultation->modalités_priseEnCharge == 'RT')Reprise de travail @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('DR.consultations.show', $consultation) }}" class="text-orange me-1"><i class="fa-solid fa-eye"></i></a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteConsultationModal{{$consultation->id}}" class="text-orange me-1"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                        @include('DR.covid.deleteConsultation')
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Rien à afficher.</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col justify-content-start mb-0"><strong>Total : {{$consultations->total()}} Consultations</strong></div>
                        <div class="col pagination justify-content-end mb-0">{{$consultations->links()}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
