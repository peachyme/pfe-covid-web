@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Situation des directions régionales</h4>
        </div>
        <div class="justify-content-center mx-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                            {{ $error }}
                    @endforeach
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (auth()->user()->roles()->where('role','admin')->exists())
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                    <a href="" data-bs-toggle="modal" data-bs-target="#addRegionModel" class="btn btn-dark-no-radius">Ajouter nouvelle direction régionale</a>
                </div>
            @endif
            @include('DR.regions.addRegion')
            <div class="card card-custom">
                <div class="card-body pb-1">
                    <table class="table table-hover table-bordered">
                        <thead>
                           <tr>
                                <th scope="col" class="col-2">Code</th>
                                <th scope="col">Libellé</th>
                                <th scope="col" class="col-2">Cas positifs</th>
                                <th scope="col" class="col-2">Décès</th>
                                @if (auth()->user()->roles()->where('role','admin')->exists())
                                <th class="text-center col-2" scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($regions) > 0)
                                @foreach ($regions as $region)
                                    <tr>
                                        <th scope="row">{{$region->code_region}}</td>
                                        <td>{{$region->libellé_region}}</td>
                                        <td>{{$report_pos[$region->id]['cas_positifs'] ?? 0}}</td>
                                        <td>{{$report_deces[$region->id]['cas_deces'] ?? 0}}</td>
                                        @if (auth()->user()->roles()->where('role','admin')->exists())
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editRegionModal{{$region->id}}" class="text-orange me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{route('DR.regions.destroy', $region->id)}}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-no-btn"><a class=" text-orange ms-1" href=""><i class="fa-solid fa-trash"></i></a></button>
                                            </form>
                                        </td>
                                        @include('DR.regions.editRegion')
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Pas de regions trouvées!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pagination justify-content-end mb-0">{{$regions->links()}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
