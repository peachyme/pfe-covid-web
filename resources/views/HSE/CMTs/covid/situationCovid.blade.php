@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>{{$situation}}Etat nominatif des cas COVID-19</h4>
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

            <div class="card card-custom mb-4 mt-4">
                <div class="card-body pb-1">
                    <form action="{{ route('HSE.CMTs.covid') }}" method="GET">
                        <div class="row">
                            <div class="col-2">
                                <select name="cmt" class="form-select form-control form-control-custom" aria-label="Default select example">
                                    <option value="" selected hidden>CMT...</option>
                                    @foreach ($cmts as $cmt)
                                        <option value="{{$cmt->id}}">CMT-{{$cmt->code_cmt}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <select name="couverture" class="form-select form-control form-control-custom" aria-label="Default select example">
                                    <option value="" selected hidden>couverture...</option>
                                    <option value="O">Organique SH</option>
                                    <option value="ST">Sous-Traitants</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <input type="text" min="2020-W09" max="{{$current_year}}-W{{$current_week}}" class="form-control form-control-custom" id="week" name="week" placeholder="Hebdomadaire" onfocus="(this.type='week')" onblur="(this.type='text')">
                            </div>
                            <div class="col-2">
                                <input type="text" min="2020-03" max="{{$current_month}}" class="form-control form-control-custom" id="month" name="month" placeholder="Mensuel" onfocus="(this.type='month')" onblur="(this.type='text')">
                            </div>
                            <div class="col-2">
                                <input type="number" min="2020" max="{{$current_year}}" class="form-control form-control-custom" id="year" name="year" placeholder="Annuel">
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-dark-no-radius" style="width: 100%"><i class="fa-solid fa-magnifying-glass me-2"></i>Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-custom mb-4">
                <div class="card-body pb-1">
                <table class="table table-hover table-bordered">
                        <thead>
                           <tr>
                                <th scope="col">CMT</th>
                                <th scope="col">Date consultation</th>
                                <th scope="col">Nom et Prénom</th>
                                <th scope="col">Structure</th>
                                <th scope="col">Symptomes</th>
                                <th scope="col">Test de dépistage</th>
                                <th scope="col">Modalité prise en charge</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($consultations) > 0)
                                @foreach ($consultations as $consultation)
                                    <tr>
                                        <td>{{$consultation->cmt->code_cmt}}</td>
                                        <td>{{date('d-m-Y', strtotime($consultation->date_consultation));}}</td>
                                        @if (!empty($consultation->sousTraitant_id))
                                            <td>{{$consultation->sousTraitant->nom}} {{$consultation->sousTraitant->prenom}}</td>
                                            <td>{{ $consultation->sousTraitant->type}}</td>
                                        @endif
                                        @if (!empty($consultation->organique_id))
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
            <div class="row">
                <a href="#" data-bs-toggle="modal" data-bs-target="#generateReportingModal" class="btn btn-dark-no-radius"><i class="fa-solid fa-file-pdf me-2"></i>Générer Reporting</a>
            </div>
            @include('HSE.CMTs.covid.generateReporting')
        </div>
    </div>
</div>
@endsection
