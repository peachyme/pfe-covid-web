<div class="modal fade" id="editPrenomModal{{$user->id}}" tabindex="-1" aria-labelledby="editPrenomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editPrenomModalLabel">Modifier votre <strong class="text-orange">prénom</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4">
                <form action="{{ route('user.profile.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="prenom" class="form-label fw-bold">Prénom :</label>
                        <input type="text" class="form-control form-control-custom" id="prenom" name="prenom" value="{{$user->prenom}}">
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
