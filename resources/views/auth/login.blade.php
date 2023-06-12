@extends('layouts.app')

@section('content')
<style>
    body
    {
        background-image: url('/images/2.jpg');
    }
</style>
<div class="container pt-5 px-5">
    <div class="row justify-content-center mt-5">
            <div class="card col-md-5 col-sm-4 custom-card">
                <div class="card-body">
                    <div class="col-3 my-4 mx-auto">
                        <img src="/images/logoo.png" alt="wtv" class="img-fluid">
                    </div>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="px-4 input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input id="email" type="email" class="form-control custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-2">
                            <div class="px-4 input-group">
                                <span class="input-group-text custom-form-control" id="basic-addon1">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="mot de passe">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4 ms-3">
                                <div class="form-check">
                                    <input class="form-check-input " type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label text-black" for="remember">
                                        {{ __('Se souvenir de moi') }}
                                    </label>
                                </div>
                        </div>

                        <div class="row mb-4 px-4">
                                <button type="submit" class="btn btn-dark">
                                    {{ __('Login') }}
                                </button>
                        </div>
                        <div class="text-center">
                                        @if (Route::has('password.request'))
                                            <a class="text-black" href="{{ route('password.request') }}">
                                                {{ __('Mot de passe oublié?') }}
                                            </a>
                                         @endif
                        </div>
                    </form>
                </div>
            </div>
            {{-- <div class="text-center mt-2">
                <a class="btn-link text-light" href="{{ route('register') }}">
                    {{ __('Créer compte') }}
                </a>
            </div> --}}
    </div>
</div>
@endsection
