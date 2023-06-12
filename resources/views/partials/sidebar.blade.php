{{-- sidebar de la secrétaire médicale --}}
@if (auth()->user()->roles()->where('role','Secrétaire médicale')->exists())
    <div class="sidebar bg-custom-gray" id="side-nav">
        <ul class="list-unstyled">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class="col-4 ms-0 ps-4 me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                    <div class="col-7 ps-0 mx-0 px-0">
                        <span class="fw-bold mb-2 text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span><br>
                        <span class="text-gray small">{{ auth()->user()->roles[0]->role }} {{ auth()->user()->region_cmt }}</span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line mx-3"></i>Tableau de Bord</a></li>
            <li class="d-block py-3 ms-2">
                <a href="#" onclick="changeArrow1();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#covid-collapse" aria-expanded="true">
                    <i class="fa-solid fa-virus-covid mx-3"></i>Covid-19 &nbsp;<i id="arrow1" class="fa-solid fa-angle-right"></i></a>
                <ul class="list-unstyled collapse px-4 ms-3  mt-2" id="covid-collapse">
                    <li class="d-block py-2 {{'CMT/consultations/create' == request()->path() ? 'active' : ''}}" onclick="activate1(this.id)"><a href="{{ route('CMT.consultations.create') }}" class="text-decoration-none" onclick="changeArrow();"><i class="fa-solid fa-hand-holding-medical me-3"></i>Nouvelle consultation</a></li>
                    <li class="d-block py-2 {{'CMT/consultations' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.consultations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-days me-3"></i>Situation du jour</a></li>
                </ul>
            </li>
            <li class="d-block py-3 ms-2">
                <a href="#" onclick="changeArrow2();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#vacc-collapse" aria-expanded="true">
                    <i class="fa-solid fa-syringe mx-3"></i>Vaccination &nbsp;<i id="arrow2" class="fa-solid fa-angle-right"></i></a>
                <ul class="list-unstyled collapse px-4 ms-3 mt-2" id="vacc-collapse">
                    <li class="d-block py-2 {{'CMT/vaccinations/create' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.vaccinations.create') }}" class="text-decoration-none"><i class="fa-solid fa-hand-holding-medical me-3"></i>Nouveau vacciné</a></li>
                    <li class="d-block py-2  {{'CMT/vaccinations' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.vaccinations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-day me-3"></i>Situation du jour</a></li>
                </ul>
            </li>
            <li class="d-block py-3 ms-2 {{'CMT/depistage' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.depistage') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3"></i>Activité quotidienne du CMT</a></li>
            <li class="d-block py-3 ms-2 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.cmts.index') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3 me-4"></i>Situation CMTs</a></li>
        </ul>
    </div>

    <!-- toggled sidebar -->
    <div class="sidebar bg-custom-gray sidebar-toggle hidden" id="side-nav-toggled">
        <ul class="list-unstyled text-center ps-0 fs-5">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class=" pe-5 me-5 ps-3 ms-2  me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line"></i></a></li>
            <li class="d-block py-3">
                <a href="#" onclick="changeArrow1();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#covid-collapse" aria-expanded="true">
                    <i class="fa-solid fa-virus-covid"></i></a>
                <ul class="list-unstyled collapse px-4 mt-2 ms-1" id="covid-collapse">
                    <li class="d-block py-2 {{'CMT/consultations/create' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.consultations.create') }}" class="text-decoration-none"><i class="fa-solid fa-hand-holding-medical me-3"></i></a></li>
                    <li class="d-block py-2 {{'CMT/consultations' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.consultations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-days me-3"></i></a></li>
                </ul>
            </li>
            <li class="d-block py-3">
                <a href="#" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#vacc-collapse" aria-expanded="true">
                    <i class="fa-solid fa-syringe"></i></a>
                <ul class="list-unstyled collapse px-4 mt-2 ms-1" id="vacc-collapse">
                    <li class="d-block py-2  {{'CMT/vaccinations' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.vaccinations.index') }}" class="text-decoration-none"><i class="fa-solid fa-hand-holding-medical me-3"></i></a></li>
                    <li class="d-block py-2 {{'CMT/vaccinations' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.vaccinations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-day me-3"></i></a></li>
                </ul>
            </li>
            <li class="d-block py-3 {{'CMT/depistage' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.depistage') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.cmts.index') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3"></i></a></li>
        </ul>
    </div>
@endif

