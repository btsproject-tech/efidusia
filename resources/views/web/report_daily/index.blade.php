@if (isset($akses->daily_report))
    @if ($akses->daily_report->view == 1)
        <input type="hidden" id="update" value="{{ $akses->daily_report->update }}">
        <input type="hidden" id="delete" value="{{ $akses->daily_report->delete }}">
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
                                        <a class="nav-link fw-bold p-3 active" href="#" id="nav-report-sales1"
                                            data-bs-toggle="pill" data-bs-target="#report-sales1" type="button"
                                            role="tab" aria-controls="nav-report-sales1" aria-selected="true">Report
                                            Daily</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="report-sales1">
                                        <div class="row">
                                            <div class="col-md-7">
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
                                                        onclick="DailyReport.getData(this)">Filter</button>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label style="opacity: 0;">Export Data</label>
                                                <div class="d-grid gap-1">
                                                    <button class="btn btn-block btn-primary"
                                                        onclick="DailyReport.ezportData(this)"><i
                                                            class="far fa-file-excel"></i> Export Data</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive mb-0 fixed-solution"
                                            data-pattern="priority-columns">
                                            <table id="table-data"
                                                class="table table-centered datatable dt-responsive nowrap dataTable no-footer dtr-inline collapsed"
                                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>No Contract</th>
                                                        <th>No Minuta</th>
                                                        <th>Debitur</th>
                                                        <th>Alamat</th>
                                                        <th>Price</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Last Step</th>
                                                        {{-- <th>Action</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center" colspan="8">Tidak ada data ditemukan
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
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
