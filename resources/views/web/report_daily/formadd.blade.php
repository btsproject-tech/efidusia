<input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">
<button type="button" id="btn-show-modal" class="" style="display: none;" data-bs-toggle="modal"
  data-bs-target="#data-modal-port"></button>
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
        <div class="col-xl-2 col-sm-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-customer-tab" data-bs-toggle="pill" href="#v-pills-customer"
                    role="tab" aria-controls="v-pills-customer" aria-selected="true">
                    <i class="bx bxs-truck d-block check-nav-icon mt-4 mb-2"></i>
                    <p class="fw-bold mb-4">Customer Data</p>
                </a>
                <a class="nav-link" id="v-pills-luminiship-tab" data-bs-toggle="pill" href="#v-pills-luminiship"
                    role="tab" aria-controls="v-pills-luminiship" aria-selected="false">
                    <i class="bx bx-user-circle d-block check-nav-icon mt-4 mb-2"></i>
                    <p class="fw-bold mb-4">Lumiship Data</p>
                </a>
                <a class="nav-link" id="v-pills-addinfo-tab" data-bs-toggle="pill" href="#v-pills-addinfo" role="tab"
                    aria-controls="v-pills-addinfo" aria-selected="false">
                    <i class="bx bx-info-circle d-block check-nav-icon mt-4 mb-2"></i>
                    <p class="fw-bold mb-4">Additional Information</p>
                </a>
                <a class="nav-link" id="v-pills-tablerate-tab" data-bs-toggle="pill" href="#v-pills-tablerate" role="tab"
                    aria-controls="v-pills-tablerate" aria-selected="false">
                    <i class="bx bx-table d-block check-nav-icon mt-4 mb-2"></i>
                    <p class="fw-bold mb-4">Table Rate</p>
                </a>
            </div>
        </div>
        <div class="col-xl-10 col-sm-9">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-customer" role="tabpanel"
                            aria-labelledby="v-pills-customer-tab">
                            @include('web.quotation.form.customerform')
                        </div>
                        <div class="tab-pane fade" id="v-pills-luminiship" role="tabpanel"
                            aria-labelledby="v-pills-luminiship-tab">
                            @include('web.quotation.form.luminishipdata')
                        </div>
                        <div class="tab-pane fade" id="v-pills-addinfo" role="tabpanel"
                            aria-labelledby="v-pills-addinfo-tab">
                            @include('web.quotation.form.additionalinfo')
                        </div>
                        <div class="tab-pane fade" id="v-pills-tablerate" role="tabpanel"
                            aria-labelledby="v-pills-tablerate-tab">
                            @include('web.quotation.form.tablerate')
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-6">
                    <a href="" onclick="Quotation.cancel(this, event)" class="btn text-muted d-none d-sm-inline-block btn-link">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali </a>
                </div> <!-- end col -->
                <div class="col-sm-6">
                    <div class="text-end">
                        <a href="" onclick="Quotation.submit(this, event)" class="btn btn-success">
                            <i class="mdi mdi-truck-fast me-1"></i> Submit </a>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
</div>
<!-- end row -->