{{-- sidebar du reponsable de reporting --}}
@if (auth()->user()->roles()->where('role','Responsable de Reporting')->exists())
    {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">
         {{ __('List des utilisateurs') }}
    </a> --}}
    <div class="sidebar bg-custom-gray" id="side-nav">
        <ul class="list-unstyled">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class="col-4 ms-0 ps-4 me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                    <div class="col-7 ps-0 mx-0 px-0">
                        <span class="fw-bold mb-2 text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span><br>
                        <span class="text-gray small">{{ auth()->user()->roles[0]->role }} {{ auth()->user()->region_cmt }}</span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line mx-3"></i>Tableau de Bord</a></li>
            <li class="d-block py-3 ms-2">
                <a href="#" onclick="changeArrow1();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#covid-collapse" aria-expanded="true">
                    <i class="fa-solid fa-virus-covid mx-3"></i>Covid-19 &nbsp;<i id="arrow1" class="fa-solid fa-angle-right"></i></a>
                <ul class="list-unstyled collapse px-4 ms-3  mt-2" id="covid-collapse">
                    <li class="d-block py-2 {{'DR/consultations/create' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.consultations.create') }}" class="text-decoration-none" onclick="changeArrow();"><i class="fa-solid fa-hand-holding-medical me-3"></i>Nouvelle consultation</a></li>
                    <li class="d-block py-2 {{'DR/consultations' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.consultations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-days me-3"></i>Situation du jour</a></li>
                </ul>
            </li>
            <li class="d-block py-3 ms-2">
                <a href="#" onclick="changeArrow2();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#vacc-collapse" aria-expanded="true">
                    <i class="fa-solid fa-syringe mx-3"></i>Vaccination &nbsp;<i id="arrow2" class="fa-solid fa-angle-right"></i></a>
                <ul class="list-unstyled collapse px-4 ms-3 mt-2" id="vacc-collapse">
                    <li class="d-block py-2 {{'DR/vaccinations/create' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.vaccinations.create') }}" class="text-decoration-none"><i class="fa-solid fa-hand-holding-medical me-3"></i>Nouveau vacciné</a></li>
                    <li class="d-block py-2 {{'DR/vaccinations' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.vaccinations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-day me-3"></i>Situation du jour</a></li>
                </ul>
            </li>
            <li class="d-block py-3 ms-2 {{'DR/releves' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.releves.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital-user mx-3"></i>Confinement - Situation jour</a></li>
            <li class="d-block py-3 ms-2 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.regions.index') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot mx-3 me-4"></i>Directions régionales</a></li>
            <li class="d-block py-3 ms-2 {{'DR/zones' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.zones.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital mx-3"></i>Zones de confinement</a></li>
        </ul>
    </div>

    <!-- toggled sidebar -->
    <div class="sidebar bg-custom-gray sidebar-toggle hidden" id="side-nav-toggled">
        <ul class="list-unstyled text-center ps-0 fs-5">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class=" pe-5 me-5 ps-3 ms-2  me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line"></i></a></li>
            <li class="d-block py-3">
                <a href="#" onclick="changeArrow1();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#covid-collapse" aria-expanded="true">
                    <i class="fa-solid fa-virus-covid"></i></a>
                <ul class="list-unstyled collapse px-4 mt-2 ms-1" id="covid-collapse">
                    <li class="d-block py-2 {{'DR/consultations/create' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.consultations.create') }}" class="text-decoration-none"><i class="fa-solid fa-hand-holding-medical me-3"></i></a></li>
                    <li class="d-block py-2 {{'DR/consultations' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.consultations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-days me-3"></i></a></li>
                </ul>
            </li>
            <li class="d-block py-3">
                <a href="#" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#vacc-collapse" aria-expanded="true">
                    <i class="fa-solid fa-syringe"></i></a>
                <ul class="list-unstyled collapse px-4 mt-2 ms-1" id="vacc-collapse">
                    <li class="d-block py-2 {{'DR/vaccinations/create' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.vaccinations.create') }}" class="text-decoration-none"><i class="fa-solid fa-hand-holding-medical me-3"></i></a></li>
                    <li class="d-block py-2 {{'DR/vaccinations' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.vaccinations.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-days me-3"></i></a></li>
                </ul>
            </li>
            <li class="d-block py-3 {{'DR/releves' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.releves.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital-user mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.regions.index') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/zones' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.zones.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital mx-3"></i></a></li>

        </ul>
    </div>
