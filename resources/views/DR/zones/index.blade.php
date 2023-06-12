@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Liste des zones de confinement de la region {{ auth()->user()->region_cmt }}</h4>
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
            @if (auth()->user()->roles()->where('role','admin')->exists())
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                    <a href="" data-bs-toggle="modal" data-bs-target="#addZoneModel" class="btn btn-dark-no-radius">Ajouter nouvelle zone de confinement</a>
                </div>
            @endif
            @include('DR.zones.addZone')
            <div class="card card-custom">
                <div class="card-body pb-1">
                    <table class="table table-hover table-bordered">
                        <thead>
                           <tr>
                                @if (auth()->user()->roles()->where('role','admin')->exists())
                                <th scope="col">Région</th>
                                @endif
                                <th scope="col">Code</th>
                                <th scope="col">Libellé</th>
                                <th scope="col">Capacité</th>
                                <th scope="col">Places</th>
                                <th scope="col">Effectif médecins</th>
                                <th scope="col">Effectif infermiers</th>
                                <th scope="col">Responsable</th>
                                @if (auth()->user()->roles()->where('role','Responsable de Reporting')->exists())
                                <th scope="col" class="col-1">Filtrer</th>
                                @endif
                                @if (auth()->user()->roles()->where('role','admin')->exists())
                                <th class="text-center" scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="{{ route('zones.search') }}" method="GET">
                                    @if (auth()->user()->roles()->where('role','admin')->exists())
                                    <td class="col-1 p-1">
                                        <select name="region_zone" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected hidden>Région...</option>
                                            @foreach ($regions as $region)
                                                @if ($region->id != 1)
                                                <option value="{{ $region->id }}">{{ $region->code_region }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    @endif
                                    <td class="col-1 p-1">
                                        <input type="text" name="code_zone" id="code_zone" class="form-control  form-control-custom" placeholder="code...">
                                    </td>
                                    <td class="col-2 p-1">
                                        <input type="text" name="libelle_zone" id="libelle_zone" class="form-control form-control-custom" placeholder="libellé...">
                                    </td>
                                    <td class="col-15 p-1">
                                        <input type="text" name="capacite_zone" id="capacite_zone" class="form-control form-control-custom" placeholder="capacité...">
                                    </td>
                                    <td class="col-15 p-1">
                                        <input type="text" name="nb_places" id="nb_places" class="form-control form-control-custom" placeholder="nb_places...">
                                    </td>
                                    <td class="col-2 p-1">
                                        <input type="text" name="medecins_zone" id="medecins_zone" class="form-control form-control-custom" placeholder="médecins..." disabled>
                                    </td>
                                    <td class="col-2 p-1">
                                        <input type="text" name="infermiers_zone" id="infermiers_zone" class="form-control form-control-custom" placeholder="infermiers..." disabled>
                                    </td>
                                    <td class="col-2 p-1">
                                        <select name="responsable_zone" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected hidden>Responsable...</option>
                                            @foreach ($employes as $employe)
                                                <option value="{{ $employe->id }}">{{ $employe->matricule }} {{ $employe->nom }} {{ $employe->prenom }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="col-1 text-center">
                                        <button type="submit" class="btn-no-btn text-orange"><i class="fa-solid fa-magnifying-glass mt-2"></i></button>
                                    </td>
                                </form>
                            </tr>
                            @if (auth()->user()->roles()->where('role','admin')->exists())
                            @if (count($zones_all) > 0)
                                @foreach ($zones_all as $zone)
                                    <tr>
                                        @if (auth()->user()->roles()->where('role','admin')->exists())
                                        <th scope="row">{{$zone->region->code_region}}</td>
                                        @endif
                                        <th scope="row">{{$zone->code_zone}}</td>
                                        <td>{{$zone->libelle_zone}}</td>
                                        <td>{{$zone->capacité_zone}}</td>
                                        <td>{{$zone->nb_places}}</td>
                                        <td>{{$zone->effectif_medecins}}</td>
                                        <td>{{$zone->effectif_infermiers}}</td>
                                        <td>{{$zone->employeOrganique->nom}} {{$zone->employeOrganique->prenom}}</td>
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editZoneModal{{$zone->id}}" class="text-orange me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{route('DR.zones.destroy', $zone->id)}}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-no-btn"><a class=" text-orange ms-1" href=""><i class="fa-solid fa-trash"></i></a></button>
                                            </form>
                                        </td>
                                        @include('DR.zones.editZone')
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Pas de zones trouvées!</td></tr>
                            @endif
                            @endif
                            @if (auth()->user()->roles()->where('role','Responsable de Reporting')->exists())
                            @if (count($zones) > 0)
                                @foreach ($zones as $zone)
                                    <tr>
                                        <th scope="row">{{$zone->code_zone}}</td>
                                        <td>{{$zone->libelle_zone}}</td>
                                        <td>{{$zone->capacité_zone}}</td>
                                        <td>{{$zone->nb_places}}</td>
                                        <td>{{$zone->effectif_medecins}}</td>
                                        <td>{{$zone->effectif_infermiers}}</td>
                                        <td colspan="2">{{$zone->employeOrganique->nom}} {{$zone->employeOrganique->prenom}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Pas de zones trouvées!</td></tr>
                            @endif
                            @endif
                        </tbody>
                    </table>
                    @if (auth()->user()->roles()->where('role','admin')->exists())
                    <div class="pagination justify-content-end mb-0">{{$zones_all->links()}}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
