@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Etat nominatif de l'opération de vaccination  @if($today != 0) du {{$today}} @endif de la région {{ auth()->user()->region_cmt }}</h4>
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
                                <th scope="col">Matricule</th>
                                <th scope="col">Nom et Prénom</th>
                                <th scope="col">Structure/Type</th>
                                <th scope="col">Dose</th>
                                <th scope="col">Type vaccin</th>
                                <th scope="col">Couverture</th>
                                <th class="text-center" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="{{ route('vaccinations.search') }}" method="GET">
                                    <td class="col-15 p-1">
                                        <input type="text" name="matricule" id="matricule" class="form-control form-control-custom" placeholder="mat...">
                                    </td>
                                    <td class="col-3 p-1">
                                        <input type="text" name="nom" id="nom" class="form-control form-control-custom" placeholder="nom...">
                                    </td>
                                    <td class="col p-1">
                                        <input type="text" name="structure" id="structure" class="form-control form-control-custom" placeholder="str...">
                                    </td>
                                    <td class="col p-1">
                                        <input type="text" name="dose_vaccination" id="dose_vaccination" class="form-control form-control-custom" placeholder="dose...">
                                    </td>
                                    <td class="col-2 p-1">
                                        <select name="type_vaccin" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected hidden>type vaccin...</option>
                                            <option value="SINOVAC">SINOVAC</option>
                                            <option value="SPUTNIK">SPUTNIK</option>
                                            <option value="AstraZeneca">AstraZeneca</option>
                                        </select>
                                    </td>
                                    <td class="col-2 p-1">
                                        <select name="couverture" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected hidden>couverture...</option>
                                            <option value="organique">Organique SH</option>
                                            <option value="sousTraitant">Sous-Traitant</option>
                                        </select>
                                    </td>
                                    <td class="col-1 text-center">
                                        <button type="submit" class="btn-no-btn text-orange"><i class="fa-solid fa-magnifying-glass mt-2"></i></button>
                                    </td>
                                </form>
                            </tr>
                            @if (count($vaccinations) > 0)
                                @foreach ($vaccinations as $vaccination)
                                    <tr>
                                        @if (!empty($vaccination->sousTraitant_id))
                                            <td>{{$vaccination->sousTraitant->matricule}}</td>
                                            <td>{{$vaccination->sousTraitant->nom}} {{$vaccination->sousTraitant->prenom}}</td>
                                            <td>{{ $vaccination->sousTraitant->type}}</td>
                                        @endif
                                        @if (!empty($vaccination->organique_id))
                                            <td>{{$vaccination->employeOrganique->matricule}}</td>
                                            <td>{{$vaccination->employeOrganique->nom}} {{$vaccination->employeOrganique->prenom}}</td>
                                            <td>{{ $vaccination->employeOrganique->structure}}</td>
                                        @endif
                                        <td>
                                            @if ($vaccination->dose_vaccination == '1') 1 <sup>ère</sup> @endif
                                            @if ($vaccination->dose_vaccination == '2') 2 <sup>ème</sup> @endif
                                        </td>                                        <td>{{$vaccination->type_vaccin}}</td>
                                        @if (!empty($vaccination->sousTraitant_id))<td>Sous-Traitant</td>@endif
                                        @if (!empty($vaccination->organique_id))<td>Organique SH</td>@endif
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editVaccinationModal{{$vaccination->id}}" class="text-orange me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteVaccinationModal{{$vaccination->id}}" class="text-orange me-1"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                        @include('DR.vaccination.editVaccination')
                                        @include('DR.vaccination.deleteVaccination')
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Rien à afficher.</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col justify-content-start mb-0"><strong>Total : {{$vaccinations->total()}} Vaccinations</strong></div>
                        <div class="col pagination justify-content-end mb-0">{{$vaccinations->links()}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
