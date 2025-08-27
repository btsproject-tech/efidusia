@if (isset($akses->invoice_report))
    @if ($akses->invoice_report->view == 1)
        <input type="hidden" id="update" value="{{ $akses->invoice_report->update }}">
        <input type="hidden" id="delete" value="{{ $akses->invoice_report->delete }}">
        <button type="button" id="confirm-delete-btn" class="" style="display: none;" data-bs-toggle="modal"
            data-bs-target="#konfirmasi-delete"></button>
        <div id="content-confirm-delete"></div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-rep-plugin">
                            <div class="table-wrapper">

                                <ul class="nav nav-tabs nav-tabs-custom mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link fw-bold p-3 active" href="#" id="nav-report-sales1" data-bs-toggle="pill" data-bs-target="#report-sales1" type="button" role="tab" aria-controls="nav-report-sales1" aria-selected="true">Report Invoice</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="report-sales1">
                                        @include('web.invoicereport.report1')
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    @else
        @include('web.alert.message')
    @endif
@else
    @include('web.alert.message')
@endif