@endif

{{-- sidebar du coordinateur gestion social --}}
@if (auth()->user()->roles()->where('role','Coordinateur gestion social')->exists())
    {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">
         {{ __('List des utilisateurs') }}
    </a> --}}
    <div class="sidebar bg-custom-gray" id="side-nav">
        <ul class="list-unstyled">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class="col-4 ms-0 ps-4 me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                    <div class="col-7 ps-0 mx-0 px-0">
                        <span class="fw-bold mb-2 text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span><br>
                        <span class="text-gray small">{{ auth()->user()->roles[0]->role }} {{ auth()->user()->region_cmt }}</span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line mx-3"></i>Tableau de Bord</a></li>
            <li class="d-block py-3 ms-2 {{'CMT/situation_covid' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.covid') }}" class="text-decoration-none"><i class="fa-solid fa-virus-covid mx-3"></i>Covid-19</a></li>
            <li class="d-block py-3 ms-2 {{'CMT/situation_vaccination' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.vaccination') }}" class="text-decoration-none"><i class="fa-solid fa-syringe mx-3"></i>Vaccination</a></li>
            <li class="d-block py-3 ms-2 {{'CMT/situation_depistage' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.depistages') }}" class="text-decoration-none"><i class="fa-solid fa-file-medical mx-3"></i>Rapport d'activité des CMTs</a></li>
            <li class="d-block py-3 ms-2 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.cmts.index') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3"></i>Situation CMTs</a></li>
        </ul>
    </div>

    <!-- toggled sidebar -->
    <div class="sidebar bg-custom-gray sidebar-toggle hidden" id="side-nav-toggled">
        <ul class="list-unstyled text-center ps-0 fs-5">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class=" pe-5 me-5 ps-3 ms-2  me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line"></i></a></li>
            <li class="d-block py-3 {{'CMT/situation_covid' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.covid') }}" class="text-decoration-none"><i class="fa-solid fa-virus-covid mx-3"></i></a></li>
            <li class="d-block py-3 {{'CMT/situation_vaccination' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.vaccination') }}" class="text-decoration-none"><i class="fa-solid fa-syringe mx-3"></i></a></li>
            <li class="d-block py-3 {{'CMT/situation_depistage' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.depistages') }}" class="text-decoration-none"><i class="fa-solid fa-file-medical mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.cmts.index') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3"></i></a></li>
        </ul>
    </div>
@endif

