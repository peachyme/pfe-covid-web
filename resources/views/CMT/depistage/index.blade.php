@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Dépistage : Activité du {{$today}} du CMT-{{ auth()->user()->region_cmt }}</h4>
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
                <div class="card-body">
                    <table class="table table-bordered text-center mb-0">
                        <tr>
                            <th colspan="10">Couverture Oragnique SH</th>
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
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-body">
                    <table class="table table-bordered text-center mb-0">
                        <tr>
                            <th colspan="10">Couverture Sous-Traitants</th>
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
        </div>
    </div>
</div>
@endsection
