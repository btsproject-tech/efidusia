<input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">
<button type="button" id="btn-show-modal" class="" style="display: none;" data-bs-toggle="modal"
    data-bs-target="#data-modal-bacth"></button>
<button type="button" id="btn-show-modal-vendor" class="" style="display: none;" data-bs-toggle="modal"
    data-bs-target="#data-modal-vendor"></button>
<div id="content-modal-form"></div>

<div class="checkout-tabs">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom mb-4" id="myTab" role="tablist">
                        {{-- <li class="nav-item">
                            <button
                                class="nav-link fw-bold p-3 {{ isset($data->tipe_invoice) ? ($data->tipe_invoice == 'NOTARIS' ? 'active' : '') : 'active' }}"
                                id="home-tab" data-bs-toggle="tab" data-bs-target="#notaris" type="button"
                                role="tab" aria-controls="notaris" aria-selected="true">{{ $titleNotaris }}</button>
                        </li> --}}
                        <li class="nav-item">
                            <button
                                class="nav-link fw-bold p-3 active"
                                id="profile-tab" data-bs-toggle="tab" data-bs-target="#finance" type="button"
                                role="tab" aria-controls="finance"
                                aria-selected="false">{{ $titleFinance }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        {{-- <div class="tab-pane notaris fade {{ isset($data->tipe_invoice) ? ($data->tipe_invoice == 'NOTARIS' ? 'show active' : '') : 'show active' }}"
            id="notaris" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div class="card">
                <div class="card-body">
                    @include('web.invoice.form.invoiceformnotaris')
                </div>
            </div>
        </div> --}}

        <div class="tab-pane fade finance show active"
            id="finance" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div class="card">
                <div class="card-body">
                    @include('web.invoice.form.invoiceformfinance')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
