@extends('layouts.app')
@include('partials.navbar')

<meta http-equiv="refresh" content="30">
@section('content')
    <div class="main-container d-flex">
        @include('partials.sidebar')
        <div class="content">
            <div class="justify-content-center mx-3 mt-3">
                {{-- start charts --}}
                {{-- start cards --}}
                <div class="row mb-4">
                    <div class="col">
                        <div class="card card-dashboard h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-grayy">Cas Positifs</h5>
                                <div class="row">
                                    <div class="col">
                                        <p class="card-text fw-bold orange">Total : {{ $cas_positifs_total }}</p>
                                    </div>
                                    <div class="col">
                                        <p class="card-text fw-bold orange">Actives : {{ $cas_positif_actives }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-dashboard h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-grayy">Cas Hospitalisés</h5>
                                <div class="row">
                                    <div class="col">
                                        <p class="card-text fw-bold orange">Total : {{ $cas_hospitalisés_total }}</p>
                                    </div>
                                    <div class="col">
                                        <p class="card-text fw-bold orange">Actives : {{ $cas_hospitalisés_actives }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-dashboard h-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title fw-bold text-grayy">Guéris</h5>
                                        <p class="card-text fw-bold orange">Total : {{ $cas_guéris }}</p>
                                    </div>
                                    <div class="col">
                                        <h5 class="card-title fw-bold text-grayy">Décès</h5>
                                        <p class="card-text fw-bold orange">Total : {{ $deces }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-dashboard h-100">
                            {{-- style="background: linear-gradient(to right, #d9d9d9, #e6e6e6,#e6e6e6, #f2f2f2)"> --}}
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-grayy">Vaccination</h5>
                                <div class="row">
                                    <div class="col">
                                        <p class="card-text fw-bold orange">Total : {{ $vaccination_total }}</p>
                                    </div>
                                    <div class="col">
                                        <p class="card-text fw-bold orange">% : {{ $vaccination_pourcentage }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ((auth()->user()->roles()->where('role','Président cellue de crise')->exists()) || (auth()->user()->roles()->where('role','Coordinateur gestion social')->exists()) || (auth()->user()->roles()->where('role','Inspécteur de prévention')->exists()))
                    <div class="col-0 card card-custom right-sidebar-toggler-wrapper bg-light p-3">
                        <button class="btn-no-btn" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="fa-solid fa-chart-line"></i></button>
                    </div>
                    @endif
                </div>
                {{-- end cards --}}

                {{-- charts --}}
                <div class="row mb-4">
                    @if ($type == 'default')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-2">
                                    <div class="text-center mt-1 mb-3">
                                        <h6 class="text-grayy"><strong>Situation COVID-19 Division Production</strong></h6>
                                    </div>
                                    <div style="height: 380px" class="mt-0">{!! $covid_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'couverture')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-2 pt-2">
                                    <div class="row mt-0 pt-2">
                                        <div class="col-10 text-center ps-5 mt-2">
                                            <h6 class="text-grayy"><strong>Situation COVID-19 par couverture</strong></h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-2">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <form action="{{ route('dashboard', 'couverture') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="quot" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-left btn-padding btn-secondary">Q</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'couverture') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="hebd" value="du{{ \Carbon\Carbon::now()->startOfWeek()->format('d-m-Y') }}au{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit" class="btn btn-padding btn-secondary">H</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'couverture') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="mens" value="{{ \Carbon\Carbon::now()->format('m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-padding btn-secondary">M</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'couverture') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="ann" value="{{ \Carbon\Carbon::now()->format('Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-right btn-padding btn-secondary">A</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px" class="mt-0">{!! $couverture_line_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'regional')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-2 pt-2">
                                    <div class="row mt-0 pt-2">
                                        <div class="col-10 text-center ps-5 mt-2">
                                            <h6 class="text-grayy"><strong>Situation Régionale COVID-19</strong></h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-2">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <form action="{{ route('dashboard', 'regional') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="quotidien" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-left btn-padding btn-secondary">Q</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'regional') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="hebdomadaire" value="du{{ \Carbon\Carbon::now()->startOfWeek()->format('d-m-Y') }}au{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit" class="btn btn-padding btn-secondary">H</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'regional') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="mensuel" value="{{ \Carbon\Carbon::now()->format('m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-padding btn-secondary">M</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'regional') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="anuel" value="{{ \Carbon\Carbon::now()->format('Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-right btn-padding btn-secondary">A</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px" class="mt-0">{!! $regional_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'cmt')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-2 pt-2">
                                    <div class="row mt-0 pt-2">
                                        <div class="col-10 text-center ps-5 mt-2">
                                            <h6 class="text-grayy"><strong>Situation COVID-19 par CMT</strong></h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-2">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <form action="{{ route('dashboard', 'cmt') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="Q" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-left btn-padding btn-secondary">Q</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'cmt') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="H" value="du{{ \Carbon\Carbon::now()->startOfWeek()->format('d-m-Y') }}au{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit" class="btn btn-padding btn-secondary">H</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'cmt') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="M" value="{{ \Carbon\Carbon::now()->format('m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-padding btn-secondary">M</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'cmt') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="A" value="{{ \Carbon\Carbon::now()->format('Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-right btn-padding btn-secondary">A</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px" class="pt-2">{!! $cmt_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'structure')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-2 pt-2">
                                    <div class="row mt-0 pt-2">
                                        <div class="col-10 text-center ps-5 mt-2">
                                            <h6 class="text-grayy"><strong>Situation COVID-19 par structure</strong></h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-2">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <form action="{{ route('dashboard', 'structure') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="quo" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-left btn-padding btn-secondary">Q</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'structure') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="heb" value="du{{ \Carbon\Carbon::now()->startOfWeek()->format('d-m-Y') }}au{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit" class="btn btn-padding btn-secondary">H</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'structure') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="men" value="{{ \Carbon\Carbon::now()->format('m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-padding btn-secondary">M</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'structure') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="an" value="{{ \Carbon\Carbon::now()->format('Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-right btn-padding btn-secondary">A</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px" class="mt-0">{!! $structure_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'covid_couverture')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-2 pt-2">
                                    <div class="row mt-0 pt-2">
                                        <div class="col-10 text-center ps-5 mt-2">
                                            <h6 class="text-grayy"><strong>Situation COVID-19 par couverture</strong></h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-2">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <form action="{{ route('dashboard', 'covid_couverture') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="hebdo" value="du{{ \Carbon\Carbon::now()->startOfWeek()->format('d-m-Y') }}au{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                                    <button type="submit" class="btn btn-radius-left btn-padding btn-secondary">H</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'covid_couverture') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="mensu" value="{{ \Carbon\Carbon::now()->format('m-Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-padding btn-secondary">M</button>
                                                </form>
                                                <form action="{{ route('dashboard', 'covid_couverture') }}" method="GET"
                                                    class="my-0 py-0">
                                                    <input type="hidden" name="annu" value="{{ \Carbon\Carbon::now()->format('Y') }}">
                                                    <button type="submit"
                                                        class="btn btn-radius-right btn-padding btn-secondary">A</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px" class="mt-0">{!! $couverture_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'vaccin')
                        <div class="col-3">
                            <div class="card card-custom px-0 py-0">
                                <div class="card-body pb-3 pt-2 px-0" style="width: 80%; margin: auto">
                                    <div class="row mt-1 mt-0">
                                        <div class="col-10 text-center mt-2 ps-4">
                                            <h6 class="text-grayy"><strong>Situation Doses</strong></h6>
                                            </h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-0">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    CMT
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    @foreach ($cmts as $cmt)
                                                        <form action="{{ route('dashboard', 'vaccin') }}" method="GET"
                                                            class="my-0 py-0">
                                                            <input type="hidden" value="{{ $cmt->id }}"
                                                                name="cmt">
                                                            <li><button type="submit"
                                                                    class="dropdown-item btn-no-btn">{{ $cmt->code_cmt }}</button>
                                                            </li>
                                                        </form>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px">{!! $dose_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card card-custom px-0 py-0">
                                <div class="card-body pb-3 pt-2 px-0" style="width: 80%; margin: auto">
                                    <div class="row mt-1 mt-0">
                                        <div class="col-10 text-center mt-2 ps-4">
                                            <h6 class="text-grayy"><strong>Situation Vaccins</strong></h6>
                                            </h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-0">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    CMT
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    @foreach ($cmts as $cmt)
                                                        <form action="{{ route('dashboard', 'vaccin') }}" method="GET"
                                                            class="my-0 py-0">
                                                            <input type="hidden" value="{{ $cmt->id }}"
                                                                name="cmtt">
                                                            <li><button type="submit"
                                                                    class="dropdown-item btn-no-btn">{{ $cmt->code_cmt }}</button>
                                                            </li>
                                                        </form>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px">{!! $vaccin_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'age_sexe')
                        <div class="col-3">
                            <div class="card card-custom px-0 py-0">
                                <div class="card-body pb-3 pt-2 px-0" style="width: 80%; margin: auto">
                                    <div class="row mt-1 mt-0">
                                        <div class="col-10 text-center mt-2 ps-4">
                                            <h6 class="text-grayy"><strong>Situation par sexe</strong></h6>
                                            </h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-0">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-secondary dropdown-toggle btn-sm"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Couv
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <form action="{{ route('dashboard', 'age_sexe') }}" method="GET"
                                                        class="my-0 py-0">
                                                        <input type="hidden" value="O" name="couverture">
                                                        <li><button type="submit"
                                                                class="dropdown-item btn-no-btn">Organique
                                                                SH</button>
                                                        </li>
                                                    </form>
                                                    <form action="{{ route('dashboard', 'age_sexe') }}" method="GET"
                                                        class="my-0 py-0">
                                                        <input type="hidden" value="ST" name="couverture">
                                                        <li><button type="submit"
                                                                class="dropdown-item btn-no-btn">Sous-Traitans</button>
                                                        </li>
                                                    </form>
                                                    <form action="{{ route('dashboard', 'age_sexe') }}" method="GET"
                                                        class="my-0 py-0">
                                                        <input type="hidden" value="" name="couverture">
                                                        <li><button type="submit"
                                                                class="dropdown-item btn-no-btn">couverture
                                                                globale</button>
                                                        </li>
                                                    </form>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px">{!! $sexe_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card card-custom px-0 py-0">
                                <div class="card-body pb-3 pt-2 px-0" style="width: 80%; margin: auto">
                                    <div class="row mt-1 mt-0">
                                        <div class="col-10 text-center mt-2 ps-4">
                                            <h6 class="text-grayy"><strong>Situation par age</strong></h6>
                                            </h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end pe-0">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-secondary dropdown-toggle btn-sm"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Couv
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <form action="{{ route('dashboard', 'age_sexe') }}" method="GET"
                                                        class="my-0 py-0">
                                                        <input type="hidden" value="O" name="couverturee">
                                                        <li><button type="submit"
                                                                class="dropdown-item btn-no-btn">Organique
                                                                SH</button>
                                                        </li>
                                                    </form>
                                                    <form action="{{ route('dashboard', 'age_sexe') }}" method="GET"
                                                        class="my-0 py-0">
                                                        <input type="hidden" value="ST" name="couverturee">
                                                        <li><button type="submit"
                                                                class="dropdown-item btn-no-btn">Sous-Traitans</button>
                                                        </li>
                                                    </form>
                                                    <form action="{{ route('dashboard', 'age_sexe') }}" method="GET"
                                                        class="my-0 py-0">
                                                        <input type="hidden" value="" name="couverturee">
                                                        <li><button type="submit"
                                                                class="dropdown-item btn-no-btn">couverture
                                                                globale</button>
                                                        </li>
                                                    </form>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px">{!! $age_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'depistage')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-3 pt-2">
                                    <div class="row mt-1 mt-0">
                                        <div class="col-10 text-center ps-5 mt-2">
                                            <h6 class="text-grayy"><strong>Situation Dépistage COVID-19 par CMT</strong>
                                            </h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    CMT
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    @foreach ($cmts as $cmt)
                                                        <form action="{{ route('dashboard', 'depistage') }}"
                                                            method="GET" class="my-0 py-0">
                                                            <input type="hidden" value="{{ $cmt->id }}"
                                                                name="cmt">
                                                            <li><button type="submit"
                                                                    class="dropdown-item btn-no-btn">{{ $cmt->code_cmt }}</button>
                                                            </li>
                                                        </form>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 380px" class="mt-0">{!! $depistage_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($type == 'zones')
                        <div class="col-6">
                            <div class="card card-custom">
                                <div class="card-body pb-3 pt-2">
                                    <div class="row mt-1 mt-0">
                                        <div class="col-10 text-center ps-5 mt-2">
                                            <h6 class="text-grayy ms-5"><strong>Situation Zones de confinement</strong>
                                            </h6>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Region
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    @foreach ($regions as $region)
                                                        @if ($region->id != 1)
                                                            <form action="{{ route('dashboard', 'zones') }}"
                                                                method="GET" class="my-0 py-0">
                                                                <input type="hidden" value="{{ $region->id }}"
                                                                    name="region">
                                                                <li><button type="submit"
                                                                        class="dropdown-item btn-no-btn">{{ $region->code_region }}</button>
                                                                </li>
                                                            </form>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: 375px">{!! $zones_chart->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- start default doughnut charts --}}
                    <div class="col-3">
                        <div class="card card-custom px-0 py-0">
                            <div class="card-body pb-3 px-0" style="width: 80%; margin: auto">
                                <div class="text-center mt-1">
                                    <h6 class="text-grayy"><strong>Situation Vaccination</strong></h6>
                                </div>
                                <div style="height: 380px">{!! $vaccination_chart->container() !!}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card card-custom px-0 py-0">
                            <div class="card-body pb-3 px-0" style="width: 80%; margin: auto">
                                <div class="text-center mt-1 mb-0">
                                    <h6 class="text-grayy"><strong>Situation Dépistage</strong></h6>
                                </div>
                                <div style="height: 380px" class="mt-0">{!! $covid_test_chart->container() !!}</div>
                            </div>
                        </div>
                    </div>
                    {{-- end default doughnut charts --}}
                </div>
                {{-- end charts --}}

                {{-- start partials : right sidebar for charts --}}

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                    aria-labelledby="offcanvasRightLabel" style="width: 25%;">
                    <div class="offcanvas-header pt-4">
                        <h5 class="offcanvas-title" id="offcanvasRightLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="offcanvas-body px-0">
                        <div class="row">
                            @if (auth()->user()->roles()->where('role', 'Inspécteur de prévention')->exists())
                                <a href="{{ route('dashboard', 'default') }}"
                                    class="text-decoration-none text-grayy mt-4 ps-5 py-4  {{ 'dashboard/default' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-line me-2"></i>Situation COVID-19 Division Production</a>
                                <a href="{{ route('dashboard', 'regional') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/regional' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-column me-2"></i>Situation Régionale COVID-19</a>
                                <a href="{{ route('dashboard', 'couverture') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/couverture' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-line me-2"></i>Situation COVID-19 par couverture</a>
                                <a href="{{ route('dashboard', 'zones') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/zones' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-bar me-2"></i>Situation Zones de confinement</a>
                            @endif
                            @if (auth()->user()->roles()->where('role', 'Coordinateur gestion social')->exists())
                                <a href="{{ route('dashboard', 'default') }}"
                                    class="text-decoration-none text-grayy mt-4 ps-5 py-4  {{ 'dashboard/default' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-line me-2"></i>Situation COVID-19 Division Production</a>
                                <a href="{{ route('dashboard', 'cmt') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/cmt' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-column me-2"></i>Situation COVID-19 par CMT</a>
                                <a href="{{ route('dashboard', 'depistage') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/depistage' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-column me-2"></i>Situation Dépistage par type test et par
                                    CMT</a>
                                <a href="{{ route('dashboard', 'vaccin') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  pe-4 {{ 'dashboard/vaccin' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-bar me-2"></i>Situation Vaccination par dose et par
                                    type vaccin</a>
                            @endif
                            @if (auth()->user()->roles()->where('role', 'Président cellue de crise')->exists())
                                <a href="{{ route('dashboard', 'default') }}"
                                    class="text-decoration-none text-grayy mt-4 ps-5 py-4  {{ 'dashboard/default' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-line me-2"></i>Situation COVID-19 Division Production</a>
                                <a href="{{ route('dashboard', 'regional') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/regional' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-line me-2"></i>Situation Régionale COVID-19</a>
                                <a href="{{ route('dashboard', 'structure') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/structure' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-column me-2"></i>Situation COVID-19 par structure</a>
                                <a href="{{ route('dashboard', 'age_sexe') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/age_sexe' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-line me-2"></i>Situation COVID-19 par age et par sexe</a>
                                <a href="{{ route('dashboard', 'covid_couverture') }}"
                                    class="text-decoration-none text-grayy ps-5 py-4  {{ 'dashboard/covid_couverture' == request()->path() ? ' text-orangee bg-gray fw-bold' : '' }}">
                                    <i class="fa-solid fa-chart-bar me-2"></i>Situation COVID-19 par couverture</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- end partials : right sidebar for charts --}}


                {!! $covid_chart->script() !!}
                {!! $covid_test_chart->script() !!}
                {!! $vaccination_chart->script() !!}
                {!! $regional_chart->script() !!}
                {!! $couverture_line_chart->script() !!}
                {!! $zones_chart->script() !!}
                {!! $cmt_chart->script() !!}
                {!! $depistage_chart->script() !!}
                {!! $dose_chart->script() !!}
                {!! $vaccin_chart->script() !!}
                {!! $sexe_chart->script() !!}
                {!! $age_chart->script() !!}
                {!! $structure_chart->script() !!}
                {!! $couverture_chart->script() !!}
            </div>
        </div>
    </div>
@endsection
