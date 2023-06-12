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
        <div class="card custom-card my-auto">
                <div class="card-header text-orange">{{ __('Rénitialiser mot de passe') }}</div>

                <div class="card-body mt-2">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-4 px-3">
                            <div class="input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="d-inline medium material-icons">email</i>
                                </span>
                                <input id="email" type="email" class="form-control custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" placeholder="email" autofocus>

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
                                    <i class="d-inline medium material-icons">lock</i>
                                </span>
                                <input id="password" type="password" class="form-control custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="mot de passe">

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
                                    <i class="d-inline medium material-icons">lock</i>
                                </span>
                                <input id="password-confirm" type="password" class="form-control custom-form-control" name="password_confirmation" required autocomplete="new-password" placeholder="confirmer mot de passe">
                            </div>
                        </div>

                        <div class="row mb-2 px-4">
                            <button type="submit" class="btn btn-dark">
                                {{ __('Rénitialiser mot de passe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
