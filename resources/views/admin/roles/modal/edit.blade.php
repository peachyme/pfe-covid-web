<div class="modal fade" id="editRoleModal{{$role->id}}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editUserModalLabel">Modifier le rôle <strong>{{$role->role}}</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4">
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="role" class="form-label fw-bold">Rôle :</label>
                        <input type="text" class="form-control form-control-custom" id="role" name="role" value="{{$role->role}}">
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
