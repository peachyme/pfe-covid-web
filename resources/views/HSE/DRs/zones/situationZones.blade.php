@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Situation actuelle des zones de confinement {{$code_region}}</h4>
        </div>
        <div class="justify-content-center mx-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                            {{ $error }}
                    @endforeach
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card card-custom mb-4 mt-4">
                <div class="card-body pb-1">
                    <form action="{{ route('HSE.DRs.zones') }}" method="GET">
                        <div class="row">
                            <div class="col-2">
                                <select name="region" class="form-select form-control form-control-custom" aria-label="Default select example">
                                    <option value="" selected hidden>Site...</option>
                                    @foreach ($regions as $region)
                                    @if ($region->id == 1)@else
                                        <option value="{{$region->id}}">{{$region->code_region}}</option>@endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-dark-no-radius" style="width: 100%"><i class="fa-solid fa-magnifying-glass me-2"></i>Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body pb-1">
                    <table class="table table-hover table-bordered">
                        <thead>
                           <tr>
                                <th scope="col">Code</th>
                                <th scope="col">Libellé</th>
                                <th scope="col">Capacité</th>
                                <th scope="col">Places Disponibles</th>
                                <th scope="col">Effectif médecins</th>
                                <th scope="col">Effectif infermiers</th>
                                <th scope="col">Responsable</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($no_region == false)
                                @foreach ($zones as $zone)
                                    <tr>
                                        <th scope="row">{{$zone->code_zone}}</td>
                                        <td>{{$zone->libelle_zone}}</td>
                                        <td>{{$zone->capacité_zone}}</td>
                                        <td>{{$zone->nb_places}}</td>
                                        <td>{{$zone->effectif_medecins}}</td>
                                        <td>{{$zone->effectif_infermiers}}</td>
                                        <td>{{$zone->employeOrganique->nom}} {{$zone->employeOrganique->prenom}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Veulliez choisir une région.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <a href="{{route('HSE.DRs.zones.reporting')}}" class="btn btn-dark-no-radius"><i class="fa-solid fa-file-pdf me-2"></i>Générer le Reporting de la situation actuelle des zones de confinement</a>
            </div>
        </div>
    </div>
</div>
@endsection
