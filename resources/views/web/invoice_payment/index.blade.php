@if (isset($akses->invoice_payment))
    @if ($akses->invoice_payment->view == 1)
        <input type="hidden" id="update" value="{{ $akses->invoice_payment->update }}">
        <input type="hidden" id="delete" value="{{ $akses->invoice_payment->delete }}">
        <input type="hidden" id="print" value="{{ $akses->invoice_payment->print }}">
        <button type="button" id="confirm-delete-btn" class="" style="display: none;" data-bs-toggle="modal"
            data-bs-target="#konfirmasi-delete"></button>
        <button type="button" id="btn-show-data" class="" style="display: none;" data-bs-toggle="modal"
            data-bs-target="#data-modal-view-data"></button>
        <div id="content-modal-form"></div>
        <div id="content-confirm-delete"></div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-rep-plugin">
                            <div class="table-wrapper">
                                <div class="btn-toolbar">
                                    @if ($akses->invoice_payment->insert == 1)
                                        <div class="btn-group dropdown-btn-group pull-right"><button type="button"
                                                class="btn btn-success" onclick="InvoicePayment.add(this, event)"><i
                                                    class="mdi mdi-plus me-1"></i>Tambah
                                                Data</button>
                                        </div>
                                    @endif
                                </div>
                                <br>
                                <br>

                                <ul class="nav nav-tabs nav-tabs-custom mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link fw-bold p-3 active" href="#">Daftar
                                            {{ $title }}</a>
                                    </li>
                                </ul>

                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="mb-4">
                                            <label>Filter Date</label>

                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container='#datepicker6'>
                                                <input type="text" class="form-control" name="start"
                                                    placeholder="Start Date" id="tgl_awal" />
                                                <input type="text" class="form-control" name="end"
                                                    placeholder="End Date" id="tgl_akhir" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label style="opacity: 0;">Filter Date</label>
                                        <div class="mb-4 d-grid gap-2">
                                            <button class="btn btn-block btn-warning"
                                                onclick="InvoicePayment.getData(this)">Filter</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                                    <table id="table-data"
                                        class="table table-centered datatable dt-responsive nowrap dataTable no-footer dtr-inline collapsed"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>No Payment</th>
                                                <th>No Invoice</th>
                                                <th>Date Invoice</th>
                                                <th>Date Payment</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" colspan="8">Tidak ada data ditemukan</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
