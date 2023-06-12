@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>{{$situation}}Cas COVID-19</h4>
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
                    <form action="{{ route('HSE.DRs.covid') }}" method="GET">
                        <div class="row">
                            <div class="col-2">
                                <select name="region" class="form-select form-control form-control-custom" aria-label="Default select example">
                                    <option value="" selected hidden>Site...</option>
                                    @foreach ($regions_select as $region_select)
                                        <option value="{{$region_select->id}}">{{$region_select->code_region}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <input type="text" min="2020-03-01" max="{{$date}}" class="form-control form-control-custom" id="date" name="date" placeholder="Quotidien" onfocus="(this.type='date')" onblur="(this.type='text')">
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
                    @if (!$no_region)
                        <table class="table table-hover table-bordered text-center">
                            <thead>
                                <tr>
                                    <th scope="col" class="col-1">Sites <br> DP</th>
                                    <th scope="col">Nbr de cas confirmés positifs</th>
                                    <th scope="col">Nombre de cas guéris</th>
                                    <th scope="col">Nombre de cas en quarantaine</th>
                                    <th scope="col">Nombre de cas hospitalisés</th>
                                    <th scope="col">Nombre de décès</th>
                                    <th scope="col" class="col-1">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">{{$code_region}}</th>
                                    <td>{{abs($consultations_pos)}}</td>
                                    <td>{{abs($consultations_gueris)}}</td>
                                    <td>{{abs($consultations_conf)}}</td>
                                    <td>{{abs($consultations_hosp)}}</td>
                                    <td>{{abs($consultations_deces)}}</td>
                                    <td><strong>{{$consultations_total}}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table table-hover table-bordered text-center">
                            <thead>
                                <tr>
                                    <th scope="col" class="col-1">Sites <br> DP</th>
                                    <th scope="col">Nbr de cas confirmés positifs</th>
                                    <th scope="col">Nombre de cas guéris</th>
                                    <th scope="col">Nombre de cas en quarantaine</th>
                                    <th scope="col">Nombre de cas hospitalisés</th>
                                    <th scope="col">Nombre de décès</th>
                                    <th scope="col" class="col-1">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($regions as $region)
                                    <tr>
                                        <th scope="row">{{$region->code_region}}</th>
                                        <td>{{abs(($report_pos[$region->id]['cas_positifs'] ?? 0) - ($report_pro[$region->id]['cas_pro'] ?? 0) - ($report_deces[$region->id]['cas_deces'] ?? 0))}}</td>
                                        <td>{{$report_gueris[$region->id]['cas_gueris'] ?? 0}}</td>
                                        <td>{{abs((($report_bdv[$region->id]['cas_bdv'] ??  0) + ($report_dom[$region->id]['cas_dom'] ?? 0) + ($report_hosp[$region->id]['cas_hosp'] ?? 0)) - ($report_rt[$region->id]['cas_rt'] ?? 0) - ($report_d[$region->id]['cas_d'] ?? 0) - ($report_p[$region->id]['cas_p'] ?? 0))}}</td>
                                        <td>{{$report_hosp[$region->id]['cas_hosp'] ?? 0}}</td>
                                        <td>{{$report_deces[$region->id]['cas_deces'] ?? 0}}</td>
                                        <td><strong>{{$report_total[$region->id]['total'] ?? 0}}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination justify-content-end mb-0">{{$regions->links()}}</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <a href="#" data-bs-toggle="modal" data-bs-target="#generateReportingModal" class="btn btn-dark-no-radius"><i class="fa-solid fa-file-pdf me-2"></i>Générer Reporting</a>
                @include('HSE.DRs.covid.generateReporting')
            </div>
        </div>
    </div>
</div>
@endsection
