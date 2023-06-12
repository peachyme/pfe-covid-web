<div class="modal fade" id="generateReportingModal" tabindex="-1" aria-labelledby="generateReportingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-content-bg">
            <div class="modal-header bg-custom-dark">
                <h5 class="modal-title text-gray" id="generateReportingModalLabel">Choisir les critère du Reporting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-4 pt-1">
                <form action="{{ route('HSE.DRs.covid.reporting') }}" method="GET">
                    <div class="mb-2">
                        <label for="date" class="form-label fw-bold">Reporting Quotidien :</label>
                        <input type="text" min="2020-03-01" max="{{ $date }}"
                            class="form-control form-control-custom" id="date" name="date"
                            placeholder="Quotidien" onfocus="(this.type='date')" onblur="(this.type='text')">
                    </div>
                    <div class="mb-2">
                        <label for="week" class="form-label fw-bold">Reporting Hebdomadaire :</label>
                        <input type="text" min="2020-W09" max="{{ $current_year }}-W{{ $current_week }}"
                            class="form-control form-control-custom" id="week" name="week"
                            placeholder="Hebdomadaire" onfocus="(this.type='week')" onblur="(this.type='text')">
                    </div>
                    <div class="mb-2">
                        <label for="month" class="form-label fw-bold">Reporting Mensuel :</label>
                        <input type="text" min="2020-03" max="{{ $current_month }}"
                            class="form-control form-control-custom" id="month" name="month" placeholder="Mensuel"
                            onfocus="(this.type='month')" onblur="(this.type='text')">
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label fw-bold">Reporting Annuel :</label>
                        <input type="number" min="2020" max="{{ $current_year }}"
                            class="form-control form-control-custom" id="year" name="year" placeholder="Annuel">
                    </div>
                    <div class="hidden mb-3" id="observations">
                        <div class="mb-3">
                            <label for="observation_siege" class="form-label fw-bold">SIEGE :</label>
                            <textarea class="form-control form-control-custom" id="observation_siege" name="observation_siege" rows="2"
                                    placeholder="observations..." style="resize: none;"></textarea>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="observation_hmd" class="form-label fw-bold">HMD :</label>
                                <textarea class="form-control form-control-custom" id="observation_hmd" name="observation_hmd" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_rns" class="form-label fw-bold">RNS :</label>
                                <textarea class="form-control form-control-custom" id="observation_rns" name="observation_rns" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_stah" class="form-label fw-bold">STAH :</label>
                                <textarea class="form-control form-control-custom" id="observation_stah" name="observation_stah" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_ohanet" class="form-label fw-bold">OHANET :</label>
                                <textarea class="form-control form-control-custom" id="observation_ohanet" name="observation_ohanet" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_hbk" class="form-label fw-bold">HBK :</label>
                                <textarea class="form-control form-control-custom" id="observation_hbk" name="observation_hbk" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="observation_hrm" class="form-label fw-bold">HRM :</label>
                                <textarea class="form-control form-control-custom" id="observation_hrm" name="observation_hrm" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_inas" class="form-label fw-bold">INAS :</label>
                                <textarea class="form-control form-control-custom" id="observation_inas" name="observation_inas" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_gtl" class="form-label fw-bold">GTL :</label>
                                <textarea class="form-control form-control-custom" id="observation_gtl" name="observation_gtl" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_tft" class="form-label fw-bold">TFT :</label>
                                <textarea class="form-control form-control-custom" id="observation_tft" name="observation_tft" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                            <div class="col">
                                <label for="observation_reb" class="form-label fw-bold">REB :</label>
                                <textarea class="form-control form-control-custom" id="observation_reb" name="observation_reb" rows="4"
                                    placeholder="observations..." style="resize: none;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row px-2 mt-0">
                        <a onclick="displayObservations();" class="btn btn-dark-no-radius"><i
                                class="fa-solid fa-file-pdf me-2"></i>Ajouter des observations</a>
                    </div>
                    <div class="row px-2 mt-0">
                        <button type="submit" class="btn btn-dark-no-radius mt-3"><i
                                class="fa-solid fa-file-pdf me-2"></i>Générer Reporting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
