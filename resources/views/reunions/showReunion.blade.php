@extends('layouts.app')
@include('partials.navbar')
@section('content')
    <div class="main-container d-flex">
        @include('partials.sidebar')
        <div class="content">
            <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
                <h4>Détails Réunion</h4>
            </div>
            <div class="justify-content-center mx-4">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card card-custom">
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <tbody>
                                <tr>
                                    <th scope="col" colspan="2" class="text-orange">Objet Réunion :
                                        {{ $reunion->title }}</th>
                                </tr>
                                <tr>
                                    <th scope="col" class="col-6">Date Réunion :</th>
                                    <td scope="col">
                                        {{ \Carbon\Carbon::parse($reunion->debut_reunion)->translatedFormat('l d-m-Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">Heure Réunion :</th>
                                    <td scope="col">{{ \Carbon\Carbon::parse($reunion->debut_reunion)->format('H:i') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th rowspan="{{ $reunion->employes()->count() + 1 }}" scope="col">Membres Réunion : </th>
                                    @foreach ($reunion->employes as $membre)
                                        <tr><td scope="col">{{ $membre->nom }} {{ $membre->prenom }}</td></tr>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th scope="col">PV Réunion :</th>
                                    <td scope="col">
                                        <a href="{{ Storage::url($reunion->pv) }}" target = "_blank" class="text-orange text-decoration-none"><i class="fa-solid fa-file me-2"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">Protocôle Sanitaire :</th>
                                    <td scope="col">
                                        <a href="{{ Storage::url($reunion->protocole) }}" target = "_blank" class="text-orange text-decoration-none"><i class="fa-solid fa-hand-holding-medical me-2"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col">
                                <a href="" data-bs-toggle="modal"
                                    data-bs-target="#editReunionModel{{ $reunion->id }}" class="btn btn-dark-no-radius"><i
                                        class="fa-solid fa-pen-to-square me-2"></i>Modifier Détails Réunion</a>
                            </div>
                            <div class="col">
                                <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteReunionModal{{ $reunion->id }}"
                                    class="btn btn-dark-no-radius"><i class="fa-solid fa-file me-2"></i>Annuler la
                                    Réunion</a>
                            </div>
                            <div class="col">
                                <a href="" data-bs-toggle="modal" data-bs-target="#addPVModal{{$reunion->id}}" class="btn btn-dark-no-radius">
                                    <i class="fa-solid fa-file me-2"></i>Ajouter PV Réunion</a>
                            </div>
                            <div class="col">
                                <a href="" data-bs-toggle="modal" data-bs-target="#addProtocoleModal{{$reunion->id}}" class="btn btn-dark-no-radius">
                                    <i class="fa-solid fa-hand-holding-medical me-2"></i>Ajouter Protocôle Sanitaire</a>
                            </div>
                        </div>
                        @include('reunions.deleteReunion')
                        @include('reunions.editReunion')
                        @include('reunions.addProtocole')
                        @include('reunions.addPV')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
