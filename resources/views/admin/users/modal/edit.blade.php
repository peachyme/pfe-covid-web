<div class="modal fade" id="editUserModal{{$user->id}}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="editUserModalLabel">Editer l'utilisateur <strong>{{$user->nom}} {{$user->prenom}}</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row mb-2">
                        <div class="col">
                            <label for="nom" class="form-label fw-bold">Nom :</label>
                            <input type="text" class="form-control form-control-custom" id="nom" name="nom" value="{{$user->nom}}">
                        </div>
                        <div class="col">
                            <label for="prenom" class="form-label fw-bold">Prénom :</label>
                            <input type="text" class="form-control form-control-custom" id="prenom" name="prenom" value="{{$user->prenom}}">
                       </div>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label fw-bold">Email :</label>
                        <input type="text" class="form-control form-control-custom" id="email" name="email" value="{{$user->email}}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Rôles :</label>
                        <div class="p-3 custom-roles-div">
                            @foreach ($roles as $role)
                                <div class="form-group form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        id="{{ $role->id }}" @foreach ($user->roles as $userRole) @if ($userRole->id === $role->id) checked @endif @endforeach>
                                    <label class="form-check-label" for="{{ $role->id }}">{{ $role->role }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Région/CMT :</label>
                        <select name="region_cmt" class="form-select form-control form-control-custom" aria-label="Default select example">
                            @foreach ($cmts as $cmt)
                                <option value="{{ $cmt->code_cmt }}" @if ($user->region_cmt == $cmt->code_cmt) selected @endif>{{ $cmt->code_cmt }}</option>
                            @endforeach
                            @foreach ($regions as $region)
                                <option value="{{ $region->code_region }}" @if ($user->region_cmt === $region->code_region) selected @endif>{{ $region->code_region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
