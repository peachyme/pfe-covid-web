@extends('layouts.app')
@include('partials.navbar')
@section('content')
    <div class="main-container d-flex">
        @include('partials.sidebar')
        <div class="content">
            <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
                <h4>Situation {{$situation}} de l'opération de vaccinations des sites DPS</h4>
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
                        <form action="{{ route('HSE.DRs.vaccination') }}" method="GET">
                            <div class="row">
                                <div class="col-2">
                                    <select name="region" class="form-select form-control form-control-custom"
                                        aria-label="Default select example">
                                        <option value="" selected hidden>region...</option>
                                        @foreach ($regions_select as $region)
                                            <option value="{{ $region->id }}">{{ $region->code_region }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select name="couverture" class="form-select form-control form-control-custom"
                                        aria-label="Default select example">
                                        <option value="" selected hidden>couverture...</option>
                                        <option value="O">Organique SH</option>
                                        <option value="ST">Sous-Traitants</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <input type="text" min="2020-W09" max="{{ $current_year }}-W{{ $current_week }}"
                                        class="form-control form-control-custom" id="week" name="week"
                                        placeholder="Hebdomadaire" onfocus="(this.type='week')" onblur="(this.type='text')">
                                </div>
                                <div class="col-2">
                                    <input type="text" min="2020-03" max="{{ $current_month }}"
                                        class="form-control form-control-custom" id="month" name="month"
                                        placeholder="Mensuel" onfocus="(this.type='month')" onblur="(this.type='text')">
                                </div>
                                <div class="col-2">
                                    <input type="number" min="2020" max="{{ $current_year }}"
                                        class="form-control form-control-custom" id="year" name="year"
                                        placeholder="Annuel">
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-dark-no-radius" style="width: 100%"><i
                                            class="fa-solid fa-magnifying-glass me-2"></i>Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-custom mb-4">
                    <div class="card-body pb-1">
                        {{-- organique --}}
                        @if ($couv == 'O')
                            @if (!$no_region)
                                <table class="table table-hover table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="col-1">Sites <br> DP</th>
                                            <th scope="col" class="col-2">Effectif <br> Agents SH</th>
                                            <th scope="col" class="col">Total des vaccinés {{ $situation }}</th>
                                            <th scope="col" class="col-2">Cumul <br> vaccination</th>
                                            <th scope="col" class="col-2">% <br> vaccination</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">{{ $code_region }}</th>
                                            <td>{{ $effectif_agents }}</td>
                                            <td>{{ $total_vacc_agents }}</td>
                                            <td>{{ $cumul_agents }}</td>
                                            <td>{{ $pourcentage_agents }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="table table-hover table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="col-1">Sites <br> DP</th>
                                            <th scope="col" class="col-2">Effectif <br> Agents SH</th>
                                            <th scope="col" class="col">Total des vaccinés {{ $situation }}</th>
                                            <th scope="col" class="col-2">Cumul <br> vaccination</th>
                                            <th scope="col" class="col-2">% <br> vaccination</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regions as $region)
                                            <tr>
                                                <th scope="row">{{ $region->code_region }}</th>
                                                <td>{{ $report_effectif_agents[$region->id]['total'] ?? 0 }}</td>
                                                <td>{{ $report_total_vacc_agents[$region->id]['total'] ?? 0 }}</td>
                                                <td>{{ $report_cumul_agents[$region->id]['total'] ?? 0 }}</td>
                                                <td>{{ $report_pourcentage_agents[$region->id]['total'] ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="col pagination justify-content-end mb-0">{{ $regions->links() }}</div>
                            @endif
                        @endif

                        {{-- sous traitans --}}
                        @if ($couv == 'ST')
                            @if (!$no_region)
                                <table class="table table-hover table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="col-1">Sites <br> DP</th>
                                            <th scope="col" class="col-2">Effectif <br> Sous-Traitans</th>
                                            <th scope="col" class="col">Total des vaccinés {{ $situation }}</th>
                                            <th scope="col" class="col-2">Cumul <br> vaccination</th>
                                            <th scope="col" class="col-2">% <br> vaccination</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">{{ $code_region }}</th>
                                            <td>{{ $effectif_st }}</td>
                                            <td>{{ $total_vacc_st }}</td>
                                            <td>{{ $cumul_st }}</td>
                                            <td>{{ $pourcentage_st }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="table table-hover table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="col-1">Sites <br> DP</th>
                                            <th scope="col" class="col-2">Effectif <br> Sous-Traitans</th>
                                            <th scope="col" class="col">Total des vaccinés {{ $situation }}</th>
                                            <th scope="col" class="col-2">Cumul <br> vaccination</th>
                                            <th scope="col" class="col-2">% <br> vaccination</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regions as $region)
                                            <tr>
                                                <th scope="row">{{ $region->code_region }}</th>
                                                <td>{{ $report_effectif_st[$region->id]['total'] ?? 0 }}</td>
                                                <td>{{ $report_total_vacc_st[$region->id]['total'] ?? 0 }}</td>
                                                <td>{{ $report_cumul_st[$region->id]['total'] ?? 0 }}</td>
                                                <td>{{ $report_pourcentage_st[$region->id]['total'] ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="col pagination justify-content-end mb-0">{{ $regions->links() }}</div>
                            @endif
                        @endif

                    </div>
                </div>
                <div class="row">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#generateReportingModal" class="btn btn-dark-no-radius"><i class="fa-solid fa-file-pdf me-2"></i>Générer Reporting</a>
                    @include('HSE.DRs.vaccination.generateReporting')
                </div>
            </div>
        </div>
    </div>
@endsection
