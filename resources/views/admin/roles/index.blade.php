@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-4 bg-custom-dark">
            <h4>Liste des rôles</h4>
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
            <div class="card card-custom">
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                           <tr>
                                <th class="text-center" scope="col">id</th>
                                <th scope="col">Rôle</th>
                                <th class="text-center" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="">
                                    <td class="col-1 p-1">
                                        <input type="text" class="form-control  form-control-custom" placeholder="id...">
                                    </td>
                                    <td class="p-1">
                                        <input type="text" class="form-control form-control-custom" placeholder="rôle...">
                                    </td>
                                    <td class="col-1 text-center">
                                        <button type="submit" class="btn-no-btn"><a href="" class="text-orange"><i class="fa-solid fa-magnifying-glass mt-2"></i></a></button>
                                    </td>
                                </form>
                            </tr>
                            @foreach ($roles as $role)
                                <tr>
                                    <th class="text-center" scope="row">{{$role->id}}</th>
                                    <td>{{$role->role}}</td>
                                    <td class="text-center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editRoleModal{{$role->id}}" class="text-orange me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-no-btn"><a class=" text-orange ms-1" href=""><i class="fa-solid fa-trash"></i></a></button>
                                        </form>
                                    </td>
                                    @include('admin.roles.modal.edit')
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
