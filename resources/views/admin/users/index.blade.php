@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-4 bg-custom-dark">
            <h4>Liste des utilisateurs</h4>
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
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                <a href="{{ route('register') }}" class="btn btn-dark-no-radius">Créer nouvel utilisateur</a>
            </div>
            @include('admin.users.modal.add')
            <div class="card card-custom">
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                           <tr>
                                <th scope="col">Matricule</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Rôles</th>
                                <th scope="col">Région/CMT</th>
                                <th class="text-center" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="{{ route('users.search') }}" method="GET">
                                    <td class="col-15 p-1">
                                        <input type="text" name="matricule" id="matricule" class="form-control  form-control-custom" placeholder="mat...">
                                    </td>
                                    <td class="col-1 p-1">
                                        <input type="text" name="nom" id="nom" class="form-control form-control-custom" placeholder="nom...">
                                    </td>
                                    <td class="col-1 p-1">
                                        <input type="text" name="prenom" id="prenom" class="form-control form-control-custom" placeholder="prénom...">
                                    </td>
                                    <td class="p-1">
                                        <input type="text" name="email" id="email" class="form-control form-control-custom" placeholder="email...">
                                    </td>
                                    <td class="p-1">
                                        <select name="role" class="form-select form-control form-control-custom" aria-label="Default select example">
                                            <option value="" selected disabled hidden>Rôle...</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1 col-1">
                                        <input type="text" name="region_cmt" id="region_cmt" class="form-control form-control-custom" placeholder="region_cmt...">
                                    </td>
                                    <td class="col-1 text-center">
                                        <button type="submit" class="btn-no-btn text-orange"><i class="fa-solid fa-magnifying-glass mt-2"></i></button>
                                    </td>
                                </form>
                            </tr>
                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <th class="text-center" scope="row">{{$user->matricule}}</td>
                                        <td>{{$user->nom}}</td>
                                        <td>{{$user->prenom}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{implode(' ,' ,$user->roles()->get()->pluck('role')->toArray())}}</td>
                                        <td>{{$user->region_cmt}}</td>
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editUserModal{{$user->id}}" class="text-orange me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-no-btn"><a class=" text-orange ms-1" href=""><i class="fa fa-user-slash"></i></a></button>
                                            </form>
                                        </td>
                                        @include('admin.users.modal.edit')
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">Pas d'utilisateurs trouvées!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pagination justify-content-end mb-0">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
