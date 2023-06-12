<div class="modal fade" id="editProfileImageModal{{$user->id}}" tabindex="-1" aria-labelledby="editProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editProfileImageModalLabel">Modifier votre <strong class="text-orange">photo de profile</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4">
                <form action="{{ route('user.profile.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Photo de profile :</label>
                        <input type="file" class="form-control form-control-custom" id="image" name="image" accept="image/png , image/jpeg" placeholder="wtv">
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
