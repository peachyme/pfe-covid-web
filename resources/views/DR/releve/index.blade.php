@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Confinement : Situation du {{$today}} de la région {{ auth()->user()->region_cmt }}</h4>
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
                <div class="card-body">
                    <table class="table table-bordered text-center mb-0">
                        <tr>
                            <th colspan="8">Point de situation journalier "Confinement"</th>
                        </tr>
                        <tr>
                            <th colspan="2">Nbr effectif entrant</th>
                            <th colspan="2">Nbr effectif sortant</th>
                            <th rowspan="2" colspan="{{$nb_zones}}"><br>Capacité zone de confinement "Tampon"</th>
                        </tr>
                        <tr class="divide">
                            <th>Organique SH</th>
                            <th>Sous-Traitans</th>
                            <th>Organique SH</th>
                            <th>Sous-Traitans</th>
                        </tr>
                        <tr class="divide">
                            <td>{{$releve_entrant_organique}}</td>
                            <td>{{$releve_entrant_sousTraitant}}</td>
                            <td>{{$releve_sortant_organique}}</td>
                            <td>{{$releve_sortant_sousTraitant}}</td>
                            @foreach ($zones as $zone)
                                <td>{{$zone->code_zone}} : {{$zone->nb_places}}</td>
                            @endforeach
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card card-custom mt-4">
                <div class="card-body pb-1">
                    <table class="table table-hover table-bordered mb-3">
                        <thead>
                           <tr>
                                <th class="col-2">Etat</th>
                                <th class="col-2">Couverture</th>
                                <th class="col-2">Zone de confinement</th>
                                <th class="col-2">Région</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (count($releves) > 0)
                            @foreach ($releves as $releve)
                                <tr>
                                    <td>@if ($releve->etat_releve == 'sortant') Sortant @else Entrant @endif</td>
                                    <td>@if ($releve->couverture == 'O') Organique SH @else Sous-Traitant @endif</td>
                                    <td>
                                        @foreach ($zones as $zone)
                                            @if ($zone->id == $releve->zone_id)
                                                {{$zone->code_zone}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{$region->code_region}}</td>
                                </tr>
                            @endforeach
                            @else
                                <tr><td colspan="8">Rien à afficher.</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pagination justify-content-end mb-0">{{$releves->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
