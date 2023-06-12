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
                <div class="card-header text-orange">{{ __('Vérifier votre adresse email') }}</div>

                <div class="card-body mt-2">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nouveau lien de vérification à été envoyé à votre adresse') }}
                        </div>
                    @endif

                    <div class="text-light">
                        {{ __('Avant de continuer, veuiller consulter votre boite de récéption pour vérifier votre email.') }}
                        {{ __('Si vous n\'avez pas reçu le lien de vérification') }},
                    </div>
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link link-orange p-0 m-0 align-baseline">{{ __('cliquer ici pour le renvoyer.') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
