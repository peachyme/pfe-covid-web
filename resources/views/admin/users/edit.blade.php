@extends('layouts.app')
@include('partials.navbar')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-orange">Modifier les rôles de <strong>{{$user->nom}} {{$user->prenom}}</strong></div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @foreach ($roles as $role)
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                id="{{ $role->id }}" @foreach ($user->roles as $userRole) @if ($userRole->id === $role->id) checked @endif @endforeach>
                                <label class="form-check-label" for="{{ $role->id }}">{{ $role->role }}</label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-dark mt-3">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
