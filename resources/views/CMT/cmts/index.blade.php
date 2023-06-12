@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-3 bg-custom-dark">
            <h4>Situation des centres médicaux de travail</h4>
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
                    <a href="" data-bs-toggle="modal" data-bs-target="#addCMTModel" class="btn btn-dark-no-radius">Ajouter nouveau CMT</a>
                </div>
            @endif
            @include('CMT.cmts.addCMT')
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
                            @if (count($cmts) > 0)
                                @foreach ($cmts as $cmt)
                                    <tr>
                                        <th scope="row">{{$cmt->code_cmt}}</td>
                                        <td>{{$cmt->libellé_cmt}}</td>
                                        <td>{{$report_pos[$cmt->id]['cas_positifs'] ?? 0}}</td>
                                        <td>{{$report_deces[$cmt->id]['cas_deces'] ?? 0}}</td>
                                        @if (auth()->user()->roles()->where('role','admin')->exists())
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editCMTModal{{$cmt->id}}" class="text-orange me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{route('CMT.cmts.destroy', $cmt->id)}}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-no-btn"><a class=" text-orange ms-1" href=""><i class="fa-solid fa-trash"></i></a></button>
                                            </form>
                                        </td>
                                        @include('CMT.cmts.editCMT')
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Pas de CMTs trouvées!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pagination justify-content-end mb-0">{{$cmts->links()}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