{{-- sidebar de l'inspecteur de prévention --}}
@if (auth()->user()->roles()->where('role','Inspécteur de prévention')->exists())
    {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">
         {{ __('List des utilisateurs') }}
    </a> --}}
    <div class="sidebar bg-custom-gray" id="side-nav">
        <ul class="list-unstyled">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class="col-4 ms-0 ps-4 me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                    <div class="col-7 ps-0 mx-0 px-0">
                        <span class="fw-bold mb-2 text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span><br>
                        <span class="text-gray small">{{ auth()->user()->roles[0]->role }} {{ auth()->user()->region_cmt }}</span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line mx-3"></i>Tableau de Bord</a></li>
            <li class="d-block py-3 ms-2">
                <a href="#" onclick="changeArrow1();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#covid-collapse" aria-expanded="true">
                    <i class="fa-solid fa-virus-covid mx-3"></i>Covid-19 &nbsp;<i id="arrow1" class="fa-solid fa-angle-right"></i></a>
                <ul class="list-unstyled collapse px-4 ms-3  mt-2" id="covid-collapse">
                    <li class="d-block py-2 {{'CMTs/situation_covid' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.CMTs.covid') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical me-3"></i>Situation CMT</a></li>
                    <li class="d-block py-2 {{'DRs/situation_covid' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.DRs.covid') }}" class="text-decoration-none">&nbsp;<i class="fa-solid fa-location-dot me-3" style="font-size: 15px"></i>Situation sites DP</a></li>
                </ul>
            </li>
            <li class="d-block py-3 ms-2">
                <a href="#" onclick="changeArrow2();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#vacc-collapse" aria-expanded="true">
                    <i class="fa-solid fa-syringe mx-3"></i>Vaccination &nbsp;<i id="arrow2" class="fa-solid fa-angle-right"></i></a>
                <ul class="list-unstyled collapse px-4 ms-3 mt-2" id="vacc-collapse">
                    <li class="d-block py-2 {{'CMTs/situation_vaccination' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.CMTs.vaccination') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical me-3"></i>Situation CMT</a></li>
                    <li class="d-block py-2 {{'DRs/situation_vaccination' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.DRs.vaccination') }}" class="text-decoration-none">&nbsp;<i class="fa-solid fa-location-dot me-3" style="font-size: 15px"></i>Situation sites DP</a></li>
                </ul>
            </li>
            <li class="d-block py-3 ms-2 {{'DR/releves' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.releves.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital-user mx-3"></i>Confinement - Situation jour</a></li>
            <li class="d-block py-3 ms-2 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.regions.index') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot mx-3 me-4"></i>Directions régionales</a></li>
            <li class="d-block py-3 ms-2 {{'DRs/situation_zones' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.DRs.zones') }}" class="text-decoration-none"><i class="fa-solid fa-hospital mx-3"></i>Zones de confinement</a></li>
        </ul>
    </div>

    <!-- toggled sidebar -->
    <div class="sidebar bg-custom-gray sidebar-toggle hidden" id="side-nav-toggled">
        <ul class="list-unstyled text-center ps-0 fs-5">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class=" pe-5 me-5 ps-3 ms-2  me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line"></i></a></li>
            <li class="d-block py-3">
                <a href="#" onclick="changeArrow1();" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#covid-collapse" aria-expanded="true">
                    <i class="fa-solid fa-virus-covid"></i></a>
                <ul class="list-unstyled collapse px-4 mt-2 ms-2" id="covid-collapse">
                    <li class="d-block py-2"><a href=" {{'CMTs/situation_covid' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.CMTs.covid') }}"" class="text-decoration-none"><i class="fa-solid fa-house-medical me-3"></i></a></li>
                    <li class="d-block py-2 {{'DRs/situation_covid' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.DRs.covid') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot ms-1 me-3"></i></a></li>
                </ul>
            </li>
            <li class="d-block py-3">
                <a href="#" class="text-decoration-none btn-toggle" data-bs-toggle="collapse" data-bs-target="#vacc-collapse" aria-expanded="true">
                    <i class="fa-solid fa-syringe"></i></a>
                <ul class="list-unstyled collapse px-4 mt-2 ms-1" id="vacc-collapse">
                    <li class="d-block py-2 {{'CMTs/situation_vaccination' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.CMTs.vaccination') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical me-3"></i></a></li>
                    <li class="d-block py-2 {{'DRs/situation_vaccination' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.DRs.vaccination') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot ms-1 me-3"></i></a></li>
                </ul>
            </li>
            <li class="d-block py-3 {{'DR/releves' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.releves.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital-user mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.regions.index') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot mx-3"></i></a></li>
            <li class="d-block py-3 {{'DRs/situation_zones' == request()->path() ? 'active' : ''}}"><a href="{{ route('HSE.DRs.zones') }}" class="text-decoration-none"><i class="fa-solid fa-hospital mx-3"></i></a></li>
        </ul>
    </div>
@endif

{{-- sidebar de l'admin --}}
@if (auth()->user()->roles()->where('role','admin')->exists())
    {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">
         {{ __('List des utilisateurs') }}
    </a> --}}
    <div class="sidebar bg-custom-gray" id="side-nav">
        <ul class="list-unstyled">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class="col-4 ms-0 ps-4 me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                    <div class="col-7 ps-0 mx-0 px-0">
                        <span class="fw-bold mb-2 text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span><br>
                        <span class="text-gray small">{{ auth()->user()->roles[0]->role }} {{ auth()->user()->region_cmt }}</span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line mx-3"></i>Tableau de Bord</a></li>
            <li class="d-block py-3 ms-2 {{'admin/users' == request()->path() ? 'active' : ''}}"><a href="{{ route('admin.users.index') }}" class="text-decoration-none"><i class="fa-solid fa-users mx-3"></i>Utilisateurs</a></li>
            <li class="d-block py-3 ms-2 {{'admin/roles' == request()->path() ? 'active' : ''}}"><a href="{{ route('admin.roles.index') }}" class="text-decoration-none"><i class="fa-solid fa-user-lock mx-3"></i>Rôles</a></li>
            <li class="d-block py-3 ms-2 {{'CMT/cmts' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.cmts.index') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3 me-4"></i>CMTs</a></li>
            <li class="d-block py-3 ms-2 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.regions.index') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot mx-3 me-4"></i>Directions régionales</a></li>
            <li class="d-block py-3 ms-2 {{'DR/zones' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.zones.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital mx-3"></i>Zones de confinement</a></li>
        </ul>
    </div>

    <!-- toggled sidebar -->
    <div class="sidebar bg-custom-gray sidebar-toggle hidden" id="side-nav-toggled">
        <ul class="list-unstyled text-center ps-0 fs-5">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class=" pe-5 me-5 ps-3 ms-2  me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line"></i></a></li>
            <li class="d-block py-3 {{'admin/users' == request()->path() ? 'active' : ''}}"><a href="{{ route('admin.users.index') }}" class="text-decoration-none"><i class="fa-solid fa-users mx-3"></i></a></li>
            <li class="d-block py-3 {{'admin/roles' == request()->path() ? 'active' : ''}}"><a href="{{ route('admin.roles.index') }}" class="text-decoration-none"><i class="fa-solid fa-user-lock mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('CMT.cmts.index') }}" class="text-decoration-none"><i class="fa-solid fa-house-medical mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/regions' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.regions.index') }}" class="text-decoration-none"><i class="fa-solid fa-location-dot mx-3"></i></a></li>
            <li class="d-block py-3 {{'DR/zones' == request()->path() ? 'active' : ''}}"><a href="{{ route('DR.zones.index') }}" class="text-decoration-none"><i class="fa-solid fa-hospital mx-3"></i></a></li>

        </ul>
    </div>
@endif

{{-- default user sidebar --}}
@if (auth()->user()->roles()->where('role','Utilisateur')->exists())
    {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">
         {{ __('List des utilisateurs') }}
    </a> --}}
    <div class="sidebar bg-custom-gray" id="side-nav">
        <ul class="list-unstyled">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class="col-4 ms-0 ps-4 me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                    <div class="col-7 ps-0 mx-0 px-0">
                        <span class="fw-bold mb-2 text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span><br>
                        <span class="text-gray small">{{ auth()->user()->roles[0]->role }} {{ auth()->user()->region_cmt }}</span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line mx-3"></i>Tableau de Bord</a></li>
        </ul>
    </div>

    <!-- toggled sidebar -->
    <div class="sidebar bg-custom-gray sidebar-toggle hidden" id="side-nav-toggled">
        <ul class="list-unstyled text-center ps-0 fs-5">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class=" pe-5 me-5 ps-3 ms-2  me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line"></i></a></li>
        </ul>
    </div>
@endif

{{-- default user sidebar --}}
@if (auth()->user()->roles()->where('role','Président cellue de crise')->exists())
    {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">
         {{ __('List des utilisateurs') }}
    </a> --}}
    <div class="sidebar bg-custom-gray" id="side-nav">
        <ul class="list-unstyled">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class="col-4 ms-0 ps-4 me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                    <div class="col-7 ps-0 mx-0 px-0">
                        <span class="fw-bold mb-2 text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span><br>
                        <span class="text-gray small">{{ auth()->user()->roles[0]->role }} {{ auth()->user()->region_cmt }}</span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line mx-3"></i>Tableau de Bord</a></li>
            <li class="d-block py-3 active1 ms-2 {{str_contains(request()->path(), 'reunions') ? 'active' : ''}}"><a href="{{ route('reunions.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar-day mx-3"></i></i>Réunions</a></li>
        </ul>
    </div>

    <!-- toggled sidebar -->
    <div class="sidebar bg-custom-gray sidebar-toggle hidden" id="side-nav-toggled">
        <ul class="list-unstyled text-center ps-0 fs-5">
            <li class="d-block pt-3 pb-3 mb-2 profile">
                <a href="{{route('user.profile.index')}}" class="row text-decoration-none" id="no-hover">
                    <div class=" pe-5 me-5 ps-3 ms-2  me-0">
                        <span><img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="profile" class="rounded-circle" width="45" height="45"></span>
                    </div>
                </a>
            </li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard', 'default') }}" class="text-decoration-none"><i class="fa-solid fa-chart-line"></i></a></li>
            <li class="d-block py-3 active2 {{str_contains(request()->path(), 'reunions') ? 'active' : ''}}"><a href="{{ route('reunions.index') }}" class="text-decoration-none"><i class="fa-solid fa-calendar"></i></a></li>
        </ul>
    </div>
@endif

