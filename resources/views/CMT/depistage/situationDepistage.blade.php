@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Rapport d'activité {{$situation}} {{$code}} </h4>
        </div>
        <div class="justify-content-center mx-2">
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

            <div class="card card-custom mb-3 mt-3">
                <div class="card-body pb-0">
                    <form action="{{ route('CMT.depistages') }}" method="GET">
                        <div class="row">
                            <div class="col">
                                <select name="cmt" class="form-select form-control form-control-custom" aria-label="Default select example">
                                    <option value="" selected hidden>CMT...</option>
                                    @foreach ($cmts as $cmt)
                                        <option value="{{$cmt->id}}">CMT-{{$cmt->code_cmt}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <input type="text" min="2020-W09" max="{{$current_year}}-W{{$current_week}}" class="form-control form-control-custom" id="week" name="week" placeholder="Hebdomadaire" onfocus="(this.type='week')" onblur="(this.type='text')">
                            </div>
                            <div class="col">
                                <input type="text" min="2020-03" max="{{$current_month}}" class="form-control form-control-custom" id="month" name="month" placeholder="Mensuel" onfocus="(this.type='month')" onblur="(this.type='text')">
                            </div>
                            <div class="col">
                                <input type="number" min="2020" max="{{$current_year}}" class="form-control form-control-custom" id="year" name="year" placeholder="Annuel">
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-dark-no-radius" style="width: 100%"><i class="fa-solid fa-magnifying-glass me-2"></i>Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if ($count == 0)
                <div class="card card-custom mb-3 mt-3">
                    <div class="card-body pb-3 pt-3">
                        Veuillez choisir un CMT et une date.
                    </div>
                </div>
            @elseif ($count == 2)
            <div class="card card-custom mb-3 mt-3 mx-0 overflow-auto" style="height: 370px">
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        <tr>
                            <th rowspan="3"><br>Mois</th>
                            <th colspan="8">Couverture Oragnique SH</th>
                            <th colspan="8">Couverture Sous-Traitants</th>
                        </tr>
                        <tr>
                            @foreach ($types_test as $type_test)
                                <th colspan="2">{{ $type_test->type_test }}</th>
                            @endforeach
                            <th rowspan="2" class="pt-4">Total dépisté</th>
                            <th rowspan="2" class="pt-4">Total (+)</th>
                            @foreach ($types_test as $type_test)
                                <th colspan="2">{{ $type_test->type_test  }}</th>
                            @endforeach
                            <th rowspan="2" class="pt-4">Total dépisté</th>
                            <th rowspan="2" class="pt-4">Total (+)</th>
                        </tr>
                        <tr class="divide">
                            @foreach ($types_test as $type_test)
                                <th>Nbr dépisté</th>
                                <th>Nbr (+)</th>
                            @endforeach
                            @foreach ($types_test as $type_test)
                            <th>Nbr dépisté</th>
                            <th>Nbr (+)</th>
                            @endforeach
                        </tr>
                        @foreach ($months as $month)
                            <tr>
                                <td><span style="text-transform: uppercase ;"><strong>{{ \Carbon\Carbon::parse($month)->translatedFormat('F') }}</strong></span></td>
                                @foreach ($couvertures as $couverture)
                                    @foreach ($types_test as $type_test)
                                        <td>{{ $report[$month][$type_test->type_test ][$couverture]['depistage'] ?? '0' }}</td>
                                        <td>{{ $report_positif[$month][$type_test->type_test ][$couverture]['depistage_positif'] ?? '0' }}</td>
                                    @endforeach
                                    <td><strong>{{ $report_total[$month][$couverture]['total'] ?? '0' }}</strong></td>
                                    <td><strong>{{ $report_total_positif[$month][$couverture]['total_positif'] ?? '0' }}</strong></td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            @else
                <div class="card card-custom mb-3 mt-3">
                    <div class="card-body">
                        <table class="table table-bordered text-center mb-4">
                            <tr>
                                <th colspan="8">Couverture Oragnique SH</th>
                            </tr>
                            <tr>
                                <th colspan="2">PCR</th>
                                <th colspan="2">Antigénique</th>
                                <th colspan="2">Sérologie</th>
                                <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> dépisté</th>
                                <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> (+)</th>
                            </tr>
                            <tr class="divide">
                                <th>Dépistage</th>
                                <th>Nbr (+)</th>
                                <th>Dépistage</th>
                                <th>Nbr (+)</th>
                                <th>Dépistage</th>
                                <th>Nbr (+)</th>
                            </tr>
                            <tr class="divide">
                                <td>{{ $sh_pcr }}</td>
                                <td>{{ $sh_pcr_pos }}</td>
                                <td>{{ $sh_antigenique }}</td>
                                <td>{{ $sh_antigenique_pos }}</td>
                                <td>{{ $sh_serologique }}</td>
                                <td>{{ $sh_serologique_pos }}</td>
                                <td>{{ $sh_depistage }}</td>
                                <td>{{ $sh_depistage_pos }}</td>
                            </tr>
                        </table>
                        <table class="table table-bordered text-center mb-0">
                            <tr>
                                <th colspan="8">Couverture Sous-Traitants</th>
                            </tr>
                            <tr>
                                <th colspan="2">PCR</th>
                                <th colspan="2">Antigénique</th>
                                <th colspan="2">Sérologie</th>
                                <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> dépisté</th>
                                <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> (+)</th>
                            </tr>
                            <tr class="divide">
                                <th>Dépistage</th>
                                <th>Nbr (+)</th>
                                <th>Dépistage</th>
                                <th>Nbr (+)</th>
                                <th>Dépistage</th>
                                <th>Nbr (+)</th>
                            </tr>
                            <tr class="divide">
                                <td>{{ $st_pcr }}</td>
                                <td>{{ $st_pcr_pos }}</td>
                                <td>{{ $st_antigenique }}</td>
                                <td>{{ $st_antigenique_pos }}</td>
                                <td>{{ $st_serologique }}</td>
                                <td>{{ $st_serologique_pos }}</td>
                                <td>{{ $st_depistage }}</td>
                                <td>{{ $st_depistage_pos }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
            <a href="#" data-bs-toggle="modal" data-bs-target="#generateReportingModal" class="btn btn-dark-no-radius"><i class="fa-solid fa-file-pdf me-2"></i>Générer Reporting (PDF)</a>
            @include('CMT.depistage.generateReporting')
        </div>
    </div>
</div>
@endsection
