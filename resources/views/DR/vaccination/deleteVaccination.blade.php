<div class="modal fade" id="deleteVaccinationModal{{$vaccination->id}}" tabindex="-1" aria-labelledby="deleteVaccinationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered text-center" style="width: 500px">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="deleteVaccinationModalLabel">Supprimer la vaccination</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-0 pb-3 pt-4">
                <div>
                    <h5 class="text-orange-no-hover">Etes vous sure de vouloir supprimer cette vaccination ?</h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('DR.vaccinations.destroy', $vaccination) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-dark-no-radius">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
