<?php
use Illuminate\Support\Facades\Hash;
?>
<div class="modal fade" id="editPasswordModal{{$user->id}}" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editPasswordModalLabel">Modifier votre <strong class="text-orange">Mot de passe</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4">
                <form action="{{ route('user.profile.update', $user) }}" method="POST" onsubmit="return confirmPasswords()" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="mb-3 has-validation">
                        <label for="old_password" class="form-label fw-bold">Mot de passe courrant :</label>
                        <input type="password" class="form-control form-control-custom" id="old_password" name="old_password" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                        <span id="checkPassword" class="text-danger small"></span>

                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-bold">Nouveau Mot de passe :</label>
                        <input type="password" class="form-control form-control-custom" id="new_password" name="new_password" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label fw-bold">Confirmer Mot de passe :</label>
                        <input type="password" class="form-control form-control-custom mb-1" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">
                            Veuillez remplir ce champ.
                        </div>

                        <span id="confirmPasswords" class="text-danger small"></span>
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3" id="liveAlertBtn">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
