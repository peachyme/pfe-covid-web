<div class="modal fade" id="generateReportingModal" tabindex="-1" aria-labelledby="generateReportingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="generateReportingModalLabel">Choisir les critère du Reporting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{route('HSE.CMTs.covid.reporting')}}" method="GET">
                    <div class="mb-2">
                        <label for="week" class="form-label fw-bold">Reporting Hebdomadaire :</label>
                        <input type="text" min="2020-W09" max="{{$current_year}}-W{{$current_week}}" class="form-control form-control-custom" id="week" name="week" placeholder="Hebdomadaire" onfocus="(this.type='week')" onblur="(this.type='text')">
                    </div>
                    <div class="mb-2">
                        <label for="month" class="form-label fw-bold">Reporting Mensuel :</label>
                        <input type="text" min="2020-03" max="{{$current_month}}" class="form-control form-control-custom" id="month" name="month" placeholder="Mensuel" onfocus="(this.type='month')" onblur="(this.type='text')">
                    </div>
                    <div class="mb-2">
                        <label for="year" class="form-label fw-bold">Reporting Annuel :</label>
                        <input type="number" min="2020" max="{{$current_year}}" class="form-control form-control-custom" id="year" name="year" placeholder="Annuel">
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3"><i class="fa-solid fa-file-pdf me-2"></i>Générer Reporting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
