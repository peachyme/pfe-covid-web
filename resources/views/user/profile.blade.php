@extends('layouts.app')
@include('partials.navbar')
@section('content')
<div class="main-container d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="text-gray ps-5 pt-3 pb-2 mb-4 bg-custom-dark">
            <h4>Mon profile</h4>
        </div>
        <div class="justify-content-center mx-4">
            @if(session()->has('message'))
                <div class="alert alert-danger">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="card card-custom">
            <div class="row g-0">
                <div class="col-md-4 text-center py-4 bg-profile-custom">
                    <div class="profile-image-container">
                        <img src="{{ Storage::url($user->profile_image) }}" class="img-fluid p-2 mt-5 profile-pic rounded-circle" width="200px" height="200px" alt="...">
                        <a href="" data-bs-toggle="modal" data-bs-target="#editProfileImageModal{{$user->id}}" class="text-darc"><i class="icon fa-solid fs-5 fa-pen p-2"></i></a>
                     </div>
                    <p class="fw-bold text-dark-gray mt-4 mb-4 pb-4">{{ $user->nom }} {{ $user->prenom }}</p>
                    <a href="" data-bs-toggle="modal" data-bs-target="#editPasswordModal{{$user->id}}" class="text-orange text-decoration-none mt-5">Changer le mot de passe</a>
                </div>
                @include('user.modals.editProfileImage')
                @include('user.modals.editPassword')
                <div class="col-md-8">
                    <div class="card-body p-5 mt-3">
                        <div class="row mb-4">
                            <div class="col fw-bold">
                                Matricule :
                            </div>
                            <div class="col">
                                {{$user->matricule}}
                            </div>
                            <div class="col-2"></div>
                        </div>
                        <hr>
                        <div class="row mb-4">
                            <div class="col fw-bold">
                                Nom :
                            </div>
                            <div class="col">
                                {{$user->nom}}
                            </div>
                            <div class="col-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editNomModal{{$user->id}}" class="text-orange"><i class="fa-solid fa-pen-to-square fs-5"></i></a>
                            </div>
                        </div>
                        @include('user.modals.editNom')
                        <hr>
                        <div class="row mb-4 mt-4">
                            <div class="col fw-bold">
                                Prénom :
                            </div>
                            <div class="col">
                                {{$user->prenom}}
                            </div>
                            <div class="col-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editPrenomModal{{$user->id}}" class="text-orange"><i class="fa-solid fa-pen-to-square fs-5"></i></a>
                            </div>
                        </div>
                        @include('user.modals.editPrenom')
                        <hr>
                        <div class="row mb-4 mt-4">
                            <div class="col fw-bold">
                                Email :
                            </div>
                            <div class="col">
                                {{$user->email}}
                            </div>
                            <div class="col-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editEmailModal{{$user->id}}" class="text-orange"><i class="fa-solid fa-pen-to-square fs-5"></i></a>
                            </div>
                        </div>
                        @include('user.modals.editEmail')
                        <hr>
                        <div class="row mb-4 mt-4">
                            <div class="col fw-bold">Rôle :</div>
                            <div class="col">{{ $user->roles()->get()->pluck('role')->toArray()[0] }}</div>
                            <div class="col-2"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col fw-bold">Région/CMT :</div>
                            <div class="col">{{ $user->region_cmt }}</div>
                            <div class="col-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
