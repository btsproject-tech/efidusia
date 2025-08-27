<input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">
<button type="button" id="btn-show-modal" class="" style="display: none;" data-bs-toggle="modal"
    data-bs-target="#data-modal-invoice"></button>
<button type="button" id="btn-show-data" class="" style="display: none;" data-bs-toggle="modal"
    data-bs-target="#data-modal-view-data"></button>
<div id="content-modal-form"></div>

<div class="checkout-tabs">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom mb-4">
                        <li class="nav-item">
                            <a class="nav-link fw-bold p-3 active" href="#">
                                {{ $title }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row content-save">
        {{-- <div class="col-xl-2 col-sm-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-detailcost-tab" data-bs-toggle="pill" href="#v-pills-detailcost"
                    role="tab" aria-controls="v-pills-detailcost" aria-selected="true">
                    <i class="bx bx-wallet-alt d-block check-nav-icon mt-4 mb-2"></i>
                    <p class="fw-bold mb-4">Detail of Cost</p>
                </a>
            </div>
        </div> --}}
        <div class="col-xl-12 col-sm-9">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-detailcost" role="tabpanel"
                            aria-labelledby="v-pills-detailcost-tab">
                            @include('web.invoice_payment.form.invoiceform')
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-6">
                    <a href="" onclick="InvoicePayment.cancel(this, event)"
                        class="btn text-muted d-none d-sm-inline-block btn-link">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali </a>
                </div> <!-- end col -->
                <div class="col-sm-6">
                    <div class="text-end">
                        <a href="" onclick="InvoicePayment.submit(this, event)" class="btn btn-success">
                            <i class="mdi mdi-truck-fast me-1"></i> Submit </a>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
</div>
<!-- end row -->
