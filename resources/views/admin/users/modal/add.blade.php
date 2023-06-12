<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="addUserModalLabel">Créer nouvel utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-3">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-2">
                        <label for="matricule" class="form-label fw-bold">Matricule :</label>
                        <input type="text" class="form-control form-control-custom" id="matricule" name="matricule">
                        @error('matricule')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="nom" class="form-label fw-bold">Nom :</label>
                            <input type="text" class="form-control form-control-custom" id="nom" name="nom">
                            @error('nom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="prenom" class="form-label fw-bold">Prénom :</label>
                            <input type="text" class="form-control form-control-custom" id="prenom" name="prenom">
                            @error('prenom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                       </div>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label fw-bold">Email :</label>
                        <input type="email" class="form-control form-control-custom" id="email" name="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="password" class="form-label fw-bold">Mot de passe :</label>
                            <input id="password" type="password" class="form-control form-control-custom" @error('password') is-invalid @enderror name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="password-confirm" class="form-label fw-bold">Confirmer mot de passe :</label>
                            <input id="password-confirm" type="password" class="form-control form-control-custom" name="password_confirmation" required autocomplete="new-password">
                       </div>
                    </div>

                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
