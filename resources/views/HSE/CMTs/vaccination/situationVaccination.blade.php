@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
        <h4>Situation {{$situation}} : Etat nominatif vaccinations 3 CMT</h4>
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
                    <form action="{{ route('HSE.CMTs.vaccination') }}" method="GET">
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
                                <th scope="col">Date Vaccination</th>
                                <th scope="col">Nom et Prénom</th>
                                <th scope="col" class="col-2">Structure/Type</th>
                                <th scope="col" class="col-2">Dose</th>
                                <th scope="col" class="col-2">Type vaccin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($vaccinations) > 0)
                                @foreach ($vaccinations as $vaccination)
                                    <tr>
                                    <td>{{$vaccination->cmt->code_cmt}}</td>
                                        <td>{{date('d-m-Y', strtotime($vaccination->date_vaccination));}}</td>
                                        @if (!empty($vaccination->sousTraitant_id))
                                            <td>{{$vaccination->sousTraitant->nom}} {{$vaccination->sousTraitant->prenom}}</td>
                                            <td>{{ $vaccination->sousTraitant->type}}</td>
                                        @endif
                                        @if (!empty($vaccination->organique_id))
                                            <td>{{$vaccination->employeOrganique->nom}} {{$vaccination->employeOrganique->prenom}}</td>
                                            <td>{{ $vaccination->employeOrganique->structure}}</td>
                                        @endif
                                        <td>
                                            @if ($vaccination->dose_vaccination == '1') 1 <sup>ère</sup> @endif
                                            @if ($vaccination->dose_vaccination == '2') 2 <sup>ème</sup> @endif
                                        </td>
                                        <td>{{$vaccination->type_vaccin}}</td>
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
                <div class="row">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#generateReportingModal" class="btn btn-dark-no-radius"><i class="fa-solid fa-file-pdf me-2"></i>Générer Reporting</a>
                    @include('HSE.CMTs.vaccination.generateReporting')
                </div>
        </div>
    </div>
</div>
@endsection
