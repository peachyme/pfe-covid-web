@extends('layouts.app')

@section('content')
<style>
    body
    {
        background-image: url('/images/2.jpg');
    }
</style>
<div class="container pt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card custom-card">
                <div class="card-header text-orange">{{ __('Créer un compte utilisateur') }}</div>

                <div class="card-body mt-2">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-4 px-3">
                            <div class="input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                <i class="fa-solid fa-id-card fs-5"></i>
                                </span>
                                <input id="matricule" type="text" class="form-control custom-form-control @error('matricule') is-invalid @enderror" name="matricule" value="{{ old('matricule') }}" required autocomplete="matricule" placeholder="matricule" autofocus>

                                @error('matricule')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4 px-3">
                            <div class="input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="fa-solid fa-user fs-5"></i>
                                </span>
                                <input id="nom" type="text" class="form-control custom-form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required autocomplete="nom" placeholder="nom" autofocus>

                                @error('nom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4 px-3">
                            <div class="input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="fa-solid fa-user fs-5"></i>
                                </span>
                                <input id="prenom" type="text" class="form-control custom-form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom') }}" required autocomplete="prenom" placeholder="prénom" autofocus>

                                @error('prenom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4 px-3">
                            <div class="input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="fa-solid fa-envelope fs-5"></i>
                                </span>
                                <input id="email" type="email" class="form-control custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="email" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4 px-3">
                            <div class="input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="fa-solid fa-lock fs-5"></i>

                                </span>
                                <input id="password" type="password" class="form-control custom-form-control @error('password') is-invalid @enderror" name="password" required placeholder="mot de passe" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4 px-3">
                            <div class="input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="fa-solid fa-lock fs-5"></i>
                                </span>
                                <input id="password-confirm" type="password" class="form-control custom-form-control" name="password_confirmation" required placeholder="confirmer mot de passe" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-2 px-4">
                                <button type="submit" class="btn btn-dark">
                                    {{ __('Créer') }}
                                </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
